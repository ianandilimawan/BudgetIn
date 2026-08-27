<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Models\CashAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashSummaryService
{
    /**
     * Resolve effective user ID.
     */
    protected function resolveUserId(?int $userId = null): ?int
    {
        return $userId ?? (auth()->check() ? auth()->id() : null);
    }

    /**
     * Parse date filter parameters into standardized start_date, end_date, and label.
     */
    public function parseDateRange(?string $period = null, ?string $startDate = null, ?string $endDate = null, ?int $month = null, ?int $year = null): array
    {
        $period = $period ?? 'this_month';
        $today = Carbon::today();
        $isAllTime = false;
        $label = 'Bulan Ini';

        if ($month && $year) {
            $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $startDate = $start->format('Y-m-d');
            $endDate = $end->format('Y-m-d');
            $period = 'specific_month';
            $label = $start->translatedFormat('F Y');
        } elseif ($startDate && $endDate) {
            $period = 'custom';
            $label = Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . Carbon::parse($endDate)->translatedFormat('d M Y');
        } else {
            switch ($period) {
                case 'today':
                    $startDate = $today->format('Y-m-d');
                    $endDate = $today->format('Y-m-d');
                    $label = 'Hari Ini';
                    break;
                case '1_week':
                case '7_days':
                    $startDate = (clone $today)->subDays(6)->format('Y-m-d');
                    $endDate = $today->format('Y-m-d');
                    $label = '7 Hari Terakhir';
                    break;
                case '30_days':
                    $startDate = (clone $today)->subDays(29)->format('Y-m-d');
                    $endDate = $today->format('Y-m-d');
                    $label = '30 Hari Terakhir';
                    break;
                case 'last_month':
                    $lastMonth = (clone $today)->subMonthNoOverflow();
                    $startDate = (clone $lastMonth)->startOfMonth()->format('Y-m-d');
                    $endDate = (clone $lastMonth)->endOfMonth()->format('Y-m-d');
                    $label = 'Bulan Lalu';
                    break;
                case 'this_year':
                    $startDate = (clone $today)->startOfYear()->format('Y-m-d');
                    $endDate = (clone $today)->endOfYear()->format('Y-m-d');
                    $label = 'Tahun Ini';
                    break;
                case 'all_time':
                    $startDate = null;
                    $endDate = null;
                    $isAllTime = true;
                    $label = 'Semua Waktu';
                    break;
                case 'this_month':
                default:
                    $period = 'this_month';
                    $startDate = (clone $today)->startOfMonth()->format('Y-m-d');
                    $endDate = (clone $today)->endOfMonth()->format('Y-m-d');
                    $label = 'Bulan Ini';
                    break;
            }
        }

        return [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_all_time' => $isAllTime,
            'label' => $label,
            'month' => $month ?? (int) $today->format('m'),
            'year' => $year ?? (int) $today->format('Y'),
        ];
    }

    /**
     * Get overall total balance (all-time income, expense, balance).
     */
    public function getBalance(?int $userId = null): array
    {
        $userId = $this->resolveUserId($userId);

        $query = CashTransaction::query();
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $totals = $query->selectRaw("
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
        ")->first();

        $income = (float) ($totals->total_income ?? 0);
        $expense = (float) ($totals->total_expense ?? 0);

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'net_balance' => $income - $expense,
        ];
    }

    /**
     * Get balances for all active accounts, including their current balance and icons.
     */
    public function getAccountBalances(?int $userId = null): array
    {
        $userId = $this->resolveUserId($userId);

        $accountQuery = CashAccount::where('is_active', true);
        if ($userId) {
            $accountQuery->where('user_id', $userId);
        }
        $accounts = $accountQuery->get();
        
        $results = [];
        $totalWealth = 0;

        foreach ($accounts as $acc) {
            $txQuery = CashTransaction::query();
            if ($userId) {
                $txQuery->where('user_id', $userId);
            }

            $income = (float) (clone $txQuery)->where('account_id', $acc->id)->where('type', 'income')->sum('amount');
            $expense = (float) (clone $txQuery)->where('account_id', $acc->id)->where('type', 'expense')->sum('amount');
            $transferOut = (float) (clone $txQuery)->where('account_id', $acc->id)->where('type', 'transfer')->sum('amount');
            $transferIn = (float) (clone $txQuery)->where('to_account_id', $acc->id)->where('type', 'transfer')->sum('amount');

            $currentBalance = (float) $acc->initial_balance + $income + $transferIn - $expense - $transferOut;
            $totalWealth += $currentBalance;

            $results[] = [
                'id' => $acc->id,
                'name' => $acc->name,
                'type' => $acc->type,
                'account_number' => $acc->account_number,
                'icon' => $acc->icon ?: '',
                'color' => $acc->color ?: 'indigo',
                'current_balance' => $currentBalance,
                'total_income' => $income,
                'total_expense' => $expense,
            ];
        }

        return [
            'accounts' => $results,
            'total_wealth' => $totalWealth,
        ];
    }

    /**
     * Get summary for a specific month and year (defaults to current month).
     */
    public function getMonthlySummary(?int $month = null, ?int $year = null, ?int $userId = null): array
    {
        $month = $month ?? (int) date('m');
        $year = $year ?? (int) date('Y');
        $userId = $this->resolveUserId($userId);

        $query = CashTransaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $totals = (clone $query)->selectRaw("
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense,
            COUNT(*) as total_count
        ")->first();

        $income = (float) ($totals->total_income ?? 0);
        $expense = (float) ($totals->total_expense ?? 0);
        $count = (int) ($totals->total_count ?? 0);

        return [
            'month' => $month,
            'year' => $year,
            'month_name' => Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y'),
            'total_income' => $income,
            'total_expense' => $expense,
            'net_savings' => $income - $expense,
            'transaction_count' => $count,
        ];
    }

    /**
     * Get monthly recap list for the last N months (defaults to 12 months),
     * including income, expense, net savings, savings rate %, status (surplus/defisit), and transaction count.
     */
    public function getMonthlyRecapList(int $limitMonths = 12, ?int $userId = null): array
    {
        $recaps = [];
        $now = Carbon::now();

        for ($i = 0; $i < $limitMonths; $i++) {
            $date = (clone $now)->subMonths($i);
            $m = (int) $date->format('m');
            $y = (int) $date->format('Y');

            $summary = $this->getMonthlySummary($m, $y, $userId);
            $income = $summary['total_income'];
            $expense = $summary['total_expense'];
            $net = $summary['net_savings'];

            // Calculate savings rate %
            $savingsRate = $income > 0 ? round(($net / $income) * 100, 1) : ($net < 0 ? -100.0 : 0.0);

            // Expense ratio %
            $expenseRatio = $income > 0 ? min(100, round(($expense / $income) * 100, 1)) : ($expense > 0 ? 100.0 : 0.0);

            $recaps[] = [
                'month' => $m,
                'year' => $y,
                'month_name' => $date->translatedFormat('F Y'),
                'short_name' => $date->translatedFormat('M Y'),
                'is_current_month' => $i === 0,
                'total_income' => $income,
                'total_expense' => $expense,
                'net_savings' => $net,
                'is_surplus' => $net >= 0,
                'savings_rate' => $savingsRate,
                'expense_ratio' => $expenseRatio,
                'transaction_count' => $summary['transaction_count'],
            ];
        }

        return $recaps;
    }

    /**
     * Get summary based on a date range array.
     */
    public function getFilteredSummary(array $range, ?int $userId = null): array
    {
        $userId = $this->resolveUserId($userId);
        $query = CashTransaction::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if (!$range['is_all_time']) {
            if ($range['start_date'] && $range['end_date']) {
                $query->whereBetween('transaction_date', [$range['start_date'], $range['end_date']]);
            } elseif ($range['start_date']) {
                $query->where('transaction_date', '>=', $range['start_date']);
            } elseif ($range['end_date']) {
                $query->where('transaction_date', '<=', $range['end_date']);
            }
        }

        $totals = (clone $query)->selectRaw("
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense,
            COUNT(*) as total_count
        ")->first();

        $income = (float) ($totals->total_income ?? 0);
        $expense = (float) ($totals->total_expense ?? 0);
        $count = (int) ($totals->total_count ?? 0);

        return [
            'label' => $range['label'],
            'start_date' => $range['start_date'],
            'end_date' => $range['end_date'],
            'period' => $range['period'],
            'total_income' => $income,
            'total_expense' => $expense,
            'net_savings' => $income - $expense,
            'transaction_count' => $count,
        ];
    }

    /**
     * Get breakdown by category for a specific type and optional month/year.
     */
    public function getCategoryBreakdown(?string $type = 'expense', ?int $month = null, ?int $year = null, ?int $userId = null): array
    {
        $userId = $this->resolveUserId($userId);

        $query = CashTransaction::query()
            ->join('transaction_categories', 'cash_transactions.category_id', '=', 'transaction_categories.id')
            ->select(
                'transaction_categories.id as category_id',
                'transaction_categories.name as category_name',
                'transaction_categories.icon as category_icon',
                'transaction_categories.type as category_type',
                DB::raw('SUM(cash_transactions.amount) as total_amount'),
                DB::raw('COUNT(cash_transactions.id) as transaction_count')
            )
            ->groupBy('transaction_categories.id', 'transaction_categories.name', 'transaction_categories.icon', 'transaction_categories.type');

        if ($userId) {
            $query->where('cash_transactions.user_id', $userId);
        }

        if ($type) {
            $query->where('cash_transactions.type', $type);
        }

        if ($month) {
            $query->whereMonth('cash_transactions.transaction_date', $month);
        }

        if ($year) {
            $query->whereYear('cash_transactions.transaction_date', $year);
        }

        $results = $query->orderByDesc('total_amount')->get();
        $grandTotal = $results->sum('total_amount');

        return $results->map(function ($item) use ($grandTotal) {
            $amount = (float) $item->total_amount;
            $percentage = $grandTotal > 0 ? round(($amount / $grandTotal) * 100, 1) : 0;

            return [
                'category_id' => $item->category_id,
                'category_name' => $item->category_name,
                'category_icon' => $item->category_icon ?? 'tag',
                'category_type' => $item->category_type,
                'total_amount' => $amount,
                'transaction_count' => (int) $item->transaction_count,
                'percentage' => $percentage,
            ];
        })->toArray();
    }

    /**
     * Get category breakdown based on a date range array.
     */
    public function getFilteredCategoryBreakdown(?string $type = 'expense', array $range = [], ?int $userId = null): array
    {
        $userId = $this->resolveUserId($userId);

        $query = CashTransaction::query()
            ->join('transaction_categories', 'cash_transactions.category_id', '=', 'transaction_categories.id')
            ->select(
                'transaction_categories.id as category_id',
                'transaction_categories.name as category_name',
                'transaction_categories.icon as category_icon',
                'transaction_categories.type as category_type',
                DB::raw('SUM(cash_transactions.amount) as total_amount'),
                DB::raw('COUNT(cash_transactions.id) as transaction_count')
            )
            ->groupBy('transaction_categories.id', 'transaction_categories.name', 'transaction_categories.icon', 'transaction_categories.type');

        if ($userId) {
            $query->where('cash_transactions.user_id', $userId);
        }

        if ($type) {
            $query->where('cash_transactions.type', $type);
        }

        if (!empty($range) && empty($range['is_all_time'])) {
            if (!empty($range['start_date']) && !empty($range['end_date'])) {
                $query->whereBetween('cash_transactions.transaction_date', [$range['start_date'], $range['end_date']]);
            } elseif (!empty($range['start_date'])) {
                $query->where('cash_transactions.transaction_date', '>=', $range['start_date']);
            } elseif (!empty($range['end_date'])) {
                $query->where('cash_transactions.transaction_date', '<=', $range['end_date']);
            }
        }

        $results = $query->orderByDesc('total_amount')->get();
        $grandTotal = $results->sum('total_amount');

        return $results->map(function ($item) use ($grandTotal) {
            $amount = (float) $item->total_amount;
            $percentage = $grandTotal > 0 ? round(($amount / $grandTotal) * 100, 1) : 0;

            return [
                'category_id' => $item->category_id,
                'category_name' => $item->category_name,
                'category_icon' => $item->category_icon ?? 'tag',
                'category_type' => $item->category_type,
                'total_amount' => $amount,
                'transaction_count' => (int) $item->transaction_count,
                'percentage' => $percentage,
            ];
        })->toArray();
    }

    /**
     * Get budget limits and spending progress for categories in a specific month/year.
     */
    public function getBudgetProgress(?int $userId = null, ?int $month = null, ?int $year = null): array
    {
        $userId = $this->resolveUserId($userId);
        $month = $month ?? (int) now()->format('n');
        $year = $year ?? (int) now()->format('Y');

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');

        // Fetch all user budgets for this month/year or global default
        $budgets = \App\Models\CategoryBudget::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where(function ($q) use ($month, $year) {
                $q->where(function ($sub) use ($month, $year) {
                    $sub->where('month', $month)->where('year', $year);
                })->orWhere(function ($sub) {
                    $sub->whereNull('month')->whereNull('year');
                });
            })
            ->with('category')
            ->get()
            ->keyBy('category_id');

        // Get spending per category for this month
        $spendingQuery = CashTransaction::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('SUM(amount) as total_spent'))
            ->groupBy('category_id')
            ->pluck('total_spent', 'category_id')
            ->toArray();

        $result = [];
        $totalBudget = 0;
        $totalSpent = 0;

        foreach ($budgets as $categoryId => $budget) {
            $category = $budget->category;
            if (!$category) continue;

            $budgetLimit = (float) $budget->amount;
            $spent = (float) ($spendingQuery[$categoryId] ?? 0);
            $remaining = max(0, $budgetLimit - $spent);
            $percentage = $budgetLimit > 0 ? round(($spent / $budgetLimit) * 100, 1) : 0;

            $status = 'safe';
            if ($percentage >= 100) {
                $status = 'over';
            } elseif ($percentage >= 80) {
                $status = 'warning';
            }

            $totalBudget += $budgetLimit;
            $totalSpent += $spent;

            $result[] = [
                'category_id' => $categoryId,
                'category_name' => $category->name,
                'category_icon' => $category->icon ?? 'tag',
                'budget_limit' => $budgetLimit,
                'budget_limit_formatted' => 'Rp ' . number_format($budgetLimit, 0, ',', '.'),
                'spent' => $spent,
                'spent_formatted' => 'Rp ' . number_format($spent, 0, ',', '.'),
                'remaining' => $remaining,
                'remaining_formatted' => 'Rp ' . number_format($remaining, 0, ',', '.'),
                'is_over_budget' => $spent > $budgetLimit,
                'over_amount' => max(0, $spent - $budgetLimit),
                'over_amount_formatted' => 'Rp ' . number_format(max(0, $spent - $budgetLimit), 0, ',', '.'),
                'percentage' => min(100, $percentage),
                'actual_percentage' => $percentage,
                'status' => $status,
            ];
        }

        // Sort: over budget first, then warning, then safe
        usort($result, function ($a, $b) {
            return $b['actual_percentage'] <=> $a['actual_percentage'];
        });

        $overallPercentage = $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0;

        return [
            'month' => $month,
            'year' => $year,
            'total_budget' => $totalBudget,
            'total_budget_formatted' => 'Rp ' . number_format($totalBudget, 0, ',', '.'),
            'total_spent' => $totalSpent,
            'total_spent_formatted' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
            'overall_percentage' => min(100, $overallPercentage),
            'percentage' => min(100, $overallPercentage),
            'actual_overall_percentage' => $overallPercentage,
            'has_budgets' => count($result) > 0,
            'categories' => $result,
        ];
    }
}
