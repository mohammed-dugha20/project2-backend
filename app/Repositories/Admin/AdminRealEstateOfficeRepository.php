<?php

namespace App\Repositories\Admin;

use App\Models\RealEstateOffice;
use App\Models\User;
use App\Models\OfficeDocument;
use App\Repositories\Admin\Interfaces\AdminRealEstateOfficeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminRealEstateOfficeRepository implements AdminRealEstateOfficeRepositoryInterface
{
    protected $model;
    protected $user;

    public function __construct(RealEstateOffice $model, User $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['user', 'documents', 'properties']);
        // Add filters as needed
        if (isset($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }
        return $query->get();
    }

    public function getById(int $id)
    {
        return $this->model->with(['user', 'documents', 'properties'])->find($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->user->create([
                'username'=>$data['user']['username'],
                'name' => $data['user']['name'],
                'email' => $data['user']['email'],
                'phone' => $data['user']['phone'],
                'password' => Hash::make($data['user']['password']),
                'user_type' => 'real_estate_office',
            ]);
            $user->assignRole('real_estate_office');
            $office = $this->model->create([
                'user_id' => $user->id,
                'commercial_name' => $data['commercial_name'],
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'license_number' => $data['license_number'],
                'profile_description' => $data['profile_description'],
                'is_active' => $data['is_active'] ?? true,
            ]);
            return $office->load(['user', 'documents', 'properties']);
        });
    }

    public function update(int $id, array $data)
    {
        $office = $this->model->find($id);
        if (!$office)
            return null;
        return DB::transaction(function () use ($office, $data) {
            if (isset($data['user'])) {
                $userData = array_filter([
                    'name' => $data['user']['name'] ?? null,
                    'email' => $data['user']['email'] ?? null,
                    'phone' => $data['user']['phone'] ?? null,
                ]);
                if (!empty($userData)) {
                    $office->user->update($userData);
                }
                if (isset($data['user']['password'])) {
                    $office->user->update(['password' => Hash::make($data['user']['password'])]);
                }
            }
            $officeData = array_filter([
                'commercial_name' => $data['commercial_name'] ?? null,
                'address' => $data['address'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'is_active' => $data['is_active'] ?? null,
            ]);
            if (!empty($officeData)) {
                $office->update($officeData);
            }
            return $office->load(['user', 'documents', 'properties']);
        });
    }

    public function delete(int $id)
    {
        $office = $this->model->find($id);
        if (!$office)
            return false;
        return DB::transaction(function () use ($office) {
            $userId = $office->user_id;
            $office->delete();
            $this->user->find($userId)?->delete();
            return true;
        });
    }

    public function toggleStatus(int $id)
    {
        $office = $this->model->find($id);
        if (!$office)
            return null;
        $office->is_active = !$office->is_active;
        $office->save();
        return $office;
    }

    public function getReviews(int $officeId)
    {
        $office = $this->model->find($officeId);
        return $office ? $office->reviews : collect();
    }

    public function getDocuments(int $officeId)
    {
        $office = $this->model->find($officeId);
        return $office ? $office->documents : collect();
    }

    public function getProperties(int $officeId)
    {
        $office = $this->model->find($officeId);
        return $office ? $office->properties : collect();
    }

    public function addDocument(int $officeId, array $documentData)
    {
        $office = $this->model->find($officeId);
        if (!$office)
            return false;
        return (bool) $office->documents()->create($documentData);
    }

    public function removeDocument(int $documentId)
    {
        $document = OfficeDocument::find($documentId);
        if (!$document)
            return false;
        return (bool) $document->delete();
    }

    public function getPerformance(int $officeId)
    {
        $office = $this->model->with(['reviews', 'properties'])->find($officeId);
        if (!$office)
            return null;
        $averageRating = $office->reviews->avg('rating');
        $totalReviews = $office->reviews->count();
        $propertiesCount = $office->properties->count();
        // Add more metrics as needed
        return [
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'properties_count' => $propertiesCount,
        ];
    }

    public function getAnalytics(int $officeId)
    {
        $office = $this->model->with(['properties', 'reviews', 'documents'])->find($officeId);
        if (!$office)
            return null;
        $properties = $office->properties;
        $analytics = [
            'total_properties' => $properties->count(),
            'active_properties' => $properties->where('status_id', 1)->count(),
            'properties_by_type' => $properties->groupBy('type')->map->count(),
            'total_reviews' => $office->reviews->count(),
            'total_documents' => $office->documents->count(),
            'active' => $office->is_active,
        ];
        return $analytics;
    }
}