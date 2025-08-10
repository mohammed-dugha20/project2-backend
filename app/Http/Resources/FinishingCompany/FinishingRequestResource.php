<?php

namespace App\Http\Resources\FinishingCompany;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinishingRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'area' => $this->area,
            'rooms' => $this->rooms,
            'floor' => $this->floor,
            'status' => [
                'id' => $this->status->id,
                'name' => $this->status->status_name,
            ],
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'email' => $this->customer->email,
                'phone' => $this->customer->phone,
            ],
            'location' => [
                'id' => $this->location->id,
                'city' => $this->location->city,
                'neighborhood' => $this->location->neighborhood,
                'region' => $this->location->region,
                'address_details' => $this->location->address_details,
            ],
            'company_response' => $this->when($this->company_response, [
                'status' => $this->company_response?->status,
                'reason' => $this->company_response?->reason,
                'estimated_cost' => $this->company_response?->estimated_cost,
                'implementation_period' => $this->company_response?->implementation_period,
                'materials' => $this->company_response?->materials,
                'work_phases' => $this->company_response?->work_phases,
                'notes' => $this->company_response?->notes,
                'responded_at' => $this->company_response?->created_at,
            ]),
            'images' => FinishingRequestImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 