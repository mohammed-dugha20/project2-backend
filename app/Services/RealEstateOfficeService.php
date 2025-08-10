<?php

namespace App\Services;

use App\Exceptions\RealEstateOfficeException;
use App\Repositories\Interfaces\RealEstateOfficeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RealEstateOfficeService
{
    public function __construct(
        private readonly RealEstateOfficeRepositoryInterface $repository
    ) {
    }

    public function getOfficeProfile(int $userId): ?array
    {
        $office = $this->repository->findByUserId($userId);
        if (!$office) {
            return null;
        }

        return [
            'id' => $office->id,
            'commercial_name' => $office->commercial_name,
            'address' => $office->address,
            'phone_number' => $office->phone_number,
            'license_number' => $office->license_number,
            'profile_description' => $office->profile_description,
            'is_active' => $office->is_active,
            'documents' => $this->repository->getDocuments($office->id),
            'reviews' => $this->repository->getReviews($office->id),
        ];
    }

    public function updateOfficeProfile(int $userId, array $data): bool
    {
        $office = $this->repository->findByUserId($userId);

        if (!$office) {
            throw new RealEstateOfficeException('Office not found');
        }

        $success = $this->repository->update($office->id, $data);
        if (!$success) {
            throw new RealEstateOfficeException('Failed to update office profile');
        }

        return true;
    }

    public function uploadDocument(int $userId, UploadedFile $file, string $type, ?string $description = null): bool
    {
        $office = $this->repository->findByUserId($userId);
        if (!$office) {
            throw new RealEstateOfficeException('Office not found');
        }

        $path = $file->store('office-documents', 'public');
        if (!$path) {
            throw new RealEstateOfficeException('Failed to store document');
        }

        $success = $this->repository->addDocument($office->id, [
            'document_type' => $type,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'description' => $description,
        ]);

        if (!$success) {
            Storage::disk('public')->delete($path);
            throw new RealEstateOfficeException('Failed to save document record');
        }

        return true;
    }

    public function removeDocument(int $userId, int $documentId): bool
    {
        $office = $this->repository->findByUserId($userId);
        if (!$office) {
            throw new RealEstateOfficeException('Office not found');
        }

        $success = $this->repository->removeDocument($documentId);
        if (!$success) {
            throw new RealEstateOfficeException('Failed to remove document');
        }

        return true;
    }

    public function getOfficeProperties(int $userId): Collection
    {
        $office = $this->repository->findByUserId($userId);
        if (!$office) {
            throw new RealEstateOfficeException('Office not found');
        }

        return $this->repository->getProperties($office->id);
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

    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }
}