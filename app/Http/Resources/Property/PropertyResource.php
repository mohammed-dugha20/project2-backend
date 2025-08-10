<?php

namespace App\Http\Resources\Property;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'price' => $this->price,
            'area' => $this->area,
            'rooms' => $this->rooms,
            'legal_status' => $this->legal_status,
            'offer_type' => $this->offer_type,
            'status' => $this->whenLoaded('status', function() {
                return [
                    'id' => $this->status->id,
                    'name' => $this->status->status_name
                ];
            }),
            'contact_visible' => $this->contact_visible,
            'office' => $this->whenLoaded('office', function() {
                return [
                    'id' => $this->office->id,
                    'name' => $this->office->commercial_name
                ];
            }),
            'location' => $this->whenLoaded('location', function() {
                return [
                    'id' => $this->location->id,
                    'city' => $this->location->city,
                    'neighborhood' => $this->location->neighborhood,
                    'region' => $this->location->region,
                    'address_details' => $this->location->address_details
                ];
            }),
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
            'attachments' => PropertyAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
} 