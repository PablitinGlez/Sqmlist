@php
    $navigationLinks = [
        [
            'name' => 'En venta',
            'route' => '#',
            'active' => false,
            'dropdown' => [
                // Aquí irán las opciones del dropdown cuando las definas
                // 'Estado', 'Tipo de propiedad', 'Recámaras', etc.
                'Tipo de propiedad' => ['Casa', 'Departamento', 'Oficina', 'Local comercial'],
            ]
        ],
        [
            'name' => 'En renta',
            'route' => '#',
            'active' => false,
            'dropdown' => [
                // Aquí irán las opciones del dropdown cuando las definas

                'Tipo de propiedad' => ['Casa', 'Departamento', 'Oficina', 'Local comercial'],

                
            ]
        ],
        [
            'name' => 'Agentes',
            'route' => '#',
            'active' => false,
        ],
        [
            'name' => 'Nosotros',
            'route' => '#',
            'active' => false,
        ],
        [
            'name' => 'Contacto',
            'route' => '#',
            'active' => false,
        ],
    ];
@endphp

<nav x-data="{ 
    open: false, 
    openDropdown: null,
    scrolled: false,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 10;
        });
    }
}" 
    @scroll.window="scrolled = window.scrollY > 10"
    :class="{
        'bg-white/20 backdrop-blur-md border-b border-gray-200': scrolled,
        'bg-white border-b border-gray-100': !scrolled
    }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-3 lg:space-x-8 sm:-my-px sm:ms-6 lg:ms-10 sm:flex">
                    @foreach ($navigationLinks as $index => $item)
                        @if(isset($item['dropdown']) && count($item['dropdown']) > 0)
                            <!-- Dropdown Navigation Link -->
                            <div class="relative inline-flex items-center" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    :class="{
                                        'text-white hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
                                        'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
                                    }"
                                    class="inline-flex items-center px-1 pt-1 pb-1 border-b-2 text-sm lg:text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out h-16">
                                    <span class="text-xs md:text-sm">{{ $item['name'] }}</span>
                                    <svg class="ml-1 h-3 w-3 md:h-4 md:w-4 transition-transform duration-200" 
                                         :class="{ 'rotate-180': open }" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute top-full left-0 z-50 mt-1 w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        @if(count($item['dropdown']) > 0)
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
                                                @else
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition duration-150 ease-in-out">
                                                        {{ $options }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        @else
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Opciones próximamente
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Regular Navigation Link -->
                            <div :class="{
                                'text-white hover:text-gray-200 border-white/30 hover:border-white/50': scrolled,
                                'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300': !scrolled
                            }" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                <a href="{{ $item['route'] }}" class="text-xs md:text-sm">
                                    {{ $item['name'] }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center  space-x-2 lg:space-x-4">
                
                @guest
                    <!-- Botones para usuarios no autenticados -->
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-2 py-2 md:px-3 lg:px- bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide md:tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <span class="hidden lg:inline">Entrar como Agente</span>
                        <span class="lg:hidden">Ser Agente</span>
                    </a>
                    
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-2 py-2 md:px-3 lg:px-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide md:tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Ingresar
                    </a>
                @endguest

                @auth
                    <!-- Opciones para usuarios autenticados -->
                    
                    <!-- Botón Entrar como Agente (para solicitar ser agente) -->
                    <a href="#" 
                       class="inline-flex items-center px-2 py-2 md:px-3 lg:px-4 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide md:tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <span class="hidden lg:inline">Entrar como Agente</span>
                        <span class="lg:hidden">Ser Agente</span>
                    </a>

                    <!-- Icono de Notificaciones -->
                    <button :class="{
                        'text-white hover:text-gray-200': scrolled,
                        'text-gray-600 hover:text-gray-800': !scrolled
                    }" class="relative p-2 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" md:width="24" md:height="24" viewBox="0 0 24 24" 
                             fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" 
                             stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
                        </svg>
                        <!-- Badge de notificaciones (opcional) -->
                        <span class="absolute -top-0 -right-0 bg-red-500 text-white text-xs rounded-full h-3 w-3 md:h-4 md:w-4 flex items-center justify-center text-[10px] md:text-xs">
                            3
                        </span>
                    </button>

                    <!-- Dropdown de Perfil -->
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
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none active:bg-gray-50 transition ease-in-out duration-150">
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
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Menu Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    :class="{
                        'text-white hover:text-gray-200 hover:bg-white/20': scrolled,
                        'text-gray-400 hover:text-gray-500 hover:bg-gray-100': !scrolled
                    }"
                    class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Mobile Navigation Links -->
            @foreach ($navigationLinks as $item)
                <a href="{{ $item['route'] }}" 
                   :class="{
                       'text-white hover:text-gray-200 hover:bg-white/20 border-white/30': scrolled,
                       'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-transparent': !scrolled
                   }"
                   class="block ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out {{ $item['active'] ? 'border-indigo-400 bg-indigo-50' : '' }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </div>

        <!-- Mobile Authentication Section -->
        <div :class="{
            'border-white/30': scrolled,
            'border-gray-200': !scrolled
        }" class="pt-4 pb-1 border-t">
            @guest
                <!-- Mobile buttons for non-authenticated users -->
                <div class="px-4 space-y-2">
                    <a href="{{ route('register') }}" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        Entrar como Agente
                    </a>
                    <a href="{{ route('login') }}" 
                       class="block w-full text-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        Ingresar
                    </a>
                </div>
            @endguest

            @auth
                <!-- Mobile options for authenticated users -->
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

                <div class="px-4 mb-3">
                    <a href="#" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        Entrar como Agente
                    </a>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="#" 
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

                    <!-- Authentication -->
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
</nav>