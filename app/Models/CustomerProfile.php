<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Product;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'birth_date'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function user(){ 
        return $this->morphOne('App\Models\User', 'profile');
    }

}