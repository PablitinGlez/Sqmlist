<?php
// app/Http/Responses/RegisterResponse.php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // También puedes agregar lógica personalizada aquí
        // Como enviar emails de bienvenida, logging, etc.

        return redirect('/');
    }
}