<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // الصلاحيات الأساسية
        $permissions = [
            'create trip',
            'edit trip',
            'delete trip',
            'confirm trip',
            'view reports',
            'manage agents',
            'manage providers',
        ];

        // إنشاء الصلاحيات
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إنشاء الأدوار وربط الصلاحيات بيهم
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $agent = Role::firstOrCreate(['name' => 'agent']);
        $agent->givePermissionTo(['create trip', 'edit trip', 'view reports']);

        $provider = Role::firstOrCreate(['name' => 'provider']);
        $provider->givePermissionTo(['confirm trip', 'view reports']);
    }
}
