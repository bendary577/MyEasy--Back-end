<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends User
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'birth'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
