{{--
|--------------------------------------------------------------------------
| Desktop Navigation Links Blade
|--------------------------------------------------------------------------
|
| Este archivo Blade renderiza los enlaces de navegación principales para
| la vista de escritorio. Recibe la variable '$navigationLinks' inyectada
| por el 'NavigationComposer', la cual contiene los datos para construir
| tanto los enlaces simples como los elementos con dropdown.
| También utiliza la variable '$scrolled' para adaptar estilos.
|
--}}
<div class="hidden space-x-3 lg:space-x-8 sm:-my-px sm:ms-6 lg:ms-10 sm:flex">
    @foreach ($navigationLinks as $item)
        @if(isset($item['dropdown']) && count($item['dropdown']) > 0)
            <div class="relative inline-flex items-center" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen"
                        @click.outside="dropdownOpen = false"
                    :class="{
                            'text-gray-800 hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
                            'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
                        }"
                    class="inline-flex items-center px-1 pt-1 pb-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out h-16">
                    <span class="text-xs md:text-sm">{{ $item['name'] }}</span>
                    <svg class="ml-1 h-3 w-3 md:h-4 md:w-4 transition-transform duration-200"
                            :class="{ 'rotate-180': dropdownOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="dropdownOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute top-full left-0 z-50 mt-1 w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                    x-cloak>
                    <div class="py-1">
                        @foreach($item['dropdown'] as $category => $options)
                            @if(is_array($options))
                                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                    {{ $category }}
                                </div>
                                @foreach($options as $option)
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition duration-150 ease-in-out">
                                        {{ $option }}
                                    </a>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div :class="{
                'text-gray-800 hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
                'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
            }" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                <a href="{{ $item['route'] }}" class="text-xs md:text-sm" wire:navigate>
                    {{ $item['name'] }}
                </a>
            </div>
        @endif
    @endforeach
</div>