<?php

namespace Tests\Feature;

use App\Jobs\LogActivityJob;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create super admin user
        $role = Role::create(['name' => 'super-admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($role);
    }

    public function test_admin_can_view_activity_logs_index(): void
    {
        ActivityLog::create([
            'action' => 'create',
            'model_type' => User::class,
            'model_id' => $this->admin->id,
            'user_id' => $this->admin->id,
            'description' => 'Test log description',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Test log description');
    }

    public function test_admin_can_filter_and_search_activity_logs(): void
    {
        ActivityLog::create([
            'action' => 'create',
            'model_type' => User::class,
            'model_id' => $this->admin->id,
            'user_id' => $this->admin->id,
            'description' => 'User created sample data',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.activity-logs.index', ['action' => 'create', 'search' => 'sample']));

        $response->assertStatus(200);
        $response->assertSee('User created sample data');
    }

    public function test_activity_log_service_dispatches_job(): void
    {
        Bus::fake();

        $user = User::factory()->create();
        ActivityLogService::logCreate($user, 'User was created');

        Bus::assertDispatched(LogActivityJob::class);
    }

    public function test_activity_logs_pruning(): void
    {
        // Old log (70 days ago) inserted directly
        $oldId = DB::table('activity_logs')->insertGetId([
            'action' => 'delete',
            'model_type' => User::class,
            'model_id' => 999,
            'description' => 'Old activity log',
            'created_at' => now()->subDays(70),
        ]);

        // Recent log (5 days ago) inserted directly
        $recentId = DB::table('activity_logs')->insertGetId([
            'action' => 'delete',
            'model_type' => User::class,
            'model_id' => 1000,
            'description' => 'Recent activity log',
            'created_at' => now()->subDays(5),
        ]);

        $this->artisan('model:prune', ['--model' => [ActivityLog::class]])
            ->assertSuccessful();

        $this->assertDatabaseMissing('activity_logs', ['id' => $oldId]);
        $this->assertDatabaseHas('activity_logs', ['id' => $recentId]);
    }
}
