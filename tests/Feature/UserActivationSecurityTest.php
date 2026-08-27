<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserActivationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $activeFinanceUser;
    protected User $inactiveFinanceUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->admin = User::where('email', 'admin@intechstudio.id')->first();

        $financeRole = Role::where('name', 'finance')->first();

        $this->activeFinanceUser = User::factory()->create([
            'email' => 'active.finance@example.com',
            'is_active' => true,
        ]);
        $this->activeFinanceUser->assignRole($financeRole);

        $this->inactiveFinanceUser = User::factory()->create([
            'email' => 'inactive.finance@example.com',
            'is_active' => false,
        ]);
        $this->inactiveFinanceUser->assignRole($financeRole);
    }

    /**
     * Test active user can login normally.
     */
    public function test_active_user_can_login(): void
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => $this->activeFinanceUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->activeFinanceUser);
    }

    /**
     * Test inactive user cannot login and receives informative contact admin message.
     */
    public function test_inactive_user_cannot_login_and_sees_contact_admin_message(): void
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => $this->inactiveFinanceUser->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // Ensure error message directs user to contact admin
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('dinonaktifkan', $errors[0]);
        $this->assertStringContainsString('Super Admin', $errors[0]);
    }

    /**
     * Test inactive user with an existing session is kicked out by EnsureUserIsActive middleware.
     */
    public function test_inactive_user_with_active_session_is_logged_out_by_middleware(): void
    {
        $response = $this->actingAs($this->inactiveFinanceUser)->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
        $this->assertTrue(session()->has('errors'));
    }

    /**
     * Test super admin can access finance users management page.
     */
    public function test_super_admin_can_access_finance_users_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.finance_users.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Pengguna Finance');
    }

    /**
     * Test regular finance user cannot access finance users management page.
     */
    public function test_finance_user_is_forbidden_from_finance_users_management(): void
    {
        $response = $this->actingAs($this->activeFinanceUser)->get(route('admin.finance_users.index'));
        $response->assertStatus(403);
    }

    /**
     * Test super admin can toggle finance user active status.
     */
    public function test_super_admin_can_toggle_finance_user_status(): void
    {
        $this->assertTrue($this->activeFinanceUser->is_active);

        // Deactivate
        $resDeactivate = $this->actingAs($this->admin)->post(route('admin.finance_users.toggle_status', $this->activeFinanceUser));
        $this->activeFinanceUser->refresh();
        $this->assertFalse($this->activeFinanceUser->is_active);

        // Activate
        $resActivate = $this->actingAs($this->admin)->post(route('admin.finance_users.toggle_status', $this->activeFinanceUser));
        $this->activeFinanceUser->refresh();
        $this->assertTrue($this->activeFinanceUser->is_active);
    }

    /**
     * Test super admin can update finance user details and status.
     */
    public function test_super_admin_can_update_finance_user_details(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.finance_users.update', $this->activeFinanceUser), [
            'name' => 'Updated Finance Name',
            'email' => 'updated.finance@example.com',
            'is_active' => 0,
        ]);

        $response->assertRedirect(route('admin.finance_users.index'));
        $this->activeFinanceUser->refresh();
        $this->assertEquals('Updated Finance Name', $this->activeFinanceUser->name);
        $this->assertEquals('updated.finance@example.com', $this->activeFinanceUser->email);
        $this->assertFalse($this->activeFinanceUser->is_active);
    }
}
