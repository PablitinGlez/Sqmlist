<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\ProfileDetails;

class EnsureUserCanAccessAdvertiserPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->loadMissing('profileDetails');

        if (!$user->hasAnyRole(['owner', 'agent', 'real_estate_company'])) {
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder al panel de anunciante. Primero debes solicitar y obtener aprobación de un perfil de anunciante.');
        }

        if (!$user->profileDetails || $user->profileDetails->status !== ProfileDetails::STATUS_ACTIVE) {
            return redirect()->route('home')
                ->with('error', 'Tu perfil de anunciante ha sido inhabilitado, posiblemente por incumplimiento de normas. Por favor, contacta a soporte para más información.');
        }

        return $next($request);
    }
}
