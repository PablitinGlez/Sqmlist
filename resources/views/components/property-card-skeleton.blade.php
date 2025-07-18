{{-- Skeleton Card para mostrar mientras cargan las propiedades --}}
<div class="bg-white rounded-2xl overflow-hidden border border-gray-200 h-[440px] flex flex-col animate-pulse">
    
    {{-- Botón de favoritos skeleton --}}
    <div class="absolute top-3 right-3 z-20 w-8 h-8 bg-gray-200 rounded-full"></div>
    
    {{-- Imagen skeleton - altura fija --}}
    <div class="h-48 bg-gray-300 flex-shrink-0"></div>
    
    {{-- Contenido skeleton --}}
    <div class="p-3 pb-0 flex flex-col flex-grow">
        {{-- Badges skeleton --}}
        <div class="flex gap-1 mb-3 flex-shrink-0">
            <div class="w-16 h-5 bg-gray-200 rounded-full"></div>
            <div class="w-12 h-5 bg-gray-200 rounded-full"></div>
        </div>
        
        {{-- Precio skeleton --}}
        <div class="w-32 h-6 bg-gray-300 rounded mb-2 flex-shrink-0"></div>
        
        {{-- Características skeleton --}}
        <div class="flex items-center gap-4 mb-3 flex-shrink-0">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-200 rounded mr-1.5"></div>
                <div class="w-8 h-3 bg-gray-200 rounded"></div>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-200 rounded mr-1.5"></div>
                <div class="w-8 h-3 bg-gray-200 rounded"></div>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-200 rounded mr-1.5"></div>
                <div class="w-8 h-3 bg-gray-200 rounded"></div>
            </div>
        </div>
        
        {{-- Dirección skeleton --}}
        <div class="flex-grow flex items-start mb-2">
            <div class="w-full space-y-1">
                <div class="w-full h-3 bg-gray-200 rounded"></div>
                <div class="w-3/4 h-3 bg-gray-200 rounded"></div>
            </div>
        </div>
    </div>
    
    {{-- Botones skeleton --}}
    <div class="p-3 mt-auto pt-4 flex gap-2 flex-shrink-0">
        <div class="flex-1 h-8 bg-gray-200 rounded-lg"></div>
        <div class="flex-1 h-8 bg-gray-200 rounded-lg"></div>
    </div>
</div>