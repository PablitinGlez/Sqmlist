{{-- resources/views/components/timeline-section.blade.php --}}
<section class="timeline-section bg-gray-50 py-20 min-h-screen flex items-center">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Título de la sección -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    Pasos para Convertirse en Agente
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Sigue estos pasos simples para iniciar tu carrera como agente inmobiliario
                </p>
            </div>
            
            <!-- Línea de tiempo -->
            <div class="relative">
                <!-- Línea principal (estática) -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-gray-300 h-full timeline-line-bg"></div>
                
                <!-- Línea de progreso (animada) -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-blue-500 timeline-line"></div>
                
                <!-- Elementos de la línea de tiempo -->
                <div class="space-y-16">
                    <!-- Paso 1 -->
                    <div class="timeline-item relative flex items-center">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">1. Obtén tu Licencia</h3>
                                <p class="text-gray-600">
                                    Completa el curso de educación inmobiliaria y aprueba el examen de licencia estatal.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-md"></div>
                        <div class="w-1/2 pl-8"></div>
                    </div>
                    
                    <!-- Paso 2 -->
                    <div class="timeline-item relative flex items-center">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-md"></div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">2. Únete a una Agencia</h3>
                                <p class="text-gray-600">
                                    Encuentra una agencia inmobiliaria establecida que te ayude a comenzar tu carrera.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 3 -->
                    <div class="timeline-item relative flex items-center">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">3. Construye tu Red</h3>
                                <p class="text-gray-600">
                                    Desarrolla relaciones con clientes potenciales, otros agentes y profesionales del sector.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-md"></div>
                        <div class="w-1/2 pl-8"></div>
                    </div>
                    
                    <!-- Paso 4 -->
                    <div class="timeline-item relative flex items-center">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-md"></div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">4. Especialízate</h3>
                                <p class="text-gray-600">
                                    Enfócate en un nicho específico como propiedades comerciales, residenciales o de lujo.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 5 -->
                    <div class="timeline-item relative flex items-center">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">5. Crece tu Negocio</h3>
                                <p class="text-gray-600">
                                    Utiliza marketing digital, referencias y excelente servicio al cliente para expandir tu negocio.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-md"></div>
                        <div class="w-1/2 pl-8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.timeline-line-bg {
    z-index: 1;
}

.timeline-line {
    z-index: 2;
    height: 0%;
}

.timeline-item {
    z-index: 3;
}

/* Responsive para dispositivos móviles */
@media (max-width: 768px) {
    .timeline-item {
        flex-direction: column;
        text-align: center;
    }
    
    .timeline-item .w-1/2 {
        width: 100%;
        padding: 0;
        margin-bottom: 1rem;
    }
    
    .timeline-item .w-1/2:last-child {
        margin-bottom: 0;
    }
    
    .timeline-item .text-right {
        text-align: center;
    }
    
    .timeline-line-bg,
    .timeline-line {
        left: 20px;
        transform: none;
    }
    
    .timeline-item .absolute {
        position: static;
        transform: none;
        margin: 1rem auto;
        display: block;
    }
}
</style>