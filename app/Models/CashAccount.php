<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cash_accounts';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'account_number',
        'icon',
        'color',
        'initial_balance',
        'is_active',
    ];

    protected $casts = [
        'initial_balance' => 'float',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($account) {
            if (empty($account->user_id) && auth()->check()) {
                $account->user_id = auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForUser($query, ?int $userId = null)
    {
        $uid = $userId ?? (auth()->check() ? auth()->id() : null);
        return $uid ? $query->where('user_id', $uid) : $query;
    }

    public function accountType()
    {
        return $this->belongsTo(CashAccountType::class, 'type', 'code');
    }

    public function getTypeNameAttribute(): string
    {
        return $this->accountType?->name ?? match ($this->type) {
            'cash' => 'Tunai',
            'bank' => 'Bank',
            'ewallet' => 'E-Wallet',
            'investment' => 'Investasi',
            'loan' => 'Pinjaman / Paylater',
            default => ucfirst($this->type ?? 'Lainnya'),
        };
    }

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class, 'account_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(CashTransaction::class, 'to_account_id');
    }
}
