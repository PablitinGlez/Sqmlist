<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // Primero los roles y permisos
            UserSeeder::class,      // Luego los usuarios
            CategorySeeder::class,  // Después las categorías
            PropertyTypeSeeder::class, // ¡Y finalmente los tipos de propiedad!
            // Aquí puedes añadir llamadas a otros seeders si los tienes
        ]);
    }
}
