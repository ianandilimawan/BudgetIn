<?php

namespace App\Services;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\CategoryBudget;
use App\Models\RecurringTransaction;
use App\Models\TransactionCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialHealthService
{
    protected CashSummaryService $summaryService;

    public function __construct(CashSummaryService $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    /**
     * Calculate comprehensive financial health score & breakdown for a given user and month/year.
     */
    public function calculateFinancialHealth(int $userId, ?int $month = null, ?int $year = null): array
    {
        $month = $month ?? (int) now()->format('n');
        $year = $year ?? (int) now()->format('Y');

        // 1. Current Month Summary
        $monthlySummary = $this->summaryService->getMonthlySummary($month, $year, $userId);
        $totalIncome = (float) $monthlySummary['total_income'];
        $totalExpense = (float) $monthlySummary['total_expense'];
        $netSavings = (float) $monthlySummary['net_savings'];

        // 2. Total Liquid Cash Balance Across Active Accounts
        $accountBalances = $this->summaryService->getAccountBalances($userId);
        $totalLiquidBalance = (float) ($accountBalances['total_wealth'] ?? 0.0);

        // 3. Average Monthly Expense (last 3 months to avoid single-month bias)
        $pastExpenses = [];
        for ($i = 1; $i <= 3; $i++) {
            $pastDate = Carbon::createFromDate($year, $month, 1)->subMonths($i);
            $summary = $this->summaryService->getMonthlySummary((int) $pastDate->format('n'), (int) $pastDate->format('Y'), $userId);
            if ($summary['total_expense'] > 0) {
                $pastExpenses[] = (float) $summary['total_expense'];
            }
        }
        $averageMonthlyExpense = count($pastExpenses) > 0
            ? (array_sum($pastExpenses) / count($pastExpenses))
            : ($totalExpense > 0 ? $totalExpense : 0.0);

        // 4. Category Budget Discipline
        $budgetProgress = $this->summaryService->getBudgetProgress($userId, $month, $year);
        $budgetCategories = $budgetProgress['categories'] ?? [];
        $totalBudgetsCount = count($budgetCategories);
        $overBudgetCount = 0;
        foreach ($budgetCategories as $bCat) {
            if (!empty($bCat['is_over_budget'])) {
                $overBudgetCount++;
            }
        }
        $budgetTotalLimit = (float) ($budgetProgress['total_budget'] ?? 0.0);
        $budgetTotalSpent = (float) ($budgetProgress['total_spent'] ?? 0.0);

        // 5. Recurring Transactions Burden
        $activeRecurring = RecurringTransaction::forUser($userId)
            ->where('is_active', true)
            ->where('type', 'expense')
            ->get();
        $monthlyRecurringAmount = 0.0;
        foreach ($activeRecurring as $recurring) {
            $amount = (float) $recurring->amount;
            switch ($recurring->frequency) {
                case 'daily':
                    $monthlyRecurringAmount += $amount * 30;
                    break;
                case 'weekly':
                    $monthlyRecurringAmount += $amount * 4.3;
                    break;
                case 'monthly':
                    $monthlyRecurringAmount += $amount;
                    break;
                case 'yearly':
                    $monthlyRecurringAmount += $amount / 12;
                    break;
                default:
                    $monthlyRecurringAmount += $amount;
                    break;
            }
        }

        // ==========================================
        // PILAR 1: Rasio Tabungan (Savings Rate) - Bobot 35%
        // Target Ideal: >= 20% dari Total Pemasukan
        // ==========================================
        $savingsRate = 0.0;
        if ($totalIncome > 0) {
            $savingsRate = round((($totalIncome - $totalExpense) / $totalIncome) * 100, 1);
            if ($savingsRate >= 20) {
                $pillar1Score = 100;
            } elseif ($savingsRate > 0) {
                $pillar1Score = (int) round(($savingsRate / 20) * 100);
            } else {
                // Negative savings (defisit)
                $pillar1Score = max(0, 50 - (int) round(abs($savingsRate) * 1.5));
            }
        } else {
            // Belum ada pemasukan yang dicatat bulan ini
            $pillar1Score = $totalExpense > 0 ? 30 : 70;
        }
        $pillar1Score = max(0, min(100, $pillar1Score));

        // ==========================================
        // PILAR 2: Kepatuhan Anggaran (Budget Discipline) - Bobot 25%
        // ==========================================
        if ($totalBudgetsCount > 0) {
            $adherencePercentage = round((($totalBudgetsCount - $overBudgetCount) / $totalBudgetsCount) * 100, 1);
            $pillar2Score = (int) $adherencePercentage;
            // Penalti tambahan jika total belanja kategori melampaui total anggaran
            if ($budgetTotalLimit > 0 && $budgetTotalSpent > $budgetTotalLimit) {
                $overRatio = ($budgetTotalSpent - $budgetTotalLimit) / $budgetTotalLimit;
                $pillar2Score = max(0, (int) round($pillar2Score - ($overRatio * 40)));
            }
        } else {
            // Jika user belum set budget, evaluasi berdasarkan rasio pengeluaran vs pemasukan
            if ($totalIncome > 0) {
                $expenseRatio = ($totalExpense / $totalIncome);
                $pillar2Score = $expenseRatio <= 0.7 ? 85 : ($expenseRatio <= 0.9 ? 70 : 40);
            } else {
                $pillar2Score = 75; // Default netral
            }
        }
        $pillar2Score = max(0, min(100, $pillar2Score));

        // ==========================================
        // PILAR 3: Ketahanan Dana Darurat (Liquidity Runway) - Bobot 25%
        // Target Ideal: Saldo mampu bertahan 3 - 6 bulan pengeluaran
        // ==========================================
        $runwayMonths = 0.0;
        if ($averageMonthlyExpense > 0) {
            $runwayMonths = round($totalLiquidBalance / $averageMonthlyExpense, 1);
            if ($runwayMonths >= 6.0) {
                $pillar3Score = 100;
            } elseif ($runwayMonths >= 3.0) {
                $pillar3Score = 80 + (int) round((($runwayMonths - 3.0) / 3.0) * 20);
            } elseif ($runwayMonths >= 1.0) {
                $pillar3Score = 50 + (int) round((($runwayMonths - 1.0) / 2.0) * 30);
            } elseif ($runwayMonths > 0) {
                $pillar3Score = (int) round($runwayMonths * 50);
            } else {
                $pillar3Score = 10;
            }
        } else {
            $pillar3Score = $totalLiquidBalance > 0 ? 90 : 50;
            $runwayMonths = $totalLiquidBalance > 0 ? 6.0 : 0.0;
        }
        $pillar3Score = max(0, min(100, $pillar3Score));

        // ==========================================
        // PILAR 4: Stabilitas Arus Kas & Beban Rutin (Cash Flow Stability) - Bobot 15%
        // ==========================================
        if ($totalIncome > 0) {
            $recurringRatio = ($monthlyRecurringAmount / $totalIncome) * 100;
            if ($recurringRatio <= 30) {
                $pillar4Score = 100;
            } elseif ($recurringRatio <= 50) {
                $pillar4Score = 80;
            } else {
                $pillar4Score = max(20, 100 - (int) round($recurringRatio));
            }
        } else {
            $pillar4Score = 75;
        }
        $pillar4Score = max(0, min(100, $pillar4Score));

        // ==========================================
        // SKOR AKHIR & STATUS KESEHATAN
        // ==========================================
        $overallScore = (int) round(
            ($pillar1Score * 0.35) +
            ($pillar2Score * 0.25) +
            ($pillar3Score * 0.25) +
            ($pillar4Score * 0.15)
        );
        $overallScore = max(0, min(100, $overallScore));

        if ($overallScore >= 80) {
            $statusLabel = 'Sangat Sehat';
            $statusColor = 'emerald';
            $statusDescription = 'Kondisi finansial Anda sangat prima dengan rasio tabungan dan cadangan kas yang solid.';
        } elseif ($overallScore >= 65) {
            $statusLabel = 'Sehat & Terkendali';
            $statusColor = 'teal';
            $statusDescription = 'Arus kas Anda stabil dan terkontrol dengan ruang untuk mengoptimalkan tabungan.';
        } elseif ($overallScore >= 50) {
            $statusLabel = 'Waspada';
            $statusColor = 'amber';
            $statusDescription = 'Perhatikan pos pengeluaran dan disiplin anggaran agar saldo tabungan tidak tergerus.';
        } else {
            $statusLabel = 'Perlu Evaluasi';
            $statusColor = 'rose';
            $statusDescription = 'Pengeluaran melebihi laju pemasukan. Diperlukan penyesuaian belanja dan penghematan segera.';
        }

        // Kategori Pengeluaran Terbesar Bulan Ini
        $topExpenses = CashTransaction::forUser($userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->select('category_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->with('category')
            ->take(3)
            ->get()
            ->map(function ($item) use ($totalExpense) {
                $categoryName = $item->category ? $item->category->name : 'Lainnya';
                $amount = (float) $item->total_amount;
                $pct = $totalExpense > 0 ? round(($amount / $totalExpense) * 100, 1) : 0;
                return [
                    'name' => $categoryName,
                    'amount' => $amount,
                    'percentage' => $pct,
                ];
            })->toArray();

        return [
            'month' => $month,
            'year' => $year,
            'month_name' => Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y'),
            'overall_score' => $overallScore,
            'status_label' => $statusLabel,
            'status_color' => $statusColor,
            'status_description' => $statusDescription,
            'pillars' => [
                'savings_rate' => [
                    'name' => 'Rasio Tabungan',
                    'score' => $pillar1Score,
                    'value_formatted' => ($savingsRate >= 0 ? '+' : '') . $savingsRate . '%',
                    'target' => '≥ 20%',
                    'weight' => '35%',
                    'is_healthy' => $savingsRate >= 20,
                ],
                'budget_discipline' => [
                    'name' => 'Kepatuhan Anggaran',
                    'score' => $pillar2Score,
                    'value_formatted' => $totalBudgetsCount > 0 ? (($totalBudgetsCount - $overBudgetCount) . '/' . $totalBudgetsCount . ' Pos Aman') : 'Terkontrol',
                    'target' => '100% Pos Aman',
                    'weight' => '25%',
                    'is_healthy' => $overBudgetCount === 0,
                ],
                'emergency_runway' => [
                    'name' => 'Dana Darurat / Runway',
                    'score' => $pillar3Score,
                    'value_formatted' => $runwayMonths . ' Bulan',
                    'target' => '3 - 6 Bulan',
                    'weight' => '25%',
                    'is_healthy' => $runwayMonths >= 3.0,
                ],
                'cashflow_stability' => [
                    'name' => 'Stabilitas Arus Kas',
                    'score' => $pillar4Score,
                    'value_formatted' => $totalIncome > 0 ? (round(($totalExpense / $totalIncome) * 100) . '% Belanja') : 'Netral',
                    'target' => '< 70% Belanja',
                    'weight' => '15%',
                    'is_healthy' => $pillar4Score >= 75,
                ],
            ],
            'metrics' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'net_savings' => $netSavings,
                'total_liquid_balance' => $totalLiquidBalance,
                'savings_rate_pct' => $savingsRate,
                'runway_months' => $runwayMonths,
                'over_budget_count' => $overBudgetCount,
                'total_budgets_count' => $totalBudgetsCount,
                'top_expense_categories' => $topExpenses,
                'monthly_recurring_amount' => $monthlyRecurringAmount,
            ],
        ];
    }
}
