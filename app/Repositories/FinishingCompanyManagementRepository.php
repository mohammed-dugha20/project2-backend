<?php

namespace App\Repositories;

use App\Models\FinishingCompany;
use App\Models\FinishingRequest;
use App\Models\FinishingRequestResponse;
use App\Repositories\Interfaces\FinishingCompanyManagementRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FinishingCompanyManagementRepository implements FinishingCompanyManagementRepositoryInterface
{
    protected $finishingCompany;
    protected $finishingRequest;
    protected $finishingRequestResponse;

    public function __construct(
        FinishingCompany $finishingCompany,
        FinishingRequest $finishingRequest,
        FinishingRequestResponse $finishingRequestResponse
    ) {
        $this->finishingCompany = $finishingCompany;
        $this->finishingRequest = $finishingRequest;
        $this->finishingRequestResponse = $finishingRequestResponse;
    }

    public function getCompanyProfile(int $userId)
    {
        $company = $this->finishingCompany
            ->where('user_id', $userId)
            ->with(['user', 'services', 'workAreas.location', 'portfolio'])
            ->first();

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return $company;
    }

    public function updateCompanyProfile(int $userId, array $data)
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        // Update company basic info
        $updateData = array_filter([
            'commercial_name' => $data['commercial_name'] ?? null,
            'contact_info' => $data['contact_info'] ?? null,
            'profile_description' => $data['profile_description'] ?? null,
        ]);

        $company->update($updateData);

        // Update services if provided
        if (isset($data['services']) && is_array($data['services'])) {
            $company->services()->delete();
            foreach ($data['services'] as $service) {
                $company->services()->create([
                    'service_type' => $service['service_type'],
                    'description' => $service['description'],
                ]);
            }
        }

        // Update work areas if provided
        if (isset($data['work_areas']) && is_array($data['work_areas'])) {
            $company->workAreas()->delete();
            foreach ($data['work_areas'] as $locationId) {
                $company->workAreas()->create([
                    'location_id' => $locationId,
                ]);
            }
        }

        return $company->load(['user', 'services', 'workAreas.location', 'portfolio']);
    }

    public function toggleCompanyStatus(int $userId)
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $company->update(['is_active' => !$company->is_active]);
        return $company;
    }

    public function getCompanyReviews(int $userId, array $filters = [])
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $query = $company->reviews()->with('customer');

        if (isset($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (isset($filters['search'])) {
            $query->where('comment', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function getFinishingRequests(int $userId, array $filters = [])
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $query = $company->finishingRequests()
            ->with(['customer', 'status', 'location', 'images', 'companyResponse']);

        if (isset($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (isset($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        if (isset($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('description', 'like', "%{$filters['search']}%")
                  ->orWhere('service_type', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function getFinishingRequestDetails(int $requestId, int $userId)
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $request = $company->finishingRequests()
            ->with(['customer', 'status', 'location', 'images', 'companyResponse'])
            ->find($requestId);

        if (!$request) {
            throw new ModelNotFoundException('Finishing request not found');
        }

        return $request;
    }

    public function respondToRequest(int $requestId, int $userId, array $data)
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $request = $company->finishingRequests()->find($requestId);
        
        if (!$request) {
            throw new ModelNotFoundException('Finishing request not found');
        }

        // Create or update company response
        $response = $this->finishingRequestResponse->updateOrCreate(
            ['finishing_request_id' => $requestId],
            [
                'status' => $data['status'],
                'reason' => $data['reason'] ?? null,
                'estimated_cost' => $data['estimated_cost'] ?? null,
                'implementation_period' => $data['implementation_period'] ?? null,
                'materials' => $data['materials'] ?? null,
                'work_phases' => $data['work_phases'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]
        );

        // Update request status based on response
        $newStatusId = $data['status'] === 'accepted' ? 3 : 4; // 3 = accepted, 4 = rejected
        $request->update(['status_id' => $newStatusId]);

        return $request->load(['customer', 'status', 'location', 'images', 'companyResponse']);
    }

    public function getRequestHistory(int $userId, array $filters = [])
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $query = $company->finishingRequests()
            ->with(['customer', 'status', 'location', 'companyResponse'])
            ->whereIn('status_id', [3, 4, 5]); // completed, rejected, cancelled

        if (isset($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function getCompanyAnalytics(int $userId)
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $requests = $company->finishingRequests();
        
        return [
            'total_requests' => $requests->count(),
            'pending_requests' => $requests->where('status_id', 1)->count(),
            'accepted_requests' => $requests->where('status_id', 3)->count(),
            'rejected_requests' => $requests->where('status_id', 4)->count(),
            'completed_requests' => $requests->where('status_id', 5)->count(),
            'total_revenue' => $requests->where('status_id', 5)
                ->join('finishing_request_responses', 'finishing_requests.id', '=', 'finishing_request_responses.finishing_request_id')
                ->sum('finishing_request_responses.estimated_cost'),
            'average_response_time' => $this->calculateAverageResponseTime($company->id),
        ];
    }

    public function getCompanyPerformance(int $userId)
    {
        $company = $this->finishingCompany->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $reviews = $company->reviews();
        
        return [
            'average_rating' => $reviews->avg('rating'),
            'total_reviews' => $reviews->count(),
            'rating_distribution' => [
                '5_star' => $reviews->where('rating', 5)->count(),
                '4_star' => $reviews->where('rating', 4)->count(),
                '3_star' => $reviews->where('rating', 3)->count(),
                '2_star' => $reviews->where('rating', 2)->count(),
                '1_star' => $reviews->where('rating', 1)->count(),
            ],
            'acceptance_rate' => $this->calculateAcceptanceRate($company->id),
            'completion_rate' => $this->calculateCompletionRate($company->id),
        ];
    }

    private function calculateAverageResponseTime(int $companyId): float
    {
        $responses = $this->finishingRequestResponse
            ->whereHas('finishingRequest', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->get();

        if ($responses->isEmpty()) {
            return 0;
        }

        $totalHours = $responses->sum(function ($response) {
            return $response->created_at->diffInHours($response->finishingRequest->created_at);
        });

        return round($totalHours / $responses->count(), 2);
    }

    private function calculateAcceptanceRate(int $companyId): float
    {
        $totalRequests = $this->finishingRequest->where('company_id', $companyId)->count();
        $acceptedRequests = $this->finishingRequest->where('company_id', $companyId)
            ->where('status_id', 3)->count();

        return $totalRequests > 0 ? round(($acceptedRequests / $totalRequests) * 100, 2) : 0;
    }

    private function calculateCompletionRate(int $companyId): float
    {
        $acceptedRequests = $this->finishingRequest->where('company_id', $companyId)
            ->where('status_id', 3)->count();
        $completedRequests = $this->finishingRequest->where('company_id', $companyId)
            ->where('status_id', 5)->count();

        return $acceptedRequests > 0 ? round(($completedRequests / $acceptedRequests) * 100, 2) : 0;
    }
} 