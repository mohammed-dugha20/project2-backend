<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderService extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceProviderServiceFactory> */
    use HasFactory;
    protected $table = 'service_provider_services';
    protected $fillable = [
        'service_provider_id',
        'service_type',
    ];
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id');
    }
}
