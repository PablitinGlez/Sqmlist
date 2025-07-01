{{--
    Esta sección describe los pasos para que un usuario se convierta en agente inmobiliario.
    Incluye un encabezado, un carrusel de pasos interactivo y una llamada a la acción.
--}}
<section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50 overflow-hidden" id="steps-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20" id="steps-header">
            <h2 class="text-5xl font-bold text-gray-900 mb-6">
                Conviértete en
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                    Agente Inmobiliario
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Un proceso simple y efectivo para comenzar tu carrera profesional
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mx-auto mt-8 rounded-full"></div>
        </div>

        <div class="relative" id="steps-container">
            <div class="absolute top-1/2 left-0 right-0 h-2 bg-gray-200 rounded-full transform -translate-y-1/2 z-0">
                <div class="h-full bg-gradient-to-r from-blue-600 to-purple-600 rounded-full transition-all duration-1000 ease-out" id="progress-bar" style="width: 0%"></div>
            </div>

            <div class="relative z-10 flex justify-between items-center" id="steps-track">
                <div class="step-card bg-white rounded-2xl shadow-xl p-8 w-72 transform translate-x-0" data-step="1">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg step-number">
                            1
                        </div>
                        <div class="ml-4">
                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Registro Inicial
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Completa tu perfil profesional con tus datos personales y experiencia previa.
                    </p>
                    <div class="flex items-center text-blue-600 font-semibold">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span>5 minutos</span>
                    </div>
                </div>

                <div class="step-card bg-white rounded-2xl shadow-xl p-8 w-72 transform translate-x-full opacity-50" data-step="2">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-green-700 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg step-number">
                            2
                        </div>
                        <div class="ml-4">
                            <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Documentación
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Envía tu solicitud oficial y documentos requeridos para verificación completa.
                    </p>
                    <div class="flex items-center text-green-600 font-semibold">
                        <i class="fas fa-file-alt mr-2"></i>
                        <span>10 minutos</span>
                    </div>
                </div>

                <div class="step-card bg-white rounded-2xl shadow-xl p-8 w-72 transform translate-x-full opacity-50" data-step="3">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg step-number">
                            3
                        </div>
                        <div class="ml-4">
                            <div class="w-3 h-3 bg-purple-600 rounded-full"></div>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Aprobación
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Revisión profesional de tu solicitud en un plazo máximo de 48-72 horas.
                    </p>
                    <div class="flex items-center text-purple-600 font-semibold">
                        <i class="fas fa-clock mr-2"></i>
                        <span>48-72 horas</span>
                    </div>
                </div>

                <div class="step-card bg-white rounded-2xl shadow-xl p-8 w-72 transform translate-x-full opacity-50" data-step="4">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg step-number">
                            <i class="fas fa-check text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="w-3 h-3 bg-emerald-600 rounded-full animate-bounce"></div>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        ¡Comienza Ya!
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Accede a tu panel profesional y empieza a promocionar propiedades.
                    </p>
                    <div class="flex items-center text-emerald-600 font-semibold">
                        <i class="fas fa-rocket mr-2"></i>
                        <span>¡Inmediato!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
