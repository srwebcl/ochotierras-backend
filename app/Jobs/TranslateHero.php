<?php

namespace App\Jobs;

use App\Models\HeroSection;
use App\Services\GoogleTranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateHero implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hero;

    /**
     * Create a new job instance.
     */
    public function __construct(HeroSection $hero)
    {
        $this->hero = $hero;
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleTranslationService $translator): void
    {
        Log::info("Translating Hero Section ID: {$this->hero->id}");

        $fieldsToTranslate = [
            'title' => 'title_en',
            'subtitle' => 'subtitle_en',
            'button_primary_text' => 'button_primary_text_en',
            'button_secondary_text' => 'button_secondary_text_en',
        ];

        $hasChanges = false;

        foreach ($fieldsToTranslate as $source => $target) {
            // Only translate if target is empty and source is not empty
            if (empty($this->hero->{$target}) && !empty($this->hero->{$source})) {
                $translated = $translator->translate($this->hero->{$source});
                if ($translated) {
                    $this->hero->{$target} = $translated;
                    $hasChanges = true;
                }
            }
        }

        if ($hasChanges) {
            $this->hero->saveQuietly();
            Log::info("Hero Section ID: {$this->hero->id} translated successfully.");
        }
    }
}
