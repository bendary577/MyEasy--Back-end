<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    use HasFactory;

    public function invoice()
    {
        return $this->hasMeny(Invoice::class);
    }
    
    public function store()
    {
        return $this->hasOne(Store::class);
    }
}
