<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    use HasFactory;

    /*protected $fillable = [
        'admin_name',
    ];
    */

    public function user(){ 
        return $this->morphOne('App\Models\User', 'profile');
    }


}
