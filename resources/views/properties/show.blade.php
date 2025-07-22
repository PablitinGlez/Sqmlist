<x-app-layout>

    {{-- Sub-navegador/Barra de Acciones del Detalle --}}
    {{-- Ajuste del tamaño del padding vertical en móviles para un diseño más compacto --}}
    <div class="bg-white shadow-sm py-2 sm:py-4 border-b border-gray-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            {{-- Botón Regresar --}}
           
<button onclick="window.history.back()"
        class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors duration-200 text-xs sm:text-sm font-semibold">
    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i> <span class="hidden sm:inline">Regresar a la búsqueda</span>
    <span class="inline sm:hidden">Regresar</span>
</button>

            {{-- Acciones Derecha --}}
            {{-- Ajustar space-x para móviles, y flex-nowrap para evitar saltos de línea --}}
            <div class="flex items-center space-x-2 sm:space-x-3 flex-nowrap">
                {{-- Botón Guardar (Componente Livewire de Favoritos) --}}
                @livewire('favorite-button-detail', ['property' => $property], key('detail-favorite-button-' . $property->id))


                {{-- Botón Compartir --}}
                <button id="shareButton"
                        class="inline-flex items-center px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-sm text-xs sm:text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
                        onclick="copyToClipboard('{{ url()->current() }}', this)">
                    <i class="fas fa-share-alt mr-0 sm:mr-2"></i> {{-- Eliminar margen en móviles --}}
                    <span class="hidden sm:inline">Compartir</span> {{-- Ocultar texto en móviles --}}
                </button>

                {{-- Botón Ver Teléfono --}}
                {{-- Ocultar en móviles, mostrar en md y superiores --}}
                <a href="#contact-form-section"
                   class="hidden md:inline-flex items-center px-4 py-2 border border-blue-500 rounded-full shadow-sm text-sm font-medium text-blue-500 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-phone-alt mr-2"></i> Ver teléfono
                </a>

                {{-- Botón WhatsApp --}}
                {{-- Ocultar en móviles, mostrar en md y superiores --}}
                @if($property->contact_whatsapp_number || ($property->user && $property->user->profileDetails && $property->user->profileDetails->whatsapp_number))
                    @php
                        $whatsappNumber = $property->contact_whatsapp_number ?? ($property->user->profileDetails->whatsapp_number ?? '');
                        $whatsappLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsappNumber) . '?text=' . urlencode('Hola, me interesa la propiedad: ' . ($property->title ?? 'esta propiedad') . ' - ' . route('properties.show', $property->slug ?? '#'));
                    @endphp
                    <a href="{{ $whatsappLink }}"
                       target="_blank"
                       class="hidden md:inline-flex items-center px-4 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Script para copiar al portapapeles --}}
    <script>
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i> ¡Copiado!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 2000);
            }).catch(function(err) {
                console.error('No se pudo copiar el texto: ', err);
            });
        }
        // Script para el scroll suave
        document.querySelector('a[href="#contact-form-section"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>

    {{-- Aquí irá el resto del contenido de la página de detalles --}}
 <div class="py-10 sm:py-20"> {{-- Ajuste del padding vertical principal --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Título principal de la propiedad --}}
            <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-4 sm:mb-6">Detalles de la Propiedad: {{ $property->title ?? 'N/A' }}</h2> {{-- Cambiado de sm:text-3xl a md:text-3xl para que sea más pequeño en sm --}}
            <div style="height: 1500px;">
                Contenido principal de la propiedad...
            </div>
            <div id="contact-form-section" class="mt-8 sm:mt-12 p-4 sm:p-6 bg-white shadow-md rounded-lg"> {{-- Ajuste del padding --}}
                {{-- Título de la sección de contacto --}}
                <h3 class="text-lg md:text-2xl font-semibold text-gray-800">Sección de Contacto (aquí irá el formulario)</h3> {{-- Cambiado de text-xl a text-lg para móviles y de sm:text-2xl a md:text-2xl --}}
                {{-- Párrafo de descripción en la sección de contacto --}}
                <p class="mt-1 sm:mt-2 text-sm md:text-base text-gray-600">Este es el ancla para el botón "Ver teléfono".</p> {{-- Cambiado de sm:text-base a md:text-base --}}
            </div>
        </div>
    </div>

</x-app-layout>