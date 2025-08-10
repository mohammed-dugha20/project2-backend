<?php

namespace App\Services;

use App\Models\Property;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyService
{
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties(array $filters = []): LengthAwarePaginator
    {
        return $this->propertyRepository->getAll($filters);
    }

    public function getPropertyById(int $id): ?Property
    {
        return $this->propertyRepository->findById($id);
    }

    public function createProperty(array $data): Property
    {
        $property = $this->propertyRepository->create($data);

        if (isset($data['images'])) {
            $this->uploadPropertyImages($property->id, $data['images'], $data['is_primary'] ?? false);
        }

        if (isset($data['attachments'])) {
            $this->uploadPropertyAttachments($property->id, $data['attachments']);
        }

        return $property->load(['images', 'attachments']);
    }

    public function updateProperty(int $id, array $data): ?Property
    {
        return $this->propertyRepository->update($id, $data);
    }

    public function deleteProperty(int $id): bool
    {
        $property = $this->propertyRepository->findById($id);

        if (!$property) {
            return false;
        }

        // Delete associated files
        foreach ($property->images as $image) {
            Storage::delete($image->image_url);
        }

        foreach ($property->attachments as $attachment) {
            Storage::delete($attachment->file_path);
        }

        return $this->propertyRepository->delete($id);
    }

    public function updatePropertyStatus(int $id, int $statusId): ?Property
    {
        return $this->propertyRepository->update($id, ['status_id' => $statusId]);
    }

    public function uploadPropertyImages(int $propertyId, array $images, bool $isPrimary = false): Collection
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            throw new \Exception('Property not found');
        }

        $uploadedImages = new Collection();

        foreach ($images as $image) {
            $path = $image->store('properties/images', 'public');
            $filename = $image->getClientOriginalName();

            $imageData = [
                'property_id' => $propertyId,
                'image_url' => url("storage/$path"),
                'original_name' => $filename,
                'mime_type' => $image->getMimeType(),
                'size' => $image->getSize(),
                'is_primary' => $isPrimary
            ];

            $uploadedImages->push($property->images()->create($imageData));
        }

        return $uploadedImages;
    }

    public function uploadPropertyAttachments(int $propertyId, array $attachments): Collection
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            throw new \Exception('Property not found');
        }

        $uploadedAttachments = new Collection();

        foreach ($attachments as $attachment) {
            $path = $attachment->store('properties/attachments', 'public');
            $filename = $attachment->getClientOriginalName();

            $attachmentData = [
                'property_id' => $propertyId,
                'path' => $path,
                'attachment_type' => $attachment->getMimeType(), // Adjust if attachment_type should be something else
                'original_filename' => $filename, // Changed from original_name to match DB schema
                'file_path' => $path,
                'mime_type' => $attachment->getMimeType(),
                'size' => $attachment->getSize()
            ];

            $uploadedAttachments->push($property->attachments()->create($attachmentData));
        }

        return $uploadedAttachments;
    }

    public function deletePropertyImage(int $propertyId, int $imageId): bool
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            return false;
        }

        $image = $property->images()->find($imageId);

        if (!$image) {
            return false;
        }
        

        Storage::delete($image->image_url);
        return $image->delete();
    }

    public function deletePropertyAttachment(int $propertyId, int $attachmentId): bool
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            return false;
        }

        $attachment = $property->attachments()->find($attachmentId);

        if (!$attachment) {
            return false;
        }

        Storage::delete($attachment->file_path);
        return $attachment->delete();
    }
}