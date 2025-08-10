<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingCompanyWorkarea extends Model
{
    /** @use HasFactory<\Database\Factories\FinishingCompanyWorkareaFactory> */
    use HasFactory;
    protected $table = 'finishing_company_workareas';
    protected $fillable = [
        'company_id',
        'location_id',
    ];
    public function company()
    {
        return $this->belongsTo(FinishingCompany::class, 'company_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
