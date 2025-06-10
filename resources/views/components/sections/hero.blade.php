{{-- resources/views/components/sections/hero.blade.php --}}
@props(['backgroundImage' => 'images/hero-ng1.jpeg'])

<section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
    <!-- Imagen de fondo -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset($backgroundImage) }}" 
             alt="Hero Background" 
             class="w-full h-full object-cover"
             loading="eager">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    
    <!-- Contenido principal con efecto cristalizado -->
    <div class="relative z-10 w-full">
        <x-partials.container>
            <!-- Div cristalizado -->
            <div class="backdrop-blur-sm bg-white/5 border border-white/10 rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-start md:text-center sm:text-center shadow-2xl">
                <!-- Título principal -->
                <h1 class="max-w-2xl mx-auto text-xl sm:text md:text-2xl lg:text-3xl font-medium text-white mb-8 leading-tight">
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
                    
                    <!-- Barra de búsqueda -->
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
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10">
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
    document.addEventListener('DOMContentLoaded', function() {
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