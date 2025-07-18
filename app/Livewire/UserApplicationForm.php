<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class UserApplicationForm extends Component
{
    use WithFileUploads;

    public $userType = 'owner';
    public $phoneNumber;
    public $whatsappNumber;
    public $contactEmail;
    public $identificationType;
    public $identificationFile;
    public $licenseFile;
    public $yearsExperience;
    public $realEstateCompany;
    public $rfc;

    public array $userTypesOptions = [
        'owner' => 'Particular / Dueño Directo',
        'agent' => 'Agente / Corredor',
        'real_estate_company' => 'Inmobiliaria / Desarrolladora',
    ];

    protected function rules()
    {
        $baseRules = [
            'userType' => 'required|in:owner,agent,real_estate_company',
            'phoneNumber' => 'required|string|min:10|max:12|regex:/^[0-9]+$/',
            'whatsappNumber' => 'nullable|string|min:10|max:12|regex:/^[0-9]+$/',
            'contactEmail' => 'nullable|email|max:255',
            'identificationType' => 'required|in:INE,Pasaporte,Cedula Profesional,Otro',
            'identificationFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if ($this->needsLicense()) {
            $baseRules['licenseFile'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $baseRules['yearsExperience'] = 'required|integer|min:0|max:50';

            if ($this->userType === 'real_estate_company') {
                $baseRules['realEstateCompany'] = 'required|string|max:255|regex:/^[A-Za-zÑñÁáÉéÍíÓóÚúÜü\s0-9\-\.\,]+$/';
                $baseRules['rfc'] = 'required|string|size:13|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/i';
            } else {
                $baseRules['realEstateCompany'] = 'nullable|string|max:255|regex:/^[A-Za-zÑñÁáÉéÍíÓóÚúÜü\s0-9\-\.\,]+$/';
            }
        }

        return $baseRules;
    }

    protected $messages = [
        
        'required' => 'Campo obligatorio',
        'min' => [
            'string' => 'Mínimo :min caracteres',
            'file' => 'El archivo debe ser de al menos :min kilobytes'
        ],
        'max' => [
            'string' => 'Máximo :max caracteres',
            'file' => 'El archivo no debe exceder :max kilobytes'
        ],
        'email' => 'Ingrese un correo electrónico válido',
        'integer' => 'Debe ser un número entero',

    
        'userType.in' => 'Seleccione un tipo de perfil válido',

        'phoneNumber.required' => 'El teléfono es requerido',
        'phoneNumber.regex' => 'Solo se aceptan números (0-9)',
        'phoneNumber.min' => 'El teléfono debe tener al menos 10 dígitos',
        'phoneNumber.max' => 'El teléfono no debe exceder 12 dígitos',

        'whatsappNumber.regex' => 'Solo se aceptan números (0-9)',
        'whatsappNumber.min' => 'WhatsApp debe tener al menos 10 dígitos',
        'whatsappNumber.max' => 'WhatsApp no debe exceder 12 dígitos',

        'contactEmail.email' => 'Ingrese un correo electrónico válido (ejemplo@dominio.com)',
        'contactEmail.max' => 'El correo no debe exceder 255 caracteres',

        'identificationType.in' => 'Seleccione un tipo de identificación válido',

        'identificationFile.required' => 'Debe subir un documento de identificación',
        'identificationFile.mimes' => 'Formatos aceptados: PDF, JPG, JPEG, PNG',
        'identificationFile.max' => 'El archivo no debe pesar más de 2MB',

        'licenseFile.required' => 'Debe subir su licencia inmobiliaria',
        'licenseFile.mimes' => 'Formatos aceptados: PDF, JPG, JPEG, PNG',
        'licenseFile.max' => 'El archivo no debe pesar más de 2MB',

        'yearsExperience.required' => 'Ingrese sus años de experiencia',
        'yearsExperience.integer' => 'Debe ser un número entero',
        'yearsExperience.min' => 'La experiencia mínima es 0 años',
        'yearsExperience.max' => 'La experiencia máxima es 50 años',

        'realEstateCompany.required' => 'Ingrese el nombre de la inmobiliaria',
        'realEstateCompany.regex' => 'Solo letras, números y signos básicos (.-,)',
        'realEstateCompany.max' => 'Máximo 255 caracteres',

        'rfc.required' => 'El RFC es obligatorio',
        'rfc.size' => 'El RFC debe tener exactamente 13 caracteres',
        'rfc.regex' => 'Formato de RFC inválido (ejemplo: XAXX010101000)',
    ];
    public function mount()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $this->contactEmail = $user->email;

        $typeFromUrl = request()->query('type');
        if ($typeFromUrl && array_key_exists($typeFromUrl, $this->userTypesOptions)) {
            $this->userType = $typeFromUrl;
        }

        if ($user->hasRole($this->userType)) {
            session()->flash('info', 'Ya tienes este perfil activo.');
            return redirect('/dashboard');
        }

        $pendingApplication = $user->userApplications()
            ->whereIn('status', [UserApplication::STATUS_PENDING, UserApplication::STATUS_APPROVED])
            ->latest()
            ->first();

        if ($pendingApplication) {
            session()->flash('info', 'Ya tienes una solicitud ' . $pendingApplication->status_human_readable);
            return redirect()->route('solicitud.estado');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Manejo especial para archivos
        if ($propertyName === 'identificationFile' || $propertyName === 'licenseFile') {
            $this->handleFileValidation($propertyName);
        }
    }

    protected function handleFileValidation($propertyName)
    {
        try {
            $this->validateOnly($propertyName);

            $fileName = $this->$propertyName->getClientOriginalName();
            $this->dispatch(
                'file-validated',
                field: $propertyName,
                fileName: $fileName
            );
        } catch (ValidationException $e) {
            $this->reset($propertyName);
            $this->dispatch('file-invalid', field: $propertyName);
            throw $e;
        }
    }

    public function clearIdentificationFile()
    {
        $this->reset('identificationFile');
        $this->resetErrorBag('identificationFile');
        $this->dispatch('file-cleared', field: 'identificationFile');
    }

    public function clearLicenseFile()
    {
        $this->reset('licenseFile');
        $this->resetErrorBag('licenseFile');
        $this->dispatch('file-cleared', field: 'licenseFile');
    }

    public function submit()
    {
        $this->validate();

        try {
            $user = Auth::user();
            $userFolder = "user-applications/{$user->id}";

          
            $pendingApplication = $user->userApplications()
                ->whereIn('status', [UserApplication::STATUS_PENDING, UserApplication::STATUS_APPROVED])
                ->exists();

            if ($pendingApplication) {
                throw new \Exception('Ya tienes una solicitud pendiente o aprobada.');
            }

          
            $identificationPath = $this->identificationFile->store($userFolder, 'public');
            $licensePath = $this->needsLicense() && $this->licenseFile
                ? $this->licenseFile->store($userFolder, 'public')
                : null;

            //se crea la soli de el perfil de usuario  -----
            UserApplication::create([
                'user_id' => $user->id,
                'requested_user_type' => $this->userType,
                'phone_number' => $this->phoneNumber,
                'whatsapp_number' => $this->whatsappNumber,
                'contact_email' => $this->contactEmail,
                'identification_type' => $this->identificationType,
                'identification_path' => $identificationPath,
                'license_path' => $licensePath,
                'years_experience' => $this->needsLicense() ? $this->yearsExperience : null,
                'real_estate_company' => $this->needsLicense() ? $this->realEstateCompany : null,
                'rfc' => $this->userType === 'real_estate_company' ? strtoupper($this->rfc) : null,
                'status' => UserApplication::STATUS_PENDING,
            ]);

            session()->flash('success', '¡Solicitud enviada correctamente!');
            return redirect()->route('solicitud.estado');
        } catch (\Exception $e) {
            $this->addError('submit', $e->getMessage());
            session()->flash('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function needsLicense()
    {
        return in_array($this->userType, ['agent', 'real_estate_company']);
    }

    public function render()
    {
        return view('livewire.user-application-form');
    }
}
