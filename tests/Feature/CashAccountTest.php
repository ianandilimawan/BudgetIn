<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CashAccount;
use App\Models\User;

class CashAccountTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.cash_accounts.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.cash_accounts.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new cashaccount.
     */
     public function test_store_creates_new_cash_account(): void
     {
         $data = $this->getValidCreateData();

         $response = $this->actingAs($this->user)->post(route('admin.cash_accounts.store'), $data);

         $response->assertRedirect(route('admin.cash_accounts.index'));
         $response->assertSessionHas('success');

         $this->assertDatabaseHas('cash_accounts', $this->getDatabaseAssertionData($data));
     }

    /**
     * Test store method accepts custom dynamic account type.
     */
    public function test_store_accepts_custom_account_type(): void
    {
        $customType = \App\Models\CashAccountType::create([
            'name' => 'Koperasi Simpan Pinjam',
            'code' => 'koperasi',
        ]);

        $data = [
            'name' => 'Koperasi Pegawai',
            'type' => 'koperasi',
            'initial_balance' => 2000000,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)->post(route('admin.cash_accounts.store'), $data);

        $response->assertRedirect(route('admin.cash_accounts.index'));
        $this->assertDatabaseHas('cash_accounts', [
            'name' => 'Koperasi Pegawai',
            'type' => 'koperasi',
        ]);

        $account = CashAccount::where('name', 'Koperasi Pegawai')->first();
        $this->assertEquals('Koperasi Simpan Pinjam', $account->type_name);
    }

    /**
     * Test cash account type API endpoints.
     */
    public function test_cash_account_type_crud_endpoints(): void
    {
        // Store
        $response = $this->actingAs($this->user)->postJson(route('admin.cash_account_types.store'), [
            'name' => 'Crypto Wallet',
            'description' => 'Dompet aset kripto',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('cash_account_types', ['name' => 'Crypto Wallet', 'code' => 'crypto_wallet']);

        $type = \App\Models\CashAccountType::where('code', 'crypto_wallet')->first();

        // Update
        $updateResponse = $this->actingAs($this->user)->putJson(route('admin.cash_account_types.update', $type->id), [
            'name' => 'Crypto Exchange',
            'code' => 'crypto_exchange',
        ]);
        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('cash_account_types', ['name' => 'Crypto Exchange', 'code' => 'crypto_exchange']);

        // List
        $listResponse = $this->actingAs($this->user)->getJson(route('admin.cash_account_types.list'));
        $listResponse->assertStatus(200);
        $listResponse->assertJsonStructure(['success', 'data']);

        // Destroy
        $deleteResponse = $this->actingAs($this->user)->deleteJson(route('admin.cash_account_types.destroy', $type->id));
        $deleteResponse->assertStatus(200);
        $this->assertSoftDeleted('cash_account_types', ['id' => $type->id]);
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.cash_accounts.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays cashaccount details.
     */
    public function test_show_page_displays_cash_account_details(): void
    {
        $cashAccount = CashAccount::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.cash_accounts.show', $cashAccount));

        $response->assertStatus(200);
        $response->assertViewHas('cashAccount');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $cashAccount = CashAccount::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.cash_accounts.edit', $cashAccount));

        $response->assertStatus(200);
        $response->assertViewHas('cashAccount');
    }

    /**
     * Test update method updates cashaccount.
     */
    public function test_update_modifies_cash_account(): void
    {
        $cashAccount = CashAccount::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.cash_accounts.update', $cashAccount), $data);

        $response->assertRedirect(route('admin.cash_accounts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cash_accounts', array_merge(
            ['id' => $cashAccount->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $cashAccount = CashAccount::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.cash_accounts.update', $cashAccount), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes cashaccount.
     */
    public function test_destroy_deletes_cash_account(): void
    {
        $cashAccount = CashAccount::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.cash_accounts.destroy', $cashAccount));

        $response->assertRedirect(route('admin.cash_accounts.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('cash_accounts', ['id' => $cashAccount->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.cash_accounts.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a cashaccount.
     */
    protected function getValidCreateData(): array
    {
        return [
            'name' => 'Dompet Tunai Istri',
            'type' => 'cash',
            'initial_balance' => 100000,
            'is_active' => true,
        ];
    }

    /**
     * Get valid data for updating a cashaccount.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'name' => 'Bank BCA Tabungan',
            'type' => 'bank',
            'initial_balance' => 5000000,
            'is_active' => true,
        ];
    }

    /**
     * Get data for database assertion (excluding non-database fields).
     */
    protected function getDatabaseAssertionData(array $data): array
    {
        $excludedFields = ['password_confirmation', '_token', '_method'];

        return array_filter($data, function ($key) use ($excludedFields) {
            return !in_array($key, $excludedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
}
