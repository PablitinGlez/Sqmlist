<?php

namespace App\Providers\Filament;

use App\Http\Middleware\AdminMiddleware; // Asegúrate de importar tu AdminMiddleware
use Filament\Http\Middleware\Authenticate; // Lo necesitamos para el authMiddleware si lo usamos, pero no en el principal
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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
            // **IMPORTANTE: NO INCLUIR ->login() aquí si quieres que Filament NO maneje su login**
            // Esto significa que los administradores se loguearán vía Jetstream (/login)
            // y luego accederán a /admin.
            ->brandName('Inmobiliaria Admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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

                // Si NO usas ->login(), entonces Authenticate::class (de Filament) NO debe ir aquí.
                // Tu RedirectGuestFromAdmin (global) ya se encargará de la redirección inicial.
                // Aquí, SOLO necesitas asegurarte de que el usuario que llegue sea un admin.
                // AdminMiddleware::class se encargará de esto si RedirectGuestFromAdmin lo dejó pasar.
                AdminMiddleware::class, // Tu middleware para verificar el rol dentro del panel.
            ])
            // **IMPORTANTE: Remover completamente authMiddleware o dejarlo vacío**
            // Si ->login() no está, este array no tiene un propósito de autenticación.
            ->authMiddleware([])
            ->authGuard('web')
            ->authPasswordBroker('users');
    }
}
