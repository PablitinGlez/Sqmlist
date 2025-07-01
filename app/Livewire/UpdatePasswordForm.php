<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdatePasswordForm extends Component
{
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function getHasSocialLoginProperty(): bool
    {
        return $this->user->hasSocialLoginWithoutPassword();
    }

    public function hasSocialLogin(): bool
    {
        return $this->getHasSocialLoginProperty();
    }

    public function updatePassword(UpdatesUserPasswords $updater): void
    {
        $this->resetErrorBag();

        if ($this->user->hasSocialLoginWithoutPassword()) {
            return;
        }

        $updater->update(Auth::user(), $this->state);

        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_' . Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
            ]);
        }

        $this->reset('state');
        $this->dispatch('saved');
    }

    public function render()
    {
        return view('profile.update-password-form');
    }
}
