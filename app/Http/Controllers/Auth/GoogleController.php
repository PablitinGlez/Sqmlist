<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirigir al usuario a Google para autenticación
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Manejar el callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar usuario existente
            $user = User::where('external_id', $googleUser->id)
                ->where('external_auth', 'google')
                ->first();

            if ($user) {
                // Usuario existe, hacer login
                Auth::login($user);
            } else {
                // Verificar si ya existe un usuario con este email
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Actualizar usuario existente con datos de Google
                    $existingUser->update([
                        'avatar' => $googleUser->avatar,
                        'external_id' => $googleUser->id,
                        'external_auth' => 'google',
                    ]);
                    Auth::login($existingUser);
                } else {
                    // Crear nuevo usuario
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'avatar' => $googleUser->avatar,
                        'external_id' => $googleUser->id,
                        'external_auth' => 'google',
                        'email_verified_at' => now()
                    ]);
                    Auth::login($newUser);
                }
            }

            // Redirigir al dashboard o página deseada
            return redirect()->intended('/');
        } catch (Exception $e) {
            // Manejar errores
            return redirect('/login')->with('error', 'Error al autenticar con Google. Inténtalo de nuevo.');
        }
    }
}
