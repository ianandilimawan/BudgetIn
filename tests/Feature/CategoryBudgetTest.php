<?php

namespace Tests\Feature;

use App\Models\CategoryBudget;
use App\Models\Role;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Models\CashTransaction;
use App\Models\CashAccount;
use App\Services\CashSummaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryBudgetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected TransactionCategory $category;
    protected CashAccount $account;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->user = User::factory()->create();
        $financeRole = Role::where('name', 'finance')->first();
        $this->user->syncRoles([$financeRole]);

        $this->otherUser = User::factory()->create();
        $this->otherUser->syncRoles([$financeRole]);

        $this->category = TransactionCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Makanan & Minuman',
            'type' => 'expense',
            'icon' => 'utensils',
            'is_active' => true,
        ]);

        $this->account = CashAccount::create([
            'user_id' => $this->user->id,
            'name' => 'Kas Utama',
            'type' => 'cash',
            'balance' => 5000000,
            'is_active' => true,
        ]);
    }

    public function test_user_can_set_and_update_category_budget(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('admin.category_budgets.update'), [
            'category_id' => $this->category->id,
            'amount' => 1500000,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('category_budgets', [
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'amount' => 1500000,
        ]);
    }

    public function test_setting_budget_to_zero_deletes_budget_limit(): void
    {
        CategoryBudget::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'amount' => 1000000,
        ]);

        $response = $this->actingAs($this->user)->postJson(route('admin.category_budgets.update'), [
            'category_id' => $this->category->id,
            'amount' => 0,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('category_budgets', [
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);
    }

    public function test_user_cannot_set_budget_for_another_users_category(): void
    {
        $otherCategory = TransactionCategory::create([
            'user_id' => $this->otherUser->id,
            'name' => 'Private Category',
            'type' => 'expense',
            'icon' => 'tag',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->postJson(route('admin.category_budgets.update'), [
            'category_id' => $otherCategory->id,
            'amount' => 500000,
        ]);

        $response->assertStatus(404);
    }

    public function test_budget_progress_calculation_and_over_budget_detection(): void
    {
        CategoryBudget::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'amount' => 1000000,
        ]);

        // Spend 1.200.000 (Over budget)
        CashTransaction::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'type' => 'expense',
            'amount' => 1200000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $service = app(CashSummaryService::class);
        $progress = $service->getBudgetProgress($this->user->id);

        $this->assertTrue($progress['has_budgets']);
        $this->assertEquals(1000000, $progress['total_budget']);
        $this->assertEquals(1200000, $progress['total_spent']);
        $this->assertTrue($progress['categories'][0]['is_over_budget']);
        $this->assertEquals('over', $progress['categories'][0]['status']);
    }
}
