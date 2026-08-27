<?php

namespace Tests\Unit;

use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Services\CashSummaryService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashSummaryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CashSummaryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CashSummaryService();
    }

    public function test_get_balance_returns_correct_totals(): void
    {
        $catIncome = TransactionCategory::factory()->create(['type' => 'income']);
        $catExpense = TransactionCategory::factory()->create(['type' => 'expense']);

        CashTransaction::create([
            'category_id' => $catIncome->id,
            'type' => 'income',
            'amount' => 1000000,
            'transaction_date' => '2026-08-01',
        ]);

        CashTransaction::create([
            'category_id' => $catIncome->id,
            'type' => 'income',
            'amount' => 500000,
            'transaction_date' => '2026-08-05',
        ]);

        CashTransaction::create([
            'category_id' => $catExpense->id,
            'type' => 'expense',
            'amount' => 400000,
            'transaction_date' => '2026-08-10',
        ]);

        $balance = $this->service->getBalance();

        $this->assertEquals(1500000, $balance['total_income']);
        $this->assertEquals(400000, $balance['total_expense']);
        $this->assertEquals(1100000, $balance['net_balance']);
    }

    public function test_get_monthly_summary_filters_by_month_and_year(): void
    {
        $catIncome = TransactionCategory::factory()->create(['type' => 'income']);
        $catExpense = TransactionCategory::factory()->create(['type' => 'expense']);

        // August 2026 transactions
        CashTransaction::create([
            'category_id' => $catIncome->id,
            'type' => 'income',
            'amount' => 2000000,
            'transaction_date' => '2026-08-15',
        ]);

        CashTransaction::create([
            'category_id' => $catExpense->id,
            'type' => 'expense',
            'amount' => 500000,
            'transaction_date' => '2026-08-20',
        ]);

        // July 2026 transaction (should not be included in August summary)
        CashTransaction::create([
            'category_id' => $catExpense->id,
            'type' => 'expense',
            'amount' => 300000,
            'transaction_date' => '2026-07-10',
        ]);

        $summary = $this->service->getMonthlySummary(8, 2026);

        $this->assertEquals(8, $summary['month']);
        $this->assertEquals(2026, $summary['year']);
        $this->assertEquals(2000000, $summary['total_income']);
        $this->assertEquals(500000, $summary['total_expense']);
        $this->assertEquals(1500000, $summary['net_savings']);
        $this->assertEquals(2, $summary['transaction_count']);
    }

    public function test_get_category_breakdown_calculates_percentages_correctly(): void
    {
        $foodCat = TransactionCategory::factory()->create(['name' => 'Makanan', 'type' => 'expense']);
        $transportCat = TransactionCategory::factory()->create(['name' => 'Transport', 'type' => 'expense']);

        CashTransaction::create([
            'category_id' => $foodCat->id,
            'type' => 'expense',
            'amount' => 750000,
            'transaction_date' => '2026-08-10',
        ]);

        CashTransaction::create([
            'category_id' => $transportCat->id,
            'type' => 'expense',
            'amount' => 250000,
            'transaction_date' => '2026-08-12',
        ]);

        $breakdown = $this->service->getCategoryBreakdown('expense', 8, 2026);

        $this->assertCount(2, $breakdown);
        $this->assertEquals('Makanan', $breakdown[0]['category_name']);
        $this->assertEquals(750000, $breakdown[0]['total_amount']);
        $this->assertEquals(75.0, $breakdown[0]['percentage']);

        $this->assertEquals('Transport', $breakdown[1]['category_name']);
        $this->assertEquals(250000, $breakdown[1]['total_amount']);
        $this->assertEquals(25.0, $breakdown[1]['percentage']);
    }

    public function test_parse_date_range_presets(): void
    {
        // 1. this_month
        $thisMonth = $this->service->parseDateRange('this_month');
        $this->assertEquals('this_month', $thisMonth['period']);
        $this->assertEquals(now()->startOfMonth()->format('Y-m-d'), $thisMonth['start_date']);
        $this->assertEquals(now()->endOfMonth()->format('Y-m-d'), $thisMonth['end_date']);

        // 2. 7_days / 1_week
        $week = $this->service->parseDateRange('1_week');
        $this->assertEquals(now()->subDays(6)->format('Y-m-d'), $week['start_date']);
        $this->assertEquals(now()->format('Y-m-d'), $week['end_date']);

        // 3. specific month & year
        $specific = $this->service->parseDateRange(null, null, null, 7, 2026);
        $this->assertEquals('specific_month', $specific['period']);
        $this->assertEquals('2026-07-01', $specific['start_date']);
        $this->assertEquals('2026-07-31', $specific['end_date']);

        // 4. custom range
        $custom = $this->service->parseDateRange(null, '2026-05-10', '2026-05-20');
        $this->assertEquals('custom', $custom['period']);
        $this->assertEquals('2026-05-10', $custom['start_date']);
        $this->assertEquals('2026-05-20', $custom['end_date']);
    }

    public function test_get_filtered_summary(): void
    {
        $cat = TransactionCategory::factory()->create(['type' => 'expense']);

        CashTransaction::create([
            'category_id' => $cat->id,
            'type' => 'expense',
            'amount' => 150000,
            'transaction_date' => '2026-08-10',
        ]);

        CashTransaction::create([
            'category_id' => $cat->id,
            'type' => 'expense',
            'amount' => 200000,
            'transaction_date' => '2026-07-10',
        ]);

        $range = $this->service->parseDateRange(null, null, null, 8, 2026);
        $summary = $this->service->getFilteredSummary($range);

        $this->assertEquals(150000, $summary['total_expense']);
        $this->assertEquals(1, $summary['transaction_count']);
    }

    public function test_get_account_balances_calculates_wallet_balances_and_transfers(): void
    {
        $bank = \App\Models\CashAccount::factory()->create([
            'name' => 'Bank BCA',
            'type' => 'bank',
            'initial_balance' => 10000000,
        ]);

        $cashWife = \App\Models\CashAccount::factory()->create([
            'name' => 'Dompet Istri',
            'type' => 'cash',
            'initial_balance' => 0,
        ]);

        $catSalary = TransactionCategory::factory()->create(['name' => 'Gaji', 'type' => 'income']);
        $catGroceries = TransactionCategory::factory()->create(['name' => 'Belanja Dapur', 'type' => 'expense']);

        // 1. Tarik Tunai dari BCA ke Dompet Istri Rp 500.000 (Transfer)
        CashTransaction::create([
            'account_id' => $bank->id,
            'to_account_id' => $cashWife->id,
            'type' => 'transfer',
            'amount' => 500000,
            'transaction_date' => '2026-08-01',
            'note' => 'Tarik tunai bulanan',
        ]);

        // 2. Istri belanja pakai uang tunai Rp 150.000 (Expense)
        CashTransaction::create([
            'account_id' => $cashWife->id,
            'category_id' => $catGroceries->id,
            'type' => 'expense',
            'amount' => 150000,
            'transaction_date' => '2026-08-02',
            'note' => 'Sayur & beras',
        ]);

        $balances = $this->service->getAccountBalances();

        $bankData = collect($balances['accounts'])->firstWhere('id', $bank->id);
        $cashData = collect($balances['accounts'])->firstWhere('id', $cashWife->id);

        // Bank: 10jt - 500rb = 9.5jt
        $this->assertEquals(9500000, $bankData['current_balance']);

        // Cash Istri: 0 + 500rb (tarik tunai) - 150rb (belanja) = 350rb
        $this->assertEquals(350000, $cashData['current_balance']);

        // Total Kekayaan Gabungan: 9.5jt + 350rb = 9.850.000
        $this->assertEquals(9850000, $balances['total_wealth']);
    }
}
