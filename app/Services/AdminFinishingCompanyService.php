<?php

namespace App\Services;

use App\Repositories\Admin\Interfaces\AdminFinishingCompanyRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AdminFinishingCompanyService
{
    protected $repository;

    public function __construct(AdminFinishingCompanyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllFinishingCompanies(array $filters = [])
    {
        try {
            return $this->repository->getAllFinishingCompanies($filters);
        } catch (\Exception $e) {
            Log::error('Error getting all finishing companies: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyById(int $id)
    {
        try {
            return $this->repository->getFinishingCompanyById($id);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createFinishingCompany(array $data)
    {
        try {
            return $this->repository->createFinishingCompany($data);
        } catch (\Exception $e) {
            Log::error('Error creating finishing company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateFinishingCompany(int $id, array $data)
    {
        try {
            return $this->repository->updateFinishingCompany($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating finishing company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteFinishingCompany(int $id)
    {
        try {
            return $this->repository->deleteFinishingCompany($id);
        } catch (\Exception $e) {
            Log::error('Error deleting finishing company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function toggleFinishingCompanyStatus(int $id)
    {
        try {
            return $this->repository->toggleFinishingCompanyStatus($id);
        } catch (\Exception $e) {
            Log::error('Error toggling finishing company status: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyServices(int $id)
    {
        try {
            return $this->repository->getFinishingCompanyServices($id);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company services: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyWorkAreas(int $id)
    {
        try {
            return $this->repository->getFinishingCompanyWorkAreas($id);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company work areas: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyPortfolio(int $id)
    {
        try {
            return $this->repository->getFinishingCompanyPortfolio($id);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company portfolio: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyRequests(int $id, array $filters = [])
    {
        try {
            return $this->repository->getFinishingCompanyRequests($id, $filters);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company requests: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyReviews(int $id, array $filters = [])
    {
        try {
            return $this->repository->getFinishingCompanyReviews($id, $filters);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company reviews: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyAnalytics(int $id)
    {
        try {
            return $this->repository->getFinishingCompanyAnalytics($id);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company analytics: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFinishingCompanyPerformance(int $id)
    {
        try {
            return $this->repository->getFinishingCompanyPerformance($id);
        } catch (\Exception $e) {
            Log::error('Error getting finishing company performance: ' . $e->getMessage());
            throw $e;
        }
    }
} 