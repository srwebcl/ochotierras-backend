<?php

namespace App\Observers;

use App\Models\Product;
use App\Jobs\TranslateProduct;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "saved" event.
     * Use saved to catch both created and updated.
     */
    public function saved(Product $product): void
    {
        // Check if any English field is empty. If so, trigger translation.
        $needsTranslation = empty($product->name_en) ||
            empty($product->description_en) ||
            empty($product->short_description_en) ||
            empty($product->tasting_notes_en) ||
            empty($product->pairing_en);

        if ($needsTranslation) {
            // We dispatch the job to the queue. 
            // If QUEUE_CONNECTION=sync (default in dev), it runs immediately.
            try {
                TranslateProduct::dispatch($product);
            } catch (\Exception $e) {
                Log::error("Failed to dispatch translation job: " . $e->getMessage());
            }
        }
    }
}
