<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    use HasFactory;

    protected $table = 'users';

    /*protected $fillable = [
        'admin_name',
    ];
    */

    public static function boot(){
        parent::boot();

        static::addGlobalScope(function(Builder $builder) {
            $builder->where('type', 'admin');
        });
        
        static::creating(function ($admin) {
            $admin->type = 'admin';
        }); 
    }


}
