<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- fontaweosme --}}
        <script src="https://kit.fontawesome.com/529714676e.js" crossorigin="anonymous"></script>

        <style>
            /* jost-latin-wght-normal */
            @font-face {
              font-family: 'Jost Variable';
              font-style: normal;
              font-display: swap;
              font-weight: 100 900;
              src: url(https://cdn.jsdelivr.net/fontsource/fonts/jost:vf@latest/latin-wght-normal.woff2) format('woff2-variations');
              unicode-range: U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;
            }
            </style>


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>




    <body style="font-family: 'Jost Variable'" class="antialiased">

       
        <div class="min-h-screen bg-gray-100">
            @include('layouts.includes.app.navigation-menu')



            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>


            @include('layouts.includes.app.footer')
        </div>

        @stack('modals')

        @livewireScripts
        <!-- Alpine.js para el carrusel -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>




    
</html>
