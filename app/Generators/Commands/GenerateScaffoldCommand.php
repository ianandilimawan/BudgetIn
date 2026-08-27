<?php

namespace App\Generators\Commands;

use Illuminate\Console\Command;
use App\Generators\Common\CommandData;
use App\Generators\Utils\GeneratorFieldsInputUtil;
use App\Generators\Generators\ModelGenerator;
use App\Generators\Generators\ControllerGenerator;
use App\Generators\Generators\RequestGenerator;
use App\Generators\Generators\CreateRequestGenerator;
use App\Generators\Generators\UpdateRequestGenerator;
use App\Generators\Generators\MigrationGenerator;
use App\Generators\Generators\ViewGenerator;
use App\Generators\Generators\MenuGenerator;
use App\Generators\Generators\PermissionGenerator;
use App\Generators\Generators\PolicyGenerator;
use App\Generators\Generators\SeederGenerator;
use App\Generators\Generators\UnitTestGenerator;
use App\Generators\Generators\FactoryGenerator;
use App\Generators\Generators\PowerGridTableGenerator;
use App\Generators\Generators\EnumGenerator;
use App\Generators\Generators\ApiResourceGenerator;
use App\Generators\Services\FieldParser;
use App\Generators\Services\SchemaIntrospector;
use App\Generators\Services\RouteInjector;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateScaffoldCommand extends Command
{
    protected $signature = 'generate:scaffold
                            {model? : The name of the model}
                            {--fromTable : Generate from existing table structure}
                            {--tableName= : The name of the existing database table}
                            {--fields= : Fields definition (name:type:htmlType:options)}
                            {--schema= : Path to JSON schema file}
                            {--with-factory : Generate factory}
                            {--with-seeder : Generate seeder}
                            {--migration : Generate migration}
                            {--no-controller : Skip controller generation}
                            {--no-model : Skip model generation}
                            {--no-views : Skip views generation}
                            {--no-request : Skip request generation}
                            {--no-routes : Skip routes generation}
                            {--no-menu : Skip menu generation}
                            {--no-permissions : Skip permissions generation}
                            {--skip-db : Skip inserting permissions to database directly}
                            {--no-test : Skip test generation}
                            {--section-title= : Section title for the menu}
                            {--with-import : Generate import/export feature}
                            {--only= : Comma-separated generators to run (model,migration,controller,view,datatable,request,factory,seeder,test,menu,permission,enum)}
                            {--except= : Comma-separated generators to skip}
                            {--api : Generate API CRUD instead of web CRUD}
                            {--soft-deletes : Add soft deletes to the generated CRUD}
                            {--force : Overwrite existing files without prompting}';

    protected $description = 'Generate CRUD operations - from table structure or field definition';

    protected FieldParser $fieldParser;
    protected SchemaIntrospector $schemaIntrospector;
    protected RouteInjector $routeInjector;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->fieldParser = new FieldParser($this);
        $this->schemaIntrospector = new SchemaIntrospector();
        $this->routeInjector = new RouteInjector($this);

        $modelName = $this->argument('model');
        $tableName = $this->option('tableName');
        $fieldsInput = $this->option('fields');
        $schemaPath = $this->option('schema');

        $supportedOptions = [
            'force', 'with-factory', 'with-seeder', 'migration',
            'no-controller', 'no-model', 'no-views', 'no-request',
            'no-routes', 'no-menu', 'no-permissions', 'skip-db',
            'no-test', 'with-import', 'api', 'soft-deletes'
        ];
        $options = [];
        foreach ($supportedOptions as $opt) {
            if ($this->option($opt)) {
                $options[] = "--{$opt}";
            }
        }

        if ($modelName && !preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $modelName)) {
            $this->error("Invalid model name '{$modelName}'. Model name must be alphanumeric and start with a letter.");
            return 1;
        }

        try {
            if ($this->option('fromTable')) {
                $actualTableName = $tableName ?? $modelName;
                if (!$actualTableName) {
                    $this->error('Please provide model name or use --tableName option');
                    return 1;
                }
                return $this->generateFromExistingTable($actualTableName, $modelName, $options);
            }

            if (!$modelName) {
                $modelName = trim((string) $this->ask('Model name (e.g., Product, User, Order):'));
                if (empty($modelName) || !preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $modelName)) {
                    $this->error('Valid model name is required (alphanumeric starting with a letter)');
                    return 1;
                }
            }

            $fields = [];
            if ($schemaPath) {
                $fields = GeneratorFieldsInputUtil::parseFieldsFromJson($schemaPath);
            } elseif ($fieldsInput) {
                $fieldsArray = preg_split('/,(?![^\(]*\))/', $fieldsInput);
                $fields = GeneratorFieldsInputUtil::parseFieldsFromCommand($fieldsArray);
            } else {
                $this->info('No fields provided. Starting interactive mode...');
                $this->info('Format: name:dbType:htmlType:options');
                $fields = $this->fieldParser->collectFieldsInteractively();

                if (empty($fields)) {
                    $this->warn('No additional fields provided. Will generate CRUD with basic structure (id, created_at, updated_at only).');
                }
            }

            $commandData = new CommandData($modelName, $fields, $options);

            if ($commandData->withMenu) {
                $sectionTitle = $this->getSectionTitle();
                if ($sectionTitle !== null) {
                    $commandData->sectionTitle = $sectionTitle;
                }
            }

            $this->executeGenerators($commandData);

            $this->info("CRUD generation completed successfully!");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function generateFromExistingTable(string $tableName, ?string $modelName = null, array $options = []): int
    {
        try {
            $this->info("Reading table structure: {$tableName}");

            if (!Schema::hasTable($tableName)) {
                $this->error("Table {$tableName} does not exist!");
                return 1;
            }

            $modelName = $modelName ?? Str::studly(Str::singular($tableName));
            $columns = $this->schemaIntrospector->getColumns($tableName);
            $foreignKeys = $this->schemaIntrospector->getForeignKeys($tableName);
            $fields = $this->fieldParser->convertColumnsToFields($columns, $foreignKeys);
            $migrationExists = $this->schemaIntrospector->migrationExists($tableName);

            if (in_array('--migration', $options)) {
                if ($migrationExists) {
                    $this->warn("Migration file already exists, but will generate anyway due to --migration flag.");
                } else {
                    $this->info("Will generate migration based on table structure (--migration flag set).");
                }
            } else {
                if ($migrationExists) {
                    $this->info("Migration file already exists, skipping migration generation.");
                } else {
                    $this->info("Skipping migration generation (use --migration flag to generate).");
                }
                $options[] = '--no-migration';
            }

            $commandData = new CommandData($modelName, $fields, $options);

            if ($commandData->withMenu) {
                $sectionTitle = $this->getSectionTitle();
                if ($sectionTitle !== null) {
                    $commandData->sectionTitle = $sectionTitle;
                }
            }

            $this->executeGenerators($commandData);

            $this->info("CRUD generation completed successfully!");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    private function getGeneratorRegistry(): array
    {
        return [
            'model' => ['class' => ModelGenerator::class, 'flag' => 'withModel'],
            'enum' => ['class' => EnumGenerator::class, 'flag' => null],
            'resource' => ['class' => ApiResourceGenerator::class, 'flag' => null],
            'controller' => ['class' => ControllerGenerator::class, 'flag' => 'withController'],
            'datatable' => ['class' => PowerGridTableGenerator::class, 'flag' => 'withController'],
            'request' => ['class' => RequestGenerator::class, 'flag' => 'withRequest'],
            'migration' => ['class' => MigrationGenerator::class, 'flag' => 'withMigration'],
            'view' => ['class' => ViewGenerator::class, 'flag' => 'withViews'],
            'seeder' => ['class' => SeederGenerator::class, 'flag' => 'withSeeder'],
            'factory' => ['class' => FactoryGenerator::class, 'flag' => 'withFactory'],
            'test' => ['class' => UnitTestGenerator::class, 'flag' => 'withTest'],
            'menu' => ['class' => MenuGenerator::class, 'flag' => 'withMenu'],
            'permission' => ['class' => PermissionGenerator::class, 'flag' => 'withPermissions'],
            'policy' => ['class' => PolicyGenerator::class, 'flag' => null],
        ];
    }

    private function executeGenerators(CommandData $commandData): void
    {
        $this->info("Generating CRUD for {$commandData->modelName}...");

        $only = $this->option('only') ? explode(',', $this->option('only')) : null;
        $except = $this->option('except') ? explode(',', $this->option('except')) : [];

        $generatorRegistry = $this->getGeneratorRegistry();
        $generatorsToRun = [];

        foreach ($generatorRegistry as $key => $generatorInfo) {
            if ($only !== null && !in_array($key, $only)) continue;
            if (in_array($key, $except)) continue;
            
            $featureFlag = $generatorInfo['flag'];
            if ($featureFlag !== null && !$commandData->$featureFlag) {
                if ($only === null) {
                   continue;
                }
            }

            $generatorClass = $generatorInfo['class'];
            
            if ($key === 'controller') {
                $webCommandData = clone $commandData;
                $webCommandData->isApi = false;
                $generatorsToRun[] = new $generatorClass($webCommandData);
                
                if ($commandData->isApi) {
                    $apiCommandData = clone $commandData;
                    $apiCommandData->isApi = true;
                    $apiCommandData->setControllerName($commandData->modelName . 'ApiController');
                    $generatorsToRun[] = new $generatorClass($apiCommandData);
                }
            } elseif ($key === 'view' || $key === 'datatable') {
                 $webCommandData = clone $commandData;
                 $webCommandData->isApi = false;
                 $generatorsToRun[] = new $generatorClass($webCommandData);
            } elseif ($key === 'request') {
                 $generatorsToRun[] = new CreateRequestGenerator($commandData);
                 $generatorsToRun[] = new UpdateRequestGenerator($commandData);
            } elseif ($key === 'permission') {
                 $hasPermissionsTable = false;
                 try {
                     $hasPermissionsTable = Schema::hasTable('permissions');
                 } catch (\Exception $e) {
                     $hasPermissionsTable = false;
                 }
                 if ($hasPermissionsTable) {
                     $generatorsToRun[] = new $generatorClass($commandData);
                 } else {
                     $this->info("Skipping permissions generation: permissions table does not exist.");
                 }
            } else {
                $generatorsToRun[] = new $generatorClass($commandData);
            }
        }

        $executedGenerators = [];
        $failed = false;

        foreach ($generatorsToRun as $generator) {
            if ($generator->generate()) {
                $this->info("✓ Generated: " . get_class($generator));
                $executedGenerators[] = $generator;
            } else {
                if ($generator instanceof PowerGridTableGenerator) {
                    $this->error("Failed to generate DataTable. Make sure livewire-powergrid is installed.");
                } else {
                    $this->error("Failed to generate " . get_class($generator));
                }
                $failed = true;
                break;
            }
        }

        if ($failed) {
            $this->warn("\nGeneration failed! Initiating rollback process...");
            foreach (array_reverse($executedGenerators) as $generator) {
                $this->info("Rolling back: " . get_class($generator));
                $generator->rollback();
            }
            $this->error("\nRollback completed. Generation aborted.");
            return;
        }

        if ($commandData->withRoutes) {
            $this->routeInjector->injectRoutes($commandData);
        }
    }

    private function getSectionTitle(): ?string
    {
        $sectionTitleOption = $this->option('section-title');

        if ($sectionTitleOption !== null && $sectionTitleOption !== '') {
            return $sectionTitleOption;
        }

        if ($this->option('no-interaction')) {
            return null;
        }

        $existingSections = array_keys(config('menu', []));
        if (!empty($existingSections)) {
            $this->info('Available section titles:');
            foreach ($existingSections as $index => $section) {
                $this->line('  ' . ($index + 1) . '. ' . $section);
            }
            $this->line('  ' . (count($existingSections) + 1) . '. Create new section title');
            $this->line('  ' . (count($existingSections) + 2) . '. No section title (auto-detect)');

            $choice = $this->ask('Select section title (or press Enter to auto-detect):');

            if ($choice === '' || $choice === null) {
                return null;
            }

            if (is_numeric($choice)) {
                $choiceIndex = (int) $choice;

                if ($choiceIndex >= 1 && $choiceIndex <= count($existingSections)) {
                    return $existingSections[$choiceIndex - 1];
                } elseif ($choiceIndex === count($existingSections) + 1) {
                    $newTitle = $this->ask('Enter new section title:');
                    return $newTitle ?: null;
                } elseif ($choiceIndex === count($existingSections) + 2) {
                    return null;
                }
            } else {
                return trim($choice) ?: null;
            }
        } else {
            $createNew = $this->confirm('No existing section titles found. Create a new section title?', false);

            if ($createNew) {
                $newTitle = $this->ask('Enter section title:');
                return $newTitle ?: null;
            }
        }
        return null;
    }

}
