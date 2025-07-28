
<x-form-section submit="updateAdvertiserDetails">
    <x-slot name="title">
        {{ __('Datos del Anunciante') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Actualiza la información de contacto de tu perfil de anunciante y añade una breve biografía.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone_number" value="{{ __('Número de Teléfono') }}" />
            <x-input id="phone_number" type="text" class="mt-1 block w-full" wire:model="state.phone_number" autocomplete="tel" />
            <x-input-error for="phone_number" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="whatsapp_number" value="{{ __('Número de WhatsApp') }}" />
            <x-input id="whatsapp_number" type="text" class="mt-1 block w-full" wire:model="state.whatsapp_number" autocomplete="tel" />
            <x-input-error for="whatsapp_number" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="contact_email" value="{{ __('Correo Electrónico de Contacto') }}" />
            <x-input id="contact_email" type="email" class="mt-1 block w-full" wire:model="state.contact_email" autocomplete="email" />
            <x-input-error for="contact_email" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="biography" value="{{ __('Biografía (opcional)') }}" />
            <textarea id="biography" class="border-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" wire:model="state.biography" rows="5"></textarea>
            <x-input-error for="biography" class="mt-2" />
            <p class="mt-2 text-sm text-gray-500">{{ __('Cuéntanos un poco sobre ti o tu empresa. Máximo 1000 caracteres.') }}</p>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Guardado.') }}
        </x-action-message>

        <x-button>
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section>