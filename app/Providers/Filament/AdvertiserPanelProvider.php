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
use Filament\Support\Facades\FilamentView;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\EnsureUserCanAccessAdvertiserPanel;
use Filament\View\PanelsRenderHook;

class AdvertiserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn(): string => Blade::render('<link href="' . asset('css/filament/wizard.css') . '" rel="stylesheet" />'),
            scopes: [\App\Filament\Advertiser\Resources\PropertyResource::class]
        );

        return $panel
            ->id('advertiser')
            ->path('dashboard')
            ->authGuard('web')
            ->brandLogo(asset('images/logo.png'))
            ->homeUrl('/')
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn(): string => Blade::render('
                    <a
                        href="/"
                        class="flex items-center gap-x-2 fi-topbar-item block p-2 text-sm font-medium text-gray-700 hover:text-primary-500 dark:text-gray-200 dark:hover:text-primary-400 transition"
                    >
                        <x-filament::icon
                            icon="heroicon-o-arrow-left"
                            class="h-5 w-5"
                        />
                        <span>Regresar a Inicio</span>
                    </a>
                ')
            )
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Advertiser/Resources'), for: 'App\\Filament\\Advertiser\\Resources')
            ->discoverPages(in: app_path('Filament/Advertiser/Pages'), for: 'App\\Filament\\Advertiser\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Advertiser/Widgets'), for: 'App\\Filament\\Advertiser\\Widgets')
            ->widgets([])
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
                EnsureUserCanAccessAdvertiserPanel::class,
            ]);
    }
}
