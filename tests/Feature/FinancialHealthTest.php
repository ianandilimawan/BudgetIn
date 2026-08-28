<?php

namespace Tests\Feature;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\CategoryBudget;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Services\FinancialHealthService;
use App\Services\GeminiAiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialHealthTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->user = User::factory()->create([
            'is_active' => true,
        ]);
        $financeRole = \App\Models\Role::where('name', 'finance')->first();
        if ($financeRole) {
            $this->user->assignRole($financeRole);
        }
    }

    public function test_financial_health_score_calculation_with_healthy_data()
    {
        $account = CashAccount::create([
            'user_id' => $this->user->id,
            'name' => 'Rekening Utama',
            'type' => 'bank',
            'initial_balance' => 20000000,
            'is_active' => true,
        ]);

        $categoryIncome = TransactionCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Gaji Bulanan',
            'type' => 'income',
            'is_active' => true,
        ]);

        $categoryExpense = TransactionCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Kebutuhan Harian',
            'type' => 'expense',
            'is_active' => true,
        ]);

        // Income: 10,000,000, Expense: 4,000,000 -> Savings: 60%
        CashTransaction::create([
            'user_id' => $this->user->id,
            'account_id' => $account->id,
            'category_id' => $categoryIncome->id,
            'type' => 'income',
            'amount' => 10000000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        CashTransaction::create([
            'user_id' => $this->user->id,
            'account_id' => $account->id,
            'category_id' => $categoryExpense->id,
            'type' => 'expense',
            'amount' => 4000000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        CategoryBudget::create([
            'user_id' => $this->user->id,
            'category_id' => $categoryExpense->id,
            'amount' => 5000000,
            'month' => (int) now()->format('n'),
            'year' => (int) now()->format('Y'),
        ]);

        $service = app(FinancialHealthService::class);
        $result = $service->calculateFinancialHealth($this->user->id);

        $this->assertIsArray($result);
        $this->assertGreaterThanOrEqual(75, $result['overall_score']);
        $this->assertEquals('Sangat Sehat', $result['status_label']);
        $this->assertArrayHasKey('pillars', $result);
        $this->assertEquals('≥ 20%', $result['pillars']['savings_rate']['target']);
    }

    public function test_gemini_ai_service_returns_rule_based_fallback_when_no_api_key()
    {
        $healthService = app(FinancialHealthService::class);
        $healthData = $healthService->calculateFinancialHealth($this->user->id);

        $aiService = app(GeminiAiService::class);
        $insights = $aiService->getFinancialInsights($this->user->id, $healthData, true);

        $this->assertIsArray($insights);
        $this->assertArrayHasKey('summary', $insights);
        $this->assertArrayHasKey('cashflow_insight', $insights);
        $this->assertArrayHasKey('budget_warning', $insights);
        $this->assertArrayHasKey('actionable_tip', $insights);
        $this->assertEquals('algorithmic', $insights['engine']);
    }

    public function test_dashboard_renders_financial_health_and_ai_insights()
    {
        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Skor Kesehatan Keuangan');
        $response->assertSee('AI Financial Insights');
    }

    public function test_refresh_financial_ai_insights_endpoint()
    {
        $response = $this->actingAs($this->user)->postJson(route('admin.financial_health.refresh'), [
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'financial_health' => [
                'overall_score',
                'status_label',
                'pillars',
            ],
            'ai_insights' => [
                'summary',
                'cashflow_insight',
                'budget_warning',
                'actionable_tip',
                'engine',
            ],
        ]);
        $this->assertTrue($response->json('success'));
    }
}
