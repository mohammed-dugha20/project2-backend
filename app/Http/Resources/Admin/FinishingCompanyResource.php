<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinishingCompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'commercial_name' => $this->commercial_name,
            'contact_info' => $this->contact_info,
            'profile_description' => $this->profile_description,
            'is_active' => $this->is_active,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'created_at' => $this->user->created_at,
            ],
            'services_count' => $this->services_count ?? $this->services->count(),
            'work_areas_count' => $this->work_areas_count ?? $this->workAreas->count(),
            'portfolio_count' => $this->portfolio_count ?? $this->portfolio->count(),
            'requests_count' => $this->finishing_requests_count ?? 0,
            'reviews_count' => $this->reviews_count ?? 0,
            'average_rating' => $this->average_rating ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 