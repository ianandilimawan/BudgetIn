<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            if (!$user) {
                abort(403);
            }

            // Super admin role has access to all actions
            if ($user->hasRole('super-admin')) {
                return $next($request);
            }

            $routeName = $request->route()?->getName() ?? '';
            $modelNameSnake = 'permission';

            if (str_contains($routeName, '.index') || str_contains($routeName, '.show')) {
                abort_unless($user->can("view-{$modelNameSnake}s"), 403);
            } elseif (str_contains($routeName, '.create') || str_contains($routeName, '.store')) {
                abort_unless($user->can("create-{$modelNameSnake}s"), 403);
            } elseif (str_contains($routeName, '.edit') || str_contains($routeName, '.update')) {
                abort_unless($user->can("edit-{$modelNameSnake}s"), 403);
            } elseif (str_contains($routeName, '.destroy')) {
                abort_unless($user->can("delete-{$modelNameSnake}s"), 403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.pages.permissions.index');
    }

    public function create()
    {
        return view('admin.pages.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox: if not present in request, set to false
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully!');
    }

    public function edit(Permission $permission)
    {
        return view('admin.pages.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox: if not present in request, set to false
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully!');
    }
}
