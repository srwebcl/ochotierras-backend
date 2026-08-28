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
        'coupon_code',
        'discount_amount',
        'abandoned_email_sent',
        'abandoned_email_resend_id',
        'customer_rut',
        'document_type'
    ];

    protected $casts = [
        // CLP no usa decimales; evita que Filament muestre "60000.00"
        'total_amount' => 'integer',
        'discount_amount' => 'integer',
        'marketing_opt_in' => 'boolean',
        'abandoned_email_sent' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
