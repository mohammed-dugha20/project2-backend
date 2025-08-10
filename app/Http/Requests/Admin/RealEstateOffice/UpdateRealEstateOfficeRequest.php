<?php

namespace App\Http\Requests\Admin\RealEstateOffice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRealEstateOfficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'user' => 'sometimes|array',
            'user.username' => 'sometimes|max:255',
            'user.name' => 'sometimes|required|string|max:255',
            'user.email' => 'sometimes|required|email|unique:users,email',
            'user.phone' => 'sometimes|required|string|max:20',
            'user.password' => 'sometimes|required|string|min:8',
            'commercial_name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|required|string|max:20',
            'is_active' => 'boolean',
        ];
    }
} 