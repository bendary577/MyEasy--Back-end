<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'specilization',
        'customers_number',
        'orders_number',
        'badge',
        'delivery_speed',
        'has_store',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function stores(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne('App\Models\User', 'profile');
    }


}
