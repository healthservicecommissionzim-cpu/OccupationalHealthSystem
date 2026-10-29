<?php

namespace App\Services\Roster;

use App\Models\StaffRoster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RosterService
{
    protected ValidationService $validationService;
    protected AuditService $auditService;

    /**
     * Constructor to inject dependencies.
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
     * Create a new staff roster record.
     * 
     * @param int $userId
     * @param string $staffType (permanent|locum|student|rotational)
     * @param int $primaryFacilityId
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     * @param int $createdByUserId
     * @param string|null $notes
     * @return StaffRoster
     * @throws ValidationException
     */
    public function createRoster(
        int $userId,
        string $staffType,
        int $primaryFacilityId,
        Carbon $startDate,
        ?Carbon $endDate,
        int $createdByUserId,
        ?string $notes = null
    ): StaffRoster {
        // Prepare data for validation
        $data = [
            'user_id' => $userId,
            'staff_type' => $staffType,
            'primary_facility_id' => $primaryFacilityId,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'notes' => $notes,
            'created_by_user_id' => $createdByUserId,
        ];

        // Validate input using ValidationService
        $validated = $this->validationService->validateRosterData($data);

        // Create roster record within transaction
        $roster = DB::transaction(function () use ($validated) {
            return StaffRoster::create([
                'user_id' => $validated['user_id'],
                'staff_type' => $validated['staff_type'],
                'primary_facility_id' => $validated['primary_facility_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by_user_id' => $validated['created_by_user_id'],
                'active' => true,
            ]);
        });

        // Log creation using AuditService
        $this->auditService->logRosterCreation($roster, $createdByUserId);

        // Return created StaffRoster model
        return $roster;
    }

    /**
     * Update an existing roster record.
     * 
     * @param int $rosterId
     * @param array $attributes
     * @param int $updatedByUserId
     * @return StaffRoster
     * @throws ValidationException
     */
    public function updateRoster(
        int $rosterId,
        array $attributes,
        int $updatedByUserId
    ): StaffRoster {
        $roster = StaffRoster::findOrFail($rosterId);

        // Capture original values before update
        $originalAttributes = $roster->toArray();

        // Prepare data for validation (merge with existing roster data)
        $data = array_merge([
            'user_id' => $roster->user_id,
            'staff_type' => $roster->staff_type,
            'primary_facility_id' => $roster->primary_facility_id,
            'start_date' => $roster->start_date->format('Y-m-d'),
            'end_date' => $roster->end_date ? $roster->end_date->format('Y-m-d') : null,
            'notes' => $roster->notes,
            'created_by_user_id' => $roster->created_by_user_id,
        ], $attributes);

        // Validate input
        $validated = $this->validationService->validateRosterData($data);

        // Update roster within transaction
        $roster = DB::transaction(function () use ($roster, $validated) {
            $roster->update([
                'user_id' => $validated['user_id'],
                'staff_type' => $validated['staff_type'],
                'primary_facility_id' => $validated['primary_facility_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            return $roster->fresh();
        });

        // Log update with before/after values
        $this->auditService->logRosterUpdate($roster, $originalAttributes, $updatedByUserId);

        return $roster;
    }

    /**
     * Mark a roster as inactive (soft delete).
     * 
     * @param int $rosterId
     * @param int $deactivatedByUserId
     * @return bool
     */
    public function deactivateRoster(
        int $rosterId,
        int $deactivatedByUserId
    ): bool {
        $roster = StaffRoster::findOrFail($rosterId);

        // Capture original values before deactivation
        $originalAttributes = $roster->toArray();

        // Set active = false within transaction
        $result = DB::transaction(function () use ($roster) {
            $roster->active = false;
            return $roster->save();
        });

        // Log deactivation
        $this->auditService->logRosterUpdate(
            $roster->fresh(),
            $originalAttributes,
            $deactivatedByUserId
        );

        // Do not delete record
        return $result;
    }

    /**
     * Get roster by user ID.
     * 
     * @param int $userId
     * @return Collection<StaffRoster>
     */
    public function getRostersByUserId(int $userId): Collection
    {
        // Query rosters by user_id
        // Include relationships (primaryFacility, assignments)
        return StaffRoster::where('user_id', $userId)
            ->with(['primaryFacility', 'assignments'])
            ->get();
    }
}
