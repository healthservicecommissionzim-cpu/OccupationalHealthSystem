<?php

namespace App\Http\Controllers\CoS;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Services\ComplianceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceDashboardController extends Controller
{
    protected $complianceService;

    public function __construct(ComplianceService $complianceService)
    {
        $this->complianceService = $complianceService;
    }

    /**
     * Show compliance checklist form for a case.
     */
    public function show($caseId)
    {
        $this->authorize('run', 'compliance_checklist');

        $case = Incident::findOrFail($caseId);
        
        // Get checklist definition
        $checklistDefinition = $this->complianceService->getComplianceChecklist();
        
        // Evaluate case against checklist
        $evaluatedItems = $this->complianceService->evaluateCase($caseId);
        
        // Get latest compliance check if exists
        $latestCheck = $this->complianceService->getLatestCheckForCase($caseId);

        // Log this access
        app(\App\Services\AuditService::class)->logAction('checklist_viewed', $caseId, [
            'action' => 'Compliance checklist viewed',
            'affected_entity' => 'Compliance Checklist',
        ]);

        return view('dashboards.cos.compliance.show', compact(
            'case',
            'checklistDefinition',
            'evaluatedItems',
            'latestCheck'
        ));
    }

    /**
     * Save compliance checklist result.
     */
    public function save(Request $request, $caseId)
    {
        $this->authorize('run', 'compliance_checklist');

        $validated = $request->validate([
            'checklist_items' => 'required|array',
            'checklist_items.*.item' => 'required|string',
            'checklist_items.*.status' => 'required|in:COMPLIANT,NON_COMPLIANT',
        ]);

        try {
            $case = Incident::findOrFail($caseId);

            // Determine overall status
            $hasNonCompliant = collect($validated['checklist_items'])
                ->where('status', 'NON_COMPLIANT')
                ->isNotEmpty();
            
            $overallStatus = $hasNonCompliant ? 'NON_COMPLIANT' : 'COMPLIANT';

            // Save checklist result
            $compliance = $this->complianceService->saveChecklistResult(
                $caseId,
                $validated['checklist_items'],
                $overallStatus
            );

            // Log to audit trail
            app(\App\Services\AuditService::class)->logAction('checklist_run', $caseId, [
                'action' => 'Compliance checklist executed',
                'affected_entity' => 'Compliance Check',
                'new_values' => [
                    'compliance_id' => $compliance->id,
                    'overall_status' => $overallStatus,
                ],
            ]);

            $message = $overallStatus === 'COMPLIANT'
                ? 'Case marked as COMPLIANT'
                : 'Case flagged as NON_COMPLIANT for audit review';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->withError('Failed to save compliance result: ' . $e->getMessage());
        }
    }

    /**
     * View compliance report with all flagged cases.
     */
    public function report(Request $request)
    {
        $this->authorize('run', 'compliance_checklist');

        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'evaluated_by_id' => $request->input('evaluated_by_id'),
        ];

        // Get non-compliant cases
        $flaggedCases = $this->complianceService->getComplianceReport($filters, 25);
        
        // Get statistics
        $stats = $this->complianceService->getComplianceStatistics($filters);

        return view('dashboards.cos.compliance.report', compact(
            'flaggedCases',
            'stats',
            'filters'
        ));
    }

    /**
     * Export compliance report as CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('export', 'data');

        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $flaggedCases = $this->complianceService->getComplianceReport($filters, 9999);
        $filename = 'compliance-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($flaggedCases) {
            $file = fopen('php://output', 'w');

            // Write header
            fputcsv($file, [
                'Case ID',
                'Reference',
                'Worker Name',
                'Facility',
                'Overall Status',
                'Checked By',
                'Checked Date',
            ]);

            // Write data rows
            foreach ($flaggedCases as $check) {
                fputcsv($file, [
                    $check->case_id,
                    $check->case->reference_number ?? 'N/A',
                    ($check->case->first_name ?? '') . ' ' . ($check->case->surname ?? ''),
                    $check->case->facility?->name ?? 'N/A',
                    $check->overall_status,
                    $check->evaluatedBy?->name ?? 'N/A',
                    $check->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
