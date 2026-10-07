<?php

namespace App\Services;

use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Log a CoS governance action to the audit trail.
     *
     * @param string $actionType The type of action (created, escalated, nudge_sent, etc.)
     * @param string $caseId The case ID this action relates to
     * @param array $data Additional data to log
     * @return AuditLog The created audit log entry
     */
    public function logAction(string $actionType, string $caseId, array $data = []): AuditLog
    {
        try {
            $userId = Auth::id() ?? $data['user_id'] ?? null;

            $auditData = [
                'action_type' => $actionType,
                'case_id' => $caseId,
                'user_id' => $userId,
                'action' => $data['action'] ?? 'CoS_' . strtoupper($actionType),
                'affected_entity' => $data['affected_entity'] ?? 'Case',
                'old_values' => $data['old_values'] ?? null,
                'new_values' => $data['new_values'] ?? null,
                'http_code' => $data['http_code'] ?? 200,
                'error_message' => $data['error_message'] ?? null,
            ];

            $auditLog = AuditLog::logForensicActivity($auditData);

            Log::info('CoS action logged', [
                'action_type' => $actionType,
                'case_id' => $caseId,
                'user_id' => $userId,
                'audit_log_id' => $auditLog->id,
            ]);

            return $auditLog;
        } catch (\Exception $e) {
            Log::error('Failed to log audit action', [
                'action_type' => $actionType,
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get audit log entries for a specific case with optional filtering.
     *
     * @param string $caseId The case ID
     * @param array $filters Optional filters (action_type, actor_id, start_date, end_date)
     * @param int $perPage Pagination size
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated audit logs
     */
    public function getAuditLog(string $caseId, array $filters = [], int $perPage = 50)
    {
        $query = AuditLog::forCase($caseId);

        // Filter by action type
        if (!empty($filters['action_type'])) {
            $query->byActionType($filters['action_type']);
        }

        // Filter by actor (user who performed the action)
        if (!empty($filters['actor_id'])) {
            $query->byActor($filters['actor_id']);
        }

        // Filter by date range
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = Carbon::parse($filters['start_date'])->startOfDay();
            $endDate = Carbon::parse($filters['end_date'])->endOfDay();
            $query->inDateRange($startDate, $endDate);
        }

        // Order chronologically
        return $query->chronological()->paginate($perPage);
    }

    /**
     * Generate a comprehensive audit report for a date range.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $filters Optional filters
     * @return array Report data including summary and entries
     */
    public function generateAuditReport(Carbon $startDate, Carbon $endDate, array $filters = []): array
    {
        $query = AuditLog::inDateRange($startDate, $endDate);

        // Apply filters
        if (!empty($filters['action_type'])) {
            $query->byActionType($filters['action_type']);
        }

        if (!empty($filters['user_id'])) {
            $query->byActor($filters['user_id']);
        }

        if (!empty($filters['case_id'])) {
            $query->forCase($filters['case_id']);
        }

        $logs = $query->chronological()->get();

        // Generate summary statistics
        $summary = [
            'total_actions' => $logs->count(),
            'action_types' => $logs->groupBy('action_type')->map(fn($group) => $group->count()),
            'unique_actors' => $logs->pluck('user_id')->unique()->count(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period_days' => $startDate->diffInDays($endDate) + 1,
        ];

        // Group by case for case-specific analysis
        $byCaseStats = $logs->groupBy('case_id')->map(function ($caseLogs) {
            return [
                'total_actions' => $caseLogs->count(),
                'action_types' => $caseLogs->groupBy('action_type')->map(fn($group) => $group->count()),
                'actors' => $caseLogs->pluck('user_id')->unique()->count(),
                'first_action' => $caseLogs->first()->created_at,
                'last_action' => $caseLogs->last()->created_at,
            ];
        });

        return [
            'summary' => $summary,
            'by_case' => $byCaseStats,
            'logs' => $logs->toArray(),
        ];
    }

    /**
     * Get audit log entries for a specific case as CSV-exportable data.
     *
     * @param string $caseId
     * @param array $filters Optional filters
     * @return array Array of audit log data for CSV export
     */
    public function getAuditLogForExport(string $caseId, array $filters = []): array
    {
        $query = AuditLog::forCase($caseId)->with(['user']);

        // Apply filters
        if (!empty($filters['action_type'])) {
            $query->byActionType($filters['action_type']);
        }

        if (!empty($filters['actor_id'])) {
            $query->byActor($filters['actor_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = Carbon::parse($filters['start_date'])->startOfDay();
            $endDate = Carbon::parse($filters['end_date'])->endOfDay();
            $query->inDateRange($startDate, $endDate);
        }

        return $query->chronological()->get()->map(function ($log) {
            return [
                'timestamp' => $log->created_at->format('Y-m-d H:i:s'),
                'actor' => $log->user ? $log->user->name : 'Unknown',
                'action' => $log->action_type,
                'description' => $log->action,
                'status' => $log->status,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'ip_address' => $log->ip_address,
            ];
        })->toArray();
    }

    /**
     * Check if a case has been accessed/audited.
     *
     * @param string $caseId
     * @return bool
     */
    public function hasCaseAuditLog(string $caseId): bool
    {
        return AuditLog::forCase($caseId)->exists();
    }

    /**
     * Get audit statistics for a case.
     *
     * @param string $caseId
     * @return array Statistics including action counts, actors, timeline
     */
    public function getCaseAuditStatistics(string $caseId): array
    {
        $logs = AuditLog::forCase($caseId)->get();

        return [
            'total_actions' => $logs->count(),
            'unique_actors' => $logs->pluck('user_id')->unique()->count(),
            'action_types' => $logs->groupBy('action_type')->map(fn($group) => [
                'count' => $group->count(),
                'percentage' => round(($group->count() / $logs->count()) * 100, 2),
            ])->toArray(),
            'first_action_at' => $logs->first()?->created_at,
            'last_action_at' => $logs->last()?->created_at,
            'most_active_actor' => $logs->groupBy('user_id')
                ->mapWithKeys(fn($group, $userId) => [$userId => $group->count()])
                ->sortDesc()
                ->keys()
                ->first(),
        ];
    }
}
