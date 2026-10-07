<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Province;
use App\Models\District;
use App\Models\FacilityType;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Carbon\Carbon;

class RejectionRetryMechanismTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $rejectedUser;
    protected Role $supervisorRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create master data
        $organization = Organization::create([
            'name' => 'Test Organization',
            'code' => 'TEST_ORG',
            'requires_location' => false,
        ]);

        $department = Department::create(['name' => 'Test Department']);
        $designation = Designation::create(['name' => 'Test Designation']);
        $province = Province::create(['name' => 'Test Province']);
        $district = District::create(['province_id' => $province->id, 'name' => 'Test District']);
        $facilityType = FacilityType::create(['name' => 'Hospital']);
        $facility = Facility::create([
            'name' => 'Test Hospital',
            'province_id' => $province->id,
            'facility_type_id' => $facilityType->id,
            'district_id' => $district->id,
        ]);

        // Create Super Admin user
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'status' => 'approved',
            'organization_id' => $organization->id,
        ]);

        $superAdminRole = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Admin',
        ]);
        $this->superAdmin->roles()->attach($superAdminRole->id);

        // Create Supervisor role
        $this->supervisorRole = Role::create([
            'name' => 'supervisor',
            'display_name' => 'Supervisor',
        ]);

        // Create a rejected user
        $this->rejectedUser = User::create([
            'name' => 'Rejected User',
            'email' => 'rejected@test.com',
            'password' => Hash::make('password'),
            'status' => 'rejected',
            'organization_id' => $organization->id,
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'province_id' => $province->id,
            'district_id' => $district->id,
            'facility_type_id' => $facilityType->id,
            'facility_id' => $facility->id,
            'rejection_reason' => 'Incomplete information',
            'rejected_at' => now(),
            'can_reapply_at' => now()->addDays(7),
            'approved_by' => $this->superAdmin->id,
        ]);
    }

    public function test_rejected_user_cannot_reapply_before_7_days()
    {
        $this->assertTrue($this->rejectedUser->isRejected());
        $this->assertFalse($this->rejectedUser->canRetryApplication());
        $this->assertNotNull($this->rejectedUser->getDaysUntilRetry());
    }

    public function test_rejected_user_can_reapply_after_7_days()
    {
        // Update can_reapply_at to past date
        $this->rejectedUser->update([
            'can_reapply_at' => now()->subHours(1),
        ]);

        $this->assertTrue($this->rejectedUser->isRejected());
        $this->assertTrue($this->rejectedUser->canRetryApplication());
        $this->assertEquals(0, $this->rejectedUser->getDaysUntilRetry());
    }

    public function test_rejected_user_can_retry_application()
    {
        // Set reapply date to past
        $this->rejectedUser->update([
            'can_reapply_at' => now()->subHours(1),
        ]);

        $result = $this->rejectedUser->retryApplication();

        $this->assertTrue($result);
        $this->rejectedUser->refresh();
        $this->assertTrue($this->rejectedUser->isPending());
        $this->assertNull($this->rejectedUser->rejection_reason);
        $this->assertNull($this->rejectedUser->rejected_at);
        $this->assertNull($this->rejectedUser->can_reapply_at);
    }

    public function test_rejected_user_cannot_retry_if_still_in_cooldown()
    {
        $result = $this->rejectedUser->retryApplication();
        $this->assertFalse($result);
        $this->rejectedUser->refresh();
        $this->assertTrue($this->rejectedUser->isRejected());
    }

    public function test_retry_application_endpoint_works_after_cooldown()
    {
        // Set reapply date to past
        $this->rejectedUser->update([
            'can_reapply_at' => now()->subHours(1),
        ]);

        $response = $this->actingAs($this->rejectedUser)
            ->post(route('retry-application'));

        $response->assertRedirect(route('pending'));
        $response->assertSessionHas('success');

        $this->rejectedUser->refresh();
        $this->assertTrue($this->rejectedUser->isPending());
    }

    public function test_retry_application_endpoint_fails_before_cooldown()
    {
        $response = $this->actingAs($this->rejectedUser)
            ->post(route('retry-application'));

        $response->assertRedirect(route('pending'));
        $response->assertSessionHas('error');

        $this->rejectedUser->refresh();
        $this->assertTrue($this->rejectedUser->isRejected());
    }

    public function test_pending_user_view_shows_reapply_button_when_eligible()
    {
        // Set reapply date to past
        $this->rejectedUser->update([
            'can_reapply_at' => now()->subHours(1),
        ]);

        $response = $this->actingAs($this->rejectedUser)
            ->get(route('pending'));

        $response->assertStatus(200);
        $response->assertSeeText('Reapply Now');
    }

    public function test_pending_user_view_shows_days_remaining_when_not_eligible()
    {
        $response = $this->actingAs($this->rejectedUser)
            ->get(route('pending'));

        $response->assertStatus(200);
        $response->assertSeeText('day(s)');
    }

    public function test_get_days_until_retry_returns_correct_value()
    {
        $this->rejectedUser->update([
            'can_reapply_at' => now()->addDays(3),
        ]);

        $days = $this->rejectedUser->getDaysUntilRetry();
        $this->assertEquals(3, $days);
    }

    public function test_rejection_reason_is_cleared_after_retry()
    {
        $this->rejectedUser->update([
            'can_reapply_at' => now()->subHours(1),
        ]);

        $this->rejectedUser->retryApplication();
        $this->rejectedUser->refresh();

        $this->assertNull($this->rejectedUser->rejection_reason);
        $this->assertNull($this->rejectedUser->rejected_at);
    }

    public function test_approved_user_cannot_retry()
    {
        $approvedUser = User::create([
            'name' => 'Approved User',
            'email' => 'approved@test.com',
            'password' => Hash::make('password'),
            'status' => 'approved',
        ]);

        $this->assertFalse($approvedUser->canRetryApplication());
    }
}
