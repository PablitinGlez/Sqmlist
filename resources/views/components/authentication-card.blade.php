<div class="flex">
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
    <div class="flex-1 flex items-center justify-center p-6 relative">
        {{-- Fondo de imagen repetida (fondodoce.png) --}}
        <div class="fixed h-full top-0 left-0 right-0 z-0" 
             style="width:100%;height:100%;background-size:109px;background-repeat:repeat;background-image:url('{{ asset('images/fondodoce.png') }}');opacity:0.06;border-radius:0;">
            <div class="absolute left-0 right-0 bottom-0 h-[300px]"></div>
        </div>

        {{-- Gradiente borroso flotante (RESPONSIVO) --}}
        <div 
            class="absolute left-1/2 -translate-x-1/2 z-10 
                   h-[100px] w-[250px] bottom-[50px] {{-- ESTILOS PARA MÓVIL (por defecto) --}}
                   lg:h-[150px] lg:w-[400px] lg:bottom:-100px {{-- ESTILOS PARA ESCRITORIO (lg: prefix) --}}
                   rotate-[0deg] transform rounded-full bg-gradient-to-tl from-blue-800 via-blue-500 to-blue-200 blur-[150px] opacity-30">
        </div>
        
        <div class="w-full max-w-md relative z-20">
            <div class="flex justify-center text-center mb-8">
                {{ $logo ?? '' }}
            </div>

            {{-- Card con efecto cristalizado --}}
            <div class="backdrop-blur-sm bg-white/5 border border-white/10 rounded-2xl px-8 py-10 shadow-2xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>