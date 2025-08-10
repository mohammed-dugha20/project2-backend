<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRequestFactory> */
    use HasFactory;
    protected $table = 'service_requests';
    protected $fillable = [
        'service_type',
        'description',
        'budget',
        'customer_id',
        'provider_id',
        'location_id',
        'status_id',
    ];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    
}
