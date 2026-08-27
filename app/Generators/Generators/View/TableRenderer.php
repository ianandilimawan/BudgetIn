<?php

namespace App\Generators\Generators\View;

use App\Generators\Common\CommandData;

class TableRenderer
{
    protected CommandData $commandData;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
    }

    public function getTableHeaders(): string
    {
        $headers = [];
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        // Always add ID column first
        $headers[] = "                            <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider\">ID</th>";

        foreach ($this->commandData->fields as $field) {
            // Skip timestamp fields from table headers
            if (in_array($field->name, $timestampFields)) {
                continue;
            }

            if ($field->sortable) {
                $headers[] = "                            <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600\">" .
                    ucfirst(str_replace('_', ' ', $field->name)) . "</th>";
            } else {
                $tableHeader = $field->getTableHeader();
                // Update to support dark mode
                $tableHeader = str_replace(
                    'class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"',
                    'class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"',
                    $tableHeader
                );
                $headers[] = "                            " . $tableHeader;
            }
        }

        return implode("\n", $headers);
    }

    public function getTableCells(): string
    {
        $cells = [];
        $modelVar = $this->commandData->modelNameCamel;
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        // Always add ID column first
        $cells[] = "                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white\">{{\$" . $modelVar . "->id}}</td>";

        foreach ($this->commandData->fields as $field) {
            // Skip timestamp fields from table cells
            if (in_array($field->name, $timestampFields)) {
                continue;
            }

            // Handle Tagify fields (tags) - parse JSON and display comma-separated values
            // Also handle fields with 'keyword' in name (like meta_keywords)
            if ($field->htmlType === 'tags' || stripos($field->name, 'keyword') !== false) {
                $tableCell = "<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white\">";
                $tableCell .= "@php\n";
                $tableCell .= "                                            \$keywords = \$" . $modelVar . "->{$field->name} ?? null;\n";
                $tableCell .= "                                            if (\$keywords) {\n";
                $tableCell .= "                                                \$decoded = json_decode(\$keywords, true);\n";
                $tableCell .= "                                                if (is_array(\$decoded) && !empty(\$decoded)) {\n";
                $tableCell .= "                                                    \$values = [];\n";
                $tableCell .= "                                                    foreach (\$decoded as \$item) {\n";
                $tableCell .= "                                                        if (is_array(\$item) && isset(\$item['value'])) {\n";
                $tableCell .= "                                                            \$values[] = \$item['value'];\n";
                $tableCell .= "                                                        } elseif (is_string(\$item)) {\n";
                $tableCell .= "                                                            \$values[] = \$item;\n";
                $tableCell .= "                                                        }\n";
                $tableCell .= "                                                    }\n";
                $tableCell .= "                                                    echo e(implode(', ', \$values));\n";
                $tableCell .= "                                                } else {\n";
                $tableCell .= "                                                    echo e(\$keywords);\n";
                $tableCell .= "                                                }\n";
                $tableCell .= "                                            }\n";
                $tableCell .= "                                        @endphp";
                $tableCell .= "</td>";
            }
            // Handle TinyMCE fields (textarea) - strip HTML and limit to 100 chars
            elseif ($field->htmlType === 'textarea') {
                $tableCell = "<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white\">";
                $tableCell .= "@if(\$" . $modelVar . "->{$field->name})\n";
                $tableCell .= "                                        @php\n";
                $tableCell .= "                                            \$text = strip_tags(\$" . $modelVar . "->{$field->name});\n";
                $tableCell .= "                                            \$text = mb_strlen(\$text) > 100 ? mb_substr(\$text, 0, 100) . '...' : \$text;\n";
                $tableCell .= "                                            echo e(\$text);\n";
                $tableCell .= "                                        @endphp\n";
                $tableCell .= "                                    @endif";
                $tableCell .= "</td>";
            }
            // Handle boolean/checkbox fields - display as True/False pills
            elseif ($field->htmlType === 'checkbox' || $field->dbType === 'boolean') {
                $tableCell = "<td class=\"px-6 py-4 whitespace-nowrap text-sm text-center\">";
                $tableCell .= "@if(\$" . $modelVar . "->{$field->name})\n";
                $tableCell .= "                                        <span class=\"px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200\">True</span>\n";
                $tableCell .= "                                    @else\n";
                $tableCell .= "                                        <span class=\"px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200\">False</span>\n";
                $tableCell .= "                                    @endif";
                $tableCell .= "</td>";
            }
            // Handle image/file fields - display as image
            elseif (in_array($field->htmlType, ['file', 'image'])) {
                $tableCell = "<td class=\"px-6 py-4 whitespace-nowrap text-sm text-center\">";
                $tableCell .= "@if(\$" . $modelVar . "->{$field->name})\n";
                $tableCell .= "                                        @php\n";
                $tableCell .= "                                            \$fileUrl = \App\Services\FileUploadService::getFileUrl(\$" . $modelVar . "->{$field->name});\n";
                $tableCell .= "                                        @endphp\n";
                $tableCell .= "                                        @if(\$fileUrl)\n";
                $tableCell .= "                                            <img src=\"{{ \$fileUrl }}\" alt=\"{{\$" . $modelVar . "->{$field->name}}}\" class=\"h-12 w-12 object-cover rounded\" onerror=\"this.style.display='none'\">\n";
                $tableCell .= "                                        @endif\n";
                $tableCell .= "                                    @endif";
                $tableCell .= "</td>";
            }
            // Default behavior for other fields
            else {
                $tableCell = $field->getTableCell();
                // Replace $item with the correct model variable name
                $tableCell = str_replace('$item', '$' . $modelVar, $tableCell);
                // Update to support dark mode
                $tableCell = str_replace(
                    'class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"',
                    'class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"',
                    $tableCell
                );
            }

            $cells[] = "                                " . $tableCell;
        }

        return implode("\n", $cells);
    }

    public function getShowFields(): string
    {
        $fields = [];
        $modelVar = $this->commandData->modelNameCamel;
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            // Skip timestamp fields since they're hardcoded at the bottom
            if (in_array($field->name, $timestampFields)) {
                continue;
            }

            $fieldLabel = ucfirst(str_replace('_', ' ', $field->name));

            $fields[] = "                    <div class=\"lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors\">";
            $fields[] = "                        <dt class=\"lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400\">";
            $fields[] = "                            {$fieldLabel}";
            $fields[] = "                        </dt>";
            $fields[] = "                        <dd class=\"mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium\">";

            // Logic to render field value based on type
            if ($field->htmlType === 'tags' || stripos($field->name, 'keyword') !== false) {
                $fields[] = "                        @php";
                $fields[] = "                            \$keywords = \${$modelVar}->{$field->name} ?? null;";
                $fields[] = "                            if (\$keywords) {";
                $fields[] = "                                \$decoded = json_decode(\$keywords, true);";
                $fields[] = "                                if (is_array(\$decoded) && !empty(\$decoded)) {";
                $fields[] = "                                    \$values = [];";
                $fields[] = "                                    foreach (\$decoded as \$item) {";
                $fields[] = "                                        if (is_array(\$item) && isset(\$item['value'])) {";
                $fields[] = "                                            \$values[] = \$item['value'];";
                $fields[] = "                                        } elseif (is_string(\$item)) {";
                $fields[] = "                                            \$values[] = \$item;";
                $fields[] = "                                        }";
                $fields[] = "                                    }";
                $fields[] = "                                    echo implode(', ', \$values);";
                $fields[] = "                                } else {";
                $fields[] = "                                    echo \$keywords;";
                $fields[] = "                                }";
                $fields[] = "                            } else {";
                $fields[] = "                                echo 'N/A';";
                $fields[] = "                            }";
                $fields[] = "                        @endphp";
            } elseif ($field->htmlType === 'checkbox' || $field->dbType === 'boolean') {
                $fields[] = "                        @if(\${$modelVar}->{$field->name})";
                $fields[] = "                            <span class=\"px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200\">True</span>";
                $fields[] = "                        @else";
                $fields[] = "                            <span class=\"px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200\">False</span>";
                $fields[] = "                        @endif";
            } elseif (in_array($field->htmlType, ['file', 'image'])) {
                $fields[] = "                        @if(\${$modelVar}->{$field->name})";
                $fields[] = "                            @php";
                $fields[] = "                                \$fileUrl = \App\Services\FileUploadService::getFileUrl(\${$modelVar}->{$field->name});";
                $fields[] = "                            @endphp";
                $fields[] = "                            @if(\$fileUrl)";
                $fields[] = "                                <div class=\"flex items-center justify-center w-full py-4\">";
                $fields[] = "                                    <img src=\"{{ \$fileUrl }}\" alt=\"{$fieldLabel}\" class=\"max-w-full max-h-96 object-contain mx-auto rounded-lg shadow-md\">";
                $fields[] = "                                </div>";
                $fields[] = "                            @else";
                $fields[] = "                                N/A";
                $fields[] = "                            @endif";
                $fields[] = "                        @else";
                $fields[] = "                            N/A";
                $fields[] = "                        @endif";
            } else {
                $fields[] = "                        {{ \${$modelVar}->{$field->name} ?? 'N/A' }}";
            }

            $fields[] = "                        </dd>";
            $fields[] = "                    </div>";
        }

        return implode("\n", $fields);
    }

    public function getFieldCount(): int
    {
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        $fieldCount = 0;

        // Count ID column
        $fieldCount += 1;

        // Count non-timestamp fields
        foreach ($this->commandData->fields as $field) {
            if (!in_array($field->name, $timestampFields)) {
                $fieldCount += 1;
            }
        }

        // Count Actions column
        $fieldCount += 1;

        return $fieldCount;
    }
}
