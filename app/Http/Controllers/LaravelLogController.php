<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaravelLogService;
use Illuminate\Support\Facades\Auth;

class LaravelLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if (!$user) {
                abort(403);
            }

            // Super admin role has access to all actions
            if ($user->hasRole('super-admin')) {
                return $next($request);
            }

            // Check permission for viewing logs
            $routeName = $request->route()?->getName();
            if ($routeName && (str_contains($routeName, '.index') || str_contains($routeName, '.show'))) {
                abort_unless($user->hasPermission('view-laravel-logs'), 403);
            }

            return $next($request);
        });
    }

    /**
     * Display list of log files
     */
    public function index(Request $request)
    {
        $logFiles = LaravelLogService::getLogFiles();
        $groupedLogFiles = LaravelLogService::groupLogFilesByDate($logFiles);
        $selectedFile = $request->get('file', $logFiles[0]['name'] ?? null);

        // If a file is selected, get its logs
        $logData = null;
        $levels = [];

        if ($selectedFile) {
            $page = (int) $request->get('page', 1);
            $level = $request->get('level');
            $search = $request->get('search');

            $logData = LaravelLogService::readLogFile($selectedFile, $page, 50, $level, $search);
            $levels = LaravelLogService::getLogLevels($selectedFile);
        }

        return view('admin.pages.laravel_logs.index', compact('logFiles', 'groupedLogFiles', 'selectedFile', 'logData', 'levels'));
    }

    /**
     * Show specific log entry details
     */
    public function show(Request $request, string $fileName)
    {
        $page = (int) $request->get('page', 1);
        $level = $request->get('level');
        $search = $request->get('search');

        $logData = LaravelLogService::readLogFile($fileName, $page, 50, $level, $search);
        $levels = LaravelLogService::getLogLevels($fileName);
        $logFiles = LaravelLogService::getLogFiles();

        return view('admin.pages.laravel_logs.show', compact('fileName', 'logData', 'levels', 'logFiles'));
    }

    /**
     * Delete a log file
     */
    public function destroy(Request $request, string $fileName)
    {
        // Only super administrators can delete log files
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            abort(403, 'Only super admin can delete log files');
        }

        $deleted = LaravelLogService::deleteLogFile($fileName);

        if ($deleted) {
            return redirect()->route('admin.laravel-logs.index')
                ->with('success', 'Log file deleted successfully');
        }

        return redirect()->route('admin.laravel-logs.index')
                ->with('error', 'Failed to delete log file');
    }

    /**
     * Clear log file content
     */
    public function clear(Request $request, string $fileName)
    {
        // Only super administrators can clear log files
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            abort(403, 'Only super admin can clear log files');
        }

        $cleared = LaravelLogService::clearLogFile($fileName);

        if ($cleared) {
            return redirect()->route('admin.laravel-logs.index', ['file' => $fileName])
                ->with('success', 'Log file cleared successfully');
        }

        return redirect()->route('admin.laravel-logs.index', ['file' => $fileName])
            ->with('error', 'Failed to clear log file');
    }
}
