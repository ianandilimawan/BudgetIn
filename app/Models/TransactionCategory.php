<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TransactionCategory extends Model
{
    use HasFactory;

    protected $table = 'transaction_categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id', 'name', 'type', 'icon', 'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected static function booted(): void
    {
        static::creating(function ($category) {
            if (empty($category->user_id) && auth()->check()) {
                $category->user_id = auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function scopeForUser($query, ?int $userId = null)
    {
        $uid = $userId ?? (auth()->check() ? auth()->id() : null);
        return $query->where(function ($q) use ($uid) {
            $q->whereNull('user_id');
            if ($uid) {
                $q->orWhere('user_id', $uid);
            }
        });
    }

    public function cashTransactions()
    {
        return $this->hasMany(\App\Models\CashTransaction::class, 'category_id');
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\CashTransaction::class, 'category_id');
    }
}
