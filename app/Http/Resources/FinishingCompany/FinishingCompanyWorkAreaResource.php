<?php

namespace App\Http\Resources\FinishingCompany;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinishingCompanyWorkAreaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => [
                'id' => $this->location->id,
                'city' => $this->location->city,
                'neighborhood' => $this->location->neighborhood,
                'region' => $this->location->region,
                'address_details' => $this->location->address_details,
            ],
            'created_at' => $this->created_at,
        ];
    }
} 