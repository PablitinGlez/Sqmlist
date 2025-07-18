{{--
|--------------------------------------------------------------------------
| Navigation Menu Blade
|--------------------------------------------------------------------------
|
| Este archivo Blade renderiza la barra de navegación principal de la aplicación.
| Toda la lógica PHP para los enlaces y el botón dinámico ahora se gestiona
| a través del View Composer 'NavigationComposer' para una mejor separación
| de responsabilidades.
|
--}}
<nav x-data="{
    open: false,
    scrolled: false
}" 
@scroll.window="scrolled = window.scrollY > 10"
x-init="scrolled = window.scrollY > 10"
:class="{
    'bg-white/10 backdrop-blur-md border-b border-gray-200': scrolled,
    'bg-white border-b border-gray-100': !scrolled
}" 
class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="bold text-xl"  wire:navigate>Logo</a>
                </div>

                @include('layouts.includes.app.navigation-links-desktop')
            </div>

            @include('layouts.includes.app.auth-buttons-desktop')

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    :class="{
                        'text-gray-800 hover:text-gray-200 hover:bg-white/20': scrolled,
                        'text-gray-400 hover:text-gray-500 hover:bg-gray-100': !scrolled
                    }"
                    class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @include('layouts.includes.app.responsive-menu')
</nav>