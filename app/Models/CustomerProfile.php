<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'birth_date',
        'orders_number'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->morphOne('App\Models\User', 'profile');
    }

    public function complaint()
    {
        return $this->hasMany(Complaint::class);
    }
}
