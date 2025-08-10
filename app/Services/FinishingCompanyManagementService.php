<?php

namespace App\Services;

use App\Repositories\Interfaces\FinishingCompanyManagementRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FinishingCompanyManagementService
{
    protected $repository;

    public function __construct(FinishingCompanyManagementRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getCompanyProfile(int $userId)
    {
        try {
            return $this->repository->getCompanyProfile($userId);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company profile: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateCompanyProfile(int $userId, array $data)
    {
        try {
            return $this->repository->updateCompanyProfile($userId, $data);
        } catch (\Exception $e) {
            Log::error('Error updating finishing company profile: ' . $e->getMessage());
            throw $e;
        }
    }

    public function toggleCompanyStatus(int $userId)
    {
        try {
            return $this->repository->toggleCompanyStatus($userId);
        } catch (\Exception $e) {
            Log::error('Error toggling finishing company status: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCompanyReviews(int $userId, array $filters = [])
    {
        try {
            return $this->repository->getCompanyReviews($userId, $filters);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company reviews: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingRequests(int $userId, array $filters = [])
    {
        try {
            return $this->repository->getFinishingRequests($userId, $filters);
        } catch (\Exception $e) {
            Log::error('Error getting finishing requests: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingRequestDetails(int $requestId, int $userId)
    {
        try {
            return $this->repository->getFinishingRequestDetails($requestId, $userId);
        } catch (\Exception $e) {
            Log::error('Error getting finishing request details: ' . $e->getMessage());
            throw $e;
        }
    }

    public function respondToRequest(int $requestId, int $userId, array $data)
    {
        try {
            return $this->repository->respondToRequest($requestId, $userId, $data);
        } catch (\Exception $e) {
            Log::error('Error responding to finishing request: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getRequestHistory(int $userId, array $filters = [])
    {
        try {
            return $this->repository->getRequestHistory($userId, $filters);
        } catch (\Exception $e) {
            Log::error('Error getting finishing request history: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCompanyAnalytics(int $userId)
    {
        try {
            return $this->repository->getCompanyAnalytics($userId);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company analytics: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCompanyPerformance(int $userId)
    {
        try {
            return $this->repository->getCompanyPerformance($userId);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company performance: ' . $e->getMessage());
            throw $e;
        }
    }
} 