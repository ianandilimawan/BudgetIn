<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ActivityLogController extends Controller
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

            $routeName = $request->route()?->getName();
            $modelNameSnake = 'activity-log';

            if ($routeName && (str_contains($routeName, '.index') || str_contains($routeName, '.show'))) {
                abort_unless($user->hasPermission("view-{$modelNameSnake}s"), 403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by description or exact model_id
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%');
                if (is_numeric($search)) {
                    $q->orWhere('model_id', $search);
                }
            });
        }

        // ponytail: simplePaginate avoids costly COUNT(*) queries on massive log tables.
        $activityLogs = $query->simplePaginate(20)->withQueryString();

        // ponytail: Cache distinct model types for 24h to avoid full-table scans on every page load.
        $modelTypes = Cache::remember('activity_log_distinct_models', now()->addDay(), function () {
            return ActivityLog::select('model_type')
                ->distinct()
                ->orderBy('model_type')
                ->pluck('model_type')
                ->values();
        });

        // Static list of actions to avoid heavy distinct table scans
        $actions = collect(['create', 'update', 'delete', 'Login', 'Logout']);

        return view('admin.pages.activity_logs.index', compact('activityLogs', 'modelTypes', 'actions'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('admin.pages.activity_logs.show', compact('activityLog'));
    }
}
