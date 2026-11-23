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

    public function deathOnDutyCases()
    {
        return view('hsc.cases.death-on-duty');
    }

    public function incidentMapping()
    {
        // Fetch province data with incident and facility counts
        $provinceData = \App\Models\Province::withCount('incidents')
            ->withCount('facilities')
            ->get()
            ->map(function($province) {
                return [
                    'id' => $province->id,
                    'name' => $province->name,
                    'lat' => $province->latitude ?? -19.0154, // Default to Zimbabwe center
                    'lng' => $province->longitude ?? 29.1549,
                    'incidents' => $province->incidents_count,
                    'facilities' => $province->facilities_count,
                ];
            });

        // Fetch individual incidents with details for mapping
        $incidents = Incident::with(['facility.province', 'facility.district', 'incidentType'])
            ->where('status', '!=', 'CLOSED')
            ->select('id', 'reference_number', 'incident_type_id', 'incident_date', 'facility_id', 'status', 'is_workplace_fatality', 'is_road_traffic_accident')
            ->orderBy('incident_date', 'desc')
            ->limit(500) // Limit to recent 500 incidents for performance
            ->get()
            ->map(function($incident) {
                // Use facility coordinates if available, otherwise fall back to province coordinates
                $lat = $incident->facility->latitude ?? $incident->facility->province->latitude ?? -19.0154;
                $lng = $incident->facility->longitude ?? $incident->facility->province->longitude ?? 29.1549;
                
                // Determine severity based on available flags
                $severity = 'MINOR'; // Default
                if ($incident->is_workplace_fatality) {
                    $severity = 'FATAL';
                } elseif ($incident->is_road_traffic_accident) {
                    $severity = 'SERIOUS';
                } elseif ($incident->status === 'ESCALATED_TO_HR' || $incident->status === 'UNDER_MEDICAL_REVIEW') {
                    $severity = 'MODERATE';
                }
                
                return [
                    'id' => $incident->id,
                    'reference' => $incident->reference_number,
                    'type' => $incident->incidentType->name ?? 'Not specified',
                    'severity' => $severity,
                    'date' => $incident->incident_date->format('Y-m-d'),
                    'facility' => $incident->facility->name ?? 'Unknown',
                    'province' => $incident->facility->province->name ?? 'Unknown',
                    'status' => $incident->status,
                    'lat' => $lat,
                    'lng' => $lng,
                    'has_facility_coords' => !is_null($incident->facility->latitude), // For debugging
                ];
            });

        return view('hsc.cases.incident-mapping', compact('provinceData', 'incidents'));
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
        // Get incident types that have repeated (2+ occurrences) in last 180 days
        // Simplified: Just group by incident_type_id
        $repeatPatterns = Incident::selectRaw('incident_type_id, COUNT(*) as repeat_count')
            ->where('created_at', '>=', Carbon::now()->subDays(180))
            ->whereNotNull('incident_type_id')
            ->groupBy('incident_type_id')
            ->having('repeat_count', '>=', 2)
            ->with(['incidentType'])
            ->orderByDesc('repeat_count')
            ->get();

        // Get detailed incidents for each pattern (for modal)
        $patternDetails = [];
        foreach ($repeatPatterns as $pattern) {
            $key = $pattern->incident_type_id;
            
            $patternDetails[$key] = Incident::where('incident_type_id', $pattern->incident_type_id)
                ->where('created_at', '>=', Carbon::now()->subDays(180))
                ->with(['facility', 'incidentType'])
                ->orderByDesc('created_at')
                ->get()
                ->groupBy('facility_id')
                ->map(function($facilityIncidents) {
                    return [
                        'facility' => $facilityIncidents->first()->facility,
                        'count' => $facilityIncidents->count(),
                        'incidents' => $facilityIncidents
                    ];
                });
        }

        return view('hsc.monitoring.incident-trends-standalone', compact('repeatPatterns', 'patternDetails'));
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

    /**
     * View all supporting documents for a specific incident
     */
    public function viewDocuments($incidentId)
    {
        $incident = Incident::with([
            'attachments',
            'attachments.uploadedBy',
            'facility',
            'department'
        ])->findOrFail($incidentId);

        return view('incidents.hsc_documents', compact('incident'));
    }

    // ============================================================================
    // REPORTS & EVIDENCE SECTION
    // ============================================================================

}
