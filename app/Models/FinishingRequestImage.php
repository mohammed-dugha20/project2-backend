<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingRequestImage extends Model
{
    /** @use HasFactory<\Database\Factories\FinishingRequestImageFactory> */
    use HasFactory;
    protected $table = 'finishing_request_images';
    protected $fillable = [
        'finishing_request_id',
        'image_url',
    ];
    public function request()
    {
        return $this->belongsTo(FinishingRequest::class, 'finishing_request_id');
    }
}
