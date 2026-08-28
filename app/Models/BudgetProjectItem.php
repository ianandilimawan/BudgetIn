<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetProjectItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_project_id',
        'name',
        'target_amount',
        'spent_amount',
        'status',
        'note',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
    ];

    protected $appends = [
        'total_spent',
        'remaining_amount',
        'spent_percentage',
        'actual_spent_percentage',
        'is_over_budget',
        'target_amount_formatted',
        'total_spent_formatted',
        'remaining_amount_formatted',
        'over_amount_formatted',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(BudgetProject::class, 'budget_project_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class, 'budget_project_item_id');
    }

    public function getTotalSpentAttribute(): float
    {
        if ($this->relationLoaded('transactions')) {
            return (float) $this->transactions->where('type', 'expense')->sum('amount');
        }
        $dbSpent = (float) $this->transactions()->where('type', 'expense')->sum('amount');
        return $dbSpent > 0 ? $dbSpent : (float) $this->attributes['spent_amount'];
    }

    public function getRemainingAmountAttribute(): float
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

    public function getIsOverBudgetAttribute(): bool
    {
        return (float) $this->target_amount > 0 && $this->total_spent > (float) $this->target_amount;
    }

    public function getTargetAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->target_amount, 0, ',', '.');
    }

    public function getTotalSpentFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_spent, 0, ',', '.');
    }

    public function getRemainingAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }

    public function getOverAmountFormattedAttribute(): string
    {
        $over = max(0, $this->total_spent - (float) $this->target_amount);
        return 'Rp ' . number_format($over, 0, ',', '.');
    }
}
