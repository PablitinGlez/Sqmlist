<section class="py-16 bg-white" data-scroll-section>
    <x-partials.container>
        <!-- Encabezado de la sección -->
        <div class="text-center mb-12" data-scroll-header>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" data-scroll-title>
                Tu hogar perfecto te está esperando
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto" data-scroll-subtitle>
                Somos expertos en conectar personas con propiedades excepcionales. 
                Con años de experiencia en el mercado inmobiliario, te ayudamos a encontrar 
                el lugar perfecto para llamar hogar.
            </p>
        </div>

        <!-- Grid de servicios principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" data-scroll-services>
            <!-- Comprar -->
            <div class="text-center group" data-scroll-card>
                <div class="bg-blue-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-100 transition-colors duration-300" data-scroll-icon>
                    <i class="fas fa-home text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3" data-scroll-card-title>Comprar</h3>
                <p class="text-gray-600 leading-relaxed" data-scroll-card-text>
                    Encuentra la propiedad de tus sueños con nuestra selección 
                    de casas, apartamentos y terrenos en las mejores ubicaciones.
                </p>
                
            </div>

            <!-- Vender -->
            <div class="text-center group" data-scroll-card>
                <div class="bg-green-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-100 transition-colors duration-300" data-scroll-icon>
                    <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3" data-scroll-card-title>Vender</h3>
                <p class="text-gray-600 leading-relaxed" data-scroll-card-text>
                    Obtén el mejor precio por tu propiedad con nuestro equipo de expertos 
                    en marketing inmobiliario y valoración profesional.
                </p>
            </div>

            <!-- Rentar -->
            <div class="text-center group" data-scroll-card>
                <div class="bg-purple-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-100 transition-colors duration-300" data-scroll-icon>
                    <i class="fas fa-key text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3" data-scroll-card-title>Rentar</h3>
                <p class="text-gray-600 leading-relaxed" data-scroll-card-text>
                    Descubre opciones de renta que se adapten a tu estilo de vida 
                    y presupuesto, desde estudios hasta casas familiares.
                </p>
            </div>
        </div>
    </x-partials.container>
</section>

{{-- @push('scripts')
<script src="{{ asset('public/js/scroll-animations.js') }}"></script>
@endpush --}}