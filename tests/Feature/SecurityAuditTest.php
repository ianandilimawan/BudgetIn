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
}
