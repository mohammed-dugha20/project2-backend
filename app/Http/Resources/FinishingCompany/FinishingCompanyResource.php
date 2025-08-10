<?php

namespace App\Http\Resources\FinishingCompany;

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
            ],
            'services' => FinishingCompanyServiceResource::collection($this->whenLoaded('services')),
            'work_areas' => FinishingCompanyWorkAreaResource::collection($this->whenLoaded('workAreas')),
            'portfolio' => FinishingCompanyPortfolioResource::collection($this->whenLoaded('portfolio')),
            'average_rating' => $this->when($this->reviews_count, $this->average_rating),
            'reviews_count' => $this->when($this->reviews_count, $this->reviews_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 