<?php

// app/Http/Responses/LoginResponse.php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Aquí puedes agregar lógica más compleja si necesitas
        // Por ejemplo, redirigir a diferentes lugares según el tipo de usuario

        return redirect()->intended('/');
    }
}
