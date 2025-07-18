<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyType;
use App\Models\Category;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $residencialCategory = Category::where('name', 'Residencial')->first();
        $comercialCategory = Category::where('name', 'Comercial')->first();
        $industrialCategory = Category::where('name', 'Industrial')->first();

        if (!$residencialCategory || !$comercialCategory || !$industrialCategory) {
            $this->command->error('¡Error! Las categorías Residencial, Comercial o Industrial no existen. Asegúrate de ejecutar CategorySeeder primero.');
            return;
        }

        $propertyTypesData = [ // Renombrado a $propertyTypesData para evitar confusión con la variable de arriba
            // Tipos para categoría Residencial
            [
                'name' => 'Departamento',
                'description' => 'Unidad de vivienda en un edificio de apartamentos.',
                'category_id' => $residencialCategory->id,
                'order' => 10, // <-- AÑADIDO
            ],
            [
                'name' => 'Casa',
                'description' => 'Vivienda unifamiliar independiente.',
                'category_id' => $residencialCategory->id,
                'order' => 20, // <-- AÑADIDO
            ],
            [
                'name' => 'Terreno habitacional',
                'description' => 'Terreno apto para la construcción de viviendas.',
                'category_id' => $residencialCategory->id,
                'order' => 30, // <-- AÑADIDO
            ],
            [
                'name' => 'Casa en condominio',
                'description' => 'Vivienda dentro de un conjunto residencial con áreas comunes.',
                'category_id' => $residencialCategory->id,
                'order' => 40, // <-- AÑADIDO
            ],
            [
                'name' => 'Rancho residencial',
                'description' => 'Propiedad extensa con características rurales y comodidades residenciales.',
                'category_id' => $residencialCategory->id,
                'order' => 50, // <-- AÑADIDO
            ],
            // ... (continúa con tus otros tipos de propiedad para Comercial e Industrial)
            // Asegúrate de añadir 'order' a *todos* los tipos
            [
                'name' => 'Oficina',
                'description' => 'Espacio destinado para actividades de oficina.',
                'category_id' => $comercialCategory->id,
                'order' => 60,
            ],
            [
                'name' => 'Terreno comercial',
                'description' => 'Terreno apto para desarrollo comercial.',
                'category_id' => $comercialCategory->id,
                'order' => 70,
            ],
            [
                'name' => 'Edificio',
                'description' => 'Edificio completo, ya sea de oficinas o usos mixtos.',
                'category_id' => $comercialCategory->id,
                'order' => 80,
            ],
            [
                'name' => 'Local',
                'description' => 'Local comercial para tiendas o negocios.',
                'category_id' => $comercialCategory->id,
                'order' => 90,
            ],
            [
                'name' => 'Bodega comercial',
                'description' => 'Bodega destinada para fines comerciales.',
                'category_id' => $comercialCategory->id,
                'order' => 100,
            ],
            [
                'name' => 'Terreno industrial',
                'description' => 'Terreno apto para construcción de naves o bodegas industriales.',
                'category_id' => $industrialCategory->id,
                'order' => 110,
            ],
            [
                'name' => 'Nave industrial',
                'description' => 'Construcción destinada a procesos industriales o almacenamiento a gran escala.',
                'category_id' => $industrialCategory->id,
                'order' => 120,
            ],
            [
                'name' => 'Bodega industrial',
                'description' => 'Bodega destinada para almacenamiento o actividades industriales ligeras.',
                'category_id' => $industrialCategory->id,
                'order' => 130,
            ],
        ];

        foreach ($propertyTypesData as $typeData) {
            PropertyType::firstOrCreate(
                ['slug' => Str::slug($typeData['name'])], // Busca por slug para evitar duplicados
                [
                    'name' => $typeData['name'],
                    'description' => $typeData['description'],
                    'category_id' => $typeData['category_id'],
                    'is_active' => true, // Puedes establecer un valor predeterminado si no lo tienes en tus datos
                    'order' => $typeData['order'] ?? null, // Asegúrate de asignar el orden, si no existe, será nulo
                ]
            );
        }
    }
}
