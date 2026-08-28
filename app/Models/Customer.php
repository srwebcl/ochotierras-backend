<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Vista de solo lectura sobre `orders`, agrupada por email de comprador.
 * No hay tabla ni cuenta de cliente real (el checkout es de invitado) —
 * ver la migración create_customers_view. No usar create()/save() acá.
 */
class Customer extends Model
{
    protected $table = 'customers';

    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'orders_count' => 'integer',
        'total_spent' => 'integer',
        'first_order_at' => 'datetime',
        'last_order_at' => 'datetime',
    ];
}
