<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class GoogleTranslationService
{
    protected $translator;

    public function __construct()
    {
        $this->translator = new GoogleTranslate();
        $this->translator->setSource('es');
        $this->translator->setTarget('en');
    }

    /**
     * Translate a given text from Spanish to English.
     *
     * @param string|null $text
     * @return string|null
     */
    public function translate(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }

        try {
            return $this->translator->translate($text);
        } catch (\Exception $e) {
            Log::error("Translation Error: " . $e->getMessage());
            return $text; // Return original text on failure to avoid data loss
        }
    }
}
