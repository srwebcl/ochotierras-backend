<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            // Forzamos HTTPS y Dominio SIEMPRE en producción
            URL::forceScheme('https');
            URL::forceRootUrl('https://api.ochotierras.cl');

            // Aseguramos que el disco publico use la URL correcta
            config(['filesystems.disks.public.url' => 'https://api.ochotierras.cl/storage']);
        }

        // Observers
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\HeroSection::observe(\App\Observers\HeroSectionObserver::class);
    }
}
