<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = [
        'name',
        'regions',
        'price',
        'is_free_shipping',
        'is_active',
    ];

    protected $casts = [
        'regions' => 'array',
        'is_free_shipping' => 'boolean',
        'is_active' => 'boolean',
    ];
}
