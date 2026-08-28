<?php

namespace App\Http\Controllers;

use App\Services\CashSummaryService;
use App\Services\FinancialHealthService;
use App\Services\GeminiAiService;
use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Models\CashAccount;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(
        Request $request,
        CashSummaryService $summaryService,
        FinancialHealthService $healthService,
        GeminiAiService $aiService
    ) {
        $period = $request->get('period', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $month = $request->filled('month') ? (int) $request->get('month') : null;
        $year = $request->filled('year') ? (int) $request->get('year') : null;

        $dateRange = $summaryService->parseDateRange($period, $startDate, $endDate, $month, $year);

        $userId = auth()->id();

        $balance = $summaryService->getBalance($userId);
        $filteredSummary = $summaryService->getFilteredSummary($dateRange, $userId);
        $expenseBreakdown = $summaryService->getFilteredCategoryBreakdown('expense', $dateRange, $userId);
        $incomeBreakdown = $summaryService->getFilteredCategoryBreakdown('income', $dateRange, $userId);
        $accountBalances = $summaryService->getAccountBalances($userId);

        // Financial Health Score & AI Insights
        $healthMonth = $dateRange['month'] ?? (int) now()->format('n');
        $healthYear = $dateRange['year'] ?? (int) now()->format('Y');
        $financialHealth = $healthService->calculateFinancialHealth($userId, $healthMonth, $healthYear);
        $aiInsights = $aiService->getFinancialInsights($userId, $financialHealth, $request->has('refresh_ai'));

        // Query transactions for recent list based on active filter
        $recentQuery = CashTransaction::forUser($userId)->with(['category', 'account', 'toAccount', 'user']);
        if (!$dateRange['is_all_time']) {
            if ($dateRange['start_date'] && $dateRange['end_date']) {
                $recentQuery->whereBetween('transaction_date', [$dateRange['start_date'], $dateRange['end_date']]);
            }
        }
        $recentTransactions = $recentQuery->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->take(6)->get();

        // 6-month financial trend data for area chart
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m = (int) $date->format('m');
            $y = (int) $date->format('Y');
            $summary = $summaryService->getMonthlySummary($m, $y, $userId);
            $monthlyTrends[] = [
                'month_name' => $date->translatedFormat('M Y'),
                'income' => (float) $summary['total_income'],
                'expense' => (float) $summary['total_expense'],
                'net' => (float) $summary['net_savings'],
            ];
        }

        $totalCategories = TransactionCategory::forUser($userId)->where('is_active', true)->count();
        $totalTransactions = CashTransaction::forUser($userId)->count();

        // 12-Month Financial & Savings Recap List
        $monthlyRecap = $summaryService->getMonthlyRecapList(12, $userId);

        // Budget Planner Progress & Categories
        $budgetMonth = $dateRange['month'] ?? (int) now()->format('n');
        $budgetYear = $dateRange['year'] ?? (int) now()->format('Y');
        $budgetProgress = $summaryService->getBudgetProgress($userId, $budgetMonth, $budgetYear);
        $expenseCategories = TransactionCategory::forUser($userId)->where('type', 'expense')->where('is_active', true)->get();
        $incomeCategories = TransactionCategory::forUser($userId)->where('type', 'income')->where('is_active', true)->get();
        $cashAccounts = CashAccount::where('user_id', $userId)->where('is_active', true)->get();
        $existingBudgets = \App\Models\CategoryBudget::where('user_id', $userId)->pluck('amount', 'category_id')->toArray();

        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super-admin');

        $systemStats = null;
        if ($isSuperAdmin) {
            $userGrowthTrends = [];
            for ($i = 5; $i >= 0; $i--) {
                $d = now()->subMonths($i);
                $m = (int) $d->format('m');
                $y = (int) $d->format('Y');
                $count = \App\Models\User::finance()
                    ->whereMonth('created_at', $m)
                    ->whereYear('created_at', $y)
                    ->count();
                $userGrowthTrends[] = [
                    'month_name' => $d->translatedFormat('M Y'),
                    'new_users' => $count,
                ];
            }

            $systemStats = [
                'total_users' => \App\Models\User::count(),
                'active_users' => \App\Models\User::where('is_active', true)->count(),
                'inactive_users' => \App\Models\User::where('is_active', false)->count(),
                'finance_users_count' => \App\Models\User::finance()->count(),
                'active_finance_users' => \App\Models\User::finance()->where('is_active', true)->count(),
                'inactive_finance_users' => \App\Models\User::finance()->where('is_active', false)->count(),
                'new_finance_users_this_month' => \App\Models\User::finance()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'new_finance_users_this_week' => \App\Models\User::finance()->where('created_at', '>=', now()->subDays(7))->count(),
                'user_growth_trends' => $userGrowthTrends,
                'total_platform_transactions' => CashTransaction::count(),
                'total_platform_income' => (float) CashTransaction::where('type', 'income')->sum('amount'),
                'total_platform_expense' => (float) CashTransaction::where('type', 'expense')->sum('amount'),
                'total_platform_accounts' => CashAccount::count(),
                'total_platform_categories' => TransactionCategory::count(),
                'total_recurring_schedules' => \App\Models\RecurringTransaction::count(),
                'active_recurring_schedules' => \App\Models\RecurringTransaction::where('is_active', true)->count(),
                'recent_users' => \App\Models\User::finance()->latest()->take(6)->get(),
                'recent_activities' => \App\Models\ActivityLog::with('user')->latest()->take(8)->get(),
                'server_info' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'environment' => app()->environment(),
                    'db_driver' => config('database.default'),
                    'cache_driver' => config('cache.default'),
                    'queue_driver' => config('queue.default'),
                ],
            ];
        }

        return view('admin.pages.dashboard', compact(
            'isSuperAdmin',
            'systemStats',
            'balance',
            'filteredSummary',
            'dateRange',
            'expenseBreakdown',
            'incomeBreakdown',
            'accountBalances',
            'recentTransactions',
            'monthlyTrends',
            'monthlyRecap',
            'totalCategories',
            'totalTransactions',
            'budgetProgress',
            'expenseCategories',
            'incomeCategories',
            'cashAccounts',
            'existingBudgets',
            'financialHealth',
            'aiInsights'
        ));
    }

    /**
     * AJAX Endpoint to refresh AI financial insights on demand.
     */
    public function refreshFinancialAiInsights(
        Request $request,
        FinancialHealthService $healthService,
        GeminiAiService $aiService
    ) {
        $userId = auth()->id();
        $month = $request->filled('month') ? (int) $request->get('month') : (int) now()->format('n');
        $year = $request->filled('year') ? (int) $request->get('year') : (int) now()->format('Y');

        $financialHealth = $healthService->calculateFinancialHealth($userId, $month, $year);
        $aiInsights = $aiService->getFinancialInsights($userId, $financialHealth, true);

        return response()->json([
            'success' => true,
            'message' => 'Analisis keuangan AI berhasil diperbarui.',
            'financial_health' => $financialHealth,
            'ai_insights' => $aiInsights,
        ]);
    }
}
