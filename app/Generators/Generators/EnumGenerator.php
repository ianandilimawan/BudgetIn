<?php

namespace App\Generators\Generators;

use App\Generators\Common\CommandData;
use App\Generators\Utils\FileUtil;
use Illuminate\Support\Str;

class EnumGenerator extends BaseGenerator
{
    public function __construct(CommandData $commandData)
    {
        parent::__construct($commandData);
    }

    public function generate(): bool
    {
        $hasEnums = false;

        foreach ($this->commandData->fields as $field) {
            if ($field->enumData) {
                $hasEnums = true;
                $this->generateEnum($field);
            }
        }

        return true; // Return true to indicate successful execution, even if no enums were generated
    }

    public function rollback(): bool
    {
        $hasEnums = false;
        foreach ($this->commandData->fields as $field) {
            if ($field->enumData) {
                $hasEnums = true;
                $enumClass = $field->enumData['class'];
                $outputPath = app_path("Enums/{$enumClass}.php");
                FileUtil::delete($outputPath);
            }
        }
        return true;
    }

    private function generateEnum($field): void
    {
        $enumClass = $field->enumData['class'];
        $cases = $field->enumData['cases'];

        $path = app_path('Enums');
        $fileName = $enumClass . '.php';

        $template = FileUtil::getStubContents('enum');

        $casesStr = '';
        foreach ($cases as $case) {
            $caseName = Str::studly(str_replace(['-', ' '], '_', $case));
            $casesStr .= "    case {$caseName} = '{$case}';\n";
        }

        $template = str_replace('{{enumName}}', $enumClass, $template);
        $template = str_replace('{{cases}}', rtrim($casesStr), $template);

        FileUtil::createFile($path . '/' . $fileName, $template);
    }
}
