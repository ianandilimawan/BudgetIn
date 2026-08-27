<?php

namespace App\Traits;

use App\Services\FileUploadService;
use Illuminate\Http\Request;

trait HasFileUpload
{
    /**
     * Get list of file field names from model fields
     */
    protected function getFileFields(string $modelClass): array
    {
        $fileFields = [];
        $model = new $modelClass();
        $fillableFields = $model->getFillable();

        foreach ($fillableFields as $fieldName) {
            $lowerFieldName = strtolower($fieldName);
            if (
                str_contains($lowerFieldName, 'image') ||
                str_contains($lowerFieldName, 'file') ||
                str_contains($lowerFieldName, 'photo') ||
                str_contains($lowerFieldName, 'banner') ||
                str_contains($lowerFieldName, 'avatar') ||
                str_contains($lowerFieldName, 'logo') ||
                str_contains($lowerFieldName, 'icon') ||
                str_contains($lowerFieldName, 'thumbnail') ||
                str_contains($lowerFieldName, 'proof') ||
                str_contains($lowerFieldName, 'receipt') ||
                str_contains($lowerFieldName, 'attachment')
            ) {
                $fileFields[] = $fieldName;
            }
        }

        return $fileFields;
    }

    /**
     * Handle file uploads for store and update methods
     */
    protected function handleFileUploads(Request $request, array &$data, string $modelClass, string $modelNameSnake, $model = null): void
    {
        $fileFields = $this->getFileFields($modelClass);
        foreach ($fileFields as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $oldFilePath = $model ? $model->{$fieldName} : null;
                $data[$fieldName] = FileUploadService::uploadFile(
                    $request->file($fieldName),
                    $oldFilePath,
                    $modelNameSnake
                );
            } else {
                unset($data[$fieldName]);
            }
        }
    }

    /**
     * Get array of file URLs for views
     */
    protected function getFileUrls(string $modelClass, $model = null): array
    {
        $fileUrls = [];
        $fileFields = $this->getFileFields($modelClass);
        foreach ($fileFields as $fieldName) {
            $filePath = $model ? $model->{$fieldName} : null;
            $fileUrls[$fieldName] = $filePath ? FileUploadService::getFileUrl($filePath) : null;
        }
        return $fileUrls;
    }

    /**
     * Delete associated files before deleting a model
     */
    protected function deleteAssociatedFiles(string $modelClass, $model): void
    {
        $fileFields = $this->getFileFields($modelClass);
        foreach ($fileFields as $fieldName) {
            $filePath = $model->{$fieldName} ?? null;
            if ($filePath) {
                FileUploadService::deleteFile($filePath);
            }
        }
    }
}
