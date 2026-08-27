<?php

namespace Tests\Feature;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\RecurringTransaction;
use App\Models\Role;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecurringTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected CashAccount $account;
    protected TransactionCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->user = User::factory()->create();
        $financeRole = Role::where('name', 'finance')->first();
        $this->user->syncRoles([$financeRole]);

        $this->otherUser = User::factory()->create();
        $this->otherUser->syncRoles([$financeRole]);

        $this->account = CashAccount::create([
            'user_id' => $this->user->id,
            'name' => 'BCA Rekening',
            'type' => 'bank',
            'balance' => 10000000,
            'is_active' => true,
        ]);

        $this->category = TransactionCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Internet & Tagihan',
            'type' => 'expense',
            'icon' => 'bolt',
            'is_active' => true,
        ]);
    }

    public function test_user_can_view_recurring_transactions_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.recurring_transactions.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_create_recurring_transaction(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.recurring_transactions.store'), [
            'name' => 'Langganan WiFi Indihome',
            'type' => 'expense',
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'amount' => 450000,
            'frequency' => 'monthly',
            'day_of_month' => 10,
            'start_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.recurring_transactions.index'));

        $this->assertDatabaseHas('recurring_transactions', [
            'user_id' => $this->user->id,
            'name' => 'Langganan WiFi Indihome',
            'amount' => 450000,
            'day_of_month' => 10,
        ]);
    }

    public function test_user_cannot_create_recurring_transaction_with_other_users_account(): void
    {
        $otherAccount = CashAccount::create([
            'user_id' => $this->otherUser->id,
            'name' => 'Secret Vault',
            'type' => 'bank',
            'balance' => 1000000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('admin.recurring_transactions.store'), [
            'name' => 'Hack Attempt',
            'type' => 'expense',
            'category_id' => $this->category->id,
            'account_id' => $otherAccount->id,
            'amount' => 100000,
            'frequency' => 'monthly',
            'day_of_month' => 1,
            'start_date' => now()->toDateString(),
        ]);

        $response->assertStatus(404);
    }

    public function test_user_can_manually_execute_recurring_transaction_now(): void
    {
        $recurring = RecurringTransaction::create([
            'user_id' => $this->user->id,
            'name' => 'Bayar Sewa Kantor',
            'type' => 'expense',
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'amount' => 2500000,
            'frequency' => 'monthly',
            'day_of_month' => 1,
            'start_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('admin.recurring_transactions.execute_now', $recurring->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cash_transactions', [
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'amount' => 2500000,
            'type' => 'expense',
        ]);

        $recurring->refresh();
        $this->assertEquals(now()->toDateString(), $recurring->last_generated_date->toDateString());
    }

    public function test_artisan_command_generates_due_recurring_transactions(): void
    {
        $recurring = RecurringTransaction::create([
            'user_id' => $this->user->id,
            'name' => 'Gaji Rutin Admin',
            'type' => 'expense',
            'category_id' => $this->category->id,
            'account_id' => $this->account->id,
            'amount' => 5000000,
            'frequency' => 'monthly',
            'day_of_month' => (int) now()->format('j'), // Due today!
            'start_date' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        $this->artisan('app:generate-recurring-transactions')
            ->assertExitCode(0);

        $this->assertDatabaseHas('cash_transactions', [
            'user_id' => $this->user->id,
            'amount' => 5000000,
        ]);
    }
}
