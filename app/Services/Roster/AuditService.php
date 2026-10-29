<?php

namespace App\Services\Roster;

use App\Models\RosterAuditLog;
use App\Models\StaffRoster;
use App\Models\RosterAssignment;
use Illuminate\Support\Collection;

class AuditService
{
    /**
     * Log roster creation.
     *
     * @param StaffRoster $roster
     * @param int $userId
     * @return void
     */
    public function logRosterCreation(StaffRoster $roster, int $userId): void
    {
        RosterAuditLog::create([
            'auditable_type' => StaffRoster::class,
            'auditable_id' => $roster->id,
            'action_type' => 'created',
            'user_id' => $userId,
            'old_values' => null,
            'new_values' => $roster->toArray(),
        ]);
    }

    /**
     * Log roster update with before/after value tracking.
     *
     * @param StaffRoster $roster
     * @param array $originalAttributes
     * @param int $userId
     * @return void
     */
    public function logRosterUpdate(
        StaffRoster $roster,
        array $originalAttributes,
        int $userId
    ): void {
        RosterAuditLog::create([
            'auditable_type' => StaffRoster::class,
            'auditable_id' => $roster->id,
            'action_type' => 'updated',
            'user_id' => $userId,
            'old_values' => $originalAttributes,
            'new_values' => $roster->toArray(),
        ]);
    }

    /**
     * Log assignment creation.
     *
     * @param RosterAssignment $assignment
     * @param int $userId
     * @return void
     */
    public function logAssignmentCreation(
        RosterAssignment $assignment,
        int $userId
    ): void {
        RosterAuditLog::create([
            'auditable_type' => RosterAssignment::class,
            'auditable_id' => $assignment->id,
            'action_type' => 'created',
            'user_id' => $userId,
            'old_values' => null,
            'new_values' => $assignment->toArray(),
        ]);
    }

    /**
     * Log assignment update with before/after value tracking.
     *
     * @param RosterAssignment $assignment
     * @param array $originalAttributes
     * @param int $userId
     * @return void
     */
    public function logAssignmentUpdate(
        RosterAssignment $assignment,
        array $originalAttributes,
        int $userId
    ): void {
        RosterAuditLog::create([
            'auditable_type' => RosterAssignment::class,
            'auditable_id' => $assignment->id,
            'action_type' => 'updated',
            'user_id' => $userId,
            'old_values' => $originalAttributes,
            'new_values' => $assignment->toArray(),
        ]);
    }

    /**
     * Log assignment cancellation.
     *
     * @param RosterAssignment $assignment
     * @param int $userId
     * @return void
     */
    public function logAssignmentCancellation(
        RosterAssignment $assignment,
        int $userId
    ): void {
        RosterAuditLog::create([
            'auditable_type' => RosterAssignment::class,
            'auditable_id' => $assignment->id,
            'action_type' => 'cancelled',
            'user_id' => $userId,
            'old_values' => null,
            'new_values' => $assignment->toArray(),
        ]);
    }

    /**
     * Get audit logs for a roster or assignment.
     * Orders logs by created_at descending.
     * Includes user relationship for display.
     *
     * @param string $auditableType (StaffRoster::class or RosterAssignment::class)
     * @param int $auditableId
     * @return Collection
     */
    public function getAuditLogs(string $auditableType, int $auditableId): Collection
    {
        return RosterAuditLog::where('auditable_type', $auditableType)
            ->where('auditable_id', $auditableId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
