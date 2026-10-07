<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\SupportTicket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    /**
     * Create a new ticket instance.
     */
    public function __construct(private TicketService $ticketService)
    {
    }

    /**
     * Store a newly created support ticket in storage.
     *
     * Handles POST /api/tickets requests and creates a new support ticket
     * with the provided title, category, and description.
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        try {
            $ticket = $this->ticketService->createTicket(
                user: $request->user(),
                title: $request->validated('title'),
                category: $request->validated('category'),
                description: $request->validated('description')
            );

            return response()->json([
                'success' => true,
                'message' => 'Support ticket created successfully.',
                'data' => [
                    'ticket_id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'status' => $ticket->status,
                    'created_at' => $ticket->created_at,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create support ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified support ticket with conversation thread.
     *
     * Shows ticket details and all replies for both originator and assigned super admin.
     * Returns both web view and JSON API responses.
     */
    public function show(SupportTicket $ticket)
    {
        // Verify authorization
        $user = auth()->user();
        $isOriginator = $ticket->originator_user_id === $user->id;
        $isAssignedSuperAdmin = $ticket->routed_to_super_admin_id === $user->id;
        $isSuperAdmin = $user->hasRole('super_admin');

        if (!$isOriginator && !$isAssignedSuperAdmin && !$isSuperAdmin) {
            abort(403, 'You do not have permission to view this ticket.');
        }

        // Load relationships
        $ticket->load(['originator', 'assignedSuperAdmin', 'replies' => function ($query) {
            $query->orderBy('created_at', 'asc')->with('sender');
        }]);

        // Return JSON if API request
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'title' => $ticket->title,
                    'category' => $ticket->category,
                    'description' => $ticket->description,
                    'status' => $ticket->status,
                    'originator' => $ticket->originator ? [
                        'id' => $ticket->originator->id,
                        'name' => $ticket->originator->name,
                        'email' => $ticket->originator->email,
                    ] : null,
                    'assigned_super_admin' => $ticket->assignedSuperAdmin ? [
                        'id' => $ticket->assignedSuperAdmin->id,
                        'name' => $ticket->assignedSuperAdmin->name,
                        'email' => $ticket->assignedSuperAdmin->email,
                    ] : null,
                    'replies' => $ticket->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'message' => $reply->message,
                            'sender' => [
                                'id' => $reply->sender->id,
                                'name' => $reply->sender->name,
                                'role' => $reply->sender_role,
                            ],
                            'created_at' => $reply->created_at,
                        ];
                    }),
                    'created_at' => $ticket->created_at,
                    'updated_at' => $ticket->updated_at,
                    'current_user_role' => $user->role,
                ],
            ]);
        }

        // Return web view
        return view('support_tickets.show', compact('ticket'));
    }

    /**
     * Store a new reply to a support ticket.
     *
     * Handles POST /api/tickets/{id}/replies requests and creates a new reply.
     */
    public function storeReply(\App\Http\Requests\TicketReplyRequest $request, SupportTicket $ticket): JsonResponse
    {
        try {
            $reply = $this->ticketService->replyToTicket(
                superAdminUser: $request->user(),
                ticketId: $ticket->id,
                message: $request->validated('message')
            );

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'data' => [
                    'reply_id' => $reply->id,
                    'message' => $reply->message,
                    'sender' => $request->user()->name,
                    'created_at' => $reply->created_at,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the status of a support ticket.
     *
     * Handles PUT /api/tickets/{id}/status requests.
     */
    public function updateStatus(\App\Http\Requests\TicketStatusRequest $request, SupportTicket $ticket): JsonResponse
    {
        try {
            $updatedTicket = $this->ticketService->updateTicketStatus(
                superAdminUser: $request->user(),
                ticketId: $ticket->id,
                newStatus: $request->validated('status')
            );

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully.',
                'data' => [
                    'ticket_id' => $updatedTicket->id,
                    'ticket_number' => $updatedTicket->ticket_number,
                    'status' => $updatedTicket->status,
                    'updated_at' => $updatedTicket->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all replies for a specific support ticket.
     *
     * Handles GET /support/ticket/{ticket}/replies-json requests and returns all replies
     * in chronological order with sender information.
     */
    public function getReplies(SupportTicket $ticket): JsonResponse
    {
        // Verify authorization
        $user = auth()->user();
        $isOriginator = $ticket->originator_user_id === $user->id;
        $isAssignedSuperAdmin = $ticket->routed_to_super_admin_id === $user->id;
        $isSuperAdmin = $user->hasRole('super_admin');

        if (!$isOriginator && !$isAssignedSuperAdmin && !$isSuperAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Load replies in chronological order with sender info
        $ticket->load(['replies' => function ($query) {
            $query->orderBy('created_at', 'asc')->with('sender');
        }]);

        return response()->json([
            'success' => true,
            'data' => $ticket->replies->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'support_ticket_id' => $reply->support_ticket_id,
                    'message' => $reply->message,
                    'sender' => [
                        'id' => $reply->sender->id,
                        'name' => $reply->sender->name,
                        'email' => $reply->sender->email,
                        'role' => $reply->sender_role,
                    ],
                    'created_at' => $reply->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $reply->updated_at->format('Y-m-d H:i:s'),
                ];
            })->toArray(),
        ]);
    }


    /**
     * Display a listing of the authenticated user's support tickets.
     *
     * Shows tickets created by the authenticated user with optional filtering.
     * Supports search by ticket ID, title, and filtering by status/category.
     */
    public function myTickets()
    {
        $user = auth()->user();
        
        // Build query with filters
        $query = SupportTicket::where('originator_user_id', $user->id);

        // Search by ticket ID (exact match) or title (contains)
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (request()->filled('status')) {
            $statuses = is_array(request('status')) 
                ? request('status') 
                : [request('status')];
            $query->whereIn('status', $statuses);
        }

        // Filter by category
        if (request()->filled('category')) {
            $categories = is_array(request('category')) 
                ? request('category') 
                : [request('category')];
            $query->whereIn('category', $categories);
        }

        // Filter by date range
        if (request()->filled('created_from')) {
            $query->where('created_at', '>=', request('created_from'));
        }
        if (request()->filled('created_to')) {
            $query->where('created_at', '<=', request('created_to') . ' 23:59:59');
        }

        // Sort with OPEN/IN_PROGRESS first, then by date
        $tickets = $query
            ->with('replies')
            ->orderByRaw("CASE WHEN status IN ('OPEN', 'IN_PROGRESS') THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 20));

        // Return JSON if API request
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tickets->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'title' => $ticket->title,
                        'category' => $ticket->category,
                        'status' => $ticket->status,
                        'created_at' => $ticket->created_at,
                        'updated_at' => $ticket->updated_at,
                    ];
                }),
                'pagination' => [
                    'total' => $tickets->total(),
                    'per_page' => $tickets->perPage(),
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                ],
            ]);
        }

        return view('support_tickets.my_tickets', compact('tickets'));
    }
}
