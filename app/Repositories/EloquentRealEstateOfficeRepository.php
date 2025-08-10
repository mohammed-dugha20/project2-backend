<?php

namespace App\Repositories;

use App\Models\RealEstateOffice;
use App\Repositories\Interfaces\RealEstateOfficeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentRealEstateOfficeRepository implements RealEstateOfficeRepositoryInterface
{
    public function __construct(
        private readonly RealEstateOffice $model
    ) {}

    public function findById(int $id): ?RealEstateOffice
    {
        return $this->model->find($id);
    }

    public function findByUserId(int $userId): ?RealEstateOffice
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function update(int $id, array $data): bool
    {
        $office = $this->findById($id);
        if (!$office) {
            return false;
        }
        return $office->update($data);
    }

    public function getReviews(int $officeId): Collection
    {
        return $this->model->find($officeId)?->reviews ?? collect();
    }

    public function getDocuments(int $officeId): Collection
    {
        return $this->model->find($officeId)?->documents ?? collect();
    }

    public function getProperties(int $officeId): Collection
    {
        return $this->model->find($officeId)?->properties ?? collect();
    }

    public function addDocument(int $officeId, array $documentData): bool
    {
        $office = $this->findById($officeId);
        if (!$office) {
            return false;
        }
        return (bool) $office->documents()->create($documentData);
    }

    public function removeDocument(int $documentId): bool
    {
        $document = \App\Models\OfficeDocument::find($documentId);
        if (!$document) {
            return false;
        }
        return (bool) $document->delete();
    }

    public function getAll(array $filters = [])
    {
        return $this->model->all();
    }

    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function delete(int $id)
    {
        $office = $this->findById($id);
        if (!$office) {
            return false;
        }
        return $office->delete();
    }

    public function toggleStatus(int $id)
    {
        $office = $this->findById($id);
        if (!$office) {
            return false;
        }
        $office->is_active = !$office->is_active;
        $office->save();
        return $office;
    }

    public function getByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
} 