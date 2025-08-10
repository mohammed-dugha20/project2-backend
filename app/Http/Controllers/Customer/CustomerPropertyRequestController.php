<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerPropertyRequestController extends Controller
{
    /**
     * Display a listing of requests received for a specific property owned by the authenticated user.
     */
    public function received(Request $request, $propertyId)
    {
        $user = $request->user();
        $property = \App\Models\Property::where('user_id', $user->id)->findOrFail($propertyId);

        $requests = \App\Models\PropertyRequest::with('customer')
            ->where('property_id', $property->id)
            ->latest()
            ->get();

        // Return with customer details
        return response()->json([
            'property_id' => $property->id,
            'requests' => $requests
        ]);
    }

    /**
     * Respond to a request on a property (reject or negotiate).
     */
    public function respond(Request $request, $propertyId, $requestId)
    {
        $user = $request->user();
        $property = \App\Models\Property::where('user_id', $user->id)->findOrFail($propertyId);

        $propertyRequest = \App\Models\PropertyRequest::where('property_id', $property->id)
            ->findOrFail($requestId);

        $validated = $request->validate([
            'status' => 'required|string|in:rejected,negotiation',
            'message' => 'nullable|string'
        ]);

        // You may want to map status to a status_id if using a statuses table
        // For now, store status as a string in a custom field or use status_id if mapped
        $propertyRequest->status_id = $validated['status']; // Replace with status_id mapping if needed
        if (isset($validated['message'])) {
            $propertyRequest->response_message = $validated['message'];
        }
        $propertyRequest->save();

        return response()->json([
            'message' => 'Request response sent.',
            'request_id' => $propertyRequest->id,
            'status' => $validated['status']
        ]);
    }

    /**
     * Display a listing of requests sent by the authenticated user.
     */
    public function sent(Request $request)
    {
        $user = $request->user();
        $requests = \App\Models\PropertyRequest::with('property')
            ->where('customer_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'customer_id' => $user->id,
            'requests' => $requests
        ]);
    }

    /**
     * Update a sent request (if not finalized).
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $propertyRequest = \App\Models\PropertyRequest::where('customer_id', $user->id)->findOrFail($id);

        // Optionally, check if request is finalized (status_id logic)
        if ($propertyRequest->status_id && $propertyRequest->status_id != null) {
            // You may want to check for a specific "finalized" status
            // For now, allow update if status_id is null (pending)
            return response()->json([
                'message' => 'Cannot edit a finalized request.'
            ], 403);
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'request_type' => 'sometimes|required|string|in:buy,rent'
        ]);

        $propertyRequest->update($validated);

        return response()->json([
            'message' => 'Request updated successfully.',
            'request' => $propertyRequest
        ]);
    }

    /**
     * Remove (cancel) a sent request (if not finalized).
     */
    public function destroy($id)
    {
        $user = request()->user();
        $propertyRequest = \App\Models\PropertyRequest::where('customer_id', $user->id)->findOrFail($id);

        // Optionally, check if request is finalized (status_id logic)
        if ($propertyRequest->status_id && $propertyRequest->status_id != null) {
            return response()->json([
                'message' => 'Cannot cancel a finalized request.'
            ], 403);
        }

        $propertyRequest->delete();

        return response()->json(['message' => 'Request cancelled successfully.']);
    }

    /**
     * Store a new request for a property (public endpoint, but logic can be here).
     */
    public function store(Request $request, $propertyId)
    {
        $user = $request->user();

        $property = \App\Models\Property::findOrFail($propertyId);

        // Prevent requesting own property
        if ($property->user_id == $user->id) {
            return response()->json([
                'message' => 'You cannot send a request to your own property.'
            ], 403);
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'request_type' => 'required|string|in:buy,rent'
        ]);

        // Set status_id to "pending" for property_request, or fallback to first property_request status
        $pendingStatus = \App\Models\Status::where([
            ['status_name', '=', 'pending'],
            ['entity_type', '=', 'property_request']
        ])->first();

        $statusId = $pendingStatus
            ? $pendingStatus->id
            : \App\Models\Status::where('entity_type', 'property_request')->first()->id;

        $propertyRequest = \App\Models\PropertyRequest::create([
            'customer_id' => $user->id,
            'property_id' => $property->id,
            'status_id' => $statusId,
            'description' => $validated['description'] ?? null,
            'budget' => $validated['budget'] ?? null,
            'request_type' => $validated['request_type']
        ]);

        return response()->json([
            'message' => 'Request sent successfully.',
            'request_id' => $propertyRequest->id
        ], 201);
    }
}
