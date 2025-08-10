<?php

namespace App\Http\Requests\Property;

use App\Http\Requests\BaseApiRequest;

class UpdatePropertyRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:apartment,villa,land,office,commercial',
            'price' => 'sometimes|required|numeric|min:0',
            'area' => 'sometimes|required|numeric|min:0',
            'rooms' => 'sometimes|required|integer|min:0',
            'legal_status' => 'sometimes|required|in:registered,pending,customary',
            'offer_type' => 'sometimes|required|in:sale,rent',
            'status_id' => 'sometimes|required|exists:statuses,id',
            'contact_visible' => 'sometimes|required|boolean',
            'real_estate_office_id' => 'sometimes|required|exists:real_estate_offices,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx|max:5120'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The property title is required.',
            'description.required' => 'The property description is required.',
            'type.required' => 'The property type is required.',
            'type.in' => 'The property type must be one of: apartment, villa, land, office, commercial.',
            'price.required' => 'The property price is required.',
            'price.numeric' => 'The property price must be a number.',
            'price.min' => 'The property price must be greater than or equal to 0.',
            'area.required' => 'The property area is required.',
            'area.numeric' => 'The property area must be a number.',
            'area.min' => 'The property area must be greater than or equal to 0.',
            'rooms.required' => 'The number of rooms is required.',
            'rooms.integer' => 'The number of rooms must be an integer.',
            'rooms.min' => 'The number of rooms must be greater than or equal to 0.',
            'legal_status.required' => 'The legal status is required.',
            'legal_status.in' => 'The legal status must be one of: registered, pending, customary.',
            'offer_type.required' => 'The offer type is required.',
            'offer_type.in' => 'The offer type must be either sale or rent.',
            'status_id.required' => 'The status is required.',
            'status_id.exists' => 'The selected status is invalid.',
            'contact_visible.required' => 'The contact visibility setting is required.',
            'contact_visible.boolean' => 'The contact visibility must be either true or false.',
            'real_estate_office_id.required' => 'The real estate office is required.',
            'real_estate_office_id.exists' => 'The selected real estate office is invalid.',
            'location_id.required' => 'The location is required.',
            'location_id.exists' => 'The selected location is invalid.',
            'images.*.image' => 'The file must be an image.',
            'images.*.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'images.*.max' => 'The image may not be greater than 2MB.',
            'attachments.*.file' => 'The file must be a valid file.',
            'attachments.*.mimes' => 'The attachment must be a file of type: pdf, doc, docx.',
            'attachments.*.max' => 'The attachment may not be greater than 5MB.'
        ];
    }
} 