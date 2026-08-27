<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\CashSummaryService;
use App\Services\FileUploadService;
use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Models\CashAccount;
use App\Http\Requests\CreateCashTransactionRequest;
use App\Http\Requests\UpdateCashTransactionRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;

class CashTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-cash_transactions')->only(['index', 'show', 'export']);
        $this->middleware('permission:create-cash_transactions')->only(['create', 'store']);
        $this->middleware('permission:edit-cash_transactions')->only(['edit', 'update']);
        $this->middleware('permission:delete-cash_transactions')->only('destroy');
    }

    public function index(Request $request, CashSummaryService $summaryService)
    {
        $userId = auth()->id();
        $period = $request->get('period', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $month = $request->filled('month') ? (int) $request->get('month') : null;
        $year = $request->filled('year') ? (int) $request->get('year') : null;
        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $accountId = $request->get('account_id');

        $dateRange = $summaryService->parseDateRange($period, $startDate, $endDate, $month, $year);
        $filteredSummary = $summaryService->getFilteredSummary($dateRange, $userId);

        $cashTransaction = new CashTransaction([
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'expense',
        ]);

        $allCategories = TransactionCategory::forUser($userId)->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'type', 'icon']);

        $expenseCategories = $allCategories->where('type', 'expense')->values();
        $incomeCategories = $allCategories->where('type', 'income')->values();

        $cashAccounts = CashAccount::forUser($userId)->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Query filtered transactions for mobile cards
        $mobileQuery = CashTransaction::forUser($userId)->with(['category', 'account', 'toAccount', 'user']);
        if (!$dateRange['is_all_time']) {
            if ($dateRange['start_date'] && $dateRange['end_date']) {
                $mobileQuery->whereBetween('transaction_date', [$dateRange['start_date'], $dateRange['end_date']]);
            } elseif ($dateRange['start_date']) {
                $mobileQuery->where('transaction_date', '>=', $dateRange['start_date']);
            } elseif ($dateRange['end_date']) {
                $mobileQuery->where('transaction_date', '<=', $dateRange['end_date']);
            }
        }

        if ($type && in_array($type, ['income', 'expense', 'transfer'])) {
            $mobileQuery->where('type', $type);
        }
        if ($categoryId) {
            $mobileQuery->where('category_id', $categoryId);
        }
        if ($accountId) {
            $mobileQuery->where(function ($q) use ($accountId) {
                $q->where('account_id', $accountId)->orWhere('to_account_id', $accountId);
            });
        }

        $mobileTransactions = $mobileQuery->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(100)
            ->get()
            ->groupBy(function ($item) {
                return $item->transaction_date ? Carbon::parse($item->transaction_date)->format('Y-m-d') : 'No Date';
            });

        return view('admin.cash_transactions.index', compact(
            'cashTransaction',
            'expenseCategories',
            'incomeCategories',
            'allCategories',
            'cashAccounts',
            'mobileTransactions',
            'dateRange',
            'filteredSummary',
            'type',
            'categoryId',
            'accountId'
        ));
    }

    public function export(Request $request, CashSummaryService $summaryService)
    {
        $userId = auth()->id();
        $period = $request->get('period', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $month = $request->filled('month') ? (int) $request->get('month') : null;
        $year = $request->filled('year') ? (int) $request->get('year') : null;
        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $accountId = $request->get('account_id');

        $dateRange = $summaryService->parseDateRange($period, $startDate, $endDate, $month, $year);

        $query = CashTransaction::forUser($userId)->with(['category', 'account', 'toAccount', 'user']);

        if (!$dateRange['is_all_time']) {
            if ($dateRange['start_date'] && $dateRange['end_date']) {
                $query->whereBetween('transaction_date', [$dateRange['start_date'], $dateRange['end_date']]);
            } elseif ($dateRange['start_date']) {
                $query->where('transaction_date', '>=', $dateRange['start_date']);
            } elseif ($dateRange['end_date']) {
                $query->where('transaction_date', '<=', $dateRange['end_date']);
            }
        }

        if ($type && in_array($type, ['income', 'expense', 'transfer'])) {
            $query->where('type', $type);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($accountId) {
            $query->where(function ($q) use ($accountId) {
                $q->where('account_id', $accountId)->orWhere('to_account_id', $accountId);
            });
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->get();

        $filename = 'Laporan_Keuangan_' . ($dateRange['period'] === 'specific_month' ? str_replace(' ', '_', $dateRange['label']) : date('Ymd_His')) . '.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), 'export_') . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($tempPath);

        // Header
        $writer->addRow(Row::fromValues([
            'No',
            'ID Transaksi',
            'Tanggal',
            'Tipe',
            'Kategori',
            'Akun / Sumber Dana',
            'Nominal (Rp)',
            'Catatan',
            'Dicatat Oleh',
            'Waktu Input'
        ]));

        $index = 1;
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $t) {
            if ($t->type === 'income') {
                $totalIncome += $t->amount;
                $typeLabel = 'Pemasukan';
                $accountLabel = $t->account->name ?? '-';
            } elseif ($t->type === 'expense') {
                $totalExpense += $t->amount;
                $typeLabel = 'Pengeluaran';
                $accountLabel = $t->account->name ?? '-';
            } else {
                $typeLabel = 'Transfer Saldo';
                $accountLabel = ($t->account->name ?? '-') . ' ➔ ' . ($t->toAccount->name ?? '-');
            }

            $writer->addRow(Row::fromValues([
                $index++,
                '#TRX-' . str_pad($t->id, 5, '0', STR_PAD_LEFT),
                $t->transaction_date ? Carbon::parse($t->transaction_date)->format('d/m/Y') : '-',
                $typeLabel,
                $t->category->name ?? ($t->type === 'transfer' ? 'Pindah Dana' : '-'),
                $accountLabel,
                (float) $t->amount,
                $t->note ?? '',
                $t->user->name ?? 'Admin',
                $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-'
            ]));
        }

        // Summary Rows
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValues(['', '', '', '', '', 'TOTAL PEMASUKAN', $totalIncome, '', '', '']));
        $writer->addRow(Row::fromValues(['', '', '', '', '', 'TOTAL PENGELUARAN', $totalExpense, '', '', '']));
        $writer->addRow(Row::fromValues(['', '', '', '', '', 'SALDO BERSIH (NET)', $totalIncome - $totalExpense, '', '', '']));

        $writer->close();

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function create()
    {
        $userId = auth()->id();
        $cashTransaction = new CashTransaction();
        $expenseCategories = TransactionCategory::forUser($userId)->where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();
        $incomeCategories = TransactionCategory::forUser($userId)->where('is_active', true)
            ->where('type', 'income')
            ->orderBy('name')
            ->get();
        $cashAccounts = CashAccount::forUser($userId)->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.cash_transactions.create', compact('cashTransaction', 'expenseCategories', 'incomeCategories', 'cashAccounts'));
    }

    public function store(CreateCashTransactionRequest $request)
    {
        $userId = auth()->id();
        $data = $request->validated();
        $data['user_id'] = $userId;

        // Security / IDOR validation: Ensure account_id belongs to authenticated user
        if (!empty($data['account_id'])) {
            $ownsAccount = CashAccount::forUser($userId)->where('id', $data['account_id'])->exists();
            if (!$ownsAccount) {
                abort(403, 'Akun sumber tidak valid.');
            }
        }

        // Security / IDOR validation: Ensure to_account_id belongs to authenticated user
        if (!empty($data['to_account_id'])) {
            $ownsToAccount = CashAccount::forUser($userId)->where('id', $data['to_account_id'])->exists();
            if (!$ownsToAccount) {
                abort(403, 'Akun tujuan tidak valid.');
            }
        }

        // Security / IDOR validation: Ensure category_id is accessible to user
        if (!empty($data['category_id'])) {
            $validCat = TransactionCategory::forUser($userId)->where('id', $data['category_id'])->exists();
            if (!$validCat) {
                abort(403, 'Kategori tidak valid.');
            }
        }

        if (empty($data['type'])) {
            if (!empty($data['to_account_id'])) {
                $data['type'] = 'transfer';
            } elseif (!empty($data['category_id'])) {
                $category = TransactionCategory::find($data['category_id']);
                $data['type'] = $category ? $category->type : 'expense';
            } else {
                $data['type'] = 'expense';
            }
        }

        if ($request->hasFile('proof')) {
            $data['proof'] = FileUploadService::uploadFile($request->file('proof'), null, 'cash_transactions');
        }

        $cashTransaction = CashTransaction::create($data);

        ActivityLogService::logCreate($cashTransaction);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'redirect' => route('admin.cash_transactions.index')
            ]);
        }

        return redirect()->route('admin.cash_transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(CashTransaction $cashTransaction)
    {
        if ($cashTransaction->user_id && $cashTransaction->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $cashTransaction->load(['category', 'account', 'toAccount', 'user']);
        return view('admin.cash_transactions.show', compact('cashTransaction'));
    }

    public function edit(CashTransaction $cashTransaction)
    {
        if ($cashTransaction->user_id && $cashTransaction->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $userId = auth()->id();
        $expenseCategories = TransactionCategory::forUser($userId)->where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();
        $incomeCategories = TransactionCategory::forUser($userId)->where('is_active', true)
            ->where('type', 'income')
            ->orderBy('name')
            ->get();
        $cashAccounts = CashAccount::forUser($userId)->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.cash_transactions.edit', compact('cashTransaction', 'expenseCategories', 'incomeCategories', 'cashAccounts'));
    }

    public function update(UpdateCashTransactionRequest $request, CashTransaction $cashTransaction)
    {
        if ($cashTransaction->user_id && $cashTransaction->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $userId = auth()->id();
        $data = $request->validated();

        // Security / IDOR validation: Ensure account_id belongs to authenticated user
        if (!empty($data['account_id'])) {
            $ownsAccount = CashAccount::forUser($userId)->where('id', $data['account_id'])->exists();
            if (!$ownsAccount) {
                abort(403, 'Akun sumber tidak valid.');
            }
        }

        // Security / IDOR validation: Ensure to_account_id belongs to authenticated user
        if (!empty($data['to_account_id'])) {
            $ownsToAccount = CashAccount::forUser($userId)->where('id', $data['to_account_id'])->exists();
            if (!$ownsToAccount) {
                abort(403, 'Akun tujuan tidak valid.');
            }
        }

        // Security / IDOR validation: Ensure category_id is accessible to user
        if (!empty($data['category_id'])) {
            $validCat = TransactionCategory::forUser($userId)->where('id', $data['category_id'])->exists();
            if (!$validCat) {
                abort(403, 'Kategori tidak valid.');
            }
        }

        if (empty($data['type'])) {
            if (!empty($data['to_account_id'])) {
                $data['type'] = 'transfer';
            } elseif (!empty($data['category_id'])) {
                $category = TransactionCategory::find($data['category_id']);
                $data['type'] = $category ? $category->type : 'expense';
            } else {
                $data['type'] = 'expense';
            }
        }

        if ($request->hasFile('proof')) {
            $data['proof'] = FileUploadService::uploadFile($request->file('proof'), $cashTransaction->proof, 'cash_transactions');
        } elseif ($request->boolean('remove_proof')) {
            FileUploadService::deleteFile($cashTransaction->proof);
            $data['proof'] = null;
        }

        $oldValues = $cashTransaction->getOriginal();

        $cashTransaction->update($data);

        ActivityLogService::logUpdate($cashTransaction, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui.',
                'redirect' => route('admin.cash_transactions.index')
            ]);
        }

        return redirect()->route('admin.cash_transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(CashTransaction $cashTransaction)
    {
        if ($cashTransaction->user_id && $cashTransaction->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        ActivityLogService::logDelete($cashTransaction);

        if ($cashTransaction->proof) {
            FileUploadService::deleteFile($cashTransaction->proof);
        }

        $cashTransaction->delete();
        return redirect()->route('admin.cash_transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
