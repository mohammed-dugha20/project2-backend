<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderRating extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceProviderRatingFactory> */
    use HasFactory;
    protected $table = 'service_provider_ratings';
    protected $fillable = [
        'service_provider_id',
        'rating',
        'comment',
    ];
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id');
    }
}
