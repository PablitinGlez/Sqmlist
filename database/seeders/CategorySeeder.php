<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Importa tu modelo Category

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Category::firstOrCreate(
            ['name' => 'Residencial'],
            [
                'description' => 'Propiedades destinadas a vivienda, como casas, apartamentos, y condominios.',
                'color' => '#A7C7E7', 
            ]
        );

        
        Category::firstOrCreate(
            ['name' => 'Comercial'],
            [
                'description' => 'Propiedades para negocios y empresas, incluyendo locales, oficinas y centros comerciales.',
                'color' => '#B3E0B3', 
            ]
        );

        
        Category::firstOrCreate(
            ['name' => 'Industrial'],
            [
                'description' => 'Propiedades para actividades industriales, como naves, almacenes y fábricas.',
                'color' => '#FFDAB9', 
            ]
        );

        $this->command->info('Categorías "Residencial", "Comercial" e "Industrial" creadas/actualizadas con colores pasteles.');
    }
}
