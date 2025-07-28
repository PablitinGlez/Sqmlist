<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

class PropertyStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $property;
    protected ?string $adminNotes;

    public function __construct(Property $property, ?string $adminNotes = null)
    {
        $this->property = $property;
        $this->adminNotes = $adminNotes;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->property->status;
        $propertyId = $this->property->id;

        $title = '';
        $body = '';
        $icon = '';
        $color = '';
        $link = '#';

        if ($status === Property::STATUS_PUBLISHED) {
            $title = "¡Felicidades! Propiedad Publicada";
            $body = "¡Felicidades! Una de tus propiedades ya ha sido publicada.";
            $icon = 'heroicon-o-check-circle';
            $color = 'success';
            if (Route::has('filament.advertiser.resources.properties.edit')) {
                $link = route('filament.advertiser.resources.properties.edit', ['record' => $this->property->id]);
            } else {
                $link = '#';
            }
        } elseif ($status === Property::STATUS_REJECTED) {
            $title = "Propiedad Rechazada";
            $body = "Lamentamos informarte que tu propiedad con ID: {$propertyId} ha sido rechazada.";
            if ($this->adminNotes) {
                $body .= "\nMotivo: " . $this->adminNotes;
            } else {
                $body .= "\nPor favor, revisa los detalles en tu panel.";
            }
            $icon = 'heroicon-o-x-circle';
            $color = 'danger';
            if (Route::has('filament.advertiser.resources.properties.edit')) {
                $link = route('filament.advertiser.resources.properties.edit', ['record' => $this->property->id]);
            } else {
                $link = '#';
            }
        } else {
            $title = "Estado de Propiedad Actualizado";
            $body = "El estado de tu propiedad con ID: {$propertyId} es ahora: {$status}.";
            $icon = 'heroicon-o-information-circle';
            $color = 'info';
            if (Route::has('filament.advertiser.resources.properties.edit')) {
                $link = route('filament.advertiser.resources.properties.edit', ['record' => $this->property->id]);
            } else {
                $link = '#';
            }
        }

        $notificationData = [
            'property_id' => $propertyId,
            'status' => $status,
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
            'color' => $color,
            'link' => $link,
            'time' => now()->toDateTimeString(),
        ];

        return $notificationData;
    }
}
