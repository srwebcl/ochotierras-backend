<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\HeroSection;
use App\Models\Category;

Route::get('/categories-wines', function () {
    return Category::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->with([
            'products' => function ($query) {
                $query->where('is_active', true);
            }
        ])
        ->get()
        ->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'nameEn' => $category->name_en,
                'slug' => $category->slug,
                'wines' => $category->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'nameEn' => $product->name_en,
                        'subtitle' => $product->subtitle,
                        'slug' => $product->slug,
                        'image' => $product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : null,
                        'price' => (int) $product->price,
                        'stock' => (int) $product->stock,
                        'technical_sheet' => $product->technical_sheet ? \Illuminate\Support\Facades\Storage::url($product->technical_sheet) : null,
                        'harvest_year' => $product->harvest_year,
                        'harvest_type' => $product->harvest_type,
                        'origin' => $product->origin,
                        'vineyard_location' => $product->vineyard_location,
                        'presentation' => $product->presentation,
                        'closure_type' => $product->closure_type,
                        'varietal_composition' => $product->varietal_composition,
                        'aging_potential' => $product->aging_potential,
                        'wood_type' => $product->wood_type,
                        'alcohol' => $product->alcohol,
                        'residual_sugar' => $product->residual_sugar,
                        'total_ph' => $product->total_ph,
                        'volatile_acidity' => $product->volatile_acidity,
                        'total_acidity' => $product->total_acidity,
                        'tasting_notes' => $product->tasting_notes,
                        'tastingNotesEn' => $product->tasting_notes_en,
                        'awards' => $product->awards,
                        'description' => $product->description,
                        'descriptionEn' => $product->description_en,
                        'bgGradient' => $product->type === 'Tinto'
                            ? "radial-gradient(circle at center, #5e0916 0%, transparent 70%)"
                            : ($product->type === 'Blanco'
                                ? "radial-gradient(circle at center, #ffd700 0%, transparent 70%)"
                                : "radial-gradient(circle at center, #2a2a2a 0%, transparent 70%)"),
                        'accentColorHex' => $product->accent_color ?? '#D4AF37',
                    ];
                })
            ];
        });
});

Route::get('/hero-section', function () {
    $heroes = HeroSection::where('is_active', true)->orderBy('sort_order', 'asc')->get();

    if ($heroes->isEmpty()) {
        return null;
    }

    return $heroes->map(function ($hero) {
        $images = $hero->images ?? [];
        $imageUrls = array_map(fn($img) => \Illuminate\Support\Facades\Storage::url($img), $images);

        return [
            'title' => $hero->title,
            'titleEn' => $hero->title_en,
            'subtitle' => $hero->subtitle,
            'subtitleEn' => $hero->subtitle_en,
            'description' => $hero->description,
            'buttonText' => $hero->button_primary_text,
            'buttonTextEn' => $hero->button_primary_text_en,
            'buttonPrimaryUrl' => $hero->button_primary_url,
            'buttonSecondaryText' => $hero->button_secondary_text,
            'buttonSecondaryTextEn' => $hero->button_secondary_text_en,
            'buttonSecondaryUrl' => $hero->button_secondary_url,
            'image' => $imageUrls[0] ?? null,
            'images' => $imageUrls,
        ];
    });
});

Route::get('/collection-wines', function () {
    return \App\Models\Product::where('is_active', true)
        ->where('is_featured', true)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'nameEn' => $product->name_en,
                'subtitle' => $product->subtitle,
                'subtitleEn' => $product->subtitle_en,
                'type' => $product->type,
                'price' => (int) $product->price,
                'stock' => (int) $product->stock,
                'image' => $product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : null,
                'bgGradient' => $product->type === 'Tinto'
                    ? "radial-gradient(circle at center, #5e0916 0%, transparent 70%)"
                    : ($product->type === 'Blanco'
                        ? "radial-gradient(circle at center, #ffd700 0%, transparent 70%)"
                        : "radial-gradient(circle at center, #2a2a2a 0%, transparent 70%)"),
                'accentColor' => 'text-brand-gold',
                'accentColorHex' => $product->accent_color ?? '#D4AF37',
                'description' => $product->featured_description ?? strip_tags($product->description),
                'descriptionEn' => $product->short_description_en ?? $product->description_en,
                'slug' => $product->slug,
            ];
        });
});

Route::get('/products', function () {
    return \App\Models\Product::where('is_active', true)
        ->where('is_pack', false) // Exclude packs
        ->with('category')
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'nameEn' => $product->name_en,
                'subtitle' => $product->subtitle,
                'subtitleEn' => $product->subtitle_en,
                'type' => $product->type,
                'category_name' => $product->category ? $product->category->name : null,
                'category_slug' => $product->category ? $product->category->slug : null,
                'price' => (int) $product->price,
                'stock' => (int) $product->stock,
                'image' => $product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : null,
                'bgGradient' => $product->type === 'Tinto'
                    ? "radial-gradient(circle at center, #5e0916 0%, transparent 70%)"
                    : ($product->type === 'Blanco'
                        ? "radial-gradient(circle at center, #ffd700 0%, transparent 70%)"
                        : "radial-gradient(circle at center, #2a2a2a 0%, transparent 70%)"),
                'accentColor' => 'text-brand-gold',
                'accentColorHex' => $product->accent_color ?? '#D4AF37',
                'description' => $product->description,
                'descriptionEn' => $product->description_en,
                'slug' => $product->slug,
                'gallery' => $product->gallery ? array_map(fn($img) => \Illuminate\Support\Facades\Storage::url($img), $product->gallery) : [],
                'technical_details' => $product->technical_details,
                'technical_sheet' => $product->technical_sheet ? \Illuminate\Support\Facades\Storage::url($product->technical_sheet) : null,
                'vintage_year' => $product->vintage_year,
                'strain' => $product->strain,
                'origin' => $product->origin,
                'tastingNotesEn' => $product->tasting_notes_en,
                'pairingEn' => $product->pairing_en,
                'serviceTempEn' => $product->service_temp_en,
            ];
        });
});

Route::get('/packs', function () {
    return \App\Models\Product::where('is_active', true)
        ->where('is_pack', true) // Only packs
        ->with('bundleItems') // Includes pivot data
        ->get()
        ->map(function ($product) {
            // Calculate virtual stock based on components
            $minComponentStock = null;
            if ($product->bundleItems->isNotEmpty()) {
                foreach ($product->bundleItems as $component) {
                    $available = floor($component->stock / max(1, $component->pivot->quantity));
                    if ($minComponentStock === null || $available < $minComponentStock) {
                        $minComponentStock = $available;
                    }
                }
            } else {
                $minComponentStock = 0;
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'nameEn' => $product->name_en,
                'subtitle' => $product->subtitle,
                'subtitleEn' => $product->subtitle_en,
                'price' => (int) $product->price,
                // Use calculated stock for display, but fallback to manual stock if 0/null to avoid errors if logic fails
                'stock' => (int) max(0, $minComponentStock),
                'image' => $product->image ? \Illuminate\Support\Facades\Storage::url($product->image) : null,
                'description' => $product->description,
                'descriptionEn' => $product->description_en,
                'slug' => $product->slug,
                // Expanded includes for dynamic frontend rendering
                'includes' => $product->bundleItems->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->pivot->quantity,
                    'image' => $item->image ? \Illuminate\Support\Facades\Storage::url($item->image) : null,
                    'technical_details' => $item->technical_details,
                    'tasting_notes' => $item->tasting_notes,
                    'tasting_notes_en' => $item->tasting_notes_en,
                    'strain' => $item->strain,
                    'vintage_year' => $item->vintage_year,
                ]),
            ];
        });
});

Route::get('/categories', function () {
    return Category::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'nameEn' => $category->name_en,
                'slug' => $category->slug,
            ];
        });
});

Route::post('/payment/init', [App\Http\Controllers\PaymentController::class, 'init']);
Route::get('/payment/confirm-mock', [App\Http\Controllers\PaymentController::class, 'confirmMock']);

Route::post('/shipping/calculate', function (Request $request) {
    $request->validate([
        'region' => 'required|string',
    ]);

    $region = $request->input('region');
    $zones = \App\Models\ShippingZone::where('is_active', true)->get();

    $matchedZone = $zones->first(function ($zone) use ($region) {
        return is_array($zone->regions) && in_array($region, $zone->regions);
    });

    if ($matchedZone) {
        return response()->json([
            'available' => true,
            'zone_name' => $matchedZone->name,
            'price' => $matchedZone->is_free_shipping ? 0 : (int) $matchedZone->price,
            'message' => $matchedZone->is_free_shipping ? 'Envío Gratis' : null,
        ]);
    }

    return response()->json([
        'available' => false,
        'message' => 'No tenemos cobertura de envío para esta región actualmente.',
        'price' => 0,
    ]);
});

Route::get('/store-banners', [App\Http\Controllers\Api\StoreBannerController::class, 'index']);
