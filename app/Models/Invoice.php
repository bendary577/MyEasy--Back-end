<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Seller;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'item',
        'price',
        'creation',
        'expiration',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

}
