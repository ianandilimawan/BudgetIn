<?php

namespace App\Generators\Services;

use Illuminate\Console\Command;
use App\Generators\Common\GeneratorField;
use App\Generators\Utils\GeneratorFieldsInputUtil;

class FieldParser
{
    protected Command $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function collectFieldsInteractively(): array
    {
        $fields = [];
        $fieldNumber = 1;

        $this->command->info('Enter fields one by one. Press Enter with empty input to finish.');
        $this->command->info('You can also press Enter immediately to generate CRUD with basic structure (id, timestamps only).');
        $this->command->line('');

        while (true) {
            $fieldInput = $this->command->ask("Field {$fieldNumber} (or press Enter to finish/continue without fields):");

            if (empty(trim($fieldInput))) {
                break;
            }

            try {
                $field = GeneratorFieldsInputUtil::parseField($fieldInput);
                $fields[] = $field;
                $this->command->info("✓ Added field: {$field->name} ({$field->dbType}, {$field->htmlType})");
                $fieldNumber++;
            } catch (\Exception $e) {
                $this->command->error("Invalid field format: " . $e->getMessage());
                $this->command->warn("Please use format: name:dbType:htmlType:options");
            }
        }

        return $fields;
    }

    public function convertColumnsToFields(array $columns, array $foreignKeys = []): array
    {
        $fields = [];
        $foreignKeyMap = [];

        foreach ($foreignKeys as $fk) {
            $foreignKeyMap[$fk['column']] = $fk;
        }

        foreach ($columns as $column) {
            $htmlType = $this->determineHtmlType($column['type'], $column['name']);
            $options = [];

            if ($column['nullable']) {
                $options[] = 'nullable';
            }

            if (isset($column['column_type']) && strpos($column['column_type'], 'enum') !== false) {
                $htmlType = 'select';
                $enumValues = $this->parseEnumValues($column['column_type']);
                if (!empty($enumValues)) {
                    $options[] = 'options:' . implode(',', $enumValues);
                }
            }

            if (isset($foreignKeyMap[$column['name']])) {
                $options[] = 'foreignKey:' . $foreignKeyMap[$column['name']]['referenced_table'];
            }

            $field = new GeneratorField(
                $column['name'],
                $column['type'],
                $htmlType,
                $options
            );

            if (isset($foreignKeyMap[$column['name']])) {
                $field->foreignKey = $foreignKeyMap[$column['name']];
            }

            $fields[] = $field;
        }

        return $fields;
    }

    private function determineHtmlType(string $dbType, string $fieldName = ''): string
    {
        $typeMap = [
            'text' => 'textarea',
            'boolean' => 'checkbox',
            'date' => 'date',
            'datetime' => 'datetime-local',
            'timestamp' => 'datetime-local',
            'integer' => 'number',
            'decimal' => 'number',
            'float' => 'number',
            'double' => 'number',
            'select' => 'select',
            'json' => 'tags',
        ];

        if (strtolower($fieldName) === 'password') {
            return 'password';
        }

        if (strtolower($fieldName) === 'email') {
            return 'email';
        }

        if (
            str_contains(strtolower($fieldName), 'image') ||
            str_contains(strtolower($fieldName), 'file') ||
            str_contains(strtolower($fieldName), 'photo') ||
            str_contains(strtolower($fieldName), 'banner') ||
            str_contains(strtolower($fieldName), 'icon') ||
            str_contains(strtolower($fieldName), 'thumbnail')
        ) {
            return 'file';
        }

        return $typeMap[$dbType] ?? 'text';
    }

    private function parseEnumValues(string $columnType): array
    {
        if (preg_match("/enum\s*\(\s*'([^']+)'(?:\s*,\s*'([^']+)')*\)/i", $columnType, $matches)) {
            preg_match_all("/'([^']+)'/", $columnType, $allMatches);
            return $allMatches[1];
        }

        return [];
    }
}
