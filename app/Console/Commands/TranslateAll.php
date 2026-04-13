<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\HeroSection;
use Illuminate\Console\Command;

class TranslateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:translate-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger translation for all Products and Banners that are missing translations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting bulk translation...');

        // Products
        $products = Product::all();
        $this->info("Checking {$products->count()} products...");
        $pCount = 0;
        foreach ($products as $product) {
            // "Touch" triggers the observer which checks translation needs
            // But touch() only updates updated_at if changed. 
            // We need to re-save to trigger the Observer.
            // Since we are not changing data, just calling save() might not trigger dirty check if nothing changed.
            // However, observers 'saved' fires on save(). Let's force it.

            // To be sure, we can dispatch the jobs directly here if we want, OR
            // we can simulate a save.
            // But better: Let's reuse the Observer logic by just calling it or saving.
            // A simple $product->save() works in Laravel to trigger "saved" even if clean? 
            // Often yes, but let's be safe and dispatch manually if we really want to FORCE it.

            // Actually, the Observer checks if (empty($product->name_en)...).
            // So simply running the Observer logic is enough.
            // Let's manually invoke the observer-like logic or simpler:
            // Just dispatch the job if needed.

            $needsTranslation = empty($product->name_en) ||
                empty($product->description_en) ||
                empty($product->short_description_en);

            if ($needsTranslation) {
                \App\Jobs\TranslateProduct::dispatch($product);
                $pCount++;
            }
        }
        $this->info("Dispatched {$pCount} products for translation.");

        // Hero Sections
        $heroes = HeroSection::all();
        $this->info("Checking {$heroes->count()} banners...");
        $hCount = 0;
        foreach ($heroes as $hero) {
            $needsTranslation = empty($hero->title_en) ||
                empty($hero->subtitle_en) ||
                empty($hero->button_primary_text_en);

            if ($needsTranslation) {
                \App\Jobs\TranslateHero::dispatch($hero);
                $hCount++;
            }
        }
        $this->info("Dispatched {$hCount} banners for translation.");

        $this->info('Done! Make sure your queue worker is running: php artisan queue:work');
    }
}
