<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $admin1 = User::firstOrCreate(
            ['email' => 'admin1@xerox.com'],
            [
                'name' => 'Admin 1',
                'password' => bcrypt('A$dmin1Secure2024!'),
                'email_verified_at' => now(),
            ]
        );
        $admin1->assignRole($adminRole);

        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@xerox.com'],
            [
                'name' => 'Admin 2',
                'password' => bcrypt('S@feP4sswordAdmin2!'),
                'email_verified_at' => now(),
            ]
        );
        $admin2->assignRole($adminRole);
    }
}
