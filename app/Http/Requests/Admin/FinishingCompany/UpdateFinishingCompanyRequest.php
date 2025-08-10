<?php

namespace App\Http\Requests\Admin\FinishingCompany;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFinishingCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        $userId = $this->route('id');
        
        return [
            'user' => 'sometimes|array',
            'user.name' => 'sometimes|required|string|max:255',
            'user.email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id')
            ],
            'user.phone' => 'sometimes|required|string|max:20',
            'commercial_name' => 'sometimes|required|string|max:255',
            'contact_info' => 'sometimes|required|string|max:255',
            'profile_description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user.name.required' => 'User name is required.',
            'user.email.required' => 'User email is required.',
            'user.email.email' => 'User email must be a valid email address.',
            'user.email.unique' => 'User email already exists.',
            'user.phone.required' => 'User phone is required.',
            'commercial_name.required' => 'Commercial name is required.',
            'contact_info.required' => 'Contact information is required.',
        ];
    }
} 