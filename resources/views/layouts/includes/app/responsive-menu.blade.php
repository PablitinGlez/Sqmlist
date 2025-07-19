{{-- Asumiendo que este es el contenido de tu responsive-navigation-menu.blade.php --}}

<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        {{-- Enlaces de navegación principales para el responsive menu --}}
        {{-- Ejemplo: <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"> --}}
        {{--             {{ __('Dashboard') }} --}}
        {{--         </x-responsive-nav-link> --}}
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="flex items-center px-4">
            @auth
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            @endauth
        </div>

        <div class="mt-3 space-y-1">
            @auth
                {{-- Enlace a Perfil --}}
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                {{-- ¡NUEVO! Enlace a Mis Favoritos para el menú responsive --}}
                <x-responsive-nav-link href="{{ route('user.favorites.index') }}" :active="request()->routeIs('user.favorites.index')" wire:navigate>
                    <div class="flex items-center">
                        <i class="fas fa-heart mr-2 text-red-500"></i> {{-- Icono de corazón --}}
                        Mis Favoritos
                    </div>
                </x-responsive-nav-link>

                {{-- Enlaces a Paneles (si aplica) --}}
                @if (Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link href="{{ route('filament.admin.pages.dashboard') }}" wire:navigate>
                        <div class="flex items-center">
                            <x-heroicon-s-cog-6-tooth class="w-4 h-4 mr-2 text-gray-500"/>
                            Panel Administrativo
                        </div>
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->hasAnyRole(['owner', 'agent', 'real_estate_company']))
                    <x-responsive-nav-link href="/dashboard" wire:navigate>
                        <div class="flex items-center">
                            <x-heroicon-s-home-modern class="w-4 h-4 mr-2 text-gray-500"/>
                            Panel de Anunciante
                        </div>
                    </x-responsive-nav-link>
                @endif

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" wire:navigate>
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">
                        <div class="flex items-center text-red-600">
                            <x-heroicon-s-arrow-left-on-rectangle class="w-4 h-4 mr-2"/>
                            {{ __('Log Out') }}
                        </div>
                    </x-responsive-nav-link>
                </form>
            @else
                {{-- Enlaces para usuarios no autenticados en responsive --}}
                <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')" wire:navigate>
                    Ingresar
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')" wire:navigate>
                    Registrarse
                </x-responsive-nav-link>
            @endauth
        </div>
    </div>
</div>
