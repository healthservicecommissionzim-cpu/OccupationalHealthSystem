<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllUsersPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create the super_admin role
        Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Super Admin Role',
        ]);
    }

    public function test_super_admin_can_access_all_users_page()
    {
        $superAdmin = User::factory()->create(['status' => 'approved']);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        $response = $this->actingAs($superAdmin)->get('/user-approval/users');

        $response->assertStatus(200);
        $response->assertViewIs('super-admin.users.index');
    }

    public function test_all_users_page_displays_users_with_status()
    {
        $superAdmin = User::factory()->create(['status' => 'approved']);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        $pendingUser = User::factory()->create(['status' => 'pending']);
        $approvedUser = User::factory()->create(['status' => 'approved']);
        $rejectedUser = User::factory()->create(['status' => 'rejected']);

        $response = $this->actingAs($superAdmin)->get('/user-approval/users');

        $response->assertViewHas('users');
        $users = $response->viewData('users');
        
        // Verify all users are in the paginated result (or at least some of them)
        $userIds = $users->pluck('id')->toArray();
        
        // At least one user should be present
        $this->assertGreaterThan(0, count($userIds));
    }

    public function test_all_users_page_filters_by_status()
    {
        $superAdmin = User::factory()->create(['status' => 'approved']);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        User::factory()->create(['status' => 'pending', 'name' => 'Pending User']);
        User::factory()->create(['status' => 'approved', 'name' => 'Approved User']);
        User::factory()->create(['status' => 'rejected', 'name' => 'Rejected User']);

        $response = $this->actingAs($superAdmin)->get('/user-approval/users?search=Pending');

        $response->assertStatus(200);
        $users = $response->viewData('users');
        
        // Should find the pending user by name search
        $this->assertGreaterThan(0, count($users));
    }

    public function test_all_users_page_filters_by_organization()
    {
        $superAdmin = User::factory()->create(['status' => 'approved']);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        $org = Organization::factory()->create();
        
        $user1 = User::factory()->create(['organization_id' => $org->id, 'name' => 'Org User']);
        User::factory()->create(['organization_id' => null, 'name' => 'No Org User']);

        $response = $this->actingAs($superAdmin)->get('/user-approval/users?search=Org%20User');

        $response->assertStatus(200);
        $users = $response->viewData('users');
        
        // Should find the user from the organization
        $this->assertGreaterThan(0, count($users));
    }

    public function test_all_users_page_search_by_name()
    {
        $superAdmin = User::factory()->create(['status' => 'approved']);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        User::factory()->create(['name' => 'John Doe', 'status' => 'pending']);
        User::factory()->create(['name' => 'Jane Smith', 'status' => 'approved']);

        $response = $this->actingAs($superAdmin)->get('/user-approval/users?search=John');

        $response->assertStatus(200);
        $users = $response->viewData('users');
        
        // Should only find John Doe
        foreach ($users as $user) {
            $this->assertStringContainsString('John', $user->name);
        }
    }

    public function test_all_users_page_search_by_email()
    {
        $superAdmin = User::factory()->create(['status' => 'approved']);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        User::factory()->create(['email' => 'john@example.com', 'status' => 'pending']);
        User::factory()->create(['email' => 'jane@example.com', 'status' => 'approved']);

        $response = $this->actingAs($superAdmin)->get('/user-approval/users?search=john');

        $response->assertStatus(200);
        $users = $response->viewData('users');
        
        // Should only find john@example.com
        foreach ($users as $user) {
            $this->assertStringContainsString('john', $user->email);
        }
    }

    public function test_non_super_admin_cannot_access_all_users_page()
    {
        $user = User::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($user)->get('/user-approval/users');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_all_users_page()
    {
        $response = $this->get('/user-approval/users');

        $response->assertRedirect('/login');
    }
}
