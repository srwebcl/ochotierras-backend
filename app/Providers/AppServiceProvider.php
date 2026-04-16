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
            // Aseguramos que el disco publico use la URL correcta
            config(['filesystems.disks.public.url' => 'https://api.ochotierras.cl/storage']);
        }

        // Register Custom Resend Driver
        \Illuminate\Support\Facades\Mail::extend('resend', function (array $config) {
            return new \App\Mail\ResendTransport(config('services.resend.key'));
        });

        // Observers
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\HeroSection::observe(\App\Observers\HeroSectionObserver::class);
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
    }
}
