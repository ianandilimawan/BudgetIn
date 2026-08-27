<?php

namespace App\Generators\Common;

use Illuminate\Support\Str;

class CommandData
{
    public string $modelName;
    public string $modelNamePlural;
    public string $modelNameCamel;
    public string $modelNameSnake;
    public string $modelNameKebab;
    public string $modelNameLower;
    public string $modelNameLowerPlural;
    public string $modelNameUpper;
    public string $modelNameTitle;
    public string $modelNameSnakePlural;
    public string $modelNameKebabPlural;
    public string $modelNameCamelPlural;

    public array $fields = [];
    public array $options = [];

    public bool $withMigration = true;
    public bool $withController = true;
    public bool $withModel = true;
    public bool $withViews = true;
    public bool $withRequest = true;
    public bool $withRoutes = true;
    public bool $withFactory = false;
    public bool $withSeeder = false;
    public bool $withTest = true;
    public bool $withMenu = true;
    public bool $withPermissions = true;
    public bool $withImport = false;
    public bool $withSoftDeletes = false;
    public ?string $sectionTitle = null;
    public bool $isApi = false;
    public bool $skipDb = false;

    public string $controllerName;
    public string $requestName;
    public string $createRequestName;
    public string $updateRequestName;
    public string $factoryName;
    public string $seederName;
    public string $testName;

    public function __construct(string $modelName, array $fields = [], array $options = [])
    {
        $this->modelName = $modelName;
        $this->fields = $fields;
        $this->options = $options;

        $this->generateNames();
        $this->parseOptions();
    }

    private function generateNames(): void
    {
        $pluralName = str($this->modelName)->plural();
        $this->modelNamePlural = Str::headline($pluralName);
        $this->modelNameCamel = str($this->modelName)->camel();
        $this->modelNameSnake = str($this->modelName)->snake();
        $this->modelNameKebab = str($this->modelName)->kebab();
        $this->modelNameLower = str($this->modelName)->lower();
        $this->modelNameLowerPlural = str($pluralName)->lower();
        $this->modelNameUpper = str($this->modelName)->upper();
        $this->modelNameSnakePlural = str($pluralName)->snake();
        $this->modelNameKebabPlural = str($pluralName)->kebab();
        $this->modelNameCamelPlural = str($pluralName)->camel();
        $this->modelNameTitle = Str::headline($this->modelName);

        $this->controllerName = $this->modelName . 'Controller';
        $this->requestName = $this->modelName . 'Request';
        $this->createRequestName = 'Create' . $this->modelName . 'Request';
        $this->updateRequestName = 'Update' . $this->modelName . 'Request';
        $this->factoryName = $this->modelName . 'Factory';
        $this->seederName = $this->modelName . 'Seeder';
        $this->testName = $this->modelName . 'Test';
    }

    public function __clone()
    {
        $clonedFields = [];
        foreach ($this->fields as $field) {
            $clonedFields[] = is_object($field) ? clone $field : $field;
        }
        $this->fields = $clonedFields;
    }

    private function parseOptions(): void
    {
        foreach ($this->options as $option) {
            switch ($option) {
                case '--migration':
                    $this->withMigration = true;
                    break;
                case '--no-migration':
                    $this->withMigration = false;
                    break;
                case '--no-controller':
                    $this->withController = false;
                    break;
                case '--no-model':
                    $this->withModel = false;
                    break;
                case '--no-views':
                    $this->withViews = false;
                    break;
                case '--no-request':
                    $this->withRequest = false;
                    break;
                case '--no-routes':
                    $this->withRoutes = false;
                    break;
                case '--with-factory':
                    $this->withFactory = true;
                    break;
                case '--with-seeder':
                    $this->withSeeder = true;
                    break;
                case '--with-test':
                    $this->withTest = true;
                    break;
                case '--no-test':
                    $this->withTest = false;
                    break;
                case '--no-menu':
                    $this->withMenu = false;
                    break;
                case '--no-permissions':
                    $this->withPermissions = false;
                    break;
                case '--with-import':
                    $this->withImport = true;
                    break;
                case '--soft-deletes':
                    $this->withSoftDeletes = true;
                    break;
                case '--api':
                    $this->isApi = true;
                    // API mode: keep views and menu enabled (generate both API + CMS)
                    // Don't disable views and menu - we want both API and Web CMS
                    break;
                case '--skip-db':
                    $this->skipDb = true;
                    break;
            }
        }

        // If views are disabled, menu should also be disabled (menu is for accessing views)
        if (!$this->withViews) {
            $this->withMenu = false;
        }
    }

    public function getTableName(): string
    {
        return $this->modelNameSnakePlural;
    }

    public function getRouteName(): string
    {
        if ($this->isApi) {
            // API routes use api prefix
            return 'api.v1.' . $this->modelNameKebabPlural;
        }
        // Route dibuat di dalam Route::prefix('admin')->name('admin.')->group()
        // jadi route path tidak perlu prefix admin. karena sudah ada di prefix()
        // route name akan otomatis menjadi admin.{route_name} karena name('admin.')
        // Jadi kita perlu return dengan prefix admin. untuk digunakan di views/controller
        return 'admin.' . $this->modelNameSnakePlural;
    }

    public function getViewPath(): string
    {
        return 'admin.' . $this->modelNameSnakePlural;
    }

    public function getNamespace(): string
    {
        return 'App\\Models';
    }

    public function getControllerNamespace(): string
    {
        if ($this->isApi) {
            return 'App\\Http\\Controllers\\Api';
        }
        return 'App\\Http\\Controllers';
    }

    public function getRequestNamespace(): string
    {
        return 'App\\Http\\Requests';
    }

    public function getFactoryNamespace(): string
    {
        return 'Database\\Factories';
    }

    public function getSeederNamespace(): string
    {
        return 'Database\\Seeders';
    }

    public function getTestNamespace(): string
    {
        return 'Tests\\Feature';
    }

    /**
     * Update controller name (useful when cloning for API controller)
     */
    public function setControllerName(string $controllerName): void
    {
        $this->controllerName = $controllerName;
    }
}
