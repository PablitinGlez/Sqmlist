@props(['maxWidth' => '2xl'])

<div
    x-data="{
        show: false,
        propertyId: null, // Esta variable de Alpine.js almacenará el ID de la propiedad
        
        // Función para inicializar el modal con los datos de la propiedad
        initModal(detail) {
            this.propertyId = detail.propertyId;
            this.show = true;
            // No es necesario Livewire.dispatch('loadProperty') aquí.
            // La prop 'propertyId' y la directiva ':key' en @livewire se encargarán de esto.
        },

        // Función para cerrar el modal y resetear el ID de la propiedad
        closeModal() {
            this.show = false;
            this.propertyId = null; // Limpiar el ID al cerrar para forzar la recreación del Livewire component
        }
    }"
    x-show="show"
    x-cloak {{-- Oculta el modal hasta que Alpine lo inicialice --}}
    class="fixed inset-0 z-50 overflow-y-auto flex items-end justify-center sm:items-center p-4 sm:p-0"
    style="display: none;" {{-- Oculta el modal por defecto para evitar un "flash" al cargar --}}
    x-on:keydown.escape.window="closeModal()" {{-- Cierra el modal con la tecla Escape --}}
    {{-- Escucha el evento global 'open-contact-modal' y llama a initModal --}}
    x-on:open-contact-modal.window="initModal($event.detail)"
>
    {{-- Fondo oscuro del modal --}}
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="closeModal()" {{-- Cierra el modal al hacer clic en el fondo --}}
    ></div>

    {{-- Contenedor del contenido del modal --}}
    <div
        class="relative transform transition-all w-full sm:max-w-{{ $maxWidth }} rounded-lg bg-white shadow-xl"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="flex justify-end">
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    x-on:click="closeModal()" {{-- Botón para cerrar el modal --}}
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            {{-- Contenido del modal: Carga el componente Livewire solo si propertyId está presente --}}
            {{-- Usamos :key para asegurar que Livewire reinicie el componente cuando propertyId cambie --}}
            <template x-if="propertyId">
                {{-- CORRECCIÓN CLAVE: Pasar propertyId como prop al componente Livewire
                     y usar :key para forzar la recreación/mount --}}
                @livewire('contact-property-form', ['propertyId' => $propertyId], key('contact-form-' . $propertyId))
            </template>
        </div>
    </div>
</div>
