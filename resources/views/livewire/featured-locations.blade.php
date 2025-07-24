<section class="py-8 md:py-16 bg-white">
    <x-partials.container>
        <div class="text-left mb-8 md:mb-12 md:text-center">
            <h2 class="text-lg md:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                Destacados
            </h2>
            <p class="text-sm md:text-lg text-gray-600 md:max-w-3xl md:mx-auto">
                Explora las propiedades más buscadas en las principales ubicaciones de México.
            </p>
        </div>

        <div class="flex flex-wrap justify-start md:justify-center gap-2 md:gap-4 mb-8 border-b">
            @foreach ($locationsData as $key => $data)
                <button
                    wire:click="selectTab('{{ $key }}')"
                    class="px-4 py-2 text-xs md:text-base font-semibold rounded-t-lg transition-colors duration-200
                        {{ $activeTab === $key ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-800' }}"
                >
                    {{ $data['name'] }}
                </button>
            @endforeach
        </div>

        <div class="p-4 rounded-lg">
            @if (isset($locationsData[$activeTab]))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800 mb-4">Venta</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($locationsData[$activeTab]['venta'] as $locationName => $propertyTypes)
                                <div>
                                    <h4 class="text-sm md:font-semibold text-gray-700 mb-2">{{ $locationName }}</h4>
                                    <ul class="space-y-1">
                                        @foreach ($propertyTypes as $fullText)
                                            @php
                                                preg_match('/(Casas|Departamentos)/', $fullText, $matches);
                                                $propertyType = $matches[1] ?? '';
                                            @endphp
                                            <li>
                                                <a href="{{ $this->generateUrl($locationName, $propertyType, 'venta') }}"
                                                   class="text-xs md:text-sm text-blue-600 hover:text-blue-800 hover:underline" wire:navigate>
                                                    {{ $fullText }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800 mb-4">Renta</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($locationsData[$activeTab]['renta'] as $locationName => $propertyTypes)
                                <div>
                                    <h4 class="text-sm md:font-semibold text-gray-700 mb-2">{{ $locationName }}</h4>
                                    <ul class="space-y-1">
                                        @foreach ($propertyTypes as $fullText)
                                            @php
                                                preg_match('/(Casas|Departamentos)/', $fullText, $matches);
                                                $propertyType = $matches[1] ?? '';
                                            @endphp
                                            <li>
                                                <a href="{{ $this->generateUrl($locationName, $propertyType, 'renta') }}"
                                                   class="text-xs md:text-sm text-blue-600 hover:text-blue-800 hover:underline" wire:navigate>
                                                    {{ $fullText }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-sm md:text-base text-gray-600">No hay datos disponibles para esta sección.</p>
            @endif
        </div>
    </x-partials.container>
</section>