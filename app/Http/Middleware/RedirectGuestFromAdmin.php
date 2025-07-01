<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware RedirectGuestFromAdmin
 * Controla el acceso completo a las rutas de administración
 */
class RedirectGuestFromAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->is('admin*')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para continuar.')
                ->with('intended_url', $request->url());
        }

        if (!Auth::user()->hasRole('admin')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'No tienes permisos de administrador.'
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'No tienes permisos de administrador para acceder a esta sección.');
        }

        return $next($request);
    }
}
