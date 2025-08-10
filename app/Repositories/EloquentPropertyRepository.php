<?php

namespace App\Repositories;

use App\Models\Property;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPropertyRepository implements PropertyRepositoryInterface
{
    public function __construct(
        private readonly Property $model
    ) {}

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Property::with(['images', 'attachments']);

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('location_id', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['min_area'])) {
            $query->where('area', '>=', $filters['min_area']);
        }

        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?Property
    {
        return Property::with(['images', 'attachments'])->find($id);
    }

    public function findByOfficeId(int $officeId): Collection
    {
        return $this->model->where('real_estate_office_id', $officeId)->get();
    }

    public function create(array $data): Property
    {
        return Property::create($data);
    }

    public function update(int $id, array $data): ?Property
    {
        $property = $this->findById($id);
        if ($property) {
            $property->update($data);
            return $property->fresh(['images', 'attachments']);
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $property = $this->findById($id);
        if ($property) {
            return $property->delete();
        }
        return false;
    }

    public function addImage(int $propertyId, array $imageData): bool
    {
        $property = $this->findById($propertyId);
        if (!$property) {
            return false;
        }
        return (bool) $property->images()->create($imageData);
    }

    public function addAttachment(int $propertyId, array $attachmentData): bool
    {
        $property = $this->findById($propertyId);
        if (!$property) {
            return false;
        }
        return (bool) $property->attachments()->create($attachmentData);
    }

    public function removeImage(int $imageId): bool
    {
        return (bool) $this->model->images()->where('id', $imageId)->delete();
    }

    public function removeAttachment(int $attachmentId): bool
    {
        return (bool) $this->model->attachments()->where('id', $attachmentId)->delete();
    }

    public function updateStatus(int $propertyId, int $statusId): bool
    {
        return $this->update($propertyId, ['status_id' => $statusId]);
    }
} 