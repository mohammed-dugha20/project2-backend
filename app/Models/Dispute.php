<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'company_id',
        'company_type', // 'finishing_company' or 'real_estate_office'
        'finishing_request_id',
        'subject',
        'description',
        'status', // 'pending', 'investigating', 'resolved', 'closed'
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the customer who filed the dispute.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the finishing company involved in the dispute.
     */
    public function finishingCompany(): BelongsTo
    {
        return $this->belongsTo(FinishingCompany::class, 'company_id');
    }

    /**
     * Get the real estate office involved in the dispute.
     */
    public function realEstateOffice(): BelongsTo
    {
        return $this->belongsTo(RealEstateOffice::class, 'company_id');
    }

    /**
     * Get the finishing request related to the dispute.
     */
    public function finishingRequest(): BelongsTo
    {
        return $this->belongsTo(FinishingRequest::class);
    }

    /**
     * Get the admin who resolved the dispute.
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'resolved_by');
    }

    /**
     * Scope to get disputes for finishing companies.
     */
    public function scopeFinishingCompany($query)
    {
        return $query->where('company_type', 'finishing_company');
    }

    /**
     * Scope to get disputes by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
} 