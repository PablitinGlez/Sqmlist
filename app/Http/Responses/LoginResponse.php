<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Custom Login Response
 * Gestiona la redirección del usuario después de un inicio de sesión exitoso.
 * Redirige a administradores a su panel, y a todos los demás a la ruta raíz.
 */
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        
        if ($user->hasRole('admin')) {
            return $request->wantsJson()
                ? new JsonResponse(['two_factor' => false, 'redirect' => '/admin'])
                : redirect('/admin');
        }

      
        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false, 'redirect' => '/'])
            : redirect('/');
    }
}
