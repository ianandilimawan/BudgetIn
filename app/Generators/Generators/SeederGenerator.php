<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class SeederGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('seeder');
        $outputPath = FileUtil::getSeederPath($this->commandData->seederName);

        $replacements = $this->getReplacements();

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        $outputPath = FileUtil::getSeederPath($this->commandData->seederName);
        return FileUtil::delete($outputPath);
    }
}
