<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\AuditLog;
use App\Models\ComplianceCheck;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReportService
{
    /**
     * Generate comprehensive report with case data aggregation.
     *
     * @param string $reportType 'monthly', 'quarterly', 'annual'
     * @param Carbon $startDate Report period start
     * @param Carbon $endDate Report period end
     * @param array $filters Optional filters (facility_id, case_type, location)
     * @return array Report data including metrics and impact calculations
     */
    public function generateReport(string $reportType, Carbon $startDate, Carbon $endDate, array $filters = []): array
    {
        try {
            $cases = $this->aggregateCaseData($startDate, $endDate, $filters);
            $impactMetrics = $this->calculateImpactMetrics($cases);
            $complianceMetrics = $this->calculateComplianceMetrics($startDate, $endDate, $filters);
            $trends = $this->calculateTrends($startDate, $endDate, $filters);

            $report = [
                'report_type' => $reportType,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days' => $startDate->diffInDays($endDate),
                ],
                'case_metrics' => [
                    'total_cases' => $cases->count(),
                    'active_cases' => $cases->where('status', 'active')->count(),
                    'completed_cases' => $cases->where('status', 'completed')->count(),
                    'closed_cases' => $cases->where('status', 'closed')->count(),
                ],
                'sla_metrics' => [
                    'breach_count' => $this->calculateSLABreaches($cases),
                    'breach_percentage' => $this->calculateSLABreachPercentage($cases),
                    'average_tat_days' => $this->calculateAverageTAT($cases),
                ],
                'compliance_metrics' => $complianceMetrics,
                'impact_metrics' => $impactMetrics,
                'case_type_distribution' => $this->getCaseTypeDistribution($cases),
                'institutional_volume' => $this->getInstitutionalVolume($cases),
                'trends' => $trends,
                'generated_at' => now()->format('Y-m-d H:i:s'),
            ];

            Log::info('Report generated successfully', [
                'report_type' => $reportType,
                'total_cases' => $cases->count(),
            ]);

            return $report;
        } catch (\Exception $e) {
            Log::error('Failed to generate report', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Aggregate case data for report period.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $filters
     * @return Collection
     */
    private function aggregateCaseData(Carbon $startDate, Carbon $endDate, array $filters = []): Collection
    {
        $query = Incident::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        if (!empty($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }

        if (!empty($filters['case_type'])) {
            $query->where('incident_type_id', $filters['case_type']);
        }

        if (!empty($filters['location'])) {
            $query->whereHas('facility', function ($q) {
                $q->where('district_id', $filters['location']);
            });
        }

        return $query->with(['facility', 'incidentType'])->get();
    }

    /**
     * Calculate impact metrics: sick leave, light duty, compensation.
     *
     * @param Collection $cases
     * @return array Impact metrics
     */
    private function calculateImpactMetrics(Collection $cases): array
    {
        $totalSickLeaveDays = $cases->sum(function ($case) {
            return $case->leaveMilestones()
                ->where('milestone_type', 'sick_leave')
                ->sum('days_estimated');
        });

        $totalLightDutyDays = $cases->sum(function ($case) {
            return $case->leaveMilestones()
                ->where('milestone_type', 'light_duty')
                ->sum('days_estimated');
        });

        $totalCompensationAmount = $cases->sum(function ($case) {
            return $case->leaveMilestones()
                ->where('milestone_type', 'compensation')
                ->sum('amount_payable');
        });

        return [
            'total_sick_leave_days' => $totalSickLeaveDays,
            'total_light_duty_days' => $totalLightDutyDays,
            'total_compensation_amount' => number_format($totalCompensationAmount, 2),
            'average_sick_leave_per_case' => $cases->count() > 0 ? round($totalSickLeaveDays / $cases->count(), 1) : 0,
        ];
    }

    /**
     * Calculate compliance metrics.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $filters
     * @return array Compliance metrics
     */
    private function calculateComplianceMetrics(Carbon $startDate, Carbon $endDate, array $filters = []): array
    {
        $query = ComplianceCheck::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        if (!empty($filters['facility_id'])) {
            $query->whereHas('case', function ($q) {
                $q->where('facility_id', $filters['facility_id']);
            });
        }

        $checks = $query->get();
        $compliantCount = $checks->where('overall_status', 'COMPLIANT')->count();
        $totalChecks = $checks->count();

        return [
            'total_checks_run' => $totalChecks,
            'compliant_cases' => $compliantCount,
            'non_compliant_cases' => $totalChecks - $compliantCount,
            'compliance_rate_percentage' => $totalChecks > 0 ? round(($compliantCount / $totalChecks) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate SLA breaches in dataset.
     *
     * @param Collection $cases
     * @return int Number of SLA breaches
     */
    private function calculateSLABreaches(Collection $cases): int
    {
        $slaThresholds = [
            'Submitted' => 7,
            'Supervisor_Review' => 10,
            'DMO_Review' => 14,
            'PMD_Review' => 14,
            'HSC_Resolution' => 30,
        ];

        return $cases->filter(function ($case) use ($slaThresholds) {
            $currentStage = $case->current_stage ?? 'Submitted';
            $threshold = $slaThresholds[$currentStage] ?? 30;
            $durationDays = $case->created_at->diffInDays(now());
            return $durationDays > $threshold;
        })->count();
    }

    /**
     * Calculate SLA breach percentage.
     *
     * @param Collection $cases
     * @return float Percentage of cases with SLA breaches
     */
    private function calculateSLABreachPercentage(Collection $cases): float
    {
        $totalCases = $cases->count();
        if ($totalCases === 0) {
            return 0.0;
        }

        $breachCount = $this->calculateSLABreaches($cases);
        return round(($breachCount / $totalCases) * 100, 2);
    }

    /**
     * Calculate average TAT for cases.
     *
     * @param Collection $cases
     * @return float Average TAT in days
     */
    private function calculateAverageTAT(Collection $cases): float
    {
        $completedCases = $cases->whereIn('status', ['completed', 'closed']);

        if ($completedCases->isEmpty()) {
            return 0.0;
        }

        $totalDays = $completedCases->sum(function ($case) {
            $completedAt = $case->updated_at ?? now();
            return $case->created_at->diffInDays($completedAt);
        });

        return round($totalDays / $completedCases->count(), 1);
    }

    /**
     * Get case type distribution.
     *
     * @param Collection $cases
     * @return array Case type breakdown with counts
     */
    private function getCaseTypeDistribution(Collection $cases): array
    {
        return $cases->groupBy(function ($case) {
            return $case->incidentType->name ?? 'Unknown';
        })->map(function ($group) {
            return $group->count();
        })->toArray();
    }

    /**
     * Get volume by institution.
     *
     * @param Collection $cases
     * @return array Facility names with case counts
     */
    private function getInstitutionalVolume(Collection $cases): array
    {
        return $cases->groupBy(function ($case) {
            return $case->facility->name ?? 'Unknown';
        })->map(function ($group) {
            return $group->count();
        })->sortByDesc(function ($count) {
            return $count;
        })->toArray();
    }

    /**
     * Calculate trends over report period.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $filters
     * @return array Trend data by week/month
     */
    private function calculateTrends(Carbon $startDate, Carbon $endDate, array $filters = []): array
    {
        $cases = $this->aggregateCaseData($startDate, $endDate, $filters);
        $periodDays = $startDate->diffInDays($endDate);

        // Group by week if less than 90 days, otherwise by month
        if ($periodDays <= 90) {
            return $this->calculateWeeklyTrends($cases, $startDate, $endDate);
        } else {
            return $this->calculateMonthlyTrends($cases, $startDate, $endDate);
        }
    }

    /**
     * Calculate weekly trends.
     *
     * @param Collection $cases
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array Weekly breakdown
     */
    private function calculateWeeklyTrends(Collection $cases, Carbon $startDate, Carbon $endDate): array
    {
        $trends = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $weekEnd = $current->copy()->addWeek();
            $weekCases = $cases->filter(function ($case) use ($current, $weekEnd) {
                return $case->created_at->between($current, $weekEnd);
            });

            $trends[$current->format('Y-m-d')] = [
                'week_start' => $current->format('Y-m-d'),
                'week_end' => $weekEnd->format('Y-m-d'),
                'case_count' => $weekCases->count(),
                'completed' => $weekCases->where('status', 'completed')->count(),
            ];

            $current->addWeek();
        }

        return $trends;
    }

    /**
     * Calculate monthly trends.
     *
     * @param Collection $cases
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array Monthly breakdown
     */
    private function calculateMonthlyTrends(Collection $cases, Carbon $startDate, Carbon $endDate): array
    {
        $trends = [];
        $current = $startDate->copy()->startOfMonth();

        while ($current <= $endDate) {
            $monthEnd = $current->copy()->endOfMonth();
            $monthCases = $cases->filter(function ($case) use ($current, $monthEnd) {
                return $case->created_at->between($current, $monthEnd);
            });

            $trends[$current->format('Y-m')] = [
                'month' => $current->format('F Y'),
                'case_count' => $monthCases->count(),
                'completed' => $monthCases->where('status', 'completed')->count(),
                'breaches' => $this->calculateSLABreaches($monthCases),
            ];

            $current->addMonth();
        }

        return $trends;
    }

    /**
     * Export report data to array for PDF/Excel generation.
     *
     * @param array $report Report data
     * @param string $format 'pdf' or 'excel'
     * @return array Formatted export data
     */
    public function formatForExport(array $report, string $format = 'pdf'): array
    {
        return [
            'headers' => $this->getExportHeaders($format),
            'data' => $report,
            'footer' => "Generated on {$report['generated_at']} by CoS Dashboard",
        ];
    }

    /**
     * Get export column headers.
     *
     * @param string $format
     * @return array Column headers
     */
    private function getExportHeaders(string $format): array
    {
        return [
            'Report Period',
            'Total Cases',
            'Active Cases',
            'Completed Cases',
            'SLA Breaches',
            'Compliance Rate',
            'Average TAT',
            'Sick Leave Days',
            'Compensation Amount',
        ];
    }
}
