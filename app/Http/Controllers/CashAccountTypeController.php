<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CashAccountType;
use App\Models\CashAccount;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CashAccountTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-cash_accounts')->only(['index', 'list']);
        $this->middleware('permission:create-cash_accounts')->only('store');
        $this->middleware('permission:edit-cash_accounts')->only('update');
        $this->middleware('permission:delete-cash_accounts')->only('destroy');
    }

    public function index()
    {
        $userId = auth()->id();
        $types = CashAccountType::forUser($userId)
            ->withCount(['accounts' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->orderBy('is_system', 'desc')
            ->orderBy('name')
            ->get()
            ->map(function ($type) {
                $type->can_delete = $type->isDeletableBy(auth()->user());
                $type->can_edit = ($type->user_id === auth()->id()) || auth()->user()->hasRole('super_admin');
                return $type;
            });

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    public function list()
    {
        $userId = auth()->id();
        $types = CashAccountType::forUser($userId)
            ->where('is_active', true)
            ->orderBy('is_system', 'desc')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'icon', 'color', 'is_system', 'user_id']);

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $userId = auth()->id();

        if (empty($validated['code'])) {
            $baseSlug = Str::slug($validated['name'], '_');
            $code = $baseSlug;
            $counter = 1;
            while (CashAccountType::where('code', $code)->where(fn($q) => $q->whereNull('user_id')->orWhere('user_id', $userId))->exists()) {
                $code = $baseSlug . '_' . $counter++;
            }
            $validated['code'] = $code;
        }

        $validated['user_id'] = $userId;
        $validated['is_system'] = false;
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $validated['icon'] = $validated['icon'] ?? 'wallet';
        $validated['color'] = $validated['color'] ?? 'zinc';

        $type = CashAccountType::create($validated);

        ActivityLogService::logCreate($type);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe akun "' . $type->name . '" berhasil ditambahkan.',
                'data' => $type
            ]);
        }

        return redirect()->back()->with('success', 'Tipe akun berhasil ditambahkan.');
    }

    public function update(Request $request, CashAccountType $cashAccountType)
    {
        // Protect system types from being edited by regular users
        if (($cashAccountType->is_system || $cashAccountType->user_id === null) && !auth()->user()->hasRole('super_admin')) {
            $msg = 'Tipe akun bawaan sistem tidak dapat diubah.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return redirect()->back()->with('error', $msg);
        }

        // Protect other users' custom types
        if ($cashAccountType->user_id !== null && $cashAccountType->user_id !== auth()->id() && !auth()->user()->hasRole('super_admin')) {
            $msg = 'Anda tidak memiliki izin untuk mengubah tipe akun ini.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return redirect()->back()->with('error', $msg);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $oldCode = $cashAccountType->code;

        if (isset($validated['is_active'])) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $oldValues = $cashAccountType->getOriginal();
        $cashAccountType->update($validated);

        // If code changed, update existing accounts for this user
        if (!empty($validated['code']) && $validated['code'] !== $oldCode) {
            CashAccount::where('user_id', auth()->id())
                ->where('type', $oldCode)
                ->update(['type' => $validated['code']]);
        }

        ActivityLogService::logUpdate($cashAccountType, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe akun "' . $cashAccountType->name . '" berhasil diperbarui.',
                'data' => $cashAccountType
            ]);
        }

        return redirect()->back()->with('success', 'Tipe akun berhasil diperbarui.');
    }

    public function destroy(CashAccountType $cashAccountType)
    {
        // 1. Strict protection: System types CANNOT be deleted
        if ($cashAccountType->is_system || $cashAccountType->user_id === null) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe akun bawaan sistem/admin tidak dapat dihapus.'
            ], 403);
        }

        // 2. Ownership check: Only owner or super_admin can delete
        if ($cashAccountType->user_id !== auth()->id() && !auth()->user()->hasRole('super_admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus tipe akun ini.'
            ], 403);
        }

        // 3. Usage check: Ensure no active cash accounts rely on this custom type
        $accountsCount = CashAccount::where('type', $cashAccountType->code)
            ->where('user_id', auth()->id())
            ->count();

        if ($accountsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus tipe akun ini karena masih digunakan oleh ' . $accountsCount . ' akun kas Anda.'
            ], 422);
        }

        ActivityLogService::logDelete($cashAccountType);
        $cashAccountType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipe akun "' . $cashAccountType->name . '" berhasil dihapus.'
        ]);
    }
}
