{{-- resources/views/sections/steps.blade.php --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Conviértete en Agente Inmobiliario
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Sigue estos simples pasos y comienza tu carrera en el sector inmobiliario
            </p>
        </div>

        {{-- Content Grid --}}
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Image Side --}}
            <div class="relative">
                <div class="">
                    <img 
                        src="{{ asset('') }}" 
                        alt="Agente" 
                        class="w-full h-96 object-cover rounded-lg"
                    >
                    {{-- Decorative elements --}}
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-blue-500 rounded-full opacity-10"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-green-500 rounded-full opacity-10"></div>
                </div>
            </div>

            {{-- Steps Side --}}
            <div class="relative">
                {{-- Timeline Line --}}
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-blue-200"></div>
                
                {{-- Steps --}}
                <div class="space-y-8">
                    {{-- Step 1 --}}
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            1
                        </div>
                        <div class="ml-6 pb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                Registro Inicial
                            </h3>
                            <p class="text-gray-600">
                                Completa tu perfil profesional con tus datos personales y experiencia.
                            </p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            2
                        </div>
                        <div class="ml-6 pb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                Envía Documentación
                            </h3>
                            <p class="text-gray-600">
                                Adjunta tu solicitud oficial y documentos requeridos para la verificación.
                            </p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            3
                        </div>
                        <div class="ml-6 pb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                Proceso de Aprobación
                            </h3>
                            <p class="text-gray-600">
                                Nuestro equipo revisará tu solicitud en un plazo de 48-72 horas.
                            </p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0 w-16 h-16 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            ✓
                        </div>
                        <div class="ml-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                ¡Comienza a Vender!
                            </h3>
                            <p class="text-gray-600">
                                Accede a tu panel y empieza a promocionar propiedades inmediatamente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Section --}}
        <div class="text-center mt-16">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    ¿Listo para empezar?
                </h3>
                <p class="text-gray-600 mb-6">
                    Únete a cientos de agentes exitosos y transforma tu carrera profesional
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Registrarse Ahora
                    </a>
                    <a href="#contact" 
                       class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                        Más Información
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>