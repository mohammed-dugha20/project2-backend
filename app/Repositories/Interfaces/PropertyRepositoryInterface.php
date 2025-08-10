<?php

namespace App\Repositories\Interfaces;

use App\Models\Property;
use Illuminate\Pagination\LengthAwarePaginator;

interface PropertyRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?Property;
    public function create(array $data): Property;
    public function update(int $id, array $data): ?Property;
    public function delete(int $id): bool;
    public function addImage(int $propertyId, array $imageData): bool;
    public function addAttachment(int $propertyId, array $attachmentData): bool;
    public function removeImage(int $imageId): bool;
    public function removeAttachment(int $attachmentId): bool;
    public function updateStatus(int $propertyId, int $statusId): bool;
} 