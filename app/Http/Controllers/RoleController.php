<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
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

            $routeName = $request->route()->getName();
            $modelNameSnake = 'role';

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
        return view('admin.pages.roles.index');
    }

    public function create()
    {
        $permissions = Permission::active()->orderBy('module')->orderBy('name')->get();
        return view('admin.pages.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role = Role::create($validated);

        $role->permissions()->sync($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::active()->orderBy('module')->orderBy('name')->get();
        $role->load('permissions');
        return view('admin.pages.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role->update($validated);

        $role->permissions()->sync($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully!');
    }
}
