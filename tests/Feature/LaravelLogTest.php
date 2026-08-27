<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LaravelLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'super-admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($role);
    }

    public function test_admin_can_view_laravel_logs_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laravel-logs.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_delete_log_file(): void
    {
        $testLogFile = storage_path('logs/test-delete.log');
        File::put($testLogFile, '[2026-08-24 10:00:00] local.INFO: Sample log');

        $response = $this->actingAs($this->admin)->delete(route('admin.laravel-logs.destroy', 'test-delete.log'));

        $response->assertRedirect(route('admin.laravel-logs.index'));
        $this->assertFalse(File::exists($testLogFile));
    }
}
