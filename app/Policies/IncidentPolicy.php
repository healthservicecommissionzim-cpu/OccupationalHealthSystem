<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class IncidentPolicy
{
    /**
     * Determine if the user can manage RTW plans for this incident
     */
    public function manageRTW(User $user, Incident $incident): bool
    {
        // HR users can manage RTW for their assigned cases
        if ($user->hasRole('hr')) {
            return $incident->routed_to_hr_user_id === $user->id || $user->hasPermission('manage_incidents');
        }

        // Supervisors can view their own cases' RTW (read-only)
        if ($user->hasRole('supervisor')) {
            return $incident->assigned_supervisor_id === $user->id;
        }

        return false;
    }
}
