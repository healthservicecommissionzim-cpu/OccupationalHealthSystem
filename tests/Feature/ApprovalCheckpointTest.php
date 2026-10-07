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
use App\Models\Permission;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApprovalCheckpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        
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
     * Checkpoint 1: Super Admin can view pending users
     */
    public function test_super_admin_can_view_pending_users()
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone_number' => '9999999999',
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
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create pending users
        User::create([
            'name' => 'Pending User 1',
            'email' => 'pending1@example.com',
            'phone_number' => '1111111111',
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

        User::create([
            'name' => 'Pending User 2',
            'email' => 'pending2@example.com',
            'phone_number' => '2222222222',
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

        // Super Admin accesses pending users list
        $response = $this->actingAs($superAdmin)->get(route('user-approval.pending'));

        $response->assertStatus(200);
        $response->assertViewIs('super-admin.pending-users.index');
        $response->assertViewHas('pendingUsers');
        
        // Verify pending users are in the view
        $this->assertEquals(2, $response['pendingUsers']->count());
    }

    /**
     * Checkpoint 2: Super Admin can approve user and assign role
     */
    public function test_super_admin_can_approve_user_and_assign_role()
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone_number' => '9999999999',
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
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create pending user
        $pendingUser = User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_number' => '1111111111',
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

        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Super Admin approves user
        $response = $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $pendingUser), [
                'role_id' => $supervisorRole->id,
            ]);

        // Verify user was approved
        $pendingUser->refresh();
        $this->assertEquals('approved', $pendingUser->status);
        $this->assertNotNull($pendingUser->approved_at);
        $this->assertEquals($superAdmin->id, $pendingUser->approved_by);

        // Verify role was assigned
        $this->assertTrue($pendingUser->hasRole('supervisor'));

        // Verify redirect to pending users list
        $response->assertRedirect(route('user-approval.pending'));
    }

    /**
     * Checkpoint 3: Approved user can log in and see correct dashboard
     */
    public function test_approved_user_can_log_in_and_see_dashboard()
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone_number' => '9999999999',
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
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create and approve user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'phone_number' => '1111111111',
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

        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Approve user
        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $user), [
                'role_id' => $supervisorRole->id,
            ]);

        $user->refresh();

        // Logout super admin
        auth()->logout();

        // User can log in
        $response = $this->post(route('login.store'), [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        // Should be authenticated
        $this->assertTrue(auth()->check());
        $this->assertEquals('user@example.com', auth()->user()->email);

        // Redirect to dashboard (approval status check should pass)
        $response->assertRedirect();
    }

    /**
     * Checkpoint 4: Permissions are correctly applied
     */
    public function test_permissions_are_correctly_applied()
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone_number' => '9999999999',
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
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Create and approve user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'phone_number' => '1111111111',
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

        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Approve user
        $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $user), [
                'role_id' => $supervisorRole->id,
            ]);

        $user->refresh();

        // User should have supervisor role
        $this->assertTrue($user->hasRole('supervisor'));

        // User should have permissions from supervisor role
        $permissions = $user->getAllPermissions();
        $this->assertNotEmpty($permissions, 'Supervisor should have permissions');

        // Verify hasPermission works
        $supervisorPermissions = $supervisorRole->permissions;
        foreach ($supervisorPermissions as $permission) {
            $this->assertTrue($user->hasPermission($permission->slug), "User should have {$permission->slug} permission");
        }
    }

    /**
     * Checkpoint 5: Pending user cannot access protected routes
     */
    public function test_pending_user_cannot_access_protected_routes()
    {
        // Create pending user
        $pendingUser = User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_number' => '1111111111',
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

        // Try to access supervisor dashboard
        $response = $this->actingAs($pendingUser)
            ->get(route('dashboards.supervisor'));

        // Should be redirected to pending-user
        $response->assertRedirect(route('pending-user'));

        // Verify pending message
        $response->assertSessionHas('warning');
    }

    /**
     * Checkpoint 6: Complete end-to-end workflow
     */
    public function test_complete_end_to_end_workflow()
    {
        // Step 1: User registers and status is pending
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
        $this->assertEquals('pending', $user->status);

        // Step 2: Pending user cannot log in
        $response = $this->post(route('login.store'), [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('pending-user'));
        $this->assertGuest();

        // Step 3: Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone_number' => '9999999999',
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
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first()->id);

        // Step 4: Super Admin approves user and assigns role
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $response = $this->actingAs($superAdmin)
            ->post(route('user-approval.approve', $user), [
                'role_id' => $supervisorRole->id,
            ]);

        // Step 5: User is now approved
        $user->refresh();
        $this->assertEquals('approved', $user->status);
        $this->assertTrue($user->hasRole('supervisor'));

        // Step 6: User can now log in
        $response = $this->post(route('login.store'), [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
        $this->assertTrue(auth()->check());

        // Step 7: Approved user can access role-specific dashboard
        $dashboardRoute = $user->getDashboardRoute();
        $this->assertEquals('dashboards.supervisor', $dashboardRoute);
    }
}
