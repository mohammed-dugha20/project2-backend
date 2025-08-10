<?php

namespace App\Services;

use App\Repositories\Admin\Interfaces\AdminDisputeRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AdminDisputeService
{
    protected $repository;

    public function __construct(AdminDisputeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listDisputes(array $filters = [])
    {
        try {
            return $this->repository->listDisputes($filters);
        } catch (\Exception $e) {
            Log::error('Error listing disputes: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getDisputeById(int $id)
    {
        try {
            return $this->repository->getDisputeById($id);
        } catch (\Exception $e) {
            Log::error('Error getting dispute by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateDispute(int $id, array $data)
    {
        try {
            return $this->repository->updateDispute($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating dispute: ' . $e->getMessage());
            throw $e;
        }
    }
} 