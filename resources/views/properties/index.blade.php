<x-app-layout>
    {{-- Aquí se renderizará el componente Livewire PropertiesIndex,
         el cual contendrá la lógica de filtros y la lista de propiedades. --}}
    @livewire('properties-index', ['operation' => $operation ?? null])
</x-app-layout>
