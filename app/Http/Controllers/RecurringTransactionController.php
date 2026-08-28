<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\RecurringTransaction;
use App\Models\TransactionCategory;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-cash_transactions')->only(['index', 'show']);
        $this->middleware('permission:create-cash_transactions')->only(['create', 'store', 'executeNow']);
        $this->middleware('permission:edit-cash_transactions')->only(['edit', 'update', 'toggleStatus']);
        $this->middleware('permission:delete-cash_transactions')->only('destroy');
    }

    public function index()
    {
        $userId = auth()->id();
        $totalRecurring = RecurringTransaction::where('user_id', $userId)->count();
        $activeRecurring = RecurringTransaction::where('user_id', $userId)->where('is_active', true)->count();
        $monthlyExpenseEstimate = RecurringTransaction::where('user_id', $userId)
            ->where('is_active', true)
            ->where('type', 'expense')
            ->where('frequency', 'monthly')
            ->sum('amount');

        $mobileRecurring = RecurringTransaction::where('user_id', $userId)
            ->with(['account', 'toAccount', 'category'])
            ->orderBy('is_active', 'desc')
            ->orderBy('day_of_month', 'asc')
            ->get();

        return view('admin.recurring_transactions.index', compact('totalRecurring', 'activeRecurring', 'monthlyExpenseEstimate', 'mobileRecurring'));
    }

    public function create()
    {
        $userId = auth()->id();
        $recurring = new RecurringTransaction([
            'type' => 'expense',
            'frequency' => 'monthly',
            'day_of_month' => 1,
            'start_date' => now()->toDateString(),
            'is_active' => true,
        ]);
        $cashAccounts = CashAccount::where('user_id', $userId)->where('is_active', true)->get();
        $incomeCategories = TransactionCategory::forUser($userId)->where('type', 'income')->where('is_active', true)->get();
        $expenseCategories = TransactionCategory::forUser($userId)->where('type', 'expense')->where('is_active', true)->get();

        return view('admin.recurring_transactions.create', compact('recurring', 'cashAccounts', 'incomeCategories', 'expenseCategories'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense,transfer',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'account_id' => 'required|exists:cash_accounts,id',
            'to_account_id' => 'nullable|required_if:type,transfer|exists:cash_accounts,id|different:account_id',
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'day_of_month' => 'required|integer|between:1,31',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
            'note' => 'nullable|string|max:500',
        ]);

        // Security check: verify accounts belong to user
        CashAccount::where('user_id', $userId)->findOrFail($validated['account_id']);
        if (!empty($validated['to_account_id'])) {
            CashAccount::where('user_id', $userId)->findOrFail($validated['to_account_id']);
        }

        if (!empty($validated['category_id'])) {
            TransactionCategory::forUser($userId)->findOrFail($validated['category_id']);
        }

        $validated['user_id'] = $userId;
        $validated['is_active'] = $request->boolean('is_active', true);

        $recurring = RecurringTransaction::create($validated);

        ActivityLogService::logCreate($recurring);

        return redirect()->route('admin.recurring_transactions.index')->with('success', 'Jadwal transaksi berulang berhasil dibuat.');
    }

    public function edit(RecurringTransaction $recurring_transaction)
    {
        $userId = auth()->id();
        if ($recurring_transaction->user_id !== $userId) {
            abort(403, 'Akses ditolak.');
        }

        $recurring = $recurring_transaction;
        $cashAccounts = CashAccount::where('user_id', $userId)->where('is_active', true)->get();
        $incomeCategories = TransactionCategory::forUser($userId)->where('type', 'income')->where('is_active', true)->get();
        $expenseCategories = TransactionCategory::forUser($userId)->where('type', 'expense')->where('is_active', true)->get();

        return view('admin.recurring_transactions.edit', compact('recurring', 'cashAccounts', 'incomeCategories', 'expenseCategories'));
    }

    public function update(Request $request, RecurringTransaction $recurring_transaction)
    {
        $userId = auth()->id();
        if ($recurring_transaction->user_id !== $userId) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense,transfer',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'account_id' => 'required|exists:cash_accounts,id',
            'to_account_id' => 'nullable|required_if:type,transfer|exists:cash_accounts,id|different:account_id',
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'day_of_month' => 'required|integer|between:1,31',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
            'note' => 'nullable|string|max:500',
        ]);

        CashAccount::where('user_id', $userId)->findOrFail($validated['account_id']);
        if (!empty($validated['to_account_id'])) {
            CashAccount::where('user_id', $userId)->findOrFail($validated['to_account_id']);
        }

        if (!empty($validated['category_id'])) {
            TransactionCategory::forUser($userId)->findOrFail($validated['category_id']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $oldValues = $recurring_transaction->getOriginal();
        $recurring_transaction->update($validated);

        ActivityLogService::logUpdate($recurring_transaction, $oldValues);

        return redirect()->route('admin.recurring_transactions.index')->with('success', 'Transaksi berulang berhasil diperbarui.');
    }

    public function toggleStatus(RecurringTransaction $recurring_transaction)
    {
        $userId = auth()->id();
        if ($recurring_transaction->user_id !== $userId) {
            abort(403, 'Akses ditolak.');
        }

        $recurring_transaction->is_active = !$recurring_transaction->is_active;
        $recurring_transaction->save();

        $statusText = $recurring_transaction->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Jadwal '{$recurring_transaction->name}' berhasil {$statusText}.");
    }

    public function executeNow(RecurringTransaction $recurring_transaction)
    {
        $userId = auth()->id();
        if ($recurring_transaction->user_id !== $userId) {
            abort(403, 'Akses ditolak.');
        }

        $transaction = CashTransaction::create([
            'user_id' => $userId,
            'type' => $recurring_transaction->type,
            'category_id' => $recurring_transaction->category_id,
            'account_id' => $recurring_transaction->account_id,
            'to_account_id' => $recurring_transaction->to_account_id,
            'amount' => $recurring_transaction->amount,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => ($recurring_transaction->note ? $recurring_transaction->note . ' - ' : '') . '[Transaksi Berulang: ' . $recurring_transaction->name . ']',
        ]);

        $recurring_transaction->last_generated_date = now()->toDateString();
        $recurring_transaction->save();

        ActivityLogService::logCreate($transaction);

        return back()->with('success', "Transaksi '{$recurring_transaction->name}' sebesar Rp " . number_format($recurring_transaction->amount, 0, ',', '.') . " berhasil dicatat ke transaksi kas.");
    }

    public function destroy(RecurringTransaction $recurring_transaction)
    {
        $userId = auth()->id();
        if ($recurring_transaction->user_id !== $userId) {
            abort(403, 'Akses ditolak.');
        }

        ActivityLogService::logDelete($recurring_transaction);
        $recurring_transaction->delete();

        return redirect()->route('admin.recurring_transactions.index')->with('success', 'Jadwal transaksi berulang berhasil dihapus.');
    }
}
