<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class PolicyGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('policy');
        $outputPath = app_path("Policies/{$this->commandData->modelName}Policy.php");

        $softDeleteMethods = '';
        if ($this->commandData->withSoftDeletes) {
            $modelName = $this->commandData->modelName;
            $modelCamel = $this->commandData->modelNameCamel;
            $kebabPlural = $this->commandData->modelNameKebabPlural;
            $softDeleteMethods = "
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User \$user, {$modelName} \${$modelCamel}): bool
    {
        return \$user->hasPermissionTo('delete-{$kebabPlural}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User \$user, {$modelName} \${$modelCamel}): bool
    {
        return \$user->hasPermissionTo('delete-{$kebabPlural}');
    }";
        }

        $replacements = array_merge($this->getReplacements(), [
            '{{SOFT_DELETE_METHODS}}' => $softDeleteMethods,
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        $outputPath = app_path("Policies/{$this->commandData->modelName}Policy.php");
        if (file_exists($outputPath)) {
            return FileUtil::delete($outputPath);
        }
        return true;
    }
}
