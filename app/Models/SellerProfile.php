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
        'specialization',
        'customers_number',
        'orders_number',
        'delivery_speed',
        'has_store',
        'badge'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function user(){
        return $this->morphOne(User::class, 'profile');
    }

    public function complaint()
    {
        return $this->hasMany(Complaint::class);
    }

}
