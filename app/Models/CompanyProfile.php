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
        'specialization'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function complaint()
    {
        return $this->hasMany(Complaint::class);
    }
}
