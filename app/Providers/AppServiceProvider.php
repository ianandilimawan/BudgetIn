<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            $this->app['request']->server->set('HTTPS', true);
        }


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Configure SMTP dynamically from cached settings
        try {
            $settings = Setting::getSettings();
            if ($settings && $settings->smtp_host) {
                config([
                    'mail.mailers.smtp.host' => $settings->smtp_host,
                    'mail.mailers.smtp.port' => $settings->smtp_port,
                    'mail.mailers.smtp.encryption' => $settings->smtp_encryption,
                    'mail.mailers.smtp.username' => $settings->smtp_username,
                    'mail.mailers.smtp.password' => $settings->smtp_password,
                    'mail.from.address' => $settings->smtp_from_address,
                    'mail.from.name' => $settings->smtp_from_name,
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore during early migrations or when db is unavailable
        }

        // Share settings and filtered menus to all admin views
        View::composer('admin.*', function ($view) {
            $user = Auth::user();

            $filterMenus = function ($items) use (&$filterMenus, $user) {
                return collect($items)->filter(function ($item) use ($user) {
                    if (!$user) {
                        return false;
                    }
                    if ($user->hasRole('super-admin')) {
                        return true;
                    }
                    if (!empty($item['permission'])) {
                        try {
                            return $user->hasPermissionTo($item['permission']);
                        } catch (\Throwable $e) {
                            return false;
                        }
                    }
                    return true;
                })->map(function ($item) use (&$filterMenus) {
                    if (!empty($item['children'])) {
                        $item['children'] = $filterMenus($item['children'])->values()->toArray();
                    }
                    return $item;
                });
            };

            $groupedMenus = collect(config('menu', []))->map(function ($items) use ($filterMenus) {
                return $filterMenus($items)->values();
            })->filter(function ($items) {
                return $items->count() > 0;
            });

            $view->with([
                'settings' => Setting::getSettings(),
                'groupedMenus' => $groupedMenus,
            ]);
        });
    }
}
