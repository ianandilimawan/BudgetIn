<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'category_id',
        'account_id',
        'to_account_id',
        'amount',
        'frequency',
        'day_of_month',
        'start_date',
        'end_date',
        'last_generated_date',
        'is_active',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'day_of_month' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'last_generated_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(CashAccount::class, 'account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(CashAccount::class, 'to_account_id');
    }

    public function isDueToday(): bool
    {
        if (!$this->is_active) return false;

        $today = now()->startOfDay();
        if ($this->start_date && $this->start_date->startOfDay()->isAfter($today)) return false;
        if ($this->end_date && $this->end_date->startOfDay()->isBefore($today)) return false;

        // Check if already generated this month (for monthly)
        if ($this->frequency === 'monthly') {
            if ($this->last_generated_date && $this->last_generated_date->format('Y-m') === $today->format('Y-m')) {
                return false;
            }
            return (int) $today->format('j') >= (int) $this->day_of_month;
        }

        // Daily
        if ($this->frequency === 'daily') {
            if ($this->last_generated_date && $this->last_generated_date->format('Y-m-d') === $today->format('Y-m-d')) {
                return false;
            }
            return true;
        }

        return false;
    }
}
