<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\GoogleTranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleTranslationService $translator): void
    {
        Log::info("Translating product ID: {$this->product->id}");

        $fieldsToTranslate = [
            'name' => 'name_en',
            'description' => 'description_en',
            'short_description' => 'short_description_en',
            'tasting_notes' => 'tasting_notes_en',
            'pairing' => 'pairing_en',
            'service_temp' => 'service_temp_en'
        ];

        $hasChanges = false;

        foreach ($fieldsToTranslate as $source => $target) {
            // Only translate if target is empty and source is not empty
            // This prevents overwriting manual edits in English
            // OR we could force it if we want full automation, but safer to respect manual EN edits.
            // Current Logic: If EN is empty, translate.
            if (empty($this->product->{$target}) && !empty($this->product->{$source})) {
                $translated = $translator->translate($this->product->{$source});
                if ($translated) {
                    $this->product->{$target} = $translated;
                    $hasChanges = true;
                }
            }
        }

        if ($hasChanges) {
            // Use quiet save to avoid triggering observer again loop (though observer logic should handle it)
            $this->product->saveQuietly();
            Log::info("Product ID: {$this->product->id} translated successfully.");
        } else {
            Log::info("Product ID: {$this->product->id} no translation needed or nothing to translate.");
        }
    }
}
