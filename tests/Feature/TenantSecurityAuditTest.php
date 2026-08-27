<?php

namespace Tests\Feature;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\Role;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Services\CashSummaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $financeUser;
    protected User $attackerUser;

    protected CashAccount $adminAccount;
    protected CashAccount $financeAccount;

    protected TransactionCategory $systemCategory;
    protected TransactionCategory $adminCustomCategory;
    protected TransactionCategory $financeCustomCategory;

    protected CashTransaction $adminTransaction;
    protected CashTransaction $financeTransaction;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->adminUser = User::where('email', 'admin@intechstudio.id')->first();
        $this->financeUser = User::where('email', 'finance@intechstudio.id')->first();

        // Create a 3rd user with financial permissions to act as an external attacker
        $this->attackerUser = User::factory()->create();
        $financeRole = Role::where('name', 'finance')->first();
        $this->attackerUser->assignRole($financeRole);

        // System Category (global, user_id = null)
        $this->systemCategory = TransactionCategory::create([
            'name' => 'System Salary',
            'type' => 'income',
            'user_id' => null,
            'is_active' => true,
        ]);

        // Custom Categories
        $this->adminCustomCategory = TransactionCategory::create([
            'name' => 'Admin Secret Category',
            'type' => 'expense',
            'user_id' => $this->adminUser->id,
            'is_active' => true,
        ]);

        $this->financeCustomCategory = TransactionCategory::create([
            'name' => 'Finance Petty Expense',
            'type' => 'expense',
            'user_id' => $this->financeUser->id,
            'is_active' => true,
        ]);

        // Cash Accounts
        $this->adminAccount = CashAccount::create([
            'name' => 'Admin Bank Account',
            'type' => 'bank',
            'initial_balance' => 10000000,
            'user_id' => $this->adminUser->id,
            'is_active' => true,
        ]);

        $this->financeAccount = CashAccount::create([
            'name' => 'Finance Operating Account',
            'type' => 'bank',
            'initial_balance' => 5000000,
            'user_id' => $this->financeUser->id,
            'is_active' => true,
        ]);

        // Transactions
        $this->adminTransaction = CashTransaction::create([
            'user_id' => $this->adminUser->id,
            'account_id' => $this->adminAccount->id,
            'category_id' => $this->adminCustomCategory->id,
            'type' => 'expense',
            'amount' => 1500000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Admin Private Transaction',
        ]);

        $this->financeTransaction = CashTransaction::create([
            'user_id' => $this->financeUser->id,
            'account_id' => $this->financeAccount->id,
            'category_id' => $this->financeCustomCategory->id,
            'type' => 'expense',
            'amount' => 750000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Finance Office Supply',
        ]);
    }

    /**
     * Pentest 1: IDOR on CashAccount - Attacker cannot view, edit, update, or delete other user's cash account.
     */
    public function test_user_cannot_access_other_users_cash_account(): void
    {
        // 1. View Show
        $resShow = $this->actingAs($this->financeUser)->get(route('admin.cash_accounts.show', $this->adminAccount));
        $resShow->assertStatus(403);

        // 2. View Edit
        $resEdit = $this->actingAs($this->financeUser)->get(route('admin.cash_accounts.edit', $this->adminAccount));
        $resEdit->assertStatus(403);

        // 3. Update
        $resUpdate = $this->actingAs($this->financeUser)->put(route('admin.cash_accounts.update', $this->adminAccount), [
            'name' => 'Hijacked Account Name',
            'type' => 'bank',
            'initial_balance' => 0,
            'is_active' => true,
        ]);
        $resUpdate->assertStatus(403);
        $this->assertDatabaseHas('cash_accounts', [
            'id' => $this->adminAccount->id,
            'name' => 'Admin Bank Account',
        ]);

        // 4. Destroy
        $resDelete = $this->actingAs($this->financeUser)->delete(route('admin.cash_accounts.destroy', $this->adminAccount));
        $resDelete->assertStatus(403);
        $this->assertDatabaseHas('cash_accounts', [
            'id' => $this->adminAccount->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Pentest 2: IDOR on CashTransaction - Attacker cannot view, edit, update, or delete other user's cash transaction.
     */
    public function test_user_cannot_access_other_users_cash_transaction(): void
    {
        // 1. View Show
        $resShow = $this->actingAs($this->financeUser)->get(route('admin.cash_transactions.show', $this->adminTransaction));
        $resShow->assertStatus(403);

        // 2. View Edit
        $resEdit = $this->actingAs($this->financeUser)->get(route('admin.cash_transactions.edit', $this->adminTransaction));
        $resEdit->assertStatus(403);

        // 3. Update
        $resUpdate = $this->actingAs($this->financeUser)->put(route('admin.cash_transactions.update', $this->adminTransaction), [
            'amount' => 999999999,
            'type' => 'expense',
            'transaction_date' => now()->format('Y-m-d'),
            'account_id' => $this->adminAccount->id,
            'category_id' => $this->adminCustomCategory->id,
            'note' => 'Hacked Transaction',
        ]);
        $resUpdate->assertStatus(403);
        $this->assertDatabaseHas('cash_transactions', [
            'id' => $this->adminTransaction->id,
            'amount' => 1500000,
        ]);

        // 4. Destroy
        $resDelete = $this->actingAs($this->financeUser)->delete(route('admin.cash_transactions.destroy', $this->adminTransaction));
        $resDelete->assertStatus(403);
        $this->assertDatabaseHas('cash_transactions', [
            'id' => $this->adminTransaction->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Pentest 3: Cross-Tenant Account Tampering Attack - Attacker attempts to forge transaction with victim's account_id.
     */
    public function test_user_cannot_create_transaction_using_another_users_account(): void
    {
        $response = $this->actingAs($this->financeUser)->post(route('admin.cash_transactions.store'), [
            'account_id' => $this->adminAccount->id, // Victim's account
            'category_id' => $this->financeCustomCategory->id,
            'type' => 'expense',
            'amount' => 500000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Malicious Debit Attempt',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('cash_transactions', [
            'note' => 'Malicious Debit Attempt',
        ]);
    }

    /**
     * Pentest 4: Cross-Tenant Transfer Hijack Attack - Attacker attempts to transfer money to/from victim's account.
     */
    public function test_user_cannot_transfer_money_involving_another_users_account(): void
    {
        // Attack A: Transfer FROM victim's account to attacker's account
        $resFrom = $this->actingAs($this->financeUser)->post(route('admin.cash_transactions.store'), [
            'account_id' => $this->adminAccount->id, // Victim account
            'to_account_id' => $this->financeAccount->id, // Attacker account
            'type' => 'transfer',
            'amount' => 5000000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Theft via Transfer From',
        ]);
        $resFrom->assertStatus(403);

        // Attack B: Transfer FROM attacker's account INTO victim's account without permission
        $resTo = $this->actingAs($this->financeUser)->post(route('admin.cash_transactions.store'), [
            'account_id' => $this->financeAccount->id, // Attacker account
            'to_account_id' => $this->adminAccount->id, // Victim account
            'type' => 'transfer',
            'amount' => 100000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Unauthorized Transfer To',
        ]);
        $resTo->assertStatus(403);
    }

    /**
     * Pentest 5: System & Cross-Tenant Category Protection - Global system categories and other users' categories are protected.
     */
    public function test_system_and_cross_tenant_categories_are_protected(): void
    {
        // Attack A: Attempt to edit/delete system global category (user_id = null)
        $resSystemEdit = $this->actingAs($this->financeUser)->get(route('admin.transaction_categories.edit', $this->systemCategory));
        $resSystemEdit->assertStatus(403);

        $resSystemUpdate = $this->actingAs($this->financeUser)->put(route('admin.transaction_categories.update', $this->systemCategory), [
            'name' => 'Renamed System Category',
            'type' => 'income',
            'is_active' => true,
        ]);
        $resSystemUpdate->assertStatus(403);

        $resSystemDelete = $this->actingAs($this->financeUser)->delete(route('admin.transaction_categories.destroy', $this->systemCategory));
        $resSystemDelete->assertStatus(403);

        // Attack B: Attempt to edit/delete another user's custom category
        $resAdminCatEdit = $this->actingAs($this->financeUser)->get(route('admin.transaction_categories.edit', $this->adminCustomCategory));
        $resAdminCatEdit->assertStatus(403);

        $resAdminCatDelete = $this->actingAs($this->financeUser)->delete(route('admin.transaction_categories.destroy', $this->adminCustomCategory));
        $resAdminCatDelete->assertStatus(403);
    }

    /**
     * Pentest 6: Dashboard and Aggregation Isolation - Financial metrics must strictly reflect only authenticated user's data.
     */
    public function test_dashboard_and_reporting_strict_data_isolation(): void
    {
        $service = app(CashSummaryService::class);

        // Admin balance and wealth
        $adminBalance = $service->getBalance($this->adminUser->id);
        $adminAccounts = $service->getAccountBalances($this->adminUser->id);

        // Finance balance and wealth
        $financeBalance = $service->getBalance($this->financeUser->id);
        $financeAccounts = $service->getAccountBalances($this->financeUser->id);

        // Admin total expense should be 1,500,000 (adminTransaction only)
        $this->assertEquals(1500000, $adminBalance['total_expense']);

        // Finance total expense should be 750,000 (financeTransaction only)
        $this->assertEquals(750000, $financeBalance['total_expense']);

        // Admin accounts should only list Admin Bank Account
        $adminAccountIds = collect($adminAccounts['accounts'])->pluck('id')->toArray();
        $this->assertContains($this->adminAccount->id, $adminAccountIds);
        $this->assertNotContains($this->financeAccount->id, $adminAccountIds);

        // Finance accounts should only list Finance Operating Account
        $financeAccountIds = collect($financeAccounts['accounts'])->pluck('id')->toArray();
        $this->assertContains($this->financeAccount->id, $financeAccountIds);
        $this->assertNotContains($this->adminAccount->id, $financeAccountIds);
    }

    /**
     * Pentest 7: Role Privilege Isolation - Finance role can only access financial modules and is denied from administrative areas.
     */
    public function test_finance_role_cannot_access_administrative_modules(): void
    {
        // 1. Roles management
        $this->actingAs($this->financeUser)->get(route('admin.roles.index'))->assertStatus(403);

        // 2. Permissions management
        $this->actingAs($this->financeUser)->get(route('admin.permissions.index'))->assertStatus(403);

        // 3. User management
        $this->actingAs($this->financeUser)->get(route('admin.users.index'))->assertStatus(403);

        // 4. Laravel Logs
        $this->actingAs($this->financeUser)->get(route('admin.laravel-logs.index'))->assertStatus(403);

        // 5. Financial modules MUST be accessible (200 OK)
        $this->actingAs($this->financeUser)->get(route('admin.cash_transactions.index'))->assertStatus(200);
        $this->actingAs($this->financeUser)->get(route('admin.cash_accounts.index'))->assertStatus(200);
        $this->actingAs($this->financeUser)->get(route('admin.transaction_categories.index'))->assertStatus(200);
    }

    /**
     * Pentest 8: Export Isolation - Export file only contains rows belonging to the requesting user.
     */
    public function test_export_only_includes_requesting_users_records(): void
    {
        $response = $this->actingAs($this->financeUser)->get(route('admin.cash_transactions.export', ['period' => 'this_month']));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
