<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\TransactionCategory;
use App\Http\Requests\CreateTransactionCategoryRequest;
use App\Http\Requests\UpdateTransactionCategoryRequest;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-transaction_categories')->only(['index', 'show']);
        $this->middleware('permission:create-transaction_categories')->only(['create', 'store']);
        $this->middleware('permission:edit-transaction_categories')->only(['edit', 'update']);
        $this->middleware('permission:delete-transaction_categories')->only('destroy');
    }

    public function index()
    {
        $userId = auth()->id();
        $categories = TransactionCategory::forUser($userId)
            ->withCount(['transactions' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $expenseCount = $categories->where('type', 'expense')->count();
        $incomeCount = $categories->where('type', 'income')->count();

        return view('admin.transaction_categories.index', compact('categories', 'expenseCount', 'incomeCount'));
    }

    public function create()
    {
        $transactionCategory = new TransactionCategory();

        return view('admin.transaction_categories.create', compact('transactionCategory'));
    }

    public function store(CreateTransactionCategoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $transactionCategory = TransactionCategory::create($data);

        ActivityLogService::logCreate($transactionCategory);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dibuat.',
                'redirect' => route('admin.transaction_categories.index')
            ]);
        }

        return redirect()->route('admin.transaction_categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function show(TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->user_id && $transactionCategory->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.transaction_categories.show', compact('transactionCategory'));
    }

    public function edit(TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->user_id !== auth()->id()) {
            abort(403, 'Kategori sistem atau pengguna lain tidak dapat diubah.');
        }

        return view('admin.transaction_categories.edit', compact('transactionCategory'));
    }

    public function update(UpdateTransactionCategoryRequest $request, TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->user_id !== auth()->id()) {
            abort(403, 'Kategori sistem atau pengguna lain tidak dapat diubah.');
        }

        $data = $request->validated();

        $oldValues = $transactionCategory->getOriginal();

        $transactionCategory->update($data);

        ActivityLogService::logUpdate($transactionCategory, $oldValues);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui.',
                'redirect' => route('admin.transaction_categories.index')
            ]);
        }

        return redirect()->route('admin.transaction_categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->user_id !== auth()->id()) {
            abort(403, 'Kategori sistem atau pengguna lain tidak dapat dihapus.');
        }

        ActivityLogService::logDelete($transactionCategory);

        $transactionCategory->delete();
        return redirect()->route('admin.transaction_categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
