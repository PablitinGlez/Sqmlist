<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="inmobiliaria, bienes raíces, compra, venta, alquiler, propiedades, agentes inmobiliarios, servicios inmobiliarios">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://kit.fontawesome.com">
    <script src="https://kit.fontawesome.com/529714676e.js" crossorigin="anonymous"></script>
    
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles


</head>

<body style="font-family: 'Poppins', sans-serif;" class="antialiased">
    
    
<div id="navigation-loader">
        <div class="loader-content">
            <l-helix
                size="60"
                stroke="5"
                bg-opacity="0.1"
                speed="2"
                color="#3490dc">
            </l-helix>
        </div>
    </div>

    <div class="min-h-screen bg-transparent">
        @include('layouts.includes.app.navigation-menu')

        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>

        @include('layouts.includes.app.footer')
    </div>

    @stack('modals')
    @livewireScripts
    @stack('scripts')

   
</body>
</html>