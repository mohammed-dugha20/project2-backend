<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingCompanyReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'finishing_company_id',
        'reviewer_id',
        'rating',
        'comment',
        'is_verified',
    ];

    public function finishingCompany()
    {
        return $this->belongsTo(FinishingCompany::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
} 