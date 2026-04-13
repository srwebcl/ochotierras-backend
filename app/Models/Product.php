<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'subtitle',
        'type',
        'slug',
        'description',
        'featured_description',
        'price',
        'stock',
        'sku',
        'image',
        'accent_color',
        'meta_title',
        'meta_description',
        'is_active',
        'is_featured',
        'category_id',
        'technical_sheet',
        'harvest_year',
        'harvest_type',
        'origin',
        'vineyard_location',
        'presentation',
        'closure_type',
        'varietal_composition',
        'aging_potential',
        'wood_type',
        'alcohol',
        'residual_sugar',
        'total_ph',
        'volatile_acidity',
        'total_acidity',
        'tasting_notes',
        'awards',
        'vintage_year',
        'strain',
        'units_per_box',
        'is_pack',
        'name_en',
        'description_en',
        'short_description_en',
        'tasting_notes_en',
        'pairing_en',
        'service_temp_en',
        'technical_details',
        'gallery',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship for API (simple retrieval)
    public function bundleItems()
    {
        return $this->belongsToMany(Product::class, 'product_bundles', 'bundle_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Relationship for Filament (Form editing via HasMany Repeater)
    public function bundleConnections()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id');
    }

    protected $casts = [
        'is_active' => 'boolean',
        'is_pack' => 'boolean',
        'price' => 'decimal:2',
        'awards' => 'array',
        'technical_details' => 'array',
        'units_per_box' => 'integer',
        'gallery' => 'array',
    ];
}
