{{-- resources/views/components/skeletons/image-text.blade.php --}}
<div role="status" class="animate-pulse w-full max-w-4xl mx-auto">
    {{-- Skeleton para el título principal --}}
    <div class="h-6 bg-gray-300 rounded-lg dark:bg-gray-700 w-3/4 mb-8 mx-auto"></div>

    {{-- Skeleton para los botones de Venta/Renta --}}
    <div class="flex justify-start max-w-2xl mx-auto mb-8 gap-8">
        <div class="h-10 w-24 bg-gray-300 rounded-full dark:bg-gray-700"></div>
        <div class="h-10 w-24 bg-gray-300 rounded-full dark:bg-gray-700"></div>
    </div>
    
    {{-- Skeleton para la línea divisoria --}}
    <div class="max-w-2xl mx-auto h-px bg-gray-300 dark:bg-gray-700 mb-8"></div>

    {{-- Skeleton para el dropdown y la barra de búsqueda --}}
    <div class="flex flex-col md:flex-row gap-4 max-w-2xl mx-auto">
        {{-- Dropdown skeleton --}}
        <div class="h-12 w-full md:w-48 bg-gray-300 rounded-lg dark:bg-gray-700"></div>
        {{-- Search input skeleton --}}
        <div class="h-12 flex-1 bg-gray-300 rounded-lg dark:bg-gray-700"></div>
    </div>

    <span class="sr-only">Cargando contenido...</span>
</div>