{{-- Vista optimizada para modal --}}
<div>
    <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Contacta con el Anunciante</h3>
    <p class="text-gray-600 mb-6 text-center text-sm">Envía tus datos</p>

    {{-- Mostrar mensajes de éxito o error de Livewire --}}
    @if (session()->has('success'))
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    {{-- Mostrar errores de validación de Livewire --}}
    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

    {{-- Formulario de Contacto --}}
    <form wire:submit.prevent="submitForm" class="space-y-4">
        {{-- Campos de Nombre y Teléfono en la misma fila --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <input
                    id="name"
                    class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    type="text"
                    wire:model.live="name"
                    placeholder="Nombre"
                    required
                />
            </div>
            <div>
                <input
                    id="phone"
                    class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    type="tel"
                    wire:model.live="phone"
                    placeholder="Teléfono (opcional)"
                />
            </div>
        </div>

        {{-- Campo de Correo Electrónico --}}
        <div>
            <input
                id="email"
                class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                type="email"
                wire:model.live="email"
                placeholder="Correo Electrónico"
                required
            />
        </div>

        {{-- Campo de Mensaje --}}
        <div>
            <textarea
                id="message"
                wire:model.live="message"
                rows="4"
                class="block mt-1 w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"
                placeholder="Tu mensaje..."
                required
            ></textarea>
        </div>

        {{-- Botón de Enviar Mensaje / Mostrar Teléfono --}}
        <div class="flex justify-center mt-6">
            <button
                type="submit"
                class="w-full justify-center py-2.5 text-base bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:target="submitForm"
                x-bind:disabled="!$wire.formIsValid"
            >
                <span wire:loading.remove wire:target="submitForm">
                    <span x-show="!$wire.showPhoneNumber">Contactar y ver teléfono</span>
                    <span x-show="$wire.showPhoneNumber" class="flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $propertyPhoneNumber ?: 'Teléfono no disponible' }}
                    </span>
                </span>
                <span wire:loading wire:target="submitForm" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando...
                </span>
            </button>
        </div>
    </form>

    {{-- Botón WhatsApp (siempre visible y funcional) --}}
    @if($propertyWhatsappNumber)
        <div class="mt-4">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $propertyWhatsappNumber) }}?text=Hola,%20me%20interesa%20la%20propiedad:%20{{ urlencode($property->title ?? 'esta propiedad') }}%20-%20{{ urlencode(route('properties.show', $property->slug ?? '#')) }}"
               class="flex items-center justify-center w-full py-2.5 text-base bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200"
               target="_blank"
               wire:navigate>
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </a>
        </div>
    @endif
</div>