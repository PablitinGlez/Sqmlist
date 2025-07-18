<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Usuario Admin 1
        $admin1 = User::firstOrCreate(
            ['email' => 'admin1@admin.com'],
            [
                'name' => 'Admin 1',
                'password' => bcrypt('A$dmin1Secure2024!'), // Contraseña segura
                'email_verified_at' => now(),
            ]
        );
        $admin1->assignRole($adminRole);

        // Usuario Admin 2
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@admin.com'],
            [
                'name' => 'Admin 2',
                'password' => bcrypt('S@feP4sswordAdmin2!'), // Contraseña segura
                'email_verified_at' => now(),
            ]
        );
        $admin2->assignRole($adminRole);
    }
}
