<?php

namespace App\Services;

use App\Jobs\LogActivityJob;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogService
{
    /**
     * Log a create action
     *
     * @param Model $model The model that was created
     * @param string|null $description Custom description
     * @return void
     */
    public static function logCreate(Model $model, ?string $description = null): void
    {
        self::log('create', $model, null, $model->getAttributes(), $description);
    }

    /**
     * Log an update action
     *
     * @param Model $model The model that was updated
     * @param array|null $oldValues Old values before update
     * @param string|null $description Custom description
     * @return void
     */
    public static function logUpdate(Model $model, ?array $oldValues = null, ?string $description = null): void
    {
        // If oldValues not provided, get from model's original attributes
        if ($oldValues === null && $model->exists) {
            $oldValues = $model->getOriginal();
        }

        self::log('update', $model, $oldValues, $model->getChanges(), $description);
    }

    /**
     * Log a delete action
     *
     * @param Model $model The model that was deleted
     * @param string|null $description Custom description
     * @return void
     */
    public static function logDelete(Model $model, ?string $description = null): void
    {
        self::log('delete', $model, $model->getAttributes(), null, $description);
    }

    /**
     * Generic log method (dispatched asynchronously after response)
     *
     * @param string $action Action type (create, update, delete)
     * @param Model $model The model being logged
     * @param array|null $oldValues Old values
     * @param array|null $newValues New values
     * @param string|null $description Custom description
     * @return void
     */
    protected static function log(
        string $action,
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): void {
        try {
            // Get current user
            $user = Auth::user();
            $userId = $user ? $user->id : null;

            // Generate description if not provided
            if ($description === null) {
                $modelName = class_basename($model);
                $modelId = $model->id ?? 'new';
                $description = sprintf(
                    '%s %s %s (ID: %s)',
                    $user ? $user->name : 'System',
                    $action,
                    $modelName,
                    $modelId
                );
            }

            // Filter out sensitive fields
            $sensitiveFields = ['password', 'password_confirmation', 'remember_token', 'api_token'];

            if ($oldValues) {
                $oldValues = array_filter($oldValues, function ($key) use ($sensitiveFields) {
                    return !in_array($key, $sensitiveFields);
                }, ARRAY_FILTER_USE_KEY);
            }

            if ($newValues) {
                $newValues = array_filter($newValues, function ($key) use ($sensitiveFields) {
                    return !in_array($key, $sensitiveFields);
                }, ARRAY_FILTER_USE_KEY);
            }

            // ponytail: Dispatching afterResponse provides zero-latency synchronous responses. If distributed queue workers are active, it automatically pushes to queue.
            LogActivityJob::dispatch([
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id ?? null,
                'user_id' => $userId,
                'old_values' => !empty($oldValues) ? $oldValues : null,
                'new_values' => !empty($newValues) ? $newValues : null,
                'description' => $description,
            ])->afterResponse();
        } catch (\Exception $e) {
            Log::error('ActivityLogService: Failed to dispatch activity log', [
                'error' => $e->getMessage(),
                'action' => $action,
                'model' => get_class($model),
            ]);
        }
    }

    /**
     * Get logs for a specific model
     *
     * @param Model $model
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getModelLogs(Model $model, int $limit = 50)
    {
        return ActivityLog::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get logs for a specific user
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserLogs(int $userId, int $limit = 50)
    {
        return ActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get logs by action type
     *
     * @param string $action
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLogsByAction(string $action, int $limit = 50)
    {
        return ActivityLog::where('action', $action)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Log a custom action
     *
     * @param array $data Custom log data
     * @return void
     */
    public static function logCustom(array $data): void
    {
        try {
            LogActivityJob::dispatch($data)->afterResponse();
        } catch (\Exception $e) {
            Log::error('ActivityLogService: Failed to dispatch custom activity log', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Log a bulk import action
     *
     * @param string $modelClass The model class name (e.g., Brand::class)
     * @param int $count Number of records imported
     * @param string|null $description Custom description
     * @return void
     */
    public static function logBulkImport(string $modelClass, int $count, ?string $description = null): void
    {
        try {
            // Get current user
            $user = Auth::user();
            $userId = $user ? $user->id : null;

            // Generate description if not provided
            if ($description === null) {
                $modelName = class_basename($modelClass);
                $description = sprintf(
                    '%s imported %d %s records via bulk import',
                    $user ? $user->name : 'System',
                    $count,
                    $modelName
                );
            }

            LogActivityJob::dispatch([
                'action' => 'create',
                'model_type' => $modelClass,
                'model_id' => null, // Bulk import doesn't have a specific model_id
                'user_id' => $userId,
                'old_values' => null,
                'new_values' => ['count' => $count],
                'description' => $description,
            ])->afterResponse();
        } catch (\Exception $e) {
            Log::error('ActivityLogService: Failed to dispatch bulk import activity', [
                'error' => $e->getMessage(),
                'model' => $modelClass,
                'count' => $count,
            ]);
        }
    }

    /**
     * Log a bulk delete action
     *
     * @param string $modelClass The model class name (e.g., Brand::class)
     * @param int $count Number of records deleted
     * @param array $ids The IDs of the records deleted
     * @param string|null $description Custom description
     * @return void
     */
    public static function logBulkDelete(string $modelClass, int $count, array $ids = [], ?string $description = null): void
    {
        try {
            // Get current user
            $user = Auth::user();
            $userId = $user ? $user->id : null;

            // Generate description if not provided
            if ($description === null) {
                $modelName = class_basename($modelClass);
                $description = sprintf(
                    '%s deleted %d %s records via bulk action',
                    $user ? $user->name : 'System',
                    $count,
                    $modelName
                );
            }

            LogActivityJob::dispatch([
                'action' => 'delete',
                'model_type' => $modelClass,
                'model_id' => null, // Bulk delete doesn't have a specific model_id
                'user_id' => $userId,
                'old_values' => ['ids' => $ids, 'count' => $count],
                'new_values' => null,
                'description' => $description,
            ])->afterResponse();
        } catch (\Exception $e) {
            Log::error('ActivityLogService: Failed to dispatch bulk delete activity', [
                'error' => $e->getMessage(),
                'model' => $modelClass,
                'count' => $count,
            ]);
        }
    }
}
