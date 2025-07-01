{{--
    Esta sección define el componente Hero principal, incluyendo
    un esqueleto de carga, la imagen de fondo y el contenido interactivo
    con el formulario de búsqueda de propiedades.
--}}
@props(['backgroundImage' => 'images/ola.webp'])

<section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
    <!-- Skeleton Loader -->
    <div id="hero-skeleton" class="absolute inset-0 z-20 bg-gray-100 animate-pulse flex items-center justify-center">
        <!-- Skeleton de la imagen de fondo -->
        <div class="absolute inset-0 bg-gray-300"></div>

        <!-- Skeleton del contenido principal -->
        <div class="relative z-10 w-full">
            <x-partials.container>
                <!-- Skeleton del div cristalizado -->
                <div class="backdrop-blur-sm bg-gray-200/50 border border-gray-300/20 rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-start md:text-center sm:text-center shadow-2xl">

                    <!-- Skeleton del título -->
                    <div class="max-w-2xl text-xl sm:text md:text-2xl lg:text-2xl mb-8 leading-tight">
                        <div class="h-7 bg-gray-300 rounded-full mb-2"></div>

                    </div>

                    <!-- Skeleton de los botones En Venta / En Renta -->
                    <div class="flex justify-start max-w-2xl mx-auto">
                        <div class="flex gap-8">
                            <div class="h-8 w-16 bg-gray-300 rounded"></div>
                            <div class="h-8 w-16 bg-gray-300 rounded"></div>
                        </div>
                    </div>

                    <!-- Skeleton de la línea divisoria -->
                    <div class="max-w-2xl mx-auto h-px bg-gray-300 mb-8 mt-8"></div>

                    <!-- Skeleton de la barra de búsqueda -->
                    <div class="flex flex-col md:flex-row gap-4 max-w-2xl mx-auto">
                        <!-- Skeleton del dropdown -->
                        <div class="relative">
                            <div class="w-full md:w-48 h-12 bg-gray-300 rounded-lg"></div>
                        </div>

                        <!-- Skeleton del input de búsqueda -->
                        <div class="flex-1 relative">
                            <div class="w-full h-12 bg-gray-300 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </x-partials.container>
        </div>

        <!-- Skeleton del icono de scroll -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="w-6 h-10 bg-gray-300 rounded-full"></div>
        </div>
    </div>

    <!-- Imagen de fondo -->
    <div class="absolute inset-0 z-0">
        <img id="hero-image"
             src="{{ asset($backgroundImage) }}"
             alt="Hero Background"
             class="w-full h-full object-cover opacity-0 transition-opacity duration-500"
             loading="eager"
             onload="hideHeroSkeleton()">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- Contenido principal con efecto cristalizado -->
    <div id="hero-content" class="relative z-10 w-full opacity-0 transition-opacity duration-500">
        <x-partials.container>
            <!-- Div cristalizado -->
            <div class="backdrop-blur-sm bg-white/5 border border-white/10 rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-start md:text-center sm:text-center shadow-2xl">
                <!-- Título principal -->
                <h1 class="max-w-2xl text-xl sm:text md:text-2xl lg:text-2xl font-medium text-white mb-8 leading-tight">
                    {{ $title ?? 'Conecta con tu nuevo hogar en solo unos clics' }}
                </h1>

                <!-- Opciones En Venta / En Renta -->
                <div class="flex justify-start max-w-2xl mx-auto">
                    <div class="flex gap-8">
                        <button id="btn-venta"
                                class="property-type-btn px-4 py-2 text-white font-medium transition-all duration-300 relative active"
                                data-type="venta">
                            En Venta
                        </button>
                        <button id="btn-renta"
                                class="property-type-btn px-4 py-2 text-white font-medium transition-all duration-300 relative"
                                data-type="renta">
                            En Renta
                        </button>
                    </div>
                </div>

                <!-- Línea divisoria -->
                <div class="max-w-2xl mx-auto h-px bg-white/30 mb-8"></div>

                <!-- Barra de búsqueda -->
                <div class="flex flex-col md:flex-row gap-4 max-w-2xl mx-auto">
                    <!-- Dropdown -->
                    <div class="relative">
                        <select class="w-full md:w-48 px-4 py-3 bg-white/90 backdrop-blur-sm rounded-lg border border-white/20 text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="">Todos</option>
                            <option value="casa">Casa</option>
                            <option value="departamento">Departamento</option>
                            <option value="terreno">Terreno</option>
                            <option value="local">Local Comercial</option>
                        </select>
                    </div>

                    <!-- Input de búsqueda -->
                    <div class="flex-1 relative">
                        <input type="text"
                               placeholder="Buscar por ubicación..."
                               class="w-full px-4 py-3 pr-12 bg-white/90 backdrop-blur-sm rounded-lg border border-white/20 text-gray-800 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600 hover:text-blue-700 transition-colors">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </x-partials.container>
    </div>

    <!-- Icono de scroll animado -->
    <div id="scroll-icon" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 opacity-0 transition-opacity duration-500">
        <div class="animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>
</section>

<style>
    .property-type-btn {
        border-bottom: 2px solid transparent;
    }

    .property-type-btn.active {
        border-bottom: 2px solid #3b82f6;
    }

    .property-type-btn:hover:not(.active) {
        opacity: 0.8;
    }
</style>

<script>
    function hideHeroSkeleton() {
        const skeleton = document.getElementById('hero-skeleton');
        const image = document.getElementById('hero-image');
        const content = document.getElementById('hero-content');
        const scrollIcon = document.getElementById('scroll-icon');

        if (skeleton && image && content && scrollIcon) {
            // Ocultar skeleton
            skeleton.style.opacity = '0';
            setTimeout(() => {
                skeleton.style.display = 'none';
            }, 200);

            // Mostrar contenido real
            image.style.opacity = '1';
            content.style.opacity = '1';
            scrollIcon.style.opacity = '1';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Timeout de seguridad: ocultar skeleton después de 3 segundos máximo
        setTimeout(() => {
            const skeleton = document.getElementById('hero-skeleton');
            if (skeleton && skeleton.style.display !== 'none') {
                hideHeroSkeleton();
            }
        }, 300);

        // Manejar error de carga de imagen
        const heroImage = document.getElementById('hero-image');
        if (heroImage) {
            heroImage.addEventListener('error', function() {
                hideHeroSkeleton();
            });
        }

        // Funcionalidad de los botones
        const buttons = document.querySelectorAll('.property-type-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Remover clase active de todos los botones
                buttons.forEach(btn => btn.classList.remove('active'));

                // Agregar clase active al botón clickeado
                this.classList.add('active');

                // Lógica adicional si necesitas
                const selectedType = this.dataset.type;
                console.log('Tipo seleccionado:', selectedType);
            });
        });
    });
</script>
