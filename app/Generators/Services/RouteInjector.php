<?php

namespace App\Generators\Services;

use Illuminate\Console\Command;
use App\Generators\Common\CommandData;
use Illuminate\Support\Str;

class RouteInjector
{
    protected Command $command;
    const ADMIN_MARKER = '// [ADMIN_ROUTES_MARKER]';
    const API_MARKER = '// [API_ROUTES_MARKER]';

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function injectRoutes(CommandData $commandData): void
    {
        $webCommandData = clone $commandData;
        $webCommandData->isApi = false;
        $this->addWebRoutes($webCommandData);

        if ($commandData->isApi) {
            $apiCommandData = clone $commandData;
            $apiCommandData->isApi = true;
            $apiCommandData->setControllerName($commandData->modelName . 'ApiController');
            $this->addApiRoutes($apiCommandData);
        }
    }

    private function addApiRoutes(CommandData $commandData): void
    {
        $routePath = $commandData->modelNameKebabPlural;
        $controllerClass = "\\App\\Http\\Controllers\\Api\\" . $commandData->controllerName . "::class";
        $modelNameTitle = $commandData->modelNameTitle;

        $apiRoutesPath = base_path('routes/api.php');

        if (!file_exists($apiRoutesPath)) {
            $defaultContent = "<?php\n\nuse Illuminate\\Http\\Request;\nuse Illuminate\\Support\\Facades\\Route;\n\nRoute::prefix('v1')->middleware(['auth:sanctum'])->group(function () {\n    " . self::API_MARKER . "\n});\n";
            file_put_contents($apiRoutesPath, $defaultContent);
        }

        $routes = "// {$modelNameTitle} routes\n    Route::apiResource('{$routePath}', {$controllerClass});\n    " . self::API_MARKER;

        $this->injectMarkerRoute($apiRoutesPath, self::API_MARKER, "Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {\n    " . self::API_MARKER . "\n});", "Route::apiResource('{$routePath}'", $routes, "API routes for {$routePath}");
    }

    private function addWebRoutes(CommandData $commandData): void
    {
        $routeName = $commandData->getRouteName();
        $routePath = Str::startsWith($routeName, 'admin.') ? Str::after($routeName, 'admin.') : $routeName;
        $routeNameWithoutPrefix = $routePath;
        $controllerClass = "\\App\\Http\\Controllers\\" . $commandData->controllerName . "::class";
        $modelNameTitle = $commandData->modelNameTitle;

        $importRoutesString = "";
        if ($commandData->withImport) {
            $importRoutesString = "
        Route::get('{$routePath}/import', [{$controllerClass}, 'importForm'])->name('{$routeNameWithoutPrefix}.importForm');
        Route::post('{$routePath}/import', [{$controllerClass}, 'import'])->name('{$routeNameWithoutPrefix}.import');
        Route::get('{$routePath}/export', [{$controllerClass}, 'export'])->name('{$routeNameWithoutPrefix}.export');
        Route::get('{$routePath}/sample/{format?}', [{$controllerClass}, 'downloadSample'])->name('{$routeNameWithoutPrefix}.downloadSample');";
        }

        $routes = "
        // {$modelNameTitle} routes{$importRoutesString}
        Route::resource('{$routePath}', {$controllerClass});
        " . self::ADMIN_MARKER;

        $webRoutesPath = base_path('routes/web.php');
        $groupTemplate = "\nRoute::prefix('admin')->name('admin.')->middleware(['auth', 'web'])->group(function () {\n        " . self::ADMIN_MARKER . "\n});\n";

        $this->injectMarkerRoute($webRoutesPath, self::ADMIN_MARKER, $groupTemplate, "Route::resource('{$routePath}'", ltrim($routes), "Web routes for {$routePath}");
    }

    private function injectMarkerRoute(string $filePath, string $marker, string $groupFallback, string $checkSnippet, string $routesSnippet, string $description): void
    {
        $currentContent = file_exists($filePath) ? file_get_contents($filePath) : "";

        if (str_contains($currentContent, $checkSnippet)) {
            $this->command->info("✓ Routes for {$description} already exist, skipping.");
            return;
        }

        if (!str_contains($currentContent, $marker)) {
            $currentContent .= $groupFallback;
        }

        $newContent = str_replace($marker, $routesSnippet, $currentContent);
        file_put_contents($filePath, $newContent);

        $this->command->info("✓ Added {$description} using marker injection");
    }
}
