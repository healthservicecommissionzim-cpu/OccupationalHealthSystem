<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Facility;
use App\Models\StaffRoster;
use App\Models\RosterAssignment;
use App\Models\RosterAuditLog;
use App\Models\Incident;
use App\Services\Roster\RosterService;
use App\Services\Roster\AssignmentService;
use App\Services\Roster\CostAttributionEngine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RosterEndToEndTest extends TestCase
{
    protected $rosterService;
    protected $assignmentService;
    protected $costAttributionEngine;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->rosterService = app(RosterService::class);
        $this->assignmentService = app(AssignmentService::class);
        $this->costAttributionEngine = app(CostAttributionEngine::class);
    }

    /**
     * Test complete end-to-end flow: create roster → create assignment → create incident
     */
    public function test_complete_roster_to_incident_flow()
    {
        // Arrange: Create test data - use existing facilities and users
        $primaryFacility = Facility::first() ?? Facility::create([
            'name' => 'Primary Hospital',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $assignmentFacility = Facility::skip(1)->first() ?? Facility::create([
            'name' => 'Assignment Hospital',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $costCenterFacility = Facility::skip(2)->first() ?? Facility::create([
            'name' => 'Cost Center Hospital',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $worker = User::where('email', 'test.worker@example.com')->first() ?? User::create([
            'name' => 'Test Worker',
            'email' => 'test.worker@example.com',
            'password' => bcrypt('password'),
            'facility_id' => $primaryFacility->id,
        ]);
        
        $admin = User::where('email', 'admin.user@example.com')->first() ?? User::create([
            'name' => 'Admin User',
            'email' => 'admin.user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Act 1: Create roster
        $roster = $this->rosterService->createRoster(
            userId: $worker->id,
            staffType: 'rotational',
            primaryFacilityId: $primaryFacility->id,
            startDate: Carbon::now()->subMonths(1),
            endDate: Carbon::now()->addMonths(6),
            createdByUserId: $admin->id,
            notes: 'Test roster creation'
        );

        // Assert: Roster created successfully
        $this->assertInstanceOf(StaffRoster::class, $roster);
        $this->assertEquals($worker->id, $roster->user_id);
        $this->assertEquals('rotational', $roster->staff_type);
        $this->assertTrue($roster->active);

        // Assert: Audit log created for roster creation
        $rosterAuditLog = RosterAuditLog::where('auditable_type', StaffRoster::class)
            ->where('auditable_id', $roster->id)
            ->where('action_type', 'created')
            ->first();
        
        $this->assertNotNull($rosterAuditLog);
        $this->assertEquals($admin->id, $rosterAuditLog->user_id);

        // Act 2: Create assignment
        $assignment = $this->assignmentService->createAssignment(
            staffRosterId: $roster->id,
            facilityId: $assignmentFacility->id,
            startDate: Carbon::now()->subWeeks(2),
            endDate: Carbon::now()->addMonths(3),
            costCenterFacilityId: $costCenterFacility->id,
            assignmentType: 'rotation',
            createdByUserId: $admin->id,
            notes: 'Test assignment creation'
        );

        // Assert: Assignment created successfully
        $this->assertInstanceOf(RosterAssignment::class, $assignment);
        $this->assertEquals($roster->id, $assignment->staff_roster_id);
        $this->assertEquals($assignmentFacility->id, $assignment->facility_id);
        $this->assertEquals($costCenterFacility->id, $assignment->cost_center_facility_id);
        $this->assertEquals('active', $assignment->status);

        // Assert: Audit log created for assignment creation
        $assignmentAuditLog = RosterAuditLog::where('auditable_type', RosterAssignment::class)
            ->where('auditable_id', $assignment->id)
            ->where('action_type', 'created')
            ->first();
        
        $this->assertNotNull($assignmentAuditLog);
        $this->assertEquals($admin->id, $assignmentAuditLog->user_id);

        // Act 3: Attribute incident using Cost Attribution Engine
        $incidentDate = Carbon::now();
        $attribution = $this->costAttributionEngine->attributeIncident(
            userId: $worker->id,
            incidentFacilityId: $assignmentFacility->id,
            incidentDate: $incidentDate
        );

        // Assert: Rotational staff attribution correct
        $this->assertEquals($primaryFacility->id, $attribution['worker_primary_facility_id']);
        $this->assertEquals($costCenterFacility->id, $attribution['cost_center_facility_id']);
        $this->assertEquals($assignment->id, $attribution['roster_assignment_id']);
        $this->assertTrue($attribution['is_rotational_staff']);

        // Simulate incident creation with roster attribution
        $incident = Incident::create([
            'reference_number' => 'TEST-' . now()->format('YmdHis'),
            'facility_id' => $assignmentFacility->id,
            'supervisor_user_id' => $worker->id,
            'incident_date' => $incidentDate,
            'worker_primary_facility_id' => $attribution['worker_primary_facility_id'],
            'cost_center_facility_id' => $attribution['cost_center_facility_id'],
            'roster_assignment_id' => $attribution['roster_assignment_id'],
            'is_rotational_staff' => $attribution['is_rotational_staff'],
            'status' => 'draft',
        ]);

        // Assert: Incident created with correct roster attribution
        $this->assertInstanceOf(Incident::class, $incident);
        $this->assertEquals($primaryFacility->id, $incident->worker_primary_facility_id);
        $this->assertEquals($costCenterFacility->id, $incident->cost_center_facility_id);
        $this->assertEquals($assignment->id, $incident->roster_assignment_id);
        $this->assertTrue($incident->is_rotational_staff);

        // Assert: Incident relationships work correctly
        $this->assertNotNull($incident->workerPrimaryFacility);
        $this->assertEquals('Primary Hospital', $incident->workerPrimaryFacility->name);
        
        $this->assertNotNull($incident->costCenterFacility);
        $this->assertEquals('Cost Center Hospital', $incident->costCenterFacility->name);
        
        $this->assertNotNull($incident->rosterAssignment);
        $this->assertEquals($assignment->id, $incident->rosterAssignment->id);
    }

    /**
     * Test non-rotational staff default attribution
     */
    public function test_non_rotational_staff_default_attribution()
    {
        // Arrange: Create facility and worker without roster assignment
        $facility = Facility::first() ?? Facility::create([
            'name' => 'Test Hospital',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $worker = User::where('email', 'non.rotational@example.com')->first() ?? User::create([
            'name' => 'Non-rotational Worker',
            'email' => 'non.rotational@example.com',
            'password' => bcrypt('password'),
            'facility_id' => $facility->id,
        ]);

        // Act: Attribute incident for worker without active assignment
        $incidentDate = Carbon::now();
        $attribution = $this->costAttributionEngine->attributeIncident(
            userId: $worker->id,
            incidentFacilityId: $facility->id,
            incidentDate: $incidentDate
        );

        // Assert: Default attribution (non-rotational)
        $this->assertEquals($facility->id, $attribution['worker_primary_facility_id']);
        $this->assertEquals($facility->id, $attribution['cost_center_facility_id']);
        $this->assertNull($attribution['roster_assignment_id']);
        $this->assertFalse($attribution['is_rotational_staff']);
    }

    /**
     * Test roster update and audit logging
     */
    public function test_roster_update_creates_audit_log()
    {
        // Arrange
        $facility1 = Facility::first() ?? Facility::create([
            'name' => 'Facility 1',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $facility2 = Facility::skip(1)->first() ?? Facility::create([
            'name' => 'Facility 2',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $worker = User::where('email', 'update.worker@example.com')->first() ?? User::create([
            'name' => 'Update Worker',
            'email' => 'update.worker@example.com',
            'password' => bcrypt('password'),
            'facility_id' => $facility1->id,
        ]);
        
        $admin = User::where('email', 'update.admin@example.com')->first() ?? User::create([
            'name' => 'Update Admin',
            'email' => 'update.admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $roster = $this->rosterService->createRoster(
            userId: $worker->id,
            staffType: 'permanent',
            primaryFacilityId: $facility1->id,
            startDate: Carbon::now(),
            endDate: null,
            createdByUserId: $admin->id
        );

        $originalStaffType = $roster->staff_type;

        // Act: Update roster
        $updatedRoster = $this->rosterService->updateRoster(
            rosterId: $roster->id,
            attributes: ['staff_type' => 'rotational'],
            updatedByUserId: $admin->id
        );

        // Assert: Roster updated
        $this->assertEquals('rotational', $updatedRoster->staff_type);

        // Assert: Audit log created with before/after values
        $updateAuditLog = RosterAuditLog::where('auditable_type', StaffRoster::class)
            ->where('auditable_id', $roster->id)
            ->where('action_type', 'updated')
            ->first();
        
        $this->assertNotNull($updateAuditLog);
        $this->assertEquals($admin->id, $updateAuditLog->user_id);
        $this->assertNotNull($updateAuditLog->old_values);
        $this->assertNotNull($updateAuditLog->new_values);
        $this->assertEquals($originalStaffType, $updateAuditLog->old_values['staff_type']);
        $this->assertEquals('rotational', $updateAuditLog->new_values['staff_type']);
    }

    /**
     * Test assignment cancellation
     */
    public function test_assignment_cancellation()
    {
        // Arrange
        $facility = Facility::first() ?? Facility::create([
            'name' => 'Cancellation Facility',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $worker = User::where('email', 'cancel.worker@example.com')->first() ?? User::create([
            'name' => 'Cancel Worker',
            'email' => 'cancel.worker@example.com',
            'password' => bcrypt('password'),
            'facility_id' => $facility->id,
        ]);
        
        $admin = User::where('email', 'cancel.admin@example.com')->first() ?? User::create([
            'name' => 'Cancel Admin',
            'email' => 'cancel.admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $roster = $this->rosterService->createRoster(
            userId: $worker->id,
            staffType: 'locum',
            primaryFacilityId: $facility->id,
            startDate: Carbon::now(),
            endDate: Carbon::now()->addMonths(6),
            createdByUserId: $admin->id
        );

        $assignment = $this->assignmentService->createAssignment(
            staffRosterId: $roster->id,
            facilityId: $facility->id,
            startDate: Carbon::now(),
            endDate: Carbon::now()->addMonths(3),
            costCenterFacilityId: $facility->id,
            assignmentType: 'locum_contract',
            createdByUserId: $admin->id
        );

        // Act: Cancel assignment
        $result = $this->assignmentService->cancelAssignment(
            assignmentId: $assignment->id,
            cancelledByUserId: $admin->id
        );

        // Assert: Assignment cancelled
        $this->assertTrue($result);
        $cancelledAssignment = RosterAssignment::find($assignment->id);
        $this->assertEquals('cancelled', $cancelledAssignment->status);

        // Assert: Audit log created for cancellation
        $cancelAuditLog = RosterAuditLog::where('auditable_type', RosterAssignment::class)
            ->where('auditable_id', $assignment->id)
            ->where('action_type', 'cancelled')
            ->first();
        
        $this->assertNotNull($cancelAuditLog);
        $this->assertEquals($admin->id, $cancelAuditLog->user_id);
    }

    /**
     * Test roster deactivation (soft delete)
     */
    public function test_roster_deactivation_preserves_data()
    {
        // Arrange
        $facility = Facility::first() ?? Facility::create([
            'name' => 'Deactivation Facility',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $worker = User::where('email', 'deactivate.worker@example.com')->first() ?? User::create([
            'name' => 'Deactivate Worker',
            'email' => 'deactivate.worker@example.com',
            'password' => bcrypt('password'),
            'facility_id' => $facility->id,
        ]);
        
        $admin = User::where('email', 'deactivate.admin@example.com')->first() ?? User::create([
            'name' => 'Deactivate Admin',
            'email' => 'deactivate.admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $roster = $this->rosterService->createRoster(
            userId: $worker->id,
            staffType: 'student',
            primaryFacilityId: $facility->id,
            startDate: Carbon::now(),
            endDate: Carbon::now()->addYears(1),
            createdByUserId: $admin->id,
            notes: 'Medical student placement'
        );

        // Act: Deactivate roster
        $result = $this->rosterService->deactivateRoster(
            rosterId: $roster->id,
            deactivatedByUserId: $admin->id
        );

        // Assert: Roster deactivated but data preserved
        $this->assertTrue($result);
        $deactivatedRoster = StaffRoster::find($roster->id);
        $this->assertNotNull($deactivatedRoster);
        $this->assertFalse($deactivatedRoster->active);
        $this->assertEquals($worker->id, $deactivatedRoster->user_id);
        $this->assertEquals('student', $deactivatedRoster->staff_type);
        $this->assertEquals('Medical student placement', $deactivatedRoster->notes);
    }

    /**
     * Test active assignment query filtering
     */
    public function test_active_assignment_filtering()
    {
        // Arrange
        $facility = Facility::first() ?? Facility::create([
            'name' => 'Filter Facility',
            'facility_type_id' => 1,
            'district_id' => 1,
        ]);
        
        $worker = User::where('email', 'filter.worker@example.com')->first() ?? User::create([
            'name' => 'Filter Worker',
            'email' => 'filter.worker@example.com',
            'password' => bcrypt('password'),
            'facility_id' => $facility->id,
        ]);
        
        $admin = User::where('email', 'filter.admin@example.com')->first() ?? User::create([
            'name' => 'Filter Admin',
            'email' => 'filter.admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $roster = $this->rosterService->createRoster(
            userId: $worker->id,
            staffType: 'rotational',
            primaryFacilityId: $facility->id,
            startDate: Carbon::now()->subMonths(3),
            endDate: Carbon::now()->addMonths(9),
            createdByUserId: $admin->id
        );

        // Create assignment for specific date range
        $this->assignmentService->createAssignment(
            staffRosterId: $roster->id,
            facilityId: $facility->id,
            startDate: Carbon::parse('2024-01-01'),
            endDate: Carbon::parse('2024-03-31'),
            costCenterFacilityId: $facility->id,
            assignmentType: 'rotation',
            createdByUserId: $admin->id
        );

        // Act & Assert: Query date within assignment range
        $activeAssignment = $this->assignmentService->getActiveAssignment(
            userId: $worker->id,
            facilityId: $facility->id,
            date: Carbon::parse('2024-02-15')
        );
        $this->assertNotNull($activeAssignment);

        // Act & Assert: Query date before assignment range
        $beforeAssignment = $this->assignmentService->getActiveAssignment(
            userId: $worker->id,
            facilityId: $facility->id,
            date: Carbon::parse('2023-12-15')
        );
        $this->assertNull($beforeAssignment);

        // Act & Assert: Query date after assignment range
        $afterAssignment = $this->assignmentService->getActiveAssignment(
            userId: $worker->id,
            facilityId: $facility->id,
            date: Carbon::parse('2024-04-15')
        );
        $this->assertNull($afterAssignment);
    }
}
