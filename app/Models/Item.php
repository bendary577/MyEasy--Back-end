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
        'store',
        'name',
        'price',
        'category',
        'add',
        'available',
        'photo_path'
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
