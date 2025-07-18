{{--
|--------------------------------------------------------------------------
| Desktop Navigation Links Blade
|--------------------------------------------------------------------------
|
| Este archivo Blade renderiza los enlaces de navegación principales para
| la vista de escritorio. Ahora todos los enlaces son simples y directos,
| sin dropdowns.
| También utiliza la variable '$scrolled' para adaptar estilos.
|
--}}
<div class="hidden space-x-3 lg:space-x-8 sm:-my-px sm:ms-6 lg:ms-10 sm:flex">
    @foreach ($navigationLinks as $item)
        <div :class="{
            'text-gray-800 hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
            'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
        }" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
            <a href="{{ $item['route'] }}"
               class="text-xs md:text-sm {{ $item['active'] ? 'border-blue-500 text-blue-600' : '' }}" {{-- Aplica la clase 'active' --}}
               wire:navigate>
                {{ $item['name'] }}
            </a>
        </div>
    @endforeach
</div>
