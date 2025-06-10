{{-- resources/views/components/sections/featured-properties.blade.php --}}
<section class="py-16 bg-gray-50">
    <x-partials.container>
        <!-- Encabezado de la sección -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Propiedades Destacadas
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Descubre las mejores oportunidades inmobiliarias seleccionadas especialmente para ti
            </p>
        </div>

        <!-- Grid de propiedades -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $featuredProperties = [
                    [
                        'id' => 1,
                        'title' => 'Casa en Venta en Alvarado Centro',
                        'location' => 'Alvarado Centro, Alvarado, Veracruz',
                        'price' => 1865000,
                        'currency' => 'MXN',
                        'type' => 'Casa',
                        'operation' => 'Venta',
                        'featured' => true,
                        'bedrooms' => 3,
                        'bathrooms' => 2,
                        'area' => 120,
                        'images' => [
                            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1600607687644-aac4c3eac7f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                        ]
                    ],
                    [
                        'id' => 2,
                        'title' => 'Departamento Moderno',
                        'location' => 'Centro, Veracruz, Veracruz',
                        'price' => 2500000,
                        'currency' => 'MXN',
                        'type' => 'Departamento',
                        'operation' => 'Venta',
                        'featured' => true,
                        'bedrooms' => 2,
                        'bathrooms' => 2,
                        'area' => 85,
                        'images' => [
                            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                        ]
                    ],
                    [
                        'id' => 3,
                        'title' => 'Casa de Playa en Boca del Río',
                        'location' => 'Playa de Oro, Boca del Río, Veracruz',
                        'price' => 4200000,
                        'currency' => 'MXN',
                        'type' => 'Casa',
                        'operation' => 'Venta',
                        'featured' => true,
                        'bedrooms' => 4,
                        'bathrooms' => 3,
                        'area' => 180,
                        'images' => [
                            'https://images.unsplash.com/photo-1613490493576-7fde63acd811?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                        ]
                    ],
                    [
                        'id' => 4,
                        'title' => 'Terreno Comercial en Xalapa',
                        'location' => 'Centro, Xalapa, Veracruz',
                        'price' => 1200000,
                        'currency' => 'MXN',
                        'type' => 'Terreno',
                        'operation' => 'Venta',
                        'featured' => false,
                        'bedrooms' => null,
                        'bathrooms' => null,
                        'area' => 500,
                        'images' => [
                            'https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                        ]
                    ],
                    [
                        'id' => 5,
                        'title' => 'Casa en Renta en Fraccionamiento',
                        'location' => 'Las Américas, Boca del Río, Veracruz',
                        'price' => 25000,
                        'currency' => 'MXN/mes',
                        'type' => 'Casa',
                        'operation' => 'Renta',
                        'featured' => true,
                        'bedrooms' => 3,
                        'bathrooms' => 2,
                        'area' => 150,
                        'images' => [
                            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1600566752355-35792bedcfea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                        ]
                    ],
                    [
                        'id' => 6,
                        'title' => 'Oficina en Renta Centro Histórico',
                        'location' => 'Centro Histórico, Veracruz, Veracruz',
                        'price' => 15000,
                        'currency' => 'MXN/mes',
                        'type' => 'Oficina',
                        'operation' => 'Renta',
                        'featured' => false,
                        'bedrooms' => null,
                        'bathrooms' => 1,
                        'area' => 60,
                        'images' => [
                            'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                        ]
                    ]
                ];
            @endphp

            @foreach ($featuredProperties as $property)
                <x-partials.property-card :property="$property" />
            @endforeach
        </div>

        <!-- Botón para ver más propiedades -->
        <div class="text-center mt-12">
            <a href="#" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-300">
                <span>Ver todas las propiedades</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </x-partials.container>
</section>