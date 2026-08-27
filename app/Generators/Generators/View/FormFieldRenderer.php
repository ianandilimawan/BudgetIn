<?php

namespace App\Generators\Generators\View;

use App\Generators\Common\CommandData;

class FormFieldRenderer
{
    protected CommandData $commandData;
    protected ComponentRenderer $componentRenderer;

    public function __construct(CommandData $commandData, ComponentRenderer $componentRenderer)
    {
        $this->commandData = $commandData;
        $this->componentRenderer = $componentRenderer;
    }

    public function getFormFields(): string
    {
        $fields = [];
        $scriptsAndStyles = [];
        $hiddenFields = [];

        // Separate timestamp fields from regular fields
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        // Process fields in the same order as they appear in the input (matching migration order)
        foreach ($this->commandData->fields as $field) {
            // Check if this is a timestamp field
            if (in_array($field->name, $timestampFields)) {
                // Add as hidden field instead of regular input
                $modelVar = $this->commandData->modelNameCamel;
                $hiddenFields[] = "<input type=\"hidden\" name=\"{$field->name}\" value=\"{{\$" . $modelVar . "->{$field->name} ?? ''}}\">";
            } else {
                // Regular field - add as visible form input (maintain order from input)
                $fieldInput = $this->getFormInputForField($field, $scriptsAndStyles);

                $fields[] = $fieldInput;
            }
        }

        // Add hidden fields at the beginning (to match migration structure: ID, timestamps, softDeletes, then custom fields)
        if (!empty($hiddenFields)) {
            // Reverse hiddenFields to maintain correct order when using array_unshift
            $hiddenFields = array_reverse($hiddenFields);
            $fields = array_merge(
                ["<!-- Hidden timestamp fields -->"],
                $hiddenFields,
                $fields
            );
        }

        // Check if title/name and slug fields exist for auto-generate slug feature
        $hasTitleField = false;
        $hasNameField = false;
        $hasSlugField = false;
        $slugSourceField = null; // 'title' or 'name'

        foreach ($this->commandData->fields as $field) {
            if ($field->name === 'title') {
                $hasTitleField = true;
                if (!$slugSourceField) {
                    $slugSourceField = 'title';
                }
            }
            if ($field->name === 'name') {
                $hasNameField = true;
                if (!$slugSourceField) {
                    $slugSourceField = 'name';
                }
            }
            if ($field->name === 'slug') {
                $hasSlugField = true;
            }
        }

        // Prefer title over name if both exist
        if ($hasTitleField && $hasNameField) {
            $slugSourceField = 'title';
        }

        // Check if there are currency fields
        $hasCurrencyFields = false;
        $hasPasswordFields = false;
        foreach ($scriptsAndStyles as $item) {
            if (isset($item['type']) && $item['type'] === 'currency') {
                $hasCurrencyFields = true;
            }
            if (isset($item['type']) && $item['type'] === 'password') {
                $hasPasswordFields = true;
            }
        }

        // Add scripts and styles at the bottom
        // Always add scripts if there are fields that need them OR if auto-generate slug is needed OR if there are currency fields OR password fields
        $hasSlugAutoGenerate = ($hasTitleField || $hasNameField) && $hasSlugField;
        if (!empty($scriptsAndStyles) || $hasSlugAutoGenerate || $hasCurrencyFields || $hasPasswordFields) {
            $fields[] = "";
            $fields[] = "@push('scripts')";
            $fields[] = "    @include('admin.partials.form-styles')";

            // Prepare data for form-scripts partial
            $tagifyFields = [];
            $textareaFields = [];
            $selectFields = [];
            $currencyFields = [];
            $passwordFields = [];

            foreach ($scriptsAndStyles as $item) {
                if ($item['type'] === 'textarea') {
                    $textareaFields[] = $item['id'];
                } elseif ($item['type'] === 'select') {
                    $selectFields[] = $item['id'];
                } elseif ($item['type'] === 'tags') {
                    $tagifyFields[] = $item['id'];
                } elseif ($item['type'] === 'currency') {
                    $currencyFields[] = $item['id'];
                } elseif ($item['type'] === 'password') {
                    $passwordFields[] = $item['id'];
                }
            }

            $fields[] = "    @php";
            $fields[] = "        \$hasTitleField = " . ($hasTitleField ? 'true' : 'false') . ";";
            $fields[] = "        \$hasNameField = " . ($hasNameField ? 'true' : 'false') . ";";
            $fields[] = "        \$hasSlugField = " . ($hasSlugField ? 'true' : 'false') . ";";
            $fields[] = "        \$slugSourceField = " . ($slugSourceField ? "'{$slugSourceField}'" : 'null') . ";";
            $fields[] = "        \$tagifyFields = " . var_export($tagifyFields, true) . ";";
            $fields[] = "        \$textareaFields = " . var_export($textareaFields, true) . ";";
            $fields[] = "        \$selectFields = " . var_export($selectFields, true) . ";";
            $fields[] = "        \$currencyFields = " . var_export($currencyFields, true) . ";";
            $fields[] = "        \$passwordFields = " . var_export($passwordFields, true) . ";";
            $fields[] = "    @endphp";
            $fields[] = "    @include('admin.partials.form-scripts')";
            $fields[] = "@endpush";
        }

        return implode("\n", $fields);
    }

    public function getFormInputs(): string
    {
        return $this->getFormFields();
    }

    public function getFormInputForField($field, &$scriptsAndStyles = []): string
    {
        $fieldName = $field->name;
        $cleanName = preg_replace('/_id$/i', '', $fieldName);
        $fieldLabel = ucwords(str_replace('_', ' ', $cleanName));
        $modelVar = $this->commandData->modelNameCamel;

        if ($field->enumData) {
            $enumClass = $field->enumData['class'];
            $scriptsAndStyles[] = ['type' => 'select', 'id' => $fieldName];
            $optionsCode = "collect(\App\Enums\\{$enumClass}::cases())->mapWithKeys(fn(\$enum) => [\$enum->value => ucfirst(\$enum->value)])->toArray()";
            return \App\Generators\Utils\FileUtil::replaceStub('fields/select', [
                '{{ name }}' => $fieldName,
                '{{ label }}' => $fieldLabel,
                '{{ value }}' => "{{ \${$modelVar}->{$fieldName} ?? '' }}",
                '{{ options }}' => $optionsCode
            ]);
        }

        if ($field->belongsTo || $field->foreignKey) {
            $scriptsAndStyles[] = ['type' => 'select', 'id' => $fieldName];

            if ($field->belongsTo) {
                $relatedModel = $field->belongsTo;
            } else {
                $referencedTable = $field->foreignKey['referenced_table'];
                $relatedModel = \Illuminate\Support\Str::studly(\Illuminate\Support\Str::singular($referencedTable));
            }
            $varName = \Illuminate\Support\Str::camel(\Illuminate\Support\Str::plural($relatedModel));

            return \App\Generators\Utils\FileUtil::replaceStub('fields/select', [
                '{{ name }}' => $fieldName,
                '{{ label }}' => $fieldLabel,
                '{{ value }}' => "{{ \${$modelVar}->{$fieldName} ?? '' }}",
                '{{ options }}' => "\${$varName}"
            ]);
        }

        switch ($field->htmlType) {
            case 'textarea':
                $scriptsAndStyles[] = ['type' => 'textarea', 'id' => $fieldName];
                return \App\Generators\Utils\FileUtil::replaceStub('fields/textarea', [
                    '{{ name }}' => $fieldName,
                    '{{ label }}' => $fieldLabel,
                    '{{ value }}' => "\${$modelVar}->{$fieldName} ?? ''"
                ]);
            case 'select':
                $scriptsAndStyles[] = ['type' => 'select', 'id' => $fieldName];

                $optionsArrayString = "[";
                if (!empty($field->htmlInputs)) {
                    foreach ($field->htmlInputs as $option) {
                        $optionsArrayString .= "'{$option}' => '" . ucfirst($option) . "', ";
                    }
                }
                $optionsArrayString .= "]";

                return \App\Generators\Utils\FileUtil::replaceStub('fields/select', [
                    '{{ name }}' => $fieldName,
                    '{{ label }}' => $fieldLabel,
                    '{{ value }}' => "{{ \${$modelVar}->{$fieldName} ?? '' }}",
                    '{{ options }}' => $optionsArrayString
                ]);

            case 'checkbox':
                return \App\Generators\Utils\FileUtil::replaceStub('fields/checkbox', [
                    '{{ name }}' => $fieldName,
                    '{{ label }}' => $fieldLabel,
                    '{{ checked }}' => "\${$modelVar}->{$fieldName} ?? false"
                ]);

            case 'password':
                $scriptsAndStyles[] = ['type' => 'password', 'id' => $fieldName];
                return $this->componentRenderer->getPasswordFieldWithStrength($field, $fieldLabel);

            case 'file':
                return $this->componentRenderer->getDropifyComponent($field, $fieldLabel);

            case 'tags':
                $scriptsAndStyles[] = ['type' => 'tags', 'id' => $fieldName];
                return $this->componentRenderer->getTagifyComponent($field, $fieldLabel);

            case 'date':
            case 'email':
            case 'number':
            case 'currency':
            default:
                if (stripos($fieldName, 'keyword') !== false) {
                    $scriptsAndStyles[] = ['type' => 'tags', 'id' => $fieldName];
                    return $this->componentRenderer->getTagifyComponent($field, $fieldLabel);
                }

                $type = 'text';
                if ($field->htmlType === 'email') $type = 'email';
                if ($field->htmlType === 'date') $type = 'date';
                if ($field->htmlType === 'number' && !$this->isCurrencyField($field)) $type = 'number';

                $isCurrencyStr = $this->isCurrencyField($field) ? ' :isCurrency="true"' : '';
                if ($this->isCurrencyField($field)) {
                    $scriptsAndStyles[] = ['type' => 'currency', 'id' => $fieldName];
                }

                $valueExpression = "{{ \${$modelVar}->{$fieldName} ?? '' }}";
                if ($type === 'date') {
                    $valueExpression = "{{ isset(\${$modelVar}->{$fieldName}) && \${$modelVar}->{$fieldName} ? (is_string(\${$modelVar}->{$fieldName}) ? \${$modelVar}->{$fieldName} : \${$modelVar}->{$fieldName}->format('Y-m-d')) : '' }}";
                }

                if (in_array($field->htmlType, ['datetime', 'timestamp'])) {
                    return \App\Generators\Utils\FileUtil::replaceStub('fields/datetime', [
                        '{{ name }}' => $fieldName,
                        '{{ label }}' => $fieldLabel,
                        '{{ value }}' => "{{ \${$modelVar}->{$fieldName} ?? '' }}"
                    ]);
                }

                return \App\Generators\Utils\FileUtil::replaceStub('fields/text', [
                    '{{ type }}' => $type,
                    '{{ name }}' => $fieldName,
                    '{{ label }}' => $fieldLabel,
                    '{{ value }}' => $valueExpression,
                    '{{ isCurrency }}' => $isCurrencyStr
                ]);
        }
    }

    public function isCurrencyField($field): bool
    {
        if ($field->htmlType === 'currency') {
            return true;
        }
        $currencyKeywords = ['price', 'cost', 'amount', 'currency', 'fee', 'charge', 'total', 'sum', 'balance', 'payment', 'sale_price', 'discount', 'tax', 'salary', 'revenue', 'budget', 'rp'];
        $fieldNameLower = strtolower($field->name);
        foreach ($currencyKeywords as $keyword) {
            if (str_contains($fieldNameLower, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Format input field HTML with consistent line breaks and indentation
     */
    public function formatInputField(string $input, int $baseIndent): string
    {
        if (strpos($input, "\n") !== false) {
            $lines = explode("\n", $input);
            $formatted = [];
            foreach ($lines as $line) {
                $trimmed = rtrim($line);
                if ($trimmed === '') {
                    $formatted[] = "";
                    continue;
                }
                preg_match('/^(\s*)(.*)$/', $line, $matches);
                $originalIndent = strlen($matches[1] ?? '');
                $content = $matches[2] ?? $trimmed;

                $indentDiff = max(0, $originalIndent - $baseIndent);
                $newIndent = $baseIndent + $indentDiff;

                $formatted[] = str_repeat(" ", $newIndent) . $content;
            }
            return implode("\n", $formatted);
        }

        return $input;
    }
}
