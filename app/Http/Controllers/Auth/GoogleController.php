<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Log; // Eliminado ya que no se usa
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Auth\Events\Verified;

/**
 * Controlador para la autenticación de usuarios a través de Google Socialite.
 * Gestiona la redirección a Google, la recepción del callback,
 * la creación o vinculación de usuarios y el disparo del evento de verificación
 * de correo electrónico cuando la cuenta es nueva o recién verificada.
 */
class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('external_id', $googleUser->id)
                ->where('external_auth', 'google')
                ->first();

            $isNewUser = false;
            $emailJustVerified = false;

            if ($user) {
                $needsUpdate = false;
                $updateData = [];

                if (is_null($user->email_verified_at)) {
                    $updateData['email_verified_at'] = now();
                    $emailJustVerified = true;
                    $needsUpdate = true;
                }

                if ($user->avatar !== $googleUser->avatar) {
                    $updateData['avatar'] = $googleUser->avatar;
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $user->update($updateData);
                }
            } else {
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    if (is_null($existingUser->email_verified_at)) {
                        $emailJustVerified = true;
                    }

                    $existingUser->update([
                        'avatar' => $googleUser->avatar,
                        'external_id' => $googleUser->id,
                        'external_auth' => 'google',
                        'email_verified_at' => $existingUser->email_verified_at ?? now(),
                    ]);

                    $user = $existingUser;
                } else {
                    $isNewUser = true;

                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'avatar' => $googleUser->avatar,
                        'external_id' => $googleUser->id,
                        'external_auth' => 'google',
                        'email_verified_at' => now()
                    ]);

                    $emailJustVerified = true;
                }
            }

            $user->refresh();

            if ($isNewUser || $emailJustVerified) {
                event(new Verified($user));
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error al autenticar con Google. Inténtalo de nuevo.');
        }
    }
}
