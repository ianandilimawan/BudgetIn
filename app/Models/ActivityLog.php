<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use Prunable;

    // ponytail: Activity logs are append-only. Disable updated_at to save DB I/O and storage.
    const UPDATED_AT = null;

    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'user_id',
        'old_values',
        'new_values',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Determine the prunable query for the model (retention: 60 days).
     *
     * ponytail: Default retention is hardcoded to 60 days. If multi-tenant/configurable retention is needed, read from config('app.activity_log_retention_days', 60).
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(60));
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was logged
     */
    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }
}
