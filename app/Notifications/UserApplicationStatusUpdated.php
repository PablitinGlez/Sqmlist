<?php

namespace App\Notifications;

use App\Models\UserApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class UserApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $userApplication;

    public function __construct(UserApplication $userApplication)
    {
        $this->userApplication = $userApplication;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->userApplication->status;
        $requestedType = $this->userApplication->requested_user_type;
        $readableType = $this->userApplication->requested_user_type_human_readable;
        $messageBody = '';
        $actionText = 'Ver Estado de Solicitud';
        $actionUrl = route('solicitud.estado');

        if ($status === UserApplication::STATUS_APPROVED) {
            $messageBody = "¡Felicidades! Tu solicitud para registrarte como {$readableType} ha sido aprobada.";

            
            if ($requestedType === UserApplication::TYPE_AGENT) {
                $messageBody .= " Ahora puedes publicar propiedades y gestionar tus clientes.";
            } elseif ($requestedType === UserApplication::TYPE_REAL_ESTATE_COMPANY) {
                $messageBody .= " Ahora puedes registrar tus propiedades y agentes asociados.";
            } elseif ($requestedType === UserApplication::TYPE_OWNER) {
                $messageBody .= " Ahora puedes publicar tus propiedades directamente.";
            }
        } elseif ($status === UserApplication::STATUS_REJECTED) {
            $messageBody = "Lamentamos informarte que tu solicitud para registrarte como {$readableType} ha sido rechazada.";

            if ($this->userApplication->status_message) {
                $messageBody .= "\n\nMotivo: " . $this->userApplication->status_message;
                $messageBody .= "\n\nPuedes corregir la información y volver a enviar tu solicitud.";
            }
        } else {
            $messageBody = "El estado de tu solicitud para registrarte como {$readableType} es: " .
                $this->userApplication->status_human_readable . ".";
        }

        return (new MailMessage)
            ->subject("Estado de tu solicitud: {$this->userApplication->status_human_readable}")
            ->greeting("Hola {$notifiable->name},")
            ->line($messageBody)
            ->action($actionText, $actionUrl)
            ->line('Gracias por usar nuestra plataforma.')
            ->salutation('Saludos, El equipo de ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->userApplication->status;
        $requestedType = $this->userApplication->requested_user_type;
        $readableType = $this->userApplication->requested_user_type_human_readable;

        $title = "Solicitud de {$readableType}";
        $body = '';
        $icon = '';
        $color = '';
        $link = route('solicitud.estado');

        if ($status === UserApplication::STATUS_APPROVED) {
            $body = "¡Aprobada! Ya eres {$readableType} en nuestra plataforma.";
            $icon = 'check-circle';
            $color = 'success';
        } elseif ($status === UserApplication::STATUS_REJECTED) {
            $body = "Solicitud rechazada. " . ($this->userApplication->status_message ?: '');
            $icon = 'x-circle';
            $color = 'danger';
        } else {
            $body = "Estado actual: {$this->userApplication->status_human_readable}";
            $icon = 'clock';
            $color = 'warning';
        }

        $notificationData = [
            'user_application_id' => $this->userApplication->id,
            'status' => $status,
            'requested_user_type' => $requestedType,
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
            'color' => $color,
            'link' => $link,
            'time' => now()->toDateTimeString(),
        ];

        Log::channel('notifications')->info('Notification sent:', [
            'user_id' => $notifiable->id,
            'application_id' => $this->userApplication->id,
            'type' => $requestedType
        ]);

        return $notificationData;
    }
}
