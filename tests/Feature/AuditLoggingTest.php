<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleAssignment;
use App\Models\RejectionLog;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_role_assignment_is_logged_when_user_approved()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'status' => 'approved',
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create pending user
        $pendingUser = User::factory()->create([
            'status' => 'pending',
        ]);

        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Act as super admin and approve user
        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $pendingUser), [
                'role_id' => $supervisorRole->id,
            ]);

        // Assert role assignment is logged
        $roleAssignment = RoleAssignment::where('user_id', $pendingUser->id)->first();
        $this->assertNotNull($roleAssignment);
        $this->assertEquals($supervisorRole->id, $roleAssignment->role_id);
        $this->assertEquals($superAdmin->id, $roleAssignment->assigned_by);
        $this->assertNotNull($roleAssignment->assigned_at);
    }

    public function test_rejection_is_logged_when_user_rejected()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'status' => 'approved',
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create pending user
        $pendingUser = User::factory()->create([
            'status' => 'pending',
        ]);

        $rejectionReason = 'Incomplete documentation';

        // Act as super admin and reject user
        $this->actingAs($superAdmin)
            ->post(route('user-approval.reject', $pendingUser), [
                'rejection_reason' => $rejectionReason,
            ]);

        // Assert rejection is logged
        $rejectionLog = RejectionLog::where('user_id', $pendingUser->id)->first();
        $this->assertNotNull($rejectionLog);
        $this->assertEquals($rejectionReason, $rejectionLog->reason);
        $this->assertEquals($superAdmin->id, $rejectionLog->rejected_by);
    }

    public function test_super_admin_can_view_audit_logs()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'status' => 'approved',
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create a pending user and a supervisor role
        $pendingUser = User::factory()->create([
            'status' => 'pending',
        ]);
        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Approve and log a role assignment
        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $pendingUser), [
                'role_id' => $supervisorRole->id,
            ]);

        // Access audit logs
        $response = $this->actingAs($superAdmin)
            ->get(route('user-approval.audit_logs'));

        $response->assertStatus(200);
        $response->assertViewHas('roleAssignments');
        $response->assertViewHas('rejectionLogs');
    }

    public function test_audit_logs_show_correct_timestamps()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'status' => 'approved',
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create pending user
        $pendingUser = User::factory()->create([
            'status' => 'pending',
        ]);

        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Approve user
        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $pendingUser), [
                'role_id' => $supervisorRole->id,
            ]);

        // Get role assignment
        $roleAssignment = RoleAssignment::where('user_id', $pendingUser->id)->first();

        // Assert timestamp is set
        $this->assertNotNull($roleAssignment->assigned_at);
        $this->assertTrue($roleAssignment->assigned_at->isToday());
    }

    public function test_multiple_role_assignments_are_tracked()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'status' => 'approved',
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create two pending users
        $user1 = User::factory()->create(['status' => 'pending']);
        $user2 = User::factory()->create(['status' => 'pending']);

        $supervisorRole = Role::where('name', 'supervisor')->first();
        $dmoRole = Role::where('name', 'dmo')->first();

        // Approve both users with different roles
        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $user1), [
                'role_id' => $supervisorRole->id,
            ]);

        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $user2), [
                'role_id' => $dmoRole->id,
            ]);

        // Assert both are logged
        $this->assertCount(2, RoleAssignment::all());
        $this->assertCount(1, RoleAssignment::where('user_id', $user1->id)->get());
        $this->assertCount(1, RoleAssignment::where('user_id', $user2->id)->get());
    }

    public function test_multiple_rejections_are_tracked()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'status' => 'approved',
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create two pending users
        $user1 = User::factory()->create(['status' => 'pending']);
        $user2 = User::factory()->create(['status' => 'pending']);

        // Reject both users
        $this->actingAs($superAdmin)
            ->post(route('user-approval.reject', $user1), [
                'rejection_reason' => 'Reason 1',
            ]);

        $this->actingAs($superAdmin)
            ->post(route('user-approval.reject', $user2), [
                'rejection_reason' => 'Reason 2',
            ]);

        // Assert both rejections are logged
        $this->assertCount(2, RejectionLog::all());
        $this->assertCount(1, RejectionLog::where('user_id', $user1->id)->get());
        $this->assertCount(1, RejectionLog::where('user_id', $user2->id)->get());
    }

    public function test_rejection_log_contains_who_rejected()
    {
        // Create two super admins
        $superAdmin1 = User::factory()->create(['status' => 'approved']);
        $superAdmin1->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        $superAdmin2 = User::factory()->create(['status' => 'approved']);
        $superAdmin2->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create two pending users
        $user1 = User::factory()->create(['status' => 'pending']);
        $user2 = User::factory()->create(['status' => 'pending']);

        // Reject users by different super admins
        $this->actingAs($superAdmin1)
            ->post(route('user-approval.reject', $user1), [
                'rejection_reason' => 'Rejected by admin 1',
            ]);

        $this->actingAs($superAdmin2)
            ->post(route('user-approval.reject', $user2), [
                'rejection_reason' => 'Rejected by admin 2',
            ]);

        // Assert rejections are from correct admins
        $rejection1 = RejectionLog::where('user_id', $user1->id)->first();
        $rejection2 = RejectionLog::where('user_id', $user2->id)->first();

        $this->assertEquals($superAdmin1->id, $rejection1->rejected_by);
        $this->assertEquals($superAdmin2->id, $rejection2->rejected_by);
    }
}
