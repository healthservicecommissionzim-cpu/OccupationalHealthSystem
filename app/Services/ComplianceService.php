<?php

namespace App\Services;

use App\Models\ComplianceCheck;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplianceService
{
    /**
     * Get the standard compliance checklist definition.
     *
     * @return array Checklist items with descriptions
     */
    public function getComplianceChecklist(): array
    {
        return [
            [
                'item' => 'IOD_FORM_COMPLETED',
                'label' => 'IOD Form Completed',
                'description' => 'IOD/NSSA form has been completed and submitted',
            ],
            [
                'item' => 'REPORTING_WINDOW_MET',
                'label' => '48-Hour Reporting Window Met',
                'description' => 'Incident was reported within 48 hours of occurrence',
            ],
            [
                'item' => 'INCIDENT_DATE_DOCUMENTED',
                'label' => 'Incident Date Documented',
                'description' => 'Incident date and time are properly recorded',
            ],
            [
                'item' => 'SUPERVISOR_NOTIFIED',
                'label' => 'Supervisor Notified',
                'description' => 'Supervisor has been notified and has acknowledged',
            ],
            [
                'item' => 'MEDICAL_PROVIDER_ASSIGNED',
                'label' => 'Medical Provider Assigned',
                'description' => 'Medical professional has been assigned for care',
            ],
        ];
    }

    /**
     * Evaluate a case against the compliance checklist.
     * Auto-populates checklist items by querying case data.
     *
     * @param string $caseId
     * @return array Checklist items with auto-populated status and evidence
     */
    public function evaluateCase(string $caseId): array
    {
        $case = Incident::findOrFail($caseId);
        $checklist = $this->getComplianceChecklist();

        $evaluatedItems = [];

        foreach ($checklist as $item) {
            $status = $this->evaluateChecklistItem($case, $item['item']);
            $evidence = $this->getEvidenceForItem($case, $item['item']);

            $evaluatedItems[] = [
                'item' => $item['item'],
                'label' => $item['label'],
                'status' => $status,
                'evidence' => $evidence,
                'compliant' => $status === 'COMPLIANT',
            ];
        }

        return $evaluatedItems;
    }

    /**
     * Evaluate a single checklist item against case data.
     *
     * @param Incident $case
     * @param string $itemKey
     * @return string 'COMPLIANT', 'NON_COMPLIANT', or 'UNKNOWN'
     */
    private function evaluateChecklistItem(Incident $case, string $itemKey): string
    {
        return match($itemKey) {
            'IOD_FORM_COMPLETED' => $this->checkIODFormCompleted($case),
            'REPORTING_WINDOW_MET' => $this->checkReportingWindowMet($case),
            'INCIDENT_DATE_DOCUMENTED' => $this->checkIncidentDateDocumented($case),
            'SUPERVISOR_NOTIFIED' => $this->checkSupervisorNotified($case),
            'MEDICAL_PROVIDER_ASSIGNED' => $this->checkMedicalProviderAssigned($case),
            default => 'UNKNOWN',
        };
    }

    /**
     * Get evidence for a specific checklist item.
     *
     * @param Incident $case
     * @param string $itemKey
     * @return string Evidence description
     */
    private function getEvidenceForItem(Incident $case, string $itemKey): string
    {
        return match($itemKey) {
            'IOD_FORM_COMPLETED' => $case->hod_report ? 'HOD Report submitted on ' . $case->hod_report_submitted_at?->format('Y-m-d H:i') : 'No HOD report found',
            'REPORTING_WINDOW_MET' => $this->getReportingWindowEvidence($case),
            'INCIDENT_DATE_DOCUMENTED' => $case->incident_date ? 'Incident date: ' . $case->incident_date->format('Y-m-d') . ' at ' . ($case->incident_time ?? 'unknown time') : 'No incident date',
            'SUPERVISOR_NOTIFIED' => $case->supervisor_verified_at ? 'Supervisor verified on ' . $case->supervisor_verified_at->format('Y-m-d H:i') : 'Supervisor not yet verified',
            'MEDICAL_PROVIDER_ASSIGNED' => $case->routed_to_hr_user_id ? 'HR user assigned on escalation' : 'No medical provider assigned',
            default => 'No evidence available',
        };
    }

    /**
     * Check if IOD form is completed.
     */
    private function checkIODFormCompleted(Incident $case): string
    {
        return !empty($case->hod_report) && !empty($case->hod_report_submitted_at)
            ? 'COMPLIANT'
            : 'NON_COMPLIANT';
    }

    /**
     * Check if incident was reported within 48 hours.
     */
    private function checkReportingWindowMet(Incident $case): string
    {
        if (!$case->incident_date || !$case->created_at) {
            return 'UNKNOWN';
        }

        $hoursElapsed = $case->incident_date->diffInHours($case->created_at);
        return $hoursElapsed <= 48 ? 'COMPLIANT' : 'NON_COMPLIANT';
    }

    /**
     * Check if incident date is documented.
     */
    private function checkIncidentDateDocumented(Incident $case): string
    {
        return !empty($case->incident_date) ? 'COMPLIANT' : 'NON_COMPLIANT';
    }

    /**
     * Check if supervisor has been notified.
     */
    private function checkSupervisorNotified(Incident $case): string
    {
        return !empty($case->supervisor_verified_at) || !empty($case->assigned_supervisor_id)
            ? 'COMPLIANT'
            : 'NON_COMPLIANT';
    }

    /**
     * Check if medical provider has been assigned.
     */
    private function checkMedicalProviderAssigned(Incident $case): string
    {
        return !empty($case->routed_to_hr_user_id) || !empty($case->routed_to_medical_superintendent_user_id)
            ? 'COMPLIANT'
            : 'NON_COMPLIANT';
    }

    /**
     * Get reporting window evidence.
     */
    private function getReportingWindowEvidence(Incident $case): string
    {
        if (!$case->incident_date || !$case->created_at) {
            return 'Unable to determine - missing dates';
        }

        $hoursElapsed = $case->incident_date->diffInHours($case->created_at);
        $status = $hoursElapsed <= 48 ? 'within' : 'outside';

        return "Reported {$hoursElapsed} hours {$status} 48-hour window";
    }

    /**
     * Save compliance checklist result.
     *
     * @param string $caseId
     * @param array $checklistItems Checklist items with status
     * @param string $overallStatus 'COMPLIANT' or 'NON_COMPLIANT'
     * @return ComplianceCheck
     */
    public function saveChecklistResult(string $caseId, array $checklistItems, string $overallStatus): ComplianceCheck
    {
        try {
            $compliance = ComplianceCheck::create([
                'case_id' => $caseId,
                'evaluated_by_id' => Auth::id(),
                'checklist_items' => $checklistItems,
                'overall_status' => $overallStatus,
            ]);

            Log::info('Compliance checklist saved', [
                'case_id' => $caseId,
                'overall_status' => $overallStatus,
                'compliance_id' => $compliance->id,
            ]);

            return $compliance;
        } catch (\Exception $e) {
            Log::error('Failed to save compliance checklist', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get compliance report with flagged non-compliant cases.
     *
     * @param array $filters Optional filters (date range, evaluator, etc.)
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getComplianceReport(array $filters = [], int $perPage = 50)
    {
        $query = ComplianceCheck::nonCompliant();

        // Filter by date range
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = \Carbon\Carbon::parse($filters['start_date'])->startOfDay();
            $endDate = \Carbon\Carbon::parse($filters['end_date'])->endOfDay();
            $query->inDateRange($startDate, $endDate);
        }

        // Filter by evaluator
        if (!empty($filters['evaluated_by_id'])) {
            $query->byEvaluator($filters['evaluated_by_id']);
        }

        return $query->with(['case', 'evaluatedBy'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get latest compliance check for a case.
     *
     * @param string $caseId
     * @return ComplianceCheck|null
     */
    public function getLatestCheckForCase(string $caseId): ?ComplianceCheck
    {
        return ComplianceCheck::latestForCase($caseId);
    }

    /**
     * Get compliance statistics.
     *
     * @param array $filters Optional date range filters
     * @return array Statistics including total checks, compliance rate, etc.
     */
    public function getComplianceStatistics(array $filters = []): array
    {
        $query = ComplianceCheck::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = \Carbon\Carbon::parse($filters['start_date'])->startOfDay();
            $endDate = \Carbon\Carbon::parse($filters['end_date'])->endOfDay();
            $query->inDateRange($startDate, $endDate);
        }

        $checks = $query->get();
        $totalChecks = $checks->count();
        $compliantChecks = $checks->where('overall_status', 'COMPLIANT')->count();
        $nonCompliantChecks = $checks->where('overall_status', 'NON_COMPLIANT')->count();

        return [
            'total_checks' => $totalChecks,
            'compliant' => $compliantChecks,
            'non_compliant' => $nonCompliantChecks,
            'compliance_rate' => $totalChecks > 0 ? round(($compliantChecks / $totalChecks) * 100, 2) : 0,
            'non_compliance_rate' => $totalChecks > 0 ? round(($nonCompliantChecks / $totalChecks) * 100, 2) : 0,
        ];
    }
}
