{{--
    Esta sección describe el proceso de publicación de un inmueble
    mediante una línea de tiempo interactiva y animada con Alpine.js.
    resources/views/components/sections/how-it-works.blade.php
    VERSIÓN RESPONSIVE MEJORADA
--}}
<section class="py-8 md:py-16 bg-white overflow-hidden" data-how-it-works-section>
    <x-partials.container>
        <div 
            x-data="timelineData()" 
            x-init="startTimeline()"
            class="max-w-7xl mx-auto"
        >
            <!-- Título -->
            <div class="text-center mb-8 md:mb-16 px-4">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">
                    Publica tu inmueble gratis y en simples pasos
                </h2>
                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                    Sigue estos pasos para publicar tu propiedad de manera rápida y sencilla
                </p>
            </div>

            <!-- Layout responsive: Stack en móvil, Grid en desktop -->
            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 lg:gap-12 items-start lg:items-center">
                
                <!-- Línea de tiempo - Versión móvil optimizada -->
                <div class="relative w-full order-2 lg:order-1">
                    <!-- Línea vertical - más pequeña en móvil -->
                    <div class="absolute left-4 md:left-8 top-0 w-0.5 h-full bg-gray-200 z-0"></div>
                    <!-- Línea de progreso -->
                    <div 
                        class="absolute left-4 md:left-8 top-0 w-0.5 bg-gradient-to-b from-blue-600 to-blue-500 transition-all duration-1000 ease-in-out z-0"
                        :style="`height: ${progressHeight}%`"
                    ></div>

                    <!-- Pasos - espaciado optimizado para móvil -->
                    <div class="space-y-4 md:space-y-8">
                        <template x-for="(step, index) in steps" :key="index">
                            <div 
                                class="flex items-start relative"
                                :class="{ 'opacity-100': step.completed, 'opacity-50': !step.completed }"
                            >
                                <!-- Círculo del paso - más pequeño en móvil -->
                                <div 
                                    class="relative w-8 h-8 md:w-12 lg:w-16 md:h-12 lg:h-16 rounded-full flex items-center justify-center text-sm md:text-base lg:text-lg font-bold transition-all duration-500 z-20 bg-white border-2 md:border-4 shrink-0"
                                    :class="step.completed ? 'border-blue-500 text-blue-500' : 'border-gray-300 text-gray-400'"
                                    :style="step.active ? `background: conic-gradient(#3b82f6 ${step.progress * 3.6}deg, #e5e7eb ${step.progress * 3.6}deg)` : ''"
                                >
                                    <!-- Círculo interno blanco -->
                                    <div 
                                        class="absolute inset-0.5 md:inset-1 bg-white rounded-full flex items-center justify-center z-30"
                                        x-show="step.active"
                                    >
                                        <span x-text="step.number" class="text-blue-500 font-bold text-xs md:text-sm lg:text-base z-30"></span>
                                    </div>
                                    
                                    <!-- Número del paso (cuando no está activo) -->
                                    <span x-show="!step.active" x-text="step.number" class="relative z-30 text-xs md:text-sm lg:text-base"></span>
                                    
                                    <!-- Ícono de completado -->
                                    <div 
                                        x-show="step.completed && !step.active"
                                        x-transition
                                        class="absolute inset-0 flex items-center justify-center text-white bg-blue-500 rounded-full z-50"
                                    >
                                        <i class="fas fa-check text-xs md:text-sm z-50"></i>
                                    </div>
                                </div>

                                <!-- Contenido del paso - espaciado optimizado -->
                                <div class="ml-3 md:ml-6 flex-1 min-w-0">
                                    <h3 
                                        class="text-sm md:text-lg lg:text-xl font-semibold mb-1 md:mb-2 transition-colors duration-300 leading-tight"
                                        :class="step.completed || step.active ? 'text-gray-900' : 'text-gray-500'"
                                        x-text="step.title"
                                    ></h3>
                                    <p 
                                        class="text-xs md:text-sm lg:text-base text-gray-600 transition-colors duration-300 leading-relaxed"
                                        :class="step.completed || step.active ? 'text-gray-600' : 'text-gray-400'"
                                        x-text="step.description"
                                    ></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Área de contenido visual - Optimizada para móvil -->
                <div class="relative w-full order-1 lg:order-2">
                    <!-- Altura responsive -->
                    <div class="relative h-64 md:h-80 lg:h-[500px] flex items-center justify-center">
                        <!-- Contenedor de imágenes -->
                        <div class="relative w-full h-full max-w-xs md:max-w-md lg:max-w-lg mx-auto">
                            <template x-for="(step, index) in steps" :key="index">
                                <div 
                                    x-show="currentStep === index"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <!-- Mockup de dispositivos responsive -->
                                    <div class="relative">
                                        <!-- Laptop mockup - tamaños responsivos -->
                                        <div class="bg-gray-800 rounded-lg p-1 md:p-2 shadow-2xl transform rotate-1">
                                            <div class="bg-white rounded-md overflow-hidden" style="width: 200px; height: 125px;">
                                                <!-- Barra superior del navegador -->
                                                <div class="h-4 md:h-6 bg-gray-100 flex items-center px-2 md:px-3 space-x-1">
                                                    <div class="w-1.5 h-1.5 md:w-2 md:h-2 bg-red-500 rounded-full"></div>
                                                    <div class="w-1.5 h-1.5 md:w-2 md:h-2 bg-yellow-500 rounded-full"></div>
                                                    <div class="w-1.5 h-1.5 md:w-2 md:h-2 bg-green-500 rounded-full"></div>
                                                </div>
                                                <!-- Contenido -->
                                                <div class="p-2 md:p-4 h-full bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col justify-center items-center">
                                                    <div class="text-center">
                                                        <i :class="step.icon" class="text-2xl md:text-3xl lg:text-4xl text-blue-500 mb-1 md:mb-3"></i>
                                                        <h4 class="text-xs md:text-sm font-semibold text-gray-800 mb-1" x-text="step.title"></h4>
                                                        <p class="text-xs text-gray-600 hidden md:block" x-text="step.shortDescription"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Phone mockup - tamaño optimizado para móvil -->
                                        <div class="absolute -bottom-2 md:-bottom-4 -right-4 md:-right-8 bg-gray-800 rounded-xl md:rounded-2xl p-0.5 md:p-1 shadow-xl transform -rotate-12 scale-75 md:scale-75">
                                            <div class="bg-white rounded-lg md:rounded-xl overflow-hidden" style="width: 80px; height: 140px;">
                                                <div class="h-1 md:h-2 bg-gray-100"></div>
                                                <div class="px-2 md:px-3 py-1 md:py-2 h-full bg-gradient-to-b from-blue-50 to-blue-100 flex flex-col justify-center items-center">
                                                    <i :class="step.icon" class="text-lg md:text-2xl text-blue-500 mb-1 md:mb-2"></i>
                                                    <div class="text-center">
                                                        <div class="w-10 md:w-16 h-0.5 md:h-1 bg-gray-300 rounded mb-1"></div>
                                                        <div class="w-8 md:w-12 h-0.5 md:h-1 bg-gray-200 rounded"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de acción - responsive -->
            <div class="text-center mt-8 md:mt-12 px-4">
                <button 
                    @click="restartTimeline()"
                    class="inline-flex items-center px-6 md:px-8 py-3 md:py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-sm md:text-base"
                >
                    <i class="fas fa-plus-circle mr-2"></i>
                    Comienza a publicar
                </button>
                
                <!-- Controles adicionales - ocultos en móvil pequeño -->
                <div class="mt-4 md:mt-6 flex justify-center space-x-3 md:space-x-4">
                    <button 
                        @click="pauseTimeline()" 
                        x-show="!isPaused"
                        class="text-gray-500 hover:text-gray-700 text-xs md:text-sm flex items-center"
                    >
                        <i class="fas fa-pause mr-1"></i>
                        <span class="hidden sm:inline">Pausar</span>
                    </button>
                    <button 
                        @click="resumeTimeline()" 
                        x-show="isPaused"
                        class="text-blue-500 hover:text-blue-600 text-xs md:text-sm flex items-center"
                    >
                        <i class="fas fa-play mr-1"></i>
                        <span class="hidden sm:inline">Continuar</span>
                    </button>
                    <button 
                        @click="restartTimeline()" 
                        class="text-gray-500 hover:text-gray-700 text-xs md:text-sm flex items-center"
                    >
                        <i class="fas fa-redo mr-1"></i>
                        <span class="hidden sm:inline">Reiniciar</span>
                    </button>
                </div>
            </div>
        </div>
    </x-partials.container>
</section>

<script>
function timelineData() {
    return {
        currentStep: 0,
        progressHeight: 0,
        isPaused: false,
        intervalId: null,
        stepProgress: 0,
        
        steps: [
            {
                number: 1,
                title: 'Ubica tu inmueble en el mapa',
                description: 'Selecciona la ubicación exacta de tu propiedad en nuestro mapa interactivo',
                shortDescription: 'Ubicación en mapa',
                icon: 'fas fa-map-marker-alt',
                completed: false,
                active: true,
                progress: 0
            },
            {
                number: 2,
                title: 'Cuéntanos cómo es',
                description: 'Describe las características principales de tu inmueble: habitaciones, baños, etc.',
                shortDescription: 'Características',
                icon: 'fas fa-home',
                completed: false,
                active: false,
                progress: 0
            },
            {
                number: 3,
                title: 'Sube fotos y videos',
                description: 'Añade imágenes y videos de calidad para mostrar lo mejor de tu propiedad',
                shortDescription: 'Fotos y videos',
                icon: 'fas fa-camera',
                completed: false,
                active: false,
                progress: 0
            },
            {
                number: 4,
                title: 'Establece datos de contacto',
                description: 'Para que los interesados se comuniquen contigo',
                shortDescription: 'Datos de contacto',
                icon: 'fas fa-phone',
                completed: false,
                active: false,
                progress: 0
            }
        ],
        
        startTimeline() {
            this.resetTimeline();
            this.runTimeline();
        },
        
        resetTimeline() {
            this.currentStep = 0;
            this.progressHeight = 0;
            this.stepProgress = 0;
            this.steps.forEach((step, index) => {
                step.completed = false;
                step.active = index === 0;
                step.progress = 0;
            });
        },
        
        runTimeline() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
            }
            
            this.intervalId = setInterval(() => {
                if (this.isPaused) return;
                
                // Incrementar progreso del paso actual
                this.stepProgress += 1;
                this.steps[this.currentStep].progress = this.stepProgress;
                
                // Si el paso actual está completo (100%)
                if (this.stepProgress >= 100) {
                    // Marcar como completado
                    this.steps[this.currentStep].completed = true;
                    this.steps[this.currentStep].active = false;
                    
                    // Avanzar al siguiente paso
                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                        this.steps[this.currentStep].active = true;
                        this.stepProgress = 0;
                        
                        // Actualizar altura del progreso
                        this.progressHeight = (this.currentStep / (this.steps.length - 1)) * 100;
                    } else {
                        // Completar último paso
                        this.progressHeight = 100;
                        // Reiniciar después de un tiempo
                        setTimeout(() => {
                            this.startTimeline();
                        }, 3000);
                    }
                }
            }, 50); // 50ms = progreso suave
        },
        
        pauseTimeline() {
            this.isPaused = true;
        },
        
        resumeTimeline() {
            this.isPaused = false;
        },
        
        restartTimeline() {
            this.startTimeline();
        }
    }
}
</script>

<style>
/* Animaciones adicionales */
@keyframes pulse-blue {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
    }
}

.pulse-blue {
    animation: pulse-blue 2s infinite;
}

/* Mejoras responsive adicionales */
@media (max-width: 640px) {
    /* Asegurar que no hay overflow horizontal */
    .overflow-hidden {
        overflow-x: hidden;
    }
    
    /* Optimizar espaciado en móvil */
    [data-how-it-works-section] {
        padding-left: 0;
        padding-right: 0;
    }
}

/* Breakpoint específico para tablets */
@media (min-width: 641px) and (max-width: 1023px) {
    /* Ajustes específicos para tablets si es necesario */
    .lg\:grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}
</style>