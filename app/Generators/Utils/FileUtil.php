<?php

namespace App\Generators\Utils;

use Illuminate\Support\Facades\File;

class FileUtil
{
    public static function createFile(string $path, string $contents): bool
    {
        $directory = dirname($path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return File::put($path, $contents) !== false;
    }

    public static function createFileIfNotExists(string $path, string $contents): bool
    {
        if (File::exists($path)) {
            return false;
        }

        return self::createFile($path, $contents);
    }

    public static function getFileContents(string $path): string
    {
        if (!File::exists($path)) {
            throw new \Exception("File not found: {$path}");
        }

        return File::get($path);
    }

    public static function getStubContents(string $stubName): string
    {
        $stubPath = resource_path("stubs/{$stubName}.stub");

        if (!File::exists($stubPath)) {
            throw new \Exception("Stub not found: {$stubPath}");
        }

        return File::get($stubPath);
    }

    public static function replaceTemplate(string $template, array $replacements): string
    {
        foreach ($replacements as $search => $replace) {
            $template = str_replace($search, $replace, $template);
        }

        return $template;
    }

    public static function replaceStub(string $stubName, array $replacements): string
    {
        $template = self::getStubContents($stubName);
        return self::replaceTemplate($template, $replacements);
    }

    public static function getModelPath(string $modelName): string
    {
        return app_path("Models/{$modelName}.php");
    }

    public static function getControllerPath(string $controllerName, bool $isApi = false): string
    {
        if ($isApi) {
            return app_path("Http/Controllers/Api/{$controllerName}.php");
        }
        return app_path("Http/Controllers/{$controllerName}.php");
    }

    public static function getRequestPath(string $requestName): string
    {
        return app_path("Http/Requests/{$requestName}.php");
    }

    public static function getMigrationPath(string $tableName): string
    {
        $timestamp = date('Y_m_d_His');
        return database_path("migrations/{$timestamp}_create_{$tableName}_table.php");
    }

    public static function getViewPath(string $viewName): string
    {
        $viewName = str_replace('.', '/', $viewName);
        return resource_path("views/{$viewName}.blade.php");
    }

    public static function getFactoryPath(string $factoryName): string
    {
        return database_path("factories/{$factoryName}.php");
    }

    public static function getSeederPath(string $seederName): string
    {
        return database_path("seeders/{$seederName}.php");
    }

    public static function getTestPath(string $testName): string
    {
        return base_path("tests/Feature/{$testName}.php");
    }

    public static function getLivewireTablePath(string $tableName): string
    {
        return app_path("Livewire/Tables/{$tableName}.php");
    }

    public static function getResourcePath(string $modelName): string
    {
        return app_path("Http/Resources/{$modelName}Resource.php");
    }

    public static function delete(string $path): bool
    {
        return File::delete($path);
    }

    public static function deleteDirectory(string $path): bool
    {
        return File::deleteDirectory($path);
    }

    /**
     * Inject a string into a file after a specific search marker.
     * 
     * @param string $path File path
     * @param string $searchMarker String to search for
     * @param string $insertString String to insert
     * @param bool $after Insert after (true) or before (false) the marker
     * @return bool True if successful, false otherwise
     */
    public static function injectIntoFile(string $path, string $searchMarker, string $insertString, bool $after = true): bool
    {
        if (!File::exists($path)) {
            return false;
        }

        $content = File::get($path);
        
        // Check if string is already injected
        if (str_contains($content, trim($insertString))) {
            return true;
        }

        if (str_contains($content, $searchMarker)) {
            if ($after) {
                // Insert after the marker
                $replacement = $searchMarker . "\n" . $insertString;
            } else {
                // Insert before the marker
                $replacement = $insertString . "\n" . $searchMarker;
            }
            
            $content = str_replace($searchMarker, $replacement, $content);
            return File::put($path, $content) !== false;
        }

        return false;
    }
}
