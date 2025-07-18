<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
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
