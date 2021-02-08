<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'admin_name',
        'admin_age'
    ];

    public static function boot(){
        parent::boot();
        static::addGlobalScope(function ($query) {
            $query->where('is_admin', true);
        });
    }


}
