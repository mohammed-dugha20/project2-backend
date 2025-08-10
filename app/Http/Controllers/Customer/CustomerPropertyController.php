<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerPropertyController extends Controller
{
    /**
     * Display a listing of the authenticated user's properties.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $properties = \App\Models\Property::with(['images', 'location', 'status', 'office', 'attachments'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return \App\Http\Resources\Property\PropertyResource::collection($properties);
    }

    /**
     * Store a newly created property for the authenticated user.
     * Enforces a maximum of 5 properties per user per year.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Enforce 5 properties per year rule
        $propertiesThisYear = \App\Models\Property::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subYear())
            ->count();

        if ($propertiesThisYear >= 5) {
            return response()->json([
                'message' => 'You have reached the maximum of 5 properties per year.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'area' => 'required|numeric|min:0',
            'rooms' => 'required|integer|min:1',
            'legal_status' => 'required|string|max:50',
            'offer_type' => 'required|string|max:20',
            'location_id' => 'required|exists:locations,id',
            'status_id' => 'required|exists:statuses,id',
            //'real_estate_office_id' => 'required|exists:real_estate_offices,id',
            'contact_visible' => 'boolean',
            // images: array of files or URLs, optional
            'images' => 'array',
            'images.*' => 'file|image|max:2048'
        ]);

        $property = \App\Models\Property::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'price' => $validated['price'],
            'area' => $validated['area'],
            'rooms' => $validated['rooms'],
            'legal_status' => $validated['legal_status'],
            'offer_type' => $validated['offer_type'],
            'location_id' => $validated['location_id'],
            'status_id' => $validated['status_id'],

            'contact_visible' => $validated['contact_visible'] ?? true,
            'user_id' => $user->id,
        ]);

        // Handle images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/properties/images');
                $property->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        $property->load(['images', 'location', 'status', 'office', 'attachments']);

        return new \App\Http\Resources\Property\PropertyResource($property);
    }

    /**
     * Update the specified property owned by the authenticated user.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $property = \App\Models\Property::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|max:50',
            'price' => 'sometimes|required|numeric|min:0',
            'area' => 'sometimes|required|numeric|min:0',
            'rooms' => 'sometimes|required|integer|min:1',
            'legal_status' => 'sometimes|required|string|max:50',
            'offer_type' => 'sometimes|required|string|max:20',
            'location_id' => 'sometimes|required|exists:locations,id',
            'contact_visible' => 'boolean',
            'images' => 'array',
            'images.*' => 'file|image|max:2048'
        ]);

        $property->update($validated);

        // Handle images if provided (additive, does not remove old images)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/properties/images');
                $property->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        $property->load(['images', 'location', 'status', 'office', 'attachments']);

        return new \App\Http\Resources\Property\PropertyResource($property);
    }

    /**
     * Remove the specified property owned by the authenticated user.
     */
    public function destroy($id)
    {
        $user = request()->user();
        $property = \App\Models\Property::where('user_id', $user->id)->findOrFail($id);

        // Optionally, delete related images/attachments
        $property->images()->delete();
        $property->attachments()->delete();

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully.']);
    }

    /**
     * Update the contact info visibility for the specified property.
     */
    public function updateContactVisibility(Request $request, $id)
    {
        $user = $request->user();
        $property = \App\Models\Property::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'contact_visible' => 'required|boolean'
        ]);

        $property->contact_visible = $validated['contact_visible'];
        $property->save();

        return response()->json([
            'message' => 'Contact visibility updated.',
            'contact_visible' => $property->contact_visible
        ]);
    }
}
