{{--
|--------------------------------------------------------------------------
| Desktop Authentication Buttons Blade
|--------------------------------------------------------------------------
|
| Componente adaptado para usar correctamente los roles de Spatie Permission
| con verificaciones mejoradas de roles y permisos.
|
--}}
<div class="hidden sm:flex sm:items-center space-x-2 lg:space-x-4">
    @if ($shouldShowButton)
        <a href="{{ $buttonRoute }}"
           class="inline-flex items-center px-2 py-2 md:px-3 lg:px-4 {{ $buttonClass }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide transition ease-in-out duration-150">
            {{ $buttonText }}
        </a>
    @endif

    @guest
        <a href="{{ route('login') }}"
           class="inline-flex items-center px-2 py-2 md:px-3 lg:px-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide hover:bg-gray-700 transition ease-in-out duration-150">
            Ingresar
        </a>
    @endguest

    @auth
        {{-- Componente de notificaciones --}}
        @livewire('notifications-dropdown')

        <div class="ms-1 md:ms-3 relative">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="size-8 rounded-full object-cover"
                                 src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </button>
                    @else
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                :class="{
                                    'text-white bg-white/20 hover:text-gray-200 hover:bg-white/30': scrolled,
                                    'text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50': !scrolled
                                }"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                                {{ Auth::user()->name }}
                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </span>
                    @endif
                </x-slot>

                <x-slot name="content">
                    <!-- Sección de Cuenta -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                    <x-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Sección de Paneles -->
                    @if (Auth::user()->hasRole('admin'))
                        <div class="border-t border-gray-200"></div>
                        <x-dropdown-link href="{{ route('filament.admin.pages.dashboard') }}">
                            <div class="flex items-center">
                                <x-heroicon-s-cog-6-tooth class="w-4 h-4 mr-2 text-gray-500"/>
                                Panel Administrativo
                            </div>
                        </x-dropdown-link>
                    @endif

                    @if (Auth::user()->hasAnyRole(['owner', 'agent', 'real_estate_company']))
                        <div class="border-t border-gray-200"></div>
                        <x-dropdown-link href="{{ route('dashboard') }}">
                            <div class="flex items-center">
                                <x-heroicon-s-home-modern class="w-4 h-4 mr-2 text-gray-500"/>
                                Panel de Anunciante
                            </div>
                        </x-dropdown-link>
                        
                        @if(Auth::user()->hasRole('agent'))
                            <x-dropdown-link href="{{ route('agent.properties.index') }}">
                                <div class="flex items-center">
                                    <x-heroicon-s-building-office class="w-4 h-4 mr-2 text-gray-500"/>
                                    Mis Propiedades
                                </div>
                            </x-dropdown-link>
                        @endif
                    @endif

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                            {{ __('API Tokens') }}
                        </x-dropdown-link>
                    @endif

                    <!-- Cerrar sesión -->
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            <div class="flex items-center text-red-600">
                                <x-heroicon-s-arrow-left-on-rectangle class="w-4 h-4 mr-2"/>
                                {{ __('Log Out') }}
                            </div>
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    @endauth
</div>