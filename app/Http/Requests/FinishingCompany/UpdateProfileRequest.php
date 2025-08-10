<?php

namespace App\Http\Requests\FinishingCompany;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isFinishingCompany();
    }

    public function rules(): array
    {
        return [
            'commercial_name' => 'sometimes|required|string|max:255',
            'contact_info' => 'sometimes|required|string|max:255',
            'profile_description' => 'sometimes|required|string',
            'services' => 'sometimes|array',
            'services.*.service_type' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'work_areas' => 'sometimes|array',
            'work_areas.*' => 'required|exists:locations,id',
        ];
    }

    public function messages(): array
    {
        return [
            'commercial_name.required' => 'The commercial name is required.',
            'contact_info.required' => 'The contact information is required.',
            'profile_description.required' => 'The profile description is required.',
            'services.array' => 'Services must be an array.',
            'services.*.service_type.required' => 'Service type is required.',
            'services.*.description.required' => 'Service description is required.',
            'work_areas.array' => 'Work areas must be an array.',
            'work_areas.*.exists' => 'Selected work area is invalid.',
        ];
    }
} 