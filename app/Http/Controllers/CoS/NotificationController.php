<?php

namespace App\Http\Controllers\CoS;

use App\Http\Controllers\Controller;
use App\Models\IncidentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications hub with unread notifications.
     */
    public function index(Request $request)
    {
        $this->authorize('view', 'all_cases');

        $perPage = $request->input('per_page', 25);

        // Get unread notifications for current user
        $unreadNotifications = IncidentNotification::where('recipient_user_id', Auth::id())
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Get unread count
        $unreadCount = IncidentNotification::where('recipient_user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        // Get notification types for filtering
        $notificationTypes = [
            'CASE_CREATED' => 'Case Created',
            'CASE_COMPLETED' => 'Case Completed',
            'SLA_BREACH' => 'SLA Breach',
            'DOCUMENT_UPLOAD' => 'Document Upload',
            'NUDGE_SENT' => 'Nudge Reminder',
        ];

        return view('dashboards.cos.notifications.index', compact(
            'unreadNotifications',
            'unreadCount',
            'notificationTypes'
        ));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($notificationId)
    {
        $this->authorize('view', 'all_cases');

        try {
            $notification = IncidentNotification::where('recipient_user_id', Auth::id())
                ->findOrFail($notificationId);

            $notification->update(['read_at' => now()]);

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Notification marked as read');
        } catch (\Exception $e) {
            return back()->withError('Failed to mark notification as read');
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $this->authorize('view', 'all_cases');

        IncidentNotification::where('recipient_user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Get notifications API endpoint (for real-time updates).
     */
    public function getNotifications(Request $request)
    {
        $this->authorize('view', 'all_cases');

        $limit = $request->input('limit', 10);

        $notifications = IncidentNotification::where('recipient_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->with('incident')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->notification_type,
                    'incident_id' => $notification->incident_id,
                    'case_reference' => $notification->incident?->reference_number,
                    'message' => $this->getNotificationMessage($notification),
                    'read' => $notification->read_at !== null,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => IncidentNotification::where('recipient_user_id', Auth::id())
                ->whereNull('read_at')
                ->count(),
        ]);
    }

    /**
     * Filter notifications.
     */
    public function filter(Request $request)
    {
        $this->authorize('view', 'all_cases');

        $type = $request->input('type');
        $status = $request->input('status', 'unread'); // unread, read, all

        $query = IncidentNotification::where('recipient_user_id', Auth::id());

        if (!empty($type)) {
            $query->where('notification_type', $type);
        }

        if ($status === 'unread') {
            $query->whereNull('read_at');
        } elseif ($status === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('dashboards.cos.notifications.filtered', compact('notifications', 'type', 'status'));
    }

    /**
     * Get human-readable notification message.
     */
    private function getNotificationMessage(IncidentNotification $notification): string
    {
        $case = $notification->incident;
        $worker = $case ? ($case->first_name . ' ' . $case->surname) : 'Unknown Worker';

        return match($notification->notification_type) {
            'CASE_CREATED' => "New case created: $worker - {$case?->reference_number}",
            'CASE_COMPLETED' => "Case completed: $worker - {$case?->reference_number}",
            'SLA_BREACH' => "SLA breach: {$case?->reference_number} - $worker",
            'DOCUMENT_UPLOAD' => "Documents uploaded for {$case?->reference_number}",
            'NUDGE_SENT' => "Reminder sent for {$case?->reference_number}",
            default => 'Notification',
        };
    }
}
