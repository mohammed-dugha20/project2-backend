<?php

namespace App\Http\Requests\Property;

use App\Http\Requests\BaseApiRequest;

class UploadPropertyAttachmentsRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attachments.*' => 'required|file|mimes:pdf,doc,docx|max:5120'
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
            'attachments.*.required' => 'The attachment file is required.',
            'attachments.*.file' => 'The file must be a valid file.',
            'attachments.*.mimes' => 'The attachment must be a file of type: pdf, doc, docx.',
            'attachments.*.max' => 'The attachment may not be greater than 5MB.'
        ];
    }
} 