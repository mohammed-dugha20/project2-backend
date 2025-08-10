<?php

namespace App\Http\Resources\FinishingCompany;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinishingCompanyServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
} 