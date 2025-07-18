<div class="bg-white p-4 shadow-md">
    <!-- Barra de Búsqueda (paso anterior) -->
    <div class="mb-4">
        <input 
            type="text" 
            wire:model.lazy="searchQuery" 
            placeholder="Buscar por dirección, ciudad, etc." 
            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
    </div>

    <!-- Dropdowns -->
    <div class="flex flex-wrap gap-4">
        <!-- Dropdown Tipo de Operación -->
        <div class="relative" x-data="{ open: false }">
            <!-- Botón del dropdown -->
            <button 
                @click="open = !open" 
                class="flex items-center justify-between px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                <span>{{ $selectedOperationLabel }}</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Contenido del dropdown (se muestra al hacer clic) -->
            <div 
                x-show="open" 
                @click.away="open = false" 
                class="absolute z-10 w-48 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg"
            >
                <div class="p-2">
                    <!-- Checkbox Venta -->
                    <label class="flex items-center px-2 py-1 hover:bg-gray-100 rounded">
                        <input 
                            type="checkbox" 
                            wire:model="operationTypes.sale" 
                            wire:click="updateOperationType('sale')" 
                            class="mr-2 rounded text-blue-500 focus:ring-blue-500"
                        >
                        <span>Venta</span>
                    </label>

                    <!-- Checkbox Renta -->
                    <label class="flex items-center px-2 py-1 hover:bg-gray-100 rounded">
                        <input 
                            type="checkbox" 
                            wire:model="operationTypes.rent" 
                            wire:click="updateOperationType('rent')" 
                            class="mr-2 rounded text-blue-500 focus:ring-blue-500"
                        >
                        <span>Renta</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>