<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class PropertyStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;
    protected ?string $adminNotes; // Para el motivo del rechazo

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Property $property La propiedad cuyo estado ha cambiado.
     * @param string|null $adminNotes Notas del administrador (especialmente para rechazo).
     */
    public function __construct(Property $property, ?string $adminNotes = null)
    {
        $this->property = $property;
        $this->adminNotes = $adminNotes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // La notificación solo se almacenará en la base de datos.
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = $this->property->status;
        $propertyId = $this->property->id;
        // El título de la propiedad ya no se usará en el cuerpo del mensaje, solo el ID.
        // $propertyTitle = $this->property->title ?? 'Propiedad sin título'; 

        $title = '';
        $body = '';
        $icon = ''; // Icono para la interfaz de usuario (ej. Filament)
        $color = ''; // Color para la interfaz de usuario (ej. Filament)
        $link = '#'; // El enlace se mantendrá en 'data' para el botón "Ver Detalles" en el recurso, pero no se usará en el cuerpo del mensaje.

        // Determinar el contenido de la notificación según el estado de la propiedad
        if ($status === Property::STATUS_PUBLISHED) {
            $title = "¡Felicidades! Propiedad Publicada";
            // ✅ CAMBIO: Mensaje simplificado, solo confirmación de publicación
            $body = "¡Felicidades! Una de tus propiedades ya ha sido publicada.";
            $icon = 'heroicon-o-check-circle';
            $color = 'success';
            // Mantener el enlace para el botón "Ver Detalles" en el panel del anunciante
            if (Route::has('filament.advertiser.resources.properties.edit')) {
                $link = route('filament.advertiser.resources.properties.edit', ['record' => $this->property->id]);
            } else {
                Log::warning('Ruta filament.advertiser.resources.properties.edit no definida para notificación de propiedad publicada.', ['property_id' => $propertyId]);
                $link = '#';
            }
        } elseif ($status === Property::STATUS_REJECTED) {
            $title = "Propiedad Rechazada";
            // ✅ CAMBIO: Mensaje de rechazo con ID y salto de línea para el motivo
            $body = "Lamentamos informarte que tu propiedad con ID: {$propertyId} ha sido rechazada.";
            if ($this->adminNotes) {
                $body .= "\nMotivo: " . $this->adminNotes; // Salto de línea para el motivo
            } else {
                $body .= "\nPor favor, revisa los detalles en tu panel.";
            }
            $icon = 'heroicon-o-x-circle';
            $color = 'danger';
            // Mantener el enlace para el botón "Ver Detalles" en el panel del anunciante
            if (Route::has('filament.advertiser.resources.properties.edit')) {
                $link = route('filament.advertiser.resources.properties.edit', ['record' => $this->property->id]);
            } else {
                Log::warning('Ruta filament.advertiser.resources.properties.edit no definida para notificación de propiedad rechazada.', ['property_id' => $propertyId]);
                $link = '#';
            }
        } else {
            // Para otros estados (ej. pending_review, draft, etc.) si se desea notificar
            $title = "Estado de Propiedad Actualizado";
            $body = "El estado de tu propiedad con ID: {$propertyId} es ahora: {$status}.";
            $icon = 'heroicon-o-information-circle';
            $color = 'info';
            // Mantener el enlace para el botón "Ver Detalles" en el panel del anunciante
            if (Route::has('filament.advertiser.resources.properties.edit')) {
                $link = route('filament.advertiser.resources.properties.edit', ['record' => $this->property->id]);
            } else {
                Log::warning('Ruta filament.advertiser.resources.properties.edit no definida para notificación de propiedad con estado genérico.', ['property_id' => $propertyId]);
                $link = '#';
            }
        }

        $notificationData = [
            'property_id' => $propertyId,
            'status' => $status,
            'title' => $title,
            'body' => $body,
            'icon' => $icon, // Usamos iconos de Heroicons para Filament
            'color' => $color, // Colores de Filament (success, danger, warning, info, primary, secondary, gray)
            'link' => $link, // El enlace sigue siendo parte de los datos para el botón en Filament
            'time' => now()->toDateTimeString(), // Marca de tiempo de la notificación
        ];

        // Opcional: Registrar la notificación en los logs para depuración
        Log::channel('notifications')->info('Property Status Notification created:', [
            'user_id' => $notifiable->id,
            'property_id' => $propertyId,
            'status' => $status,
            'data' => $notificationData
        ]);

        return $notificationData;
    }
}
