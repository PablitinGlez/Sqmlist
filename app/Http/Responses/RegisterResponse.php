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

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 200)
            : redirect()->intended(config('fortify.home'));
    }
}
