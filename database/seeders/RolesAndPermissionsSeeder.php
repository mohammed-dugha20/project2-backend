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

        // Create permissions
        $permissions = [
            // Property permissions
            'view_properties',
            'create_property',
            'edit_property',
            'delete_property',
            
            // Contract permissions
            'view_contracts',
            'create_contract',
            'edit_contract',
            'delete_contract',
            
            // User management permissions
            'view_users',
            'create_user',
            'edit_user',
            'delete_user',
            
            // Admin specific permissions
            'manage_roles',
            'manage_permissions',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'admin' => $permissions,
            'customer' => [
                'view_properties',
                'view_contracts',
            ],
            'service_provider' => [
                'view_properties',
                'view_contracts',
                'create_contract',
                'edit_contract',
            ],
            'real_estate_office' => [
                'view_properties',
                'create_property',
                'edit_property',
                'view_contracts',
                'create_contract',
                'edit_contract',
            ],
            'finishing_company' => [
                'view_properties',
                'view_contracts',
                'create_contract',
                'edit_contract',
            ],
        ];

        foreach ($roles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($rolePermissions);
        }
    }
} 