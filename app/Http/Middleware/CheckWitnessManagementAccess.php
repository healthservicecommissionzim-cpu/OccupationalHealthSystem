<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWitnessManagementAccess
{
    /**
     * Verify that the user has supervisor role and is the assigned supervisor for the incident.
     *
     * This middleware checks:
     * 1. User has 'supervisor' role
     * 2. User is the assigned supervisor for this incident (via assigned_supervisor_id)
     * 3. Prevents cross-supervisor access
     *
     * Requirements: 9.1, 9.2, 9.3
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Requirement 9.1: Verify user has supervisor role
        if (!$user || !$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to manage witnesses.');
        }

        // Get the incident from route parameters
        $incident = $request->route('incident');

        if (!$incident) {
            abort(404, 'Incident not found.');
        }

        // Requirement 9.2: Filter witnesses by supervisor responsibility
        // Block cross-supervisor access - only assigned supervisor can manage witnesses
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        return $next($request);
    }
}
