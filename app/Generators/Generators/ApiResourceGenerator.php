<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class ApiResourceGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        // Only generate if --api flag is set
        if (!$this->commandData->isApi) {
            return true;
        }

        $template = FileUtil::getStubContents('resource');
        $outputPath = FileUtil::getResourcePath($this->commandData->modelName);

        $replacements = array_merge($this->getReplacements(), [
            '{{RESOURCE_FIELDS}}' => $this->getResourceFields(),
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        $outputPath = FileUtil::getResourcePath($this->commandData->modelName);
        if (file_exists($outputPath)) {
            return FileUtil::delete($outputPath);
        }
        return true;
    }

    private function getResourceFields(): string
    {
        $skipFields = ['id', 'created_at', 'updated_at', 'deleted_at', 'password', 'remember_token', 'api_token', 'secret'];
        $lines = [];

        foreach ($this->commandData->fields as $field) {
            if (in_array($field->name, $skipFields)) {
                continue;
            }

            $lines[] = "            '{$field->name}' => \$this->{$field->name},";

            // Add whenLoaded for belongsTo relationships
            if ($field->belongsTo) {
                $relationName = preg_replace('/_id$/', '', $field->name);
                $relationName = \Illuminate\Support\Str::camel($relationName);
                $lines[] = "            '{$relationName}' => \$this->whenLoaded('{$relationName}'),";
            }
        }

        return implode("\n", $lines);
    }
}
