<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'manage_users', 'group' => 'User Management'],
            ['name' => 'manage_roles', 'group' => 'Role Management'],
            ['name' => 'view_dashboard', 'group' => 'Dashboard'],
            ['name' => 'manage_sales', 'group' => 'Sales Management'],
            ['name' => 'export_reports', 'group' => 'Reports'],
            ['name' => 'manage_products', 'group' => 'Product Management'],
            ['name' => 'manage_customers', 'group' => 'Customer Management'],
            ['name' => 'manage_orders', 'group' => 'Order Management'],
            ['name' => 'view_reports', 'group' => 'Reports'],
            ['name' => 'manage_tenants', 'group' => 'Tenant Management'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name'], 'group' => $perm['group']]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view_dashboard',
            'manage_sales',
            'manage_products',
            'manage_customers',
            'manage_orders',
            'export_reports',
            'view_reports',
        ]);

        $analyst = Role::firstOrCreate(['name' => 'Analyst']);
        $analyst->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'export_reports',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'Viewer']);
        $viewer->givePermissionTo([
            'view_dashboard',
            'view_reports',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
