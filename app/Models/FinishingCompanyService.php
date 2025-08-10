<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingCompanyService extends Model
{
    /** @use HasFactory<\Database\Factories\FinishingCompanyServiceFactory> */
    use HasFactory;
    protected $table = 'finishing_company_services';
    protected $fillable = [
        'company_id',
        'service_type',
        'description',
    ];
    public function company()
    {
        return $this->belongsTo(FinishingCompany::class, 'company_id');
    }
    
}
