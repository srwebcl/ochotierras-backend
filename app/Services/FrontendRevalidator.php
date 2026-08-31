<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Le avisa al frontend (Next.js) que refresque su caché apenas se guarda
 * algo editable desde el panel — sin esto, un cambio (precio, teléfono del
 * footer, banner principal, etc.) tarda hasta 1 hora en verse en el sitio
 * público porque el frontend cachea esas respuestas ese tiempo.
 *
 * Si el frontend no responde o el secreto no está configurado, no rompe
 * nada — el dato ya quedó guardado en la base, solo se ve un poco más
 * tarde (hasta que se cumpla la hora de caché, como antes de esto).
 */
class FrontendRevalidator
{
    public static function tag(string $tag): void
    {
        $secret = config('services.frontend.revalidate_secret');
        $url = config('services.frontend.url');

        if (!$secret || !$url) {
            return;
        }

        try {
            Http::timeout(5)->post(rtrim($url, '/') . '/api/revalidate', [
                'secret' => $secret,
                'tag' => $tag,
            ]);
        } catch (\Throwable $e) {
            Log::warning("No se pudo avisar al frontend que revalide el tag '{$tag}': " . $e->getMessage());
        }
    }
}
