<?php

namespace App\Http\Controllers;

use App\Models\CategoryBudget;
use App\Models\TransactionCategory;
use App\Services\ActivityLogService;
use App\Services\CashSummaryService;
use Illuminate\Http\Request;

class CategoryBudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-cash_transactions')->only(['list']);
        $this->middleware('permission:create-cash_transactions')->only(['updateBudget']);
    }

    public function list(Request $request)
    {
        $userId = auth()->id();
        $month = $request->filled('month') ? (int) $request->get('month') : (int) now()->format('n');
        $year = $request->filled('year') ? (int) $request->get('year') : (int) now()->format('Y');

        $summaryService = app(CashSummaryService::class);
        $progress = $summaryService->getBudgetProgress($userId, $month, $year);

        // Get all available expense categories for setting budgets
        $expenseCategories = TransactionCategory::forUser($userId)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->get();

        $existingBudgets = CategoryBudget::where('user_id', $userId)
            ->where(function ($q) use ($month, $year) {
                $q->where(function ($sub) use ($month, $year) {
                    $sub->where('month', $month)->where('year', $year);
                })->orWhere(function ($sub) {
                    $sub->whereNull('month')->whereNull('year');
                });
            })
            ->pluck('amount', 'category_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'expense_categories' => $expenseCategories,
            'existing_budgets' => $existingBudgets,
        ]);
    }

    public function updateBudget(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:transaction_categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:2099',
        ]);

        $userId = auth()->id();
        $categoryId = (int) $request->category_id;
        $amount = (float) $request->amount;
        $month = $request->filled('month') ? (int) $request->month : null;
        $year = $request->filled('year') ? (int) $request->year : null;

        // Verify category is accessible by user
        $category = TransactionCategory::forUser($userId)->where('id', $categoryId)->firstOrFail();

        if ($amount <= 0) {
            // Delete budget if set to 0
            CategoryBudget::where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->where('month', $month)
                ->where('year', $year)
                ->delete();

            $message = "Target anggaran untuk {$category->name} berhasil dihapus.";
        } else {
            CategoryBudget::updateOrCreate(
                [
                    'user_id' => $userId,
                    'category_id' => $categoryId,
                    'month' => $month,
                    'year' => $year,
                ],
                [
                    'amount' => $amount,
                ]
            );

            $message = "Target anggaran {$category->name} berhasil diatur ke Rp " . number_format($amount, 0, ',', '.');
        }

        ActivityLogService::logCustom([
            'action' => 'Set Budget Limit',
            'model_type' => CategoryBudget::class,
            'model_id' => $categoryId,
            'user_id' => $userId,
            'description' => "Mengatur target anggaran kategori {$category->name} sebesar Rp " . number_format($amount, 0, ',', '.'),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
