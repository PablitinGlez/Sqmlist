<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
