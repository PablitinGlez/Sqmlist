<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\WelcomeVerifiedUser;

/**
 * Listener que se encarga de enviar una notificación de bienvenida
 * al usuario una vez que ha verificado su dirección de correo electrónico.
 * Esta operación se ejecuta en una cola para no bloquear la respuesta de la aplicación.
 */
class SendWelcomeNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;
        $user->notify(new WelcomeVerifiedUser($user));
    }
}
