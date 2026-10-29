<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Facility;
use App\Models\District;
use App\Models\StaffRoster;
use App\Models\RosterAssignment;
use App\Models\Incident;
use App\Services\Roster\AssignmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Data Integrity Constraints
 * 
 * Task 20: Data integrity constraints and prevention
 * - Referential integrity checks (20.1)
 * - Multiple assignment conflict detection (20.2)
 */
class DataIntegrityConstraintsTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $facility;
    protected $district;
    protected $assignmentService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Use existing district and facility from database
        $this->district = District::first();
        $this->facility = Facility::first();
        
        // Skip if no test data available
        if (!$this->district || !$this->facility) {
            $this->markTestSkipped('Test requires existing district and facility records in database');
        }
        
        // Create super admin user
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'super_admin']);
        }
        
        $this->superAdmin = User::factory()->create([
            'email' => 'superadmin_integrity_test@test.com',
            'approval_status' => 'approved',
            'facility_id' => $this->facility->id,
        ]);
        $this->superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        
        // Initialize service
        $this->assignmentService = app(AssignmentService::class);
    }

    /**
     * Test 20.1: Referential integrity - cannot delete roster linked to incidents
     */
    public function test_cannot_delete_roster_with_linked_incidents()
    {
        // Create roster
        $roster = StaffRoster::create([
            'user_id' => $this->superAdmin->id,
            'staff_type' => 'rotational',
            'primary_facility_id' => $this->facility->id,
            'start_date' => Carbon::now(),
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Create assignment
        $assignment = RosterAssignment::create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $this->facility->id,
            'assignment_start_date' => Carbon::now(),
            'assignment_end_date' => Carbon::now()->addMonths(3),
            'cost_center_facility_id' => $this->facility->id,
            'assignment_type' => 'rotation',
            'status' => 'active',
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Create incident linked to assignment
        $incident = Incident::create([
            'incident_type' => 'injury',
            'facility_id' => $this->facility->id,
            'user_id' => $this->superAdmin->id,
            'roster_assignment_id' => $assignment->id,
            'is_rotational_staff' => true,
            'reference_number' => 'TEST-001',
            'incident_date' => Carbon::now(),
            'incident_time' => '10:00',
        ]);

        // Attempt to delete assignment - should throw exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot delete assignment: This assignment is linked to');
        
        $assignment->delete();
    }

    /**
     * Test 20.1: Referential integrity - cannot delete assignment linked to incidents
     */
    public function test_cannot_delete_assignment_with_linked_incidents()
    {
        // Create roster
        $roster = StaffRoster::create([
            'user_id' => $this->superAdmin->id,
            'staff_type' => 'rotational',
            'primary_facility_id' => $this->facility->id,
            'start_date' => Carbon::now(),
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Create assignment
        $assignment = RosterAssignment::create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $this->facility->id,
            'assignment_start_date' => Carbon::now(),
            'assignment_end_date' => Carbon::now()->addMonths(3),
            'cost_center_facility_id' => $this->facility->id,
            'assignment_type' => 'rotation',
            'status' => 'active',
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Create incident linked to assignment
        $incident = Incident::create([
            'incident_type' => 'injury',
            'facility_id' => $this->facility->id,
            'user_id' => $this->superAdmin->id,
            'roster_assignment_id' => $assignment->id,
            'is_rotational_staff' => true,
            'reference_number' => 'TEST-002',
            'incident_date' => Carbon::now(),
            'incident_time' => '10:00',
        ]);

        // Attempt to delete roster - should throw exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot delete roster');
        
        $roster->delete();
    }

    /**
     * Test 20.2: Multiple assignment conflict detection
     */
    public function test_detects_multiple_assignment_conflicts()
    {
        // Get a second facility
        $facility2 = Facility::where('id', '!=', $this->facility->id)->first();
        
        if (!$facility2) {
            $this->markTestSkipped('Test requires at least 2 facilities in database');
        }

        // Create roster
        $roster = StaffRoster::create([
            'user_id' => $this->superAdmin->id,
            'staff_type' => 'rotational',
            'primary_facility_id' => $this->facility->id,
            'start_date' => Carbon::now(),
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Create two overlapping assignments at different facilities
        $assignment1 = RosterAssignment::create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $this->facility->id,
            'assignment_start_date' => Carbon::parse('2024-01-01'),
            'assignment_end_date' => Carbon::parse('2024-06-30'),
            'cost_center_facility_id' => $this->facility->id,
            'assignment_type' => 'rotation',
            'status' => 'active',
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        $assignment2 = RosterAssignment::create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $facility2->id,
            'assignment_start_date' => Carbon::parse('2024-03-01'),
            'assignment_end_date' => Carbon::parse('2024-09-30'),
            'cost_center_facility_id' => $facility2->id,
            'assignment_type' => 'rotation',
            'status' => 'active',
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Check for conflicts
        $conflicts = $this->assignmentService->checkMultipleAssignments(Carbon::parse('2024-04-15'));

        // Should detect conflict
        $this->assertNotEmpty($conflicts, 'Should detect multiple assignment conflict');
        $this->assertCount(1, $conflicts, 'Should have exactly one conflict detected');
        
        $conflict = $conflicts->first();
        $this->assertEquals($roster->id, $conflict['staff_roster_id']);
        $this->assertEquals(2, $conflict['facility_count']);
        $this->assertCount(2, $conflict['assignments']);
    }

    /**
     * Test 20.2: No conflict when assignments are at same facility
     */
    public function test_no_conflict_for_same_facility_assignments()
    {
        // Create roster
        $roster = StaffRoster::create([
            'user_id' => $this->superAdmin->id,
            'staff_type' => 'rotational',
            'primary_facility_id' => $this->facility->id,
            'start_date' => Carbon::now(),
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Create two assignments at the SAME facility (should be prevented by overlap detection)
        $assignment1 = RosterAssignment::create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $this->facility->id,
            'assignment_start_date' => Carbon::parse('2024-01-01'),
            'assignment_end_date' => Carbon::parse('2024-03-31'),
            'cost_center_facility_id' => $this->facility->id,
            'assignment_type' => 'rotation',
            'status' => 'active',
            'created_by_user_id' => $this->superAdmin->id,
        ]);

        // Check for conflicts on date where only one assignment exists
        $conflicts = $this->assignmentService->checkMultipleAssignments(Carbon::parse('2024-02-15'));

        // Should NOT detect conflict (only one facility)
        $this->assertEmpty($conflicts, 'Should not detect conflict for single facility assignments');
    }
}
