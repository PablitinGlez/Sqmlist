<?php

namespace App\Listeners;

use Laravel\Fortify\Events\Authenticated;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class CheckUserStatusAfterLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Authenticated $event): void
    {
        $user = $event->user;

        
        if (!$user->canLogin()) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

          
            $message = 'Tu cuenta está inactiva o no tiene acceso al sistema. Contacta al administrador.';

            session()->flash('error', $message);

            abort(redirect()->route('login'));
        }
    }
}
