<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinishingRequestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'finishing_request_id',
        'status', // 'accepted' or 'rejected'
        'reason',
        'estimated_cost',
        'implementation_period',
        'materials',
        'work_phases',
        'notes',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
    ];

    /**
     * Get the finishing request that this response belongs to.
     */
    public function finishingRequest(): BelongsTo
    {
        return $this->belongsTo(FinishingRequest::class);
    }
} 