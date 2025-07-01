<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Gestiona el menú desplegable de notificaciones del usuario,
 * mostrando notificaciones leídas y no leídas, y permitiendo su gestión.
 */
class NotificationsDropdown extends Component
{
    public $unreadNotifications;
    public $readNotifications;
    public $showDropdown = false;
    public $scrolled;

    protected $listeners = [
        'notificationRead' => 'getNotifications',
    ];

    public function mount(): void
    {
        $this->getNotifications();
    }

    public function getNotifications(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->unreadNotifications = $user->unreadNotifications()->latest()->limit(5)->get();
            $this->readNotifications = $user->readNotifications()->latest()->limit(5)->get();
        } else {
            $this->unreadNotifications = collect();
            $this->readNotifications = collect();
        }
    }

    public function markAsRead(string $notificationId): void
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->where('id', $notificationId)->first();

            if ($notification) {
                $notification->markAsRead();
            }

            $this->dispatch('notificationRead');
            $this->getNotifications();
        }
    }

    public function markAllAsRead(): void
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();

            $this->dispatch('notificationRead');
            $this->getNotifications();
            $this->showDropdown = false;
        }
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        return view('livewire.notifications-dropdown');
    }
}
