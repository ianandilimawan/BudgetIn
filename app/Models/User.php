<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Role;
use App\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope finance role users.
     */
    public function scopeFinance($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'finance');
        });
    }

    /**
     * Check if user has permission (bypassed for super-admin/administrator, safe fallback if permission record does not exist).
     */
    public function hasPermission($permission): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        try {
            return $this->hasPermissionTo($permission);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function cashAccounts()
    {
        return $this->hasMany(CashAccount::class, 'user_id');
    }

    public function cashTransactions()
    {
        return $this->hasMany(CashTransaction::class, 'user_id');
    }

    public function transactionCategories()
    {
        return $this->hasMany(TransactionCategory::class, 'user_id');
    }
}
