<?php

namespace App\Generators\Generators;

abstract class RequestGenerator extends BaseGenerator
{
    protected function formatEnumRule($field): string
    {
        $enumClass = $field->enumData['class'];
        $baseRules = explode('|', $field->getValidationRules());
        $rulesArray = [];
        foreach ($baseRules as $r) {
            $rulesArray[] = "'{$r}'";
        }
        $rulesArray[] = "\Illuminate\Validation\Rule::enum(\App\Enums\\{$enumClass}::class)";
        $rulesStr = implode(', ', $rulesArray);
        return "            '{$field->name}' => [{$rulesStr}],";
    }

    protected function isIgnoredField(string $fieldName): bool
    {
        return in_array($fieldName, ['id', 'created_at', 'updated_at', 'deleted_at']);
    }
}
