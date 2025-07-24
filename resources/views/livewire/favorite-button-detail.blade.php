<div x-data="{ isFavorited: @json($isFavorited), tooltip: false }" class="relative inline-block">
    <button
        class="inline-flex items-center px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-sm text-xs sm:text-sm font-medium bg-white transition-colors duration-200
               "
               :class="{
                   'text-red-500 hover:bg-red-50': isFavorited,
                   'text-gray-700 hover:bg-gray-50': ! isFavorited
               }"
        @if(Auth::check())
            wire:click.prevent="toggleFavorite"
            @mouseenter="tooltip = true"
            @mouseleave="tooltip = false"
        @else
            @mouseenter="tooltip = true"
            @mouseleave="tooltip = false"
        @endif
    >
        <i class="mr-0 sm:mr-2" :class="{ 'fas fa-heart': isFavorited, 'far fa-heart': ! isFavorited }"></i>
        <span class="hidden sm:inline" x-text="isFavorited ? 'Guardado' : 'Guardar'"></span>
    </button>

    <div x-show="tooltip"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="absolute top-12 z-30 px-3 py-1 text-xs text-white bg-gray-800 rounded-md shadow-lg whitespace-nowrap
               left-1/2 -translate-x-1/2
               sm:left-1/2 sm:-translate-x-1/2
               w-max max-w-[calc(100vw-2rem)] text-center
               "
        style="display: none;"
    >
        @auth
            <span x-text="isFavorited ? 'Eliminar de favoritos' : 'Añadir a favoritos'"></span>
        @else
            <span>Iniciar sesión para añadir a favoritos</span>
        @endauth
    </div>
</div>