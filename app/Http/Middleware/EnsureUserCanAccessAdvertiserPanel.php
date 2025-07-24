<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class EnsureUserCanAccessAdvertiserPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasAnyRole(['owner', 'agent', 'real_estate_company'])) {
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder al panel de anunciante. Primero debes solicitar y obtener aprobación de un perfil de anunciante.');
        }

        return $next($request);
    }
}
