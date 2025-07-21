<div class="min-h-screen flex">
    {{-- Sección de la imagen de fondo para pantallas grandes --}}
    <div class="hidden lg:flex lg:w-1/2 relative">
        <img 
            src="{{ asset(request()->routeIs('login') ? 'images/login-auth.webp' : 'images/register-auth.webp') }}" 
            alt="Background" 
            class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-black/20"></div>
    </div>
    
    {{-- Sección del formulario --}}
    {{-- Por defecto (móvil) será el fondo de puntos, y en lg: será blanco --}}
    <div class="flex-1 flex items-center justify-center p-6 
                bg-white bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] 
                lg:bg-white"> {{-- <--- CAMBIO CLAVE AQUÍ --}}
                {{-- Ahora lg:bg-white reemplaza lo que era lg:bg-gray-50 --}}
        <div class="w-full max-w-md">
            <div class="flex justify-center text-center mb-8">
                {{ $logo ?? '' }}
            </div>
            
            {{-- El contenedor blanco del formulario se mantiene para la tarjeta --}}
            <div class="bg-white px-8 py-10 shadow-lg rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>