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
        $types = CashAccountType::withCount('accounts')->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    public function list()
    {
        $types = CashAccountType::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code', 'icon', 'color']);
        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50|unique:cash_account_types,code',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        if (empty($validated['code'])) {
            $baseSlug = Str::slug($validated['name'], '_');
            $code = $baseSlug;
            $counter = 1;
            while (CashAccountType::where('code', $code)->exists()) {
                $code = $baseSlug . '_' . $counter++;
            }
            $validated['code'] = $code;
        }

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
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50|unique:cash_account_types,code,' . $cashAccountType->id,
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

        // If code changed, update existing accounts with old code
        if (!empty($validated['code']) && $validated['code'] !== $oldCode) {
            CashAccount::where('type', $oldCode)->update(['type' => $validated['code']]);
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
        $accountsCount = CashAccount::where('type', $cashAccountType->code)->count();
        if ($accountsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus tipe akun ini karena masih digunakan oleh ' . $accountsCount . ' akun kas.'
            ], 422);
        }

        ActivityLogService::logDelete($cashAccountType);
        $cashAccountType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipe akun berhasil dihapus.'
        ]);
    }
}
