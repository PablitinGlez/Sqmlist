
@php
    $footerSections = [
        [
            'title' => 'Empresa',
            'links' => [
                ['name' => 'Acerca', 'route' => '#'],
                ['name' => 'Lorem', 'route' => '#'],
                ['name' => 'Lorem', 'route' => '#'],
            ]
        ],
        [
            'title' => 'Servicios',
            'links' => [
                ['name' => 'Convierte en Agente', 'route' => '#'],
                ['name' => 'Lorem', 'route' => '#'],
                ['name' => 'Lorem', 'route' => '#'],
            ]
        ],
        [
            'title' => 'Contacto',
            'links' => [
                ['name' => 'Lunes a sábado de 9:00 a.m. a 7:00 p.m.', 'route' => '#'],
                ['name' => 'contactoinmobiliaria@gmail.com', 'route' => 'mailto:contactoinmobiliaria@gmail.com'],
            ]
        ]
    ];

    $socialLinks = [
        ['icon' => 'fab fa-facebook-f', 'url' => '#', 'name' => 'Facebook'],
        ['icon' => 'fab fa-twitter', 'url' => '#', 'name' => 'Twitter'],
        ['icon' => 'fab fa-instagram', 'url' => '#', 'name' => 'Instagram'],
    ];
@endphp

<footer class="bg-white border-t border-gray-200 mt-16">
    <div class="mx-auto w-full max-w-screen-xl px-4 py-8 lg:py-12 relative">
        <!-- Contenido principal del footer -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Logo, descripción y redes sociales -->
            <div class="text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start mb-4">
                    <div class="w-8 h-8 bg-gray-300 rounded mr-3"></div>
                    <span class="text-xl font-semibold text-gray-900">Logoipsum</span>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-6 max-w-md mx-auto lg:mx-0">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.
                </p>
                
                <!-- Redes sociales -->
                <div class="flex space-x-4 justify-center lg:justify-start">
                    @foreach($socialLinks as $social)
                        <a href="{{ $social['url'] }}" 
                           class="text-blue-400 hover:text-blue-500 transition-colors duration-200"
                           aria-label="{{ $social['name'] }}">
                            <i class="{{ $social['icon'] }} text-lg"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Línea divisoria vertical (solo visible en desktop) -->
            <div class="hidden lg:block absolute left-[43%] top-1 w-px h-56 bg-gray-200 transform -translate-x-1/2"></div>



            <!-- Secciones de enlaces -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center lg:text-left">
                @foreach($footerSections as $section)
                    <div>
                        <h3 class="text-blue-400 font-medium text-sm mb-4 uppercase tracking-wide">
                            {{ $section['title'] }}
                        </h3>
                        <ul class="space-y-3">
                            @foreach($section['links'] as $link)
                                <li>
                                    <a href="{{ $link['route'] }}" 
                                       class="text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm">
                                        {{ $link['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Línea divisoria -->
        <hr class="my-8 border-gray-200">

        <!-- Copyright -->
        <div class="text-center">
            <p class="text-gray-500 text-sm">
                Copyright © {{ date('Y') }}. Logoipsum. All rights reserved.
            </p>
        </div>
    </div>
</footer>