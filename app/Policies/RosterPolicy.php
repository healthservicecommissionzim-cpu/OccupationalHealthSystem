<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StaffRoster;
use App\Models\RosterAssignment;
use App\Services\Roster\RosterAccessService;

class RosterPolicy
{
    protected $rosterAccessService;

    public function __construct(RosterAccessService $rosterAccessService)
    {
        $this->rosterAccessService = $rosterAccessService;
    }

    /**
     * Determine if the user can view any rosters.
     * 
     * Roles with access:
     * - Super Admin: full access
     * - Central HR / MOHHC HR: national access
     * - District HR: district-scoped access
     * - Hospital HR: facility-scoped access
     * - HSC Conditions: read-only national access
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'super_admin',
            'central_hr',
            'mohhc_hr',
            'district_hr',
            'hospital_hr',
            'hsc_conditions'
        ]);
    }

    /**
     * Determine if the user can view a specific roster.
     * 
     * @param User $user
     * @param StaffRoster $roster
     * @return bool
     */
    public function view(User $user, StaffRoster $roster): bool
    {
        return $this->rosterAccessService->canViewRoster($user, $roster);
    }

    /**
     * Determine if the user can create rosters.
     * Only Super Admins can create rosters.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can update a roster.
     * Only Super Admins can update rosters.
     * 
     * @param User $user
     * @param StaffRoster $roster
     * @return bool
     */
    public function update(User $user, StaffRoster $roster): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can delete (deactivate) a roster.
     * Only Super Admins can delete rosters.
     * 
     * @param User $user
     * @param StaffRoster $roster
     * @return bool
     */
    public function delete(User $user, StaffRoster $roster): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can create roster assignments.
     * Only Super Admins can create assignments.
     * 
     * @param User $user
     * @return bool
     */
    public function createAssignment(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can update a roster assignment.
     * Only Super Admins can update assignments.
     * 
     * @param User $user
     * @param RosterAssignment $assignment
     * @return bool
     */
    public function updateAssignment(User $user, RosterAssignment $assignment): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can delete (cancel) a roster assignment.
     * Only Super Admins can cancel assignments.
     * 
     * @param User $user
     * @param RosterAssignment $assignment
     * @return bool
     */
    public function deleteAssignment(User $user, RosterAssignment $assignment): bool
    {
        return $user->hasRole('super_admin');
    }
}
