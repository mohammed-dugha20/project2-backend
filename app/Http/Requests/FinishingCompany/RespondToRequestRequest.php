<?php

namespace App\Http\Requests\FinishingCompany;

use Illuminate\Foundation\Http\FormRequest;

class RespondToRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isFinishingCompany();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:accepted,rejected',
            'reason' => 'required_if:status,rejected|string|max:500',
            'estimated_cost' => 'required_if:status,accepted|numeric|min:0',
            'implementation_period' => 'required_if:status,accepted|string|max:255',
            'materials' => 'required_if:status,accepted|string',
            'work_phases' => 'required_if:status,accepted|string',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'The response status is required.',
            'status.in' => 'The status must be either accepted or rejected.',
            'reason.required_if' => 'A reason is required when rejecting a request.',
            'estimated_cost.required_if' => 'Estimated cost is required when accepting a request.',
            'estimated_cost.numeric' => 'Estimated cost must be a number.',
            'estimated_cost.min' => 'Estimated cost must be greater than or equal to 0.',
            'implementation_period.required_if' => 'Implementation period is required when accepting a request.',
            'materials.required_if' => 'Materials description is required when accepting a request.',
            'work_phases.required_if' => 'Work phases description is required when accepting a request.',
        ];
    }
} 