<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeDocument extends Model
{
    protected $fillable = [
        'real_estate_office_id',
        'document_type',
        'file_path',
        'original_filename',
        'mime_type',
        'description',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(RealEstateOffice::class, 'real_estate_office_id');
    }
} 