<?php

namespace App\Http\Resources\FinishingCompany;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinishingRequestImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
        ];
    }
} 