<?php

namespace App\Http\Requests\Admin\FinishingCompany;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinishingCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'user' => 'required|array',
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|string|max:20',
            'user.password' => 'required|string|min:8',
            'commercial_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'profile_description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user.required' => 'User information is required.',
            'user.name.required' => 'User name is required.',
            'user.email.required' => 'User email is required.',
            'user.email.email' => 'User email must be a valid email address.',
            'user.email.unique' => 'User email already exists.',
            'user.phone.required' => 'User phone is required.',
            'user.password.required' => 'User password is required.',
            'user.password.min' => 'User password must be at least 8 characters.',
            'commercial_name.required' => 'Commercial name is required.',
            'contact_info.required' => 'Contact information is required.',
        ];
    }
} 