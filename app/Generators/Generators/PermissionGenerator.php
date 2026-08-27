<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;
use Illuminate\Support\Facades\Schema;

class PermissionGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        try {
            if (!Schema::hasTable('permissions')) {
                return false;
            }

            $permissions = $this->getPermissionsData();
            $createdPermissionIds = [];

            // Always append to RolePermissionSeeder for persistence
            $this->appendToSeeder($permissions);

            if (!$this->commandData->skipDb) {
                foreach ($permissions as $permission) {
                    $createdPermission = $this->insertPermission($permission);
                    if ($createdPermission) {
                        $createdPermissionIds[] = $createdPermission->id;
                    }
                }

                // Assign permissions to administrator role
                if (!empty($createdPermissionIds)) {
                    $this->assignToAdministrator($createdPermissionIds);
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function rollback(): bool
    {
        $module = $this->commandData->modelNameSnakePlural;

        // Delete permissions from database
        try {
            if (Schema::hasTable('permissions')) {
                \App\Models\Permission::where('module', $module)->delete();
            }
        } catch (\Exception $e) {
            // Ignore DB errors on rollback
        }

        // Clean up RolePermissionSeeder.php
        $seederPath = base_path('database/seeders/RolePermissionSeeder.php');
        if (file_exists($seederPath)) {
            $content = file_get_contents($seederPath);
            $searchPattern = "/'module'\s*=>\s*'" . preg_quote($module, '/') . "'/";

            while (preg_match($searchPattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $matchPos = $matches[0][1];
                $depth = 0;
                $startPos = -1;
                for ($i = $matchPos; $i >= 0; $i--) {
                    $char = $content[$i];
                    if ($char === ']') $depth++;
                    elseif ($char === '[') {
                        if ($depth === 0) {
                            $startPos = $i;
                            break;
                        }
                        $depth--;
                    }
                }
                if ($startPos === -1) break;
                $depth = 0;
                $endPos = -1;
                $len = strlen($content);
                for ($i = $startPos; $i < $len; $i++) {
                    $char = $content[$i];
                    if ($char === '[') $depth++;
                    elseif ($char === ']') {
                        $depth--;
                        if ($depth === 0) {
                            $endPos = $i;
                            break;
                        }
                    }
                }
                if ($endPos === -1) break;
                $endPosIndex = $endPos + 1;
                if ($endPosIndex < $len && $content[$endPosIndex] === ',') $endPosIndex++;
                while ($endPosIndex < $len && ($content[$endPosIndex] === "\r" || $content[$endPosIndex] === "\n")) $endPosIndex++;
                while ($startPos > 0 && ($content[$startPos - 1] === ' ' || $content[$startPos - 1] === "\t")) $startPos--;
                $content = substr($content, 0, $startPos) . substr($content, $endPosIndex);
            }
            file_put_contents($seederPath, $content);
        }

        return true;
    }

    private function getPermissionsData(): array
    {
        $module = $this->commandData->modelNameSnakePlural;
        $modelName = $this->commandData->modelName;

        return [
            [
                'display_name' => "View {$this->commandData->modelNamePlural}",
                'name' => "view-{$this->commandData->modelNameSnakePlural}",
                'description' => "Can view {$this->commandData->modelNameLowerPlural} list",
                'module' => $module,
                'guard_name' => 'web',
                'is_active' => true
            ],
            [
                'display_name' => "Create {$this->commandData->modelNamePlural}",
                'name' => "create-{$this->commandData->modelNameSnakePlural}",
                'description' => "Can create new {$this->commandData->modelNameLower}",
                'module' => $module,
                'guard_name' => 'web',
                'is_active' => true
            ],
            [
                'display_name' => "Edit {$this->commandData->modelNamePlural}",
                'name' => "edit-{$this->commandData->modelNameSnakePlural}",
                'description' => "Can edit {$this->commandData->modelNameLower}",
                'module' => $module,
                'guard_name' => 'web',
                'is_active' => true
            ],
            [
                'display_name' => "Delete {$this->commandData->modelNamePlural}",
                'name' => "delete-{$this->commandData->modelNameSnakePlural}",
                'description' => "Can delete {$this->commandData->modelNameLower}",
                'module' => $module,
                'guard_name' => 'web',
                'is_active' => true
            ]
        ];
    }

    private function insertPermission(array $permissionData): ?\App\Models\Permission
    {
        // Use updateOrCreate to avoid duplicates
        return \App\Models\Permission::updateOrCreate(
            ['name' => $permissionData['name'], 'guard_name' => 'web'],
            $permissionData
        );
    }

    private function assignToAdministrator(array $permissionIds): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('role_has_permissions')) {
            return;
        }

        // Find super admin role by name
        $superAdminRole = \App\Models\Role::where('name', 'super-admin')->first();

        if ($superAdminRole) {
            // Spatie method to give permissions
            // We need to fetch the permission models first
            $permissions = \App\Models\Permission::whereIn('id', $permissionIds)->get();
            $superAdminRole->givePermissionTo($permissions);
        }
    }

    private function appendToSeeder(array $permissions): void
    {
        $seederPath = base_path('database/seeders/RolePermissionSeeder.php');
        if (!file_exists($seederPath)) {
            return;
        }

        $content = file_get_contents($seederPath);

        $seederStub = "";
        foreach ($permissions as $perm) {
            // Skip if permission already exists in seeder
            if (str_contains($content, "'name' => '{$perm['name']}'")) {
                continue;
            }

            $seederStub .= "            [\n";
            $seederStub .= "                'display_name' => '{$perm['display_name']}',\n";
            $seederStub .= "                'name' => '{$perm['name']}',\n";
            $seederStub .= "                'description' => '{$perm['description']}',\n";
            $seederStub .= "                'module' => '{$perm['module']}',\n";
            $seederStub .= "                'is_active' => true,\n";
            $seederStub .= "                'guard_name' => 'web',\n";
            $seederStub .= "                'created_at' => \$now,\n";
            $seederStub .= "                'updated_at' => \$now,\n";
            $seederStub .= "            ],\n";
        }

        if (empty($seederStub)) {
            return;
        }

        // Find the array closing bracket for $permissions = [ ... ];
        // This is a bit tricky, but we can look for the end of the basic permissions
        $search = "        ];";

        // Find the first occurrence of "];" after "$permissions = ["
        $permissionsStart = strpos($content, '$permissions = [');
        if ($permissionsStart !== false) {
            $arrayEnd = strpos($content, '];', $permissionsStart);
            if ($arrayEnd !== false) {
                $before = substr($content, 0, $arrayEnd);
                $after = substr($content, $arrayEnd);

                $newContent = $before . $seederStub . "        " . $after;
                file_put_contents($seederPath, $newContent);
            }
        }
    }
}
