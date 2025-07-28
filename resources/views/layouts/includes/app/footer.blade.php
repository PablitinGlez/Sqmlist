@php
    $socialLinks = [
        ['icon' => 'fab fa-facebook-f', 'url' => '#', 'name' => 'Facebook'],
        ['icon' => 'fab fa-twitter', 'url' => '#', 'name' => 'Twitter'],
        ['icon' => 'fab fa-instagram', 'url' => '#', 'name' => 'Instagram'],
    ];
@endphp

<footer class="bg-white border-t border-gray-200 mt-16">
    <div class="mx-auto w-full max-w-screen-xl px-4 py-8">
        <div class="text-center">
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-auto mr-3">
                <span class="text-xl font-semibold text-gray-900">SQMLIST</span>
            </div>

            <div class="flex space-x-4 justify-center mb-6">
                @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] }}"
                        class="text-blue-400 hover:text-blue-500 transition-colors duration-200"
                       aria-label="{{ $social['name'] }}">
                        <i class="{{ $social['icon'] }} text-lg"></i>
                    </a>
                @endforeach
            </div>

            <p class="text-gray-500 text-sm">
                Copyright © {{ date('Y') }}. SQMLIST. Todos los derechos reservados.
            </p>
        </div>
    </div>
</footer>