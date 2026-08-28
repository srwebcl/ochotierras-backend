<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Ochotierras')
            ->brandLogo(asset('images/logo.webp'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo.webp'))
            ->colors([
                // Mismos colores de marca que el sitio público (globals.css:
                // --brand-gold, --brand-red, --brand-dark), para que el panel
                // se sienta parte del mismo producto y no un Filament genérico.
                'primary' => Color::hex('#bca874'),
                'danger' => Color::hex('#58181F'),
                'gray' => Color::Stone,
            ])
            ->font('Inter')
            ->darkMode(true)
            ->navigationGroups([
                'Ventas',
                'Catálogo',
                'Sitio Web',
                'Sistema',
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): string => '<style>
                    .fi-simple-layout {
                        background: radial-gradient(circle at top, #1a1611 0%, #0a0a0a 65%);
                    }
                    .fi-simple-layout .fi-simple-main {
                        border: 1px solid rgba(188, 168, 116, 0.15);
                        box-shadow: 0 20px 60px -20px rgba(0, 0, 0, 0.6);
                    }
                </style>',
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
