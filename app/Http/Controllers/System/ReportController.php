<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\Roster\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

/**
 * ReportController
 * 
 * Handles report generation for the Multi-Facility Roster Mapping system.
 * Provides cost center reports, workforce distribution, and rotation movements.
 * 
 * Authorization: Super Admin only
 */
class ReportController extends Controller
{
    protected ReportService $reportService;

    /**
     * Constructor
     * 
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        // Restrict access to Super Admin role only
        $this->middleware(['auth', 'role:super_admin']);
        
        $this->reportService = $reportService;
    }

    /**
     * Display cost center incident report
     * 
     * Shows incidents grouped by cost center facility with filtering options.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function costCenterReport(Request $request)
    {
        // Get date range filters from request
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))
            : null;
        
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : null;

        // Get incidents grouped by cost center
        $incidentsByCostCenter = $this->reportService->getIncidentsByCostCenter($startDate, $endDate);

        // Get summary statistics
        $summaryStatistics = $this->reportService->getCostCenterSummaryStatistics($startDate, $endDate);

        return view('system.roster.reports.cost-center', compact(
            'incidentsByCostCenter',
            'summaryStatistics',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display workforce distribution by facility
     * 
     * Shows staff counts by facility for a specific date, including permanent and rotational staff.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function workforceDistribution(Request $request)
    {
        // Get date filter from request, default to today
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        // Get workforce distribution for the specified date
        $workforceDistribution = $this->reportService->getWorkforceDistribution($date);

        return view('system.roster.reports.workforce-distribution', compact(
            'workforceDistribution',
            'date'
        ));
    }

    /**
     * Display rotation movements (incoming and outgoing staff)
     * 
     * Shows staff movements for a facility within a date range.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function rotationMovements(Request $request)
    {
        // Validate facility_id is required
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
        ]);

        $facilityId = $request->input('facility_id');
        
        // Get date range filters, default to current month
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : Carbon::now()->endOfMonth();

        // Get rotation movements for the facility
        $movements = $this->reportService->getRotationMovements($facilityId, $startDate, $endDate);

        // Get facility details
        $facility = \App\Models\Facility::findOrFail($facilityId);

        // Get all facilities for dropdown
        $facilities = \App\Models\Facility::orderBy('name')->get();

        return view('system.roster.reports.rotation-movements', compact(
            'movements',
            'facility',
            'facilities',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export cost center report to CSV
     * 
     * Downloads a CSV file containing incidents grouped by cost center.
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCostCenterReport(Request $request)
    {
        // Get date range filters from request
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))
            : null;
        
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))
            : null;

        // Get incidents grouped by cost center
        $incidentsByCostCenter = $this->reportService->getIncidentsByCostCenter($startDate, $endDate);

        // Generate filename with date range
        $filename = 'cost_center_report';
        if ($startDate) {
            $filename .= '_from_' . $startDate->format('Y-m-d');
        }
        if ($endDate) {
            $filename .= '_to_' . $endDate->format('Y-m-d');
        }
        $filename .= '_' . now()->format('Y-m-d_His') . '.csv';

        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Stream CSV content
        $callback = function() use ($incidentsByCostCenter) {
            $file = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($file, [
                'Cost Center Facility',
                'Incident Reference',
                'Worker Name',
                'Incident Date',
                'Worker Primary Facility',
                'Incident Facility',
                'Cost Center Facility',
                'Is Rotational Staff',
                'Assignment Type',
                'Assignment Start Date',
                'Assignment End Date',
            ]);

            // Write data rows
            foreach ($incidentsByCostCenter as $costCenterGroup) {
                foreach ($costCenterGroup['incidents'] as $incident) {
                    $row = [
                        $costCenterGroup['cost_center_facility_name'],
                        $incident['reference_number'],
                        $incident['worker_name'],
                        $incident['incident_date']->format('Y-m-d'),
                        $incident['worker_primary_facility_name'],
                        $incident['incident_facility_name'],
                        $incident['cost_center_facility_name'],
                        $incident['is_rotational_staff'] ? 'Yes' : 'No',
                        $incident['roster_context']['assignment_type'] ?? 'N/A',
                        isset($incident['roster_context']['assignment_start_date']) 
                            ? $incident['roster_context']['assignment_start_date']->format('Y-m-d') 
                            : 'N/A',
                        isset($incident['roster_context']['assignment_end_date']) 
                            ? $incident['roster_context']['assignment_end_date']->format('Y-m-d') 
                            : 'N/A',
                    ];

                    fputcsv($file, $row);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export roster data to CSV
     * 
     * Downloads a CSV file containing roster and assignment data with optional filtering.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportRosterData(Request $request)
    {
        // Build filters array from request
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'facility_id' => $request->input('facility_id'),
            'staff_type' => $request->input('staff_type'),
            'district_id' => $request->input('district_id'),
        ];

        // Get CSV content from report service
        $csvContent = $this->reportService->exportRosterData($filters);

        // Generate filename with timestamp
        $filename = 'roster_data_export_' . now()->format('Y-m-d_His') . '.csv';

        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        return Response::make($csvContent, 200, $headers);
    }
}
