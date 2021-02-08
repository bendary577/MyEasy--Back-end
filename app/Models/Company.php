<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Seller
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
    ];

}
