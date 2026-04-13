<?php

namespace App\Observers;

use App\Models\HeroSection;
use App\Jobs\TranslateHero;
use Illuminate\Support\Facades\Log;

class HeroSectionObserver
{
    /**
     * Handle the HeroSection "saved" event.
     */
    public function saved(HeroSection $hero): void
    {
        $needsTranslation = empty($hero->title_en) ||
            empty($hero->subtitle_en) ||
            empty($hero->button_primary_text_en);

        if ($needsTranslation) {
            try {
                TranslateHero::dispatch($hero);
            } catch (\Exception $e) {
                Log::error("Failed to dispatch Hero translation job: " . $e->getMessage());
            }
        }
    }
}
