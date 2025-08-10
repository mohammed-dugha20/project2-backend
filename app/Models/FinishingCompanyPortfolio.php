<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingCompanyPortfolio extends Model
{
    /** @use HasFactory<\Database\Factories\FinishingCompanyPortfolioFactory> */
    use HasFactory;
    protected $table = 'finishing_company_portfolios';
    protected $fillable = [
        'image_url', 'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(FinishingCompany::class, 'company_id');
    }
    
}
