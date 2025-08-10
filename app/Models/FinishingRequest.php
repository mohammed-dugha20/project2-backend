<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FinishingRequest extends Model
{
    /** @use HasFactory<\Database\Factories\FinishingRequestFactory> */
    use HasFactory;
    protected $table = 'finishing_requests';
    protected $fillable = [
        'customer_id',
        'company_id',
        'location_id',
        'status_id',
        'service_type',
        'description',
        'area',
        'rooms',
        'floor',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(FinishingCompany::class, 'company_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(FinishingRequestImage::class);
    }

    public function companyResponse(): HasOne
    {
        return $this->hasOne(FinishingRequestResponse::class);
    }
    public function response(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(FinishingRequestResponse::class);
    }
}
