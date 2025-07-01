{{--
    Esta sección muestra estadísticas clave de la empresa,
    como propiedades vendidas, clientes satisfechos y años de experiencia,
    para generar confianza en los usuarios.
--}}
<section class="py-16 bg-white">
    <x-partials.container>
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Números que nos Respaldan
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Nuestra experiencia y resultados hablan por sí solos en el mercado inmobiliario de Veracruz
            </p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 via-purple-50 to-green-50 rounded-3xl p-8 md:p-12 shadow-lg">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-home text-2xl text-blue-600"></i>
                        </div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { const interval = setInterval(() => { count < 850 ? count += 17 : clearInterval(interval) }, 20) }, 200)" x-text="count + '+'"></div>
                        <div class="text-gray-600 text-sm md:text-base font-medium">Propiedades Vendidas</div>
                    </div>
                </div>

                <div class="text-center group">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-heart text-2xl text-green-600"></i>
                        </div>
                        <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { const interval = setInterval(() => { count < 98 ? count += 2 : clearInterval(interval) }, 30) }, 400)" x-text="count + '%'"></div>
                        <div class="text-gray-600 text-sm md:text-base font-medium">Clientes Satisfechos</div>
                    </div>
                </div>

                <div class="text-center group">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                            <i class="fas fa-award text-2xl text-purple-600"></i>
                        </div>
                        <div class="text-3xl md:text-4xl font-bold text-purple-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { const interval = setInterval(() => { count < 12 ? count += 1 : clearInterval(interval) }, 100) }, 600)" x-text="count + '+'"></div>
                        <div class="text-gray-600 text-sm md:text-base font-medium">Años de Experiencia</div>
                    </div>
                </div>

                <div class="text-center group">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                            <i class="fas fa-clock text-2xl text-orange-600"></i>
                        </div>
                        <div class="text-3xl md:text-4xl font-bold text-orange-600 mb-2">24/7</div>
                        <div class="text-gray-600 text-sm md:text-base font-medium">Atención al Cliente</div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <p class="text-gray-700 font-medium">
                    Más de una década construyendo confianza en el mercado inmobiliario veracruzano
                </p>
            </div>
        </div>
    </x-partials.container>
</section>
