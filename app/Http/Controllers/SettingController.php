<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\FileUploadService;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = \Illuminate\Support\Facades\Auth::user();

            if (!$user) {
                abort(403);
            }

            // Super admin role has access to all actions
            if ($user->hasRole('super-admin')) {
                return $next($request);
            }

            $routeName = $request->route()?->getName();
            $modelNameSnake = 'setting';

            if ($routeName) {
                if (str_contains($routeName, '.index') || str_contains($routeName, '.show')) {
                    abort_unless($user->hasPermission("view-{$modelNameSnake}s"), 403);
                } elseif (str_contains($routeName, '.edit') || str_contains($routeName, '.update')) {
                    abort_unless($user->hasPermission("edit-{$modelNameSnake}s"), 403);
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        $setting = Setting::getSettings();

        // Prepare logo URL for view
        $logoUrl = null;
        if ($setting->app_logo) {
            $logoUrl = FileUploadService::getFileUrl($setting->app_logo);
        }

        // Prepare favicon URL for view
        $faviconUrl = null;
        if ($setting->favicon) {
            $faviconUrl = FileUploadService::getFileUrl($setting->favicon);
        }

        return view('admin.pages.settings.index', compact('setting', 'logoUrl', 'faviconUrl'));
    }

    public function update(Request $request)
    {
        $setting = Setting::getSettings();

        $validated = $request->validate([
            // General
            'app_name' => 'required|string|max:255',
            'logo_type' => 'required|in:text,image',
            'logo_text' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'maintenance_mode' => 'nullable|boolean',

            // SMTP
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|string|max:10',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',

            // Appearance
            'theme_default' => 'nullable|in:light,dark,system',
            'sidebar_style' => 'nullable|in:full,collapsed',
        ]);

        // Checkboxes return nothing if unchecked
        $validated['maintenance_mode'] = $request->has('maintenance_mode');

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $oldLogoPath = $setting->app_logo;
            $validated['app_logo'] = FileUploadService::uploadFile(
                $request->file('app_logo'),
                $oldLogoPath,
                'settings'
            );
            $validated['logo_type'] = 'image';
            // Clear logo_text when uploading image
            $validated['logo_text'] = null;
        } else {
            // If logo_type is text, remove logo and require logo_text
            if ($validated['logo_type'] === 'text') {
                if ($setting->app_logo) {
                    FileUploadService::deleteFile($setting->app_logo);
                    $validated['app_logo'] = null;
                }
                // Require logo_text when logo_type is text
                if (empty($validated['logo_text'])) {
                    $validated['logo_text'] = $setting->logo_text ?? $setting->app_name;
                }
            } else {
                // Keep existing logo if not uploading new one and logo_type is image
                unset($validated['app_logo']);
                // Clear logo_text when logo_type is image
                $validated['logo_text'] = null;
            }
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFaviconPath = $setting->favicon;
            $validated['favicon'] = FileUploadService::uploadFile(
                $request->file('favicon'),
                $oldFaviconPath,
                'settings'
            );
        } else {
            // Keep existing favicon if not uploading new one
            unset($validated['favicon']);
        }

        $setting->update($validated);

        // Remember which tab was active
        $activeTab = $request->input('active_tab', 'general');

        return redirect()->route('admin.settings.index', ['tab' => $activeTab])->with('success', 'Settings updated successfully!');
    }
}

