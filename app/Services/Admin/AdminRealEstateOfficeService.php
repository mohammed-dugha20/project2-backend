<?php

namespace App\Services\Admin;

use App\Repositories\Admin\Interfaces\AdminRealEstateOfficeRepositoryInterface;

class AdminRealEstateOfficeService
{
    protected $repository;

    public function __construct(AdminRealEstateOfficeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function getReviews(int $officeId)
    {
        return $this->repository->getReviews($officeId);
    }

    public function getDocuments(int $officeId)
    {
        return $this->repository->getDocuments($officeId);
    }

    public function getProperties(int $officeId)
    {
        return $this->repository->getProperties($officeId);
    }

    public function addDocument(int $officeId, array $documentData)
    {
        return $this->repository->addDocument($officeId, $documentData);
    }

    public function removeDocument(int $documentId)
    {
        return $this->repository->removeDocument($documentId);
    }

    public function getPerformance(int $officeId)
    {
        return $this->repository->getPerformance($officeId);
    }

    public function getAnalytics(int $officeId)
    {
        return $this->repository->getAnalytics($officeId);
    }
} 