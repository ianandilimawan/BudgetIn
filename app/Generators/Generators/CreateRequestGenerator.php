<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class CreateRequestGenerator extends RequestGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('request/create');
        $outputPath = FileUtil::getRequestPath($this->commandData->createRequestName);

        $replacements = array_merge($this->getReplacements(), [
            '{{VALIDATION_RULES}}' => $this->getValidationRules(),
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        return FileUtil::delete(FileUtil::getRequestPath($this->commandData->createRequestName));
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
                $rules[] = "            '{$field->name}' => '{$rule}',";
            }
        }

        return implode("\n", $rules);
    }
}
