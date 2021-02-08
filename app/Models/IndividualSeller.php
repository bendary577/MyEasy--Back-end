<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualSeller extends Seller
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'birth_date',
    ];

}
