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
        'name',
        'code',
        'icon',
        'color',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($type) {
            if (empty($type->code) && !empty($type->name)) {
                $type->code = Str::slug($type->name, '_');
            }
        });
    }

    public function accounts()
    {
        return $this->hasMany(CashAccount::class, 'type', 'code');
    }
}
