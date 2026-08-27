<?php

namespace App\Generators\Generators;

use App\Generators\Common\CommandData;
use App\Generators\Utils\FileUtil;

abstract class BaseGenerator
{
    protected CommandData $commandData;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
    }

    abstract public function generate(): bool;

    abstract public function rollback(): bool;

    protected function generateFile(string $template, string $outputPath, array $replacements = []): bool
    {
        try {
            $force = in_array('--force', $this->commandData->options);
            
            if (!$force && \Illuminate\Support\Facades\File::exists($outputPath)) {
                \Illuminate\Support\Facades\Log::info("Skipping {$outputPath}, already exists. Use --force to overwrite.");
                return true; 
            }

            $content = FileUtil::replaceTemplate($template, $replacements);
            return FileUtil::createFile($outputPath, $content);
        } catch (\Throwable $e) {
            \Log::error("Failed to generate file {$outputPath}: " . $e->getMessage(), [
                'exception' => $e,
                'file' => $outputPath
            ]);
            return false;
        }
    }

    protected function getReplacements(): array
    {
        return [
            '{{MODEL_NAME}}' => $this->commandData->modelName,
            '{{MODEL_NAME_TITLE}}' => $this->commandData->modelNameTitle,
            '{{MODEL_NAME_PLURAL}}' => $this->commandData->modelNamePlural,
            '{{MODEL_NAME_CAMEL}}' => $this->commandData->modelNameCamel,
            '{{MODEL_NAME_CAMEL_PLURAL}}' => $this->commandData->modelNameCamelPlural,
            '{{MODEL_NAME_SNAKE}}' => $this->commandData->modelNameSnake,
            '{{MODEL_NAME_SNAKE_PLURAL}}' => $this->commandData->modelNameSnakePlural,
            '{{MODEL_NAME_KEBAB}}' => $this->commandData->modelNameKebab,
            '{{MODEL_NAME_KEBAB_PLURAL}}' => $this->commandData->modelNameKebabPlural,
            '{{MODEL_NAME_LOWER}}' => $this->commandData->modelNameLower,
            '{{MODEL_NAME_LOWER_PLURAL}}' => $this->commandData->modelNameLowerPlural,
            '{{MODEL_NAME_UPPER}}' => $this->commandData->modelNameUpper,

            // camelCase aliases for stub consistency
            '{{modelName}}' => $this->commandData->modelName,
            '{{modelNameCamel}}' => $this->commandData->modelNameCamel,
            '{{modelNameSnake}}' => $this->commandData->modelNameSnake,
            '{{modelNamePlural}}' => $this->commandData->modelNamePlural,
            '{{modelNameLower}}' => $this->commandData->modelNameLower,

            '{{CONTROLLER_NAME}}' => $this->commandData->controllerName,
            '{{REQUEST_NAME}}' => $this->commandData->requestName,
            '{{FACTORY_NAME}}' => $this->commandData->factoryName,
            '{{SEEDER_NAME}}' => $this->commandData->seederName,
            '{{TEST_NAME}}' => $this->commandData->testName,
            '{{TABLE_NAME}}' => $this->commandData->getTableName(),
            '{{ROUTE_NAME}}' => $this->commandData->getRouteName(),
            '{{VIEW_PATH}}' => $this->commandData->getViewPath(),
            '{{NAMESPACE}}' => $this->commandData->getNamespace(),
            '{{CONTROLLER_NAMESPACE}}' => $this->commandData->getControllerNamespace(),
            '{{REQUEST_NAMESPACE}}' => $this->commandData->getRequestNamespace(),
            '{{FACTORY_NAMESPACE}}' => $this->commandData->getFactoryNamespace(),
            '{{SEEDER_NAMESPACE}}' => $this->commandData->getSeederNamespace(),
            '{{TEST_NAMESPACE}}' => $this->commandData->getTestNamespace(),
            '{{LIVEWIRE_TABLE_TAG}}' => $this->commandData->modelNameKebab . '-table',
        ];
    }
}
