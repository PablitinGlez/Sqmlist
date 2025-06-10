
<div class="min-h-screen flex">
    <!-- Panel izquierdo - Imagen -->
    <div class="hidden lg:flex lg:w-1/2 relative">
    
        <img src="{{ asset(request()->routeIs('login') ? 'images/login-auth.jpg' : 'images/register-auth.jpg') }}" alt="Background" class="w-full h-full object-cover">
        
       
        <div class="absolute inset-0 bg-black/20"></div>
        
        
        {{-- <div class="absolute top-8 left-8 text-white">
            {{ $logo ?? '' }}
        </div> --}}
    </div>
    
    <!-- Panel derecho - Formulario -->
       <!-- Panel derecho - Formulario -->
       <div class="flex-1 flex items-center justify-center p-6 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Logo centrado para todas las pantallas -->
            <div class="flex justify-center text-center mb-8">
                {{ $logo ?? '' }}
            </div>
            
            <!-- Tarjeta del formulario -->
            <div class="bg-white px-8 py-10 shadow-lg rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>