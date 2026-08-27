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
                'is_active' => true,
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
            [
                'display_name' => 'View Transaction Categories',
                'name' => 'view-transaction_categories',
                'description' => 'Can view transactioncategories list',
                'module' => 'transaction_categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Transaction Categories',
                'name' => 'create-transaction_categories',
                'description' => 'Can create new transactioncategory',
                'module' => 'transaction_categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Transaction Categories',
                'name' => 'edit-transaction_categories',
                'description' => 'Can edit transactioncategory',
                'module' => 'transaction_categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Transaction Categories',
                'name' => 'delete-transaction_categories',
                'description' => 'Can delete transactioncategory',
                'module' => 'transaction_categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Cash Transactions',
                'name' => 'view-cash_transactions',
                'description' => 'Can view cashtransactions list',
                'module' => 'cash_transactions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Cash Transactions',
                'name' => 'create-cash_transactions',
                'description' => 'Can create new cashtransaction',
                'module' => 'cash_transactions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Cash Transactions',
                'name' => 'edit-cash_transactions',
                'description' => 'Can edit cashtransaction',
                'module' => 'cash_transactions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Cash Transactions',
                'name' => 'delete-cash_transactions',
                'description' => 'Can delete cashtransaction',
                'module' => 'cash_transactions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Cash Accounts',
                'name' => 'view-cash_accounts',
                'description' => 'Can view cashaccounts list',
                'module' => 'cash_accounts',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Cash Accounts',
                'name' => 'create-cash_accounts',
                'description' => 'Can create new cashaccount',
                'module' => 'cash_accounts',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Cash Accounts',
                'name' => 'edit-cash_accounts',
                'description' => 'Can edit cashaccount',
                'module' => 'cash_accounts',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Cash Accounts',
                'name' => 'delete-cash_accounts',
                'description' => 'Can delete cashaccount',
                'module' => 'cash_accounts',
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

        // Create Finance Role
        $financeRole = Role::updateOrCreate(
            ['name' => 'finance', 'guard_name' => 'web'],
            [
                'display_name' => 'Finance',
                'name' => 'finance',
                'description' => 'Akses manajemen keuangan, akun kas, kategori, dan transaksi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Finance role created');

        // Assign financial permissions to Finance role
        $financePermissions = [
            'view-cash_transactions',
            'create-cash_transactions',
            'edit-cash_transactions',
            'delete-cash_transactions',
            'view-cash_accounts',
            'create-cash_accounts',
            'edit-cash_accounts',
            'delete-cash_accounts',
            'view-transaction_categories',
            'create-transaction_categories',
            'edit-transaction_categories',
            'delete-transaction_categories',
        ];
        $financeRole->syncPermissions($financePermissions);
        $this->command->info('Financial permissions assigned to Finance role');

        // Create Demo Finance User
        $financeUser = User::firstOrCreate(
            ['email' => 'finance@intechstudio.id'],
            [
                'name' => 'Staff Finance',
                'password' => Hash::make('intechstudio.id'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $financeUser->syncRoles([$financeRole]);
        $this->command->info('Finance user created: finance@intechstudio.id / intechstudio.id');
    }
}
