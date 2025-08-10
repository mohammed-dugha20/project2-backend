<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderPortfolio extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceProviderPortfolioFactory> */
    use HasFactory;
    protected $table = 'service_provider_portfolios';
    protected $fillable = [
        'provider_id',
        'image_url',
    ];

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }
}
