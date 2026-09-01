<?php

namespace App\Http\Controllers;

use App\Models\CategoryBudget;
use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Services\ActivityLogService;
use App\Services\CashSummaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryBudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-cash_transactions')->only(['index', 'list']);
        $this->middleware('permission:create-cash_transactions')->only(['updateBudget', 'batchUpdate', 'copyFromPreviousMonth']);
    }

    /**
     * Display the dedicated Category Budget Planning & Limits page.
     */
    public function index(Request $request, CashSummaryService $summaryService)
    {
        $userId = auth()->id();
        $month = $request->filled('month') ? (int) $request->get('month') : (int) date('m');
        $year = $request->filled('year') ? (int) $request->get('year') : (int) date('Y');

        if ($month < 1 || $month > 12) $month = (int) date('m');
        if ($year < 2020 || $year > 2099) $year = (int) date('Y');

        $periodDate = Carbon::createFromDate($year, $month, 1);
        $periodLabel = $periodDate->translatedFormat('F Y');

        // Progress and summary data
        $budgetProgress = $summaryService->getBudgetProgress($userId, $month, $year);

        // Get all active expense categories
        $categories = TransactionCategory::forUser($userId)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get existing budgets specifically for this month/year or global default
        $specificBudgets = CategoryBudget::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('category_id');

        $globalBudgets = CategoryBudget::where('user_id', $userId)
            ->whereNull('month')
            ->whereNull('year')
            ->get()
            ->keyBy('category_id');

        // Get spent amount per category for selected month
        $startOfMonth = $periodDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $periodDate->copy()->endOfMonth()->format('Y-m-d');

        $spendingPerCategory = CashTransaction::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('SUM(amount) as total_spent'))
            ->groupBy('category_id')
            ->pluck('total_spent', 'category_id')
            ->toArray();

        $categoryItems = [];
        $totalBudget = 0;
        $totalSpent = 0;

        foreach ($categories as $cat) {
            $budgetRecord = $specificBudgets->get($cat->id) ?? $globalBudgets->get($cat->id);
            $limit = $budgetRecord ? (float) $budgetRecord->amount : 0;
            $spent = (float) ($spendingPerCategory[$cat->id] ?? 0);
            $remaining = max(0, $limit - $spent);
            $percentage = $limit > 0 ? round(($spent / $limit) * 100, 1) : 0;

            $status = 'unbudgeted';
            if ($limit > 0) {
                if ($percentage >= 100) {
                    $status = 'over';
                } elseif ($percentage >= 80) {
                    $status = 'warning';
                } else {
                    $status = 'safe';
                }
                $totalBudget += $limit;
            }
            $totalSpent += $spent;

            $categoryItems[] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'icon' => $cat->icon ?? 'tag',
                'limit' => $limit,
                'limit_formatted' => $limit > 0 ? 'Rp ' . number_format($limit, 0, ',', '.') : 'Belum diatur',
                'spent' => $spent,
                'spent_formatted' => 'Rp ' . number_format($spent, 0, ',', '.'),
                'remaining' => $remaining,
                'remaining_formatted' => 'Rp ' . number_format($remaining, 0, ',', '.'),
                'percentage' => $percentage,
                'display_percentage' => min(100, $percentage),
                'status' => $status,
                'is_specific' => $specificBudgets->has($cat->id),
            ];
        }

        // Sort: over budget first, then warning, then safe, then unbudgeted
        usort($categoryItems, function ($a, $b) {
            $order = ['over' => 1, 'warning' => 2, 'safe' => 3, 'unbudgeted' => 4];
            $scoreA = $order[$a['status']] ?? 5;
            $scoreB = $order[$b['status']] ?? 5;
            if ($scoreA !== $scoreB) return $scoreA <=> $scoreB;
            return $b['percentage'] <=> $a['percentage'];
        });

        $remainingBudget = max(0, $totalBudget - $totalSpent);
        $overallPercentage = $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0;

        // Month navigation helpers
        $prevDate = $periodDate->copy()->subMonth();
        $nextDate = $periodDate->copy()->addMonth();

        $monthList = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthList[$m] = Carbon::createFromDate($year, $m, 1)->translatedFormat('F');
        }

        $currentYear = (int) date('Y');
        $yearList = range($currentYear - 2, $currentYear + 2);

        return view('admin.category_budgets.index', compact(
            'categoryItems',
            'totalBudget',
            'totalSpent',
            'remainingBudget',
            'overallPercentage',
            'budgetProgress',
            'month',
            'year',
            'periodLabel',
            'prevDate',
            'nextDate',
            'monthList',
            'yearList'
        ));
    }

    public function list(Request $request)
    {
        $userId = auth()->id();
        $month = $request->filled('month') ? (int) $request->get('month') : (int) now()->format('n');
        $year = $request->filled('year') ? (int) $request->get('year') : (int) now()->format('Y');

        $summaryService = app(CashSummaryService::class);
        $progress = $summaryService->getBudgetProgress($userId, $month, $year);

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
            'amount' => 'required|numeric|min:0|max:999999999999.99',
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

    /**
     * Batch update multiple category budgets for a specific month and year.
     */
    public function batchUpdate(Request $request)
    {
        $request->validate([
            'budgets' => 'required|array',
            'budgets.*.category_id' => 'required|exists:transaction_categories,id',
            'budgets.*.amount' => 'required|numeric|min:0|max:999999999999.99',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $userId = auth()->id();
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $budgets = $request->input('budgets', []);

        $savedCount = 0;
        $deletedCount = 0;

        DB::transaction(function () use ($userId, $month, $year, $budgets, &$savedCount, &$deletedCount) {
            foreach ($budgets as $item) {
                $categoryId = (int) $item['category_id'];
                $amount = (float) $item['amount'];

                // Ensure category belongs to user or is system
                $category = TransactionCategory::forUser($userId)->where('id', $categoryId)->first();
                if (!$category) continue;

                if ($amount <= 0) {
                    CategoryBudget::where('user_id', $userId)
                        ->where('category_id', $categoryId)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->delete();
                    $deletedCount++;
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
                    $savedCount++;
                }
            }
        });

        $periodName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        $message = "Target anggaran periode {$periodName} berhasil diperbarui ({$savedCount} disimpan).";

        ActivityLogService::logCustom([
            'action' => 'Batch Update Budgets',
            'model_type' => CategoryBudget::class,
            'user_id' => $userId,
            'description' => "Memperbarui target anggaran {$periodName}: {$savedCount} disimpan, {$deletedCount} dihapus.",
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.category_budgets.index', ['month' => $month, 'year' => $year]),
            ]);
        }

        return redirect()->route('admin.category_budgets.index', ['month' => $month, 'year' => $year])
            ->with('success', $message);
    }

    /**
     * Copy budget limits from the previous month to the current month.
     */
    public function copyFromPreviousMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $userId = auth()->id();
        $targetMonth = (int) $request->input('month');
        $targetYear = (int) $request->input('year');

        $targetDate = Carbon::createFromDate($targetYear, $targetMonth, 1);
        $prevDate = $targetDate->copy()->subMonth();
        $prevMonth = (int) $prevDate->format('n');
        $prevYear = (int) $prevDate->format('Y');

        $prevBudgets = CategoryBudget::where('user_id', $userId)
            ->where('month', $prevMonth)
            ->where('year', $prevYear)
            ->where('amount', '>', 0)
            ->get();

        if ($prevBudgets->isEmpty()) {
            // Fallback to global defaults if no specific previous month budgets exist
            $prevBudgets = CategoryBudget::where('user_id', $userId)
                ->whereNull('month')
                ->whereNull('year')
                ->where('amount', '>', 0)
                ->get();
        }

        if ($prevBudgets->isEmpty()) {
            $msg = "Tidak ditemukan data target anggaran dari bulan sebelumnya ({$prevDate->translatedFormat('F Y')}) untuk disalin.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $copiedCount = 0;
        DB::transaction(function () use ($userId, $targetMonth, $targetYear, $prevBudgets, &$copiedCount) {
            foreach ($prevBudgets as $budget) {
                CategoryBudget::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'category_id' => $budget->category_id,
                        'month' => $targetMonth,
                        'year' => $targetYear,
                    ],
                    [
                        'amount' => $budget->amount,
                    ]
                );
                $copiedCount++;
            }
        });

        $msg = "Berhasil menyalin {$copiedCount} target anggaran dari {$prevDate->translatedFormat('F Y')} ke {$targetDate->translatedFormat('F Y')}.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'redirect' => route('admin.category_budgets.index', ['month' => $targetMonth, 'year' => $targetYear]),
            ]);
        }

        return redirect()->route('admin.category_budgets.index', ['month' => $targetMonth, 'year' => $targetYear])
            ->with('success', $msg);
    }
}
