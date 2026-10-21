<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;

class CommunicationLogsController extends Controller
{
    /**
     * Display communication logs
     */
    public function index(Request $request)
    {
        $query = CommunicationLog::query();

        // Filter by communication type
        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        // Filter by channel
        if ($request->has('channel') && $request->channel) {
            $query->byChannel($request->channel);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        // Date range filter
        $dateRange = $request->get('date_range', '30');
        if ($dateRange === 'all') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->recent((int)$dateRange);
        }

        // Search by recipient email or phone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25);

        // Get statistics
        $stats = [
            'total' => CommunicationLog::count(),
            'sent' => CommunicationLog::where('status', 'SENT')->count(),
            'failed' => CommunicationLog::where('status', 'FAILED')->count(),
            'email' => CommunicationLog::where('channel', 'email')->count(),
            'sms' => CommunicationLog::where('channel', 'sms')->count(),
        ];

        return view('super-admin.communications.logs', compact('logs', 'stats'));
    }

    /**
     * Show communication details
     */
    public function show(CommunicationLog $log)
    {
        return view('super-admin.communications.show', compact('log'));
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        $query = CommunicationLog::query();

        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        if ($request->has('channel') && $request->channel) {
            $query->byChannel($request->channel);
        }

        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $csv = "Communication Type,Channel,Recipient Email,Recipient Phone,Subject,Status,Sent At,Related Entity\n";
        
        foreach ($logs as $log) {
            $entity = $log->related_entity_type ? "{$log->related_entity_type}:{$log->related_entity_id}" : 'N/A';
            $csv .= "\"{$log->communication_type}\",\"{$log->channel}\",\"{$log->recipient_email}\",\"{$log->recipient_phone}\",\"{$log->subject}\",\"{$log->status}\",\"{$log->sent_at}\",\"{$entity}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="communication_logs_' . now()->format('Y-m-d_H-i-s') . '.csv"',
        ]);
    }

    /**
     * Get communication log details as JSON for modal
     */
    public function getDetails(CommunicationLog $log)
    {
        // Parse retry history if it's JSON
        $retryHistory = [];
        if ($log->retry_history) {
            $retryHistory = is_string($log->retry_history)
                ? json_decode($log->retry_history, true)
                : $log->retry_history;
        }

        // Build delivery timeline
        $timeline = [];

        if ($log->first_attempt_at) {
            $timeline[] = [
                'title' => 'First Send Attempt',
                'timestamp' => \Carbon\Carbon::parse($log->first_attempt_at)->format('M d, Y H:i:s'),
                'color' => '#3b82f6'
            ];
        }

        if ($log->sent_at) {
            $timeline[] = [
                'title' => 'Message Sent',
                'timestamp' => \Carbon\Carbon::parse($log->sent_at)->format('M d, Y H:i:s'),
                'color' => '#10b981'
            ];
        }

        if ($log->delivered_at) {
            $timeline[] = [
                'title' => 'Delivery Confirmed',
                'timestamp' => \Carbon\Carbon::parse($log->delivered_at)->format('M d, Y H:i:s'),
                'color' => '#059669'
            ];
        }

        if ($log->webhook_received_at) {
            $timeline[] = [
                'title' => 'Webhook Received',
                'timestamp' => \Carbon\Carbon::parse($log->webhook_received_at)->format('M d, Y H:i:s'),
                'color' => '#8b5cf6'
            ];
        }

        if ($log->delivery_status === 'FAILED' || $log->delivery_status === 'BOUNCED') {
            $timeline[] = [
                'title' => ucfirst($log->delivery_status),
                'timestamp' => $log->updated_at->format('M d, Y H:i:s'),
                'color' => '#ef4444'
            ];
        }

        return response()->json([
            'id' => $log->id,
            'communication_type' => $log->communication_type,
            'channel' => $log->channel,
            'status' => $log->status,
            'delivery_status' => $log->delivery_status ?? 'PENDING',
            'recipient_email' => $log->recipient_email,
            'recipient_phone' => $log->recipient_phone,
            'recipient_name' => optional($log->recipient)->name ?? 'N/A',
            'subject' => $log->subject,
            'message_preview' => $log->message_preview,
            'full_content' => $log->full_content,
            'sent_at_formatted' => $log->sent_at?->format('M d, Y H:i:s') ?? '-',
            'delivered_at_formatted' => $log->delivered_at?->format('M d, Y H:i:s') ?? null,
            'failure_reason' => $log->failure_reason,
            'failure_code' => $log->failure_code,
            'delivery_attempts' => $log->delivery_attempts ?? 1,
            'provider' => $log->provider,
            'provider_message_id' => $log->provider_message_id,
            'timeline' => $timeline,
            'retry_history' => $retryHistory,
        ]);
    }
}
