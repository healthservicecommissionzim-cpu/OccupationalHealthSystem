<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class SuperAdminTicketController extends Controller
{
    /**
     * Display a listing of all support tickets for the super admin.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::query();

        // Get authenticated user
        $user = auth()->user();

        // Filter tickets - show all tickets for super_admin role or if they have manage_all_tickets permission
        $hasManageAllPermission = $user->hasPermission('manage_all_tickets');
        $isSuperAdmin = $user->hasRole('super_admin');
        
        if (!$hasManageAllPermission && !$isSuperAdmin) {
            // Regular super admin - show only tickets assigned to them or unassigned tickets
            $query->where(function ($q) use ($user) {
                $q->where('routed_to_super_admin_id', $user->id)
                  ->orWhereNull('routed_to_super_admin_id');
            });
        }
        // If they have manage_all_tickets permission OR are super_admin, show all tickets

        // Filter by status (supports array for multiple select)
        if ($request->filled('status')) {
            $statuses = is_array($request->status) 
                ? $request->status 
                : [$request->status];
            $query->whereIn('status', $statuses);
        }

        // Filter by category (supports array for multiple select)
        if ($request->filled('category')) {
            $categories = is_array($request->category) 
                ? $request->category 
                : [$request->category];
            $query->whereIn('category', $categories);
        }

        // Filter by date range
        if ($request->filled('created_from')) {
            $query->where('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->where('created_at', '<=', $request->created_to . ' 23:59:59');
        }

        // Search functionality - by ticket ID, title, or originator name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('originator', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by created_at (newest first) or by specified column
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        if (in_array($sort, ['created_at', 'status', 'updated_at'])) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $tickets = $query->with(['originator', 'assignedSuperAdmin'])->paginate($perPage);

        // Return JSON if API request
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tickets->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'title' => $ticket->title,
                        'category' => $ticket->category,
                        'status' => $ticket->status,
                        'originator' => $ticket->originator ? [
                            'id' => $ticket->originator->id,
                            'name' => $ticket->originator->name,
                            'email' => $ticket->originator->email,
                        ] : null,
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

        return view('support_tickets.index', compact('tickets'));
    }

    /**
     * Get count of pending tickets for super admin.
     */
    public function getPendingCount(Request $request)
    {
        $user = auth()->user();

        $count = SupportTicket::where('routed_to_super_admin_id', $user->id)
            ->whereIn('status', ['OPEN', 'IN_PROGRESS'])
            ->count();

        return response()->json([
            'pending_count' => $count,
        ]);
    }
}
