<?php

namespace App\Repositories\Interfaces;

use App\Models\RealEstateOffice;
use Illuminate\Database\Eloquent\Collection;

interface RealEstateOfficeRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function getByUserId(int $userId);
    public function findById(int $id): ?RealEstateOffice;
    public function findByUserId(int $userId): ?RealEstateOffice;
    public function getReviews(int $officeId): Collection;
    public function getDocuments(int $officeId): Collection;
    public function getProperties(int $officeId): Collection;
    public function addDocument(int $officeId, array $documentData): bool;
    public function removeDocument(int $documentId): bool;
} 