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
    
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/DrawSVGPlugin.min.js"></script> 
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @filamentStyles

</head>

<body style="font-family: 'Poppins', sans-serif;" class="antialiased">
    
    <div class="bg-transparent">
        @include('layouts.includes.app.navigation-menu')

        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="relative">
            @if (session('error'))
                <div class="absolute top-4 left-0 right-0 z-50 px-4"> 
                    <div class="max-w-7xl mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-lg">
                        <strong class="font-bold">¡Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentNode.parentNode.remove()">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 2.652a1.2 1.2 0 1 1-1.697-1.697L8.303 10 5.651 7.348a1.2 1.2 0 1 1 1.697-1.697L10 8.303l2.651-2.652a1.2 1.2 0 0 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>

        @include('layouts.includes.app.footer')
    </div>

    @stack('modals')
    @livewireScripts
    @filamentScripts
    @stack('scripts')

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (event) => {
                const type = event[0].type || 'info';
                const message = event[0].message || 'Operación completada.';

                const toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    const newContainer = document.createElement('div');
                    newContainer.id = 'toast-container';
                    newContainer.className = 'fixed bottom-4 right-4 z-[9999] flex flex-col space-y-2';
                    document.body.appendChild(newContainer);
                }

                const toast = document.createElement('div');
                toast.className = `p-3 rounded-lg shadow-md text-white text-sm animate-fade-in-up flex items-center`;

                let bgColor = '';
                let iconClass = '';

                const icons = {
                    success: 'fas fa-check-circle',
                    error: 'fas fa-times-circle',
                    warning: 'fas fa-exclamation-triangle',
                    info: 'fas fa-info-circle'
                };

                switch (type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        iconClass = icons.success;
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        iconClass = icons.error;
                        break;
                    case 'warning':
                        bgColor = 'bg-yellow-500';
                        iconClass = icons.warning;
                        break;
                    case 'info':
                    default:
                        bgColor = 'bg-blue-500';
                        iconClass = icons.info;
                        break;
                }
                toast.classList.add(bgColor);
                
                const iconElement = document.createElement('i');
                iconElement.className = `${iconClass} mr-2`;
                toast.appendChild(iconElement);

                const textElement = document.createElement('span');
                textElement.textContent = message;
                toast.appendChild(textElement);

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('animate-fade-out-down');
                    toast.addEventListener('animationend', () => toast.remove());
                }, 3000);
            });
        });
    </script>
    <style>
        @keyframes fadeInOutUp {
            0% { opacity: 0; transform: translateY(20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(20px); }
        }
        @keyframes fadeOutDown {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }
        .animate-fade-in-up {
            animation: fadeInOutUp 3.3s ease-in-out forwards;
        }
        .animate-fade-out-down {
            animation: fadeOutDown 0.3s forwards;
        }
    </style>
</body>
</html>
