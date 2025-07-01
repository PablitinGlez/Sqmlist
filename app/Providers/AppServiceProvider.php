<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Livewire\Livewire;
use App\View\Composers\NavigationComposer;

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
        // Sobrescribir el componente de Jetstream
        // CORRECCIÓN: Usar Livewire::component() correctamente
        Livewire::component('profile.update-password-form', \App\Livewire\UpdatePasswordForm::class);

        // Configurar colores de Filament
        FilamentColor::register([
            'primary' => Color::hex('#5F97FF'),
        ]);

        // --- Registro del View Composer ---
        // CORRECCIÓN: Usa el nombre completo de la vista
        View::composer('layouts.includes.app.navigation-menu', NavigationComposer::class);

        // También registra para las otras vistas que usan las variables
        View::composer([
            'layouts.includes.app.navigation-links-desktop',
            'layouts.includes.app.auth-buttons-desktop',
            'layouts.includes.app.responsive-menu'
        ], NavigationComposer::class);
    }
}
