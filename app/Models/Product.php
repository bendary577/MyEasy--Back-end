<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Store;
use Laravel\Scout\Searchable;
use Models\CustomerProfile;

class Product extends Model
{
    use HasFactory, Searchable;

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

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customerProfile(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CustomerProfile::class);
    }

    public function searchableAs()
    {
        return 'products';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $data = [
            'name' => $array['name'],
            'description' => $array['description'],
        ];
        
        return $data;
    }
}
