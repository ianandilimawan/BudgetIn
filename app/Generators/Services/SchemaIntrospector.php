<?php

namespace App\Generators\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaIntrospector
{
    public function getColumns(string $tableName): array
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tableName)) {
            throw new \InvalidArgumentException("Invalid table name: {$tableName}");
        }

        $columns = [];

        try {
            $driver = config('database.default');
            $connection = DB::connection($driver);

            if ($connection->getDriverName() === 'mysql') {
                $columns = $this->getMySQLColumns($connection, $tableName);
            } elseif ($connection->getDriverName() === 'pgsql') {
                $columns = $this->getPostgreSQLColumns($connection, $tableName);
            } elseif ($connection->getDriverName() === 'sqlite') {
                $columns = $this->getSQLiteColumns($connection, $tableName);
            }
        } catch (\Exception $e) {
            \Log::warning("Could not get columns via introspection: " . $e->getMessage());

            // Fallback to Laravel's schema
            $columns = Schema::getColumnListing($tableName);
            $columns = array_map(function ($col) use ($tableName) {
                return [
                    'name' => $col,
                    'type' => $this->getColumnType($tableName, $col),
                    'nullable' => false,
                    'default' => null,
                ];
            }, $columns);
        }

        return $columns;
    }

    private function getMySQLColumns($connection, string $tableName): array
    {
        $query = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_TYPE, ORDINAL_POSITION
                  FROM INFORMATION_SCHEMA.COLUMNS
                  WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
                  ORDER BY ORDINAL_POSITION";

        $databaseName = $connection->getDatabaseName();
        $columns = DB::select($query, [$databaseName, $tableName]);

        $result = [];
        foreach ($columns as $column) {
            if (in_array($column->COLUMN_NAME, ['id', 'created_at', 'updated_at'])) {
                continue; // Skip Laravel default columns
            }

            $result[] = [
                'name' => $column->COLUMN_NAME,
                'type' => $this->convertMySQLType($column->DATA_TYPE, $column->COLUMN_TYPE),
                'nullable' => $column->IS_NULLABLE === 'YES',
                'default' => $column->COLUMN_DEFAULT,
                'column_type' => $column->COLUMN_TYPE, // Keep original for ENUM parsing
            ];
        }

        return $result;
    }

    private function getPostgreSQLColumns($connection, string $tableName): array
    {
        $query = "SELECT column_name, data_type, is_nullable, column_default, ordinal_position
                  FROM information_schema.columns
                  WHERE table_name = ?
                  ORDER BY ordinal_position";

        $columns = DB::select($query, [$tableName]);

        $result = [];
        foreach ($columns as $column) {
            if (in_array($column->column_name, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $result[] = [
                'name' => $column->column_name,
                'type' => $this->convertPostgreSQLType($column->data_type),
                'nullable' => $column->is_nullable === 'YES',
                'default' => $column->column_default,
            ];
        }

        return $result;
    }

    private function getSQLiteColumns($connection, string $tableName): array
    {
        $query = "PRAGMA table_info({$tableName})";
        $columns = DB::select($query);

        $result = [];
        foreach ($columns as $column) {
            if (in_array($column->name, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $result[] = [
                'name' => $column->name,
                'type' => $this->convertSQLiteType($column->type),
                'nullable' => !$column->notnull,
                'default' => $column->dflt_value,
            ];
        }

        return $result;
    }

    private function convertMySQLType(string $dataType, string $fullType = ''): string
    {
        $typeMap = [
            'varchar' => 'string',
            'char' => 'string',
            'text' => 'text',
            'longtext' => 'text',
            'mediumtext' => 'text',
            'tinytext' => 'text',
            'int' => 'integer',
            'bigint' => 'integer',
            'smallint' => 'integer',
            'tinyint' => 'boolean',
            'decimal' => 'decimal',
            'float' => 'float',
            'double' => 'double',
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'timestamp',
            'time' => 'time',
            'json' => 'json',
            'boolean' => 'boolean',
        ];

        // Check for enum
        if (strpos($fullType, 'enum') !== false) {
            return 'select';
        }

        return $typeMap[strtolower($dataType)] ?? 'string';
    }

    private function convertPostgreSQLType(string $dataType): string
    {
        $typeMap = [
            'character varying' => 'string',
            'varchar' => 'string',
            'text' => 'text',
            'integer' => 'integer',
            'bigint' => 'integer',
            'smallint' => 'integer',
            'decimal' => 'decimal',
            'numeric' => 'decimal',
            'real' => 'float',
            'double precision' => 'double',
            'date' => 'date',
            'timestamp' => 'timestamp',
            'timestamp with time zone' => 'timestamp',
            'boolean' => 'boolean',
            'json' => 'json',
            'jsonb' => 'json',
        ];

        return $typeMap[strtolower($dataType)] ?? 'string';
    }

    private function convertSQLiteType(string $type): string
    {
        $typeMap = [
            'text' => 'text',
            'integer' => 'integer',
            'real' => 'float',
            'blob' => 'binary',
            'numeric' => 'decimal',
        ];

        return $typeMap[strtolower($type)] ?? 'string';
    }

    private function getColumnType(string $tableName, string $columnName): string
    {
        try {
            $column = Schema::getColumnType($tableName, $columnName);
            return $column;
        } catch (\Exception $e) {
            return 'string';
        }
    }

    public function getForeignKeys(string $tableName): array
    {
        $foreignKeys = [];

        try {
            $driver = config('database.default');
            $connection = DB::connection($driver);

            if ($connection->getDriverName() === 'mysql') {
                $foreignKeys = $this->getMySQLForeignKeys($connection, $tableName);
            } elseif ($connection->getDriverName() === 'pgsql') {
                $foreignKeys = $this->getPostgreSQLForeignKeys($connection, $tableName);
            } elseif ($connection->getDriverName() === 'sqlite') {
                $foreignKeys = $this->getSQLiteForeignKeys($connection, $tableName);
            }
        } catch (\Exception $e) {
            \Log::warning("Could not detect foreign keys for table {$tableName}: " . $e->getMessage());
        }

        return $foreignKeys;
    }

    private function getMySQLForeignKeys($connection, string $tableName): array
    {
        $databaseName = $connection->getDatabaseName();
        $query = "SELECT
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                  FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                  WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
                    AND REFERENCED_TABLE_NAME IS NOT NULL";

        $results = DB::select($query, [$databaseName, $tableName]);

        $foreignKeys = [];
        foreach ($results as $result) {
            $foreignKeys[] = [
                'column' => $result->COLUMN_NAME,
                'referenced_table' => $result->REFERENCED_TABLE_NAME,
                'referenced_column' => $result->REFERENCED_COLUMN_NAME,
            ];
        }

        return $foreignKeys;
    }

    private function getPostgreSQLForeignKeys($connection, string $tableName): array
    {
        $query = "SELECT
                    kcu.column_name,
                    ccu.table_name AS referenced_table_name,
                    ccu.column_name AS referenced_column_name
                  FROM information_schema.table_constraints AS tc
                  JOIN information_schema.key_column_usage AS kcu
                    ON tc.constraint_name = kcu.constraint_name
                  JOIN information_schema.constraint_column_usage AS ccu
                    ON ccu.constraint_name = tc.constraint_name
                  WHERE tc.constraint_type = 'FOREIGN KEY'
                    AND tc.table_name = ?";

        $results = DB::select($query, [$tableName]);

        $foreignKeys = [];
        foreach ($results as $result) {
            $foreignKeys[] = [
                'column' => $result->column_name,
                'referenced_table' => $result->referenced_table_name,
                'referenced_column' => $result->referenced_column_name,
            ];
        }

        return $foreignKeys;
    }

    private function getSQLiteForeignKeys($connection, string $tableName): array
    {
        return [];
    }
    
    public function migrationExists(string $tableName): bool
    {
        $migrationFiles = glob(database_path("migrations/*_create_{$tableName}_table.php"));
        return !empty($migrationFiles);
    }
}
