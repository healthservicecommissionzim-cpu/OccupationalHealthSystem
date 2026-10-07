<?php

namespace App\Http\Controllers\CoS;

use App\Http\Controllers\Controller;
use App\Services\CoSDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExecutiveDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(CoSDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the executive dashboard with metrics, charts, and filters.
     */
    public function index(Request $request)
    {
        // Verify user has VIEW_ALL_CASES permission
        $this->authorize('view', 'all_cases');

        // Get filters from request
        $filters = [
            'case_type' => $request->input('case_type'),
            'facility_id' => $request->input('facility_id'),
            'province_id' => $request->input('province_id'),
        ];

        // Get metrics by stage
        $metrics = $this->dashboardService->getMetricsByStage($filters);

        // Get SLA breaches
        $breaches = $this->dashboardService->identifyBreaches();

        // Get TAT data for each stage
        $tatMetrics = [];
        $stages = ['submitted', 'supervisor_review', 'dmo_review', 'pmd_review', 'hsc_resolution'];
        
        foreach ($stages as $stage) {
            $tatMetrics[$stage] = $this->dashboardService->calculateAverageTAT($stage);
        }

        // Get performance trends
        $trends = $this->dashboardService->getPerformanceTrends(30);

        // Get available filters
        $caseTypes = ['injury', 'illness', 'hazard_exposure', 'mental_health'];
        $provinces = \App\Models\Province::all();
        $facilities = \App\Models\Facility::all();

        return view('dashboards.cos.executive.index', compact(
            'metrics',
            'breaches',
            'tatMetrics',
            'trends',
            'caseTypes',
            'provinces',
            'facilities',
            'filters'
        ));
    }

    /**
     * Export dashboard metrics as CSV or PDF.
     */
    public function export(Request $request)
    {
        $this->authorize('export', 'data');

        $format = $request->input('format', 'csv');
        $filters = [
            'case_type' => $request->input('case_type'),
            'facility_id' => $request->input('facility_id'),
            'province_id' => $request->input('province_id'),
        ];

        $metrics = $this->dashboardService->getMetricsByStage($filters);
        $breaches = $this->dashboardService->identifyBreaches();

        if ($format === 'csv') {
            return $this->exportCsv($metrics, $breaches);
        } elseif ($format === 'pdf') {
            return $this->exportPdf($metrics, $breaches);
        }

        return back()->withError('Invalid export format');
    }

    /**
     * Export metrics as CSV.
     */
    private function exportCsv($metrics, $breaches)
    {
        $filename = 'cos-dashboard-metrics-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($metrics, $breaches) {
            $file = fopen('php://output', 'w');

            // Write metrics header
            fputcsv($file, ['Stage Metrics']);
            fputcsv($file, ['Stage', 'Count', 'Percentage']);

            foreach ($metrics['by_stage'] as $stage => $data) {
                fputcsv($file, [$data['stage'], $data['count'], $data['percentage'] . '%']);
            }

            fputcsv($file, []);

            // Write breaches header
            fputcsv($file, ['SLA Breaches']);
            fputcsv($file, ['Reference', 'Stage', 'Hours Overdue', 'Days Overdue', 'Worker', 'Facility']);

            foreach ($breaches as $breach) {
                fputcsv($file, [
                    $breach['reference'],
                    $breach['current_stage'],
                    $breach['hours_at_stage'],
                    $breach['days_overdue'],
                    $breach['worker_name'],
                    $breach['facility'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export metrics as PDF.
     */
    private function exportPdf($metrics, $breaches)
    {
        // Implementation would use a PDF library like DomPDF
        // For now, return a placeholder
        return response()->json([
            'message' => 'PDF export functionality to be implemented',
            'metrics' => $metrics,
            'breaches' => count($breaches),
        ]);
    }
}
