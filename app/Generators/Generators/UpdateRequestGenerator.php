<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class UpdateRequestGenerator extends RequestGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('request/update');
        $outputPath = FileUtil::getRequestPath($this->commandData->updateRequestName);

        $replacements = array_merge($this->getReplacements(), [
            '{{VALIDATION_RULES}}' => $this->getValidationRules(),
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        return FileUtil::delete(FileUtil::getRequestPath($this->commandData->updateRequestName));
    }

    private function getValidationRules(): string
    {
        $rules = [];

        foreach ($this->commandData->fields as $field) {
            if ($this->isIgnoredField($field->name)) {
                continue;
            }

            if ($field->enumData) {
                $rules[] = $this->formatEnumRule($field);
            } else {
                $rule = $field->getValidationRules();

                // For unique validation on update, we need to ignore the current model
                // Note: This is a basic implementation. For complex unique rules, manual adjustment might be needed.
                if (str_contains($rule, 'unique:')) {
                    // Extract table name from unique:table_name
                    preg_match('/unique:([a-zA-Z0-9_]+)/', $rule, $matches);
                    if (isset($matches[1])) {
                        $tableName = $matches[1];
                        // Replace string rule with array rule for unique
                        $rules[] = "            '{$field->name}' => [" . 
                            implode(', ', array_map(function($r) use ($tableName) {
                                if (str_starts_with($r, 'unique:')) {
                                    return "Rule::unique('{$tableName}')->ignore(\$this->route('{$this->commandData->modelNameSnake}'))";
                                }
                                return "'{$r}'";
                            }, explode('|', $rule))) . "],";
                        continue;
                    }
                }
                
                // For update requests, make rules more flexible (nullable unless required)
                if (!$field->nullable && !str_contains($rule, 'required')) {
                    $rule = 'nullable|' . $rule;
                }
                $rules[] = "            '{$field->name}' => '{$rule}',";
            }
        }

        return implode("\n", $rules);
    }
}
