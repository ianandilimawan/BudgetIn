<?php

namespace App\Generators\Generators\View;

use App\Generators\Common\CommandData;

class ImportExportRenderer
{
    protected CommandData $commandData;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
    }

    public function getCsvSample(): string
    {
        $headers = [];
        $sampleRow = [];
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            // Skip timestamp fields from CSV sample
            if (in_array($field->name, $timestampFields)) {
                continue;
            }

            if ($field->fillable) {
                $headers[] = $field->name;
                // Generate sample value based on field type
                switch ($field->dbType) {
                    case 'integer':
                        $sampleRow[] = '123';
                        break;
                    case 'bigint':
                        $sampleRow[] = '1';
                        break;
                    case 'boolean':
                        $sampleRow[] = '1';
                        break;
                    case 'date':
                        $sampleRow[] = '2024-01-01';
                        break;
                    case 'datetime':
                        $sampleRow[] = '2024-01-01 12:00:00';
                        break;
                    case 'timestamp':
                        $sampleRow[] = '2024-01-01 12:00:00';
                        break;
                    case 'decimal':
                        $sampleRow[] = '100.00';
                        break;
                    case 'float':
                        $sampleRow[] = '100.00';
                        break;
                    case 'double':
                        $sampleRow[] = '100.00';
                        break;
                    case 'enum':
                        if (!empty($field->htmlInputs)) {
                            $sampleRow[] = $field->htmlInputs[0];
                        } elseif (!empty($field->enumData['cases'])) {
                            $sampleRow[] = $field->enumData['cases'][0];
                        } else {
                            $sampleRow[] = 'active';
                        }
                        break;
                    default:
                        $sampleRow[] = 'Sample Value';
                        break;
                }
            }
        }

        $csv = implode(',', $headers) . "\n";
        $csv .= implode(',', $sampleRow);

        return $csv;
    }

    /**
     * Format array for Blade template with proper indentation
     */
    public function formatArrayForBlade(array $array): string
    {
        if (empty($array)) {
            return "[]";
        }

        $lines = ["["];
        $lastIndex = count($array) - 1;
        $index = 0;

        foreach ($array as $key => $value) {
            $comma = $index < $lastIndex ? ',' : '';
            $lines[] = "                            '{$value}'{$comma}";
            $index++;
        }

        $lines[] = "                        ]";

        return implode("\n", $lines);
    }
}
