<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyType; // Importa tu modelo PropertyType
use App\Models\Category;     // Importa tu modelo Category para relacionarlos

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener las categorías existentes (asumimos que CategorySeeder ya se ejecutó)
        $residencialCategory = Category::where('name', 'Residencial')->first();
        $comercialCategory = Category::where('name', 'Comercial')->first();
        $industrialCategory = Category::where('name', 'Industrial')->first();

        if (!$residencialCategory || !$comercialCategory || !$industrialCategory) {
            $this->command->error('¡Error! Las categorías Residencial, Comercial o Industrial no existen. Asegúrate de ejecutar CategorySeeder primero.');
            return; // Detener la ejecución si las categorías no están presentes
        }

        $propertyTypes = [
            // Tipos para categoría Residencial
            [
                'name' => 'Departamento',
                'description' => 'Unidad de vivienda en un edificio de apartamentos.',
                'category_id' => $residencialCategory->id,
            ],
            [
                'name' => 'Casa',
                'description' => 'Vivienda unifamiliar independiente.',
                'category_id' => $residencialCategory->id,
            ],
            [
                'name' => 'Terreno habitacional',
                'description' => 'Terreno apto para la construcción de viviendas.',
                'category_id' => $residencialCategory->id,
            ],
            [
                'name' => 'Casa en condominio',
                'description' => 'Vivienda dentro de un conjunto residencial con áreas comunes.',
                'category_id' => $residencialCategory->id,
            ],
            [
                'name' => 'Rancho residencial',
                'description' => 'Propiedad extensa con características rurales y comodidades residenciales.',
                'category_id' => $residencialCategory->id,
            ],

            // Tipos para categoría Comercial
            [
                'name' => 'Oficina',
                'description' => 'Espacio destinado para actividades laborales o administrativas.',
                'category_id' => $comercialCategory->id,
            ],
            [
                'name' => 'Terreno comercial',
                'description' => 'Terreno con potencial para desarrollo de negocios.',
                'category_id' => $comercialCategory->id,
            ],
            [
                'name' => 'Edificio',
                'description' => 'Construcción de múltiples pisos para diversos usos comerciales.',
                'category_id' => $comercialCategory->id,
            ],
            [
                'name' => 'Local',
                'description' => 'Espacio comercial a nivel de calle o en plaza.',
                'category_id' => $comercialCategory->id,
            ],
            [
                'name' => 'Bodega comercial',
                'description' => 'Espacio de almacenamiento para distribución de productos comerciales.',
                'category_id' => $comercialCategory->id,
            ],

            // Tipos para categoría Industrial
            [
                'name' => 'Terreno industrial',
                'description' => 'Terreno apto para la construcción de naves o instalaciones industriales.',
                'category_id' => $industrialCategory->id,
            ],
            [
                'name' => 'Nave industrial',
                'description' => 'Edificación grande para producción, manufactura o almacenamiento industrial.',
                'category_id' => $industrialCategory->id,
            ],
            [
                'name' => 'Bodega industrial',
                'description' => 'Espacio de almacenamiento para uso industrial.',
                'category_id' => $industrialCategory->id,
            ],
        ];

        foreach ($propertyTypes as $typeData) {
            PropertyType::firstOrCreate(
                ['name' => $typeData['name'], 'category_id' => $typeData['category_id']],
                [
                    'description' => $typeData['description'],
                    'is_active' => true, // Por defecto, activos al crearse
                ]
            );
            $this->command->info('Tipo de propiedad "' . $typeData['name'] . '" creado/actualizado.');
        }

        $this->command->info('¡Todos los tipos de propiedad han sido creados/actualizados!');
    }
}
