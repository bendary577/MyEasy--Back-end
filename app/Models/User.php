<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, /*HasRoles,*/ SoftDeletes;

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

    protected $appends = ['avatar_url'];

    /* -------------------------------- profiles ----------------------------------- */

    protected $with = ['profile'];
    
    private $profile_type;

    public function profile()
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

    /*---------------------- get avatar url of user -------------------------------*/
    public function getAvatarUrlAttribute()
    {
        return Storage::url('avatars/'.$this->id.'/'.$this->avatar);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordRequestNotification($token));
    }

    //define the channel where the users notifications start going through
    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.' . $this->id;
    }

}
