<div>
    {{-- Alineado a la izquierda --}}
    <h3 class="text-base sm:text-xl font-bold text-gray-800 mb-2 text-left">Contacta con el Anunciante</h3>
    <p class="text-gray-600 mb-4 text-left text-xs sm:text-sm">Envía tus datos</p> {{-- Ajustado mb y tamaño --}}

    {{-- Mostrar mensajes de éxito o error de Livewire (Estos se mantienen) --}}
    @if (session()->has('success'))
        <div class="mb-3 font-medium text-xs sm:text-sm text-green-600 bg-green-50 p-2 sm:p-3 rounded-md"> {{-- Ajustado mb y padding --}}
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-3 font-medium text-xs sm:text-sm text-red-600 bg-red-50 p-2 sm:p-3 rounded-md"> {{-- Ajustado mb y padding --}}
            {{ session('error') }}
        </div>
    @endif

    {{-- Mostrar errores de validación de Livewire --}}
    {{-- Los mensajes de error ya suelen alinearse a la izquierda por defecto --}}
    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

    {{-- Formulario de Contacto --}}
    <form wire:submit.prevent="submitForm" class="space-y-3 sm:space-y-4"> {{-- Espaciado reducido para móviles --}}
        {{-- Campos de Nombre y Teléfono en la misma fila --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4"> {{-- Espaciado reducido para móviles --}}
            <div>
                <input
                    id="name"
                    class="block mt-1 w-full p-2 sm:p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm" {{-- Ajustado padding y tamaño de texto --}}
                    type="text"
                    wire:model.live="name"
                    placeholder="Nombre"
                    required
                />
            </div>
            <div>
                <input
                    id="phone"
                    class="block mt-1 w-full p-2 sm:p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm" {{-- Ajustado padding y tamaño de texto --}}
                    type="tel"
                    wire:model.live="phone"
                    placeholder="Teléfono"
                />
            </div>
        </div>

        {{-- Campo de Correo Electrónico --}}
        <div>
            <input
                id="email"
                class="block mt-1 w-full p-2 sm:p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm" {{-- Ajustado padding y tamaño de texto --}}
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
                rows="3" {{-- Reducido rows para móviles --}}
                class="block mt-1 w-full p-2 sm:p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none text-xs sm:text-sm" {{-- Ajustado padding y tamaño de texto --}}
                placeholder="Tu mensaje..."
                required
            ></textarea>
        </div>

        {{-- Botón de Enviar Mensaje / Mostrar Teléfono --}}
        {{-- Alineado a la izquierda, eliminando justify-center --}}
        <div class="mt-4 sm:mt-6">
            <button
                type="submit"
                class="w-full inline-flex items-center justify-center py-2 sm:py-2.5 text-xs sm:text-sm bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" {{-- Ajustado padding vertical y ahora text-xs sm:text-sm --}}
                wire:loading.attr="disabled"
                wire:target="submitForm"
                x-bind:disabled="!$wire.formIsValid || $wire.showPhoneNumber"
            >
                <span wire:loading.remove wire:target="submitForm">
                    <span x-show="!$wire.showPhoneNumber">Contactar y ver teléfono</span>
                    <span x-show="$wire.showPhoneNumber" class="flex items-center justify-center">
                        <i class="fas fa-phone mr-1 sm:mr-2"></i>
                        {{ $propertyPhoneNumber ?: 'Teléfono no disponible' }}
                    </span>
                </span>
                <span wire:loading wire:target="submitForm" class="inline-flex items-center justify-center">
                    <svg class="animate-spin mr-1 sm:mr-2 h-4 w-4 sm:h-5 sm:w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="inline-block text-xs sm:text-sm">Enviando...</span> {{-- Ajustado tamaño de texto --}}
                </span>
            </button>
        </div>

        {{-- Botón WhatsApp (siempre visible y funcional) --}}
        @if($propertyWhatsappNumber)
            <div class="mt-3 sm:mt-4">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $propertyWhatsappNumber) }}?text=Hola,%20me%20interesa%20la%20propiedad:%20{{ urlencode($property->title ?? 'esta propiedad') }}%20-%20{{ urlencode(route('properties.show', $property->slug ?? '#')) }}"
                   class="flex items-center justify-center w-full py-2 sm:py-2.5 text-xs sm:text-sm bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200" {{-- Ajustado padding vertical y ahora text-xs sm:text-sm --}}
                   target="_blank" wire:navigate>
                    <i class="fab fa-whatsapp mr-1 sm:mr-2"></i> WhatsApp
                </a>
            </div>
        @endif
    </form>
</div>