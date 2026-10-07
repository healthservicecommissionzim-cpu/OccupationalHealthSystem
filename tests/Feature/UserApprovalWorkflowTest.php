<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Province;
use App\Models\District;
use App\Models\FacilityType;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create required master data
        $this->organization = Organization::create([
            'name' => 'Test Organization',
            'code' => 'TEST_ORG',
            'requires_location' => false,
        ]);

        $this->department = Department::create([
            'name' => 'Test Department',
        ]);

        $this->designation = Designation::create([
            'name' => 'Test Designation',
        ]);

        $this->province = Province::create([
            'name' => 'Test Province',
        ]);

        $this->district = District::create([
            'name' => 'Test District',
            'province_id' => $this->province->id,
        ]);

        $this->facilityType = FacilityType::create([
            'name' => 'Test Facility Type',
        ]);

        $this->facility = Facility::create([
            'name' => 'Test Facility',
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
        ]);
    }

    /**
     * Test 1: Migrations run without errors and tables exist
     */
    public function test_migrations_create_required_tables()
    {
        // Check that all required tables exist
        $this->assertTrue(\DB::getSchemaBuilder()->hasTable('users'));
        $this->assertTrue(\DB::getSchemaBuilder()->hasTable('permissions'));
        $this->assertTrue(\DB::getSchemaBuilder()->hasTable('role_permission'));
        $this->assertTrue(\DB::getSchemaBuilder()->hasTable('roles'));
    }

    /**
     * Test 2: Users table has required approval columns
     */
    public function test_users_table_has_approval_columns()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('users');
        
        $this->assertContains('status', $columns);
        $this->assertContains('approved_by', $columns);
        $this->assertContains('approved_at', $columns);
        $this->assertContains('rejection_reason', $columns);
    }

    /**
     * Test 3: User can register and status is set to pending
     */
    public function test_user_registration_sets_status_to_pending()
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'designation_id' => $this->designation->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        
        $this->assertNotNull($user);
        $this->assertEquals('pending', $user->status);
        $this->assertNull($user->approved_at);
        $this->assertNull($user->approved_by);
    }

    /**
     * Test 4: Pending user cannot log in
     */
    public function test_pending_user_cannot_log_in()
    {
        // Create a pending user
        $user = User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
            'facility_id' => $this->facility->id,
            'password' => Hash::make('password123'),
            'status' => 'pending',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'pending@example.com',
            'password' => 'password123',
        ]);

        // Should redirect to pending-user route
        $response->assertRedirect(route('pending-user'));
        $response->assertSessionHas('warning');

        // User should not be authenticated
        $this->assertGuest();
    }

    /**
     * Test 5: Pending user sees pending screen
     */
    public function test_pending_user_can_access_pending_screen()
    {
        // Create and log in a pending user
        $user = User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
            'facility_id' => $this->facility->id,
            'password' => Hash::make('password123'),
            'status' => 'pending',
        ]);

        // Manually authenticate the user
        $this->actingAs($user);

        $response = $this->get(route('pending-user'));

        $response->assertStatus(200);
        $response->assertViewIs('pending-user');
    }

    /**
     * Test 6: Rejected user cannot log in
     */
    public function test_rejected_user_cannot_log_in()
    {
        // Create a rejected user
        $user = User::create([
            'name' => 'Rejected User',
            'email' => 'rejected@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
            'facility_id' => $this->facility->id,
            'password' => Hash::make('password123'),
            'status' => 'rejected',
            'rejection_reason' => 'Invalid information',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'rejected@example.com',
            'password' => 'password123',
        ]);

        // Should redirect to pending-user route with rejection message
        $response->assertRedirect(route('pending-user'));
        $response->assertSessionHas('error');

        // User should not be authenticated
        $this->assertGuest();
    }

    /**
     * Test 7: User status check methods work correctly
     */
    public function test_user_status_check_methods()
    {
        $pendingUser = User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
            'facility_id' => $this->facility->id,
            'password' => Hash::make('password123'),
            'status' => 'pending',
        ]);

        $this->assertTrue($pendingUser->isPending());
        $this->assertFalse($pendingUser->isApproved());
        $this->assertFalse($pendingUser->isRejected());

        $rejectedUser = User::create([
            'name' => 'Rejected User',
            'email' => 'rejected@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
            'facility_id' => $this->facility->id,
            'password' => Hash::make('password123'),
            'status' => 'rejected',
        ]);

        $this->assertTrue($rejectedUser->isRejected());
        $this->assertFalse($rejectedUser->isPending());
        $this->assertFalse($rejectedUser->isApproved());
    }

    /**
     * Test 8: Approved user cannot access pending screen, should access dashboard
     */
    public function test_approved_user_bypass_pending_check()
    {
        // Seed roles and permissions
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\PermissionSeeder::class);

        // Get supervisor role
        $role = \App\Models\Role::where('name', 'supervisor')->first();

        // Create an approved user
        $user = User::create([
            'name' => 'Approved User',
            'email' => 'approved@example.com',
            'phone_number' => '1234567890',
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'province_id' => $this->province->id,
            'district_id' => $this->district->id,
            'facility_type_id' => $this->facilityType->id,
            'facility_id' => $this->facility->id,
            'password' => Hash::make('password123'),
            'status' => 'approved',
        ]);

        // Assign role to user
        $user->roles()->attach($role->id);

        $this->actingAs($user);

        // Approved user should be able to access protected routes
        $response = $this->get(route('dashboards.supervisor'));
        $response->assertStatus(200);
    }
}
