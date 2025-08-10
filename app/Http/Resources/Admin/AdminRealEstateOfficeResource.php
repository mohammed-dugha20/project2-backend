<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRealEstateOfficeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'commercial_name' => $this->commercial_name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'license_number' => $this->license_number,
            'profile_description' => $this->profile_description,
            'is_active' => $this->is_active,

            'user' => [
                'id' => $this->user->id ?? null,
                'username' => $this->user->username ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
                'phone' => $this->user->phone ?? null,
            ],
            'documents' => $this->documents,
            'properties_count' => $this->properties->count(),
            'documents_count' => $this->documents->count(),

            'reviews_count' => $this->reviews->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 