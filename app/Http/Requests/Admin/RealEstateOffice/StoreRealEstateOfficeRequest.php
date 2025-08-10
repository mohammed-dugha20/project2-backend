<?php

namespace App\Http\Requests\Admin\RealEstateOffice;

use Illuminate\Foundation\Http\FormRequest;

class StoreRealEstateOfficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'user' => 'required|array',
            'user.username' => 'string|max:255',
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|string|max:20',
            'user.password' => 'required|string|min:8',
            'commercial_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:255',
            'profile_description' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
} 