<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    const CACHE_KEY = 'app_settings';

    protected $fillable = [
        'app_name',
        'app_logo',
        'logo_type',
        'logo_text',
        'favicon',
        'maintenance_mode',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'theme_default',
        'sidebar_style',
    ];

    protected $casts = [
        'logo_type' => 'string',
        'maintenance_mode' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Get the current settings (singleton pattern with caching and safe fallback)
     */
    public static function getSettings(): self
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                $settings = self::first();

                if (!$settings) {
                    // Create default settings if none exist
                    $settings = self::create([
                        'app_name' => 'BudgetIn',
                        'app_logo' => null,
                        'logo_type' => 'text',
                        'logo_text' => 'BudgetIn',
                    ]);
                }

                return $settings;
            });
        } catch (\Throwable $e) {
            return new self([
                'app_name' => config('app.name', 'BudgetIn'),
                'theme_default' => 'dark',
                'sidebar_style' => 'full',
            ]);
        }
    }
}
