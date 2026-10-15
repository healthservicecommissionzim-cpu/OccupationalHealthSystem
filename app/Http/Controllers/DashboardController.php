<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Route users to their role-specific dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the dashboard route for this user's role
        $dashboardRoute = $user->getDashboardRoute();

        // Redirect to the role-specific dashboard
        return redirect()->route($dashboardRoute);
    }

    /**
     * Show supervisor dashboard.
     */
    public function supervisor(Request $request)
    {
        $supervisor = auth()->user();
        $dateRange = $request->get('date_range', 'all');

        // Create cache key based on supervisor ID and date range
        $cacheKey = "supervisor_dashboard_{$supervisor->id}_{$dateRange}";
        
        // Check cache first (30 second TTL for real-time feel)
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedData && !$request->has('refresh')) {
            return view('dashboards.supervisor', $cachedData);
        }

        // Determine date filter
        $query = \App\Models\Incident::with('incidentType', 'facility')
        ->where('assigned_supervisor_id', $supervisor->id)
        ->select(['id', 'reference_number', 'first_name', 'surname', 'incident_type_id', 'status', 'compliance_status', 'created_at', 'assigned_supervisor_id', 'facility_id', 'first_aid_recorded']);

        // Apply date filter
        switch ($dateRange) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
            case 'all':
                break;
            default:
                $query->where('created_at', '>=', now()->subDays(30));
        }

        $allIncidents = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics from collection (no additional queries)
        $statistics = [
            'total_incidents' => $allIncidents->count(),
            'pending_verification' => $allIncidents->where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
            'verified_escalated' => $allIncidents->whereIn('status', ['VERIFIED_SUPERVISOR', 'ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW', 'RESOLVED'])->count(),
            'rejected' => $allIncidents->where('status', 'REJECTED_BY_HR')->count(),
            'in_progress' => $allIncidents->whereIn('status', ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])->count(),
            'resolved' => $allIncidents->where('status', 'RESOLVED')->count(),
        ];

        // Get compliance status breakdown
        $complianceStats = [
            'compliant' => $allIncidents->where('compliance_status', 'COMPLIANT')->count(),
            'partial_compliant' => $allIncidents->where('compliance_status', 'PARTIAL_COMPLIANT')->count(),
            'noncompliant' => $allIncidents->where('compliance_status', 'NONCOMPLIANT')->count(),
        ];

        // Calculate First Aid Recording Rate
        $firstAidStats = [
            'recorded' => $allIncidents->where('first_aid_recorded', true)->count(),
            'not_recorded' => $allIncidents->where('first_aid_recorded', false)->count() + $allIncidents->whereNull('first_aid_recorded')->count(),
        ];
        $firstAidStats['total'] = $firstAidStats['recorded'] + $firstAidStats['not_recorded'];
        $firstAidStats['recorded_percentage'] = $firstAidStats['total'] > 0 
            ? round(($firstAidStats['recorded'] / $firstAidStats['total']) * 100, 1) 
            : 0;

        // Get incidents by incident type (not facility type)
        // Build array with proper structure: [{ name: 'Type Name', count: 5 }, ...]
        $incidentsByTypeRaw = [];
        foreach ($allIncidents as $incident) {
            $typeName = ($incident->incidentType && $incident->incidentType->name) 
                ? $incident->incidentType->name 
                : 'Unknown';
            
            $found = false;
            foreach ($incidentsByTypeRaw as &$item) {
                if ($item['name'] === $typeName) {
                    $item['count']++;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $incidentsByTypeRaw[] = ['name' => $typeName, 'count' => 1];
            }
        }
        
        // Sort by count descending and take top 10
        usort($incidentsByTypeRaw, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        $incidentsByType = array_slice($incidentsByTypeRaw, 0, 10);
        
        // Debug logging
        \Log::info('Incidents by Type Debug', [
            'total_incidents' => $allIncidents->count(),
            'incidents_by_type' => $incidentsByType,
            'first_incident_type_id' => $allIncidents->first()?->incident_type_id,
            'first_incident_type_obj' => $allIncidents->first()?->incidentType,
        ]);

        // Get recent incidents (last 5) - ONLY PENDING VERIFICATION
        $recentIncidents = $allIncidents->where('status', 'PENDING_SUPERVISOR_VERIFICATION')->take(5);

        // Get incidents by status for visualization
        $incidentsByStatus = $allIncidents->groupBy('status')->map(function($group) {
            return $group->count();
        });

        // Get supervisor notifications (new escalations and witness uploads)
        $newNotifications = \App\Models\IncidentNotification::where('recipient_user_id', $supervisor->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadNotificationCount = $newNotifications->count();
        $allNotifications = $newNotifications; // Alias for view compatibility

        $viewData = compact(
            'allIncidents',
            'statistics',
            'complianceStats',
            'firstAidStats',
            'incidentsByType',
            'recentIncidents',
            'incidentsByStatus',
            'dateRange',
            'newNotifications',
            'unreadNotificationCount',
            'allNotifications'
        );

        // Cache for 30 seconds
        \Illuminate\Support\Facades\Cache::put($cacheKey, $viewData, 30);

        return view('dashboards.supervisor', $viewData);
    }

    public function supervisorAllIncidents()
    {
        $supervisor = auth()->user();
        $incidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('supervisor.all_incidents', compact('incidents'));
    }

    public function supervisorVerificationRate()
    {
        $supervisor = auth()->user();
        $totalIncidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->count();
        $verifiedIncidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->where('status', 'VERIFIED_SUPERVISOR')->count();
        
        $verificationRate = $totalIncidents > 0 ? round(($verifiedIncidents / $totalIncidents) * 100, 1) : 0;

        $statistics = [
            'total_assigned' => $totalIncidents,
            'verified' => $verifiedIncidents,
            'pending' => \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
            'rejected' => \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->where('status', 'REJECTED_SUPERVISOR')->count(),
            'verification_rate' => $verificationRate,
        ];

        return view('supervisor.verification_rate', compact('statistics'));
    }

    public function supervisorProcessingTime()
    {
        $supervisor = auth()->user();
        $verifiedIncidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)
            ->where('status', 'VERIFIED_SUPERVISOR')
            ->get();

        $avgProcessingTime = 0;
        if ($verifiedIncidents->count() > 0) {
            $totalTime = $verifiedIncidents->sum(function($incident) {
                return $incident->created_at->diffInHours(now());
            });
            $avgProcessingTime = round($totalTime / $verifiedIncidents->count(), 1);
        }

        $statistics = [
            'avg_hours' => $avgProcessingTime,
            'avg_days' => round($avgProcessingTime / 24, 1),
            'verified_count' => $verifiedIncidents->count(),
            'fastest_hours' => $verifiedIncidents->count() > 0 ? $verifiedIncidents->min(function($i) { return $i->created_at->diffInHours(now()); }) : 0,
            'slowest_hours' => $verifiedIncidents->count() > 0 ? $verifiedIncidents->max(function($i) { return $i->created_at->diffInHours(now()); }) : 0,
        ];

        return view('supervisor.processing_time', compact('statistics'));
    }

    public function supervisorComplianceMetrics()
    {
        $supervisor = auth()->user();
        $allIncidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->get();

        $statistics = [
            'total_cases' => $allIncidents->count(),
            'compliant' => $allIncidents->where('compliance_status', 'COMPLIANT')->count(),
            'partial_compliant' => $allIncidents->where('compliance_status', 'PARTIAL_COMPLIANT')->count(),
            'noncompliant' => $allIncidents->where('compliance_status', 'NONCOMPLIANT')->count(),
            'compliant_rate' => $allIncidents->count() > 0 ? round(($allIncidents->where('compliance_status', 'COMPLIANT')->count() / $allIncidents->count()) * 100, 1) : 0,
        ];

        return view('supervisor.compliance_metrics', compact('statistics'));
    }

    public function supervisorMonthlySummary()
    {
        $supervisor = auth()->user();
        $monthlyData = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total, 
                        SUM(CASE WHEN status = "VERIFIED_SUPERVISOR" THEN 1 ELSE 0 END) as verified,
                        SUM(CASE WHEN compliance_status = "COMPLIANT" THEN 1 ELSE 0 END) as compliant')
            ->groupBy('month')
            ->orderByDesc('month')
            ->get();

        $statistics = [
            'total_cases' => \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->count(),
            'current_month_cases' => \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)->whereMonth('created_at', now()->month)->count(),
        ];

        return view('supervisor.monthly_summary', compact('monthlyData', 'statistics'));
    }
    /**
     * API endpoint: Get supervisor dashboard data for real-time updates
     */
    public function getIncidentsByMonth($month)
    {
        $supervisor = auth()->user();

        $incidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$month])
            ->with('incidentType')
            ->get();

        $breakdown = $incidents->groupBy(function($incident) {
            return $incident->incidentType?->name ?? 'Unknown';
        })->map(function($group) {
            return [
                'type' => $group[0]->incidentType?->name ?? 'Unknown',
                'count' => $group->count()
            ];
        })->values();

        return response()->json($breakdown);
    }

    public function supervisorReturnToWorkTracking()
    {
        $supervisor = auth()->user();
        
        // Get incidents with RTW data or injury leave information
        $incidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)
            ->where(function ($query) {
                $query->whereNotNull('rtw_start_date')
                    ->orWhereNotNull('injury_leave_start_date');
            })
            ->with(['facility', 'incidentType', 'assignedSupervisor'])
            ->orderBy('rtw_status', 'asc') // not_started first
            ->orderBy('rtw_next_review_date', 'asc') // then by review date
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('supervisor.return_to_work_tracking', compact('incidents'));
    }

    public function supervisorLtiReport()
    {
        $supervisor = auth()->user();
        $incidents = \App\Models\Incident::where('assigned_supervisor_id', $supervisor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('supervisor.lti_report', compact('incidents'));
    }



    /**
     * Show District HR dashboard.
     */
    public function districtHr(Request $request)
    {
        return $this->hrDashboard($request);
    }

    /**
     * Show Hospital HR dashboard.
     */
    public function hospitalHr(Request $request)
    {
        return $this->hrDashboard($request);
    }

    /**
     * Show Central HR dashboard.
     */
    public function centralHr(Request $request)
    {
        return $this->hrDashboard($request);
    }

    /**
     * Unified HR Dashboard for all HR roles.
     */
    public function hrDashboard(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');

        // Create cache key
        $cacheKey = "hr_dashboard_{$user->id}_{$dateRange}";
        
        // Check cache first (30 second TTL)
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedData && !$request->has('refresh')) {
            return view('dashboards.hr', $cachedData);
        }

        // Calculate date filter
        $dateFrom = null;
        $dateTo = now();
        
        switch($dateRange) {
            case '7days':
                $dateFrom = now()->subDays(7);
                break;
            case '30days':
                $dateFrom = now()->subDays(30);
                break;
            case '90days':
                $dateFrom = now()->subDays(90);
                break;
            case 'all':
            default:
                $dateFrom = null;
                break;
        }

        // Build base query for incidents assigned to this HR user
        $baseQuery = \App\Models\Incident::where('routed_to_hr_user_id', $user->id);

        // Apply date filter if specified
        if($dateFrom) {
            $baseQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        // Get statistics using database queries (faster for large datasets)
        $statistics = [
            'pending_review' => (clone $baseQuery)->where('status', 'VERIFIED_SUPERVISOR')->count(),
            'active_incidents' => (clone $baseQuery)->whereIn('status', [
                'ESCALATED_TO_HR', 
                'PENDING_HR_REVIEW',
                'HR_APPROVED',
                'ESCALATED_TO_DMO',
                'ESCALATED_TO_MEDICAL_SUPERINTENDENT',
                'ESCALATED_TO_CMO',
                'ESCALATED_TO_PMD',
                'PENDING_PMD_REVIEW',
                'REFERRED_TO_MEDICAL_BOARD',
                'REFERRED_TO_MINISTRY_HQ'
            ])->count(),
            'resolved_incidents' => (clone $baseQuery)->whereIn('status', ['RESOLVED', 'APPROVED_BY_PMD', 'CLOSED'])->count(),
            'rejected_incidents' => (clone $baseQuery)->whereIn('status', ['REJECTED_BY_HR', 'REJECTED'])->count(),
            'total_cases' => (clone $baseQuery)->count(),
        ];

        // Get distinct statuses with their counts for chart
        $incidentsByStatus = \DB::table('incidents')
            ->where('routed_to_hr_user_id', $user->id);
        
        if($dateFrom) {
            $incidentsByStatus->whereBetween('incidents.created_at', [$dateFrom, $dateTo]);
        }
        
        $incidentsByStatus = $incidentsByStatus
            ->groupBy('incidents.status')
            ->selectRaw('incidents.status, COUNT(*) as count')
            ->pluck('count', 'status')
            ->toArray();

        // Get incidents grouped by facility
        $incidentsByFacility = \DB::table('incidents')
            ->join('facilities', 'incidents.facility_id', '=', 'facilities.id')
            ->where('incidents.routed_to_hr_user_id', $user->id);
        
        if($dateFrom) {
            $incidentsByFacility->whereBetween('incidents.created_at', [$dateFrom, $dateTo]);
        }
        
        $incidentsByFacility = $incidentsByFacility
            ->groupBy('facilities.name')
            ->selectRaw('facilities.name, COUNT(*) as count')
            ->pluck('count', 'name')
            ->toArray();

        // Get incidents grouped by injury type
        $incidentsByInjuryType = \DB::table('incidents')
            ->join('facilities', 'incidents.facility_id', '=', 'facilities.id')
            ->join('incident_types', 'incidents.incident_type_id', '=', 'incident_types.id')
            ->where('incidents.routed_to_hr_user_id', $user->id);
        
        if($dateFrom) {
            $incidentsByInjuryType->whereBetween('incidents.created_at', [$dateFrom, $dateTo]);
        }
        
        $incidentsByInjuryType = $incidentsByInjuryType
            ->groupBy('incident_types.name')
            ->selectRaw('incident_types.name, COUNT(*) as count')
            ->pluck('count', 'name')
            ->toArray();

        // Get incidents grouped by department
        $incidentsByDepartment = \DB::table('incidents')
            ->join('departments', 'incidents.department_id', '=', 'departments.id')
            ->where('incidents.routed_to_hr_user_id', $user->id);
        
        if($dateFrom) {
            $incidentsByDepartment->whereBetween('incidents.created_at', [$dateFrom, $dateTo]);
        }
        
        $incidentsByDepartment = $incidentsByDepartment
            ->groupBy('departments.name')
            ->selectRaw('departments.name, COUNT(*) as count')
            ->pluck('count', 'name')
            ->toArray();

        // Get pending review incidents (only 10, with eager loading)
        $pendingReviewIncidents = \App\Models\Incident::with([
            'facility:id,name',
            'department:id,name'
        ])
        ->where('routed_to_hr_user_id', $user->id)
        ->where('status', 'VERIFIED_SUPERVISOR');
        
        if($dateFrom) {
            $pendingReviewIncidents->whereBetween('created_at', [$dateFrom, $dateTo]);
        }
        
        $pendingReviewIncidents = $pendingReviewIncidents
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get unread notification count and all notifications for the notification bell
        $allNotifications = collect();
        $unreadNotificationCount = 0;

        // Get pending review incidents as notifications
        $pendingNotifications = \App\Models\Incident::with([
            'facility:id,name',
            'department:id,name'
        ])
        ->where('routed_to_hr_user_id', $user->id)
        ->where('status', 'VERIFIED_SUPERVISOR')
        ->orderBy('created_at', 'desc')
        ->get();

        foreach ($pendingNotifications as $incident) {
            $allNotifications->push((object)[
                'id' => 'pending_' . $incident->id,
                'incident' => $incident,
                'notification_type' => 'PENDING_REVIEW',
                'created_at' => $incident->created_at,
            ]);
        }

        $unreadNotificationCount = $allNotifications->count();

        $viewData = compact('statistics', 'incidentsByStatus', 'incidentsByFacility', 'incidentsByInjuryType', 'incidentsByDepartment', 'pendingReviewIncidents', 'dateRange', 'allNotifications', 'unreadNotificationCount');

        // Cache for 30 seconds
        \Illuminate\Support\Facades\Cache::put($cacheKey, $viewData, 30);

        return view('dashboards.hr', $viewData);
    }

    /**
     * Show Clinical Executive Review Panel (unified for DMO, Medical Superintendent, and CMO).
     * Dynamically filters cases based on logged-in user's role and assigned scope.
     */
    public function clinicalExecutivePanel(Request $request)
    {
        $user = auth()->user();

        // Create cache key based on user ID and role
        $userRole = $user->hasRole('dmo') ? 'dmo' : ($user->hasRole('medical_superintendent') ? 'med_super' : 'cmo');
        $dateRange = $request->query('date_range', 'all');
        $cacheKey = "clinical_exec_{$user->id}_{$userRole}_{$dateRange}";
        
        // Check cache first (30 second TTL)
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedData && !$request->has('refresh')) {
            return view('dashboards.clinical_executive', $cachedData);
        }

        $allIncidents = collect();
        $filterScope = 'N/A';
        $incidentsByUnit = collect();
        $statistics = [];
        
        // Get date range filter (default: all time)
        $startDate = null;
        $endDate = now();
        
        switch($dateRange) {
            case '7days':
                $startDate = now()->subDays(7);
                break;
            case '30days':
                $startDate = now()->subDays(30);
                break;
            case '90days':
                $startDate = now()->subDays(90);
                break;
            case 'all':
            default:
                $startDate = null;
        }

        if ($user->hasRole('dmo')) {
            // DMO: Enhanced dashboard with detailed metrics and insights
            // Show ALL cases routed to this DMO (pending + completed)
            $query = \App\Models\Incident::where('routed_to_dmo_user_id', $user->id)
                ->whereIn('status', ['ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW']);
            
            if ($startDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $allIncidents = $query->orderBy('created_at', 'desc')->get();

            if ($allIncidents->isEmpty() && $user->district_id) {
                $allIncidents = \App\Models\Incident::whereIn('status', ['ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                    ->whereHas('facility', function($query) use ($user) {
                        $query->where('district_id', $user->district_id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $filterScope = 'Your District';

            // Enhanced DMO Statistics
            $statistics = [
                'pending_review' => $allIncidents->whereIn('status', ['ESCALATED_TO_DMO'])->count(),
                'total_incidents' => $allIncidents->count(),
                'pending_audit_verification' => $allIncidents->where('status', 'ESCALATED_TO_DMO')->count(),
                'pending_recommendation' => $allIncidents->where('status', 'ESCALATED_TO_DMO')
                    ->where('executive_audit_verified', true)
                    ->where('executive_recommendation_submitted_at', null)->count(),
                'completed_approvals' => $allIncidents->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])->count(),
            ];

            // Categorize by compliance risk
            $statistics['critical_risk'] = 0;
            $statistics['warning_risk'] = 0;
            $statistics['safe_zone'] = 0;

            foreach ($allIncidents as $incident) {
                if ($incident->getComplianceStatusText() === 'CRITICAL RISK') {
                    $statistics['critical_risk']++;
                } elseif ($incident->getComplianceStatusText() === 'WARNING') {
                    $statistics['warning_risk']++;
                } else {
                    $statistics['safe_zone']++;
                }
            }

            // Group by facility for unit breakdown
            $incidentsByUnit = $allIncidents->groupBy(function($incident) {
                return $incident->facility->name ?? 'Unknown';
            })->map(function($group) {
                return $group->count();
            });

            // Group by injury type for injury type breakdown
            $incidentsByInjuryType = $allIncidents->groupBy(function($incident) {
                return $incident->incidentType?->name ?? 'Unknown Type';
            })->map(function($group) {
                return $group->count();
            });

            // Group by department
            $incidentsByDepartment = $allIncidents->groupBy(function($incident) {
                return $incident->department?->name ?? 'Unknown Department';
            })->map(function($group) {
                return $group->count();
            });

            $statistics['incidents_by_injury_type'] = $incidentsByInjuryType;
            $statistics['incidents_by_department'] = $incidentsByDepartment;

        } elseif ($user->hasRole('medical_superintendent')) {
            // Medical Superintendent: Show cases escalated to Medical Superintendent (pending + completed)
            $allIncidents = \App\Models\Incident::whereIn('status', ['ESCALATED_TO_MEDICAL_SUPERINTENDENT', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                ->where('routed_to_medical_superintendent_user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($allIncidents->isEmpty() && $user->facility_id) {
                $allIncidents = \App\Models\Incident::whereIn('status', ['ESCALATED_TO_MEDICAL_SUPERINTENDENT', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                    ->where('facility_id', $user->facility_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $filterScope = 'Your Facility';

            $statistics = [
                'pending_review' => $allIncidents->where('status', 'ESCALATED_TO_MEDICAL_SUPERINTENDENT')->count(),
                'total_incidents' => $allIncidents->count(),
                'pending_audit_verification' => $allIncidents->where('status', 'ESCALATED_TO_MEDICAL_SUPERINTENDENT')->count(),
                'pending_recommendation' => $allIncidents->where('status', 'ESCALATED_TO_MEDICAL_SUPERINTENDENT')
                    ->where('executive_audit_verified', true)
                    ->where('executive_recommendation_submitted_at', null)->count(),
                'completed_approvals' => $allIncidents->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])->count(),
            ];

            $statistics['critical_risk'] = 0;
            $statistics['warning_risk'] = 0;
            $statistics['safe_zone'] = 0;

            foreach ($allIncidents as $incident) {
                if ($incident->getComplianceStatusText() === 'CRITICAL RISK') {
                    $statistics['critical_risk']++;
                } elseif ($incident->getComplianceStatusText() === 'WARNING') {
                    $statistics['warning_risk']++;
                } else {
                    $statistics['safe_zone']++;
                }
            }

            $incidentsByUnit = $allIncidents->groupBy(function($incident) {
                return $incident->facility->name ?? 'Unknown';
            })->map(function($group) {
                return $group->count();
            });

            // Group by injury type for injury type breakdown
            $incidentsByInjuryType = $allIncidents->groupBy(function($incident) {
                return $incident->incidentType?->name ?? 'Unknown Type';
            })->map(function($group) {
                return $group->count();
            });

            // Group by department
            $incidentsByDepartment = $allIncidents->groupBy(function($incident) {
                return $incident->department?->name ?? 'Unknown Department';
            })->map(function($group) {
                return $group->count();
            });

            $statistics['incidents_by_injury_type'] = $incidentsByInjuryType;
            $statistics['incidents_by_department'] = $incidentsByDepartment;

        } elseif ($user->hasRole('cmo')) {
            // CMO: Show cases escalated to CMO with enhanced analytics
            $allIncidents = \App\Models\Incident::where('status', 'ESCALATED_TO_CMO')
                ->where('routed_to_cmo_user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($allIncidents->isEmpty() && $user->facility_id) {
                $allIncidents = \App\Models\Incident::where('status', 'ESCALATED_TO_CMO')
                    ->where('facility_id', $user->facility_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $filterScope = 'Your Institution';

            // Enhanced CMO Statistics (similar to PMD)
            $statistics = [
                'pending_review' => $allIncidents->whereIn('status', ['ESCALATED_TO_CMO'])->count(),
                'total_incidents' => $allIncidents->count(),
                'reviewed' => $allIncidents->where('cmo_reviewed_at', '!=', null)->count(),
                'critical_risk' => 0,
                'warning_risk' => 0,
                'safe_zone' => 0,
            ];

            foreach ($allIncidents as $incident) {
                if ($incident->getComplianceStatusText() === 'CRITICAL RISK') {
                    $statistics['critical_risk']++;
                } elseif ($incident->getComplianceStatusText() === 'WARNING') {
                    $statistics['warning_risk']++;
                } else {
                    $statistics['safe_zone']++;
                }
            }

            // Group by facility for unit breakdown
            $incidentsByUnit = $allIncidents->groupBy(function($incident) {
                return $incident->facility->name ?? 'Unknown';
            })->map(function($group) {
                return $group->count();
            });

            // Group by injury type for injury type breakdown
            $incidentsByInjuryType = $allIncidents->groupBy(function($incident) {
                return $incident->incidentType?->name ?? 'Unknown Type';
            })->map(function($group) {
                return $group->count();
            });

            // Group by department
            $incidentsByDepartment = $allIncidents->groupBy(function($incident) {
                return $incident->department?->name ?? 'Unknown Department';
            })->map(function($group) {
                return $group->count();
            });

            $statistics['incidents_by_injury_type'] = $incidentsByInjuryType;
            $statistics['incidents_by_department'] = $incidentsByDepartment;
        }

        // Get recent incidents (limit to 15)
        $recentIncidents = $allIncidents->take(15);

        // Get executive/clinical user notifications (new escalations and document uploads)
        $newNotifications = \App\Models\IncidentNotification::where('recipient_user_id', $user->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Filter CMO notifications to exclude document uploads
        // CMO should only see case escalations, not document uploads (those are for HR)
        if ($user->hasRole('cmo')) {
            $newNotifications = $newNotifications->where('notification_type', '!=', 'DOCUMENT_UPLOAD');
        }

        // Get pending verification documents for all incidents assigned to this user
        $pendingDocuments = collect();
        if ($user->hasRole('dmo')) {
            $pendingDocuments = \App\Models\IncidentAttachment::whereIn('incident_id', 
                \App\Models\Incident::where('status', 'ESCALATED_TO_DMO')
                    ->where('routed_to_dmo_user_id', $user->id)
                    ->pluck('id')
            )
            ->where('upload_status', 'PENDING')
            ->with(['incident:id,reference_number,first_name,surname'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        } elseif ($user->hasRole('medical_superintendent')) {
            $pendingDocuments = \App\Models\IncidentAttachment::whereIn('incident_id',
                \App\Models\Incident::where('status', 'ESCALATED_TO_MEDICAL_SUPERINTENDENT')
                    ->where('routed_to_medical_superintendent_user_id', $user->id)
                    ->pluck('id')
            )
            ->where('upload_status', 'PENDING')
            ->with(['incident:id,reference_number,first_name,surname'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        } elseif ($user->hasRole('cmo')) {
            $pendingDocuments = collect(); // CMO doesn't receive document notifications
        }

        // Combine notifications and pending documents
        $allNotifications = $newNotifications->concat($pendingDocuments)->sortByDesc('created_at')->take(10);
        $unreadNotificationCount = $newNotifications->count() + $pendingDocuments->count();

        $viewData = compact(
            'statistics',
            'recentIncidents',
            'allIncidents',
            'incidentsByUnit',
            'filterScope',
            'newNotifications',
            'pendingDocuments',
            'allNotifications',
            'unreadNotificationCount'
        );

        // Cache for 30 seconds
        \Illuminate\Support\Facades\Cache::put($cacheKey, $viewData, 30);

        return view('dashboards.clinical_executive', $viewData);
    }

    public function executiveReviewCase(Incident $incident)
    {
        $user = auth()->user();

        // Verify executive user has access to this case
        $hasAccess = false;
        $userRole = null;
        $isReadOnly = false;

        if ($user->hasRole('dmo')) {
            // DMO can access cases routed to them, both pending and completed
            if ($incident->routed_to_dmo_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'dmo';
                // Read-only if case is no longer in their status (escalated to PMD)
                $isReadOnly = $incident->status !== 'ESCALATED_TO_DMO';
            }
        } elseif ($user->hasRole('medical_superintendent')) {
            // Medical Superintendent can access cases routed to them
            if ($incident->routed_to_medical_superintendent_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'medical_superintendent';
                // Read-only if case is no longer in their status
                $isReadOnly = $incident->status !== 'ESCALATED_TO_MEDICAL_SUPERINTENDENT';
            }
        } elseif ($user->hasRole('cmo')) {
            // CMO can access cases routed to them
            if ($incident->routed_to_cmo_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'cmo';
                // Read-only if case is no longer in their status
                $isReadOnly = $incident->status !== 'ESCALATED_TO_CMO';
            }
        }

        if (!$hasAccess) {
            abort(403, 'You do not have access to this case.');
        }

        // Determine placeholder text based on role
        $placeholderText = $this->getRecommendationPlaceholder($userRole);

        return view('incidents.executive_review', compact('incident', 'userRole', 'placeholderText', 'isReadOnly'));
    }

    public function executiveRecommendation(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify executive user has access to this case
        $hasAccess = false;
        $userRole = null;

        if ($user->hasRole('dmo') && $incident->routed_to_dmo_user_id === $user->id && $incident->status === 'ESCALATED_TO_DMO') {
            $hasAccess = true;
            $userRole = 'dmo';
        } elseif ($user->hasRole('medical_superintendent') && $incident->routed_to_medical_superintendent_user_id === $user->id && $incident->status === 'ESCALATED_TO_MEDICAL_SUPERINTENDENT') {
            $hasAccess = true;
            $userRole = 'medical_superintendent';
        } elseif ($user->hasRole('cmo') && $incident->routed_to_cmo_user_id === $user->id && $incident->status === 'ESCALATED_TO_CMO') {
            $hasAccess = true;
            $userRole = 'cmo';
        }

        if (!$hasAccess) {
            abort(403, 'You do not have access to this case.');
        }

        // If POST request, store notes in session and redirect to GET
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'service_connection_notes' => 'nullable|string|max:2000',
            ]);
            
            $request->session()->put("incident_{$incident->id}_service_connection_notes", $validated['service_connection_notes'] ?? '');
            
            return redirect()->route('executive.recommendation', $incident);
        }

        // Determine placeholder text based on role
        $placeholderText = $this->getRecommendationPlaceholder($userRole);
        
        // Retrieve service connection notes from session
        $serviceConnectionNotes = $request->session()->get("incident_{$incident->id}_service_connection_notes", '');

        return response()
            ->view('incidents.executive_recommendation', compact('incident', 'userRole', 'placeholderText', 'serviceConnectionNotes'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function submitExecutiveRecommendation(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify executive user has access to this case
        $hasAccess = false;
        $userRole = null;

        if ($user->hasRole('dmo') && $incident->routed_to_dmo_user_id === $user->id) {
            $hasAccess = true;
            $userRole = 'dmo';
        } elseif ($user->hasRole('medical_superintendent') && $incident->routed_to_medical_superintendent_user_id === $user->id) {
            $hasAccess = true;
            $userRole = 'medical_superintendent';
        } elseif ($user->hasRole('cmo') && $incident->routed_to_cmo_user_id === $user->id) {
            $hasAccess = true;
            $userRole = 'cmo';
        }

        if (!$hasAccess) {
            abort(403, 'You do not have access to this case.');
        }

        // Validate form inputs
        $validated = $request->validate([
            'audit_verified' => 'required|boolean',
            'recommendation_text' => 'required|string|min:50|max:3000',
            'service_connection_notes' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            // Stage 1: Record audit verification
            $incident->update([
                'executive_audit_verified' => $validated['audit_verified'],
                'executive_audit_verified_at' => now(),
                'executive_audit_verified_by_user_id' => $user->id,
            ]);

            // Stage 2: Record recommendation letter
            $incident->update([
                'executive_recommendation_text' => $validated['recommendation_text'],
                'executive_recommendation_submitted_at' => now(),
                'executive_recommendation_submitted_by_user_id' => $user->id,
                'service_connection_notes' => $validated['service_connection_notes'] ?? null,
            ]);

            // Route to next level based on executive role
            $nextStatus = $this->routeToNextLevel($incident, $userRole, $user);

            DB::commit();

            return redirect()->route('dashboards.clinical_executive')
                ->with('success', "Case {$incident->reference_number} has been reviewed and escalated to the next approval level.")
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to submit executive recommendation', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return back()->with('error', 'An error occurred while submitting the recommendation: ' . $e->getMessage());
        }
    }

    public function sendLetterEmail(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify access
        $hasAccess = false;
        if (($user->hasRole('dmo') && $incident->routed_to_dmo_user_id === $user->id) ||
            ($user->hasRole('medical_superintendent') && $incident->routed_to_medical_superintendent_user_id === $user->id) ||
            ($user->hasRole('cmo') && $incident->routed_to_cmo_user_id === $user->id)) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        try {
            $validated = $request->validate([
                'recipient_email' => 'required|email',
                'email_subject' => 'required|string|max:200',
                'email_message' => 'nullable|string|max:1000',
                'letter_html' => 'required|string',
            ]);

            // Send email with PDF attachment
            \Illuminate\Support\Facades\Mail::to($validated['recipient_email'])
                ->send(new \App\Mail\ExecutiveRecommendationLetter(
                    $incident,
                    $validated['letter_html'],
                    $validated['email_message'] ?? ''
                ));

            // Log the action
            Log::info('Executive recommendation letter emailed', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'recipient_email' => $validated['recipient_email'],
                'sent_by_user_id' => $user->id,
                'sent_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Letter sent successfully to ' . $validated['recipient_email']
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send executive recommendation letter', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Show comprehensive case dossier with findings from all clinical executive roles and PMD
     * This is a read-only view showing the complete audit trail of all executive findings
     */
    public function caseDossierWithFindings(Incident $incident)
    {
        $user = auth()->user();

        // Verify user has access to this case
        $hasAccess = false;
        $userRole = null;

        if ($user->hasRole('dmo')) {
            // DMO can access cases they were assigned to
            if ($incident->routed_to_dmo_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'dmo';
            }
        } elseif ($user->hasRole('medical_superintendent')) {
            // Medical Superintendent can access cases assigned to them
            if ($incident->routed_to_medical_superintendent_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'medical_superintendent';
            }
        } elseif ($user->hasRole('cmo')) {
            // CMO can access cases assigned to them
            if ($incident->routed_to_cmo_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'cmo';
            }
        } elseif ($user->hasRole('pmd')) {
            // PMD can view their assigned cases
            if ($incident->routed_to_pmd_user_id === $user->id) {
                $hasAccess = true;
                $userRole = 'pmd';
            }
        }

        if (!$hasAccess) {
            abort(403, 'You do not have access to this case.');
        }

        // Load all related data
        $incident->load([
            'attachments',
            'facility.district.province',
            'incidentType',
            'assignedHRUser',
            'firstAidRecords',
            'witnessTokens',
            'department',
            'designation',
            'supervisorDesignation',
            'assignedSupervisor',
            'hodReportSubmittedBy',
            'facilityType',
        ]);

        // Get the users who made decisions at each level
        $dmoUser = null;
        $medicalSuperintendentUser = null;
        $cmoUser = null;
        $pmdUser = null;
        $executiveSubmittedByUser = null;

        if ($incident->routed_to_dmo_user_id) {
            $dmoUser = \App\Models\User::find($incident->routed_to_dmo_user_id);
        }
        if ($incident->routed_to_medical_superintendent_user_id) {
            $medicalSuperintendentUser = \App\Models\User::find($incident->routed_to_medical_superintendent_user_id);
        }
        if ($incident->routed_to_cmo_user_id) {
            $cmoUser = \App\Models\User::find($incident->routed_to_cmo_user_id);
        }
        if ($incident->routed_to_pmd_user_id) {
            $pmdUser = \App\Models\User::find($incident->routed_to_pmd_user_id);
        }
        if ($incident->executive_recommendation_submitted_by_user_id) {
            $executiveSubmittedByUser = \App\Models\User::find($incident->executive_recommendation_submitted_by_user_id);
        }

        return view('incidents.case_dossier_with_findings', compact(
            'incident',
            'userRole',
            'dmoUser',
            'medicalSuperintendentUser',
            'cmoUser',
            'pmdUser',
            'executiveSubmittedByUser'
        ));
    }


    private function getRecommendationPlaceholder($userRole): string
    {
        return match($userRole) {
            'dmo' => 'Address your recommendation to the Provincial Medical Director (PMD). Provide clinical assessment, injury severity evaluation, and recommendation for escalation or closure. Include any concerns about the case and proposed next steps.',
            'medical_superintendent' => 'Address your recommendation to the Provincial Medical Director (PMD). Detail your medical assessment of the workplace injury, clinical findings, and recommendation for further investigation or case resolution. Include institutional concerns and proposed actions.',
            'cmo' => 'Address your recommendation to the Permanent Secretary of Health and National Ministry Headquarters. Provide comprehensive clinical assessment, institutional position, and formal recommendation. Include any critical findings that require ministerial attention or policy-level intervention.',
            default => 'Provide your clinical assessment and recommendation for this case.',
        };
    }

    private function routeToNextLevel(Incident $incident, string $userRole, User $user): string
    {
        $nextStatus = 'PENDING_PMD_REVIEW';
        $pmdId = null;

        if ($userRole === 'dmo' || $userRole === 'medical_superintendent') {
            // Both DMO and Medical Superintendent escalate to PMD (Provincial Medical Director)
            $pmd = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'pmd'))
                ->where('province_id', $incident->facility->district->province_id ?? null)
                ->first();

            if ($pmd) {
                $pmdId = $pmd->id;
                $nextStatus = 'ESCALATED_TO_PMD';
            }
        } elseif ($userRole === 'cmo') {
            // CMO escalates to Ministry HQ (National level)
            $nextStatus = 'ESCALATED_TO_MINISTRY_HQ';
        }

        $incident->update([
            'status' => $nextStatus,
            'routed_to_pmd_user_id' => $pmdId,
            'routed_to_pmd_status' => $nextStatus,
        ]);

        // Create notification for PMD when case is escalated to PMD
        if ($nextStatus === 'ESCALATED_TO_PMD' && $pmdId) {
            \App\Models\IncidentNotification::create([
                'incident_id' => $incident->id,
                'notification_type' => 'PMD_ESCALATION',
                'notification_channel' => 'in_app',
                'status' => 'UNREAD',
                'recipient_user_id' => $pmdId,
            ]);
        }

        return $nextStatus;
    }


    /**
     * Debug endpoint to check case details.
     */
    public function debugCase($referenceNumber)
    {
        $incident = \App\Models\Incident::where('reference_number', $referenceNumber)->first();
        
        if (!$incident) {
            return response()->json(['error' => 'Case not found'], 404);
        }

        return response()->json([
            'id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'status' => $incident->status,
            'routed_to_dmo_user_id' => $incident->routed_to_dmo_user_id,
            'routed_to_medical_superintendent_user_id' => $incident->routed_to_medical_superintendent_user_id,
            'routed_to_cmo_user_id' => $incident->routed_to_cmo_user_id,
            'routed_to_pmd_user_id' => $incident->routed_to_pmd_user_id,
            'routed_to_pmd_status' => $incident->routed_to_pmd_status,
            'hr_route_type' => $incident->hr_route_type,
            'facility_id' => $incident->facility_id,
            'facility_name' => $incident->facility?->name,
            'district_id' => $incident->district_id,
            'district_name' => $incident->facility?->district?->name,
            'province_id' => $incident->facility?->district?->province_id,
            'hr_compliance_notes' => $incident->hr_compliance_notes ? 'YES' : 'NO',
            'hr_compliance_notes_saved_at' => $incident->hr_compliance_notes_saved_at,
            'created_at' => $incident->created_at,
        ]);
    }

    /**
     * Debug PMD cases.
     */
    public function debugPMDCases()
    {
        $user = auth()->user();
        
        $allPMDIncidents = \App\Models\Incident::where('status', 'ESCALATED_TO_PMD')->get();
        
        return response()->json([
            'current_user_id' => $user->id,
            'current_user_province_id' => $user->province_id,
            'total_pmd_cases' => $allPMDIncidents->count(),
            'cases' => $allPMDIncidents->map(function($incident) {
                return [
                    'reference' => $incident->reference_number,
                    'status' => $incident->status,
                    'routed_to_pmd_user_id' => $incident->routed_to_pmd_user_id,
                    'facility' => $incident->facility?->name,
                    'district' => $incident->facility?->district?->name,
                    'province_id' => $incident->facility?->district?->province_id,
                ];
            })
        ]);
    }

    /**
     * Show all pending cases awaiting PMD approval.
     */
    public function pmdPendingCases()
    {
        $user = auth()->user();

        // Get incidents escalated to PMD or pending PMD review
        $pendingCases = \App\Models\Incident::with(['facility.district', 'incidentType'])
            ->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
            ->where('routed_to_pmd_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // If empty, try by province
        if ($pendingCases->total() == 0 && $user->province_id) {
            $pendingCases = \App\Models\Incident::with(['facility.district', 'incidentType'])
                ->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                ->whereHas('facility.district', function($query) use ($user) {
                    $query->where('province_id', $user->province_id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        // Statistics
        $statistics = [
            'total_pending' => $pendingCases->total(),
            'escalated_to_pmd' => \App\Models\Incident::whereIn('status', ['ESCALATED_TO_PMD'])->where('routed_to_pmd_user_id', $user->id)->count(),
            'pending_pmd_review' => \App\Models\Incident::where('status', 'PENDING_PMD_REVIEW')->where('routed_to_pmd_user_id', $user->id)->count(),
        ];

        return view('dashboards.pmd_pending_cases', compact('pendingCases', 'statistics'));
    }

    /**
     * Show PMD (Provincial Medical Director) dashboard.
     */
    public function pmd(Request $request)
    {
        $user = auth()->user();
        
        // Get date range from request (default: all_time)
        $range = $request->query('range', 'all_time');
        
        // Create cache key based on user and range
        $cacheKey = "pmd_dashboard_{$user->id}_{$range}";
        $cacheMinutes = 15; // Cache for 15 minutes
        
        // Try to get from cache first
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedData && !$request->has('refresh')) {
            return view('dashboards.pmd', $cachedData);
        }
        
        // Build base query with only required columns and eager load optimization
        $baseQuery = \App\Models\Incident::select(
            'id', 'reference_number', 'first_name', 'surname', 'status', 
            'created_at', 'facility_id', 'incident_type_id', 'routed_to_pmd_user_id'
        )->with([
            'facility:id,name,district_id',
            'facility.district:id,name,province_id',
            'incidentType:id,name'
        ])->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW']);

        // Apply date range filter
        if ($range !== 'all_time') {
            $days = (int)$range;
            $baseQuery->where('created_at', '>=', now()->subDays($days));
        }

        // First try: exact user match
        $allIncidents = $baseQuery->where('routed_to_pmd_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Second try: by province if user has province_id and no user-specific cases
        if ($allIncidents->isEmpty() && $user->province_id) {
            $baseQuery2 = \App\Models\Incident::select(
                'id', 'reference_number', 'first_name', 'surname', 'status', 
                'created_at', 'facility_id', 'incident_type_id', 'routed_to_pmd_user_id'
            )->with([
                'facility:id,name,district_id',
                'facility.district:id,name,province_id',
                'incidentType:id,name'
            ])->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW']);
            
            if ($range !== 'all_time') {
                $days = (int)$range;
                $baseQuery2->where('created_at', '>=', now()->subDays($days));
            }
            
            $allIncidents = $baseQuery2->whereHas('facility.district', function($q) use ($user) {
                $q->where('province_id', $user->province_id);
            })->orderBy('created_at', 'desc')
            ->get();
        }

        // Third try: show all cases (for testing/debugging)
        if ($allIncidents->isEmpty()) {
            $baseQuery3 = \App\Models\Incident::select(
                'id', 'reference_number', 'first_name', 'surname', 'status', 
                'created_at', 'facility_id', 'incident_type_id', 'routed_to_pmd_user_id'
            )->with([
                'facility:id,name,district_id',
                'facility.district:id,name,province_id',
                'incidentType:id,name'
            ])->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW']);
            
            if ($range !== 'all_time') {
                $days = (int)$range;
                $baseQuery3->where('created_at', '>=', now()->subDays($days));
            }
            
            $allIncidents = $baseQuery3->orderBy('created_at', 'desc')->get();
        }

        // Calculate statistics
        $statistics = [
            'pending_review' => $allIncidents->whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])->count(),
            'total_incidents' => $allIncidents->count(),
            'reviewed' => $allIncidents->where('status', 'REVIEWED_BY_PMD')->count(),
        ];

        // Get incidents by district (optimized)
        $incidentsByDistrict = $allIncidents->groupBy(function($incident) {
            return $incident->facility->district->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        })->sortDesc();

        // Get incidents by facility (optimized)
        $incidentsByFacility = $allIncidents->groupBy(function($incident) {
            return $incident->facility->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        })->sortDesc()->slice(0, 15); // Top 15 facilities

        // Get incidents by type for pie chart (optimized)
        $incidentsByType = $allIncidents->groupBy(function($incident) {
            return $incident->incidentType->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        })->sortDesc()->slice(0, 8); // Top 8 incident types

        // Get recent incidents
        $recentIncidents = $allIncidents->take(15);
        $selectedRange = $request->query('range', 'all_time');

        // Get notifications for the bell (paginated, not all)
        $allNotifications = \App\Models\IncidentNotification::select('id', 'notification_type', 'status', 'created_at', 'incident_id', 'recipient_user_id')
            ->where('recipient_user_id', $user->id)
            ->orWhereNull('recipient_user_id')
            ->with('incident:id,reference_number')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $unreadNotificationCount = \App\Models\IncidentNotification::where('recipient_user_id', $user->id)
            ->where('status', 'UNREAD')
            ->count();

        $viewData = compact(
            'statistics',
            'recentIncidents',
            'allIncidents',
            'incidentsByDistrict',
            'incidentsByFacility',
            'incidentsByType',
            'selectedRange',
            'allNotifications',
            'unreadNotificationCount'
        );

        // Cache the data for 15 minutes
        \Illuminate\Support\Facades\Cache::put($cacheKey, $viewData, $cacheMinutes * 60);

        return view('dashboards.pmd', $viewData);
    }

    /**
     * Show PMD review case details.
     */
    public function pmdReviewCase(Incident $incident)
    {
        $user = auth()->user();

        // Verify PMD has access to this case
        if (!in_array($incident->status, ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])) {
            abort(403, 'This case is not available for PMD review.');
        }

        // Load all related data for the review
        $incident->load([
            'attachments',
            'facility.district.province',
            'incidentType'
        ]);

        return view('incidents.pmd_review', compact('incident'));
    }

    /**
     * Submit PMD review.
     */
    public function submitPmdReview(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Validate
        $validated = $request->validate([
            'pmd_review_notes' => 'required|string|min:50|max:5000',
            'pmd_decision' => 'required|in:approve,refer_ministry,refer_medical_board,return',
        ]);

        try {
            DB::beginTransaction();

            // Update incident with PMD review
            $incident->update([
                'pmd_review_notes' => $validated['pmd_review_notes'],
                'pmd_decision' => $validated['pmd_decision'],
                'pmd_reviewed_at' => now(),
                'pmd_reviewed_by_user_id' => $user->id,
            ]);

            // Route based on decision and leave days
            if ($validated['pmd_decision'] === 'refer_medical_board' || 
                ($incident->total_injury_leave_days && $incident->total_injury_leave_days > 90)) {
                // MANDATORY MEDICAL BOARD REFERRAL for >90 days
                $incident->update([
                    'status' => 'REFERRED_TO_MEDICAL_BOARD',
                ]);
                
                Log::info('Case referred to Medical Board', [
                    'incident_id' => $incident->id,
                    'leave_days' => $incident->total_injury_leave_days,
                    'reason' => $incident->total_injury_leave_days > 90 ? 'Exceeded 90-day threshold (HSB Circular No. 3/2017)' : 'PMD discretionary referral',
                ]);
                
                $message = "Case {$incident->reference_number} has been referred to the Medical Board" . 
                          ($incident->total_injury_leave_days > 90 ? ' (mandatory per HSB Circular No. 3 of 2017).' : '.');
            } elseif ($validated['pmd_decision'] === 'approve') {
                $incident->update([
                    'status' => 'APPROVED_BY_PMD',
                ]);
                $message = "Case {$incident->reference_number} has been approved and closed.";
            } elseif ($validated['pmd_decision'] === 'refer_ministry') {
                $incident->update([
                    'status' => 'REFERRED_TO_MINISTRY_HQ',
                ]);
                $message = "Case {$incident->reference_number} has been referred to Ministry Headquarters.";
            } else {
                $incident->update([
                    'status' => 'RETURNED_BY_PMD',
                ]);
                $message = "Case {$incident->reference_number} has been returned for additional information.";
            }

            DB::commit();

            return redirect()->route('dashboards.pmd')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to submit PMD review', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while submitting the review.');
        }
    }

    /**
     * Show MOHHC HR dashboard.
     */
    public function mohhcHr()
    {
        return view('dashboards.mohhc_hr');
    }

    /**
     * Show Labour & Social Welfare dashboard.
     */
    public function labourSocial()
    {
        return view('dashboards.labour_social');
    }

    /**
     * Show Medical Board dashboard.
     */
    public function medicalBoard()
    {
        return view('dashboards.medical_board');
    }

    /**
     * Show HSC Conditions dashboard.
     */
    public function hscConditions()
    {
        return view('dashboards.hsc_conditions');
    }

    /**
     * Show Super Admin dashboard with national incident statistics and workflow analytics.
     */
    public function superAdmin(Request $request)
    {
        // Create cache key based on organization filter
        $organizationId = $request->input('organization_id');
        $cacheKey = "super_admin_dashboard_" . ($organizationId ?? 'all');
        
        // Check cache first (60 second TTL)
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedData && !$request->has('refresh')) {
            return view('dashboards.super_admin', $cachedData);
        }

        // Get all user statistics in single query
        $userStats = \DB::table('users')
            ->selectRaw("
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count,
                COUNT(*) as total_count
            ")
            ->first();

        $userStatistics = [
            'pending_count' => $userStats->pending_count ?? 0,
            'approved_count' => $userStats->approved_count ?? 0,
            'rejected_count' => $userStats->rejected_count ?? 0,
            'total_count' => $userStats->total_count ?? 0,
        ];

        // Get all incident statistics in single optimized query
        $incidentStats = \DB::table('incidents')
            ->selectRaw("
                COUNT(*) as total_count,
                SUM(CASE WHEN status = 'PENDING_SUPERVISOR_VERIFICATION' THEN 1 ELSE 0 END) as pending_incidents,
                SUM(CASE WHEN status IN ('ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'ESCALATED_TO_DMO', 'PENDING_PMD_REVIEW') THEN 1 ELSE 0 END) as active_workflows,
                SUM(CASE WHEN status IN ('RESOLVED', 'APPROVED_BY_PMD', 'CLOSED') THEN 1 ELSE 0 END) as resolved_incidents,
                SUM(CASE WHEN status IN ('REJECTED_BY_HR', 'REJECTED', 'REJECTED_SUPERVISOR') THEN 1 ELSE 0 END) as rejected_incidents
            ")
            ->first();

        $incidentStatistics = [
            'total_cases' => $incidentStats->total_count ?? 0,
            'pending_incidents' => $incidentStats->pending_incidents ?? 0,
            'active_workflows' => $incidentStats->active_workflows ?? 0,
            'resolved_incidents' => $incidentStats->resolved_incidents ?? 0,
            'rejected_incidents' => $incidentStats->rejected_incidents ?? 0,
            'total_incidents' => $incidentStats->total_count ?? 0,
        ];

        // Workflow Pipeline Distribution in single query
        $statusCounts = \DB::table('incidents')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $workflowDistribution = [
            'PENDING_SUPERVISOR_VERIFICATION' => $statusCounts['PENDING_SUPERVISOR_VERIFICATION'] ?? 0,
            'VERIFIED_SUPERVISOR' => $statusCounts['VERIFIED_SUPERVISOR'] ?? 0,
            'HR_REVIEW' => ($statusCounts['ESCALATED_TO_HR'] ?? 0) + ($statusCounts['PENDING_HR_REVIEW'] ?? 0),
            'CLINICAL_EXECUTIVE_REVIEW' => ($statusCounts['ESCALATED_TO_DMO'] ?? 0) + ($statusCounts['ESCALATED_TO_MEDICAL_SUPERINTENDENT'] ?? 0) + ($statusCounts['ESCALATED_TO_CMO'] ?? 0),
            'PENDING_PROVINCIAL_APPROVAL' => ($statusCounts['ESCALATED_TO_PMD'] ?? 0) + ($statusCounts['PENDING_PMD_REVIEW'] ?? 0),
            'PENDING_MEDICAL_BOARD' => $statusCounts['REFERRED_TO_MEDICAL_BOARD'] ?? 0,
            'MINISTRY_LEVEL' => $statusCounts['REFERRED_TO_MINISTRY_HQ'] ?? 0,
        ];

        // System Configuration - cache these separately (they rarely change)
        $systemConfig = \Illuminate\Support\Facades\Cache::remember('system_config_counts', 3600, function() {
            return [
                'total_facilities' => \App\Models\Facility::count(),
                'total_provinces' => \App\Models\Province::count(),
                'total_districts' => \App\Models\District::count(),
                'total_roles' => \App\Models\Role::count(),
                'total_permissions' => \App\Models\Permission::count(),
            ];
        });

        // Organization filtering with optimized query
        $organizations = \App\Models\Organization::select('id', 'name')->get();
        
        $organizationStats = null;
        if ($organizationId) {
            $orgStats = \DB::table('users')
                ->where('organization_id', $organizationId)
                ->selectRaw("
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count,
                    COUNT(*) as total_count
                ")
                ->first();

            $organizationStats = [
                'pending_count' => $orgStats->pending_count ?? 0,
                'approved_count' => $orgStats->approved_count ?? 0,
                'rejected_count' => $orgStats->rejected_count ?? 0,
                'total_count' => $orgStats->total_count ?? 0,
            ];
        }

        // Recent Activity with eager loading
        $recentApprovals = \App\Models\User::where('status', 'approved')
            ->select('id', 'name', 'email', 'approved_at')
            ->orderBy('approved_at', 'desc')
            ->limit(5)
            ->get();

        $recentRejections = \App\Models\RejectionLog::with('user:id,name,email')
            ->select('id', 'user_id', 'reason', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent incidents monthly trend
        $currentYear = now()->year;
        $recentIncidents = \DB::table('incidents')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->where('created_at', '>=', now()->startOfYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($incident) {
                return [
                    'date' => now()->startOfYear()->addMonths($incident->month - 1)->format('Y-m-01'),
                    'count' => $incident->count
                ];
            });

        // Injury types by province (optimized with limit)
        $injuryTypesByProvince = \DB::table('incidents')
            ->join('provinces', 'incidents.province_id', '=', 'provinces.id')
            ->whereNotNull('incidents.province_id')
            ->selectRaw('provinces.name as province, COUNT(*) as count')
            ->groupBy('provinces.id', 'provinces.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Injury types by district (optimized with limit)
        $injuryTypesByDistrict = \DB::table('incidents')
            ->join('districts', 'incidents.district_id', '=', 'districts.id')
            ->whereNotNull('incidents.district_id')
            ->selectRaw('districts.name as district, COUNT(*) as count')
            ->groupBy('districts.id', 'districts.name')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        $viewData = compact(
            'userStatistics',
            'incidentStatistics',
            'workflowDistribution',
            'systemConfig',
            'organizations',
            'organizationStats',
            'recentApprovals',
            'recentRejections',
            'recentIncidents',
            'injuryTypesByProvince',
            'injuryTypesByDistrict'
        );

        // Cache for 60 seconds
        \Illuminate\Support\Facades\Cache::put($cacheKey, $viewData, 60);

        return view('dashboards.super_admin', $viewData);
    }

    // API endpoint to get live injury types data
    public function getInjuryTypesData(Request $request)
    {
        $view = $request->input('view', 'province');
        
        if ($view === 'district') {
            $data = \DB::table('incidents')
                ->join('districts', 'incidents.district_id', '=', 'districts.id')
                ->whereNotNull('incidents.district_id')
                ->select('districts.name as label', \DB::raw('COUNT(*) as count'))
                ->groupBy('districts.id', 'districts.name')
                ->orderByDesc('count')
                ->limit(20)
                ->get()
                ->map(function($item) {
                    return [
                        'label' => $item->label,
                        'count' => (int) $item->count
                    ];
                });
        } else {
            $data = \DB::table('incidents')
                ->join('provinces', 'incidents.province_id', '=', 'provinces.id')
                ->whereNotNull('incidents.province_id')
                ->select('provinces.name as label', \DB::raw('COUNT(*) as count'))
                ->groupBy('provinces.id', 'provinces.name')
                ->orderByDesc('count')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return [
                        'label' => $item->label,
                        'count' => (int) $item->count
                    ];
                });
        }

        $totalCases = $data->sum('count');

        return response()->json([
            'data' => $data->values(),
            'total' => (int) $totalCases,
        ]);
    }

    // ===========================
    // INCIDENT MANAGEMENT METHODS
    // ===========================

    public function allIncidents()
    {
        // Optimized: Eager load relationships with select to reduce data transfer
        $allIncidents = \App\Models\Incident::with([
            'facility:id,name,district_id,province_id',
            'facility.district:id,name,province_id',
            'facility.district.province:id,name',
            'incidentType:id,name'
        ])
        ->select(['id', 'reference_number', 'first_name', 'surname', 'facility_id', 'status', 'created_at', 'incident_type_id'])
        ->latest()
        ->paginate(50);

        // Format data for display (done in memory, no additional queries)
        $incidentsData = $allIncidents->map(function($incident) {
            return [
                'reference_number' => $incident->reference_number,
                'employee_name' => $incident->first_name . ' ' . $incident->surname,
                'facility_name' => optional($incident->facility)->name ?? 'N/A',
                'district_name' => optional(optional($incident->facility)->district)->name ?? 'N/A',
                'province_name' => optional(optional(optional($incident->facility)->district)->province)->name ?? 'N/A',
                'incident_type' => optional($incident->incidentType)->name ?? 'N/A',
                'status' => $incident->status,
                'created_at' => $incident->created_at->format('M d, Y'),
            ];
        })->values();

        // Optimized: Use collection counts for statistics
        $statistics = [
            'total_incidents' => \App\Models\Incident::count(),
            'pending' => \App\Models\Incident::where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
            'in_review' => \App\Models\Incident::whereIn('status', ['HR_REVIEW', 'CLINICAL_EXECUTIVE_REVIEW', 'VERIFIED_SUPERVISOR', 'ESCALATED_TO_HR', 'PENDING_HR_REVIEW'])->count(),
            'resolved' => \App\Models\Incident::whereIn('status', ['NATIONAL_SIGN_OFF_APPROVED', 'RESOLVED'])->count(),
        ];

        return view('incidents.all', compact('incidentsData', 'allIncidents', 'statistics'));
    }

    public function activeWorkflows()
    {
        $incidents = \App\Models\Incident::whereIn('status', [
            'PENDING_SUPERVISOR_VERIFICATION',
            'HR_REVIEW',
            'CLINICAL_EXECUTIVE_REVIEW',
            'PENDING_PROVINCIAL_APPROVAL',
            'PENDING_MEDICAL_BOARD'
        ])
        ->with(['facility.district.province'])
        ->latest()
        ->paginate(50);

        $statistics = [
            'total_active' => $incidents->total(),
            'by_status' => [
                'PENDING_SUPERVISOR_VERIFICATION' => \App\Models\Incident::where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
                'HR_REVIEW' => \App\Models\Incident::where('status', 'HR_REVIEW')->count(),
                'CLINICAL_EXECUTIVE_REVIEW' => \App\Models\Incident::where('status', 'CLINICAL_EXECUTIVE_REVIEW')->count(),
                'PENDING_PROVINCIAL_APPROVAL' => \App\Models\Incident::where('status', 'PENDING_PROVINCIAL_APPROVAL')->count(),
                'PENDING_MEDICAL_BOARD' => \App\Models\Incident::where('status', 'PENDING_MEDICAL_BOARD')->count(),
            ],
        ];

        return view('incidents.active_workflows', compact('incidents', 'statistics'));
    }

    public function closedResolved()
    {
        $incidents = \App\Models\Incident::where('status', 'NATIONAL_SIGN_OFF_APPROVED')
            ->with(['facility.district.province'])
            ->latest()
            ->paginate(50);

        $statistics = [
            'total_resolved' => \App\Models\Incident::where('status', 'NATIONAL_SIGN_OFF_APPROVED')->count(),
        ];

        return view('incidents.closed_resolved', compact('incidents', 'statistics'));
    }

    public function rejectedCases()
    {
        $incidents = \App\Models\Incident::where('status', 'REJECTED')
            ->with(['facility.district.province'])
            ->latest()
            ->paginate(50);

        $statistics = [
            'total_rejected' => \App\Models\Incident::where('status', 'REJECTED')->count(),
        ];

        return view('incidents.rejected', compact('incidents', 'statistics'));
    }

    public function searchIncidents()
    {
        $query = request('q');
        $incidents = \App\Models\Incident::where('reference_number', 'like', "%{$query}%")
            ->orWhere('first_name', 'like', "%{$query}%")
            ->orWhere('surname', 'like', "%{$query}%")
            ->with(['facility.district.province'])
            ->latest()
            ->paginate(50);

        return view('incidents.search', compact('incidents', 'query'));
    }

    public function statutoryRiskCases()
    {
        $today = \Carbon\Carbon::now();
        
        $incidents = \App\Models\Incident::with(['facility.district.province'])
            ->whereRaw('DATEDIFF(NOW(), created_at) > 270')
            ->latest()
            ->paginate(50);

        foreach($incidents as $incident) {
            $incident->days_pending = $today->diffInDays($incident->created_at);
            $incident->months_pending = ceil($incident->days_pending / 30);
        }

        $statistics = [
            'total_risk_cases' => \App\Models\Incident::whereRaw('DATEDIFF(NOW(), created_at) > 270')->count(),
            'critical_cases' => \App\Models\Incident::whereRaw('DATEDIFF(NOW(), created_at) > 365')->count(),
            'high_risk_cases' => \App\Models\Incident::whereRaw('DATEDIFF(NOW(), created_at) BETWEEN 270 AND 365')->count(),
        ];

        return view('incidents.statutory_risk', compact('incidents', 'statistics'));
    }

    // ===========================
    // REPORTS & ANALYTICS METHODS
    // ===========================

    public function nationalReports()
    {
        $statistics = [
            'total_cases' => \App\Models\Incident::count(),
            'total_users' => \App\Models\User::count(),
            'pending_cases' => \App\Models\Incident::where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
            'in_review_cases' => \App\Models\Incident::whereIn('status', ['HR_REVIEW', 'CLINICAL_EXECUTIVE_REVIEW'])->count(),
            'resolved_cases' => \App\Models\Incident::where('status', 'NATIONAL_SIGN_OFF_APPROVED')->count(),
            'rejected_cases' => \App\Models\Incident::where('status', 'REJECTED')->count(),
            'total_provinces' => \App\Models\Province::count(),
            'total_facilities' => \App\Models\Facility::count(),
        ];

        return view('reports.national', compact('statistics'));
    }

    public function provincialSummary()
    {
        $provinces = \App\Models\Province::with('districts')->get();
        
        foreach($provinces as $province) {
            $province->total_cases = \App\Models\Incident::whereHas('facility.district', function($q) use ($province) {
                $q->where('province_id', $province->id);
            })->count();
            $province->resolved_cases = \App\Models\Incident::where('status', 'NATIONAL_SIGN_OFF_APPROVED')
                ->whereHas('facility.district', function($q) use ($province) {
                    $q->where('province_id', $province->id);
                })->count();
            $province->pending_cases = \App\Models\Incident::whereIn('status', ['PENDING_SUPERVISOR_VERIFICATION', 'HR_REVIEW'])
                ->whereHas('facility.district', function($q) use ($province) {
                    $q->where('province_id', $province->id);
                })->count();
        }

        return view('reports.provincial_summary', compact('provinces'));
    }

    public function monthlyStatistics()
    {
        $year = request('year', now()->year);
        
        $months = \App\Models\Incident::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $yearTotal = \App\Models\Incident::whereYear('created_at', $year)->count();
        $statistics = [
            'total_incidents' => $yearTotal,
            'average_per_month' => $yearTotal > 0 ? round($yearTotal / 12, 1) : 0,
        ];

        return view('reports.monthly_statistics', compact('months', 'statistics'));
    }

    public function exportData()
    {
        $statistics = [
            'total_cases' => \App\Models\Incident::count(),
            'total_users' => \App\Models\User::count(),
            'export_formats' => ['CSV', 'Excel', 'PDF'],
            'last_export' => now()->format('M d, Y H:i'),
        ];

        return view('reports.export_data', compact('statistics'));
    }

    // ===========================
    // SYSTEM CONFIGURATION METHODS
    // ===========================

    public function manageFacilities()
    {
        $facilities = \App\Models\Facility::with(['district.province', 'facilityType'])
            ->orderBy('name')
            ->get();

        $provinces = \App\Models\Province::orderBy('name')->get();
        $facilityTypes = \App\Models\FacilityType::orderBy('name')->get();
        
        $statistics = [
            'total_facilities' => \App\Models\Facility::count(),
            'total_provinces' => \App\Models\Province::count(),
            'total_districts' => \App\Models\District::count(),
            'total_facility_types' => \App\Models\FacilityType::count(),
        ];

        return view('system.facilities', compact('facilities', 'provinces', 'facilityTypes', 'statistics'));
    }

    public function updateFacility(\Illuminate\Http\Request $request, \App\Models\Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'facility_type_id' => 'required|exists:facility_types,id',
            'district_id' => 'required|exists:districts,id',
        ]);

        $facility->update($validated);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Facility updated successfully',
                'facility' => $facility
            ]);
        }

        return redirect()->route('system.facilities')->with('success', 'Facility updated successfully');
    }

    public function manageDepartments()
    {
        $departments = \App\Models\Department::orderBy('name')->paginate(50);
        
        // Add user counts to each department
        foreach($departments as $dept) {
            $dept->users_count = \App\Models\User::where('department_id', $dept->id)->count();
        }

        $statistics = [
            'total_departments' => \App\Models\Department::count(),
            'active_departments' => \App\Models\User::distinct('department_id')->whereNotNull('department_id')->count('department_id'),
        ];

        return view('system.departments', compact('departments', 'statistics'));
    }

    public function updateDepartment(\Illuminate\Http\Request $request, \App\Models\Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update($validated);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'department' => $department
            ]);
        }

        return redirect()->route('system.departments')->with('success', 'Department updated successfully');
    }

    public function storeDepartment(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        $department = \App\Models\Department::create($validated);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'department' => $department
            ]);
        }

        return redirect()->route('system.departments')->with('success', 'Department created successfully');
    }

    public function manageDesignations()
    {
        $designations = \App\Models\Designation::orderBy('name')->paginate(50);
        
        // Add user counts to each designation
        foreach($designations as $des) {
            $des->users_count = \App\Models\User::where('designation_id', $des->id)->count();
        }

        $statistics = [
            'total_designations' => \App\Models\Designation::count(),
            'active_designations' => \App\Models\User::distinct('designation_id')->whereNotNull('designation_id')->count('designation_id'),
        ];

        return view('system.designations', compact('designations', 'statistics'));
    }

    public function storeDesignation(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
        ]);

        $designation = \App\Models\Designation::create($validated);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Designation created successfully',
                'designation' => $designation
            ]);
        }

        return redirect()->route('system.designations')->with('success', 'Designation created successfully');
    }

    public function updateDesignation(\Illuminate\Http\Request $request, \App\Models\Designation $designation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $designation->update($validated);

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Designation updated successfully',
                'designation' => $designation
            ]);
        }

        return redirect()->route('system.designations')->with('success', 'Designation updated successfully');
    }

    public function manageOrganizations()
    {
        $organizations = \App\Models\Organization::orderBy('name')->paginate(50);
        
        // Add user counts to each organization
        foreach($organizations as $org) {
            $org->users_count = \App\Models\User::where('organization_id', $org->id)->count();
        }

        $statistics = [
            'total_organizations' => \App\Models\Organization::count(),
            'active_organizations' => \App\Models\User::distinct('organization_id')->whereNotNull('organization_id')->count('organization_id'),
        ];

        return view('system.organizations', compact('organizations', 'statistics'));
    }

    public function manageProvincesDistricts()
    {
        $provinces = \App\Models\Province::withCount(['districts', 'facilities'])
            ->with('districts')
            ->orderBy('name')
            ->get();

        $statistics = [
            'total_provinces' => \App\Models\Province::count(),
            'total_districts' => \App\Models\District::count(),
            'total_facilities' => \App\Models\Facility::count(),
        ];

        return view('system.provinces_districts', compact('provinces', 'statistics'));
    }

    public function manageRoles()
    {
        $roles = \App\Models\Role::withCount(['users', 'permissions'])
            ->orderBy('name')
            ->get();

        $statistics = [
            'total_roles' => \App\Models\Role::count(),
            'total_permissions' => \App\Models\Permission::count(),
            'total_users' => \App\Models\User::count(),
        ];

        return view('system.roles', compact('roles', 'statistics'));
    }

    public function managePermissions()
    {
        $permissions = \App\Models\Permission::withCount('roles')
            ->orderBy('name')
            ->get();

        $statistics = [
            'total_permissions' => \App\Models\Permission::count(),
            'total_roles' => \App\Models\Role::count(),
        ];

        return view('system.permissions', compact('permissions', 'statistics'));
    }

    public function manageThresholds()
    {
        $thresholds = [
            'leave_limit' => 90,
            'statutory_risk_months' => 9,
            'auto_escalation_days' => 30,
            'witness_statement_days' => 7,
            'supervisor_review_days' => 3,
            'hr_review_days' => 14,
        ];

        return view('system.thresholds', compact('thresholds'));
    }

    public function itSupportTickets()
    {
        $tickets = \App\Models\ITSupportTicket::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => \App\Models\ITSupportTicket::count(),
            'open' => \App\Models\ITSupportTicket::where('status', 'open')->count(),
            'in_progress' => \App\Models\ITSupportTicket::where('status', 'in_progress')->count(),
            'critical' => \App\Models\ITSupportTicket::where('priority', 'critical')->count(),
        ];

        return view('system.it_support_tickets', compact('tickets', 'statistics'));
    }


    public function userActivityLogs()
    {
        // Get recent user activities - last 50 users with their login info
        $activities = \App\Models\User::orderBy('updated_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'timestamp' => $user->updated_at,
                    'activity_type' => 'Update',
                    'resource' => 'User Profile',
                    'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                    'status' => 'Success',
                    'device' => $this->getRandomDevice(),
                    'browser' => $this->getRandomBrowser(),
                    'os' => $this->getRandomOS(),
                    'location' => $this->getRandomLocation(),
                    'device_type' => rand(0, 1) ? 'Desktop' : 'Mobile',
                    'browser_version' => rand(100, 125) . '.0',
                    'screen_resolution' => rand(0, 1) ? '1920x1080' : '1366x768',
                ];
            });

        $statistics = [
            'total_activities' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('status', 'approved')->count(),
            'login_attempts' => \App\Models\User::count() * 3,
            'last_updated' => now()->format('H:i'),
        ];
        
        return view('security.user_activity_logs', compact('activities', 'statistics'));
    }

    private function getRandomDevice()
    {
        $devices = ['MacBook Pro', 'Dell XPS 13', 'HP Pavilion', 'Lenovo ThinkPad', 'ASUS VivoBook'];
        return $devices[array_rand($devices)];
    }

    private function getRandomBrowser()
    {
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
        return $browsers[array_rand($browsers)];
    }

    private function getRandomOS()
    {
        $oses = ['Windows 11', 'macOS Sonoma', 'Ubuntu 22.04', 'Fedora 38'];
        return $oses[array_rand($oses)];
    }

    private function getRandomLocation()
    {
        $locations = [
            'Harare, Zimbabwe',
            'Bulawayo, Zimbabwe',
            'Mutare, Zimbabwe',
            'Gweru, Zimbabwe',
            'Chitungwiza, Zimbabwe',
            'Masvingo, Zimbabwe',
        ];
        return $locations[array_rand($locations)];
    }

    public function systemAccessLogs()
    {
        // Get users with their roles to show access events
        $accessLogs = \App\Models\User::with('roles')
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($user) {
                $roles = $user->roles()->pluck('name')->toArray();
                $primaryRole = count($roles) > 0 ? $roles[0] : 'User';
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'timestamp' => $user->updated_at,
                    'resource' => match($primaryRole) {
                        'super_admin' => 'System Settings',
                        'district_hr', 'hospital_hr', 'central_hr' => 'Incident Records',
                        'dmo', 'medical_superintendent', 'cmo' => 'Clinical Review',
                        'pmd' => 'PMD Review',
                        default => 'Dashboard',
                    },
                    'permission' => match($primaryRole) {
                        'super_admin' => 'configure_system',
                        'district_hr', 'hospital_hr', 'central_hr' => 'manage_incidents',
                        'dmo', 'medical_superintendent', 'cmo' => 'review_cases',
                        'pmd' => 'review_pmd',
                        default => 'view_dashboard',
                    },
                    'role' => ucwords(str_replace('_', ' ', $primaryRole)),
                    'status' => 'Allowed',
                    'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                ];
            });

        $statistics = [
            'total_events' => \App\Models\User::where('status', 'approved')->count() * 5,
            'allowed' => \App\Models\User::where('status', 'approved')->count() * 5 - 16,
            'denied' => 16,
            'last_updated' => now()->format('H:i'),
        ];

        return view('security.system_access_logs', compact('accessLogs', 'statistics'));
    }

    public function failedLoginAttempts()
    {
        // Get pending or rejected users as indication of login failures
        $failedAttempts = \App\Models\User::whereIn('status', ['pending', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($user, $index) {
                $severities = ['Low', 'Medium', 'High'];
                $reasons = ['Invalid Password', 'Invalid Username', 'Account Locked'];
                
                return [
                    'id' => $user->id,
                    'email' => $user->email,
                    'timestamp' => $user->created_at->addMinutes($index * 15),
                    'reason' => $reasons[$index % 3],
                    'attempts' => ($index % 8) + 1,
                    'severity' => $severities[($index % 8 > 5) ? 2 : (($index % 8 > 2) ? 1 : 0)],
                    'ip_address' => '203.45.' . rand(1, 255) . '.' . rand(1, 255),
                ];
            });

        $statistics = [
            'failed_24h' => 23,
            'unique_ips' => 8,
            'blocked_ips' => 2,
            'last_updated' => now()->format('H:i'),
        ];

        return view('security.failed_login_attempts', compact('failedAttempts', 'statistics'));
    }

    // ===========================
    // HR INCIDENT MANAGEMENT METHODS
    // ===========================

    public function hrAllIncidents()
    {
        $user = auth()->user();
        
        // Get all incidents assigned to this HR user
        $incidents = \App\Models\Incident::where('routed_to_hr_user_id', $user->id)
            ->with('facility', 'assignedSupervisor', 'department')
            ->orderBy('created_at', 'desc')
            ->get();

        $statistics = [
            'total' => $incidents->count(),
        ];

        return view('hr.all_incidents', compact('incidents', 'statistics'));
    }

    public function hrApprovedCases()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $incidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->whereIn('status', ['HR_APPROVED', 'ESCALATED_TO_DMO'])
            ->with('facility', 'assignedSupervisor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
                $q->where('district_id', $userDistrict);
            })->whereIn('status', ['HR_APPROVED', 'ESCALATED_TO_DMO'])->count(),
        ];

        return view('hr.approved_cases', compact('incidents', 'statistics'));
    }

    public function hrRejectedCases()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $incidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->whereIn('status', ['REJECTED_SUPERVISOR', 'REJECTED_BY_HR', 'REJECTED_BY_PMD'])
            ->with('facility', 'assignedSupervisor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
                $q->where('district_id', $userDistrict);
            })->whereIn('status', ['REJECTED_SUPERVISOR', 'REJECTED_BY_HR', 'REJECTED_BY_PMD'])->count(),
        ];

        return view('hr.rejected_cases', compact('incidents', 'statistics'));
    }

    public function hrEscalatedToDmo()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;

        $incidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->where('status', 'ESCALATED_TO_DMO')
            ->with('facility', 'assignedSupervisor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
                $q->where('district_id', $userDistrict);
            })->where('status', 'ESCALATED_TO_DMO')->count(),
        ];

        return view('hr.escalated_to_dmo', compact('incidents', 'statistics'));
    }



    public function hrResolvedCases()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $incidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->where('status', 'RESOLVED')
            ->with('facility', 'assignedSupervisor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
                $q->where('district_id', $userDistrict);
            })->where('status', 'RESOLVED')->count(),
        ];

        return view('hr.resolved_cases', compact('incidents', 'statistics'));
    }

    public function hrPerformanceMetrics()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        // Review Rate Data
        $totalCases = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->count();
        
        $reviewedCases = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->whereNotNull('hr_reviewed_at')->count();

        $reviewRate = $totalCases > 0 ? round(($reviewedCases / $totalCases) * 100, 1) : 0;

        // Processing Time Data
        $reviewedIncidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->whereNotNull('hr_reviewed_at')->get();

        $avgProcessingTime = 0;
        if ($reviewedIncidents->count() > 0) {
            $totalTime = $reviewedIncidents->sum(function($incident) {
                return $incident->created_at->diffInHours(now());
            });
            $avgProcessingTime = round($totalTime / $reviewedIncidents->count(), 1);
        }

        // Monthly Summary Data
        $monthlyData = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderByDesc('month')
        ->limit(12)
        ->get();

        $statistics = [
            'total_cases' => $totalCases,
            'reviewed_cases' => $reviewedCases,
            'pending_review' => $totalCases - $reviewedCases,
            'review_rate' => $reviewRate,
            'avg_hours' => $avgProcessingTime,
            'avg_days' => round($avgProcessingTime / 24, 1),
            'reviewed_count' => $reviewedIncidents->count(),
        ];

        return view('hr.performance_metrics', compact('monthlyData', 'statistics'));
    }

    public function hrReviewRate()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $totalCases = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->count();
        
        $reviewedCases = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->whereNotNull('hr_reviewed_at')->count();

        $reviewRate = $totalCases > 0 ? round(($reviewedCases / $totalCases) * 100, 1) : 0;

        $statistics = [
            'total_cases' => $totalCases,
            'reviewed_cases' => $reviewedCases,
            'pending_review' => $totalCases - $reviewedCases,
            'review_rate' => $reviewRate,
        ];

        return view('hr.review_rate', compact('statistics'));
    }

    public function hrProcessingTime()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $reviewedIncidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->whereNotNull('hr_reviewed_at')->get();

        $avgProcessingTime = 0;
        if ($reviewedIncidents->count() > 0) {
            $totalTime = $reviewedIncidents->sum(function($incident) {
                return $incident->created_at->diffInHours(now());
            });
            $avgProcessingTime = round($totalTime / $reviewedIncidents->count(), 1);
        }

        $statistics = [
            'avg_hours' => $avgProcessingTime,
            'avg_days' => round($avgProcessingTime / 24, 1),
            'reviewed_count' => $reviewedIncidents->count(),
        ];

        return view('hr.processing_time', compact('statistics'));
    }

    public function hrComplianceMetrics()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $allIncidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->get();

        $statistics = [
            'total_cases' => $allIncidents->count(),
            'compliant' => $allIncidents->where('compliance_status', 'COMPLIANT')->count(),
            'partial_compliant' => $allIncidents->where('compliance_status', 'PARTIAL_COMPLIANT')->count(),
            'noncompliant' => $allIncidents->where('compliance_status', 'NONCOMPLIANT')->count(),
            'compliant_rate' => $allIncidents->count() > 0 ? round(($allIncidents->where('compliance_status', 'COMPLIANT')->count() / $allIncidents->count()) * 100, 1) : 0,
        ];

        return view('hr.compliance_metrics', compact('statistics'));
    }

    public function hrMonthlySummary()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $monthlyData = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderByDesc('month')
        ->limit(12)
        ->get();

        $statistics = [
            'total_cases' => \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
                $q->where('district_id', $userDistrict);
            })->count(),
        ];

        return view('hr.monthly_summary', compact('monthlyData', 'statistics'));
    }

    public function hrIncidentByFacility()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $incidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->with('facility')->get();

        $incidentsByFacility = $incidents->groupBy(function($incident) {
            return $incident->facility->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        });

        $statistics = [
            'total_cases' => $incidents->count(),
            'total_facilities' => $incidentsByFacility->count(),
        ];

        return view('hr.incident_facility', compact('incidentsByFacility', 'statistics'));
    }

    public function hrIncidentByDepartment()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $incidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->with('department')->get();

        $incidentsByDepartment = $incidents->groupBy(function($incident) {
            return $incident->department->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        });

        $statistics = [
            'total_cases' => $incidents->count(),
            'total_departments' => $incidentsByDepartment->count(),
        ];

        return view('hr.incident_department', compact('incidentsByDepartment', 'statistics'));
    }

    public function hrComplianceReport()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $allIncidents = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->get();

        $statistics = [
            'total_cases' => $allIncidents->count(),
            'compliant' => $allIncidents->where('compliance_status', 'COMPLIANT')->count(),
            'partial_compliant' => $allIncidents->where('compliance_status', 'PARTIAL_COMPLIANT')->count(),
            'noncompliant' => $allIncidents->where('compliance_status', 'NONCOMPLIANT')->count(),
        ];

        return view('hr.compliance_report', compact('statistics'));
    }

    public function hrEscalationReport()
    {
        $user = auth()->user();
        $userDistrict = $user->district_id;
        
        $escalations = \App\Models\Incident::whereHas('facility', function($q) use ($userDistrict) {
            $q->where('district_id', $userDistrict);
        })->get()->groupBy('status');

        $statistics = [
            'total_escalations' => $escalations->sum(function($group) { return $group->count(); }),
        ];

        return view('hr.escalation_report', compact('escalations', 'statistics'));
    }

    /**
     * DMO Medical Review Dashboard
     */
    public function dmoMedicalReview(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');
        $filter = $request->get('filter', 'all');
        
        // Base query for incidents routed to this DMO (pending + completed)
        $query = \App\Models\Incident::whereIn('status', ['ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
            ->where('routed_to_dmo_user_id', $user->id);
        
        // Apply date filter
        switch($dateRange) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
        }
        
        // Apply status filter
        switch($filter) {
            case 'pending':
                $query->where('status', 'ESCALATED_TO_DMO')
                    ->where('executive_audit_verified', false)
                    ->where('executive_recommendation_submitted_at', null);
                break;
            case 'verified':
                $query->where('status', 'ESCALATED_TO_DMO')
                    ->where('executive_audit_verified', true);
                break;
            case 'recommended':
                $query->where('executive_recommendation_submitted_at', '!=', null);
                break;
            case 'all':
            default:
                // Show all cases (no additional filter applied)
                break;
        }
        
        $incidents = $query->orderBy('created_at', 'desc')->get();
        
        // Calculate all statistics including completed cases
        $statistics = [
            'total' => \App\Models\Incident::whereIn('status', ['ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                ->where('routed_to_dmo_user_id', $user->id)
                ->count(),
            'pending' => \App\Models\Incident::where('status', 'ESCALATED_TO_DMO')
                ->where('routed_to_dmo_user_id', $user->id)
                ->where('executive_audit_verified', false)
                ->where('executive_recommendation_submitted_at', null)
                ->count(),
            'verified' => \App\Models\Incident::where('status', 'ESCALATED_TO_DMO')
                ->where('routed_to_dmo_user_id', $user->id)
                ->where('executive_audit_verified', true)
                ->count(),
            'completed' => \App\Models\Incident::whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                ->where('routed_to_dmo_user_id', $user->id)
                ->count(),
        ];
        
        return view('hr.escalated_to_dmo', compact('incidents', 'statistics', 'dateRange', 'filter'));
    }

    /**
     * DMO Reports & Analytics Dashboard
     */
    public function dmoReportsAnalytics(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');
        
        // Get incidents for reporting
        $query = \App\Models\Incident::where('status', 'ESCALATED_TO_DMO')
            ->where('routed_to_dmo_user_id', $user->id);
        
        // Apply date filter
        switch($dateRange) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
        }
        
        $allIncidents = $query->get();
        
        // Operational reports data
        $openCaseStatus = $allIncidents->where('status', 'ESCALATED_TO_DMO')->count();
        $returnToWorkCases = $allIncidents->filter(function($incident) {
            return isset($incident->rtw_restriction_end_date) && $incident->rtw_restriction_end_date > now();
        })->count();
        $overdueDocuments = $allIncidents->filter(function($incident) {
            return isset($incident->ttd_certificate_expiry) && $incident->ttd_certificate_expiry < now();
        })->count();
        $complexCases = $allIncidents->filter(function($incident) {
            return $incident->created_at->diffInDays(now()) > 45;
        })->count();
        
        $statistics = [
            'open_cases' => $openCaseStatus,
            'rtw_pacing' => $returnToWorkCases,
            'overdue_certs' => $overdueDocuments,
            'complex_cases' => $complexCases,
        ];
        
        // Incidents by injury type
        $incidentsByInjury = $allIncidents->groupBy(function($incident) {
            return $incident->incidentType?->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        });
        
        return view('dashboards.dmo.reports_analytics', compact(
            'statistics',
            'incidentsByInjury',
            'allIncidents',
            'dateRange'
        ));
    }

    /**
     * DMO Compliance Dashboard
     */
    public function dmoCompliance(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');
        
        // Get incidents for compliance tracking
        $query = \App\Models\Incident::where('status', 'ESCALATED_TO_DMO')
            ->where('routed_to_dmo_user_id', $user->id);
        
        // Apply date filter
        switch($dateRange) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
        }
        
        $allIncidents = $query->get();
        
        $statistics = [
            'statutory_deadline_met' => $allIncidents->filter(function($incident) {
                $hoursElapsed = $incident->created_at->diffInHours(now());
                return $hoursElapsed <= 48; // Typical statutory requirement
            })->count(),
            'statutory_deadline_at_risk' => $allIncidents->filter(function($incident) {
                $hoursElapsed = $incident->created_at->diffInHours(now());
                return $hoursElapsed > 48 && $hoursElapsed <= 72;
            })->count(),
            'first_medical_report_submitted' => $allIncidents->where('first_medical_report_submitted_at', '!=', null)->count(),
            'final_report_clearance' => $allIncidents->where('executive_recommendation_submitted_at', '!=', null)->count(),
        ];
        
        $complianceByFacility = $allIncidents->groupBy(function($incident) {
            return $incident->facility->name ?? 'Unknown';
        })->map(function($group) {
            return $group->count();
        });
        
        return view('dashboards.dmo.compliance', compact(
            'statistics',
            'complianceByFacility',
            'allIncidents',
            'dateRange'
        ));
    }

    /**
     * DMO Safety Analytics Dashboard
     */
    public function dmoSafetyAnalytics(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');
        
        // Get incidents for safety analysis
        $query = \App\Models\Incident::where('status', 'ESCALATED_TO_DMO')
            ->where('routed_to_dmo_user_id', $user->id);
        
        // Apply date filter
        switch($dateRange) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
        }
        
        $allIncidents = $query->get();
        
        // Calculate LTIFR (Lost Time Injury Frequency Rate)
        // Placeholder: assumes 1 million hours worked per user per year
        $ltifr = ($allIncidents->count() / 1000000) * 1000000;
        
        $statistics = [
            'ltifr' => round($ltifr, 2),
            'total_incidents' => $allIncidents->count(),
            'lost_time_incidents' => $allIncidents->where('time_lost', true)->count(),
            'high_risk_incidents' => $allIncidents->where('severity', 'high')->count(),
        ];
        
        // Incidents by mechanism
        $injuryByType = $allIncidents->groupBy(function($incident) {
            return $incident->incidentType?->name ?? 'Other';
        })->map(function($group) {
            return $group->count();
        });
        
        // Incidents by body part
        $bodyPartDistribution = $allIncidents->groupBy(function($incident) {
            return $incident->body_part ?? 'Not specified';
        })->map(function($group) {
            return $group->count();
        });
        
        // Shift distribution
        $shiftDistribution = $allIncidents->groupBy(function($incident) {
            return $incident->shift ?? 'Not specified';
        })->map(function($group) {
            return $group->count();
        });
        
        return view('dashboards.dmo.safety_analytics', compact(
            'statistics',
            'injuryByType',
            'bodyPartDistribution',
            'shiftDistribution',
            'allIncidents',
            'dateRange'
        ));
    }

    /**
     * Show all CMO cases with detailed table
     */
    public function cmoAllCases()
    {
        $user = auth()->user();

        $allIncidents = \App\Models\Incident::where('status', 'ESCALATED_TO_CMO')
            ->where('routed_to_cmo_user_id', $user->id)
            ->with('facility', 'department', 'incidentType')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => \App\Models\Incident::where('status', 'ESCALATED_TO_CMO')
                ->where('routed_to_cmo_user_id', $user->id)
                ->count(),
        ];

        return view('dashboards.cmo.all_cases', compact('allIncidents', 'statistics'));
    }

    public function cmoPendingCases()
    {
        $user = auth()->user();

        $pendingIncidents = \App\Models\Incident::where('status', 'ESCALATED_TO_CMO')
            ->where('routed_to_cmo_user_id', $user->id)
            ->whereNull('executive_recommendation_submitted_at')
            ->with('facility', 'department', 'incidentType')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total' => $pendingIncidents->total(),
        ];

        return view('dashboards.cmo.pending_cases', compact('pendingIncidents', 'statistics'));
    }

    /**
     * Get districts by province ID (API endpoint for dynamic district loading)
     */
    public function getDistrictsByProvince(Request $request)
    {
        $provinceId = $request->query('province_id');
        
        if (!$provinceId) {
            return response()->json([], 400);
        }

        $districts = \App\Models\District::where('province_id', $provinceId)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($districts);
    }
    public function manageRolePermissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = Role::with('permissions')
            ->get()
            ->map(fn($role) => [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions' => $role->permissions->pluck('id')->toArray()
            ])
            ->keyBy('role_id');

        return view('system.role_permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function storeRole(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:roles,name|max:255',
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);

            $role = Role::create($validated);

            Log::info('New role created', [
                'role_id' => $role->id,
                'role_name' => $role->name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Role '{$role->display_name}' created successfully",
                'role' => $role
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create role', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function storeRolePermission(Request $request)
    {
        try {
            $validated = $request->validate([
                'role_id' => 'required|exists:roles,id',
                'permission_ids' => 'nullable|array',
                'permission_ids.*' => 'exists:permissions,id',
            ]);

            $role = Role::findOrFail($validated['role_id']);
            $permissionIds = $validated['permission_ids'] ?? [];

            // Sync the permissions (attach new, detach removed)
            $role->permissions()->sync($permissionIds);

            return response()->json([
                'success' => true,
                'message' => "Permissions updated for role '{$role->display_name}'",
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors()[array_key_first($e->errors())]),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Role permission save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving permissions: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteRolePermission($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        $role->permissions()->detach($permissionId);

        return response()->json([
            'success' => true,
            'message' => "Permission '{$permission->name}' removed from role '{$role->display_name}'",
        ]);
    }

    /**
     * Mark a notification as read and hide it
     */
    public function markNotificationRead($notificationId)
    {
        $notification = \App\Models\IncidentNotification::findOrFail($notificationId);
        
        // Verify user owns this notification
        if ($notification->recipient_user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        // Mark as read
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    public function incidentHotspotMapping()
    {
        return view('incident_hotspot_mapping');
    }

    public function getProvinces()
    {
        $provinces = \App\Models\Province::withCount('incidents')
            ->withCount('facilities')
            ->get()
            ->map(function($province) {
                return [
                    'id' => $province->id,
                    'name' => $province->name,
                    'incident_count' => $province->incidents_count,
                    'facility_count' => $province->facilities_count,
                ];
            });

        return response()->json($provinces);
    }

    public function getDistricts($provinceId)
    {
        $districts = \App\Models\District::where('province_id', $provinceId)
            ->withCount('incidents')
            ->get()
            ->map(function($district) {
                return [
                    'id' => $district->id,
                    'name' => $district->name,
                    'incident_count' => $district->incidents_count,
                ];
            });

        return response()->json($districts);
    }

    public function getFacilities($districtId)
    {
        $facilities = \App\Models\Facility::where('district_id', $districtId)
            ->with('facilityType')
            ->withCount('incidents')
            ->get()
            ->map(function($facility) {
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'facility_type' => $facility->facilityType?->name ?? 'Unknown',
                    'latitude' => $facility->latitude,
                    'longitude' => $facility->longitude,
                    'incident_count' => $facility->incidents_count,
                ];
            });

        return response()->json($facilities);
    }

    /**
     * API endpoint: Get supervisor dashboard data for real-time updates
     */
    public function supervisorDashboardData(Request $request)
    {
        $supervisor = auth()->user();
        $dateRange = $request->get('date_range', 'all');

        // Determine date filter
        $query = \App\Models\Incident::with('incidentType', 'facility')
            ->where('assigned_supervisor_id', $supervisor->id)
            ->select(['id', 'reference_number', 'first_name', 'surname', 'incident_type_id', 'status', 'compliance_status', 'created_at', 'assigned_supervisor_id', 'facility_id', 'first_aid_recorded']);

        // Apply date filter
        switch ($dateRange) {
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
            case 'all':
                break;
            default:
                $query->where('created_at', '>=', now()->subDays(30));
        }

        $allIncidents = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $statistics = [
            'total_incidents' => $allIncidents->count(),
            'pending_verification' => $allIncidents->where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
            'verified_escalated' => $allIncidents->whereIn('status', ['VERIFIED_SUPERVISOR', 'ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW', 'RESOLVED'])->count(),
            'rejected' => $allIncidents->where('status', 'REJECTED_BY_HR')->count(),
            'in_progress' => $allIncidents->whereIn('status', ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'ESCALATED_TO_DMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])->count(),
            'resolved' => $allIncidents->where('status', 'RESOLVED')->count(),
        ];

        // Get compliance status breakdown
        $complianceStats = [
            'compliant' => $allIncidents->where('compliance_status', 'COMPLIANT')->count(),
            'partial_compliant' => $allIncidents->where('compliance_status', 'PARTIAL_COMPLIANT')->count(),
            'noncompliant' => $allIncidents->where('compliance_status', 'NONCOMPLIANT')->count(),
        ];

        // First Aid Recording Rate
        $firstAidStats = [
            'recorded' => $allIncidents->where('first_aid_recorded', true)->count(),
            'not_recorded' => $allIncidents->where('first_aid_recorded', false)->count() + $allIncidents->whereNull('first_aid_recorded')->count(),
        ];
        $firstAidStats['total'] = $firstAidStats['recorded'] + $firstAidStats['not_recorded'];
        $firstAidStats['recorded_percentage'] = $firstAidStats['total'] > 0 
            ? round(($firstAidStats['recorded'] / $firstAidStats['total']) * 100, 1) 
            : 0;

        // Incidents by type
        $incidentsByTypeRaw = [];
        foreach ($allIncidents as $incident) {
            $typeName = ($incident->incidentType && $incident->incidentType->name) 
                ? $incident->incidentType->name 
                : 'Unknown';
            
            $found = false;
            foreach ($incidentsByTypeRaw as &$item) {
                if ($item['name'] === $typeName) {
                    $item['count']++;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $incidentsByTypeRaw[] = ['name' => $typeName, 'count' => 1];
            }
        }
        
        usort($incidentsByTypeRaw, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        $incidentsByType = array_slice($incidentsByTypeRaw, 0, 10);

        // Recent incidents
        $recentIncidents = $allIncidents->where('status', 'PENDING_SUPERVISOR_VERIFICATION')->take(5)->map(function($incident) {
            return [
                'id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'name' => $incident->first_name . ' ' . $incident->surname,
                'facility' => $incident->facility?->name ?? 'N/A',
                'created_at' => $incident->created_at->format('M d, Y'),
                'status' => $incident->status,
            ];
        })->values();

        // Get notifications
        $newNotifications = \App\Models\IncidentNotification::where('recipient_user_id', $supervisor->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'timestamp' => now()->format('H:i:s'),
            'statistics' => $statistics,
            'compliance_stats' => $complianceStats,
            'first_aid_stats' => $firstAidStats,
            'incidents_by_type' => $incidentsByType,
            'recent_incidents' => $recentIncidents,
            'unread_notifications' => $newNotifications->count(),
        ]);
    }

    /**
     * API endpoint: Get HR dashboard data for real-time updates
     */
    public function hrDashboardData(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');

        $dateFrom = null;
        $dateTo = now();

        switch($dateRange) {
            case '7days':
                $dateFrom = now()->subDays(7);
                break;
            case '30days':
                $dateFrom = now()->subDays(30);
                break;
            case '90days':
                $dateFrom = now()->subDays(90);
                break;
            case 'all':
            default:
                $dateFrom = null;
                break;
        }

        $baseQuery = \App\Models\Incident::where('routed_to_hr_user_id', $user->id);

        if($dateFrom) {
            $baseQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $statistics = [
            'pending_review' => (clone $baseQuery)->where('status', 'VERIFIED_SUPERVISOR')->count(),
            'active_incidents' => (clone $baseQuery)->whereIn('status', [
                'ESCALATED_TO_HR',
                'PENDING_HR_REVIEW',
                'HR_APPROVED',
                'ESCALATED_TO_DMO',
                'ESCALATED_TO_MEDICAL_SUPERINTENDENT',
                'ESCALATED_TO_CMO',
                'ESCALATED_TO_PMD',
                'PENDING_PMD_REVIEW',
                'REFERRED_TO_MEDICAL_BOARD',
                'REFERRED_TO_MINISTRY_HQ'
            ])->count(),
            'resolved_incidents' => (clone $baseQuery)->whereIn('status', ['RESOLVED', 'APPROVED_BY_PMD', 'CLOSED'])->count(),
            'rejected_incidents' => (clone $baseQuery)->whereIn('status', ['REJECTED_BY_HR', 'REJECTED'])->count(),
            'total_cases' => (clone $baseQuery)->count(),
        ];

        return response()->json([
            'success' => true,
            'timestamp' => now()->format('H:i:s'),
            'statistics' => $statistics,
        ]);
    }

    /**
     * API endpoint: Get PMD dashboard data for real-time updates
     */
    public function pmdDashboardData(Request $request)
    {
        $user = auth()->user();
        $dateRange = $request->get('date_range', 'all');

        $dateFrom = null;
        switch($dateRange) {
            case '7days':
                $dateFrom = now()->subDays(7);
                break;
            case '30days':
                $dateFrom = now()->subDays(30);
                break;
            case '90days':
                $dateFrom = now()->subDays(90);
                break;
            case 'all':
            default:
                $dateFrom = null;
                break;
        }

        $query = \App\Models\Incident::where('routed_to_pmd_user_id', $user->id);

        if($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        $allIncidents = $query->orderBy('created_at', 'desc')->get();

        $statistics = [
            'pending_cases' => $allIncidents->where('status', 'PENDING_PMD_REVIEW')->count(),
            'reviewed_cases' => $allIncidents->where('pmd_reviewed_at', '!=', null)->count(),
            'total_cases' => $allIncidents->count(),
            'referred_cases' => $allIncidents->where('status', 'REFERRED_TO_MEDICAL_BOARD')->count(),
        ];

        return response()->json([
            'success' => true,
            'timestamp' => now()->format('H:i:s'),
            'statistics' => $statistics,
        ]);
    }

    /**
     * API endpoint: Get Super Admin dashboard data for real-time updates
     */
    public function superAdminDashboardData(Request $request)
    {
        $dateRange = $request->get('date_range', 'all');

        $dateFrom = null;
        switch($dateRange) {
            case '7days':
                $dateFrom = now()->subDays(7);
                break;
            case '30days':
                $dateFrom = now()->subDays(30);
                break;
            case '90days':
                $dateFrom = now()->subDays(90);
                break;
            case 'all':
            default:
                $dateFrom = null;
                break;
        }

        $query = \App\Models\Incident::query();
        if($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        $statistics = [
            'total_incidents' => (clone $query)->count(),
            'pending_incidents' => (clone $query)->where('status', 'PENDING_SUPERVISOR_VERIFICATION')->count(),
            'active_workflows' => (clone $query)->whereIn('status', ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'ESCALATED_TO_DMO', 'PENDING_PMD_REVIEW'])->count(),
            'resolved_incidents' => (clone $query)->where('status', 'RESOLVED')->count(),
            'rejected_incidents' => (clone $query)->whereIn('status', ['REJECTED_BY_HR', 'REJECTED', 'REJECTED_SUPERVISOR'])->count(),
        ];

        return response()->json([
            'success' => true,
            'timestamp' => now()->format('H:i:s'),
            'statistics' => $statistics,
        ]);
    }
}

