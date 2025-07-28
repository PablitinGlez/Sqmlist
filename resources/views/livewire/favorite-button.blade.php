<div x-data="{ isFavorited: @json($isFavorited), tooltip: false }" class="relative inline-block">
    <button
        class="absolute top-3 right-3 z-20 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200"
        @if(Auth::check())
            wire:click.prevent="toggleFavorite"
            @mouseenter="tooltip = true"
            @mouseleave="tooltip = false"
        @else
            @mouseenter="tooltip = true"
            @mouseleave="tooltip = false"
        @endif
    >
        <i class="{{ $isFavorited ? 'fas fa-heart text-red-500' : 'far fa-heart text-white hover:text-red-400' }} text-base transition-colors"></i>
    </button>

    <div x-show="tooltip"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="absolute top-12 right-0 z-30 px-3 py-1 text-xs text-white bg-gray-800 rounded-md shadow-lg transform translate-x-1/2 -translate-y-1/2 whitespace-nowrap"
        style="display: none;"
    >
        @auth
            <span x-text="isFavorited ? 'Eliminar de favoritos' : 'Añadir a favoritos'"></span>
        @else
            <span>Iniciar sesión para añadir a favoritos</span>
        @endauth
    </div>
</div>