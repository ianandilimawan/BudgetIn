<?php

return [
    /*
    |--------------------------------------------------------------------------
    | InForge Menu Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can define the menus that will be displayed in the admin sidebar.
    | Menus are grouped by sections. To create a menu without a section,
    | use 'Main' or any other title, or just group them under an empty string ''.
    |
    | Supported keys for each menu item:
    | - name: The label of the menu.
    | - route: The named route (e.g., 'admin.dashboard').
    | - url: The URL (if route is not provided).
    | - icon: The icon class (e.g., 'home', 'users'). Uses Heroicons if short name, or FontAwesome if prefixed with 'fa'.
    | - permission: The permission required to view this menu (e.g., 'view-users').
    | - children: Array of submenus with the same structure.
    |
    | // INJECT_NEW_MENU_SECTION_HERE
    */

    'Main' => [
        [
            'name' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'home',
            // No permission required, visible to all authenticated admins
        ],
    ],

    'User Management' => [
        [
            'name' => 'Users',
            'route' => 'admin.users.index',
            'icon' => 'users',
            'permission' => 'view-users',
        ],
        [
            'name' => 'Pengguna Finance',
            'route' => 'admin.finance_users.index',
            'icon' => 'user-group',
            'permission' => 'view-users',
        ],
        [
            'name' => 'Roles',
            'route' => 'admin.roles.index',
            'icon' => 'shield',
            'permission' => 'view-roles',
        ],
        [
            'name' => 'Permissions',
            'route' => 'admin.permissions.index',
            'icon' => 'key',
            'permission' => 'view-permissions',
        ],
    ],

    'System' => [
        [
            'name' => 'Activity Logs',
            'route' => 'admin.activity-logs.index',
            'icon' => 'fa fa-history',
            'permission' => 'view-activity-logs',
        ],
        [
            'name' => 'Server Logs',
            'route' => 'admin.laravel-logs.index',
            'icon' => 'fa fa-clipboard-list',
            'permission' => 'view-laravel-logs',
        ],
        [
            'name' => 'Settings',
            'route' => 'admin.settings.index',
            'icon' => 'cog',
            'permission' => 'view-settings',
        ],
    ],

    'Finance' => [
        [
            'name' => 'Cash Transactions',
            'route' => 'admin.cash_transactions.index',
            'icon' => 'banknotes',
            'permission' => 'view-cash_transactions',
        ],
        [
            'name' => 'Transaksi Berulang',
            'route' => 'admin.recurring_transactions.index',
            'icon' => 'arrow-path',
            'permission' => 'view-cash_transactions',
        ],
        [
            'name' => 'Target Anggaran',
            'route' => 'admin.category_budgets.index',
            'icon' => 'fa fa-bullseye',
            'permission' => 'view-cash_transactions',
        ],
        [
            'name' => 'Anggaran Proyek / Acara',
            'route' => 'admin.budget_projects.index',
            'icon' => 'sparkles',
            'permission' => 'view-cash_transactions',
        ],
        [
            'name' => 'Cash Accounts',
            'route' => 'admin.cash_accounts.index',
            'icon' => 'wallet',
            'permission' => 'view-cash_accounts',
        ],
        [
            'name' => 'Transaction Categories',
            'route' => 'admin.transaction_categories.index',
            'icon' => 'tag',
            'permission' => 'view-transaction_categories',
        ],
    ],

    'Content Management' => [

    ],
];