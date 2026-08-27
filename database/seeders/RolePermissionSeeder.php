<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@intechstudio.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('intechstudio.id'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super Admin user created: admin@intechstudio.id / intechstudio.id');



        // Create Basic Permissions first
        $now = now();
        $permissions = [
            [
                'display_name' => 'View Users', // Custom display name
                'name' => 'view-users',         // Spatie name (slug)
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create User',
                'name' => 'create-users',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit User',
                'name' => 'edit-users',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete User',
                'name' => 'delete-users',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Roles',
                'name' => 'view-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Role',
                'name' => 'create-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Role',
                'name' => 'edit-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Role',
                'name' => 'delete-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Permissions',
                'name' => 'view-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Permission',
                'name' => 'create-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Permission',
                'name' => 'edit-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Permission',
                'name' => 'delete-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Activity Logs',
                'name' => 'view-activity-logs',
                'description' => null,
                'module' => 'activity_logs',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'display_name' => 'View Laravel Logs',
                'name' => 'view-laravel-logs',
                'description' => 'Access to view Laravel application logs',
                'module' => 'logs',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Settings',
                'name' => 'view-settings',
                'description' => null,
                'module' => 'settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Setting',
                'name' => 'edit-settings',
                'description' => null,
                'module' => 'settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                $permission
            );
        }

        $this->command->info('System permissions created');



        // Create Super Admin Role
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            [
                'display_name' => 'Super Admin',
                'name' => 'super-admin',
                'description' => 'Full system access',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Super Admin role created');

        // Assign all permissions to Super Admin role
        $allPermissions = Permission::all();
        if ($allPermissions->count() > 0) {
            $superAdminRole->syncPermissions($allPermissions);
            $this->command->info('All permissions assigned to Super Admin role');
        }

        // Assign Super Admin role to admin user
        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
            $this->command->info('Admin user assigned to Super Admin role');
        }

        // Create Developer Role
        $developerRole = Role::updateOrCreate(
            ['name' => 'developer', 'guard_name' => 'web'],
            [
                'display_name' => 'Developer',
                'name' => 'developer',
                'description' => 'Access to view Laravel logs and system debugging',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Developer role created');

        // Assign Laravel Logs permission to Developer role
        $viewLaravelLogsPermission = Permission::where('name', 'view-laravel-logs')->first();
        if ($viewLaravelLogsPermission) {
            $developerRole->givePermissionTo($viewLaravelLogsPermission);
            $this->command->info('Laravel Logs permission assigned to Developer role');
        }
    }
}
