<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CashTransaction;
use App\Http\Controllers\CashTransactionController;
use App\Models\User;
use App\Models\Role;

class CashTransactionTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.cash_transactions.index'));

        $response->assertStatus(200);
    }

    /**
     * Test export downloads excel file.
     */
    public function test_export_downloads_excel_file(): void
    {
        $category = \App\Models\TransactionCategory::factory()->create(['type' => 'income']);
        CashTransaction::create([
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 500000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Gaji freelance',
        ]);

        $response = $this->actingAs($this->user)->get(route('admin.cash_transactions.export', ['period' => 'this_month']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.cash_transactions.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new cashtransaction.
     */
    public function test_store_creates_new_cash_transaction(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.cash_transactions.store'), $data);

        $response->assertRedirect(route('admin.cash_transactions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cash_transactions', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method assigns authenticated user_id automatically.
     */
    public function test_store_assigns_authenticated_user_id(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.cash_transactions.store'), $data);

        $response->assertRedirect(route('admin.cash_transactions.index'));

        $this->assertDatabaseHas('cash_transactions', [
            'category_id' => $data['category_id'],
            'amount' => $data['amount'],
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.cash_transactions.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays cashtransaction details.
     */
    public function test_show_page_displays_cash_transaction_details(): void
    {
        $cashTransaction = CashTransaction::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.cash_transactions.show', $cashTransaction));

        $response->assertStatus(200);
        $response->assertViewHas('cashTransaction');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $cashTransaction = CashTransaction::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.cash_transactions.edit', $cashTransaction));

        $response->assertStatus(200);
        $response->assertViewHas('cashTransaction');
    }

    /**
     * Test update method updates cashtransaction.
     */
    public function test_update_modifies_cash_transaction(): void
    {
        $cashTransaction = CashTransaction::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.cash_transactions.update', $cashTransaction), $data);

        $response->assertRedirect(route('admin.cash_transactions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cash_transactions', array_merge(
            ['id' => $cashTransaction->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $cashTransaction = CashTransaction::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.cash_transactions.update', $cashTransaction), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test store method handles proof file upload.
     */
    public function test_store_handles_proof_file_upload(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $data = $this->getValidCreateData();
        $data['proof'] = \Illuminate\Http\UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($this->user)->post(route('admin.cash_transactions.store'), $data);

        $response->assertRedirect(route('admin.cash_transactions.index'));
        $transaction = CashTransaction::latest('id')->first();

        $this->assertNotNull($transaction->proof);
        $this->assertNotNull($transaction->proof_url);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($transaction->proof);
    }

    /**
     * Test destroy method deletes cashtransaction and its proof file.
     */
    public function test_destroy_deletes_cash_transaction(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $data = $this->getValidCreateData();
        $data['proof'] = \Illuminate\Http\UploadedFile::fake()->image('receipt_to_delete.jpg');

        $this->actingAs($this->user)->post(route('admin.cash_transactions.store'), $data);
        $cashTransaction = CashTransaction::latest('id')->first();
        $proofPath = $cashTransaction->proof;

        $response = $this->actingAs($this->user)->delete(route('admin.cash_transactions.destroy', $cashTransaction));

        $response->assertRedirect(route('admin.cash_transactions.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('cash_transactions', ['id' => $cashTransaction->id]);
        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($proofPath);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.cash_transactions.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a cashtransaction.
     */
    protected function getValidCreateData(): array
    {
        $category = \App\Models\TransactionCategory::factory()->create(['type' => 'income']);

        return [
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 100000,
            'transaction_date' => '2026-08-27',
            'note' => 'Test transaction',
        ];
    }

    /**
     * Get valid data for updating a cashtransaction.
     */
    protected function getValidUpdateData(): array
    {
        $category = \App\Models\TransactionCategory::factory()->create(['type' => 'expense']);

        return [
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 150000,
            'transaction_date' => '2026-08-27',
            'note' => 'Updated test transaction',
        ];
    }

    /**
     * Get data for database assertion (excluding non-database fields).
     */
    protected function getDatabaseAssertionData(array $data): array
    {
        // Remove fields that are not stored in database (e.g., password confirmation)
        $excludedFields = ['password_confirmation', '_token', '_method'];

        $result = array_filter($data, function ($key) use ($excludedFields) {
            return !in_array($key, $excludedFields);
        }, ARRAY_FILTER_USE_KEY);

        if (isset($result['transaction_date'])) {
            $result['transaction_date'] = \Carbon\Carbon::parse($result['transaction_date'])->format('Y-m-d 00:00:00');
        }

        return $result;
    }
}
