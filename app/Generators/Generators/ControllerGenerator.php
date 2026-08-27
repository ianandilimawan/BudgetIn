<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class ControllerGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $stubName = $this->commandData->isApi ? 'controller-api' : 'controller';
        $template = FileUtil::getStubContents($stubName);
        $outputPath = FileUtil::getControllerPath($this->commandData->controllerName, $this->commandData->isApi);

        $useTraitsImports = "use App\Traits\HasFileUpload;\n";
        $useTraitsStatements = "    use HasFileUpload;\n";
        $importPermissions = "";
        $importExportAbstracts = "";

        if ($this->commandData->withImport) {
            $useTraitsImports .= "use App\Traits\HasImportExport;\n";
            $useTraitsStatements = "    use HasFileUpload, HasImportExport;\n";
            $importPermissions = ", 'import', 'importForm'";
            
            $modelName = $this->commandData->modelName;
            $modelNameSnakePlural = $this->commandData->modelNameSnakePlural;
            $modelNameSnake = $this->commandData->modelNameSnake;
            
            $importExportAbstracts = "
    protected function getModelClass(): string
    {
        return {$modelName}::class;
    }

    protected function getViewPath(): string
    {
        return '{$modelNameSnakePlural}';
    }

    protected function getRouteName(): string
    {
        return 'admin.{$modelNameSnakePlural}';
    }

    protected function getModelNameSnake(): string
    {
        return '{$modelNameSnake}';
    }
";
        }

        $relatedFetch = "";
        $relatedCompact = "";
        foreach ($this->commandData->fields as $field) {
            if ($field->belongsTo) {
                $relatedModel = $field->belongsTo;
                $varName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural($relatedModel));
                $relatedFetch .= "        \$displayCol = \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\\{$relatedModel})->getTable(), 'name') ? 'name' : (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\\{$relatedModel})->getTable(), 'title') ? 'title' : 'id');\n";
                $relatedFetch .= "        \${$varName} = \App\Models\\{$relatedModel}::pluck(\$displayCol, 'id');\n";
                $relatedCompact .= ", '{$varName}'";
            } elseif ($field->belongsToMany) {
                $relatedModel = $field->belongsToMany;
                $varName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural($relatedModel));
                $relatedFetch .= "        \$displayCol = \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\\{$relatedModel})->getTable(), 'name') ? 'name' : (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\\{$relatedModel})->getTable(), 'title') ? 'title' : 'id');\n";
                $relatedFetch .= "        \${$varName} = \App\Models\\{$relatedModel}::pluck(\$displayCol, 'id');\n";
                $relatedCompact .= ", '{$varName}'";
            } elseif ($field->foreignKey) {
                $referencedTable = $field->foreignKey['referenced_table'];
                $relatedModel = \Illuminate\Support\Str::studly(\Illuminate\Support\Str::singular($referencedTable));
                $varName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural($relatedModel));
                $relatedFetch .= "        \$displayCol = \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\\{$relatedModel})->getTable(), 'name') ? 'name' : (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\\{$relatedModel})->getTable(), 'title') ? 'title' : 'id');\n";
                $relatedFetch .= "        \${$varName} = \App\Models\\{$relatedModel}::pluck(\$displayCol, 'id');\n";
                $relatedCompact .= ", '{$varName}'";
            }
        }

        $replacements = array_merge($this->getReplacements(), [
            '{{CREATE_REQUEST_CLASS}}' => $this->commandData->createRequestName,
            '{{UPDATE_REQUEST_CLASS}}' => $this->commandData->updateRequestName,
            '{{MODEL_VARIABLE}}' => $this->commandData->modelNameCamel,
            '{{MODEL_VARIABLE_PLURAL}}' => $this->commandData->modelNameCamel . 's',
            '{{MODEL_NAME_SNAKE}}' => $this->commandData->modelNameSnake,
            '{{USE_TRAITS_IMPORTS}}' => $useTraitsImports,
            '{{USE_TRAITS_STATEMENTS}}' => $useTraitsStatements,
            '{{IMPORT_PERMISSIONS}}' => $importPermissions,
            '{{IMPORT_EXPORT_ABSTRACT_METHODS}}' => $importExportAbstracts,
            '{{RELATED_VARIABLES_FETCH}}' => rtrim($relatedFetch, "\n") ? $relatedFetch : "",
            '{{RELATED_VARIABLES_COMPACT}}' => $relatedCompact,
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        $outputPath = FileUtil::getControllerPath($this->commandData->controllerName, $this->commandData->isApi);
        return FileUtil::delete($outputPath);
    }
}
