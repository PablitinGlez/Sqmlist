{{--
    Esta sección define el componente Hero principal, incluyendo
    un esqueleto de carga, la imagen de fondo y el contenido interactivo
    con el formulario de búsqueda de propiedades.
--}}
@props(['backgroundImage' => 'images/ola.webp', 'title' => 'Conecta con tu nuevo hogar en solo unos clics'])

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
             loading="eager">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- Contenido principal con efecto cristalizado -->
    <div id="hero-content" class="relative z-10 w-full opacity-0 transition-opacity duration-500">
        <x-partials.container>
            <!-- Div cristalizado -->
            <div class="backdrop-blur-sm bg-white/5 border border-white/10 rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-start md:text-center sm:text-center shadow-2xl">
                <!-- Título principal con cursor -->
                <div class="relative max-w-2xl mx-auto">
                    <h1 id="hero-title"
                    class="text-xl sm:text md:text-2xl lg:text-2xl font-semibold text-white mb-8 leading-tight"
                    data-original-text="Conecta con tu nuevo hogar en solo unos clics">
                    Conecta con tu nuevo hogar en solo unos <span id="clics-word" class="relative">clics</span>
                    </h1>
                    
                    <!-- Cursor SVG - Reposicionado con CSS y animación -->
                    <div id="cursor-icon" class="absolute top-2 right-4 pointer-events-none z-30 opacity-0">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="white"  stroke-width="1.5"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-click cursor-animate"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12l3 0" /><path d="M12 3l0 3" /><path d="M7.8 7.8l-2.2 -2.2" /><path d="M16.2 7.8l2.2 -2.2" /><path d="M7.8 16.2l-2.2 2.2" /><path d="M12 12l9 3l-4 2l-2 4l-3 -9" /></svg>
                    </div>
                </div>

                <!-- Barra de búsqueda -->
                @livewire('hero-search', [
                    'initialLocationSearch' => request('ubicacion'),
                    'initialPropertyType' => request('tipo'),
                    'initialOperationType' => request('operacion')
                ])
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
<script>
    function hideHeroSkeleton() {
        const skeleton = document.getElementById('hero-skeleton');
        const image = document.getElementById('hero-image');
        const content = document.getElementById('hero-content');
        const scrollIcon = document.getElementById('scroll-icon');

        if (skeleton && image && content && scrollIcon) {
            if (skeleton.style.display !== 'none' || skeleton.style.opacity !== '0') {
                skeleton.style.opacity = '0';
                setTimeout(() => {
                    skeleton.style.display = 'none';
                }, 200);

                image.style.opacity = '1';
                content.style.opacity = '1';
                scrollIcon.style.opacity = '1';

                document.dispatchEvent(new CustomEvent('hero-content-loaded'));
            }
        }
    }

    // Función mejorada con posicionamiento responsivo
    function positionCursorNearClics() {
        const clicsWord = document.getElementById('clics-word');
        const cursorIcon = document.getElementById('cursor-icon');
        
        if (clicsWord && cursorIcon) {
            const rect = clicsWord.getBoundingClientRect();
            const containerRect = clicsWord.closest('.relative').getBoundingClientRect();
            
            // Media queries en JavaScript para ajustes responsivos
            const screenWidth = window.innerWidth;
            let adjustments = { left: 5, top: -2 }; // Valores por defecto (desktop)
            
            // Ajustes específicos por tamaño de pantalla
            if (screenWidth <= 640) {
                // sm y menores (móviles) - más a la izquierda y arriba
                adjustments = { left: -12, top: -10 };
            } else if (screenWidth <= 768) {
                // md (tablets pequeñas)
                adjustments = { left: -8, top: -6 };
            } else if (screenWidth <= 1024) {
                // lg (tablets)
                adjustments = { left: 0, top: -4 };
            }
            
            const leftPosition = rect.right - containerRect.left + adjustments.left;
            const topPosition = rect.top - containerRect.top + adjustments.top;
            
            cursorIcon.style.left = leftPosition + 'px';
            cursorIcon.style.top = topPosition + 'px';
            cursorIcon.style.opacity = '1';
        }
    }

    // Función para animar el cursor con movimiento curveado (solo una vez)
    function animateCursor() {
        const cursorIcon = document.getElementById('cursor-icon');
        if (!cursorIcon) return;

        // Hacer visible el ícono
        cursorIcon.style.opacity = '1';

        let startTime = null;
        const duration = 3000; // 3 segundos
        
        function animate(currentTime) {
            if (startTime === null) startTime = currentTime;
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Crear movimiento curveado usando funciones trigonométricas
            const curveX = Math.sin(progress * Math.PI * 2) * 15; // Amplitud horizontal
            const curveY = Math.sin(progress * Math.PI * 4) * 8;  // Amplitud vertical más rápida
            
            // Aplicar la transformación
            cursorIcon.style.transform = `translate(${curveX}px, ${curveY}px)`;
            
            // Continuar la animación solo hasta completarla
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                // Animación completada, resetear posición y mantenerla
                cursorIcon.style.transform = `translate(0px, 0px)`;
            }
        }
        
        requestAnimationFrame(animate);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const heroImage = document.getElementById('hero-image');

        if (heroImage && heroImage.complete && heroImage.naturalHeight !== 0) {
            hideHeroSkeleton();
        } else if (heroImage) {
            heroImage.addEventListener('load', hideHeroSkeleton);
            heroImage.addEventListener('error', hideHeroSkeleton);
        } else {
            setTimeout(hideHeroSkeleton, 300);
        }

        // Posicionar el cursor después de que el contenido se cargue
        setTimeout(positionCursorNearClics, 100);
        
        // Iniciar la animación del cursor después de 4 segundos
        setTimeout(() => {
            animateCursor();
        }, 4000);
    });

    document.addEventListener('livewire:navigated', () => {
        hideHeroSkeleton();
        setTimeout(positionCursorNearClics, 100);
        
        // Reiniciar la animación en navegación después de 4 segundos
        setTimeout(() => {
            animateCursor();
        }, 4000);
    });

    // Reposicionar el cursor cuando se redimensiona la ventana
    window.addEventListener('resize', () => {
        setTimeout(positionCursorNearClics, 50);
    });
</script>