<?php

namespace App\Http\Requests\Admin\Dispute;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,investigating,resolved,closed',
            'resolution_notes' => 'nullable|string',
        ];
    }
} 