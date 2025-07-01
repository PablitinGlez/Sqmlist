<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Resetear la caché de permisos (importante antes de crear/asignar)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear roles si no existen (nombres en inglés)
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $agentRole = Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']); // Rol: Dueño
        // En RoleSeeder.php
        $realEstateRole = Role::firstOrCreate(['name' => 'real_estate_company', 'guard_name' => 'web']); // Cambiado a real_estate_company

        // Rol: Inmobiliaria

        // 3. Crear permisos si no existen
        $createPropertyPermission = Permission::firstOrCreate(['name' => 'create property', 'guard_name' => 'web']);
        $editPropertyPermission = Permission::firstOrCreate(['name' => 'edit property', 'guard_name' => 'web']);
        $deletePropertyPermission = Permission::firstOrCreate(['name' => 'delete property', 'guard_name' => 'web']); // Otro ejemplo

        // 4. Asignar permisos a los roles

        // Rol 'user': Acceso básico, sin permisos de gestión de propiedades por defecto.

        // Rol 'agent': Puede crear propiedades, editar sus propias propiedades
        $agentRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission, // Suponiendo que el agente edita sus propiedades
        ]);

        // Rol 'owner': Puede crear propiedades, editar sus propias propiedades
        $ownerRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission, // Suponiendo que el dueño edita sus propiedades
        ]);

        // Rol 'real estate': Puede crear propiedades, editar y quizás eliminar propiedades
        $realEstateRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission,
            $deletePropertyPermission, // Las inmobiliarias suelen tener más control
        ]);

        // Rol 'admin': Puede hacer todo. Generalmente se le asignan todos los permisos o se usa una "super-admin gate".
        // Para este seeder, le asignaremos explícitamente algunos permisos comunes de gestión.
        $adminRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission,
            $deletePropertyPermission,
            // Aquí puedes añadir más permisos específicos de administración global,
            // como 'manage users', 'manage requests', etc.
        ]);
    }
}
