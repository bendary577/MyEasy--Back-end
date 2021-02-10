<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'address',
        'zipcode',
        'photo_path',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* -------------------------------- profiles ----------------------------------- */

    protected $with = ['profile'];
    /**
     * @var mixed
     */
    private $profile_type;

    public function profile(): MorphTo
    {
      return $this->morphTo();
    }

    public function getHasAdminProfileAttribute(): bool
    {
      return $this->profile_type == 'App\Models\AdminProfile';
    }

    public function getHasCustomerProfileAttribute(): bool
    {
      return $this->profile_type == 'App\Models\CustomerProfile';
    }

    public function getHasSellerProfileAttribute(): bool
    {
      return $this->profile_type == 'App\Models\SellerProfile';
    }

    public function getHasCompanyProfileAttribute(): bool
    {
      return $this->profile_type == 'App\Models\CompanyProfile';
    }

    /* ------------------------------- jwt authentication ---------------------------- */
    public function getJWTIdentifier()             //return the JWTIdentifier
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims(): array           //used in generating the JWT token
    {
      return [];
    }


}
