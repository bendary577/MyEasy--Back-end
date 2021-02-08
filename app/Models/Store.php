<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'name',
        'creation',
        'specilization',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function item()
    {
        return $this->hasMany(Item::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
