<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RosterAssignment extends Model
{
    protected $table = 'roster_assignments';

    protected $fillable = [
        'staff_roster_id',
        'facility_id',
        'assignment_start_date',
        'assignment_end_date',
        'cost_center_facility_id',
        'assignment_type',
        'supervisor_id',
        'status',
        'notes',
        'created_by_user_id',
    ];

    protected $casts = [
        'assignment_start_date' => 'date',
        'assignment_end_date' => 'date',
    ];

    /**
     * Get the staff roster this assignment belongs to.
     */
    public function staffRoster(): BelongsTo
    {
        return $this->belongsTo(StaffRoster::class, 'staff_roster_id');
    }

    /**
     * Get the facility where this assignment is located.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    /**
     * Get the cost center facility responsible for this assignment.
     */
    public function costCenterFacility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'cost_center_facility_id');
    }

    /**
     * Get the supervisor for this assignment.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the user who created this assignment.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all incidents linked to this assignment.
     */
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'roster_assignment_id');
    }

    /**
     * Scope to filter only active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter assignments active on a specific date.
     */
    public function scopeActiveOn($query, Carbon $date)
    {
        return $query->where('assignment_start_date', '<=', $date)
            ->where('assignment_end_date', '>=', $date)
            ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope to filter assignments at a specific facility.
     */
    public function scopeAtFacility($query, int $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Override delete method to check for linked incidents
     * 
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        // Check if this assignment is linked to any incidents
        $linkedIncidentCount = $this->incidents()->count();

        if ($linkedIncidentCount > 0) {
            throw new \Exception(
                "Cannot delete assignment: This assignment is linked to {$linkedIncidentCount} incident(s). " .
                "Please cancel the assignment instead or contact system administrator."
            );
        }

        return parent::delete();
    }
}
