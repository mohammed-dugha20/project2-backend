<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeReview extends Model
{
    protected $fillable = [
        'real_estate_office_id',
        'reviewer_id',
        'rating',
        'comment',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(RealEstateOffice::class, 'real_estate_office_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
} 