<?php

namespace App\Services\Roster;

use App\Models\RosterAssignment;
use App\Models\StaffRoster;
use App\Exceptions\OverlapException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    protected ValidationService $validationService;
    protected AuditService $auditService;

    /**
     * Create AssignmentService with injected dependencies
     *
     * @param ValidationService $validationService
     * @param AuditService $auditService
     */
    public function __construct(
        ValidationService $validationService,
        AuditService $auditService
    ) {
        $this->validationService = $validationService;
        $this->auditService = $auditService;
    }

    /**
     * Check for overlapping assignments
     * 
     * @param int $staffRosterId
     * @param int $facilityId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int|null $excludeAssignmentId
     * @return bool
     */
    public function hasOverlappingAssignment(
        int $staffRosterId,
        int $facilityId,
        Carbon $startDate,
        Carbon $endDate,
        ?int $excludeAssignmentId = null
    ): bool {
        $query = RosterAssignment::where('staff_roster_id', $staffRosterId)
            ->where('facility_id', $facilityId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startDate, $endDate) {
                // Check date range overlap: (start1 <= end2) AND (end1 >= start2)
                $q->where(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('assignment_start_date', '<=', $endDate)
                        ->where('assignment_end_date', '>=', $startDate);
                });
            });

        // Optionally exclude specific assignment ID for updates
        if ($excludeAssignmentId !== null) {
            $query->where('id', '!=', $excludeAssignmentId);
        }

        return $query->exists();
    }

    /**
     * Create a new facility assignment
     * 
     * @param int $staffRosterId
     * @param int $facilityId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $costCenterFacilityId
     * @param string $assignmentType
     * @param int $createdByUserId
     * @param int|null $supervisorId
     * @param string|null $notes
     * @return RosterAssignment
     * @throws OverlapException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createAssignment(
        int $staffRosterId,
        int $facilityId,
        Carbon $startDate,
        Carbon $endDate,
        int $costCenterFacilityId,
        string $assignmentType,
        int $createdByUserId,
        ?int $supervisorId = null,
        ?string $notes = null
    ): RosterAssignment {
        // Validate input using ValidationService
        $validatedData = $this->validationService->validateAssignmentData([
            'staff_roster_id' => $staffRosterId,
            'facility_id' => $facilityId,
            'assignment_start_date' => $startDate->format('Y-m-d'),
            'assignment_end_date' => $endDate->format('Y-m-d'),
            'cost_center_facility_id' => $costCenterFacilityId,
            'assignment_type' => $assignmentType,
            'supervisor_id' => $supervisorId,
            'notes' => $notes,
            'created_by_user_id' => $createdByUserId,
        ]);

        // Check for overlapping assignments
        if ($this->hasOverlappingAssignment(
            $staffRosterId,
            $facilityId,
            $startDate,
            $endDate
        )) {
            // Get the overlapping assignment details for the error message
            $overlappingAssignment = RosterAssignment::where('staff_roster_id', $staffRosterId)
                ->where('facility_id', $facilityId)
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->where('assignment_start_date', '<=', $endDate)
                        ->where('assignment_end_date', '>=', $startDate);
                })
                ->with('facility')
                ->first();

            $facilityName = $overlappingAssignment?->facility?->name ?? 'facility';
            $assignmentId = $overlappingAssignment?->id ?? 'unknown';

            throw new OverlapException(
                "Assignment dates overlap with existing assignment [{$assignmentId}] at {$facilityName}"
            );
        }

        // Create assignment within database transaction
        return DB::transaction(function () use ($validatedData, $createdByUserId) {
            $assignment = RosterAssignment::create($validatedData);

            // Log creation using AuditService
            $this->auditService->logAssignmentCreation($assignment, $createdByUserId);

            return $assignment;
        });
    }

    /**
     * Get active assignment for a staff member at a facility on a specific date
     * 
     * @param int $userId
     * @param int $facilityId
     * @param Carbon $date
     * @return RosterAssignment|null
     */
    public function getActiveAssignment(
        int $userId,
        int $facilityId,
        Carbon $date
    ): ?RosterAssignment {
        // Get the staff roster for this user
        $staffRoster = StaffRoster::where('user_id', $userId)
            ->where('active', true)
            ->first();

        if (!$staffRoster) {
            return null;
        }

        // Query assignments where query date between start and end dates
        // Filter by facility_id and staff roster's user_id
        // Exclude cancelled status
        return RosterAssignment::where('staff_roster_id', $staffRoster->id)
            ->where('facility_id', $facilityId)
            ->where('assignment_start_date', '<=', $date)
            ->where('assignment_end_date', '>=', $date)
            ->where('status', '!=', 'cancelled')
            ->first();
    }

    /**
     * Update an existing assignment
     * 
     * @param int $assignmentId
     * @param array $attributes
     * @param int $updatedByUserId
     * @return RosterAssignment
     * @throws OverlapException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateAssignment(
        int $assignmentId,
        array $attributes,
        int $updatedByUserId
    ): RosterAssignment {
        $assignment = RosterAssignment::findOrFail($assignmentId);

        // Capture original values before update
        $originalAttributes = $assignment->toArray();

        // Build validation data with existing values as defaults
        $validationData = [
            'staff_roster_id' => $attributes['staff_roster_id'] ?? $assignment->staff_roster_id,
            'facility_id' => $attributes['facility_id'] ?? $assignment->facility_id,
            'assignment_start_date' => isset($attributes['assignment_start_date']) 
                ? (is_string($attributes['assignment_start_date']) 
                    ? $attributes['assignment_start_date'] 
                    : $attributes['assignment_start_date']->format('Y-m-d'))
                : $assignment->assignment_start_date->format('Y-m-d'),
            'assignment_end_date' => isset($attributes['assignment_end_date']) 
                ? (is_string($attributes['assignment_end_date']) 
                    ? $attributes['assignment_end_date'] 
                    : $attributes['assignment_end_date']->format('Y-m-d'))
                : $assignment->assignment_end_date->format('Y-m-d'),
            'cost_center_facility_id' => $attributes['cost_center_facility_id'] ?? $assignment->cost_center_facility_id,
            'assignment_type' => $attributes['assignment_type'] ?? $assignment->assignment_type,
            'supervisor_id' => $attributes['supervisor_id'] ?? $assignment->supervisor_id,
            'status' => $attributes['status'] ?? $assignment->status,
            'notes' => $attributes['notes'] ?? $assignment->notes,
            'created_by_user_id' => $updatedByUserId,
        ];

        // Validate input
        $validatedData = $this->validationService->validateAssignmentData($validationData);

        // Check for overlaps if dates are being changed
        $datesChanged = isset($attributes['assignment_start_date']) || isset($attributes['assignment_end_date']);
        
        if ($datesChanged) {
            $startDate = Carbon::parse($validatedData['assignment_start_date']);
            $endDate = Carbon::parse($validatedData['assignment_end_date']);
            
            if ($this->hasOverlappingAssignment(
                $validatedData['staff_roster_id'],
                $validatedData['facility_id'],
                $startDate,
                $endDate,
                $assignmentId // Exclude current assignment from overlap check
            )) {
                // Get the overlapping assignment details for the error message
                $overlappingAssignment = RosterAssignment::where('staff_roster_id', $validatedData['staff_roster_id'])
                    ->where('facility_id', $validatedData['facility_id'])
                    ->where('status', '!=', 'cancelled')
                    ->where('id', '!=', $assignmentId)
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->where('assignment_start_date', '<=', $endDate)
                            ->where('assignment_end_date', '>=', $startDate);
                    })
                    ->with('facility')
                    ->first();

                $facilityName = $overlappingAssignment?->facility?->name ?? 'facility';
                $overlappingId = $overlappingAssignment?->id ?? 'unknown';

                throw new OverlapException(
                    "Updated assignment dates overlap with existing assignment [{$overlappingId}] at {$facilityName}"
                );
            }
        }

        // Update assignment within transaction and log update
        return DB::transaction(function () use ($assignment, $validatedData, $originalAttributes, $updatedByUserId) {
            // Remove created_by_user_id from update (it's not a fillable field for updates)
            unset($validatedData['created_by_user_id']);
            
            $assignment->update($validatedData);

            // Log update with before/after values
            $this->auditService->logAssignmentUpdate(
                $assignment->fresh(),
                $originalAttributes,
                $updatedByUserId
            );

            return $assignment->fresh();
        });
    }

    /**
     * Cancel an assignment
     * 
     * @param int $assignmentId
     * @param int $cancelledByUserId
     * @return bool
     */
    public function cancelAssignment(
        int $assignmentId,
        int $cancelledByUserId
    ): bool {
        $assignment = RosterAssignment::findOrFail($assignmentId);

        // Set status to 'cancelled' and log cancellation
        return DB::transaction(function () use ($assignment, $cancelledByUserId) {
            $assignment->status = 'cancelled';
            $assignment->save();

            // Log cancellation
            $this->auditService->logAssignmentCancellation($assignment, $cancelledByUserId);

            return true;
        });
    }

    /**
     * Check for staff members with multiple active assignments on the same date at different facilities
     * 
     * This detects data integrity issues where a staff member is assigned to multiple
     * facilities on the same date, which requires Super Admin resolution.
     * 
     * @param Carbon|null $date Optional specific date to check. If null, checks all active assignments.
     * @return \Illuminate\Support\Collection Collection of conflicts with staff info and conflicting assignments
     */
    public function checkMultipleAssignments(?Carbon $date = null): \Illuminate\Support\Collection
    {
        $query = RosterAssignment::query()
            ->where('status', '!=', 'cancelled')
            ->with(['staffRoster.user', 'facility']);

        // If a specific date is provided, filter assignments active on that date
        if ($date !== null) {
            $query->where('assignment_start_date', '<=', $date)
                ->where('assignment_end_date', '>=', $date);
        }

        // Get all non-cancelled assignments
        $assignments = $query->get();

        // Group by staff roster ID and check dates for each
        $conflicts = collect();
        
        $assignmentsByStaff = $assignments->groupBy('staff_roster_id');

        foreach ($assignmentsByStaff as $staffRosterId => $staffAssignments) {
            if ($staffAssignments->count() < 2) {
                continue; // No potential conflicts if only one assignment
            }

            // Check each date in the range of all assignments for this staff member
            $allDates = collect();
            
            foreach ($staffAssignments as $assignment) {
                $startDate = Carbon::parse($assignment->assignment_start_date);
                $endDate = Carbon::parse($assignment->assignment_end_date);
                
                // Generate all dates in the range
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $allDates->push($currentDate->format('Y-m-d'));
                    $currentDate->addDay();
                }
            }

            // Find dates where multiple assignments exist at different facilities
            $uniqueDates = $allDates->unique();
            
            foreach ($uniqueDates as $checkDate) {
                $checkDateCarbon = Carbon::parse($checkDate);
                
                // Get assignments active on this date
                $activeOnDate = $staffAssignments->filter(function ($assignment) use ($checkDateCarbon) {
                    $start = Carbon::parse($assignment->assignment_start_date);
                    $end = Carbon::parse($assignment->assignment_end_date);
                    
                    return $checkDateCarbon->between($start, $end, true);
                });

                // Check if they are at different facilities
                $facilitiesOnDate = $activeOnDate->pluck('facility_id')->unique();
                
                if ($facilitiesOnDate->count() > 1) {
                    // Found a conflict - multiple facilities on same date
                    $staffRoster = $activeOnDate->first()->staffRoster;
                    
                    $conflicts->push([
                        'staff_roster_id' => $staffRosterId,
                        'user_id' => $staffRoster->user_id,
                        'user_name' => $staffRoster->user->first_name . ' ' . $staffRoster->user->surname,
                        'conflict_date' => $checkDate,
                        'facility_count' => $facilitiesOnDate->count(),
                        'assignments' => $activeOnDate->map(function ($assignment) {
                            return [
                                'assignment_id' => $assignment->id,
                                'facility_id' => $assignment->facility_id,
                                'facility_name' => $assignment->facility->name,
                                'assignment_start_date' => $assignment->assignment_start_date->format('Y-m-d'),
                                'assignment_end_date' => $assignment->assignment_end_date->format('Y-m-d'),
                                'assignment_type' => $assignment->assignment_type,
                            ];
                        })->values()->all(),
                    ]);
                    
                    // Only report the first conflict date for this staff member to avoid spam
                    break;
                }
            }
        }

        return $conflicts;
    }
}
