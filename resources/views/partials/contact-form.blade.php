{{-- Este parcial contiene el formulario de contacto, diseñado para ser inyectado en un modal.
     Las variables de Alpine.js (senderName, senderEmail, etc.) serán definidas en el x-data del modal padre. --}}
     <div class="bg-white p-0 rounded-lg"> {{-- Padding y shadow se manejarán en el modal padre --}}
        <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Contacta con el Anunciante</h3>
        <p class="text-gray-600 mb-6 text-center text-sm">Envía tus datos</p>
    
        {{-- Mensajes de éxito o error (manejados por el modal padre) --}}
        <div x-show="messageStatus === 'success'" class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md" x-cloak>
            <span x-text="messageText"></span>
        </div>
        <div x-show="messageStatus === 'error'" class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-md" x-cloak>
            <span x-text="messageText"></span>
        </div>
    
        {{-- Errores de validación (manejados por el modal padre) --}}
        <template x-if="errors.name">
            <p class="text-red-500 text-xs mt-1" x-text="errors.name[0]"></p>
        </template>
        <template x-if="errors.email">
            <p class="text-red-500 text-xs mt-1" x-text="errors.email[0]"></p>
        </template>
        <template x-if="errors.phone">
            <p class="text-red-500 text-xs mt-1" x-text="errors.phone[0]"></p>
        </template>
        <template x-if="errors.message">
            <p class="text-red-500 text-xs mt-1" x-text="errors.message[0]"></p>
        </template>
    
        {{-- Formulario de Contacto --}}
        <form x-on:submit.prevent="submitForm()" class="space-y-4">
            {{-- Campos de Nombre y Teléfono en la misma fila --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <input
                        id="sender_name"
                        class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        type="text"
                        x-model="senderName" {{-- Enlazado a la variable Alpine del modal --}}
                        placeholder="Nombre"
                        required
                    />
                </div>
                <div>
                    <input
                        id="sender_phone"
                        class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        type="tel"
                        x-model="senderPhone" {{-- Enlazado a la variable Alpine del modal --}}
                        placeholder="Teléfono (opcional)"
                    />
                </div>
            </div>
    
            {{-- Campo de Correo Electrónico --}}
            <div>
                <input
                    id="sender_email"
                    class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    type="email"
                    x-model="senderEmail" {{-- Enlazado a la variable Alpine del modal --}}
                    placeholder="Correo Electrónico"
                    required
                />
            </div>
    
            {{-- Campo de Mensaje --}}
            <div>
                <textarea
                    id="message_text"
                    x-model="messageText" {{-- Enlazado a la variable Alpine del modal --}}
                    rows="4"
                    class="block mt-1 w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Estoy interesado en esta propiedad..."
                    required
                ></textarea>
            </div>
    
            {{-- Botón de Enviar Mensaje / Mostrar Teléfono --}}
            <div class="flex justify-center mt-6">
                <button
                    type="submit"
                    class="w-full justify-center py-2.5 text-base bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200"
                    x-bind:disabled="loading || !formIsValid()" {{-- Deshabilita si está cargando o el formulario no es válido --}}
                >
                    <span x-show="!loading && !showPhoneNumber">Contactar y ver teléfono</span>
                    <span x-show="!loading && showPhoneNumber" x-text="propertyPhoneNumber"></span>
                    <span x-show="loading">Enviando...</span>
                </button>
            </div>
        </form>
    
        {{-- Botón WhatsApp (visible solo después de enviar el formulario si el número está disponible) --}}
        <div x-show="showPhoneNumber && propertyWhatsappNumber" class="mt-4" x-cloak>
            <a x-bind:href="'https://wa.me/' + propertyWhatsappNumber.replace(/[^0-9]/g, '') + '?text=Hola,%20me%20interesa%20la%20propiedad:%20{{ urlencode($property->title ?? 'esta propiedad') }}%20-%20{{ urlencode(url()->current()) }}'"
               class="flex items-center justify-center w-full py-2.5 text-base bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md shadow-sm transition-colors duration-200"
               target="_blank">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </a>
        </div>
    </div>
    