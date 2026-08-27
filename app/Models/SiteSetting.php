<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'schedule',
        'schedule_en',
        'location',
        'location_en',
        'phone_whatsapp',
        'whatsapp_only',
        'email',
        'sales_contacts',
    ];

    protected $casts = [
        'sales_contacts' => 'array',
    ];

    /**
     * Es un singleton: siempre hay una única fila (id = 1).
     * Si por alguna razón no existe, la crea vacía en vez de romper.
     */
    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }
}
