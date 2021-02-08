<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Item;

class Customer extends User
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'birth_date'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function item()
    {
        return $this->hasMany(Item::class);
    }
}
