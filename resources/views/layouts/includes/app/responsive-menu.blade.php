{{--
|--------------------------------------------------------------------------
| Responsive Menu Blade
|--------------------------------------------------------------------------
|
| Este archivo Blade renderiza el menú de navegación completo para dispositivos
| móviles, incluyendo los enlaces principales con soporte para dropdowns,
| el botón dinámico "Publicar" / "Estado de Solicitud", y las opciones
| de autenticación y perfil de usuario. Recibe todas las variables necesarias
| del 'NavigationComposer'.
|
--}}
<div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    {{-- Enlaces de Navegación Responsive --}}
    <div class="pt-2 pb-3 space-y-1">
        @foreach ($navigationLinks as $item)
            @if(isset($item['dropdown']) && count($item['dropdown']) > 0)
                {{-- Elemento con Dropdown en Móvil --}}
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center justify-between w-full ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out"
                            :class="{
                                'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                                'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                            }">
                        <span>{{ $item['name'] }}</span>
                        <svg class="ms-2 size-4 transition-transform duration-200"
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
                         class="bg-white/90 backdrop-blur-sm rounded-md shadow-inner ring-1 ring-black ring-opacity-5 mx-4 mt-1"
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
                {{-- Elemento sin Dropdown en Móvil --}}
                <a href="{{ $item['route'] }}"
                   :class="{
                       'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                       'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                   }"
                   class="block ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out {{ $item['active'] ? 'border-indigo-400 bg-indigo-50' : '' }}">
                    {{ $item['name'] }}
                </a>
            @endif
        @endforeach
    </div>

    {{-- Sección de Botones de Autenticación y Perfil --}}
    <div :class="{
        'border-white/30': scrolled,
        'border-gray-200': !scrolled
    }" class="pt-4 pb-1 border-t">

        {{-- Botón dinámico "Publicar" / "Estado de Solicitud" --}}
        @if ($shouldShowButton)
            <div class="px-4 mb-3">
                <a href="{{ $buttonRoute }}"
                   class="block w-full text-center px-4 py-2 {{ $buttonClass }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                    {{ $buttonText }}
                </a>
            </div>
        @endif

        @guest
            <div class="px-4 space-y-2">
                <a href="{{ route('login') }}"
                   class="block w-full text-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Ingresar
                </a>
            </div>
        @endguest

        @auth
            <div :class="{
                'text-white': scrolled,
                'text-gray-800': !scrolled
            }" class="flex items-center px-4 mb-3">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                    <div :class="{
                        'text-gray-300': scrolled,
                        'text-gray-500': !scrolled
                    }" class="font-medium text-sm">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- Notificaciones (si tienes un componente de Livewire para móvil) --}}
                {{-- @livewire('notifications-responsive-button') --}}
                <a href="#" {{-- Actualiza esta ruta si tienes una página de notificaciones --}}
                   :class="{
                       'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                       'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                   }"
                   class="block ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                    Notificaciones
                </a>

                <a href="{{ route('profile.show') }}"
                   :class="{
                       'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                       'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                   }"
                   class="block ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('profile.show') ? 'border-indigo-400 bg-indigo-50' : '' }}">
                    {{ __('Profile') }}
                </a>

                @if($hasAdvertiserRole)
                    <a href="/dashboard" 
                       :class="{
                           'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                           'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                       }"
                       class="block ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Panel de Anunciante
                    </a>
                @endif

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <a href="{{ route('api-tokens.index') }}"
                       :class="{
                           'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                           'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                       }"
                       class="block ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        {{ __('API Tokens') }}
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit"
                            :class="{
                                'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                                'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                            }"
                            class="block w-full text-left ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>