<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CashAccountType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cash_account_types';

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'icon',
        'color',
        'description',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($type) {
            if (empty($type->user_id) && auth()->check() && !$type->is_system) {
                $type->user_id = auth()->id();
            }

            if (empty($type->code) && !empty($type->name)) {
                $baseSlug = Str::slug($type->name, '_');
                $code = $baseSlug;
                $counter = 1;
                while (static::where('code', $code)->exists()) {
                    $code = $baseSlug . '_' . $counter++;
                }
                $type->code = $code;
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
        return $query->where(function ($q) use ($uid) {
            $q->where('is_system', true)
              ->orWhereNull('user_id');
            if ($uid) {
                $q->orWhere('user_id', $uid);
            }
        });
    }

    public function accounts()
    {
        return $this->hasMany(CashAccount::class, 'type', 'code');
    }

    public function isDeletableBy(?User $user = null): bool
    {
        if ($this->is_system || $this->user_id === null) {
            return false;
        }

        $u = $user ?? auth()->user();
        if (!$u) {
            return false;
        }

        return $this->user_id === $u->id;
    }
}
