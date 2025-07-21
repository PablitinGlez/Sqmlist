{{--
|--------------------------------------------------------------------------
| Desktop Navigation Links Blade - Con Dropdowns
|--------------------------------------------------------------------------
|
| Este archivo Blade renderiza los enlaces de navegación principales para
| la vista de escritorio. Ahora "En Venta" y "En Renta" son dropdowns
| con opciones de Estados, Tipos de Propiedad y Recámaras.
|
--}}

<div class="hidden space-x-3 lg:space-x-8 sm:-my-px sm:ms-6 lg:ms-10 sm:flex">
    @foreach ($navigationLinks as $item)
        @if (isset($item['type']) && $item['type'] === 'dropdown')
            {{-- Dropdown para En Venta / En Renta --}}
            <div class="relative flex items-center" x-data="{ open: false }" @click.outside="open = false">
                <div :class="{
                    'text-gray-800 hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
                    'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
                }"
                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out cursor-pointer"
                @click="open = !open">
                    <span class="text-xs md:text-sm {{ $item['active'] ? 'border-blue-500 text-blue-600' : '' }}">
                        {{ $item['name'] }}
                    </span>
                    {{-- Icono de flecha --}}
                    <svg class="ml-1 h-4 w-4 transition-transform duration-200"
                         :class="{'rotate-180': open}"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                {{-- Dropdown Menu --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-0 mt-2 top-full w-[28rem] bg-white rounded-lg shadow-lg border border-gray-200 z-50"> {{-- CAMBIO AQUÍ: Ejemplo con valor arbitrario --}}

                    <div class="p-4">
                        <div class="grid grid-cols-3 gap-4">
                            {{-- Columna de Estados --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Estados</h4>
                                <ul class="space-y-1">
                                    @foreach ($item['dropdown_items']['states'] as $state)
                                        <li>
                                            <a href="{{ route('properties.index', [
                                                'operacion' => $item['operacion'],
                                                'ubicacion' => $state->name
                                            ]) }}"
                                            class="block text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors duration-150"
                                            wire:navigate
                                            @click="open = false">
                                                {{ $state->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Columna de Tipos de Propiedad --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Tipo</h4>
                                <ul class="space-y-1">
                                    @foreach ($item['dropdown_items']['property_types'] as $type)
                                        <li>
                                            <a href="{{ route('properties.index', [
                                                'operacion' => $item['operacion'],
                                                'tipo' => \Illuminate\Support\Str::slug($type->name)
                                            ]) }}"
                                            class="block text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors duration-150"
                                            wire:navigate
                                            @click="open = false">
                                                {{ $type->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Columna de Recámaras --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Recámaras</h4>
                                <ul class="space-y-1">
                                    @foreach ($item['dropdown_items']['bedrooms'] as $bedroom)
                                        <li>
                                            <a href="{{ route('properties.index', [
                                                'operacion' => $item['operacion'],
                                                'recamaras' => $bedroom['value']
                                            ]) }}"
                                            class="block text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors duration-150"
                                            wire:navigate
                                            @click="open = false">
                                                {{ $bedroom['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Enlace principal del dropdown --}}
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ $item['route'] }}"
                               class="block text-center text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-150"
                               wire:navigate
                               @click="open = false">
                                Ver todas las propiedades {{ strtolower($item['name']) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Enlaces simples (Nosotros, Contacto) --}}
            <div :class="{
                'text-gray-800 hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
                'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
            }" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                <a href="{{ $item['route'] }}"
                   class="text-xs md:text-sm {{ $item['active'] ? 'border-blue-500 text-blue-600' : '' }}"
                   wire:navigate>
                    {{ $item['name'] }}
                </a>
            </div>
        @endif
    @endforeach
</div>