<x-app-layout>
    <div class="min-h-screen bg-white flex items-center justify-center py-4 mt-8">
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1.5fr_2fr] items-stretch rounded-2xl overflow-hidden shadow-xl">

                <div class="bg-gray-900 rounded-l-2xl lg:rounded-r-none p-11 text-white relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute -bottom-10 -right-5 w-48 h-48 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-10 right-20 w-36 h-36 bg-white/5 rounded-full"></div>
                    <div class="absolute top-0 left-0 w-24 h-24 bg-white/5 rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute top-20 left-10 w-16 h-16 bg-white/5 rounded-full opacity-50"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div>
                            <h2 class="text-2xl font-bold mb-4">
                                Conéctate con Nosotros
                            </h2>
                            <p class="text-gray-300 mb-12">
                                Estamos aquí para ayudarte a encontrar la propiedad perfecta o a vender la tuya con éxito. Tu consulta es el primer paso hacia tu próximo gran movimiento.
                            </p>

                            <div class="space-y-8">
                                <div class="flex items-center space-x-4">
                                    <div class="w-6 h-6 flex items-center justify-center">
                                        <i class="fas fa-phone-alt w-5 h-5 text-white"></i>
                                    </div>
                                    <span class="text-white">+52 55 1234 5678</span>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="w-6 h-6 flex items-center justify-center">
                                        <i class="fas fa-envelope w-5 h-5 text-white"></i>
                                    </div>
                                    <span class="text-white">contacto@inmobiliaria.com</span>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="w-6 h-6 flex items-center justify-center mt-1">
                                        <i class="fas fa-map-marker-alt w-5 h-5 text-white"></i>
                                    </div>
                                    <span class="text-white leading-relaxed">
                                        Av. Principal #123, Colonia Centro<br>
                                        lorem
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4 mt-auto pt-16">
                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <i class="fab fa-facebook text-gray-900"></i>
                            </a>

                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <i class="fab fa-x-twitter text-gray-900"></i>
                            </a>

                            <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <i class="fab fa-instagram text-gray-900"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-r-2xl lg:rounded-l-none relative">

                    <x-validation-errors class="mb-4" />

                    @session('success')
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md">
                            {{ $value }}
                        </div>
                    @endsession

                   

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-6 ">
                            <div>
                                <x-label for="first_name" value="Nombre" />
                                <x-input
                                    id="first_name"
                                    class="block mt-1 w-full"
                                    type="text"
                                    name="first_name"
                                    :value="old('first_name')"
                                    required
                                    autofocus
                                />
                            </div>
                            <div>
                                <x-label for="last_name" value="Apellido" />
                                <x-input
                                    id="last_name"
                                    class="block mt-1 w-full"
                                    type="text"
                                    name="last_name"
                                    :value="old('last_name')"
                                    required
                                />
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="email" value="Correo Electrónico" />
                                <x-input
                                    id="email"
                                    class="block mt-1 w-full"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                />
                            </div>
                            <div>
                                <x-label for="phone" value="Teléfono" />
                                <x-input
                                    id="phone"
                                    class="block mt-1 w-full"
                                    type="tel"
                                    name="phone"
                                    :value="old('phone')"
                                />
                            </div>
                        </div>

                        <div>
                            <x-label for="subject" value="Motivo de Contacto" />
                            <select
                                id="subject"
                                name="subject"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required
                            >
                                <option value="">Selecciona un motivo</option>
                                <option value="informacion_propiedad" {{ old('subject') == 'informacion_propiedad' ? 'selected' : '' }}>
                                    Información sobre una propiedad específica
                                </option>
                                <option value="agendar_visita" {{ old('subject') == 'agendar_visita' ? 'selected' : '' }}>
                                    Agendar una visita a una propiedad
                                </option>
                                <option value="vender_propiedad" {{ old('subject') == 'vender_propiedad' ? 'selected' : '' }}>
                                    Quiero vender mi propiedad
                                </option>
                                <option value="alquilar_propiedad" {{ old('subject') == 'alquilar_propiedad' ? 'selected' : '' }}>
                                    Quiero alquilar mi propiedad
                                </option>
                                <option value="asesoria_general" {{ old('subject') == 'asesoria_general' ? 'selected' : '' }}>
                                    Asesoría general inmobiliaria
                                </option>
                                <option value="otros" {{ old('subject') == 'otros' ? 'selected' : '' }}>
                                    Otro motivo
                                </option>
                            </select>
                        </div>

                        <div>
                            <x-label for="message" value="Mensaje" />
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full resize-none"
                                placeholder="Me gustaría obtener más detalles sobre..."
                                required
                            >{{ old('message') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <x-button class="bg-gray-900 hover:bg-gray-800">
                                Enviar Mensaje
                            </x-button>
                        </div>
                    </form>

                    <img
                        src="{{ asset('images/letter_send1.svg') }}"
                        alt="avion"
                        class="absolute -bottom-16 right-64 z-0 w-64 h-xl opacity-70"
                        style="transform: rotate(10deg);"
                    >
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
