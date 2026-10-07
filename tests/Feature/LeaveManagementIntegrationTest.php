<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Incident;
use App\Models\User;
use App\Models\Role;
use App\Models\Facility;
use App\Models\District;
use App\Models\Province;
use App\Models\IncidentType;
use App\Models\IncidentLeaveMilestone;
use App\Models\AuditLog;
use App\Services\LeaveManagementService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveManagementIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected LeaveManagementService $leaveService;
    protected User $hrUser;
    protected Incident $incident;

    protected function setUp(): void
    {
        parent::setUp();
        $this->leaveService = app(LeaveManagementService::class);

        // Create HR user role if it doesn't exist
        $hrRole = Role::firstOrCreate(['name' => 'HR_User'], ['display_name' => 'HR User']);
        
        // Create HR user and attach role
        $this->hrUser = User::factory()->create();
        $this->hrUser->roles()->attach($hrRole->id);

        // Create facility hierarchy
        $province = Province::create(['name' => 'Test Province', 'code' => 'TP']);
        $district = District::create(['name' => 'Test District', 'province_id' => $province->id]);
        $facility = Facility::create([
            'name' => 'Test Facility',
            'province_id' => $province->id,
            'district_id' => $district->id,
            'facility_type_id' => 1,
        ]);

        // Create incident type
        $incidentType = IncidentType::create(['name' => 'Injury']);

        // Create incident in HR review status
        $this->incident = Incident::create([
            'reference_number' => 'OHS-2026-001',
            'first_name' => 'John',
            'surname' => 'Doe',
            'phone' => '0712345678',
            'facility_id' => $facility->id,
            'incident_type_id' => $incidentType->id,
            'incident_date' => Carbon::now()->subDays(5),
            'status' => 'DOCUMENT_VERIFICATION_IN_PROGRESS',
            'description' => 'Test incident',
            'routed_to_hr_user_id' => $this->hrUser->id,
        ]);
    }

    /**
     * Test that HR leave capture doesn't affect existing incident data
     */
    public function test_hr_leave_capture_does_not_affect_existing_incident_data()
    {
        // Store original values
        $originalDescription = $this->incident->description;
        $originalIncidentDate = $this->incident->incident_date;
        $originalFirstName = $this->incident->first_name;

        // Capture leave information
        $leaveData = [
            'sick_leave_start_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'medical_certificate_provided' => true,
            'expected_recovery_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ];

        $this->leaveService->saveLeaveInformation($this->incident, $leaveData, $this->hrUser);

        // Refresh incident from database
        $this->incident->refresh();

        // Verify non-leave fields are unchanged
        $this->assertEquals($originalDescription, $this->incident->description);
        $this->assertEquals($originalIncidentDate->format('Y-m-d'), $this->incident->incident_date->format('Y-m-d'));
        $this->assertEquals($originalFirstName, $this->incident->first_name);

        // Verify leave fields are set
        $this->assertNotNull($this->incident->sick_leave_start_date);
        $this->assertTrue($this->incident->medical_certificate_provided);
        $this->assertNotNull($this->incident->expected_recovery_date);
    }

    /**
     * Test that leave data is preserved through milestone flagging
     */
    public function test_leave_data_preserved_through_milestone_flagging()
    {
        // Set leave information
        $leaveData = [
            'sick_leave_start_date' => Carbon::now()->subDays(91)->format('Y-m-d'),
            'medical_certificate_provided' => true,
            'expected_recovery_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ];

        $this->leaveService->saveLeaveInformation($this->incident, $leaveData, $this->hrUser);

        // Store original leave values
        $originalLeaveStartDate = $this->incident->sick_leave_start_date;
        $originalMedicalCertificate = $this->incident->medical_certificate_provided;
        $originalRecoveryDate = $this->incident->expected_recovery_date;

        // Trigger milestone flagging (this would normally happen via scheduler)
        $this->leaveService->checkAndApplyMilestones();

        // Refresh incident
        $this->incident->refresh();

        // Verify leave data is unchanged
        $this->assertEquals($originalLeaveStartDate->format('Y-m-d'), $this->incident->sick_leave_start_date->format('Y-m-d'));
        $this->assertEquals($originalMedicalCertificate, $this->incident->medical_certificate_provided);
        $this->assertEquals($originalRecoveryDate->format('Y-m-d'), $this->incident->expected_recovery_date->format('Y-m-d'));

        // Verify milestone was applied
        $this->assertEquals('REFERRED_TO_MEDICAL_BOARD', $this->incident->status);
        $this->assertTrue($this->incident->leaveMilestones()->where('milestone_type', '90_DAY')->exists());
    }

    /**
     * Test complete workflow: HR capture -> Milestone flag -> Medical Board decision
     */
    public function test_complete_workflow_hr_capture_to_medical_board_decision()
    {
        // Step 1: HR captures leave information
        $leaveData = [
            'sick_leave_start_date' => Carbon::now()->subDays(91)->format('Y-m-d'),
            'medical_certificate_provided' => true,
            'expected_recovery_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ];

        $this->leaveService->saveLeaveInformation($this->incident, $leaveData, $this->hrUser);

        // Verify audit log created
        $this->assertTrue(AuditLog::where('incident_id', $this->incident->id)
            ->where('action', 'leave_information_saved')
            ->exists());

        // Step 2: Scheduler triggers milestone check
        $this->leaveService->checkAndApplyMilestones();
        $this->incident->refresh();

        // Verify status changed and milestone created
        $this->assertEquals('REFERRED_TO_MEDICAL_BOARD', $this->incident->status);
        $milestone = $this->incident->leaveMilestones()->where('milestone_type', '90_DAY')->first();
        $this->assertNotNull($milestone);

        // Step 3: Medical Board submits decision
        $medicalBoardRole = Role::firstOrCreate(['name' => 'medical_board'], ['display_name' => 'Medical Board']);
        $medicalBoardUser = User::factory()->create();
        $medicalBoardUser->roles()->attach($medicalBoardRole->id);
        $this->actingAs($medicalBoardUser);

        $response = $this->postJson('/api/medical-board/incidents/' . $this->incident->id . '/decision', [
            'decision' => 'APPROVE_RETURN_TO_WORK',
            'decision_reason' => 'Worker has recovered adequately and is cleared for return to normal duties.',
        ]);

        // Verify decision was recorded
        $this->assertEquals(200, $response->status());
        $this->incident->refresh();
        $this->assertEquals('CLEARED_FOR_RETURN', $this->incident->status);

        // Verify audit log created
        $this->assertTrue(AuditLog::where('incident_id', $this->incident->id)
            ->where('action', 'medical_board_decision')
            ->exists());
    }

    /**
     * Test case status transitions work correctly
     */
    public function test_case_status_transitions_through_workflow()
    {
        // Initial status
        $this->assertEquals('DOCUMENT_VERIFICATION_IN_PROGRESS', $this->incident->status);

        // After leave capture, status should remain the same
        $leaveData = [
            'sick_leave_start_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'medical_certificate_provided' => false,
        ];

        $this->leaveService->saveLeaveInformation($this->incident, $leaveData, $this->hrUser);
        $this->incident->refresh();
        $this->assertEquals('DOCUMENT_VERIFICATION_IN_PROGRESS', $this->incident->status);

        // After 90-day milestone, status should change to REFERRED_TO_MEDICAL_BOARD
        $this->incident->update([
            'sick_leave_start_date' => Carbon::now()->subDays(91),
        ]);

        $this->leaveService->checkAndApplyMilestones();
        $this->incident->refresh();
        $this->assertEquals('REFERRED_TO_MEDICAL_BOARD', $this->incident->status);

        // After medical board decision, status should change
        $medicalBoardRole = Role::firstOrCreate(['name' => 'medical_board'], ['display_name' => 'Medical Board']);
        $medicalBoardUser = User::factory()->create();
        $medicalBoardUser->roles()->attach($medicalBoardRole->id);
        $this->actingAs($medicalBoardUser);

        $this->postJson('/api/medical-board/incidents/' . $this->incident->id . '/decision', [
            'decision' => 'RECOMMEND_EXTENDED_LEAVE',
            'decision_reason' => 'Worker needs additional recovery time.',
        ]);

        $this->incident->refresh();
        $this->assertEquals('EXTENDED_LEAVE_APPROVED', $this->incident->status);
    }

    /**
     * Test that validation prevents invalid leave data
     */
    public function test_leave_data_validation_prevents_invalid_data()
    {
        // Test leave start date before incident date
        $invalidData = [
            'sick_leave_start_date' => $this->incident->incident_date->subDays(10)->format('Y-m-d'),
            'medical_certificate_provided' => false,
        ];

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->leaveService->saveLeaveInformation($this->incident, $invalidData, $this->hrUser);
    }

    /**
     * Test that multiple milestones can exist for same incident
     */
    public function test_multiple_milestones_can_be_applied()
    {
        // Set leave start date to 181 days ago
        $leaveData = [
            'sick_leave_start_date' => Carbon::now()->subDays(181)->format('Y-m-d'),
            'medical_certificate_provided' => true,
            'expected_recovery_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ];

        $this->leaveService->saveLeaveInformation($this->incident, $leaveData, $this->hrUser);

        // Run milestone check
        $this->leaveService->checkAndApplyMilestones();
        $this->incident->refresh();

        // Verify both 90 and 180 day milestones exist
        $this->assertTrue($this->incident->leaveMilestones()->where('milestone_type', '90_DAY')->exists());
        $this->assertTrue($this->incident->leaveMilestones()->where('milestone_type', '180_DAY')->exists());

        // Verify status is MEDICAL_DISCHARGE_ELIGIBLE (180-day takes precedence)
        $this->assertEquals('MEDICAL_DISCHARGE_ELIGIBLE', $this->incident->status);
    }
}
