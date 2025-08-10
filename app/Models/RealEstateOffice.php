<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RealEstateOffice extends Model
{
    /** @use HasFactory<\Database\Factories\RealEstateOfficeFactory> */
    use HasFactory;
    protected $table = 'real_estate_offices';
    protected $fillable = [
        'user_id',
        'commercial_name',
        'address',
        'phone_number',
        'license_number',
        'profile_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OfficeDocument::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(OfficeReview::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
