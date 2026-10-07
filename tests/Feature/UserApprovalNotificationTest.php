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
use App\Notifications\UserApprovedNotification;
use App\Notifications\UserRejectedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserApprovalNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $approverUser;
    protected User $pendingUser;
    protected Role $testRole;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create required master data
        $organization = Organization::create([
            'name' => 'Test Organization',
            'code' => 'TEST_ORG',
            'requires_location' => false,
        ]);

        $department = Department::create([
            'name' => 'Test Department',
        ]);

        $designation = Designation::create([
            'name' => 'Test Designation',
        ]);

        $province = Province::create([
            'name' => 'Test Province',
        ]);

        $district = District::create([
            'name' => 'Test District',
            'province_id' => $province->id,
        ]);

        $facilityType = FacilityType::create([
            'name' => 'Test Facility Type',
        ]);

        $facility = Facility::create([
            'name' => 'Test Facility',
            'province_id' => $province->id,
            'district_id' => $district->id,
            'facility_type_id' => $facilityType->id,
        ]);

        // Create test role
        $this->testRole = Role::create([
            'name' => 'supervisor',
            'display_name' => 'Supervisor',
            'description' => 'Supervisor role',
        ]);

        // Create super admin role
        $superAdminRole = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Super Admin role',
        ]);

        // Create approver (super admin) user
        $this->approverUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone_number' => '9999999999',
            'organization_id' => $organization->id,
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'province_id' => $province->id,
            'district_id' => $district->id,
            'facility_type_id' => $facilityType->id,
            'facility_id' => $facility->id,
            'password' => Hash::make('password123'),
            'status' => 'approved',
        ]);

        // Assign super_admin role to approver
        $this->approverUser->roles()->attach($superAdminRole->id);

        // Create pending user
        $this->pendingUser = User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $organization->id,
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'province_id' => $province->id,
            'district_id' => $district->id,
            'facility_type_id' => $facilityType->id,
            'facility_id' => $facility->id,
            'password' => Hash::make('password123'),
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Approval notification is sent when user is approved
     */
    public function test_approval_notification_is_sent_when_user_is_approved()
    {
        Notification::fake();

        $this->actingAs($this->approverUser);

        $response = $this->post(route('user-approval.approve', $this->pendingUser), [
            'role_id' => $this->testRole->id,
        ]);

        // Verify notification was sent
        Notification::assertSentTo(
            [$this->pendingUser],
            UserApprovedNotification::class
        );
    }

    /**
     * Test: Approval notification includes role assignment details
     */
    public function test_approval_notification_includes_role_details()
    {
        Notification::fake();

        $this->actingAs($this->approverUser);

        $this->post(route('user-approval.approve', $this->pendingUser), [
            'role_id' => $this->testRole->id,
        ]);

        Notification::assertSentTo(
            [$this->pendingUser],
            UserApprovedNotification::class,
            function ($notification) {
                $mail = $notification->toMail($this->pendingUser);
                return strpos($mail->render(), 'Supervisor') !== false;
            }
        );
    }

    /**
     * Test: Approval notification includes login link
     */
    public function test_approval_notification_includes_login_link()
    {
        Notification::fake();

        $this->actingAs($this->approverUser);

        $this->post(route('user-approval.approve', $this->pendingUser), [
            'role_id' => $this->testRole->id,
        ]);

        Notification::assertSentTo(
            [$this->pendingUser],
            UserApprovedNotification::class,
            function ($notification) {
                $mail = $notification->toMail($this->pendingUser);
                $rendered = $mail->render();
                return strpos($rendered, route('login')) !== false;
            }
        );
    }

    /**
     * Test: Rejection notification is sent when user is rejected
     */
    public function test_rejection_notification_is_sent_when_user_is_rejected()
    {
        Notification::fake();

        $this->actingAs($this->approverUser);

        $response = $this->post(route('user-approval.reject', $this->pendingUser), [
            'rejection_reason' => 'Invalid information provided',
        ]);

        // Verify notification was sent
        Notification::assertSentTo(
            [$this->pendingUser],
            UserRejectedNotification::class
        );
    }

    /**
     * Test: Rejection notification includes rejection reason
     */
    public function test_rejection_notification_includes_rejection_reason()
    {
        Notification::fake();

        $rejectionReason = 'Missing required documents';

        $this->actingAs($this->approverUser);

        $this->post(route('user-approval.reject', $this->pendingUser), [
            'rejection_reason' => $rejectionReason,
        ]);

        Notification::assertSentTo(
            [$this->pendingUser],
            UserRejectedNotification::class,
            function ($notification) use ($rejectionReason) {
                $mail = $notification->toMail($this->pendingUser);
                return strpos($mail->render(), $rejectionReason) !== false;
            }
        );
    }

    /**
     * Test: User status is updated after approval notification is sent
     */
    public function test_user_status_is_updated_after_approval()
    {
        Notification::fake();

        $this->actingAs($this->approverUser);

        $this->post(route('user-approval.approve', $this->pendingUser), [
            'role_id' => $this->testRole->id,
        ]);

        $this->pendingUser->refresh();
        $this->assertEquals('approved', $this->pendingUser->status);
        $this->assertNotNull($this->pendingUser->approved_at);
        $this->assertEquals($this->approverUser->id, $this->pendingUser->approved_by);
    }

    /**
     * Test: User status is updated after rejection notification is sent
     */
    public function test_user_status_is_updated_after_rejection()
    {
        Notification::fake();

        $this->actingAs($this->approverUser);

        $this->post(route('user-approval.reject', $this->pendingUser), [
            'rejection_reason' => 'Invalid information provided',
        ]);

        $this->pendingUser->refresh();
        $this->assertEquals('rejected', $this->pendingUser->status);
        $this->assertNotNull($this->pendingUser->approved_at);
        $this->assertEquals($this->approverUser->id, $this->pendingUser->approved_by);
        $this->assertEquals('Invalid information provided', $this->pendingUser->rejection_reason);
    }
}
