<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Invoice;
use Models\Store;
use Models\PDF;

class Seller extends User
{
    use HasFactory;

    protected $fillable = [
        'specilization',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    public function stores()
    {
        return $this->hasOne(Store::class);
    }

    public function pdf(){
        return $this->hasOne(PDF::class);
    }
    
}
