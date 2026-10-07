<?php

namespace App\Services;

use App\Events\TicketCreated;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketService
{
    /**
     * Create a new support ticket.
     *
     * @param User $user The user creating the ticket (originator)
     * @param string $title Ticket title (3-255 chars)
     * @param string $category Ticket category
     * @param string $description Ticket description (10-5000 chars)
     *
     * @return SupportTicket Created ticket instance
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createTicket(User $user, string $title, string $category, string $description): SupportTicket
    {
        // Generate unique ticket number
        $ticketNumber = $this->generateTicketNumber();

        // Create the ticket
        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'originator_user_id' => $user->id,
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'status' => 'OPEN',
            'created_by_role' => $user->role,
            'last_activity_at' => now(),
        ]);

        // Dispatch TicketCreated event for routing and notifications
        event(new TicketCreated($ticket, $user, $ticketNumber));

        return $ticket;
    }

    /**
     * Generate a unique ticket number.
     * Format: TICKET-YYYY-NNNN (e.g., TICKET-2025-0001)
     *
     * @return string
     */
    private function generateTicketNumber(): string
    {
        $year = now()->year;
        $count = SupportTicket::where('ticket_number', 'like', "TICKET-{$year}-%")->count();
        $nextNumber = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "TICKET-{$year}-{$nextNumber}";
    }

    /**
     * Add a reply to an existing support ticket.
     *
     * @param User $superAdminUser The super admin user replying
     * @param int $ticketId The ticket ID to reply to
     * @param string $message The reply message (2-5000 chars)
     *
     * @return \App\Models\TicketReply Created reply instance
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function replyToTicket(User $superAdminUser, int $ticketId, string $message): \App\Models\TicketReply
    {
        // Get the ticket
        $ticket = SupportTicket::findOrFail($ticketId);

        // Verify the ticket is not closed
        if ($ticket->status === 'CLOSED') {
            throw new \Exception('Cannot reply to a closed ticket.');
        }

        // Create the reply
        $reply = \App\Models\TicketReply::create([
            'support_ticket_id' => $ticketId,
            'sender_user_id' => $superAdminUser->id,
            'message' => $message,
            'sender_role' => $superAdminUser->role,
            'status' => 'SENT',
        ]);

        // Update ticket's updated_at timestamp
        $ticket->update([
            'updated_at' => now(),
            'last_activity_at' => now(),
        ]);

        // Dispatch TicketReplyAdded event for notifications
        event(new \App\Events\TicketReplyAdded($ticket, $reply, $ticket->originator));

        return $reply;
    }

    /**
     * Update the status of a support ticket.
     *
     * @param User $superAdminUser The super admin user updating the status
     * @param int $ticketId The ticket ID to update
     * @param string $newStatus The new status (OPEN, IN_PROGRESS, RESOLVED, CLOSED)
     *
     * @return SupportTicket Updated ticket instance
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateTicketStatus(User $superAdminUser, int $ticketId, string $newStatus): SupportTicket
    {
        $validStatuses = ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'];

        if (!in_array($newStatus, $validStatuses)) {
            throw new \Exception("Invalid status. Must be one of: " . implode(', ', $validStatuses));
        }

        // Get the ticket
        $ticket = SupportTicket::findOrFail($ticketId);
        $oldStatus = $ticket->status;

        // Update the ticket status
        $ticket->update([
            'status' => $newStatus,
            'updated_at' => now(),
            'last_activity_at' => now(),
        ]);

        // Dispatch TicketStatusChanged event for notifications
        event(new \App\Events\TicketStatusChanged($ticket, $oldStatus, $newStatus, $ticket->originator));

        return $ticket;
    }
}
