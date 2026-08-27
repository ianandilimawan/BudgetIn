<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cash_transactions';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'account_id',
        'to_account_id',
        'category_id',
        'type',
        'amount',
        'transaction_date',
        'note',
        'proof',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'proof_url',
    ];

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof ? \App\Services\FileUploadService::getFileUrl($this->proof) : null;
    }

    protected static function booted(): void
    {
        static::creating(function ($transaction) {
            if (empty($transaction->user_id) && auth()->check()) {
                $transaction->user_id = auth()->id();
            }
        });
    }

    public function scopeForUser($query, ?int $userId = null)
    {
        $uid = $userId ?? (auth()->check() ? auth()->id() : null);
        return $uid ? $query->where('user_id', $uid) : $query;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\TransactionCategory::class, 'category_id');
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\CashAccount::class, 'account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(\App\Models\CashAccount::class, 'to_account_id');
    }
}
