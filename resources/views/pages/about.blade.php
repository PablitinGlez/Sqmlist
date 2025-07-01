{{--
    Esta vista presenta información "Acerca de Nosotros", incluyendo la historia de la empresa,
    nuestra misión, visión, valores, y el equipo.
--}}
<x-app-layout>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-blue-600 text-white mt-16 py-24">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Tu Hogar Ideal Te Espera
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Con más de 15 años de experiencia, somos líderes en el mercado inmobiliario,
                conectando sueños con realidades.
            </p>
        </div>
    </section>

    <!-- Separador -->
    <div class="h-px bg-gray-200"></div>

    <!-- Nuestra Historia -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">
                        Nuestra Historia
                    </h2>
                    <p class="text-lg text-gray-600 mb-4">
                        Fundada en 2009, nuestra inmobiliaria nació con la visión de transformar
                        la experiencia de compra y venta de propiedades. Lo que comenzó como un
                        pequeño equipo de dos personas, hoy se ha convertido en una de las
                        agencias más confiables de la región.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Hemos ayudado a más de 2,500 familias a encontrar su hogar perfecto,
                        construyendo relaciones duraderas basadas en la confianza,
                        transparencia y resultados excepcionales.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">15+</div>
                            <div class="text-sm text-gray-500">Años de experiencia</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">2,500+</div>
                            <div class="text-sm text-gray-500">Familias satisfechas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">98%</div>
                            <div class="text-sm text-gray-500">Satisfacción cliente</div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 lg:mt-0">
                    <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
                        <i class="fas fa-building text-6xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Separador -->
    <div class="h-px bg-gray-200"></div>

    <!-- Misión, Visión y Valores -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Nuestros Principios
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Los valores que nos guían en cada transacción y relación con nuestros clientes.
                </p>
            </div>

            <div class="space-y-20">
                <!-- Misión - Texto izquierda, imagen derecha -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                    <div class="order-1 lg:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6 mision-title">
                            Misión
                        </h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Brindamos a nuestros clientes una experiencia inmobiliaria personalizada, profesional,
                            tecnológica y segura, ofreciendo soluciones integrales en la renta y venta de propiedades.
                        </p>
                        <p class="text-lg text-gray-600">
                            Nos comprometemos a facilitar el proceso de compra, venta y alquiler de propiedades,
                            brindando un servicio que supere las expectativas de nuestros clientes en cada transacción.
                        </p>
                    </div>
                    <div class="order-2 lg:order-2 mt-8 lg:mt-0">
                        <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg h-80 shadow-lg">
                            <img
                                src="{{ asset('images/image.png') }}"
                                alt="Tachuela"
                                class="absolute -top- -right-6 z-10 w-12 h-12 drop-shadow-md"
                                style="transform: rotate(10deg);"
                            >

                            <img src="{{ asset('images/mision.jpg') }}" alt="Imagen de Misión" class="w-full h-full object-cover rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Visión - Imagen izquierda, texto derecha -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                    <div class="order-2 lg:order-1">
                        <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg h-80 flex items-center justify-center shadow-lg">
                            <div class="text-center">
                                <i class="fas fa-city text-6xl text-orange-600 mb-4"></i>
                                <p class="text-orange-800 font-semibold">Oficina con Vista Panorámica</p>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 lg:order-2 mt-8 lg:mt-0">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6 vision-title">
                            Visión
                        </h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Ser la inmobiliaria líder en la ciudad de Puebla con el objetivo de expandir nuestros servicios
                            en las principales ciudades del país.
                        </p>
                        <p class="text-lg text-gray-600">
                            Aspiramos a ser reconocidos por nuestra innovación, integridad y compromiso con la satisfacción
                            del cliente, estableciendo nuevos estándares en el mercado inmobiliario nacional.
                        </p>
                    </div>
                </div>

                <!-- Valores - Texto izquierda, imagen derecha -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                    <div class="order-1 lg:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6 valores-title">
                            Valores
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Transparencia</h4>
                                    <p class="text-gray-600">Información clara y honesta en cada transacción.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Profesionalismo</h4>
                                    <p class="text-gray-600">Excelencia y competencia en cada servicio.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Compromiso</h4>
                                    <p class="text-gray-600">Dedicación total a los objetivos de nuestros clientes.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Innovación</h4>
                                    <p class="text-gray-600">Tecnología de vanguardia para mejores resultados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-2 lg:order-2 mt-8 lg:mt-0">
                        <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-lg h-80 flex items-center justify-center shadow-lg">
                            <div class="text-center">
                                <i class="fas fa-award text-6xl text-green-600 mb-4"></i>
                                <p class="text-green-800 font-semibold">Certificaciones y Reconocimientos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Separador -->
    <div class="h-px bg-gray-200"></div>

    <!-- Nuestro Equipo -->
    <section class="py-16 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Nuestro Equipo
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Profesionales expertos comprometidos con tu éxito inmobiliario.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Miembro del equipo 1 -->
                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">María González</h3>
                    <p class="text-blue-600 mb-2">Directora General</p>
                </div>

                <!-- Miembro del equipo 2 -->
                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Carlos Rodríguez</h3>
                    <p class="text-blue-600 mb-2">Agente Senior</p>
                </div>

                <!-- Miembro del equipo 3 -->
                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Ana Martínez</h3>
                    <p class="text-blue-600 mb-2">Especialista en Marketing</p>
                </div>
            </div>
        </div>
    </section>

    <section class="skewed-section">
        <div class="skewed-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-white mb-4">
                        ¿Por Qué Elegirnos?
                    </h2>
                    <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                        Las ventajas que nos convierten en tu mejor opción inmobiliaria.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition-all duration-300">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Respuesta Rápida</h3>
                            <p class="text-gray-200">Respondemos a todas las consultas en menos de 2 horas.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition-all duration-300">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Transacciones Seguras</h3>
                            <p class="text-gray-200">Proceso legal transparente y seguro en cada operación.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition-all duration-300">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Análisis de Mercado</h3>
                            <p class="text-gray-200">Evaluaciones precisas basadas en datos del mercado actual.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition-all duration-300">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Asesoría Personalizada</h3>
                            <p class="text-gray-200">Atención one-to-one adaptada a tus necesidades específicas.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition-all duration-300">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-camera text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Marketing Premium</h3>
                            <p class="text-gray-200">Fotografía profesional y tours virtuales para cada propiedad.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white/10 backdrop-blur-sm rounded-lg p-6 hover:bg-white/20 transition-all duration-300">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-mobile-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-2">Tecnología Avanzada</h3>
                            <p class="text-gray-200">Plataforma digital moderna para búsquedas eficientes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>


<style>
    .skewed-section {
        position: relative;
        background: linear-gradient(135deg, #4784e6 0%, #4a89ff 100%);
        transform: skewY(-2deg);
        margin: 4rem 0;
    }
    .skewed-content {
        transform: skewY(2deg);
        padding: 4rem 0;
    }
    .skewed-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #67b7fa;
background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='192' height='192' viewBox='0 0 192 192'%3E%3Cpath fill='%23ffffff' fill-opacity='0.30' d='M192 15v2a11 11 0 0 0-11 11c0 1.94 1.16 4.75 2.53 6.11l2.36 2.36a6.93 6.93 0 0 1 1.22 7.56l-.43.84a8.08 8.08 0 0 1-6.66 4.13H145v35.02a6.1 6.1 0 0 0 3.03 4.87l.84.43c1.58.79 4 .4 5.24-.85l2.36-2.36a12.04 12.04 0 0 1 7.51-3.11 13 13 0 1 1 .02 26 12 12 0 0 1-7.53-3.11l-2.36-2.36a4.93 4.93 0 0 0-5.24-.85l-.84.43a6.1 6.1 0 0 0-3.03 4.87V143h35.02a8.08 8.08 0 0 1 6.66 4.13l.43.84a6.91 6.91 0 0 1-1.22 7.56l-2.36 2.36A10.06 10.06 0 0 0 181 164a11 11 0 0 0 11 11v2a13 13 0 0 1-13-13 12 12 0 0 1 3.11-7.53l2.36-2.36a4.93 4.93 0 0 0 .85-5.24l-.43-.84a6.1 6.1 0 0 0-4.87-3.03H145v35.02a8.08 8.08 0 0 1-4.13 6.66l-.84.43a6.91 6.91 0 0 1-7.56-1.22l-2.36-2.36A10.06 10.06 0 0 0 124 181a11 11 0 0 0-11 11h-2a13 13 0 0 1 13-13c2.47 0 5.79 1.37 7.53 3.11l2.36 2.36a4.94 4.94 0 0 0 5.24.85l.84-.43a6.1 6.1 0 0 0 3.03-4.87V145h-35.02a8.08 8.08 0 0 1-6.66-4.13l-.43-.84a6.91 6.91 0 0 1 1.22-7.56l2.36-2.36A10.06 10.06 0 0 0 107 124a11 11 0 0 0-22 0c0 1.94 1.16 4.75 2.53 6.11l2.36 2.36a6.93 6.93 0 0 1 1.22 7.56l-.43.84a8.08 8.08 0 0 1-6.66 4.13H49v35.02a6.1 6.1 0 0 0 3.03 4.87l.84.43c1.58.79 4 .4 5.24-.85l2.36-2.36a12.04 12.04 0 0 1 7.51-3.11A13 13 0 0 1 81 192h-2a11 11 0 0 0-11-11c-1.94 0-4.75 1.16-6.11 2.53l-2.36 2.36a6.93 6.93 0 0 1-7.56 1.22l-.84-.43a8.08 8.08 0 0 1-4.13-6.66V145H11.98a6.1 6.1 0 0 0-4.87 3.03l-.43.84c-.79 1.58-.4 4 .85 5.24l2.36 2.36a12.04 12.04 0 0 1 3.11 7.51A13 13 0 0 1 0 177v-2a11 11 0 0 0 11-11c0-1.94-1.16-4.75-2.53-6.11l-2.36-2.36a6.93 6.93 0 0 1-1.22-7.56l.43-.84a8.08 8.08 0 0 1 6.66-4.13H47v-35.02a6.1 6.1 0 0 0-3.03-4.87l-.84-.43c-1.59-.8-4-.4-5.24.85l-2.36 2.36A12 12 0 0 1 28 109a13 13 0 1 1 0-26c2.47 0 5.79 1.37 7.53 3.11l2.36 2.36a4.94 4.94 0 0 0 5.24.85l.84-.43A6.1 6.1 0 0 0 47 84.02V49H11.98a8.08 8.08 0 0 1-6.66-4.13l-.43-.84a6.91 6.91 0 0 1 1.22-7.56l2.36-2.36A10.06 10.06 0 0 0 11 28 11 11 0 0 0 0 17v-2a13 13 0 0 1 13 13c0 2.47-1.37 5.79-3.11 7.53l-2.36 2.36a4.94 4.94 0 0 0-.85 5.24l.43.84A6.1 6.1 0 0 0 11.98 47H47V11.98a8.08 8.08 0 0 1 4.13-6.66l.84-.43a6.91 6.91 0 0 1 7.56 1.22l2.36 2.36A10.06 10.06 0 0 0 68 11 11 11 0 0 0 79 0h2a13 13 0 0 1-13 13 12 12 0 0 1-7.53-3.11l-2.36-2.36a4.93 4.93 0 0 0-5.24-.85l-.84.43A6.1 6.1 0 0 0 49 11.98V47h35.02a8.08 8.08 0 0 1 6.66 4.13l.43.84a6.91 6.91 0 0 1-1.22 7.56l-2.36 2.36A10.06 10.06 0 0 0 85 68a11 11 0 0 0 22 0c0-1.94-1.16-4.75-2.53-6.11l-2.36-2.36a6.93 6.93 0 0 1-1.22-7.56l.43-.84a8.08 8.08 0 0 1 6.66-4.13H143V11.98a6.1 6.1 0 0 0-3.03-4.87l-.84-.43c-1.59-.8-4-.4-5.24.85l-2.36 2.36A12 12 0 0 1 124 13a13 13 0 0 1-13-13h2a11 11 0 0 0 11 11c1.94 0 4.75-1.16 6.11-2.53l2.36-2.36a6.93 6.93 0 0 1 7.56-1.22l.84.43a8.08 8.08 0 0 1 4.13 6.66V47h35.02a6.1 6.1 0 0 0 4.87-3.03l.43-.84c.8-1.59.4-4-.85-5.24l-2.36-2.36A12 12 0 0 1 179 28a13 13 0 0 1 13-13zM84.02 143a6.1 6.1 0 0 0 4.87-3.03l.43-.84c.8-1.59.4-4-.85-5.24l-2.36-2.36A12 12 0 0 1 83 124a13 13 0 1 1 26 0c0 2.47-1.37 5.79-3.11 7.53l-2.36 2.36a4.94 4.94 0 0 0-.85 5.24l.43.84a6.1 6.1 0 0 0 4.87 3.03H143v-35.02a8.08 8.08 0 0 1 4.13-6.66l.84-.43a6.91 6.91 0 0 1 7.56 1.22l2.36 2.36A10.06 10.06 0 0 0 164 107a11 11 0 0 0 0-22c-1.94 0-4.75 1.16-6.11 2.53l-2.36 2.36a6.93 6.93 0 0 1-7.56 1.22l-.84-.43a8.08 8.08 0 0 1-4.13-6.66V49h-35.02a6.1 6.1 0 0 0-4.87 3.03l-.43.84c-.79 1.58-.4 4 .85 5.24l2.36 2.36a12.04 12.04 0 0 1 3.11 7.51A13 13 0 1 1 83 68a12 12 0 0 1 3.11-7.53l2.36-2.36a4.93 4.93 0 0 0 .85-5.24l-.43-.84A6.1 6.1 0 0 0 84.02 49H49v35.02a8.08 8.08 0 0 1-4.13 6.66l-.84.43a6.91 6.91 0 0 1-7.56-1.22l-2.36-2.36A10.06 10.06 0 0 0 28 85a11 11 0 0 0 0 22c1.94 0 4.75-1.16 6.11-2.53l2.36-2.36a6.93 6.93 0 0 1 7.56-1.22l.84.43a8.08 8.08 0 0 1 4.13 6.66V143h35.02z'%3E%3C/path%3E%3C/svg%3E");
        opacity: 0.3;
    }
</style>