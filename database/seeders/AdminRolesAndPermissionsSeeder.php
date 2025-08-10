<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create admin permissions
        $adminPermissions = [
            // User management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'toggle_user_status',
            
            // Real Estate Office management
            'view_real_estate_offices',
            'create_real_estate_offices',
            'edit_real_estate_offices',
            'delete_real_estate_offices',
            'toggle_real_estate_office_status',
            
            // Finishing Company management
            'view_finishing_companies',
            'create_finishing_companies',
            'edit_finishing_companies',
            'delete_finishing_companies',
            'toggle_finishing_company_status',
            
            // Admin management
            'view_admins',
            'create_admins',
            'edit_admins',
            'delete_admins',
            'toggle_admin_status',
            
            // System management
            'view_system_settings',
            'edit_system_settings',
            'view_analytics',
            'view_reports',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create admin roles
        $adminRoles = [
            'super_admin' => $adminPermissions,
            'admin' => [
                'view_users',
                'edit_users',
                'toggle_user_status',
                'view_real_estate_offices',
                'edit_real_estate_offices',
                'toggle_real_estate_office_status',
                'view_finishing_companies',
                'edit_finishing_companies',
                'toggle_finishing_company_status',
                'view_analytics',
                'view_reports',
            ],
            'moderator' => [
                'view_users',
                'view_real_estate_offices',
                'view_finishing_companies',
                'view_analytics',
            ],
        ];

        foreach ($adminRoles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role, 'guard_name' => 'admin']);
            $role->givePermissionTo($rolePermissions);
        }
    }
} 