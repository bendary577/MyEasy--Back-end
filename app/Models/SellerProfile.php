<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
    use HasFactory; 

    protected $fillable = [
        'gender',
        'birth_date',
        'specilization',
    ];
   
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    public function stores()
    {
        return $this->hasOne(Store::class);
    }

    public function user(){ 
        return $this->morphOne('App\Models\User', 'profile');
    }



}