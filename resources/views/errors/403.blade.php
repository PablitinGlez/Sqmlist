<x-app-layout>

<div class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Acceso No Autorizado</h1>
            
            <p class="text-gray-600 mb-6">
                {{ $exception->getMessage() ?: 'No tienes permisos para acceder a esta sección.' }}
            </p>
            
            <p class="text-sm text-gray-500 mb-6">Error 403 - Prohibido</p>
            
            <div class="space-y-3">
                @auth
                    <a href="{{ route('home') }}" 
                       class="inline-block w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Volver al Inicio
                    </a>
                    
                    @if(auth()->user()->hasAnyRole(['owner', 'agent', 'real_estate']))
                        <a href="{{ route('dashboard') }}" 
                           class="inline-block w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            Ir a Mi Panel
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-block w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Iniciar Sesión
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="inline-block w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                        Volver al Inicio
                    </a>
                @endauth
            </div>
        </div>
        
        <p class="mt-6 text-sm text-gray-500">
            Si crees que esto es un error, contacta al administrador del sistema.
        </p>
    </div>
</div>

</x-app-layout>
