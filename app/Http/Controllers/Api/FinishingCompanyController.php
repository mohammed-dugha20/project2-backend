<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FinishingCompany\StoreFinishingCompanyRequest;
use App\Http\Requests\Admin\FinishingCompany\UpdateFinishingCompanyRequest;
use App\Http\Requests\FinishingCompany\RespondToRequestRequest;
use App\Http\Requests\FinishingCompany\UpdateProfileRequest;
use App\Http\Resources\Admin\FinishingCompanyResource;
use App\Http\Resources\FinishingCompany\FinishingCompanyResource as CompanyResource;
use App\Http\Resources\FinishingCompany\FinishingRequestResource;
use App\Services\AdminFinishingCompanyService;
use App\Services\FinishingCompanyManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class FinishingCompanyController extends Controller
{
    protected $service;
    protected $adminService;

    public function __construct(
        FinishingCompanyManagementService $service,
        AdminFinishingCompanyService $adminService
    ) {
        $this->service = $service;
        $this->adminService = $adminService;
    }

    // ==================== COMPANY MANAGEMENT METHODS ====================

    /**
     * Get company profile
     */
    public function getProfile(): JsonResponse
    {
        try {
            $profile = $this->service->getCompanyProfile(Auth::id());
            return response()->json([
                'success' => true,
                'data' => new CompanyResource($profile),
                'message' => 'Company profile retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update company profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $profile = $this->service->updateCompanyProfile(Auth::id(), $request->validated());
            return response()->json([
                'success' => true,
                'data' => new CompanyResource($profile),
                'message' => 'Company profile updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update company profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle company status (activate/deactivate)
     */
    public function toggleStatus(): JsonResponse
    {
        try {
            $company = $this->service->toggleCompanyStatus(Auth::id());
            return response()->json([
                'success' => true,
                'data' => [
                    'is_active' => $company->is_active,
                    'status_message' => $company->is_active ? 'Company activated' : 'Company deactivated'
                ],
                'message' => 'Company status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update company status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get company reviews
     */
    public function getReviews(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['rating', 'search', 'per_page']);
            $reviews = $this->service->getCompanyReviews(Auth::id(), $filters);
            
            return response()->json([
                'success' => true,
                'data' => $reviews,
                'message' => 'Company reviews retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing requests
     */
    public function getRequests(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['status_id', 'service_type', 'search', 'per_page']);
            $requests = $this->service->getFinishingRequests(Auth::id(), $filters);
            
            return response()->json([
                'success' => true,
                'data' => FinishingRequestResource::collection($requests),
                'pagination' => [
                    'current_page' => $requests->currentPage(),
                    'last_page' => $requests->lastPage(),
                    'per_page' => $requests->perPage(),
                    'total' => $requests->total(),
                ],
                'message' => 'Finishing requests retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing request details
     */
    public function getRequestDetails(int $requestId): JsonResponse
    {
        try {
            $request = $this->service->getFinishingRequestDetails($requestId, Auth::id());
            return response()->json([
                'success' => true,
                'data' => new FinishingRequestResource($request),
                'message' => 'Request details retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve request details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Respond to finishing request
     */
    public function respondToRequest(RespondToRequestRequest $request, int $requestId): JsonResponse
    {
        try {
            $updatedRequest = $this->service->respondToRequest($requestId, Auth::id(), $request->validated());
            return response()->json([
                'success' => true,
                'data' => new FinishingRequestResource($updatedRequest),
                'message' => 'Response submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit response',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get request history
     */
    public function getRequestHistory(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['status_id', 'date_from', 'date_to', 'per_page']);
            $history = $this->service->getRequestHistory(Auth::id(), $filters);
            
            return response()->json([
                'success' => true,
                'data' => FinishingRequestResource::collection($history),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'last_page' => $history->lastPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                ],
                'message' => 'Request history retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve request history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get company analytics
     */
    public function getAnalytics(): JsonResponse
    {
        try {
            $analytics = $this->service->getCompanyAnalytics(Auth::id());
            return response()->json([
                'success' => true,
                'data' => $analytics,
                'message' => 'Company analytics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get company performance
     */
    public function getPerformance(): JsonResponse
    {
        try {
            $performance = $this->service->getCompanyPerformance(Auth::id());
            return response()->json([
                'success' => true,
                'data' => $performance,
                'message' => 'Company performance retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Get all finishing companies (Admin)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'status', 'sort_by', 'sort_direction', 'per_page']);
            $companies = $this->adminService->getAllFinishingCompanies($filters);
            
            return response()->json([
                'success' => true,
                'data' => FinishingCompanyResource::collection($companies),
                'pagination' => [
                    'current_page' => $companies->currentPage(),
                    'last_page' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                ],
                'message' => 'Finishing companies retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company by ID (Admin)
     */
    public function show(int $id): JsonResponse
    {
        try {
            $company = $this->adminService->getFinishingCompanyById($id);
            return response()->json([
                'success' => true,
                'data' => new FinishingCompanyResource($company),
                'message' => 'Finishing company retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create finishing company (Admin)
     */
    public function store(StoreFinishingCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->adminService->createFinishingCompany($request->validated());
            return response()->json([
                'success' => true,
                'data' => new FinishingCompanyResource($company),
                'message' => 'Finishing company created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create finishing company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update finishing company (Admin)
     */
    public function update(UpdateFinishingCompanyRequest $request, int $id): JsonResponse
    {
        try {
            $company = $this->adminService->updateFinishingCompany($id, $request->validated());
            return response()->json([
                'success' => true,
                'data' => new FinishingCompanyResource($company),
                'message' => 'Finishing company updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update finishing company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete finishing company (Admin)
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->adminService->deleteFinishingCompany($id);
            return response()->json([
                'success' => true,
                'message' => 'Finishing company deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete finishing company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle finishing company status (Admin)
     */
    public function adminToggleStatus(int $id): JsonResponse
    {
        try {
            $company = $this->adminService->toggleFinishingCompanyStatus($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $company->id,
                    'is_active' => $company->is_active,
                    'status_message' => $company->is_active ? 'Company activated' : 'Company deactivated'
                ],
                'message' => 'Finishing company status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update finishing company status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company services (Admin)
     */
    public function getServices(int $id): JsonResponse
    {
        try {
            $services = $this->adminService->getFinishingCompanyServices($id);
            return response()->json([
                'success' => true,
                'data' => $services,
                'message' => 'Finishing company services retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company work areas (Admin)
     */
    public function getWorkAreas(int $id): JsonResponse
    {
        try {
            $workAreas = $this->adminService->getFinishingCompanyWorkAreas($id);
            return response()->json([
                'success' => true,
                'data' => $workAreas,
                'message' => 'Finishing company work areas retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company work areas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company portfolio (Admin)
     */
    public function getPortfolio(int $id): JsonResponse
    {
        try {
            $portfolio = $this->adminService->getFinishingCompanyPortfolio($id);
            return response()->json([
                'success' => true,
                'data' => $portfolio,
                'message' => 'Finishing company portfolio retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company portfolio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company requests (Admin)
     */
    public function adminRequests(Request $request, int $id): JsonResponse
    {
        try {
            $filters = $request->only(['status_id', 'date_from', 'date_to', 'per_page']);
            $requests = $this->adminService->getFinishingCompanyRequests($id, $filters);
            
            return response()->json([
                'success' => true,
                'data' => FinishingRequestResource::collection($requests),
                'pagination' => [
                    'current_page' => $requests->currentPage(),
                    'last_page' => $requests->lastPage(),
                    'per_page' => $requests->perPage(),
                    'total' => $requests->total(),
                ],
                'message' => 'Finishing company requests retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company reviews (Admin)
     */
    public function adminReviews(Request $request, int $id): JsonResponse
    {
        try {
            $filters = $request->only(['rating', 'search', 'per_page']);
            $reviews = $this->adminService->getFinishingCompanyReviews($id, $filters);
            
            return response()->json([
                'success' => true,
                'data' => $reviews,
                'message' => 'Finishing company reviews retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company analytics (Admin)
     */
    public function adminAnalytics(int $id): JsonResponse
    {
        try {
            $analytics = $this->adminService->getFinishingCompanyAnalytics($id);
            return response()->json([
                'success' => true,
                'data' => $analytics,
                'message' => 'Finishing company analytics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company performance (Admin)
     */
    public function adminPerformance(int $id): JsonResponse
    {
        try {
            $performance = $this->adminService->getFinishingCompanyPerformance($id);
            return response()->json([
                'success' => true,
                'data' => $performance,
                'message' => 'Finishing company performance retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== PUBLIC METHODS ====================

    /**
     * Get all active finishing companies (Public)
     */
    public function publicIndex(Request $request): JsonResponse
    {
        try {
            $companies = \App\Models\FinishingCompany::where('is_active', true)
                ->with(['user', 'services', 'workAreas.location'])
                ->when($request->search, function($query, $search) {
                    $query->where('commercial_name', 'like', "%{$search}%");
                })
                ->paginate(15);
                
            return response()->json([
                'success' => true,
                'data' => FinishingCompanyResource::collection($companies),
                'pagination' => [
                    'current_page' => $companies->currentPage(),
                    'last_page' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                ],
                'message' => 'Finishing companies retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get finishing company details (Public)
     */
    public function publicShow(int $id): JsonResponse
    {
        try {
            $company = \App\Models\FinishingCompany::where('is_active', true)
                ->with(['user', 'services', 'workAreas.location', 'portfolio'])
                ->findOrFail($id);
                
            return response()->json([
                'success' => true,
                'data' => new FinishingCompanyResource($company),
                'message' => 'Finishing company details retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finishing company details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 