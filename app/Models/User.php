<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements 
    JWTSubject
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

    public function profile()
    {
      return $this->morphTo();
    }

    public function getHasAdminProfileAttribute()
    {
      return $this->profile_type == 'App\Models\AdminProfile';
    }

    public function getHasCustomerProfileAttribute()
    {
      return $this->profile_type == 'App\Models\CustomerProfile';
    }

    public function getHasSellerProfileAttribute()
    {
      return $this->profile_type == 'App\Models\SellerProfile';
    }

    public function getHasCompanyProfileAttribute()
    {
      return $this->profile_type == 'App\Models\CompanyProfile';
    }

    /* ------------------------------- jwt ---------------------------- */
    public function getJWTIdentifier()             //return the JWTIdentifier 
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()           //used in generating the JWT token
    {
      return [];
    }

    
}
