<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Services\TicketAssignmentService;
use Illuminate\Support\Facades\Log;

class RouteTicketToSuperAdmin
{
    /**
     * Create the event listener.
     */
    public function __construct(private TicketAssignmentService $assignmentService)
    {
    }

    /**
     * Handle the TicketCreated event and route ticket to super admin.
     */
    public function handle(TicketCreated $event): void
    {
        try {
            $assignedSuperAdmin = $this->assignmentService->loadBalanceAssignToSuperAdmin($event->ticket);

            // If no super admin found, log and return
            if ($assignedSuperAdmin === null) {
                Log::warning('No super admin available to assign ticket', [
                    'ticket_id' => $event->ticket->id,
                    'ticket_number' => $event->ticket->ticket_number,
                ]);
                return;
            }

            // Update the ticket with the assigned super admin
            $event->ticket->update([
                'routed_to_super_admin_id' => $assignedSuperAdmin->id,
            ]);

            Log::info('Ticket routed to super admin', [
                'ticket_id' => $event->ticket->id,
                'ticket_number' => $event->ticket->ticket_number,
                'assigned_to_super_admin_id' => $assignedSuperAdmin->id,
                'assigned_to_super_admin_name' => $assignedSuperAdmin->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to route ticket to super admin', [
                'ticket_id' => $event->ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
