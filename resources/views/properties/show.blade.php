<div>

    
<div class="bg-white p-6 sm:p-8 rounded-lg shadow-md max-w-lg mx-auto">
    <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Contacta con el Agente</h3>
    <p class="text-gray-600 mb-6 text-center text-sm">Envía tus datos</p>

    {{-- Mostrar mensajes de éxito o error --}}
    <x-validation-errors class="mb-4" />

    @session('success')
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md">
            {{ $value }}
        </div>
    @endsession

    @session('error')
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-md">
            {{ $value }}
        </div>
    @endsession

    <form method="POST" action="{{ route('properties.contact', $property->slug) }}" class="space-y-4">
        @csrf

        {{-- Campos de Nombre y Teléfono en la misma fila --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-label for="name" value="Nombre" class="sr-only" /> {{-- sr-only oculta visualmente pero es accesible --}}
                <x-input
                    id="name"
                    class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    type="text"
                    name="name"
                    :value="old('name')"
                    placeholder="Nombre"
                    required
                    autofocus
                />
            </div>
            <div>
                <x-label for="phone" value="Teléfono" class="sr-only" />
                <x-input
                    id="phone"
                    class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    type="tel"
                    name="phone"
                    :value="old('phone')"
                    placeholder="Teléfono"
                />
            </div>
        </div>

        {{-- Campo de Correo Electrónico --}}
        <div>
            <x-label for="email" value="Correo Electrónico" class="sr-only" />
            <x-input
                id="email"
                class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="Correo Electrónico"
                required
            />
        </div>

        {{-- Campo de Descripción (Mensaje) --}}
        <div>
            <x-label for="message" value="Descripción" class="sr-only" />
            <textarea
                id="message"
                name="message"
                rows="4"
                class="block mt-1 w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"
                placeholder="Descripción"
                required
            >{{ old('message') }}</textarea>
        </div>

        {{-- Botón de Enviar Mensaje --}}
        <div class="flex justify-center mt-6">
            <x-button class="w-full justify-center py-3 text-lg bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200">
               Contactar y ver telefono
            </x-button>
        </div>
    </form>

    {{-- Separador o texto "O" --}}
    <p class="text-center text-gray-500 text-sm my-4">- O -</p>

    {{-- Botones de Contacto Directo --}}
    <div class="space-y-3">
        {{-- Botón Ver Teléfono eso esta mal xd apenas lo vi --}}
        <a href="tel:{{ $property->user->profileDetails->phone_number ?? $property->user->phone ?? '' }}"
           class="flex items-center justify-center w-full py-3 text-lg bg-blue-400 hover:bg-blue-500 text-white font-semibold rounded-md shadow-sm transition-colors duration-200"
           target="_blank"
           wire:navigate>
            <i class="fas fa-phone-alt mr-2"></i> 
        </a>

        {{-- Botón WhatsApp --}}
        <a href="https://wa.me/{{ $property->user->profileDetails->whatsapp_number ?? $property->user->phone ?? '' }}?text=Hola,%20me%20interesa%20la%20propiedad:%20{{ urlencode($property->title) }}%20-%20{{ urlencode(route('properties.show', $property->slug)) }}"
           class="flex items-center justify-center w-full py-3 text-lg bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200"
           target="_blank"
           wire:navigate>
            <i class="fab fa-whatsapp mr-2"></i> WhatsApp
        </a>
    </div>
</div>

</div>