<?php

namespace App\Repositories\Admin;

use App\Models\FinishingCompany;
use App\Models\User;
use App\Repositories\Admin\Interfaces\AdminFinishingCompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminFinishingCompanyRepository implements AdminFinishingCompanyRepositoryInterface
{
    protected $finishingCompany;
    protected $user;

    public function __construct(FinishingCompany $finishingCompany, User $user)
    {
        $this->finishingCompany = $finishingCompany;
        $this->user = $user;
    }

    public function getAllFinishingCompanies(array $filters = [])
    {
        $query = $this->finishingCompany->with(['user', 'services', 'workAreas.location']);

        if (isset($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('commercial_name', 'like', "%{$filters['search']}%")
                    ->orWhere('contact_info', 'like', "%{$filters['search']}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($filters) {
                        $userQuery->where('name', 'like', "%{$filters['search']}%")
                            ->orWhere('email', 'like', "%{$filters['search']}%");
                    });
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getFinishingCompanyById(int $id)
    {
        $company = $this->finishingCompany
            ->with(['user', 'services', 'workAreas.location', 'portfolio'])
            ->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return $company;
    }

    public function createFinishingCompany(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create user account
            $user = $this->user->create([
                'name' => $data['user']['name'],
                'username' => $data['user']['name'],
                'name' => $data['user']['name'],
                'email' => $data['user']['email'],
                'phone' => $data['user']['phone'],
                'password' => Hash::make($data['user']['password']),
                'user_type' => 'finishing_company',
            ]);

            // Assign finishing company role
            $user->assignRole('finishing_company');

            // Create finishing company
            $company = $this->finishingCompany->create([
                'user_id' => $user->id,
                'commercial_name' => $data['commercial_name'],
                'contact_info' => $data['contact_info'],
                'profile_description' => $data['profile_description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            return $company->load(['user', 'services', 'workAreas.location', 'portfolio']);
        });
    }

    public function updateFinishingCompany(int $id, array $data)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return DB::transaction(function () use ($company, $data) {
            // Update user information if provided
            if (isset($data['user'])) {
                $userData = array_filter([
                    'name' => $data['user']['name'] ?? null,
                    'email' => $data['user']['email'] ?? null,
                    'phone' => $data['user']['phone'] ?? null,
                ]);

                if (!empty($userData)) {
                    $company->user->update($userData);
                }
            }

            // Update company information
            $companyData = array_filter([
                'commercial_name' => $data['commercial_name'] ?? null,
                'contact_info' => $data['contact_info'] ?? null,
                'profile_description' => $data['profile_description'] ?? null,
                'is_active' => $data['is_active'] ?? null,
            ]);

            if (!empty($companyData)) {
                $company->update($companyData);
            }

            return $company->load(['user', 'services', 'workAreas.location', 'portfolio']);
        });
    }

    public function deleteFinishingCompany(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return DB::transaction(function () use ($company) {
            // Archive associated projects
            $this->archiveFinishingCompanyProjects($company->id);

            // Delete the company and associated user
            $userId = $company->user_id;
            $company->delete();

            // Delete the user account
            $this->user->find($userId)->delete();

            return true;
        });
    }

    public function toggleFinishingCompanyStatus(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $company->update(['is_active' => !$company->is_active]);
        return $company;
    }

    public function getFinishingCompanyServices(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return $company->services;
    }

    public function getFinishingCompanyWorkAreas(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return $company->workAreas()->with('location')->get();
    }

    public function getFinishingCompanyPortfolio(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        return $company->portfolio;
    }

    public function getFinishingCompanyRequests(int $id, array $filters = [])
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $query = $company->finishingRequests()
            ->with(['customer', 'status', 'location', 'images', 'companyResponse']);

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

    public function getFinishingCompanyReviews(int $id, array $filters = [])
    {
        $company = $this->finishingCompany->find($id);

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

    public function getFinishingCompanyAnalytics(int $id)
    {
        $company = $this->finishingCompany->find($id);

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

    public function getFinishingCompanyPerformance(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        $reviews = $company->reviews();

        return [
            'average_rating' => $reviews->clone()->avg('rating'),
            'total_reviews' => $reviews->clone()->count(),
            'rating_distribution' => [
                '5_star' => $reviews->clone()->where('rating', 5)->count(),
                '4_star' => $reviews->clone()->where('rating', 4)->count(),
                '3_star' => $reviews->clone()->where('rating', 3)->count(),
                '2_star' => $reviews->clone()->where('rating', 2)->count(),
                '1_star' => $reviews->clone()->where('rating', 1)->count(),
            ],
            'acceptance_rate' => $this->calculateAcceptanceRate($company->id),
            'completion_rate' => $this->calculateCompletionRate($company->id),
        ];
    }

    public function archiveFinishingCompanyProjects(int $id)
    {
        $company = $this->finishingCompany->find($id);

        if (!$company) {
            throw new ModelNotFoundException('Finishing company not found');
        }

        // Archive all finishing requests by updating their status to archived
        $company->finishingRequests()->update(['status_id' => 6]); // Assuming 6 is archived status

        return true;
    }

    private function calculateAverageResponseTime(int $companyId): float
    {
        $responses = \App\Models\FinishingRequestResponse::whereHas('finishingRequest', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->get();

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
        $totalRequests = \App\Models\FinishingRequest::where('company_id', $companyId)->count();
        $acceptedRequests = \App\Models\FinishingRequest::where('company_id', $companyId)
            ->where('status_id', 3)->count();

        return $totalRequests > 0 ? round(($acceptedRequests / $totalRequests) * 100, 2) : 0;
    }

    private function calculateCompletionRate(int $companyId): float
    {
        $acceptedRequests = \App\Models\FinishingRequest::where('company_id', $companyId)
            ->where('status_id', 3)->count();
        $completedRequests = \App\Models\FinishingRequest::where('company_id', $companyId)
            ->where('status_id', 5)->count();

        return $acceptedRequests > 0 ? round(($completedRequests / $acceptedRequests) * 100, 2) : 0;
    }
}