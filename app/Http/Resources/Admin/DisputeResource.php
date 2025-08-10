<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisputeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_type' => $this->company_type,
            'company_id' => $this->company_id,
            'finishing_company' => $this->whenLoaded('finishingCompany'),
            'real_estate_office' => $this->whenLoaded('realEstateOffice'),
            'finishing_request' => $this->whenLoaded('finishingRequest'),
            'customer' => $this->whenLoaded('customer'),
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status,
            'resolution_notes' => $this->resolution_notes,
            'resolved_by' => $this->whenLoaded('resolvedBy'),
            'resolved_at' => $this->resolved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 