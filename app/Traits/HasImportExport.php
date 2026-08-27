<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Services\ActivityLogService;

trait HasImportExport
{
    /**
     * Get the fully qualified class name of the Model.
     */
    abstract protected function getModelClass(): string;

    /**
     * Get the view path (e.g., 'users', 'products').
     */
    abstract protected function getViewPath(): string;

    /**
     * Get the route name prefix (e.g., 'admin.users').
     */
    abstract protected function getRouteName(): string;

    /**
     * Get the snake_case name of the model (e.g., 'user', 'product_category').
     */
    abstract protected function getModelNameSnake(): string;

    public function importForm()
    {
        return view($this->getViewPath() . '.import');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'csv');
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $fillableFields = $model->getFillable();

        if (!in_array($format, ['csv', 'xlsx'])) {
            $format = 'csv';
        }

        $headers = $fillableFields;
        $allRows = [];
        // ponytail: Using cursor() streams rows sequentially from DB to prevent memory exhaustion on large tables.
        foreach ($modelClass::query()->cursor() as $record) {
            $row = [];
            foreach ($fillableFields as $field) {
                $row[] = $record->$field;
            }
            $allRows[] = $row;
        }

        if ($format === 'csv') {
            return $this->downloadCsvData($headers, $allRows);
        } else {
            return $this->downloadExcelData($headers, $allRows);
        }
    }

    public function downloadSample($format = 'csv')
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $fillableFields = $model->getFillable();

        if (!in_array($format, ['csv', 'xlsx'])) {
            $format = 'csv';
        }

        // Generate sample data
        $headers = $fillableFields;
        $sampleRow = $this->getSampleRowData($fillableFields);

        if ($format === 'csv') {
            return $this->downloadCsvSample($headers, $sampleRow);
        } else {
            return $this->downloadExcelSample($headers, $sampleRow);
        }
    }

    private function getSampleRowData($fillableFields)
    {
        $sampleData = [];
        foreach ($fillableFields as $field) {
            $sampleData[] = $this->getSampleValueForField($field);
        }
        return $sampleData;
    }

    private function getSampleValueForField($fieldName)
    {
        $lowerField = strtolower($fieldName);

        if (str_contains($lowerField, 'email')) {
            return 'sample@example.com';
        } elseif (str_contains($lowerField, 'phone')) {
            return '081234567890';
        } elseif (str_contains($lowerField, 'date') || str_contains($lowerField, 'at')) {
            return '2024-01-01';
        } elseif (str_contains($lowerField, 'price') || str_contains($lowerField, 'cost') || str_contains($lowerField, 'amount')) {
            return '100000';
        } elseif (str_contains($lowerField, 'quantity') || str_contains($lowerField, 'qty') || str_contains($lowerField, 'count')) {
            return '1';
        } elseif (str_contains($lowerField, 'status')) {
            return 'active';
        } elseif (str_contains($lowerField, 'name')) {
            return 'Sample Name';
        } elseif (str_contains($lowerField, 'title')) {
            return 'Sample Title';
        } elseif (str_contains($lowerField, 'description')) {
            return 'Sample Description';
        } else {
            return 'Sample Value';
        }
    }

    private function downloadCsvSample($headers, $sampleRow)
    {
        $filename = $this->getModelNameSnake() . '_sample.csv';

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        fputcsv($output, $sampleRow);
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function downloadExcelSample($headers, $sampleRow)
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            return redirect()->route($this->getRouteName() . '.importForm')
                ->with('error', 'PhpSpreadsheet library is not installed. Please run: composer require phpoffice/phpspreadsheet');
        }

        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $colIndex = 0;
            foreach ($headers as $index => $header) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);

                if (isset($sampleRow[$index])) {
                    $sheet->setCellValue($col . '2', $sampleRow[$index]);
                }

                $sheet->getColumnDimension($col)->setAutoSize(true);
                $colIndex++;
            }

            $filename = $this->getModelNameSnake() . '_sample.xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            ob_start();
            $writer->save('php://output');
            $content = ob_get_clean();

            return response($content, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return redirect()->route($this->getRouteName() . '.importForm')
                ->with('error', 'Failed to generate Excel file: ' . $e->getMessage());
        }
    }

    private function downloadCsvData($headers, $allRows)
    {
        $filename = $this->getModelNameSnake() . '_export_' . date('Ymd_His') . '.csv';

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        foreach ($allRows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function downloadExcelData($headers, $allRows)
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            return redirect()->route($this->getRouteName() . '.index')
                ->with('error', 'PhpSpreadsheet library is not installed. Please run: composer require phpoffice/phpspreadsheet');
        }

        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $colIndex = 0;
            foreach ($headers as $index => $header) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $colIndex++;
            }

            $rowIndex = 2;
            foreach ($allRows as $row) {
                $colIndex = 0;
                foreach ($row as $index => $value) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                    $sheet->setCellValue($col . $rowIndex, $value);
                    $colIndex++;
                }
                $rowIndex++;
            }

            $filename = $this->getModelNameSnake() . '_export_' . date('Ymd_His') . '.xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            ob_start();
            $writer->save('php://output');
            $content = ob_get_clean();

            return response($content, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return redirect()->route($this->getRouteName() . '.index')
                ->with('error', 'Failed to generate Excel file: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        set_time_limit(300);
        ini_set('max_execution_time', 300);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['csv', 'txt', 'xlsx', 'xls'])) {
            return redirect()->back()->withErrors(['file' => 'The file must be a file of type: csv, txt, xlsx, xls.']);
        }

        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $fillableFields = $model->getFillable();

        if (in_array($extension, ['xlsx', 'xls'])) {
            return $this->importExcel($file, $fillableFields);
        } else {
            return $this->importCsv($file, $fillableFields);
        }
    }

    private function importCsv($file, $fillableFields)
    {
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return redirect()->route($this->getRouteName() . '.importForm')->with('error', 'Failed to open file.');
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            return redirect()->route($this->getRouteName() . '.importForm')->with('error', 'File is empty or invalid.');
        }

        $headers = array_map(function ($header) {
            return trim(str_replace("\xEF\xBB\xBF", '', $header));
        }, $headers);

        $fieldMap = $this->mapHeadersToFields($headers, $fillableFields);

        $allRows = [];
        $rowNumber = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if (count($row) >= count($headers)) {
                $allRows[] = $row;
            }
        }
        fclose($handle);

        return $this->processRows($allRows, $fieldMap);
    }

    private function importExcel($file, $fillableFields)
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            return redirect()->route($this->getRouteName() . '.importForm')->with('error', 'PhpSpreadsheet library is not installed.');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return redirect()->route($this->getRouteName() . '.importForm')->with('error', 'File is empty or invalid.');
            }

            $headers = array_map(function ($header) {
                return trim(str_replace("\xEF\xBB\xBF", '', (string)$header));
            }, $rows[0]);

            $fieldMap = $this->mapHeadersToFields($headers, $fillableFields);

            $allRows = array_slice($rows, 1);

            // Normalize rows for processRows
            $allRows = array_map(function ($row) {
                return array_map(function ($cell) {
                    return $cell !== null ? (string)$cell : '';
                }, $row);
            }, $allRows);

            return $this->processRows($allRows, $fieldMap);
        } catch (\Exception $e) {
            return redirect()->route($this->getRouteName() . '.importForm')->with('error', 'Failed to read Excel file: ' . $e->getMessage());
        }
    }

    private function processRows(array $allRows, array $fieldMap)
    {
        $totalRows = count($allRows);
        $batchSize = 100;
        $imported = 0;
        $failed = 0;
        $errors = [];
        $modelClass = $this->getModelClass();

        for ($i = 0; $i < $totalRows; $i += $batchSize) {
            $batch = array_slice($allRows, $i, $batchSize);
            $batchErrors = [];

            foreach ($batch as $index => $row) {
                $currentRowNumber = $i + $index + 2;
                $result = $this->prepareRowData($row, $fieldMap, $currentRowNumber);

                if ($result['success']) {
                    try {
                        $modelClass::create($result['data']);
                        $imported++;
                    } catch (\Exception $e) {
                        $failed++;
                        $errorMsg = "Row {$currentRowNumber}: " . $e->getMessage();
                        if (str_contains($e->getMessage(), 'SQLSTATE')) {
                            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                                $errorMsg = "Row {$currentRowNumber}: Duplicate entry detected.";
                            } elseif (str_contains($e->getMessage(), 'cannot be null')) {
                                $errorMsg = "Row {$currentRowNumber}: Required field is missing or null.";
                            } elseif (str_contains($e->getMessage(), 'Data too long')) {
                                $errorMsg = "Row {$currentRowNumber}: Data exceeds maximum length.";
                            }
                        }
                        $errors[] = $errorMsg;
                    }
                } else {
                    $failed++;
                    $errors[] = $result['error'];
                }
            }
        }

        if ($imported > 0) {
            ActivityLogService::logBulkImport($modelClass, $imported, "Imported {$imported} records via bulk import");
        }

        return $this->returnImportResult($imported, $failed, $totalRows, $errors);
    }

    private function mapHeadersToFields($headers, $fillableFields)
    {
        $fieldMap = [];
        foreach ($fillableFields as $fieldName) {
            foreach ($headers as $index => $header) {
                $normalizedHeader = strtolower(str_replace([' ', '_'], '', (string)$header));
                $normalizedField = strtolower(str_replace('_', '', $fieldName));
                if ($normalizedHeader === $normalizedField || strtolower($header) === strtolower($fieldName)) {
                    $fieldMap[$fieldName] = $index;
                    break;
                }
            }
        }
        return $fieldMap;
    }

    private function prepareRowData($row, $fieldMap, $rowNumber)
    {
        $data = [];
        $errors = [];
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $casts = $model->getCasts();

        foreach ($fieldMap as $fieldName => $columnIndex) {
            if (isset($row[$columnIndex])) {
                $value = trim((string)$row[$columnIndex]);
                if ($value !== '') {
                    if (isset($casts[$fieldName]) && $casts[$fieldName] === 'boolean') {
                        $value = $this->convertToBoolean($value);
                    } elseif (isset($casts[$fieldName]) && $casts[$fieldName] === 'integer') {
                        $value = $this->convertToInteger($value, $fieldName, $rowNumber, $errors);
                    } elseif (isset($casts[$fieldName]) && ($casts[$fieldName] === 'decimal' || $casts[$fieldName] === 'float')) {
                        $value = $this->convertToDecimal($value, $fieldName, $rowNumber, $errors);
                    }
                    $data[$fieldName] = $value;
                }
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'error' => implode(' ', $errors)];
        }

        if (!empty($data)) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
            return ['success' => true, 'data' => $data];
        }

        return ['success' => false, 'error' => "Row {$rowNumber}: No data to import."];
    }

    private function convertToBoolean($value)
    {
        $value = strtolower(trim($value));
        if (in_array($value, ['1', 'true', 'yes', 'on', 'y', 'active', 'aktif'])) {
            return true;
        }
        if (in_array($value, ['0', 'false', 'no', 'off', 'n', 'inactive', 'nonaktif'])) {
            return false;
        }
        return true;
    }

    private function convertToInteger($value, $fieldName, $rowNumber, &$errors)
    {
        $value = trim($value);
        if ($value === '' || $value === null) {
            return null;
        }

        $cleanValue = preg_replace('/[^0-9-]/', '', $value);
        if ($cleanValue === '' || $cleanValue === '-') {
            $errors[] = "Row {$rowNumber}: Field '{$fieldName}' must be a valid integer, got '{$value}'.";
            return 0;
        }
        return (int)$cleanValue;
    }

    private function convertToDecimal($value, $fieldName, $rowNumber, &$errors)
    {
        $value = trim($value);
        if ($value === '' || $value === null) {
            return null;
        }

        $cleanValue = preg_replace('/[^0-9.-]/', '', str_replace(',', '', $value));
        if ($cleanValue === '' || $cleanValue === '-' || $cleanValue === '.') {
            $errors[] = "Row {$rowNumber}: Field '{$fieldName}' must be a valid number, got '{$value}'.";
            return 0.0;
        }
        return (float)$cleanValue;
    }

    private function returnImportResult($imported, $failed, $total, $errors)
    {
        $message = "Import completed! Total: {$total}, Success: {$imported}, Failed: {$failed}";
        if (!empty($errors)) {
            $errorsToShow = array_slice($errors, 0, 50);
            session()->flash('import_errors', $errorsToShow);
            if (count($errors) > 50) {
                $message .= " (Showing first 50 errors out of " . count($errors) . " errors)";
            }
        }
        return redirect()->route($this->getRouteName() . '.index')->with('success', $message);
    }
}
