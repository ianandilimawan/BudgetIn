<?php

namespace App\Generators\Utils;

use App\Generators\Common\GeneratorField;

class GeneratorFieldsInputUtil
{
    private static function determineHtmlType(string $dbType, string $fieldName = ''): string
    {
        $typeMap = [
            'text' => 'textarea',
            'boolean' => 'checkbox',
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'datetime',
            'integer' => 'number',
            'decimal' => 'number',
            'float' => 'number',
            'double' => 'number',
            'select' => 'select',
            'json' => 'tags',
            'file' => 'file',
            'image' => 'file',
        ];

        if (strtolower($fieldName) === 'password') {
            return 'password';
        }

        if (stripos($fieldName, 'email') !== false) {
            return 'email';
        }

        if (stripos($fieldName, 'image') !== false || stripos($fieldName, 'file') !== false || stripos($fieldName, 'photo') !== false || stripos($fieldName, 'thumbnail') !== false || stripos($fieldName, 'image_main') !== false || stripos($fieldName, 'image_thumbnail') !== false) {
            return 'file';
        }

        return $typeMap[$dbType] ?? 'text';
    }
    public static function parseFieldsFromCommand(array $fieldsInput): array
    {
        $fields = [];

        foreach ($fieldsInput as $fieldInput) {
            $fields[] = self::parseField($fieldInput);
        }

        return $fields;
    }

    public static function parseField(string $fieldInput): GeneratorField
    {
        // Format: name:dbType:htmlType:options
        // Example: name:string:text:nullable,searchable
        // Example: email:string:email:required,email
        // Example: status:string:select:options=active,inactive

        // Split by colon but handle validation rules properly
        $parts = preg_split('/:(?![^,]*,[^,]*:)/', $fieldInput);

        $name = trim($parts[0] ?? 'field');
        $dbType = trim($parts[1] ?? 'string');
        $htmlTypeRaw = isset($parts[2]) && trim($parts[2]) !== "" ? trim($parts[2]) : null;
        $optionsRaw = isset($parts[3]) ? preg_split('/,(?![^\(]*\))/', $parts[3]) : [];
        
        $htmlType = self::determineHtmlType($dbType, $name);
        $options = $optionsRaw;

        if ($htmlTypeRaw) {
            // Check if parts[2] looks like an option (e.g., contains '=', '(', or is a known option)
            if (str_contains($htmlTypeRaw, '=') || str_contains($htmlTypeRaw, '(') || in_array($htmlTypeRaw, ['nullable', 'searchable', 'sortable'])) {
                $options[] = $htmlTypeRaw; // It's an option, add it
            } else {
                $htmlType = $htmlTypeRaw; // It's actually an htmlType
            }
        }

        // Clean up options - remove empty values and trim
        $options = array_filter(array_map('trim', $options));

        return new GeneratorField($name, $dbType, $htmlType, $options);
    }

    public static function parseFieldsFromJson(string $jsonPath): array
    {
        if (!file_exists($jsonPath)) {
            throw new \Exception("Schema file not found: {$jsonPath}");
        }

        $schema = json_decode(file_get_contents($jsonPath), true);

        if (!$schema) {
            throw new \Exception("Invalid JSON schema file: {$jsonPath}");
        }

        $fields = [];

        foreach ($schema['fields'] as $fieldData) {
            $options = [];

            if (isset($fieldData['nullable']) && $fieldData['nullable']) {
                $options[] = 'nullable';
            }

            if (isset($fieldData['searchable']) && $fieldData['searchable']) {
                $options[] = 'searchable';
            }

            if (isset($fieldData['sortable']) && $fieldData['sortable']) {
                $options[] = 'sortable';
            }

            if (isset($fieldData['validation'])) {
                $options[] = 'validation:' . implode(',', $fieldData['validation']);
            }

            if (isset($fieldData['options'])) {
                $options[] = 'options:' . implode(',', $fieldData['options']);
            }

            if (isset($fieldData['default'])) {
                $options[] = 'default:' . $fieldData['default'];
            }

            if (isset($fieldData['description'])) {
                $options[] = 'description:' . $fieldData['description'];
            }

            $fields[] = new GeneratorField(
                $fieldData['name'],
                $fieldData['dbType'] ?? 'string',
                $fieldData['htmlType'] ?? 'text',
                $options
            );
        }

        return $fields;
    }
}
