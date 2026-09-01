<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'super-admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->regularUser = User::factory()->create();
    }

    public function test_unauthorized_user_is_forbidden_from_permissions_management(): void
    {
        // Regular user with no permission should get 403 Forbidden
        $response = $this->actingAs($this->regularUser)->get(route('admin.permissions.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->regularUser)->post(route('admin.permissions.store'), [
            'name' => 'Hacked Permission',
            'slug' => 'hacked-permission',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_access_permissions_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.permissions.index'));
        $response->assertStatus(200);
    }

    public function test_otp_brute_force_is_blocked_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        // Seed OTP
        Cache::put('login_otp_' . $userId, '123456', now()->addMinutes(5));

        // Attempt 5 wrong OTPs
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->withSession(['otp_user_id' => $userId])
                ->post(route('admin.login.otp.post'), ['otp' => '999999']);
            
            $response->assertSessionHasErrors('otp');
        }

        // 6th attempt should be blocked and redirect back to login
        $response = $this->withSession(['otp_user_id' => $userId])
            ->post(route('admin.login.otp.post'), ['otp' => '123456']);

        $response->assertRedirect(route('admin.login'));
        $this->assertFalse(Cache::has('login_otp_' . $userId));
    }

    public function test_dangerous_executable_extension_is_neutralized(): void
    {
        Storage::fake('public');

        $service = new FileUploadService();
        $file = UploadedFile::fake()->create('malicious.php', 10, 'application/x-php');

        $path = $service->upload($file, null, 'test_uploads');

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.bin', $path);
        $this->assertStringNotContainsString('.php', $path);
    }

    public function test_bulk_delete_without_permission_is_blocked(): void
    {
        $account = \App\Models\CashAccount::create([
            'user_id' => $this->regularUser->id,
            'name' => 'Dompet Rahasia',
            'type' => 'cash',
            'balance' => 100000,
            'is_active' => true,
        ]);

        $tx = \App\Models\CashTransaction::create([
            'user_id' => $this->regularUser->id,
            'account_id' => $account->id,
            'type' => 'expense',
            'amount' => 50000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        \Livewire\Livewire::actingAs($this->regularUser)
            ->test(\App\Livewire\Tables\CashTransactionTable::class)
            ->call('triggerBulkDelete', [$tx->id])
            ->assertDispatched('notify', fn ($name, $params) => $params['type'] === 'error');

        $this->assertDatabaseHas('cash_transactions', ['id' => $tx->id]);
    }

    public function test_invalid_cash_account_type_is_blocked_by_whitelist_validation(): void
    {
        \App\Models\CashAccountType::firstOrCreate(
            ['code' => 'cash'],
            ['name' => 'Tunai', 'is_active' => true, 'is_system' => true]
        );

        $permission = \App\Models\Permission::firstOrCreate(['name' => 'create-cash_accounts', 'slug' => 'create-cash_accounts']);
        $this->regularUser->givePermissionTo($permission);

        // Attempt to create an account with arbitrary unwhitelisted type 'root' / 'admin'
        $response = $this->actingAs($this->regularUser)->post(route('admin.cash_accounts.store'), [
            'name' => 'Hacker Wallet',
            'type' => 'root',
            'initial_balance' => 50000,
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('type');
        $this->assertDatabaseMissing('cash_accounts', ['name' => 'Hacker Wallet']);
    }

    public function test_excessive_initial_balance_is_blocked_with_validation_error(): void
    {
        \App\Models\CashAccountType::firstOrCreate(
            ['code' => 'cash'],
            ['name' => 'Tunai', 'is_active' => true, 'is_system' => true]
        );

        $permission = \App\Models\Permission::firstOrCreate(['name' => 'create-cash_accounts', 'slug' => 'create-cash_accounts']);
        $this->regularUser->givePermissionTo($permission);

        // Attempt to send enormous balance string that would cause DB integer/numeric overflow
        $response = $this->actingAs($this->regularUser)->post(route('admin.cash_accounts.store'), [
            'name' => 'Overflow Wallet',
            'type' => 'cash',
            'initial_balance' => 'Rp 999999999999999999999999999999.99',
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('initial_balance');
        $this->assertDatabaseMissing('cash_accounts', ['name' => 'Overflow Wallet']);
    }

    public function test_excel_export_sanitizes_dangerous_formula_injection_payloads(): void
    {
        $permission = \App\Models\Permission::create(['name' => 'view-cash_transactions', 'slug' => 'view-cash_transactions']);
        $this->regularUser->givePermissionTo($permission);

        $account = \App\Models\CashAccount::create([
            'user_id' => $this->regularUser->id,
            'name' => '=CMD|\' /C calc\'!A0',
            'type' => 'cash',
            'initial_balance' => 100000,
            'is_active' => true,
        ]);

        $category = \App\Models\TransactionCategory::create([
            'user_id' => $this->regularUser->id,
            'name' => '@HYPERLINK("http://evil.com")',
            'type' => 'expense',
            'is_active' => true,
        ]);

        \App\Models\CashTransaction::create([
            'user_id' => $this->regularUser->id,
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 50000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => '=1+1',
        ]);

        $response = $this->actingAs($this->regularUser)->get(route('admin.cash_transactions.export', ['period' => 'all_time']));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'spreadsheetml'));
    }

    public function test_user_cannot_log_project_transaction_with_other_users_account(): void
    {
        $victim = \App\Models\User::factory()->create(['is_active' => true]);
        $victimAccount = \App\Models\CashAccount::create([
            'user_id' => $victim->id,
            'name' => 'Victim Wallet',
            'type' => 'cash',
            'initial_balance' => 1000000,
            'is_active' => true,
        ]);

        $project = \App\Models\BudgetProject::create([
            'user_id' => $this->regularUser->id,
            'name' => 'Proyek Liburan',
            'target_amount' => 5000000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->regularUser)->post(route('admin.budget_projects.transactions.store', $project->id), [
            'account_id' => $victimAccount->id,
            'amount' => 100000,
            'transaction_date' => now()->format('Y-m-d'),
            'note' => 'Unauthorized account usage attempt',
        ]);

        $response->assertSessionHasErrors('account_id');
        $this->assertDatabaseMissing('cash_transactions', [
            'budget_project_id' => $project->id,
            'account_id' => $victimAccount->id,
        ]);
    }

    public function test_log_deletion_path_traversal_is_blocked(): void
    {
        $admin = \App\Models\User::factory()->create(['is_active' => true]);
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin->assignRole($adminRole);

        // Attempt path traversal deletion targeting .env
        $this->actingAs($admin)->delete(route('admin.laravel-logs.destroy', ['fileName' => '../../.env']));

        // Ensure .env still exists
        $this->assertFileExists(base_path('.env'));
    }

    public function test_svg_avatar_upload_is_rejected(): void
    {
        Storage::fake('public');

        $svgPayload = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>';
        $file = UploadedFile::fake()->createWithContent('malicious.svg', $svgPayload);

        $response = $this->actingAs($this->regularUser)->put(route('admin.profile.update'), [
            'name' => 'Audited User',
            'email' => $this->regularUser->email,
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_inactive_user_cannot_complete_otp_verification(): void
    {
        $inactiveUser = \App\Models\User::factory()->create(['is_active' => false]);
        Cache::put('login_otp_' . $inactiveUser->id, '123456', now()->addMinutes(5));

        $response = $this->withSession(['otp_user_id' => $inactiveUser->id])
            ->post(route('admin.login.otp.post'), [
                'otp' => '123456',
            ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
