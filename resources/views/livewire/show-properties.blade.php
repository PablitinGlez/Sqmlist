<div>
    {{--
        Contenedor de la cuadrícula para las tarjetas de propiedades.
        Controla cuántas tarjetas se muestran por fila en diferentes tamaños de pantalla:
        - grid-cols-1: 1 columna en pantallas muy pequeñas (móviles).
        - sm:grid-cols-2: 2 columnas en pantallas pequeñas (tabletas).
        - md:grid-cols-3: 3 columnas en pantallas medianas.
        - lg:grid-cols-4: 4 columnas en pantallas grandes (escritorio).
        - gap-6: Espacio entre las tarjetas.
    --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {{-- Mostrar skeletons mientras se cargan las propiedades --}}
        @if($isLoading)
            {{-- Mostrar 8 skeletons por defecto --}}
            @for($i = 0; $i < 8; $i++)
                @include('components.property-card-skeleton')
            @endfor
        @else
            {{-- Mostrar las propiedades reales una vez cargadas --}}
            @forelse($properties as $property)
                {{-- Pasar la instancia completa del modelo Property al componente --}}
                <x-property-card :property="$property" />
            @empty
                <div class="col-span-full text-center py-10 flex flex-col items-center justify-center">
                    <p class="text-gray-500 text-lg mb-4">No hay propiedades publicadas que coincidan con los criterios.</p>
                    
                    {{-- Animación Lottie --}}
                    <dotlottie-wc
                        src="https://lottie.host/b05d2783-7d4a-4d54-8707-f3e37523d229/U2Bf8GIxG4.lottie"
                        style="width: 300px; height: 300px;"
                        speed="1"
                        autoplay
                        loop
                    ></dotlottie-wc>
                    {{-- Puedes ajustar width y height en el style o usar clases de Tailwind como w-full h-64 --}}
                </div>
            @endforelse
        @endif
    </div>

    {{-- Opcional: Si tienes paginación en tu componente Livewire, puedes mostrarla aquí --}}
    {{-- Solo mostrar paginación si no está cargando --}}
    @if(!$isLoading)
        {{-- @if ($properties->hasPages())
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        @endif --}}
    @endif
</div>