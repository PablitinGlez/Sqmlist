<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $agentRole = Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $realEstateRole = Role::firstOrCreate(['name' => 'real_estate_company', 'guard_name' => 'web']);

        $createPropertyPermission = Permission::firstOrCreate(['name' => 'create property', 'guard_name' => 'web']);
        $editPropertyPermission = Permission::firstOrCreate(['name' => 'edit property', 'guard_name' => 'web']);
        $deletePropertyPermission = Permission::firstOrCreate(['name' => 'delete property', 'guard_name' => 'web']);

        $agentRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission,
        ]);

        $ownerRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission,
        ]);

        $realEstateRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission,
            $deletePropertyPermission,
        ]);

        $adminRole->givePermissionTo([
            $createPropertyPermission,
            $editPropertyPermission,
            $deletePropertyPermission,
        ]);
    }
}
