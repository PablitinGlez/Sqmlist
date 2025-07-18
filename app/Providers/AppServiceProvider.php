<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Livewire\Livewire;
use App\View\Composers\NavigationComposer;

use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;


use App\Http\Responses\LoginResponse as CustomLoginResponse;
use App\Http\Responses\RegisterResponse as CustomRegisterResponse;
use App\Http\Responses\VerifyEmailResponse as CustomVerifyEmailResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);
        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);
        $this->app->singleton(VerifyEmailResponse::class, CustomVerifyEmailResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('profile.update-password-form', \App\Livewire\UpdatePasswordForm::class);

        FilamentColor::register([
            'primary' => Color::hex('#5F97FF'),
        ]);

        View::composer('layouts.includes.app.navigation-menu', NavigationComposer::class);

        View::composer([
            'layouts.includes.app.navigation-links-desktop',
            'layouts.includes.app.auth-buttons-desktop',
            'layouts.includes.app.responsive-menu'
        ], NavigationComposer::class);
    }
}
