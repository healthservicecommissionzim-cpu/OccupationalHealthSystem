<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffRoster extends Model
{
    protected $table = 'staff_rosters';

    protected $fillable = [
        'user_id',
        'staff_type',
        'primary_facility_id',
        'active',
        'start_date',
        'end_date',
        'notes',
        'created_by_user_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user associated with this roster.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the primary facility for this roster.
     */
    public function primaryFacility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'primary_facility_id');
    }

    /**
     * Get the user who created this roster.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all assignments for this roster.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(RosterAssignment::class, 'staff_roster_id');
    }

    /**
     * Scope to filter only active rosters.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to filter by staff type.
     */
    public function scopeByStaffType($query, string $type)
    {
        return $query->where('staff_type', $type);
    }

    /**
     * Scope to filter by facility.
     */
    public function scopeByFacility($query, int $facilityId)
    {
        return $query->where('primary_facility_id', $facilityId);
    }

    /**
     * Override delete method to check for linked incidents
     * 
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        // Check if any assignments are linked to incidents
        $linkedIncidentCount = $this->assignments()
            ->whereHas('incidents')
            ->count();

        if ($linkedIncidentCount > 0) {
            throw new \Exception(
                "Cannot delete roster: {$linkedIncidentCount} assignment(s) are linked to incidents. " .
                "Please mark the roster as inactive instead."
            );
        }

        return parent::delete();
    }
}
