<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User; // Asegúrate de importar el modelo User
use App\Models\ProfileDetails; // Asegúrate de importar el modelo ProfileDetails
use Illuminate\Validation\Rule;

class UpdateAdvertiserDetailsForm extends Component
{
    public array $state = [];

    public ?ProfileDetails $profileDetails = null;

    // En app/Livewire/Profile/UpdateAdvertiserDetailsForm.php

    public function mount(): void
    {
        $user = auth()->user();
        if ($user && $user->hasBusinessProfile()) {
            $this->profileDetails = $user->profileDetails;

            $this->state = [
                'phone_number' => $this->profileDetails?->phone_number,
                'whatsapp_number' => $this->profileDetails?->whatsapp_number,
                'contact_email' => $this->profileDetails?->contact_email,
                'biography' => $this->profileDetails?->biography, // Añade esta línea
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
                // Validación para asegurar que el correo de contacto sea único si se proporciona,
                // o si no quieres que colisione con el correo principal del usuario
                // Rule::unique('profile_details', 'contact_email')->ignore($this->profileDetails?->id),
            ],
            'state.biography' => ['nullable', 'string', 'max:1000'],
        ], [
            'state.phone_number.max' => 'El número de teléfono no debe exceder los 20 caracteres.',
            'state.whatsapp_number.max' => 'El número de WhatsApp no debe exceder los 20 caracteres.',
            'state.contact_email.email' => 'El correo electrónico de contacto debe ser una dirección de correo válida.',
            'state.contact_email.max' => 'El correo electrónico de contacto no debe exceder los 255 caracteres.',
            'state.biography.max' => 'La biografía no debe exceder los 1000 caracteres.', // Mensaje de error para la biografía
            // 'state.contact_email.unique' => 'Este correo electrónico de contacto ya está en uso por otro anunciante.',
        ]);

        // Asegúrate de que $this->profileDetails esté cargado.
        // Si por alguna razón no existe (aunque mount() debería manejarlo), créalo.
        if (!$this->profileDetails) {
            $this->profileDetails = $user->profileDetails()->create([
                'user_application_id' => $user->getLatestUserApplication()?->id, // Asocia si existe una aplicación
            ]);
        }

        $this->profileDetails->forceFill([
            'phone_number' => $this->state['phone_number'] ?? null,
            'whatsapp_number' => $this->state['whatsapp_number'] ?? null,
            'contact_email' => $this->state['contact_email'] ?? null,
            'biography' => $this->state['biography'] ?? null,
        ])->save();

        $this->dispatch('saved'); // Evento de Jetstream para mostrar el mensaje "Saved."
    }

    public function render()
    {
        return view('livewire.profile.update-advertiser-details-form');
    }
}
