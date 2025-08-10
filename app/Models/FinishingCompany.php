<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinishingCompany extends Model
{
    /** @use HasFactory<\Database\Factories\FinisihingCompanyFactory> */
    use HasFactory;
    protected $table = 'finishing_companies';
    protected $fillable = [
        'user_id',
        'commercial_name',
        'contact_info',
        'profile_description',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'finishing_company_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(FinishingCompanyService::class);
    }

    public function workAreas(): HasMany
    {
        return $this->hasMany(FinishingCompanyWorkarea::class);
    }

    public function portfolio(): HasMany
    {
        return $this->hasMany(FinishingCompanyPortfolio::class);
    }

    public function finishingRequests(): HasMany
    {
        return $this->hasMany(FinishingRequest::class, 'company_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(FinishingCompanyReview::class);
    }
    public function portfolios(): HasMany
    {
        return $this->hasMany(FinishingCompanyPortfolio::class);
    }
}
