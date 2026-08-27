<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;
use Illuminate\Support\Facades\Schema;

class ModelGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('model');
        $outputPath = FileUtil::getModelPath($this->commandData->modelName);

        $replacements = array_merge($this->getReplacements(), [
            '{{FILLABLE_FIELDS}}' => $this->getFillableFields(),
            '{{CASTS}}' => $this->getCasts(),
            '{{HIDDEN_FIELDS}}' => $this->getHiddenFields(),
            '{{RELATIONSHIPS}}' => $this->getRelationships(),
            '{{SOFT_DELETES_IMPORT}}' => $this->commandData->withSoftDeletes ? "use Illuminate\Database\Eloquent\SoftDeletes;" : "",
            '{{SOFT_DELETES_TRAIT}}' => $this->commandData->withSoftDeletes ? ", SoftDeletes" : "",
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        $outputPath = FileUtil::getModelPath($this->commandData->modelName);
        return FileUtil::delete($outputPath);
    }

    private function getFillableFields(): string
    {
        $fillableFields = [];
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            // Skip timestamp fields from fillable - Laravel handles them automatically
            if (in_array($field->name, $timestampFields)) {
                continue;
            }

            if ($field->fillable) {
                $fillableFields[] = "'{$field->name}'";
            }
        }

        return implode(', ', $fillableFields);
    }

    private function getCasts(): string
    {
        $casts = [];

        foreach ($this->commandData->fields as $field) {
            if ($field->enumData) {
                $enumClass = $field->enumData['class'];
                $casts[] = "'{$field->name}' => \App\Enums\\{$enumClass}::class";
                continue;
            }

            switch ($field->dbType) {
                case 'boolean':
                    $casts[] = "'{$field->name}' => 'boolean'";
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                    $casts[] = "'{$field->name}' => 'datetime'";
                    break;
                case 'json':
                    $casts[] = "'{$field->name}' => 'array'";
                    break;
            }
        }

        if (empty($casts)) {
            return '';
        }

        return "protected \$casts = [\n        " . implode(",\n        ", $casts) . "\n    ];";
    }

    private function getHiddenFields(): string
    {
        $hiddenFields = [];
        $sensitiveFields = ['password', 'remember_token', 'secret', 'api_token', 'token'];

        foreach ($this->commandData->fields as $field) {
            if (in_array(strtolower($field->name), $sensitiveFields)) {
                $hiddenFields[] = "'{$field->name}'";
            }
        }

        if (empty($hiddenFields)) {
            return '';
        }

        return "protected \$hidden = [\n        " . implode(",\n        ", $hiddenFields) . "\n    ];";
    }

    private function getRelationships(): string
    {
        $relationships = [];
        $tableName = $this->commandData->getTableName();

        foreach ($this->commandData->fields as $field) {
            if ($field->belongsTo) {
                $relatedModelName = $field->belongsTo;
                $column = $field->name;
                
                // Determine relationship name (remove trailing _id suffix if present)
                $relationName = preg_replace('/_id$/', '', $column);
                $relationName = \Illuminate\Support\Str::camel($relationName);

                $relationships[] = "    public function {$relationName}()\n    {\n        return \$this->belongsTo(\\App\\Models\\{$relatedModelName}::class, '{$column}');\n    }";
            } elseif ($field->foreignKey) {
                $fk = $field->foreignKey;
                $referencedTable = $fk['referenced_table'];
                $column = $fk['column'];

                // Convert table name to model name (singular, studly case)
                $relatedModelName = \Illuminate\Support\Str::studly(\Illuminate\Support\Str::singular($referencedTable));

                // Determine relationship name (remove trailing _id suffix if present)
                $relationName = preg_replace('/_id$/', '', $column);
                $relationName = \Illuminate\Support\Str::camel($relationName);

                // Generate belongsTo relationship
                $relationships[] = "    public function {$relationName}()\n    {\n        return \$this->belongsTo(\\App\\Models\\{$relatedModelName}::class, '{$column}');\n    }";
            } elseif ($field->hasMany) {
                $relatedModel = $field->hasMany;
                $relationName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural($relatedModel));
                $foreignKey = \Illuminate\Support\Str::snake($this->commandData->modelName) . '_id';
                $relationships[] = "    public function {$relationName}()\n    {\n        return \$this->hasMany(\\App\\Models\\{$relatedModel}::class, '{$foreignKey}');\n    }";
            } elseif ($field->belongsToMany) {
                $relatedModel = $field->belongsToMany;
                $relationName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural($relatedModel));
                $models = [$this->commandData->modelName, $relatedModel];
                sort($models);
                $pivotTable = strtolower(\Illuminate\Support\Str::snake($models[0])) . '_' . strtolower(\Illuminate\Support\Str::snake($models[1]));
                $relationships[] = "    public function {$relationName}()\n    {\n        return \$this->belongsToMany(\\App\\Models\\{$relatedModel}::class, '{$pivotTable}');\n    }";
            }
        }

        if (empty($relationships)) {
            return '';
        }

        return "\n" . implode("\n\n", $relationships);
    }
}
