<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use App\Models\ProfileDetails;
use Illuminate\Validation\Rule;

class UpdateAdvertiserDetailsForm extends Component
{
    public array $state = [];

    public ?ProfileDetails $profileDetails = null;

    public function mount(): void
    {
        $user = auth()->user();
        if ($user && $user->hasBusinessProfile()) {
            $this->profileDetails = $user->profileDetails;

            $this->state = [
                'phone_number' => $this->profileDetails?->phone_number,
                'whatsapp_number' => $this->profileDetails?->whatsapp_number,
                'contact_email' => $this->profileDetails?->contact_email,
                'biography' => $this->profileDetails?->biography,
            ];
        } else {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function updateAdvertiserDetails(): void
    {
        $user = auth()->user();
        if (!$user || !$user->hasBusinessProfile()) {
            $this->addError('general', 'No tienes permiso para actualizar estos datos.');
            return;
        }

        $this->resetErrorBag();

        $this->validate([
            'state.phone_number' => ['nullable', 'string', 'max:20'],
            'state.whatsapp_number' => ['nullable', 'string', 'max:20'],
            'state.contact_email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'state.biography' => ['nullable', 'string', 'max:1000'],
        ], [
            'state.phone_number.max' => 'El número de teléfono no debe exceder los 20 caracteres.',
            'state.whatsapp_number.max' => 'El número de WhatsApp no debe exceder los 20 caracteres.',
            'state.contact_email.email' => 'El correo electrónico de contacto debe ser una dirección de correo válida.',
            'state.contact_email.max' => 'El correo electrónico de contacto no debe exceder los 255 caracteres.',
            'state.biography.max' => 'La biografía no debe exceder los 1000 caracteres.',
        ]);

        if (!$this->profileDetails) {
            $this->profileDetails = $user->profileDetails()->create([
                'user_application_id' => $user->getLatestUserApplication()?->id,
            ]);
        }

        $this->profileDetails->forceFill([
            'phone_number' => $this->state['phone_number'] ?? null,
            'whatsapp_number' => $this->state['whatsapp_number'] ?? null,
            'contact_email' => $this->state['contact_email'] ?? null,
            'biography' => $this->state['biography'] ?? null,
        ])->save();

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.profile.update-advertiser-details-form');
    }
}
