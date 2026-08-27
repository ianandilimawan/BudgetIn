<?php

namespace App\Generators\Generators\View;

use App\Generators\Common\CommandData;
use App\Generators\Utils\FileUtil;

class ComponentRenderer
{
    protected CommandData $commandData;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
    }

    public function getFileUploadComponent($field, string $fieldLabel): string
    {
        $fieldName = $field->name;
        return <<<HTML
        <x-filepond name="{$fieldName}" label="{$fieldLabel}" :defaultFile="isset(\$fileUrls['{$fieldName}']) ? \$fileUrls['{$fieldName}'] : null" />
HTML;
    }

    public function getDropifyComponent($field, string $fieldLabel): string
    {
        return $this->getFileUploadComponent($field, $fieldLabel);
    }

    public function getTagifyComponent($field, string $fieldLabel): string
    {
        $fieldName = $field->name;
        $modelVar = $this->commandData->modelNameCamel;
        $html = <<<HTML
        <div>
            <label for="{$fieldName}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{$fieldLabel}</label>
            <input name="{$fieldName}" id="{$fieldName}" value="{{ \${$modelVar}->{$fieldName} ?? '' }}" placeholder="Add tags..." class="block w-full rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm dark:shadow-md focus:border-blue-500 focus:ring-2 focus:ring-blue-500 px-4 py-4 transition-colors items-center">
            @error('{$fieldName}')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ \$message }}</p>
            @enderror
        </div>
HTML;
        try {
            $scriptTemplate = FileUtil::getStubContents('js/tagify-init.js');
            $scriptTemplate = str_replace('{{fieldName}}', $fieldName, $scriptTemplate);
            return $html . "\n@push('scripts')\n<script>\n" . $scriptTemplate . "\n</script>\n@endpush";
        } catch (\Exception $e) {
            return $html;
        }
    }

    public function getPasswordFieldWithStrength($field, string $fieldLabel): string
    {
        $fieldName = $field->name;
        $strengthId = $fieldName . 'Strength';
        $strengthBar1 = 'strengthBar1' . ucfirst($fieldName);
        $strengthBar2 = 'strengthBar2' . ucfirst($fieldName);
        $strengthBar3 = 'strengthBar3' . ucfirst($fieldName);
        $strengthBar4 = 'strengthBar4' . ucfirst($fieldName);
        $strengthText = 'strengthText' . ucfirst($fieldName);

        $html = <<<HTML
        <div>
            <x-input-floating type="password" name="{$fieldName}" label="{$fieldLabel}" />
            <div id="{$strengthId}" class="mt-2 hidden">
                <div class="flex gap-2 mb-2">
                    <div id="{$strengthBar1}" class="h-2 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 transition-colors"></div>
                    <div id="{$strengthBar2}" class="h-2 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 transition-colors"></div>
                    <div id="{$strengthBar3}" class="h-2 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 transition-colors"></div>
                    <div id="{$strengthBar4}" class="h-2 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 transition-colors"></div>
                </div>
                <p id="{$strengthText}" class="text-xs font-medium"></p>
            </div>
        </div>
HTML;

        try {
            $scriptTemplate = FileUtil::getStubContents('js/password-strength.js');
            $scriptTemplate = str_replace(
                ['{{fieldName}}', '{{strengthId}}', '{{strengthBar1}}', '{{strengthBar2}}', '{{strengthBar3}}', '{{strengthBar4}}', '{{strengthText}}'],
                [$fieldName, $strengthId, $strengthBar1, $strengthBar2, $strengthBar3, $strengthBar4, $strengthText],
                $scriptTemplate
            );
            return $html . "\n@push('scripts')\n<script>\n" . $scriptTemplate . "\n</script>\n@endpush";
        } catch (\Exception $e) {
            return $html; // Fallback to just HTML if stub not found
        }
    }
}
