<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveGeneratorCommand extends Command
{
    protected $signature = 'generator:remove {--force : Skip confirmation prompt}';

    protected $description = 'Remove the generator engine, stubs, and related files. Use before handover to client.';

    /**
     * Items to remove: [path, label]
     */
    private function getRemovableItems(): array
    {
        return [
            [app_path('Generators'), 'Generator Engine (Commands, Generators, Services, Utils, Common)'],
            [resource_path('stubs'), 'Stub Templates'],
            [base_path('tests/Feature/GeneratorTest.php'), 'Generator Test'],
            [base_path('tests/generator-test'), 'Generator Test Schemas'],
        ];
    }

    public function handle(): int
    {
        $this->newLine();
        $this->components->warn('This will permanently remove the generator engine from this project.');
        $this->components->info('Generated code (Controllers, Models, Views, etc.) will NOT be affected.');
        $this->newLine();

        // Show what will be removed
        $items = $this->getRemovableItems();
        $existingItems = [];

        foreach ($items as [$path, $label]) {
            if (file_exists($path)) {
                $count = is_dir($path) ? $this->countFiles($path) : 1;
                $existingItems[] = [$path, $label, $count];
                $this->components->twoColumnDetail(
                    "<fg=red>✕</> {$label}",
                    is_dir($path) ? "{$count} files" : 'file'
                );
            }
        }

        if (empty($existingItems)) {
            $this->components->info('Generator engine already removed. Nothing to do.');
            return self::SUCCESS;
        }

        $this->newLine();

        // Confirm
        if (!$this->option('force') && !$this->components->confirm('Proceed with removal?', false)) {
            $this->components->info('Cancelled.');
            return self::SUCCESS;
        }

        // Remove items
        $totalFiles = 0;

        foreach ($existingItems as [$path, $label, $count]) {
            if (is_dir($path)) {
                File::deleteDirectory($path);
            } else {
                File::delete($path);
            }

            $totalFiles += $count;

            if (file_exists($path)) {
                $this->components->error("Failed to remove: {$label}");
            } else {
                $this->components->twoColumnDetail(
                    "<fg=green>✓</> Removed {$label}",
                    "{$count} files"
                );
            }
        }

        // Clean README-AI.md — remove generator-specific instructions
        $this->cleanReadmeAI();

        $this->newLine();
        $this->components->info("Done. Removed {$totalFiles} files total.");
        $this->components->info('This project is now a standard Laravel application.');
        $this->newLine();

        $this->components->warn('Tip: You can also delete this command after removal:');
        $this->line("  rm app/Console/Commands/RemoveGeneratorCommand.php");
        $this->newLine();

        return self::SUCCESS;
    }

    private function countFiles(string $directory): int
    {
        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() !== '.DS_Store') {
                $count++;
            }
        }

        return $count;
    }

    private function cleanReadmeAI(): void
    {
        $readmePath = base_path('README-AI.md');

        if (!file_exists($readmePath)) {
            return;
        }

        $content = file_get_contents($readmePath);

        // Remove generator-related lines
        $linesToRemove = [
            '/^.*generate:scaffold.*$/m',
            '/^.*revert:scaffold.*$/m',
            '/^.*STRICTLY PROHIBITED.*generator.*$/m',
            '/^.*scaffold generator.*$/m',
            '/^.*Advanced Generator Capabilities.*$/m',
        ];

        foreach ($linesToRemove as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }

        // Clean up multiple empty lines
        $content = preg_replace('/\n{3,}/', "\n\n", $content);

        file_put_contents($readmePath, $content);

        $this->components->twoColumnDetail(
            '<fg=green>✓</> Cleaned README-AI.md',
            'generator references removed'
        );
    }
}
