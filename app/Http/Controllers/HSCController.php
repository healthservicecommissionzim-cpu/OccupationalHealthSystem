<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\AuditLog;
use App\Models\IncidentNotification;
use App\Models\Facility;
use App\Models\User;
use App\Models\RiskAssessment;
use App\Models\RiskCorrectiveAction;
use App\Models\RiskAssessmentAuditLog;
use App\Models\Department;
use App\Services\RiskPredictiveAnalytics;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HSCController extends Controller
{
    // ============================================================================
    // CASE MANAGEMENT SECTION
    // ============================================================================

    public function allCases(Request $request)
    {
        $query = Incident::with([
            'facility.district.province', 
            'department', 
            'incidentType', 
            'assignedSupervisor'
        ]);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'ilike', "%{$search}%")
                  ->orWhere('first_name', 'ilike', "%{$search}%")
                  ->orWhere('surname', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $incidents = $query->orderByDesc('created_at')->paginate(50);
        $statusCounts = Incident::groupBy('status')->selectRaw('status, count(*) as count')->pluck('count', 'status');

        return view('hsc.cases.all-standalone', compact('incidents', 'statusCounts'));
    }

    public function casesByFacility()
    {
        // Get aggregated facility summary data
        $facilitySummary = Incident::selectRaw('
                EXTRACT(YEAR FROM created_at) as year,
                facility_id,
                COUNT(*) as total_cases
            ')
            ->with(['facility.district.province', 'facility.incidents.incidentType'])
            ->groupBy('year', 'facility_id')
            ->orderByDesc('year')
            ->orderBy('facility_id')
            ->get()
            ->map(function ($item) {
                $facility = $item->facility;
                
                // Get all incidents for this facility in this year
                $incidents = Incident::where('facility_id', $item->facility_id)
                    ->whereYear('created_at', $item->year)
                    ->with('incidentType')
                    ->get();
                
                // Group by incident type and count
                $caseTypeBreakdown = $incidents->groupBy('incidentType.name')
                    ->map(function ($group) {
                        return $group->count();
                    })
                    ->sortDesc();
                
                // Build description string (e.g., "Back Injury (5), Falls (3), Burns (2)")
                $description = $caseTypeBreakdown->map(function ($count, $type) {
                    return ($type ?? 'Unknown') . " ({$count})";
                })->join(', ');
                
                return [
                    'year' => $item->year,
                    'facility_name' => $facility->name,
                    'district' => $facility->district?->name ?? 'N/A',
                    'province' => $facility->district?->province?->name ?? 'N/A',
                    'total_cases' => $item->total_cases,
                    'description' => $description ?: 'No case types recorded',
                    'case_types_breakdown' => $caseTypeBreakdown,
                ];
            })
            ->filter(fn($item) => $item['total_cases'] > 0); // Only facilities with cases

        return view('hsc.cases.by-facility-standalone', compact('facilitySummary'));
    }

    // ============================================================================
    // MONITORING & ANALYTICS SECTION
    // ============================================================================

    public function complianceDashboard()
    {
        $totalCases = Incident::count();
        $compliantCases = Incident::where('compliance_status', 'COMPLIANT')->count();
        
        // Non-compliant includes all cases that are NOT marked as compliant
        // (NULL, NON_COMPLIANT, or any other status)
        $nonCompliantCases = $totalCases - $compliantCases;
        
        $complianceRate = $totalCases > 0 ? round(($compliantCases / $totalCases) * 100, 1) : 0;

        $facilityCompliance = Facility::withCount([
            'incidents',
            'incidents as compliant_incidents' => function ($q) {
                $q->where('compliance_status', 'COMPLIANT');
            }
        ])->orderByDesc('incidents_count')->get();

        $complianceTrend = Incident::selectRaw('DATE(created_at) as date, SUM(CASE WHEN compliance_status = \'COMPLIANT\' THEN 1 ELSE 0 END) as compliant, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('hsc.monitoring.compliance-dashboard-standalone', compact(
            'totalCases', 'compliantCases', 'nonCompliantCases', 'complianceRate',
            'facilityCompliance', 'complianceTrend'
        ));
    }

    public function incidentTrends()
    {
        // Get incidents by type for the chart (last 90 days)
        $trendsByType = Incident::selectRaw('incident_type_id, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->groupBy('incident_type_id')
            ->with('incidentType')
            ->orderByDesc('count')
            ->get();

        // Get detailed incidents grouped by type for the modal
        $incidentsByType = [];
        foreach ($trendsByType as $trend) {
            if ($trend->incident_type_id) {
                $incidentsByType[$trend->incident_type_id] = Incident::where('incident_type_id', $trend->incident_type_id)
                    ->where('created_at', '>=', Carbon::now()->subDays(90))
                    ->with(['facility', 'incidentType'])
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        return view('hsc.monitoring.incident-trends-standalone', compact('trendsByType', 'incidentsByType'));
    }

    public function riskAssessment(Request $request)
    {
        // Filters
        $facilityId = $request->get('facility_id');
        $hazardCategory = $request->get('hazard_category');
        $status = $request->get('status', 'ACTIVE');

        // Build risk assessments query
        $riskQuery = RiskAssessment::with([
            'facility.district.province',
            'department',
            'correctiveActions',
            'auditLogs'
        ]);

        if ($facilityId) {
            $riskQuery->where('facility_id', $facilityId);
        }

        if ($hazardCategory) {
            $riskQuery->where('hazard_category', $hazardCategory);
        }

        if ($status) {
            $riskQuery->where('status', $status);
        }

        $riskAssessments = $riskQuery->orderByDesc('residual_risk_score')
            ->orderByDesc('predicted_risk_score')
            ->get();

        // Calculate KPIs
        $totalAssessments = $riskAssessments->count();
        $highRiskCount = $riskAssessments->filter(fn($r) => $r->residual_risk_score >= 15)->count();
        $needsReviewCount = $riskAssessments->filter(fn($r) => $r->status === 'NEEDS_REVIEW' || $r->escalation_warning)->count();
        $escalationWarnings = $riskAssessments->filter(fn($r) => $r->escalation_warning)->count();

        // Aggregate predictive insights
        $totalLinkedIncidents30d = $riskAssessments->sum('linked_incidents_30d');
        $totalLinkedIncidents90d = $riskAssessments->sum('linked_incidents_90d');
        $totalLinkedDODs = $riskAssessments->sum('linked_dod_total');

        // Get AI-predicted high risks (where AI score > manual score)
        $aiPredictedHighRisks = $riskAssessments->filter(function($r) {
            return $r->predicted_risk_score && $r->predicted_risk_score > $r->residual_risk_score;
        });

        // Build risk matrix data (5x5 grid)
        $riskMatrix = $this->buildRiskMatrix($riskAssessments);

        // Get pending corrective actions
        $pendingActions = RiskCorrectiveAction::with(['riskAssessment.facility', 'assignedTo'])
            ->whereIn('status', ['PENDING', 'IN_PROGRESS'])
            ->orderBy('due_date')
            ->take(10)
            ->get();

        $overdueActions = RiskCorrectiveAction::with(['riskAssessment.facility', 'assignedTo'])
            ->overdue()
            ->count();

        // Facilities and departments for filters
        $facilities = Facility::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        // Hazard categories
        $hazardCategories = ['Physical', 'Chemical', 'Biological', 'Ergonomic', 'Psychosocial', 'Environmental'];

        return view('hsc.monitoring.risk-assessment-comprehensive', [
            'riskAssessments' => $riskAssessments,
            'totalAssessments' => $totalAssessments,
            'highRiskCount' => $highRiskCount,
            'needsReviewCount' => $needsReviewCount,
            'escalationWarnings' => $escalationWarnings,
            'totalLinkedIncidents30d' => $totalLinkedIncidents30d,
            'totalLinkedIncidents90d' => $totalLinkedIncidents90d,
            'totalLinkedDODs' => $totalLinkedDODs,
            'aiPredictedHighRisks' => $aiPredictedHighRisks,
            'riskMatrix' => $riskMatrix,
            'pendingActions' => $pendingActions,
            'overdueActions' => $overdueActions,
            'facilities' => $facilities,
            'departments' => $departments,
            'hazardCategories' => $hazardCategories,
            'selectedFacility' => $facilityId,
            'selectedCategory' => $hazardCategory,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Build 5x5 risk matrix with current hazards and predictive hotspots
     */
    private function buildRiskMatrix($riskAssessments)
    {
        $matrix = [];
        
        for ($severity = 5; $severity >= 1; $severity--) {
            for ($likelihood = 1; $likelihood <= 5; $likelihood++) {
                $score = $likelihood * $severity;
                
                // Get assessments in this cell (residual)
                $residualHazards = $riskAssessments->filter(function($r) use ($likelihood, $severity) {
                    return $r->residual_likelihood == $likelihood && $r->residual_severity == $severity;
                })->count();

                // Get predictive hotspots in this cell
                $predictedHazards = $riskAssessments->filter(function($r) use ($likelihood, $severity) {
                    return $r->predicted_likelihood == $likelihood && $r->predicted_severity == $severity;
                })->count();

                $matrix[$severity][$likelihood] = [
                    'score' => $score,
                    'residual_count' => $residualHazards,
                    'predicted_count' => $predictedHazards,
                    'level' => $this->getRiskLevel($score),
                ];
            }
        }

        return $matrix;
    }

    private function getRiskLevel($score)
    {
        if ($score >= 20) return 'CRITICAL';
        if ($score >= 15) return 'HIGH';
        if ($score >= 10) return 'MEDIUM';
        if ($score >= 5) return 'LOW';
        return 'VERY_LOW';
    }

    /**
     * AJAX: Get predictive forecast for selected location
     */
    public function getPredictiveForecast(Request $request)
    {
        $analytics = new RiskPredictiveAnalytics();
        
        $prediction = $analytics->calculatePredictiveRisk(
            $request->facility_id,
            $request->department_id,
            $request->specific_zone,
            $request->task_type
        );

        return response()->json($prediction);
    }

    /**
     * Store new risk assessment
     */
    public function storeRiskAssessment(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'department_id' => 'nullable|exists:departments,id',
            'specific_zone' => 'nullable|string',
            'task_type' => 'nullable|string',
            'equipment_involved' => 'nullable|string',
            'hazard_description' => 'required|string',
            'hazard_category' => 'required|string',
            'inherent_likelihood' => 'required|integer|min:1|max:5',
            'inherent_severity' => 'required|integer|min:1|max:5',
            'residual_likelihood' => 'required|integer|min:1|max:5',
            'residual_severity' => 'required|integer|min:1|max:5',
            'elimination_controls' => 'nullable|string',
            'substitution_controls' => 'nullable|string',
            'engineering_controls' => 'nullable|string',
            'administrative_controls' => 'nullable|string',
            'ppe_controls' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['last_reviewed_date'] = now();
        $validated['next_review_date'] = now()->addMonths(6); // Default 6-month review cycle

        $riskAssessment = RiskAssessment::create($validated);

        // Run predictive analytics
        $analytics = new RiskPredictiveAnalytics();
        $analytics->updateRiskAssessmentPredictions($riskAssessment);

        // Log creation
        RiskAssessmentAuditLog::create([
            'risk_assessment_id' => $riskAssessment->id,
            'event_type' => 'CREATED',
            'event_description' => 'Risk assessment created',
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_role' => Auth::user()->role,
        ]);

        return redirect()->route('hsc.risk.assessment')
            ->with('success', 'Risk assessment created successfully. AI predictions generated.');
    }

    /**
     * Update corrective action status
     */
    public function updateCorrectiveAction(Request $request, $id)
    {
        $action = RiskCorrectiveAction::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'completion_percentage' => 'nullable|integer|min:0|max:100',
            'progress_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'COMPLETED') {
            $validated['completed_date'] = now();
        }

        $action->update($validated);

        return response()->json(['success' => true, 'action' => $action]);
    }

    public function workflowHealth()
    {
        $workflowStages = [
            'PENDING_SUPERVISOR_VERIFICATION',
            'VERIFIED_SUPERVISOR',
            'ESCALATED_TO_HR',
            'PENDING_HR_REVIEW',
            'ESCALATED_TO_DMO',
            'ESCALATED_TO_MEDICAL_SUPERINTENDENT',
            'ESCALATED_TO_PMD',
            'PENDING_PMD_REVIEW',
            'RESOLVED',
            'CLOSED',
        ];

        $stageCounts = [];
        $averageTimeInStage = [];

        foreach ($workflowStages as $stage) {
            $stageCounts[$stage] = Incident::where('status', $stage)->count();
        }

        return view('hsc.monitoring.workflow-health', compact('stageCounts', 'workflowStages'));
    }

    // ============================================================================
    // QUALITY ASSURANCE SECTION
    // ============================================================================

    public function auditCases(Request $request)
    {
        $sampleSize = $request->input('sample_size', 20);
        $auditCases = Incident::inRandomOrder()->limit($sampleSize)
            ->with(['facility', 'incidentType', 'attachments'])
            ->get();

        $completenessChecklist = [];
        foreach ($auditCases as $incident) {
            $completenessChecklist[$incident->id] = [
                'supervisor_verified' => $incident->supervisor_verified_at !== null,
                'hod_report' => $incident->hod_report !== null,
                'first_aid_record' => $incident->firstAidRecords->isNotEmpty(),
                'documents_attached' => $incident->attachments->isNotEmpty(),
                'witness_statements' => $incident->witnessTokens->isNotEmpty(),
            ];
        }

        return view('hsc.quality.audit-cases', compact('auditCases', 'completenessChecklist', 'sampleSize'));
    }

    public function auditDocuments()
    {
        $incidentsWithAttachments = Incident::has('attachments')
            ->with(['attachments', 'facility'])
            ->paginate(50);

        $documentStats = [
            'total_documents' => \App\Models\IncidentAttachment::count(),
            'verified_documents' => \App\Models\IncidentAttachment::where('upload_status', 'VERIFIED')->count(),
            'pending_documents' => \App\Models\IncidentAttachment::where('upload_status', 'PENDING')->count(),
        ];

        return view('hsc.quality.audit-documents', compact('incidentsWithAttachments', 'documentStats'));
    }

    public function auditTimeline()
    {
        $incidents = Incident::with(['facility'])
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->get()
            ->map(function ($incident) {
                $createdAt = $incident->created_at;
                $supervisorVerifiedAt = $incident->supervisor_verified_at;
                $resolvedAt = $incident->resolved_at;

                return [
                    'incident' => $incident,
                    'supervisor_verification_time' => $supervisorVerifiedAt ? $supervisorVerifiedAt->diffInDays($createdAt) : null,
                    'total_time_to_resolution' => $resolvedAt ? $resolvedAt->diffInDays($createdAt) : null,
                    'within_sla' => $resolvedAt && $resolvedAt->diffInDays($createdAt) <= 30,
                ];
            });

        return view('hsc.quality.audit-timeline', compact('incidents'));
    }

    public function auditProcedures()
    {
        $allIncidents = Incident::with(['facility', 'incidentType'])->get();

        $procedureAdherence = [];
        foreach ($allIncidents as $incident) {
            $procedureAdherence[$incident->id] = [
                'incident' => $incident,
                'has_supervisor_verification' => $incident->supervisor_verified_at !== null,
                'has_hod_report' => $incident->hod_report !== null,
                'has_first_aid_record' => $incident->firstAidRecords->isNotEmpty(),
                'has_required_documents' => $incident->attachments->count() >= 1,
                'has_witness_statements' => $incident->witnessTokens->isNotEmpty(),
                'compliance_status' => $incident->compliance_status,
            ];
        }

        return view('hsc.quality.audit-procedures', compact('procedureAdherence'));
    }

    // ============================================================================
    // REPORTS & EVIDENCE SECTION
    // ============================================================================

    public function generateReports(Request $request)
    {
        return view('hsc.reports.generate');
    }

    public function exportData(Request $request)
    {
        $incidents = Incident::with(['facility', 'incidentType', 'department'])->get();
        return view('hsc.reports.export', compact('incidents'));
    }

    public function statisticalSummary()
    {
        $stats = [
            'total_cases' => Incident::count(),
            'active_cases' => Incident::where('status', '!=', 'RESOLVED')->where('status', '!=', 'CLOSED')->count(),
            'resolved_cases' => Incident::where('status', 'RESOLVED')->orWhere('status', 'CLOSED')->count(),
            'rejected_cases' => Incident::where('status', 'like', '%REJECTED%')->count(),
            'total_by_type' => Incident::selectRaw('incident_type_id, COUNT(*) as count')
                ->groupBy('incident_type_id')
                ->with('incidentType')
                ->get(),
            'total_by_facility' => Facility::withCount('incidents')->orderByDesc('incidents_count')->take(10)->get(),
        ];

        return view('hsc.reports.statistics', compact('stats'));
    }

    public function comparativeAnalysis()
    {
        $comparison = [
            'this_month' => Incident::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'last_month' => Incident::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count(),
            'this_quarter' => Incident::whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()])->count(),
            'last_quarter' => Incident::whereBetween('created_at', [Carbon::now()->subQuarter()->startOfQuarter(), Carbon::now()->subQuarter()->endOfQuarter()])->count(),
        ];

        return view('hsc.reports.comparative', compact('comparison'));
    }

    // ============================================================================
    // SYSTEM OVERSIGHT SECTION
    // ============================================================================

    public function userActivityLog(Request $request)
    {
        $logs = AuditLog::with(['user'])->orderByDesc('created_at')->paginate(100);
        return view('hsc.system.user-activity', compact('logs'));
    }

    public function escalationTracking()
    {
        $escalatedCases = Incident::whereIn('status', [
            'ESCALATED_TO_DMO',
            'ESCALATED_TO_MEDICAL_SUPERINTENDENT',
            'ESCALATED_TO_PMD',
            'PENDING_PMD_REVIEW'
        ])->with(['facility', 'incidentType', 'assignedHRUser'])
            ->orderByDesc('escalated_to_hr_at')
            ->paginate(50);

        return view('hsc.system.escalation-tracking', compact('escalatedCases'));
    }

    public function notificationHistory(Request $request)
    {
        $notifications = IncidentNotification::with(['incident', 'recipientUser'])
            ->orderByDesc('created_at')
            ->paginate(100);

        return view('hsc.system.notification-history', compact('notifications'));
    }

    public function performanceMetrics()
    {
        $metrics = [
            'avg_supervisor_verification_time' => $this->getAverageTimeToVerification(),
            'avg_time_to_resolution' => $this->getAverageTimeToResolution(),
            'total_escalations_this_month' => Incident::whereMonth('escalated_to_hr_at', Carbon::now()->month)->count(),
            'compliance_percentage' => $this->getCompliancePercentage(),
        ];

        return view('hsc.system.performance-metrics', compact('metrics'));
    }

    // ============================================================================
    // HELPER METHODS
    // ============================================================================

    private function getAverageTimeToVerification()
    {
        return Incident::whereNotNull('supervisor_verified_at')
            ->selectRaw('AVG(EXTRACT(DAY FROM (supervisor_verified_at - created_at))) as avg_days')
            ->first()
            ->avg_days ?? 0;
    }

    private function getAverageTimeToResolution()
    {
        return Incident::whereNotNull('resolved_at')
            ->selectRaw('AVG(EXTRACT(DAY FROM (resolved_at - created_at))) as avg_days')
            ->first()
            ->avg_days ?? 0;
    }

    private function getCompliancePercentage()
    {
        $total = Incident::count();
        if ($total === 0) return 0;

        $compliant = Incident::where('compliance_status', 'COMPLIANT')->count();
        return round(($compliant / $total) * 100, 1);
    }
}
