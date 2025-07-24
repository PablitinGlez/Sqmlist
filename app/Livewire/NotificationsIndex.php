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

    public int $unreadCount = 0;

    #[On('favorite-updated')]
    public function render()
    {
        if (!Auth::check()) {
            $notifications = collect();
            $this->unreadCount = 0;
        } else {
            $user = Auth::user();
            $notifications = $user->notifications()->paginate(10);
            $this->unreadCount = $user->unreadNotifications()->count();
        }

        return view('livewire.notifications-index', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }

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
                $this->unreadCount = $user->unreadNotifications()->count();
            }
        } else {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'Debes iniciar sesión para gestionar notificaciones.',
            ]);
        }
    }

    public function markAllAsRead(): void
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Todas las notificaciones marcadas como leídas.',
            ]);
            $this->unreadCount = 0;
        } else {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'Debes iniciar sesión para gestionar notificaciones.',
            ]);
        }
    }
}
