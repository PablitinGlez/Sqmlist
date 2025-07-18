<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
       
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse(['redirect' => route('verification.notice')], 200)
                : redirect()->route('verification.notice');
        }

        
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            return $request->wantsJson()
                ? new JsonResponse(['redirect' => '/admin'], 200)
                : redirect('/admin');
        }

        return $request->wantsJson()
            ? new JsonResponse(['redirect' => '/'], 200)
            : redirect('/'); 
    }
}
