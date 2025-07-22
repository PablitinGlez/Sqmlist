{{-- resources/views/components/sections/steps-to-advertiser.blade.php --}}
<section id="steps-to-advertiser" class="relative bg-gradient-to-br bg-transparent overflow-hidden">
    
    {{-- Contenedor principal con scroll pinning optimizado --}}
    <div class="sticky-container relative w-full">
        
        {{-- Contenedor fijo que se quedará en pantalla - altura optimizada --}}
        <div class="sticky top-0 flex items-center justify-center bg-gradient-to-br py-4 md:py-8 lg:py-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                
                {{-- Título principal - márgenes reducidos --}}
                <div class="text-center mb-4 md:mb-8 lg:mb-12">
                    <h2 class="text-xl md:text-2xl lg:text-5xl font-semibold text-gray-900 mb-2 md:mb-3 lg:mb-4">
                        Convierte en <span class="text-blue-600">Anunciante</span>
                    </h2>
                    <p class="text-sm md:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                        Sigue estos pasos y comienza a publicar tus propiedades hoy mismo
                    </p>
                </div>

                {{-- Progress Bar Container - márgenes reducidos --}}
                <div class="mb-4 md:mb-8 lg:mb-12">
                    <div class="flex justify-center mb-3 md:mb-6">
                        <div class="w-full max-w-4xl">
                            <ol id="progress-steps" class="flex items-center w-full">
                                {{-- Paso 1 --}}
                                <li class="flex w-full items-center step-item" data-step="1">
                                    <span class="step-circle flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 rounded-full lg:h-12 lg:w-12 shrink-0 transition-all duration-500">
                                        <i class="fas fa-user-plus text-gray-400 text-xs md:text-xs lg:text-base step-icon"></i>
                                    </span>
                                    <div class="step-line w-full h-0.5 md:h-1 bg-gray-200 mx-1 md:mx-2 lg:mx-3 transition-all duration-500"></div>
                                </li>
                                
                                {{-- Paso 2 --}}
                                <li class="flex w-full items-center step-item" data-step="2">
                                    <span class="step-circle flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 rounded-full lg:h-12 lg:w-12 shrink-0 transition-all duration-500">
                                        <i class="fas fa-file-alt text-gray-400 text-xs md:text-xs lg:text-base step-icon"></i>
                                    </span>
                                    <div class="step-line w-full h-0.5 md:h-1 bg-gray-200 mx-1 md:mx-2 lg:mx-3 transition-all duration-500"></div>
                                </li>
                                
                                {{-- Paso 3 --}}
                                <li class="flex w-full items-center step-item" data-step="3">
                                    <span class="step-circle flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 rounded-full lg:h-12 lg:w-12 shrink-0 transition-all duration-500">
                                        <i class="fas fa-clock text-gray-400 text-xs md:text-xs lg:text-base step-icon"></i>
                                    </span>
                                    <div class="step-line w-full h-0.5 md:h-1 bg-gray-200 mx-1 md:mx-2 lg:mx-3 transition-all duration-500"></div>
                                </li>
                                
                                {{-- Paso 4 --}}
                                <li class="flex items-center step-item" data-step="4">
                                    <span class="step-circle flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 rounded-full lg:h-12 lg:w-12 shrink-0 transition-all duration-500">
                                        <i class="fas fa-rocket text-gray-400 text-xs md:text-xs lg:text-base step-icon"></i>
                                    </span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Contenido de los pasos - altura adaptable sin cortes --}}
                <div class="step-content-container relative min-h-[280px] sm:min-h-[320px] md:h-64 lg:h-80 overflow-hidden">
                    
                    {{-- Paso 1: Registro --}}
                    <div class="step-content absolute inset-0 opacity-0 invisible transform translate-y-10 pointer-events-none" data-step="1">
                        {{-- Ajuste aquí: md:items-center para desktop, por defecto align-start en mobile --}}
                        <div class="grid md:grid-cols-2 gap-4 md:gap-6 lg:gap-8 items-start md:items-center h-full"> 
                            <div class="text-left px-2 md:px-0"> {{-- text-left para mobile y desktop --}}
                                <h3 class="text-sm md:text-xl lg:text-2xl font-bold text-gray-900 mb-1 md:mb-2 lg:mb-3">Regístrate y Verifica</h3>
                                <p class="text-xs md:text-sm lg:text-base text-gray-600 mb-2 md:mb-3 lg:mb-4 leading-relaxed">
                                    Crea tu cuenta gratuita y verifica tu email. Es rápido, seguro y solo toma unos minutos.
                                </p>
                                <ul class="text-left space-y-0.5 md:space-y-1 text-xs md:text-xs lg:text-sm text-gray-600">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Registro completamente gratuito</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Verificación por email</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Proceso en menos de 5 minutos</li>
                                </ul>
                            </div>
                            {{-- Ajuste aquí: flex justify-start para mobile, md:justify-center para desktop --}}
                            <div class="flex justify-start md:justify-center mt-2 md:mt-0"> 
                                <div class="w-40 sm:w-48 md:w-56 lg:w-64 h-28 sm:h-32 md:h-40 lg:h-48 bg-white rounded-lg shadow-lg p-2 md:p-4 border-2 border-blue-100 flex flex-col justify-between">
                                    <div class="space-y-2 md:space-y-3">
                                        <div class="h-2 md:h-3 bg-blue-200 rounded animate-pulse w-3/4"></div>
                                        <div class="h-2 md:h-3 bg-gray-200 rounded animate-pulse w-full"></div>
                                        <div class="h-2 md:h-3 bg-gray-200 rounded animate-pulse w-1/2"></div>
                                    </div>
                                    <div class="h-4 md:h-6 bg-blue-500 rounded mt-auto"></div> {{-- Use mt-auto to push to bottom --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2: Solicitud --}}
                    <div class="step-content absolute inset-0 opacity-0 invisible transform translate-y-10 pointer-events-none" data-step="2">
                        {{-- Ajuste aquí: md:items-center para desktop, por defecto align-start en mobile --}}
                        <div class="grid md:grid-cols-2 gap-4 md:gap-6 lg:gap-8 items-start md:items-center h-full">
                            <div class="text-left px-2 md:px-0"> {{-- text-left para mobile y desktop --}}
                                <h3 class="text-sm md:text-xl lg:text-2xl font-bold text-gray-900 mb-1 md:mb-2 lg:mb-3">Envía tu Solicitud</h3>
                                <p class="text-xs md:text-sm lg:text-base text-gray-600 mb-2 md:mb-3 lg:mb-4 leading-relaxed">
                                    Completa el formulario con tus datos y sube los documentos requeridos. 
                                </p>
                                <ul class="text-left space-y-0.5 md:space-y-1 text-xs md:text-xs lg:text-sm text-gray-600">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Formulario simple y rápido</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Sube tu identificación oficial</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Información de contacto segura</li>
                                </ul>
                            </div>
                            {{-- Ajuste aquí: flex justify-start para mobile, md:justify-center para desktop --}}
                            <div class="flex justify-start md:justify-center mt-2 md:mt-0">
                                <div class="w-40 sm:w-48 md:w-56 lg:w-64 h-28 sm:h-32 md:h-40 lg:h-48 bg-white rounded-lg shadow-lg p-2 md:p-4 border-2 border-green-100 flex flex-col">
                                    <div class="space-y-1 md:space-y-2 mb-auto"> {{-- Add mb-auto to push docs to bottom --}}
                                        <div class="text-xs md:text-xs text-gray-600">Tipo de Perfil</div>
                                        <div class="h-1 md:h-2 bg-green-200 rounded w-full"></div>
                                        <div class="text-xs md:text-xs text-gray-600">Datos de Contacto</div>
                                        <div class="h-1 md:h-2 bg-gray-200 rounded w-5/6"></div>
                                        <div class="h-1 md:h-2 bg-gray-200 rounded w-2/3"></div>
                                    </div>
                                    <div class="text-xs md:text-xs text-gray-600 mt-2">Documentos</div> {{-- Add mt-2 for spacing --}}
                                    <div class="h-4 md:h-6 bg-gray-100 rounded border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs">
                                        Subir Archivo
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 3: Espera --}}
                    <div class="step-content absolute inset-0 opacity-0 invisible transform translate-y-10 pointer-events-none" data-step="3">
                        {{-- Ajuste aquí: md:items-center para desktop, por defecto align-start en mobile --}}
                        <div class="grid md:grid-cols-2 gap-4 md:gap-6 lg:gap-8 items-start md:items-center h-full">
                            <div class="text-left px-2 md:px-0"> {{-- text-left para mobile y desktop --}}
                                <h3 class="text-sm md:text-xl lg:text-2xl font-bold text-gray-900 mb-1 md:mb-2 lg:mb-3">Espera la Aprobación</h3>
                                <p class="text-xs md:text-sm lg:text-base text-gray-600 mb-2 md:mb-3 lg:mb-4 leading-relaxed">
                                    Nuestro equipo revisará tu solicitud. Te notificaremos por email una vez aprobada.
                                </p>
                                <ul class="text-left space-y-0.5 md:space-y-1 text-xs md:text-xs lg:text-sm text-gray-600">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Revisión en 24-48 horas</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Notificación por email</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Soporte personalizado</li>
                                </ul>
                            </div>
                            {{-- Ajuste aquí: flex justify-start para mobile, md:justify-center para desktop --}}
                            <div class="flex justify-start md:justify-center mt-2 md:mt-0">
                                <div class="w-40 sm:w-48 md:w-56 lg:w-64 h-28 sm:h-32 md:h-40 lg:h-48 bg-white rounded-lg shadow-lg p-2 md:p-4 border-2 border-yellow-100 flex flex-col items-start justify-center"> {{-- items-start para alinear contenido interno a la izquierda --}}
                                    <div class="w-8 md:w-12 h-8 md:h-12 border-2 md:border-4 border-yellow-500 border-t-transparent rounded-full animate-spin mb-2 md:mb-3"></div> {{-- Eliminado mx-auto --}}
                                    <p class="text-yellow-600 font-semibold text-xs md:text-sm">Revisando tu solicitud...</p> {{-- Eliminado text-center --}}
                                    <p class="text-xs md:text-xs text-gray-500 mt-1">Te notificaremos pronto</p> {{-- Eliminado text-center --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 4: Publicar --}}
                    <div class="step-content absolute inset-0 opacity-0 invisible transform translate-y-10 pointer-events-none" data-step="4">
                        {{-- Ajuste aquí: md:items-center para desktop, por defecto align-start en mobile --}}
                        <div class="grid md:grid-cols-2 gap-4 md:gap-6 lg:gap-8 items-start md:items-center h-full">
                            <div class="text-left px-2 md:px-0"> {{-- text-left para mobile y desktop --}}
                                <h3 class="text-sm md:text-xl lg:text-2xl font-bold text-gray-900 mb-1 md:mb-2 lg:mb-3">¡Comienza a Publicar!</h3>
                                <p class="text-xs md:text-sm lg:text-base text-gray-600 mb-2 md:mb-3 lg:mb-4 leading-relaxed">
                                    Una vez aprobado, podrás crear y publicar tus propiedades inmediatamente.
                                </p>
                                <ul class="text-left space-y-0.5 md:space-y-1 text-xs md:text-xs lg:text-sm text-gray-600">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Publicaciones ilimitadas</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Panel de administración completo</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-1 md:mr-2 text-xs"></i>Métricas y estadísticas detalladas</li>
                                </ul>
                            </div>
                            {{-- Ajuste aquí: flex justify-start para mobile, md:justify-center para desktop --}}
                            <div class="flex justify-start md:justify-center mt-2 md:mt-0">
                                <div class="w-40 sm:w-48 md:w-56 lg:w-64 h-28 sm:h-32 md:h-40 lg:h-48 bg-white rounded-lg shadow-lg p-2 md:p-4 border-2 border-purple-100 flex flex-col">
                                    <div class="flex justify-between items-center mb-2"> {{-- Added mb-2 for spacing --}}
                                        <div class="text-xs md:text-xs font-semibold text-purple-600">Mis Propiedades</div>
                                        <div class="w-4 md:w-6 h-4 md:h-6 bg-purple-500 rounded text-white text-xs flex items-center justify-center">+</div>
                                    </div>
                                    <div class="space-y-1 md:space-y-2 flex-grow"> {{-- flex-grow to take available space --}}
                                        <div class="h-6 md:h-8 bg-purple-50 rounded border border-purple-200 p-1 md:p-2 flex flex-col justify-center"> {{-- Added flex-col justify-center --}}
                                            <div class="h-0.5 md:h-1 bg-purple-300 rounded mb-1 w-3/4"></div> {{-- Added w-3/4 --}}
                                            <div class="h-0.5 md:h-1 bg-gray-200 rounded w-1/2"></div> {{-- Added w-1/2 --}}
                                        </div>
                                        <div class="h-6 md:h-8 bg-purple-50 rounded border border-purple-200 p-1 md:p-2 flex flex-col justify-center"> {{-- Added flex-col justify-center --}}
                                            <div class="h-0.5 md:h-1 bg-purple-300 rounded mb-1 w-full"></div> {{-- Added w-full --}}
                                            <div class="h-0.5 md:h-1 bg-gray-200 rounded w-2/3"></div> {{-- Added w-2/3 --}}
                                        </div>
                                        <div class="h-6 md:h-8 bg-purple-50 rounded border border-purple-200 p-1 md:p-2 flex flex-col justify-center"> {{-- Added flex-col justify-center --}}
                                            <div class="h-0.5 md:h-1 bg-purple-300 rounded mb-1 w-1/2"></div> {{-- Added w-1/2 --}}
                                            <div class="h-0.5 md:h-1 bg-gray-200 rounded w-full"></div> {{-- Added w-full --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="relative py-8 md:py-12 lg:py-16" style="background-color: #5db9ff;">
        <div class="absolute inset-0" style="background-image: url('images/hexagon.svg'); background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0.2;"></div>

        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8 text-white">
            <h2 class="text-lg md:text-2xl lg:text-3xl font-bold mb-2 md:mb-3">
                ¡Comienza a anunciar hoy mismo!
            </h2>
            <p class="text-sm md:text-base lg:text-lg mb-4 md:mb-6 opacity-90 leading-relaxed">
                Millones de usuarios están buscando su próxima propiedad.
                <br class="hidden md:block">
                ¡No esperes más para conectar con ellos!
            </p>
            
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 md:px-6 lg:px-8 py-2 md:py-3 lg:py-4 border border-transparent text-sm md:text-base lg:text-lg font-bold rounded-lg shadow-lg text-indigo-600 bg-white hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-rocket mr-1 md:mr-2 text-sm"></i>
                Empieza Ahora
            </a>
        </div>
    </div>

</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.error('GSAP o ScrollTrigger no están disponibles');
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    let currentActiveStep = 1;
    const totalSteps = 4;
    const isMobile = window.innerWidth < 768;

    // Configuración del ScrollTrigger con inicio más tardío
   ScrollTrigger.create({
        trigger: "#steps-to-advertiser",
        start: isMobile ? "top top+=25%" : "top top+=15%", // Empezar más abajo para centrar mejor
        end: isMobile ? "+=200%" : "+=250%", // Ajustar proporcionalmente
        scrub: 0.1, // Más responsivo para cambios inmediatos
        pin: ".sticky-container",
        anticipatePin: 1,
        invalidateOnRefresh: true,
        pinSpacing: true,
        onUpdate: self => {
            const progress = self.progress;
            updateStepsProgressSmooth(progress);
        }
    });

    function updateStepsProgressSmooth(progress) {
        const stepSize = 1 / totalSteps;
        const currentProgress = Math.min(progress, 0.999);
        const newActiveStep = Math.min(Math.floor(currentProgress / stepSize) + 1, totalSteps);
        
        if (newActiveStep !== currentActiveStep) {
            currentActiveStep = newActiveStep;
            activateStep(currentActiveStep);
        }
    }

    function activateStep(stepNumber) {
        // Update progress circles
        for (let i = 1; i <= totalSteps; i++) {
            const stepItem = document.querySelector(`[data-step="${i}"]`);
            if (!stepItem) continue;
            
            const circle = stepItem.querySelector('.step-circle');
            const icon = stepItem.querySelector('.step-icon');
            const line = stepItem.querySelector('.step-line');
            
            if (i <= stepNumber) {
                circle.classList.remove('bg-gray-200');
                circle.classList.add('bg-blue-500');
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-white');
                
                if (line && i < totalSteps) {
                    line.classList.remove('bg-gray-200');
                    line.classList.add('bg-blue-500');
                }
            } else {
                circle.classList.remove('bg-blue-500');
                circle.classList.add('bg-gray-200');
                icon.classList.remove('text-white');
                icon.classList.add('text-gray-400');
                
                if (line) {
                    line.classList.remove('bg-blue-500');
                    line.classList.add('bg-gray-200');
                }
            }
        }
        
        // **CORRECCIÓN PRINCIPAL: Ocultar TODOS los contenidos primero**
        document.querySelectorAll('.step-content').forEach((content) => {
            if (parseInt(content.dataset.step) !== stepNumber) {
                // Ocultar inmediatamente los que no son el paso actual
                gsap.set(content, {
                    opacity: 0,
                    y: 30,
                    pointerEvents: 'none',
                    visibility: 'hidden', // **AGREGADO: visibility hidden**
                    zIndex: 1,
                });
            }
        });

        // **Luego mostrar SOLO el contenido activo**
        const activeContent = document.querySelector(`.step-content[data-step="${stepNumber}"]`);
        if (activeContent) {
            gsap.to(activeContent, {
                opacity: 1,
                y: 0,
                duration: 0.4,
                ease: "power2.out",
                pointerEvents: 'auto',
                visibility: 'visible', // **AGREGADO: visibility visible**
                zIndex: 10,
            });
        }
    }

    // Initialize the first step
    activateStep(1);

    ScrollTrigger.addEventListener("refresh", () => {
        currentActiveStep = 1;
        activateStep(1);
    });

    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            ScrollTrigger.refresh();
        }, 250);
    });
});
</script>
@endpush