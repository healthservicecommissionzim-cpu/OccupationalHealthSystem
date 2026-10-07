<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class LeaveManagementDashboardController extends Controller
{
    /**
     * Display the leave management dashboard.
     */
    public function index(Request $request)
    {
        $query = Incident::whereNotNull('sick_leave_start_date');
        
        // Apply facility filter
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }
        
        // Apply district filter
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        
        // Apply province filter
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference_number', 'like', "%{$searchTerm}%")
                  ->orWhere('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('surname', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get paginated results
        $incidents = $query->with(['facility', 'district', 'leaveMilestones'])
            ->orderBy('sick_leave_start_date', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total_active' => Incident::whereNotNull('sick_leave_start_date')->where('leave_status', 'ACTIVE')->count(),
            'full_pay_count' => Incident::where('leave_payment_stage', 'FULL_PAY')->whereNotNull('sick_leave_start_date')->count(),
            'half_pay_count' => Incident::where('leave_payment_stage', 'HALF_PAY')->count(),
            'discharge_eligible_count' => Incident::where('status', 'MEDICAL_DISCHARGE_ELIGIBLE')->count(),
            'approaching_90' => Incident::whereNotNull('sick_leave_start_date')
                ->where('leave_status', 'ACTIVE')
                ->whereDoesntHave('leaveMilestones', fn($q) => $q->where('milestone_type', '90_DAY'))
                ->get()
                ->filter(fn($i) => $i->days_elapsed >= 85 && $i->days_elapsed < 90)
                ->count(),
        ];
        
        return view('leave.dashboard', compact('incidents', 'stats'));
    }
}
