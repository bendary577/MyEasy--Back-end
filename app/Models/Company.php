<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends User
{
    use HasFactory;

    protected $fillable = [
        'specilization'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->hasMeny(Invoice::class);
    }
    
    public function store()
    {
        return $this->hasOne(Store::class);
    }
}
