<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        // Entero tanto para % (ej. 10) como para montos fijos en CLP (ej. 5000);
        // evita que Filament muestre "10.00" o "5000.00"
        'discount_value' => 'integer',
    ];
}
