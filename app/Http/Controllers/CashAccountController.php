<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\CashAccount;
use App\Models\CashAccountType;
use App\Http\Requests\CreateCashAccountRequest;
use App\Http\Requests\UpdateCashAccountRequest;
use Illuminate\Http\Request;

class CashAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-cash_accounts')->only(['index', 'show']);
        $this->middleware('permission:create-cash_accounts')->only(['create', 'store']);
        $this->middleware('permission:edit-cash_accounts')->only(['edit', 'update']);
        $this->middleware('permission:delete-cash_accounts')->only('destroy');
    }

    public function index()
    {
        $userId = auth()->id();
        $accountTypes = CashAccountType::withCount(['accounts' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }])->orderBy('name')->get();
        return view('admin.cash_accounts.index', compact('accountTypes'));
    }

    public function create()
    {
        $cashAccount = new CashAccount([
            'type' => 'cash',
            'color' => 'emerald',
            'is_active' => true,
        ]);
        $accountTypes = CashAccountType::where('is_active', true)->orderBy('name')->pluck('name', 'code')->toArray();
        return view('admin.cash_accounts.create', compact('cashAccount', 'accountTypes'));
    }

    public function store(CreateCashAccountRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $cashAccount = CashAccount::create($data);

        ActivityLogService::logCreate($cashAccount);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun kas berhasil dibuat.',
                'redirect' => route('admin.cash_accounts.index')
            ]);
        }

        return redirect()->route('admin.cash_accounts.index')->with('success', 'Akun kas berhasil dibuat.');
    }

    public function show(CashAccount $cashAccount)
    {
        if ($cashAccount->user_id && $cashAccount->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $cashAccount->load(['accountType', 'transactions']);
        return view('admin.cash_accounts.show', compact('cashAccount'));
    }

    public function edit(CashAccount $cashAccount)
    {
        if ($cashAccount->user_id && $cashAccount->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $accountTypes = CashAccountType::where('is_active', true)->orderBy('name')->pluck('name', 'code')->toArray();
        return view('admin.cash_accounts.edit', compact('cashAccount', 'accountTypes'));
    }

    public function update(UpdateCashAccountRequest $request, CashAccount $cashAccount)
    {
        if ($cashAccount->user_id && $cashAccount->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validated();

        $oldValues = $cashAccount->getOriginal();

        $cashAccount->update($data);

        ActivityLogService::logUpdate($cashAccount, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun kas berhasil diperbarui.',
                'redirect' => route('admin.cash_accounts.index')
            ]);
        }

        return redirect()->route('admin.cash_accounts.index')->with('success', 'Akun kas berhasil diperbarui.');
    }

    public function destroy(CashAccount $cashAccount)
    {
        if ($cashAccount->user_id && $cashAccount->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        ActivityLogService::logDelete($cashAccount);

        $cashAccount->delete();
        return redirect()->route('admin.cash_accounts.index')->with('success', 'Akun kas berhasil dihapus.');
    }
}
