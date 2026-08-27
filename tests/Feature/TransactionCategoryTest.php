<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TransactionCategory;
use App\Http\Controllers\TransactionCategoryController;
use App\Models\User;
use App\Models\Role;

class TransactionCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        // Retrieve super admin user
        $this->user = User::where('email', 'admin@intechstudio.id')->first();
    }

    /**
     * Test index page is accessible.
     */
    public function test_index_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.transaction_categories.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.transaction_categories.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new transactioncategory.
     */
    public function test_store_creates_new_transaction_category(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.transaction_categories.store'), $data);

        $response->assertRedirect(route('admin.transaction_categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transaction_categories', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.transaction_categories.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays transactioncategory details.
     */
    public function test_show_page_displays_transaction_category_details(): void
    {
        $transactionCategory = TransactionCategory::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.transaction_categories.show', $transactionCategory));

        $response->assertStatus(200);
        $response->assertViewHas('transactionCategory');
    }

    /**
     * Test edit page is accessible for user's own category.
     */
    public function test_edit_page_is_accessible(): void
    {
        $transactionCategory = TransactionCategory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('admin.transaction_categories.edit', $transactionCategory));

        $response->assertStatus(200);
        $response->assertViewHas('transactionCategory');
    }

    /**
     * Test update method updates transactioncategory.
     */
    public function test_update_modifies_transaction_category(): void
    {
        $transactionCategory = TransactionCategory::factory()->create(['user_id' => $this->user->id]);
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.transaction_categories.update', $transactionCategory), $data);

        $response->assertRedirect(route('admin.transaction_categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transaction_categories', array_merge(
            ['id' => $transactionCategory->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $transactionCategory = TransactionCategory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put(route('admin.transaction_categories.update', $transactionCategory), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes transactioncategory.
     */
    public function test_destroy_deletes_transaction_category(): void
    {
        $transactionCategory = TransactionCategory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('admin.transaction_categories.destroy', $transactionCategory));

        $response->assertRedirect(route('admin.transaction_categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('transaction_categories', ['id' => $transactionCategory->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.transaction_categories.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_bulk_delete_custom_category_succeeds_and_system_category_is_protected(): void
    {
        $customCat = TransactionCategory::factory()->create(['user_id' => $this->user->id]);
        $systemCat = TransactionCategory::factory()->create(['user_id' => null]);

        // Attempt bulk delete on custom category
        \Livewire\Livewire::actingAs($this->user)
            ->test(\App\Livewire\Tables\TransactionCategoryTable::class)
            ->call('triggerBulkDelete', [$customCat->id])
            ->assertDispatched('confirm-bulk-delete');

        // Confirm delete
        \Livewire\Livewire::actingAs($this->user)
            ->test(\App\Livewire\Tables\TransactionCategoryTable::class)
            ->call('bulkDeleteConfirmed', [$customCat->id], 'App\\Models\\TransactionCategory');

        $this->assertDatabaseMissing('transaction_categories', ['id' => $customCat->id]);

        // Attempt bulk delete on system category -> error
        \Livewire\Livewire::actingAs($this->user)
            ->test(\App\Livewire\Tables\TransactionCategoryTable::class)
            ->call('triggerBulkDelete', [$systemCat->id])
            ->assertDispatched('notify', fn ($name, $params) => $params['type'] === 'error');

        $this->assertDatabaseHas('transaction_categories', ['id' => $systemCat->id]);
    }

    /**
     * Get valid data for creating a transactioncategory.
     */
    protected function getValidCreateData(): array
    {
        return [
            'name' => 'Test Transaction Category',
            'type' => 'income',
            'icon' => 'tag',
            'is_active' => true,
        ];
    }

    /**
     * Get valid data for updating a transactioncategory.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'name' => 'Updated Test Transaction Category',
            'type' => 'expense',
            'icon' => 'shopping-bag',
            'is_active' => false,
        ];
    }

    /**
     * Get data for database assertion (excluding non-database fields).
     */
    protected function getDatabaseAssertionData(array $data): array
    {
        // Remove fields that are not stored in database (e.g., password confirmation)
        $excludedFields = ['password_confirmation', '_token', '_method'];

        return array_filter($data, function ($key) use ($excludedFields) {
            return !in_array($key, $excludedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
}
