<?php

namespace App\Services\Roster;

use App\Models\User;
use App\Models\StaffRoster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RosterAccessService
{
    /**
     * Get rosters filtered by user role and permissions.
     * 
     * Super Admin: all rosters
     * Central HR / MOHHC HR: all rosters nationally
     * District HR: rosters for facilities in their district
     * Hospital HR: current staff at their facility
     * HSC Conditions: all rosters (read-only)
     * 
     * @param User $user
     * @return Builder
     */
    public function getRostersForUser(User $user): Builder
    {
        $query = StaffRoster::query()
            ->with(['user', 'primaryFacility', 'assignments']);

        // Super Admin: full access to all rosters
        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Central HR and MOHHC HR: national-level access to all rosters
        if ($user->hasAnyRole(['central_hr', 'mohhc_hr'])) {
            return $query;
        }

        // HSC Conditions: read-only access to all rosters for compliance monitoring
        if ($user->hasRole('hsc_conditions')) {
            return $query;
        }

        // District HR: rosters for facilities in their assigned district
        if ($user->hasRole('district_hr')) {
            if (!$user->district_id) {
                // No district assigned - return empty query
                return $query->whereRaw('1 = 0');
            }

            // Get facility IDs in the user's district
            $facilityIds = \App\Models\Facility::where('district_id', $user->district_id)
                ->pluck('id')
                ->toArray();

            // Filter rosters where primary facility is in the district
            // OR where the staff has assignments in the district
            return $query->where(function ($q) use ($facilityIds) {
                $q->whereIn('primary_facility_id', $facilityIds)
                    ->orWhereHas('assignments', function ($assignmentQuery) use ($facilityIds) {
                        $assignmentQuery->whereIn('facility_id', $facilityIds);
                    });
            });
        }

        // Hospital HR: current staff at their assigned facility
        if ($user->hasRole('hospital_hr')) {
            if (!$user->facility_id) {
                // No facility assigned - return empty query
                return $query->whereRaw('1 = 0');
            }

            // Filter rosters where primary facility matches user's facility
            // OR where the staff has active assignments at this facility
            return $query->where(function ($q) use ($user) {
                $q->where('primary_facility_id', $user->facility_id)
                    ->orWhereHas('assignments', function ($assignmentQuery) use ($user) {
                        $assignmentQuery->where('facility_id', $user->facility_id)
                            ->where('status', 'active');
                    });
            });
        }

        // Default: no access for other roles
        return $query->whereRaw('1 = 0');
    }

    /**
     * Get current staff at a specific facility (including rotational staff).
     * 
     * @param int $facilityId
     * @param \Carbon\Carbon|null $date
     * @return Collection
     */
    public function getCurrentStaffAtFacility(int $facilityId, ?\Carbon\Carbon $date = null): Collection
    {
        $date = $date ?? now();

        return StaffRoster::query()
            ->with(['user', 'primaryFacility', 'assignments'])
            ->where(function ($query) use ($facilityId, $date) {
                // Permanent staff at this facility
                $query->where('primary_facility_id', $facilityId)
                    // OR rotational staff with active assignments at this facility
                    ->orWhereHas('assignments', function ($assignmentQuery) use ($facilityId, $date) {
                        $assignmentQuery->where('facility_id', $facilityId)
                            ->where('assignment_start_date', '<=', $date)
                            ->where('assignment_end_date', '>=', $date)
                            ->where('status', '!=', 'cancelled');
                    });
            })
            ->where('active', true)
            ->get();
    }

    /**
     * Check if user has read access to a specific roster.
     * 
     * @param User $user
     * @param StaffRoster $roster
     * @return bool
     */
    public function canViewRoster(User $user, StaffRoster $roster): bool
    {
        // Super Admin, Central HR, MOHHC HR, HSC Conditions: full access
        if ($user->hasAnyRole(['super_admin', 'central_hr', 'mohhc_hr', 'hsc_conditions'])) {
            return true;
        }

        // District HR: check if roster is in their district
        if ($user->hasRole('district_hr') && $user->district_id) {
            $facilityInDistrict = \App\Models\Facility::where('id', $roster->primary_facility_id)
                ->where('district_id', $user->district_id)
                ->exists();

            if ($facilityInDistrict) {
                return true;
            }

            // Check if any assignments are in their district
            $assignmentsInDistrict = $roster->assignments()
                ->whereHas('facility', function ($q) use ($user) {
                    $q->where('district_id', $user->district_id);
                })
                ->exists();

            return $assignmentsInDistrict;
        }

        // Hospital HR: check if roster is at their facility
        if ($user->hasRole('hospital_hr') && $user->facility_id) {
            if ($roster->primary_facility_id === $user->facility_id) {
                return true;
            }

            // Check if any active assignments are at their facility
            $assignmentsAtFacility = $roster->assignments()
                ->where('facility_id', $user->facility_id)
                ->where('status', 'active')
                ->exists();

            return $assignmentsAtFacility;
        }

        return false;
    }

    /**
     * Check if user can modify rosters (create, update, delete).
     * Only Super Admins have modification rights.
     * 
     * @param User $user
     * @return bool
     */
    public function canModifyRosters(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
