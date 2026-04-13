<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBanner extends Model
{
    protected $fillable = [
        'pre_title',
        'title',
        'subtitle',
        'highlighted_text',
        'cta_text',
        'cta_link',
        'image',
        'mobile_image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
