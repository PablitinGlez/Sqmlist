<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\On;

class NotificationsIndex extends Component
{
    use WithPagination;

    protected string $layout = 'layouts.app';

    // Propiedad para almacenar el conteo de notificaciones sin leer
    public int $unreadCount = 0;

    /**
     * Render the component's view.
     *
     * @return \Illuminate\View\View
     */
    #[On('favorite-updated')] // Mantener este listener si aún lo necesitas para otras cosas, aunque para favoritos ya no es directo.
    public function render()
    {
        if (!Auth::check()) {
            $notifications = collect();
            $this->unreadCount = 0; // Si no hay usuario, no hay notificaciones sin leer
        } else {
            $user = Auth::user();
            $notifications = $user->notifications()->paginate(10);
            // Actualiza el conteo de notificaciones sin leer
            $this->unreadCount = $user->unreadNotifications()->count();
        }

        return view('livewire.notifications-index', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }

    /**
     * Mark a specific notification as read.
     *
     * @param string $notificationId The ID of the notification to mark as read.
     */
    public function markAsRead(string $notificationId): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $notification = $user->notifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
                $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => 'Notificación marcada como leída.',
                ]);
                // Actualiza el conteo de notificaciones sin leer
                $this->unreadCount = $user->unreadNotifications()->count();
                // No necesitamos $refresh si solo actualizamos la propiedad, Livewire lo hará reactivo.
                // Si la vista necesita una recarga completa por otras razones, se mantendría.
            }
        } else {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'Debes iniciar sesión para gestionar notificaciones.',
            ]);
        }
    }

    /**
     * Mark all unread notifications for the current user as read.
     */
    public function markAllAsRead(): void
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Todas las notificaciones marcadas como leídas.',
            ]);
            // Una vez que todas están leídas, el conteo debe ser 0
            $this->unreadCount = 0;
            // No necesitamos $refresh si solo actualizamos la propiedad, Livewire lo hará reactivo.
        } else {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'Debes iniciar sesión para gestionar notificaciones.',
            ]);
        }
    }
}
