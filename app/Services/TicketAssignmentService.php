<?php

namespace App\Services;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TicketAssignmentService
{
    /**
     * Load-balance assign a ticket to a super admin with the fewest pending tickets.
     *
     * @param SupportTicket $ticket The ticket to assign
     * @return User The assigned super admin user
     */
    public function loadBalanceAssignToSuperAdmin(SupportTicket $ticket): User
    {
        // Get all users with super_admin role - try multiple methods
        $superAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();

        // If not found via roles relationship, try checking user role column directly
        if ($superAdmins->isEmpty()) {
            $superAdmins = User::where('role', 'super_admin')->get();
        }

        // If still no super admins exist, log and return null gracefully
        if ($superAdmins->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning('No super admin users found for ticket assignment', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
            ]);
            return null;
        }

        // Count pending tickets per super admin
        $assignedCounts = [];
        foreach ($superAdmins as $admin) {
            $pendingCount = SupportTicket::where('routed_to_super_admin_id', $admin->id)
                ->whereIn('status', ['OPEN', 'IN_PROGRESS'])
                ->count();
            $assignedCounts[$admin->id] = $pendingCount;
        }

        // Find super admin with minimum pending count
        $assignedSuperAdminId = array_key_first($assignedCounts);
        $minCount = $assignedCounts[$assignedSuperAdminId];

        foreach ($assignedCounts as $adminId => $count) {
            if ($count < $minCount) {
                $assignedSuperAdminId = $adminId;
                $minCount = $count;
            }
        }

        // Update ticket with assigned super admin
        $assignedSuperAdmin = $superAdmins->firstWhere('id', $assignedSuperAdminId);
        $ticket->update([
            'routed_to_super_admin_id' => $assignedSuperAdmin->id,
            'last_activity_at' => now(),
        ]);

        return $assignedSuperAdmin;
    }

    /**
     * Get assignment metrics for all super admins.
     *
     * @return array Array of metrics with admin ID, name, pending count, resolved count, and avg response time
     */
    public function getAssignmentMetrics(): array
    {
        $superAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();

        $metrics = [];
        foreach ($superAdmins as $admin) {
            $pendingCount = SupportTicket::where('routed_to_super_admin_id', $admin->id)
                ->whereIn('status', ['OPEN', 'IN_PROGRESS'])
                ->count();

            $resolvedCount = SupportTicket::where('routed_to_super_admin_id', $admin->id)
                ->whereIn('status', ['RESOLVED', 'CLOSED'])
                ->count();

            $metrics[] = [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'pending_count' => $pendingCount,
                'resolved_count' => $resolvedCount,
                'total_assigned' => $pendingCount + $resolvedCount,
            ];
        }

        return $metrics;
    }
}
