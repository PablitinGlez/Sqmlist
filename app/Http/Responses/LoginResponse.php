<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\User;

/**
 * Custom Login Response
 * Gestiona la redirección del usuario después de un inicio de sesión exitoso,
 * dirigiéndolo directamente al dashboard.
 */
class LoginResponse implements LoginResponseContract
{
    /**
     * Redirige al usuario después de un inicio de sesión exitoso.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Redirigir directamente al home configurado en Fortify.
        // Se ha eliminado la lógica de verificación de correo post-login.
        return redirect()->intended(config('fortify.home'));
    }
}
