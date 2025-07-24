<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeatureSection; // Importa el modelo FeatureSection

class FeatureSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        FeatureSection::firstOrCreate(
            ['slug' => 'caracteristicas_generales'],
            ['name' => 'Características Generales', 'order' => 10, 'is_active' => true]
        );

  
        FeatureSection::firstOrCreate(
            ['slug' => 'amenidades'],
            ['name' => 'Amenidades', 'order' => 20, 'is_active' => true] 
        );

   
        FeatureSection::firstOrCreate(
            ['slug' => 'servicios'],
            ['name' => 'Servicios', 'order' => 30, 'is_active' => true]
        );

        
    }
}
