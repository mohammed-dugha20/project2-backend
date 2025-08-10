<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerFinishingRequestController extends Controller
{
    /**
     * Display a listing of the authenticated user's finishing requests.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $requests = \App\Models\FinishingRequest::with(['company', 'status', 'location', 'images', 'response'])
            ->where('customer_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json($requests);
    }

    /**
     * Display a specific finishing request and its status.
     */
    public function show($id)
    {
        $user = request()->user();
        $request = \App\Models\FinishingRequest::with(['company', 'status', 'location', 'images', 'response'])
            ->where('customer_id', $user->id)
            ->findOrFail($id);

        return response()->json($request);
    }

    /**
     * Store a new finishing request for the authenticated user.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'company_id' => 'required|exists:finishing_companies,id',
            'service_type' => 'required|string|max:100',
            'location_id' => 'required|exists:locations,id',
            'area' => 'required|numeric|min:0',
            'rooms' => 'required|integer|min:1',
            'floor' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'geo_lat' => 'nullable|numeric',
            'geo_lng' => 'nullable|numeric',
            'images' => 'array',
            'images.*' => 'file|image|max:4096'
        ]);

        // Set status_id to "pending" for finishing_request, or fallback to first finishing_request status
        $pendingStatus = \App\Models\Status::where([
            ['status_name', '=', 'pending'],
            ['entity_type', '=', 'finishing_request']
        ])->first();

        $statusId = $pendingStatus
            ? $pendingStatus->id
            : \App\Models\Status::where('entity_type', 'finishing_request')->first()->id;

        $finishingRequest = \App\Models\FinishingRequest::create([
            'customer_id' => $user->id,
            'company_id' => $validated['company_id'],
            'location_id' => $validated['location_id'],
            'service_type' => $validated['service_type'],
            'area' => $validated['area'],
            'rooms' => $validated['rooms'],
            'floor' => $validated['floor'],
            'description' => $validated['description'] ?? null,
            'status_id' => $statusId,
            // Optionally store geo location in a separate field or as part of location
        ]);

        // Handle images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/finishing/requests/images');
                $finishingRequest->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Finishing request created successfully.',
            'request_id' => $finishingRequest->id
        ], 201);
    }
}
