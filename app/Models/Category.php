<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'specilization',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function store(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Store::class);
    }
}

