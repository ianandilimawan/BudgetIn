<?php

namespace Tests\Feature;

use App\Models\CashAccount;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.register'));
        $response->assertStatus(200);
    }

    public function test_new_user_can_register_and_receives_finance_role_and_default_cash_account(): void
    {
        $response = $this->post(route('admin.register.post'), [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'budi.santoso@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->hasRole('finance'));

        // Check that initial starter cash account was created for this user
        $starterAccount = CashAccount::where('user_id', $user->id)->first();
        $this->assertNotNull($starterAccount);
        $this->assertEquals('Dompet Kas Utama', $starterAccount->name);
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('admin.register.post'), [
            'name' => 'Duplikat User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_fails_with_mismatched_password(): void
    {
        $response = $this->post(route('admin.register.post'), [
            'name' => 'Mismatched User',
            'email' => 'mismatch@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}
