<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyStatusRequest;
use App\Http\Requests\Property\UploadPropertyImagesRequest;
use App\Http\Requests\Property\UploadPropertyAttachmentsRequest;
use App\Http\Resources\Property\PropertyCollection;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\Property\PropertyImageResource;
use App\Http\Resources\Property\PropertyAttachmentResource;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $properties = $this->propertyService->getAllProperties($request->all());
            return response()->json(new PropertyCollection($properties));
        } catch (\Exception $e) {
            Log::error('Error fetching properties: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch properties'
            ], 500);
        }
    }

    public function store(StorePropertyRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $office = $user->realEstateOffice;

            $data = $request->validated();
            $data['real_estate_office_id'] = $office->id;
            $data['user_id'] = $user->id;
            $property = $this->propertyService->createProperty($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Property created successfully',
                'data' => [
                    'property' => new PropertyResource($property)
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating property: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create property'
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $property = $this->propertyService->getPropertyById($id);

            if (!$property) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Property not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'property' => new PropertyResource($property)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching property: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch property'
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        // Manually validate the request data, excluding files
        $validator = Validator::make($request->except(['images', 'attachments']), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:apartment,villa,land,office,commercial',
            'price' => 'sometimes|required|numeric|min:0',
            'area' => 'sometimes|required|numeric|min:0',
            'rooms' => 'sometimes|required|integer|min:0',
            'legal_status' => 'sometimes|required|in:registered,pending,customary',
            'offer_type' => 'sometimes|required|in:sale,rent',
            'status_id' => 'sometimes|required|exists:statuses,id',
            'contact_visible' => 'sometimes|required|boolean',
            'location_id' => 'sometimes|required|exists:locations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Separately validate files if they are present
        $fileValidator = Validator::make($request->all(), [
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($fileValidator->fails()) {
            return response()->json(['errors' => $fileValidator->errors()], 422);
        }

        try {
            // Update property with validated data
            $property = $this->propertyService->updateProperty($id, $validator->validated());

            // Handle file uploads separately if they exist
            if ($request->hasFile('images')) {
                $this->propertyService->uploadPropertyImages($property->id, $request->file('images'));
            }

            if ($request->hasFile('attachments')) {
                $this->propertyService->uploadPropertyAttachments($property->id, $request->file('attachments'));
            }

            // Refresh the model to get the updated relations
            $property->load(['images', 'attachments']);

            return response()->json([
                'status' => 'success',
                'message' => 'Property updated successfully',
                'data' => [
                    'property' => new PropertyResource($property)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating property: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update property: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->propertyService->deleteProperty($id);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Property not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Property deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting property: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete property'
            ], 500);
        }
    }

    public function updateStatus(UpdatePropertyStatusRequest $request, int $id): JsonResponse
    {
        try {
            $property = $this->propertyService->updatePropertyStatus($id, $request->validated()['status_id']);

            if (!$property) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Property not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Property status updated successfully',
                'data' => [
                    'property' => new PropertyResource($property)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating property status: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update property status'
            ], 500);
        }
    }

    public function uploadImages(UploadPropertyImagesRequest $request, int $id): JsonResponse
    {
        try {
            $images = $this->propertyService->uploadPropertyImages(
                $id,
                $request->file('images'),
                $request->input('is_primary', false)
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Images uploaded successfully',
                'data' => [
                    'images' => PropertyImageResource::collection($images)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading property images: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload images'
            ], 500);
        }
    }

    public function uploadAttachments(UploadPropertyAttachmentsRequest $request, int $id): JsonResponse
    {
        try {
            $attachmentsInput = $request->file('attachments');
            if (!is_array($attachmentsInput)) {
                $attachmentsInput = [$attachmentsInput];
            }
            $attachments = $this->propertyService->uploadPropertyAttachments($id, $attachmentsInput);

            return response()->json([
                'status' => 'success',
                'message' => 'Attachments uploaded successfully',
                'data' => [
                    'attachments' => PropertyAttachmentResource::collection($attachments)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading property attachments: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload attachments'
            ], 500);
        }
    }

    public function deleteImage(int $id, int $imageId): JsonResponse
    {
        try {
            $result = $this->propertyService->deletePropertyImage($id, $imageId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting property image: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    public function deleteAttachment(int $id, int $attachmentId): JsonResponse
    {
        try {
            $result = $this->propertyService->deletePropertyAttachment($id, $attachmentId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Attachment not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Attachment deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting property attachment: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete attachment'
            ], 500);
        }
    }

    public function images($property): JsonResponse
    {
        $property = Property::findOrFail($property);
        return response()->json(['images' => $property->images]);
    }

    public function attachments($property): JsonResponse
    {
        $property = Property::findOrFail($property);
        return response()->json(['attachments' => $property->attachments]);
    }

    public function publicIndex(Request $request): JsonResponse
    {
        $properties = Property::where('status_id', 1) // Active properties only
            ->with(['images', 'location', 'office'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->location_id, function ($query, $locationId) {
                $query->where('location_id', $locationId);
            })
            ->paginate(15);

        return response()->json($properties);
    }

    public function publicShow($property): JsonResponse
    {
        $property = Property::where('status_id', 1)
            ->with(['images', 'attachments', 'location', 'office'])
            ->findOrFail($property);

        return response()->json(['property' => $property]);
    }

    public function search(Request $request): JsonResponse
    {
        $properties = Property::where('status_id', 1)
            ->with(['images', 'location'])
            ->when($request->q, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->min_price, function ($query, $minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when($request->max_price, function ($query, $maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })
            ->when($request->location_id, function ($query, $locationId) {
                $query->where('location_id', $locationId);
            })
            ->paginate(15);

        return response()->json($properties);
    }
}
