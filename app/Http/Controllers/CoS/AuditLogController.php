<?php

namespace App\Http\Controllers\CoS;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Display audit log entries for a specific case.
     */
    public function show($caseId, Request $request)
    {
        $this->authorize('view', 'audit_log');

        $filters = [
            'action_type' => $request->input('action_type'),
            'actor_id' => $request->input('actor_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        // Get paginated audit logs
        $auditLogs = $this->auditService->getAuditLog($caseId, $filters, 50);
        
        // Get statistics
        $stats = $this->auditService->getCaseAuditStatistics($caseId);

        // Get available action types for filtering
        $actionTypes = [
            'created' => 'Created',
            'escalated' => 'Escalated',
            'nudge_sent' => 'Nudge Sent',
            'monitoring_note_added' => 'Note Added',
            'checklist_run' => 'Checklist Run',
            'compliance_flagged' => 'Flagged',
            'report_generated' => 'Report Generated',
            'announcement_sent' => 'Announcement',
            'access_granted' => 'Access Granted',
            'access_denied' => 'Access Denied',
        ];

        return view('dashboards.cos.audit.show', compact(
            'caseId',
            'auditLogs',
            'stats',
            'filters',
            'actionTypes'
        ));
    }

    /**
     * Export audit log as CSV.
     */
    public function export($caseId, Request $request)
    {
        $this->authorize('view', 'audit_log');
        $this->authorize('export', 'data');

        $filters = [
            'action_type' => $request->input('action_type'),
            'actor_id' => $request->input('actor_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        // Get audit log data for export
        $auditData = $this->auditService->getAuditLogForExport($caseId, $filters);

        $filename = 'audit-log-' . $caseId . '-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($auditData) {
            $file = fopen('php://output', 'w');

            // Write header
            fputcsv($file, [
                'Timestamp',
                'Actor',
                'Action',
                'Description',
                'Status',
                'IP Address',
            ]);

            // Write data rows
            foreach ($auditData as $log) {
                fputcsv($file, [
                    $log['timestamp'],
                    $log['actor'],
                    $log['action'],
                    $log['description'],
                    $log['status'],
                    $log['ip_address'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate comprehensive audit report.
     */
    public function report(Request $request)
    {
        $this->authorize('view', 'audit_log');

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $startDate = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = \Carbon\Carbon::parse($validated['end_date'])->endOfDay();

            $filters = [
                'action_type' => $request->input('action_type'),
                'user_id' => $request->input('user_id'),
            ];

            $report = $this->auditService->generateAuditReport($startDate, $endDate, $filters);

            return view('dashboards.cos.audit.report', compact('report', 'startDate', 'endDate'));
        } catch (\Exception $e) {
            return back()->withError('Failed to generate audit report: ' . $e->getMessage());
        }
    }

    /**
     * Export audit report as PDF or CSV.
     */
    public function exportReport(Request $request)
    {
        $this->authorize('view', 'audit_log');
        $this->authorize('export', 'data');

        $format = $request->input('format', 'csv');

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = \Carbon\Carbon::parse($validated['end_date'])->endOfDay();

        $report = $this->auditService->generateAuditReport($startDate, $endDate);

        if ($format === 'csv') {
            return $this->exportReportCsv($report, $startDate, $endDate);
        } elseif ($format === 'pdf') {
            return $this->exportReportPdf($report, $startDate, $endDate);
        }

        return back()->withError('Invalid export format');
    }

    /**
     * Export report as CSV.
     */
    private function exportReportCsv($report, $startDate, $endDate)
    {
        $filename = 'audit-report-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($report) {
            $file = fopen('php://output', 'w');

            // Write summary
            fputcsv($file, ['Audit Report Summary']);
            fputcsv($file, ['Total Actions', $report['summary']['total_actions']]);
            fputcsv($file, ['Period Days', $report['summary']['period_days']]);
            fputcsv($file, ['Unique Actors', $report['summary']['unique_actors']]);
            fputcsv($file, []);

            // Write action breakdown
            fputcsv($file, ['Action Type Breakdown']);
            fputcsv($file, ['Action Type', 'Count']);
            foreach ($report['summary']['action_types'] as $action => $count) {
                fputcsv($file, [$action, $count]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export report as PDF (placeholder for PDF implementation).
     */
    private function exportReportPdf($report, $startDate, $endDate)
    {
        return response()->json([
            'message' => 'PDF export functionality to be implemented',
            'report_summary' => $report['summary'],
        ]);
    }
}
