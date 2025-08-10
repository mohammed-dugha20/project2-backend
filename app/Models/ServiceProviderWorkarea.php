<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderWorkarea extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceProviderWorkareaFactory> */
    use HasFactory;
    protected $table = 'service_provider_workareas';
    protected $fillable = [
        'provider_id',
        'location_id',
    ];
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
