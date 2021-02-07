<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $table = "invoices";

    protected $fillable = [
        'owner',
        'item',
        'price',
        'creation',
        'expiration',
        'created_at',
        'updated_at'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}