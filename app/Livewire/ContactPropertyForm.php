<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\PropertyContact;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Importar la fachada Log

class ContactPropertyForm extends Component
{
    public $propertyId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $message = '';

    public $showPhoneNumber = false;
    public $propertyPhoneNumber = '';
    public $propertyWhatsappNumber = '';

    public Property $property;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'message' => 'required|string|max:1000',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser una dirección válida.',
        'message.required' => 'El mensaje es obligatorio.',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->property = Property::with(['user.profileDetails'])->findOrFail($propertyId);

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            if ($user->profileDetails?->phone_number) {
                $this->phone = $user->profileDetails->phone_number;
            }
        }

        $this->propertyPhoneNumber = $this->property->contact_phone_number ??
            $this->property->user?->profileDetails?->phone_number;

        $this->propertyWhatsappNumber = $this->property->contact_whatsapp_number ??
            $this->property->user?->profileDetails?->whatsapp_number;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function getFormIsValidProperty()
    {
        if (!$this->property) {
            return false;
        }
        try {
            $this->validate();
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return false;
        }
    }

    public function submitForm()
    {
        $this->validate();

        if (!$this->property) {
            session()->flash('error', 'No se ha seleccionado una propiedad para contactar.');
            return;
        }

        try {
            // --- INICIO DEL LOG DE DEPURACIÓN ---
            Log::info('Solicitud de contacto recibida:', [
                'property_id' => $this->property->id,
                'property_title' => $this->property->title,
                'advertiser_name' => $this->property->user->name ?? 'N/A',
                'advertiser_email' => $this->property->user->email ?? 'N/A',
                'sender_name' => $this->name,
                'sender_email' => $this->email,
                'sender_phone' => $this->phone,
                'message_text' => $this->message,
            ]);
            // --- FIN DEL LOG DE DEPURACIÓN ---

            PropertyContact::create([
                'property_id' => $this->property->id,
                'sender_name' => $this->name,
                'sender_email' => $this->email,
                'sender_phone' => $this->phone,
                'message_text' => $this->message,
                'is_read' => false,
                'is_archived' => false,
            ]);

            $this->showPhoneNumber = true;

            $this->name = '';
            $this->email = '';
            $this->phone = '';
            $this->message = '';
            $this->resetValidation();

            session()->flash('success', '¡Mensaje enviado con éxito! El anunciante ha recibido tu mensaje. Ahora puedes ver su teléfono.');
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un problema al enviar tu mensaje. Por favor, inténtalo de nuevo.');

            Log::error("Error al guardar PropertyContact: " . $e->getMessage(), [
                'property_id' => $this->propertyId,
                'sender_email' => $this->email,
                'exception' => $e
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contact-property-form');
    }
}
