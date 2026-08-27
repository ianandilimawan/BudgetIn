<?php

namespace App\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RevertScaffoldCommand extends Command
{
    protected $signature = 'revert:scaffold
                            {model : The name of the model to revert}
                            {--force : Skip confirmation prompts}';

    protected $description = 'Revert/Remove generated scaffold files and database entries';

    public function handle()
    {
        $modelName = $this->argument('model');
        $force = $this->option('force');

        if ($modelName && !preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $modelName)) {
            $this->error("Invalid model name '{$modelName}'. Model name must be alphanumeric and start with a letter.");
            return 1;
        }

        $this->info("Reverting scaffold for {$modelName}...");

        // Confirm deletion
        if (!$force && !$this->confirm("Are you sure you want to remove all files for {$modelName}? This cannot be undone!", false)) {
            $this->info('Revert cancelled.');
            return 0;
        }

        $filesDeleted = [];
        $errors = [];

        // 1. Delete Model
        $modelPath = app_path("Models/{$modelName}.php");
        if (file_exists($modelPath)) {
            if (unlink($modelPath)) {
                $filesDeleted[] = "Model: {$modelPath}";
            } else {
                $errors[] = "Failed to delete Model: {$modelPath}";
            }
        }

        // 2. Delete Controller
        $controllerName = "{$modelName}Controller";
        $controllerPath = app_path("Http/Controllers/{$controllerName}.php");
        if (file_exists($controllerPath)) {
            if (unlink($controllerPath)) {
                $filesDeleted[] = "Controller: {$controllerPath}";
            } else {
                $errors[] = "Failed to delete Controller: {$controllerPath}";
            }
        }

        // 2b. Delete API Controller
        $apiControllerName = "{$modelName}ApiController";
        $apiControllerPath = app_path("Http/Controllers/Api/{$apiControllerName}.php");
        if (file_exists($apiControllerPath)) {
            if (unlink($apiControllerPath)) {
                $filesDeleted[] = "API Controller: {$apiControllerPath}";
            } else {
                $errors[] = "Failed to delete API Controller: {$apiControllerPath}";
            }
        }

        // 3. Delete Requests
        $createRequestPath = app_path("Http/Requests/Create{$modelName}Request.php");
        $updateRequestPath = app_path("Http/Requests/Update{$modelName}Request.php");

        if (file_exists($createRequestPath)) {
            if (unlink($createRequestPath)) {
                $filesDeleted[] = "CreateRequest: {$createRequestPath}";
            } else {
                $errors[] = "Failed to delete CreateRequest: {$createRequestPath}";
            }
        }

        if (file_exists($updateRequestPath)) {
            if (unlink($updateRequestPath)) {
                $filesDeleted[] = "UpdateRequest: {$updateRequestPath}";
            } else {
                $errors[] = "Failed to delete UpdateRequest: {$updateRequestPath}";
            }
        }

        // 4. Delete Views (try both singular and plural)
        $viewsPathSingular = resource_path("views/admin/" . Str::snake($modelName));
        $viewsPathPlural = resource_path("views/admin/" . Str::snake(Str::plural($modelName)));

        // Try singular first
        if (is_dir($viewsPathSingular)) {
            if ($this->deleteDirectory($viewsPathSingular)) {
                $filesDeleted[] = "Views: {$viewsPathSingular}";
            } else {
                $errors[] = "Failed to delete Views: {$viewsPathSingular}";
            }
        }
        // Try plural if different from singular
        elseif ($viewsPathSingular !== $viewsPathPlural && is_dir($viewsPathPlural)) {
            if ($this->deleteDirectory($viewsPathPlural)) {
                $filesDeleted[] = "Views: {$viewsPathPlural}";
            } else {
                $errors[] = "Failed to delete Views: {$viewsPathPlural}";
            }
        }

        // 5. Delete Seeder
        $seederPath = database_path("seeders/{$modelName}Seeder.php");
        if (file_exists($seederPath)) {
            if (unlink($seederPath)) {
                $filesDeleted[] = "Seeder: {$seederPath}";
            } else {
                $errors[] = "Failed to delete Seeder: {$seederPath}";
            }
        }

        // 6. Delete Factory
        $factoryPath = database_path("factories/{$modelName}Factory.php");
        if (file_exists($factoryPath)) {
            if (unlink($factoryPath)) {
                $filesDeleted[] = "Factory: {$factoryPath}";
            } else {
                $errors[] = "Failed to delete Factory: {$factoryPath}";
            }
        }

        // 7. Delete Livewire Table (PowerGrid)
        $livewireTablePath = app_path("Livewire/Tables/{$modelName}Table.php");
        if (file_exists($livewireTablePath)) {
            if (unlink($livewireTablePath)) {
                $filesDeleted[] = "Livewire Table: {$livewireTablePath}";
            } else {
                $errors[] = "Failed to delete Livewire Table: {$livewireTablePath}";
            }
        }

        // 7b. Delete old DataTable (if exists)
        $dataTablePath = app_path("DataTables/{$modelName}DataTable.php");
        if (file_exists($dataTablePath)) {
            if (unlink($dataTablePath)) {
                $filesDeleted[] = "DataTable: {$dataTablePath}";
            } else {
                $errors[] = "Failed to delete DataTable: {$dataTablePath}";
            }
        }

        // 8. Delete Test
        $testPath = base_path("tests/Feature/{$modelName}Test.php");
        if (file_exists($testPath)) {
            if (unlink($testPath)) {
                $filesDeleted[] = "Test: {$testPath}";
            } else {
                $errors[] = "Failed to delete Test: {$testPath}";
            }
        }

        // 8b. Delete API Resource
        $resourcePath = app_path("Http/Resources/{$modelName}Resource.php");
        if (file_exists($resourcePath)) {
            if (unlink($resourcePath)) {
                $filesDeleted[] = "API Resource: {$resourcePath}";
            } else {
                $errors[] = "Failed to delete API Resource: {$resourcePath}";
            }
        }

        // 8c. Delete Policy
        $policyPath = app_path("Policies/{$modelName}Policy.php");
        if (file_exists($policyPath)) {
            if (unlink($policyPath)) {
                $filesDeleted[] = "Policy: {$policyPath}";
            } else {
                $errors[] = "Failed to delete Policy: {$policyPath}";
            }
        }

        // 9. Find and optionally delete Migration
        $tableName = Str::snake(Str::pluralStudly($modelName));
        $migrationFiles = glob(database_path("migrations/*_create_{$tableName}_table.php"));

        if (!empty($migrationFiles)) {
            if ($force || $this->confirm("Found migration file(s). Do you want to delete them?", false)) {
                foreach ($migrationFiles as $migrationFile) {
                    if (unlink($migrationFile)) {
                        $filesDeleted[] = "Migration: {$migrationFile}";
                    } else {
                        $errors[] = "Failed to delete Migration: {$migrationFile}";
                    }
                }
            } else {
                $this->info("Skipping migration deletion.");
            }
        }

        // 10. Remove Routes
        $this->removeRoutes($modelName);
        $this->removeApiRoutes($modelName);

        // 11. Remove Menu entries from config
        $this->removeConfigMenu($modelName);
        // 12. Remove Permissions from database
        $hasPermissionsTable = false;
        try {
            $hasPermissionsTable = Schema::hasTable('permissions');
        } catch (\Exception $e) {
            $hasPermissionsTable = false;
        }

        if ($hasPermissionsTable) {
            $moduleName = Str::snake(Str::plural($modelName));
            $deletedPermissions = \App\Models\Permission::where('module', $moduleName)->delete();
            if ($deletedPermissions > 0) {
                $filesDeleted[] = "Database: Deleted {$deletedPermissions} permission entries";
            }
        }
        $this->removePermissionsFromSeeder($modelName);

        // 13. Regenerate autoloader
        $this->regenerateAutoloader();

        // Display results
        if (!empty($filesDeleted)) {
            $this->info("\n✓ Successfully deleted:");
            foreach ($filesDeleted as $file) {
                $this->line("  - {$file}");
            }
        }

        if (!empty($errors)) {
            $this->error("\n✗ Errors:");
            foreach ($errors as $error) {
                $this->line("  - {$error}");
            }
        }

        if (empty($filesDeleted) && empty($errors)) {
            $this->warn("No files found for {$modelName}. Scaffold may not exist.");
        } else {
            $this->info("\n✓ Scaffold revert completed!");
        }

        return 0;
    }

    /**
     * Remove routes from web.php
     */
    private function removePermissionsFromSeeder(string $modelName): void
    {
        $seederPath = base_path('database/seeders/RolePermissionSeeder.php');
        if (!file_exists($seederPath)) return;

        $content = file_get_contents($seederPath);
        $moduleName = Str::snake(Str::plural($modelName));
        
        $pattern = "/'module'\s*=>\s*'" . preg_quote($moduleName, '/') . "'/";
        $newContent = $this->removeArrayEntryContaining($content, $pattern);
        
        if ($newContent !== $content) {
            file_put_contents($seederPath, $newContent);
            $this->info("✓ Removed permissions from RolePermissionSeeder.php");
        }
    }

    private function removeConfigMenu(string $modelName): void
    {
        $configPath = base_path('config/menu.php');
        if (!file_exists($configPath)) return;

        $modelName = trim($this->argument('model'));
        $routeName = 'admin.' . Str::snake(Str::plural($modelName)) . '.index';
        
        $content = file_get_contents($configPath);
        
        $pattern = "/'route'\s*=>\s*'" . preg_quote($routeName, '/') . "'/";
        $newContent = $this->removeArrayEntryContaining($content, $pattern);
        
        if ($newContent !== $content) {
            file_put_contents($configPath, $newContent);
            $this->info("✓ Removed menu from config/menu.php");
        }
    }

    private function removeArrayEntryContaining(string $content, string $searchPattern): string
    {
        while (preg_match($searchPattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            $matchPos = $matches[0][1];
            
            $depth = 0;
            $startPos = -1;
            for ($i = $matchPos; $i >= 0; $i--) {
                $char = $content[$i];
                if ($char === ']') {
                    $depth++;
                } elseif ($char === '[') {
                    if ($depth === 0) {
                        $startPos = $i;
                        break;
                    }
                    $depth--;
                }
            }

            if ($startPos === -1) {
                break;
            }

            $depth = 0;
            $endPos = -1;
            $len = strlen($content);
            for ($i = $startPos; $i < $len; $i++) {
                $char = $content[$i];
                if ($char === '[') {
                    $depth++;
                } elseif ($char === ']') {
                    $depth--;
                    if ($depth === 0) {
                        $endPos = $i;
                        break;
                    }
                }
            }

            if ($endPos === -1) {
                break;
            }

            $endPosIndex = $endPos + 1;
            if ($endPosIndex < $len && $content[$endPosIndex] === ',') {
                $endPosIndex++;
            }
            while ($endPosIndex < $len && ($content[$endPosIndex] === "\r" || $content[$endPosIndex] === "\n")) {
                $endPosIndex++;
            }

            while ($startPos > 0 && ($content[$startPos - 1] === ' ' || $content[$startPos - 1] === "\t")) {
                $startPos--;
            }

            $content = substr($content, 0, $startPos) . substr($content, $endPosIndex);
        }

        return $content;
    }

    private function removeRoutes(string $modelName): void
    {
        $webRoutesPath = base_path('routes/web.php');

        if (!file_exists($webRoutesPath)) {
            $this->warn("Routes file not found: {$webRoutesPath}");
            return;
        }

        $currentContent = file_get_contents($webRoutesPath);
        $controllerName = "{$modelName}Controller";
        // Route path should be plural (products, not product)
        $routePath = Str::snake(Str::plural($modelName));

        // Remove controller import
        $controllerImport = "use App\\Http\\Controllers\\{$controllerName};";
        $currentContent = str_replace("\n{$controllerImport}", '', $currentContent);
        $currentContent = str_replace("{$controllerImport}\n", '', $currentContent);
        $currentContent = str_replace($controllerImport, '', $currentContent);

        // Parse and remove route lines
        $lines = explode("\n", $currentContent);
        $newLines = [];
        $inRouteBlock = false;
        // Comment uses singular form but with proper title case
        $commentLine = "// " . Str::title(str_replace('_', ' ', Str::snake($modelName))) . " routes";

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            // Check if this is the comment line for this model's routes
            if (str_contains(trim($line), trim($commentLine))) {
                $inRouteBlock = true;
                continue; // Skip comment line
            }

            // If in route block, check if line contains routes for this model
            if ($inRouteBlock) {
                // Check if this line is a route for this model (using plural route path)
                if (
                    str_contains($line, "Route::get('{$routePath}/") ||
                    str_contains($line, "Route::post('{$routePath}/") ||
                    str_contains($line, "Route::resource('{$routePath}'") ||
                    str_contains($line, "[{$controllerName}::class")
                ) {
                    continue; // Skip this route line
                }

                // If we encounter an empty line or another comment/route, end the block
                if (trim($line) === '' || str_contains($line, '// ') || (str_contains($line, 'Route::') && !str_contains($line, $routePath))) {
                    $inRouteBlock = false;
                    // Only add empty line if next line is not empty
                    if (trim($line) === '') {
                        if (isset($lines[$i + 1]) && trim($lines[$i + 1]) !== '') {
                            $newLines[] = $line;
                        }
                        continue;
                    }
                }
            }

            $newLines[] = $line;
        }

        $newContent = implode("\n", $newLines);

        // Clean up multiple consecutive empty lines (max 2)
        $newContent = preg_replace("/\n{3,}/", "\n\n", $newContent);

        file_put_contents($webRoutesPath, $newContent);
        $this->info("✓ Removed routes for {$modelName}");
    }

    /**
     * Remove routes from api.php
     */
    private function removeApiRoutes(string $modelName): void
    {
        $apiRoutesPath = base_path('routes/api.php');

        if (!file_exists($apiRoutesPath)) {
            return;
        }

        $currentContent = file_get_contents($apiRoutesPath);
        $controllerName = "{$modelName}ApiController";
        $routePath = Str::kebab(Str::plural($modelName)); // kebab case for api routes

        // Remove controller import
        $controllerImport = "use App\\Http\\Controllers\\Api\\{$controllerName};";
        $currentContent = str_replace("\n{$controllerImport}", '', $currentContent);
        $currentContent = str_replace("{$controllerImport}\n", '', $currentContent);
        $currentContent = str_replace($controllerImport, '', $currentContent);

        // Parse and remove route lines
        $lines = explode("\n", $currentContent);
        $newLines = [];
        $inRouteBlock = false;
        // Comment is exactly "// {ModelNameTitle} routes" e.g., "// Product routes"
        $commentLine = "// " . Str::title(str_replace('_', ' ', Str::snake($modelName))) . " routes";

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            // Check if this is the comment line for this model's API routes
            if (str_contains(trim($line), trim($commentLine))) {
                $inRouteBlock = true;
                continue;
            }

            if ($inRouteBlock) {
                if (
                    str_contains($line, "Route::apiResource('{$routePath}'") ||
                    str_contains($line, "[{$controllerName}::class")
                ) {
                    continue; // Skip this route line
                }

                // If we encounter an empty line or another comment, end the block
                if (trim($line) === '' || str_contains($line, '// ')) {
                    $inRouteBlock = false;
                    if (trim($line) === '') {
                        if (isset($lines[$i + 1]) && trim($lines[$i + 1]) !== '') {
                            $newLines[] = $line;
                        }
                        continue;
                    }
                }
            }

            $newLines[] = $line;
        }

        $newContent = implode("\n", $newLines);
        $newContent = preg_replace("/\n{3,}/", "\n\n", $newContent);

        file_put_contents($apiRoutesPath, $newContent);
        $this->info("✓ Removed API routes for {$modelName}");
    }

    /**
     * Regenerate autoloader
     */
    private function regenerateAutoloader(): void
    {
        $this->info("Regenerating autoloader...");

        try {
            // Clear Laravel's cached config and routes
            $this->call('config:clear');
            $this->call('route:clear');

            // Run composer dump-autoload
            $command = 'composer dump-autoload --quiet';
            $exitCode = 0;
            $output = [];

            // Use exec to capture both output and exit code
            exec($command . ' 2>&1', $output, $exitCode);

            if ($exitCode === 0) {
                $this->info("✓ Autoloader regenerated successfully");
            } else {
                $this->warn("Composer dump-autoload returned exit code: {$exitCode}");
                $this->warn("You may need to run 'composer dump-autoload' manually.");
            }
        } catch (\Exception $e) {
            // Don't fail the whole generation if autoloader regeneration fails
            $this->warn("Could not regenerate autoloader automatically: " . $e->getMessage());
            $this->warn("Please run 'composer dump-autoload' manually.");
        }
    }

    /**
     * Recursively delete a directory
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        return rmdir($dir);
    }
}
