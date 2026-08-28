<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'icon',
        'target_amount',
        'target_date',
        'status',
        'note',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'target_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'total_spent',
        'remaining_budget',
        'spent_percentage',
        'actual_spent_percentage',
        'total_allocated',
        'unallocated_amount',
        'days_remaining',
        'target_amount_formatted',
        'total_spent_formatted',
        'remaining_budget_formatted',
    ];

    protected static function booted(): void
    {
        static::creating(function ($project) {
            if (empty($project->user_id) && auth()->check()) {
                $project->user_id = auth()->id();
            }
            if (empty($project->icon)) {
                $project->icon = '✨';
            }
        });
    }

    public function scopeForUser($query, ?int $userId = null)
    {
        $uid = $userId ?? (auth()->check() ? auth()->id() : null);
        return $uid ? $query->where('user_id', $uid) : $query;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetProjectItem::class, 'budget_project_id')->orderBy('id', 'asc');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class, 'budget_project_id')->orderBy('transaction_date', 'desc')->orderBy('id', 'desc');
    }

    public function getTotalSpentAttribute(): float
    {
        if ($this->relationLoaded('transactions')) {
            return (float) $this->transactions->where('type', 'expense')->sum('amount');
        }
        return (float) $this->transactions()->where('type', 'expense')->sum('amount');
    }

    public function getRemainingBudgetAttribute(): float
    {
        return (float) max(0, (float) $this->target_amount - $this->total_spent);
    }

    public function getSpentPercentageAttribute(): float
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }
        return (float) min(100, round(($this->total_spent / (float) $this->target_amount) * 100, 1));
    }

    public function getActualSpentPercentageAttribute(): float
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }
        return (float) round(($this->total_spent / (float) $this->target_amount) * 100, 1);
    }

    public function getTotalAllocatedAttribute(): float
    {
        if ($this->relationLoaded('items')) {
            return (float) $this->items->sum('target_amount');
        }
        return (float) $this->items()->sum('target_amount');
    }

    public function getUnallocatedAmountAttribute(): float
    {
        return (float) max(0, (float) $this->target_amount - $this->total_allocated);
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->target_date) {
            return null;
        }
        $today = now()->startOfDay();
        $target = $this->target_date->startOfDay();
        return (int) $today->diffInDays($target, false);
    }

    public function getTargetAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->target_amount, 0, ',', '.');
    }

    public function getTotalSpentFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_spent, 0, ',', '.');
    }

    public function getRemainingBudgetFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_budget, 0, ',', '.');
    }
}
