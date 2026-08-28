<?php

namespace Tests\Feature;

use App\Models\BudgetProject;
use App\Models\BudgetProjectItem;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BudgetProjectTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CashAccount $account;
    protected TransactionCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'finance']);
        $permission = Permission::firstOrCreate(['name' => 'view-cash_transactions']);
        $role->givePermissionTo($permission);

        $this->user = User::factory()->create([
            'is_active' => true,
        ]);
        $this->user->assignRole($role);

        $this->account = CashAccount::create([
            'user_id' => $this->user->id,
            'name' => 'BCA Utama',
            'type' => 'bank',
            'opening_balance' => 60000000,
            'is_active' => true,
        ]);

        $this->category = TransactionCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Pernikahan & Acara',
            'type' => 'expense',
            'is_active' => true,
        ]);
    }

    public function test_user_can_view_budget_projects_index()
    {
        $this->actingAs($this->user);

        BudgetProject::create([
            'user_id' => $this->user->id,
            'name' => 'Rencana Pernikahan 2026',
            'icon' => '💍',
            'target_amount' => 50000000,
            'target_date' => now()->addMonths(6),
            'status' => 'active',
        ]);

        $response = $this->get(route('admin.budget_projects.index'));
        $response->assertStatus(200);
        $response->assertSee('Rencana Pernikahan 2026');
        $response->assertSee('50.000.000');
    }

    public function test_user_can_create_project_with_initial_items()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.budget_projects.store'), [
            'name' => 'Liburan ke Jepang',
            'icon' => '🏖️',
            'target_amount' => 30000000,
            'target_date' => now()->addMonths(4)->format('Y-m-d'),
            'note' => 'Target tabungan liburan 7 hari di Tokyo & Kyoto',
            'items' => [
                ['name' => 'Tiket Pesawat PP', 'target_amount' => 12000000],
                ['name' => 'Hotel & Airbnb', 'target_amount' => 10000000],
                ['name' => 'Kuliner & Belanja', 'target_amount' => 8000000],
            ],
        ]);

        $project = BudgetProject::where('name', 'Liburan ke Jepang')->first();
        $this->assertNotNull($project);
        $this->assertEquals(30000000, (float) $project->target_amount);
        $this->assertCount(3, $project->items);

        $response->assertRedirect(route('admin.budget_projects.show', $project->id));
    }

    public function test_user_can_view_project_show_dashboard_and_record_expense()
    {
        $this->actingAs($this->user);

        $project = BudgetProject::create([
            'user_id' => $this->user->id,
            'name' => 'Pernikahan Impian 50 Juta',
            'icon' => '💍',
            'target_amount' => 50000000,
            'target_date' => now()->addMonths(5),
            'status' => 'active',
        ]);

        $item1 = BudgetProjectItem::create([
            'budget_project_id' => $project->id,
            'name' => 'Dekorasi & Tenda',
            'target_amount' => 15000000,
            'spent_amount' => 0,
            'status' => 'pending',
        ]);

        $item2 = BudgetProjectItem::create([
            'budget_project_id' => $project->id,
            'name' => 'Katering 500 Porsi',
            'target_amount' => 20000000,
            'spent_amount' => 0,
            'status' => 'pending',
        ]);

        $response = $this->get(route('admin.budget_projects.show', $project->id));
        $response->assertStatus(200);
        $response->assertSee('Dekorasi & Tenda');
        $response->assertSee('Katering 500 Porsi');

        // Record a transaction for item1 (DP Dekorasi 5jt)
        $txResponse = $this->post(route('admin.budget_projects.transactions.store', $project->id), [
            'account_id' => $this->account->id,
            'budget_project_item_id' => $item1->id,
            'amount' => 5000000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'DP Dekorasi Pelaminan',
        ]);

        $txResponse->assertRedirect();

        // Check progress
        $item1->refresh();
        $this->assertEquals(5000000, (float) $item1->total_spent);
        $this->assertEquals(10000000, (float) $item1->remaining_amount);
        $this->assertEquals(33.3, (float) $item1->actual_spent_percentage);

        $project->refresh();
        $this->assertEquals(5000000, (float) $project->total_spent);
        $this->assertEquals(45000000, (float) $project->remaining_budget);
    }

    public function test_user_can_manage_project_items()
    {
        $this->actingAs($this->user);

        $project = BudgetProject::create([
            'user_id' => $this->user->id,
            'name' => 'Renovasi Kamar',
            'icon' => '🏠',
            'target_amount' => 10000000,
            'status' => 'active',
        ]);

        // Add item
        $this->post(route('admin.budget_projects.items.store', $project->id), [
            'name' => 'Cat Tembok & Wallpaper',
            'target_amount' => 3000000,
            'note' => 'Warna sage green',
        ]);

        $item = $project->items()->first();
        $this->assertNotNull($item);
        $this->assertEquals('Cat Tembok & Wallpaper', $item->name);

        // Update item
        $this->put(route('admin.budget_projects.items.update', [$project->id, $item->id]), [
            'name' => 'Cat Tembok Mewah',
            'target_amount' => 3500000,
            'status' => 'in_progress',
        ]);

        $item->refresh();
        $this->assertEquals('Cat Tembok Mewah', $item->name);
        $this->assertEquals(3500000, (float) $item->target_amount);

        // Toggle status
        $this->post(route('admin.budget_projects.items.toggle_status', [$project->id, $item->id]));
        $item->refresh();
        $this->assertEquals('completed', $item->status);

        // Delete item
        $this->delete(route('admin.budget_projects.items.destroy', [$project->id, $item->id]));
        $this->assertEquals(0, $project->items()->count());
    }

    public function test_tenant_isolation_user_cannot_access_other_users_project()
    {
        $otherUser = User::factory()->create(['is_active' => true]);
        $otherUser->assignRole('finance');

        $otherProject = BudgetProject::create([
            'user_id' => $otherUser->id,
            'name' => 'Proyek Rahasia Lain',
            'icon' => '🔒',
            'target_amount' => 100000000,
            'status' => 'active',
        ]);

        $this->actingAs($this->user);

        // Try to access other user's project
        $response = $this->get(route('admin.budget_projects.show', $otherProject->id));
        $response->assertStatus(403);

        // Try to add item to other user's project
        $addItemResponse = $this->post(route('admin.budget_projects.items.store', $otherProject->id), [
            'name' => 'Item Ilegal',
            'target_amount' => 1000000,
        ]);
        $addItemResponse->assertStatus(403);
    }
}
