<?php

namespace App\Http\Requests\Property;

use App\Http\Requests\BaseApiRequest;

class UpdatePropertyStatusRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'status_id' => 'required|integer|exists:statuses,id'
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
            'status.required' => 'The property status is required.',
            'status.in' => 'The property status must be one of: available, rented, sold, under_maintenance.'
        ];
    }
} 