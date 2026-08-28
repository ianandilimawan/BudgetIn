<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FinanceUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-users')->only(['index', 'show']);
        $this->middleware('permission:edit-users')->only(['edit', 'update', 'toggleStatus']);
        $this->middleware('permission:delete-users')->only('destroy');
    }

    public function index()
    {
        $totalFinanceUsers = User::finance()->count();
        $activeFinanceUsers = User::finance()->where('is_active', true)->count();
        $inactiveFinanceUsers = User::finance()->where('is_active', false)->count();

        $mobileUsers = User::finance()->orderBy('created_at', 'desc')->get();

        return view('admin.pages.finance_users.index', compact(
            'totalFinanceUsers',
            'activeFinanceUsers',
            'inactiveFinanceUsers',
            'mobileUsers'
        ));
    }

    public function edit(User $financeUser)
    {
        return view('admin.pages.finance_users.edit', ['user' => $financeUser]);
    }

    public function update(Request $request, User $financeUser)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $financeUser->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $oldValues = $financeUser->getOriginal();
        $financeUser->update($validated);

        ActivityLogService::logUpdate($financeUser, $oldValues);

        return redirect()->route('admin.finance_users.index')->with('success', 'Data akun Finance berhasil diperbarui.');
    }

    public function toggleStatus(User $financeUser)
    {
        $oldStatus = $financeUser->is_active;
        $financeUser->is_active = !$oldStatus;
        $financeUser->save();

        ActivityLogService::logCustom([
            'action' => 'Toggle Status',
            'model_type' => User::class,
            'model_id' => $financeUser->id,
            'user_id' => auth()->id(),
            'description' => 'Mengubah status akun ' . $financeUser->name . ' menjadi ' . ($financeUser->is_active ? 'Aktif' : 'Nonaktif'),
        ]);

        $statusText = $financeUser->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $financeUser->is_active,
                'message' => "Akun {$financeUser->name} berhasil {$statusText}.",
            ]);
        }

        return back()->with('success', "Akun {$financeUser->name} berhasil {$statusText}.");
    }

    public function destroy(User $financeUser)
    {
        ActivityLogService::logDelete($financeUser);
        $financeUser->delete();

        return redirect()->route('admin.finance_users.index')->with('success', 'Akun Finance berhasil dihapus.');
    }
}
