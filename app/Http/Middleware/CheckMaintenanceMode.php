<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't block admin routes so we can still access the dashboard to turn off maintenance
        if ($request->is('admin*') || $request->is('livewire*') || $request->routeIs('admin.*')) {
            return $next($request);
        }

        try {
            $setting = Setting::getSettings();
            
            if ($setting && $setting->maintenance_mode) {
                // Return HTTP 503 Service Unavailable so search engines know it is temporary
                return response()->view('maintenance', ['setting' => $setting], 503)
                    ->header('Retry-After', '300');
            }
        } catch (\Exception $e) {
            // Ignore if settings table doesn't exist yet (e.g., during migration)
        }

        return $next($request);
    }
}
