<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\RealEstateOffice;
use App\Repositories\Interfaces\RealEstateOfficeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RealEstateOfficeRepository implements RealEstateOfficeRepositoryInterface
{
    protected $model;
    protected $userModel;

    public function __construct(RealEstateOffice $model, User $userModel)
    {
        $this->model = $model;
        $this->userModel = $userModel;
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('license_number', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->with('user')->paginate($filters['per_page'] ?? 15);
    }

    public function getById(int $id)
    {
        $office = $this->model->with('user')->find($id);
        if (!$office) {
            throw new ModelNotFoundException('Real estate office not found');
        }
        return $office;
    }

    public function create(array $data)
    {
        // Create user first
        $user = $this->userModel->create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'user_type' => 'real_estate_office',
        ]);

        // Create office
        $office = $this->model->create([
            'commercial_name' => $data['commercial_name'],
            'address' => $data['address'],
            'license_number' => $data['license_number'],
            'phone_number' => $data['phone_number'],
            'profile_description' => $data['profile_description'],
            'is_active' => true,
            'user_id' => $user->id,
        ]);

        // Assign role
        $user->assignRole('real_estate_office');

        return $office->load('user');
    }

    public function update(int $id, array $data)
    {
        $office = $this->getById($id);

        $updateData = array_filter([
            'commercial_name' => $data['commercial_name'] ?? null,
            'address' => $data['address'] ?? null,
            'license_number' => $data['license_number'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'profile_description' => $data['profile_description'] ?? null,
        ]);

        $office->update($updateData);

        // Update user if provided
        if (isset($data['username']) || isset($data['email'])) {
            $userData = array_filter([
                'username' => $data['username'] ?? null,
                'email' => $data['email'] ?? null,
            ]);

            if (!empty($userData)) {
                $office->user->update($userData);
            }
        }

        return $office->load('user');
    }

    public function toggleStatus(int $id)
    {
        $office = $this->getById($id);
        $office->update(['is_active' => !$office->is_active]);
        return $office;
    }

    public function getByUserId(int $userId)
    {
        $office = $this->model->where('user_id', $userId)->first();
        if (!$office) {
            throw new ModelNotFoundException('Real estate office not found');
        }
        return $office;
    }

    public function delete(int $id)
    {
        $office = $this->getById($id);
        $office->delete();
        return true;
    }

    public function findById(int $id): ?RealEstateOffice
    {
        return $this->model->find($id);
    }

    public function findByUserId(int $userId): ?RealEstateOffice
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function getReviews(int $officeId): \Illuminate\Database\Eloquent\Collection
    {
        $office = $this->getById($officeId);
        return $office->reviews()->with('reviewer')->get();
    }

    public function getDocuments(int $officeId): \Illuminate\Database\Eloquent\Collection
    {
        $office = $this->getById($officeId);
        return $office->documents()->get();
    }

    public function getProperties(int $officeId): \Illuminate\Database\Eloquent\Collection
    {
        $office = $this->getById($officeId);
        return $office->properties()->with('images')->get();
    }

    public function addDocument(int $officeId, array $documentData): bool
    {
        $office = $this->getById($officeId);
        $document = $office->documents()->create($documentData);
        return $document ? true : false;
    }

    public function removeDocument(int $documentId): bool
    {
        $document = \App\Models\OfficeDocument::find($documentId);
        if (!$document) {
            return false;
        }
        
        // Delete the file from storage
        if ($document->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        }
        
        return $document->delete();
    }
}