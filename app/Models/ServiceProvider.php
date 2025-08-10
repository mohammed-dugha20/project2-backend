<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceProvider extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceProviderFactory> */
    use HasFactory;
    protected $table = 'service_providers';
    protected $fillable = [
        'professional_name',
        'license_info',
        'availability',
        'is_active',
        'user_id',
    ];

    /**
     * Get the user that owns the service provider.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the services offered by the service provider.
     */
    public function services(): HasMany
    {
        return $this->hasMany(ServiceProviderService::class, 'provider_id');
    }

    /**
     * Get the work areas for the service provider.
     */
    public function workareas(): HasMany
    {
        return $this->hasMany(ServiceProviderWorkarea::class, 'provider_id');
    }

    /**
     * Get the portfolio items for the service provider.
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(ServiceProviderPortfolio::class, 'provider_id');
    }

    /**
     * Get the ratings for the service provider.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(ServiceProviderRating::class, 'service_provider_id');
    }

    /**
     * Get the service requests associated with the service provider.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'provider_id');
    }
}
