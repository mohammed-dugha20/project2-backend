<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RealEstateOfficeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RealEstateOfficeController extends Controller
{
    protected $officeService;

    public function __construct(RealEstateOfficeService $officeService)
    {
        $this->officeService = $officeService;

    }

    public function index(Request $request): JsonResponse
    {
        $offices = $this->officeService->getAll($request->all());
        return response()->json($offices);
    }

    public function show(int $id): JsonResponse
    {
        $office = $this->officeService->getById($id);
        return response()->json($office);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $office = $this->officeService->create($request->all());
            return response()->json([
                'message' => 'Real estate office created successfully',
                'office' => $office
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to create real estate office',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create real estate office',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $office = $this->officeService->update($id, $request->all());
            return response()->json([
                'message' => 'Real estate office updated successfully',
                'office' => $office
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to update real estate office',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update real estate office',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $office = $this->officeService->toggleStatus($id);
            return response()->json([
                'message' => 'Real estate office status toggled successfully',
                'office' => $office
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to toggle real estate office status',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to toggle real estate office status',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function profile(): JsonResponse
    {
        try {
            $profile = $this->officeService->getOfficeProfile(Auth::id());

            if (!$profile) {
                return response()->json(['message' => 'Office profile not found'], 404);
            }

            return response()->json($profile);
        } catch (\Exception $e) {
            Log::error('Failed to fetch office profile', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Failed to fetch office profile'], 500);
        }
    }

    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'commercial_name' => ['sometimes', 'string', 'max:255'],
                'address' => ['sometimes', 'string', 'max:255'],
                'phone_number' => ['sometimes', 'string', 'max:20'],
                'license_number' => ['sometimes', 'string', 'max:50'],
                'profile_description' => ['sometimes', 'string'],
            ]);

            $success = $this->officeService->updateOfficeProfile(Auth::id(), $validated);

            if (!$success) {
                return response()->json(['message' => 'Failed to update profile'], 500);
            }

            return response()->json(['message' => 'Profile updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update office profile', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Failed to update profile'], 500);
        }
    }

    public function uploadDocument(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'document' => ['required', 'file', 'max:10240'], // 10MB max
                'type' => ['required', 'string', 'max:50'],
                'description' => ['nullable', 'string'],
            ]);

            $success = $this->officeService->uploadDocument(
                Auth::id(),
                $request->file('document'),
                $validated['type'],
                $validated['description'] ?? null
            );

            if (!$success) {
                return response()->json(['message' => 'Failed to upload document'], 500);
            }

            return response()->json(['message' => 'Document uploaded successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Failed to upload document', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Failed to upload document'], 500);
        }
    }

    public function removeDocument(int $documentId): JsonResponse
    {
        try {
            $success = $this->officeService->removeDocument(Auth::id(), $documentId);

            if (!$success) {
                return response()->json(['message' => 'Failed to remove document'], 500);
            }

            return response()->json(['message' => 'Document removed successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to remove document', [
                'user_id' => Auth::id(),
                'document_id' => $documentId,
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Failed to remove document'], 500);
        }
    }

    public function properties(): JsonResponse
    {
        try {
            $properties = $this->officeService->getOfficeProperties(Auth::id());
            return response()->json($properties);
        } catch (\Exception $e) {
            Log::error('Failed to fetch office properties', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Failed to fetch properties'], 500);
        }
    }

    public function analytics(): JsonResponse
    {
        $user = Auth::user();
        if (!$user->isRealEstateOffice()) {
            return response()->json(['message' => 'User is not a real estate office'], 403);
        }

        $office = $user->realEstateOffice;
        $analytics = [
            'total_properties' => $office->properties()->count(),
            'active_properties' => $office->properties()->where('status_id', 1)->count(),
            'total_views' => $office->properties()->sum('views'),
            'total_requests' => $office->properties()->withCount('propertyRequests')->get()->sum('property_requests_count'),
        ];

        return response()->json(['analytics' => $analytics]);
    }

    public function reviews(): JsonResponse
    {
        $user = Auth::user();
        if (!$user->isRealEstateOffice()) {
            return response()->json(['message' => 'User is not a real estate office'], 403);
        }

        $reviews = $user->realEstateOffice->reviews()->with('reviewer')->paginate(15);
        return response()->json(['reviews' => $reviews]);
    }

    public function adminReviews($id): JsonResponse
    {
        $office = \App\Models\RealEstateOffice::findOrFail($id);
        $reviews = $office->reviews()->with('reviewer')->paginate(15);
        return response()->json(['reviews' => $reviews]);
    }

    public function adminDocuments($id): JsonResponse
    {
        $office = \App\Models\RealEstateOffice::findOrFail($id);
        $documents = $office->documents()->paginate(15);
        return response()->json(['documents' => $documents]);
    }

    public function publicIndex(Request $request): JsonResponse
    {
        $offices = \App\Models\RealEstateOffice::where('is_active', true)
            ->with(['user', 'reviews'])
            ->when($request->search, function ($query, $search) {
                $query->where('commercial_name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            })
            ->paginate(15);

        return response()->json($offices);
    }

    public function publicShow($id): JsonResponse
    {
        $office = \App\Models\RealEstateOffice::where('is_active', true)
            ->with(['user', 'reviews.reviewer', 'properties.images'])
            ->findOrFail($id);

        return response()->json(['office' => $office]);
    }
}