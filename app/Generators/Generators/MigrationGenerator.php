<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class MigrationGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('migration');
        $outputPath = FileUtil::getMigrationPath($this->commandData->getTableName());

        $replacements = array_merge($this->getReplacements(), [
            '{{MIGRATION_FIELDS}}' => $this->getMigrationFields(),
        ]);

        $result = $this->generateFile($template, $outputPath, $replacements);

        // Generate pivot migrations for belongsToMany fields
        foreach ($this->commandData->fields as $field) {
            if ($field->belongsToMany) {
                $this->generatePivotMigration($field->belongsToMany);
            }
        }

        return $result;
    }

    private function generatePivotMigration(string $relatedModel): void
    {
        $models = [$this->commandData->modelName, $relatedModel];
        sort($models);
        $pivotTable = strtolower(\Illuminate\Support\Str::snake($models[0])) . '_' . strtolower(\Illuminate\Support\Str::snake($models[1]));
        $model1Fk = \Illuminate\Support\Str::snake($this->commandData->modelName) . '_id';
        $model2Fk = \Illuminate\Support\Str::snake($relatedModel) . '_id';

        $timestamp = date('Y_m_d_His', time() + 1);
        $pivotPath = database_path("migrations/{$timestamp}_create_{$pivotTable}_table.php");

        if (\Illuminate\Support\Facades\File::exists($pivotPath)) {
            return;
        }

        $content = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$pivotTable}', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('{$model1Fk}')->constrained()->cascadeOnDelete();
            \$table->foreignId('{$model2Fk}')->constrained()->cascadeOnDelete();
            \$table->timestamps();

            \$table->unique(['{$model1Fk}', '{$model2Fk}']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$pivotTable}');
    }
};
";
        FileUtil::createFile($pivotPath, $content);
    }

    public function rollback(): bool
    {
        // Find and delete the migration file
        $migrationFiles = glob(database_path("migrations/*_create_{$this->commandData->getTableName()}_table.php"));

        if (!empty($migrationFiles)) {
            return FileUtil::delete($migrationFiles[0]);
        }

        return false;
    }

    private function getMigrationFields(): string
    {
        $fields = [];

        // Add ID field
        $fields[] = '            $table->id();';

        // Add timestamps
        $fields[] = '            $table->timestamps();';

        // Add soft deletes if enabled
        if ($this->commandData->withSoftDeletes) {
            $fields[] = '            $table->softDeletes();';
        }

        // Add custom fields
        foreach ($this->commandData->fields as $field) {
            if (in_array(strtolower($field->name), ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            $fields[] = '            ' . $field->getMigrationDefinition();
        }

        // Add indexes for better query performance
        $indexFields = [];
        $hasSort = false;
        $hasShow = false;
        $hasDeletedAt = false;

        // Check if sort, show, or deleted_at columns exist
        foreach ($this->commandData->fields as $field) {
            if ($field->name === 'sort') {
                $hasSort = true;
            }
            if ($field->name === 'show') {
                $hasShow = true;
            }
        }
        // deleted_at is added by softDeletes()
        $hasDeletedAt = $this->commandData->withSoftDeletes;

        // Add indexes
        if ($hasSort) {
            $indexFields[] = '            $table->index(\'sort\');';
        }
        if ($hasShow) {
            $indexFields[] = '            $table->index(\'show\');';
        }
        if ($hasDeletedAt) {
            $indexFields[] = '            $table->index(\'deleted_at\');';
        }

        // Add indexes if any
        if (!empty($indexFields)) {
            $fields[] = '';
            $fields[] = '            // Add indexes for better query performance';
            $fields = array_merge($fields, $indexFields);
        }

        return implode("\n", $fields);
    }
}
