<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyRequest extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyRequestFactory> */
    use HasFactory;
    protected $table = 'property_requests';
    protected $fillable = [
        'customer_id',
        'property_id',
        'status_id',
        'description',
        'budget',
        'request_type'
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
