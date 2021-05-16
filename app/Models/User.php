<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'first_name',
        'second_name',
        'email',
        'password',
        'phone_number',
        'address',
        'zipcode',
        'avatar',
        'photo_path',
        'bio',
        'type',
        'is_blocked',
        'account_activated',
        'activation_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'activation_token'
    ];

    protected $dates = ['deleted_at'];

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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordRequestNotification($token));
    }

}
