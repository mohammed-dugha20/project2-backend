<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'date',
        'password' => 'hashed',
    ];

    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    public function isRealEstateOffice(): bool
    {
        return $this->user_type === 'real_estate_office';
    }

    public function isFinishingCompany(): bool
    {
        return $this->user_type === 'finishing_company';
    }

    public function isServiceProvider(): bool
    {
        return $this->user_type === 'service_provider';
    }
    public function isAdmin(): bool
    {
        return $this->user_type === 'platform_admin';
    }

    // Relationships
    public function realEstateOffice()
    {
        return $this->hasOne(RealEstateOffice::class);
    }

    public function finishingCompany()
    {
        return $this->hasOne(FinishingCompany::class);
    }
}
