<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeVerifiedUser extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('¡Bienvenido a ' . config('app.name') . '!')
            ->line('¡Hola ' . $this->user->name . '!')
            ->line('¡Bienvenido a nuestra plataforma! Tu correo electrónico ha sido verificado exitosamente.')
            ->line('Ya puedes comenzar a explorar todas las funcionalidades que tenemos para ti.')
            ->line('¡Gracias por unirte a nosotros!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'title' => '¡Bienvenido a ' . config('app.name') . '!',
            'body' => '¡Hola ' . $this->user->name . '! Tu correo ha sido verificado exitosamente. Ya puedes comenzar a usar nuestra plataforma. ¡Gracias por unirte a nosotros!',
            'link' => null,
            'icon' => 'check',
            'type' => 'welcome_verified'
        ];
    }
}
