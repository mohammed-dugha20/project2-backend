<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestAttachement extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRequestAttachementFactory> */
    use HasFactory;
    protected $table = 'service_request_attachments';
    protected $fillable = [
        'service_request_id',
        'attachment_url',
        'attachment_type',
    ];
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }
    
}
