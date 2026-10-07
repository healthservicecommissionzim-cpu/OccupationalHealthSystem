<?php

namespace App\Http\Controllers;

use App\Models\ITSupportTicket;
use App\Models\SupportTicket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(private TicketService $ticketService)
    {
    }

    public function createTicket()
    {
        return view('support.create_ticket');
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'category' => 'required|in:system_issue,feature_request,bug_report,account_issue,other',
        ]);

        try {
            // Normalize category from snake_case to Title Case
            $categoryMap = [
                'system_issue' => 'System Issue',
                'feature_request' => 'Feature Request',
                'bug_report' => 'Bug Report',
                'account_issue' => 'Account Issue',
                'other' => 'Other',
            ];

            $normalizedCategory = $categoryMap[$validated['category']] ?? $validated['category'];

            // Use the new TicketService to create a ticket
            $ticket = $this->ticketService->createTicket(
                user: auth()->user(),
                title: $validated['title'],
                category: $normalizedCategory,
                description: $validated['description']
            );

            return response()->json([
                'success' => true,
                'message' => 'Support ticket submitted successfully. Ticket #: ' . $ticket->ticket_number,
                'ticket' => [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'status' => $ticket->status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store public support request from welcome page
     * Accepts requests from unauthenticated users
     */
    public function storePublicRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:7|max:20',
            'category' => 'required|in:technical,account,incident,general,other',
            'description' => 'required|string|min:10|max:5000',
        ]);

        try {
            // Map category to SupportTicket category format
            $categoryMap = [
                'technical' => 'System Issue',
                'account' => 'Account Issue',
                'incident' => 'Bug Report',
                'general' => 'General Inquiry',
                'other' => 'Other',
            ];

            $normalizedCategory = $categoryMap[$validated['category']] ?? 'General Inquiry';

            // Create a support ticket for unauthenticated users
            $ticket = SupportTicket::create([
                'ticket_number' => 'PUB-' . strtoupper(uniqid()),
                'title' => $normalizedCategory,
                'category' => $normalizedCategory,
                'description' => $validated['description'],
                'status' => 'open',
                'created_by_role' => 'public_user',
                'originator_user_id' => null, // No user attached for public requests
                'originator_name' => $validated['name'],
                'originator_email' => $validated['email'],
                'originator_phone' => $validated['phone'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Support request submitted successfully. We will contact you shortly.',
                'ticket' => [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'status' => $ticket->status,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Public support request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit support request. Please try again later.',
            ], 500);
        }
    }
}
