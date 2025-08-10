<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;
    protected $table = 'properties';
    protected $fillable = [
        'title',
        'description',
        'type',
        'price',
        'area',
        'rooms',
        'legal_status',
        'offer_type',
        'status_id',
        'contact_visible',
        'user_id',
        'real_estate_office_id',
        'location_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area' => 'decimal:2',
        'rooms' => 'integer',
        'contact_visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(RealEstateOffice::class, 'real_estate_office_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PropertyAttachment::class);
    }
}
