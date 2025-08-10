<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyAttachment extends Model
{
    protected $fillable = [
        'property_id',
        'attachment_type',
        'file_path',
        'original_filename',
        'mime_type',
        'description',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
} 