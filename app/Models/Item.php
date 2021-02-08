<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Store;
use Models\Customer;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo_path',
        'available_number',
        'price',
        'category',
        'status',
        'customer_cart',
        'store'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsToMany(Customer::class);
    }
}
