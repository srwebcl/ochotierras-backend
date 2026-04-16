<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'address_shipping',
        'total_amount',
        'status',
        'courier_name',
        'tracking_number',
        'site_transaction_id',
        'payment_id',
        'marketing_opt_in',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
