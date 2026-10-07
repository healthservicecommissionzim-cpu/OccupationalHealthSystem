<?php

namespace App\Services;

use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CoSDashboardService
{
    const CACHE_DURATION = 60; // Cache metrics for 60 seconds

    /**
     * Get metrics for all cases grouped by stage.
     *
     * @param array $filters Optional filters (case_type, location, facility_id, province_id)
     * @return array Metrics with open case counts by stage
     */
    public function getMetricsByStage(array $filters = []): array
    {
        // Generate cache key based on filters
        $cacheKey = 'cos_metrics_' . md5(json_encode($filters));

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($filters) {
            try {
                $query = Incident::active();

                // Apply filters
                if (!empty($filters['case_type'])) {
                    $query->where('case_type', $filters['case_type']);
                }

                if (!empty($filters['facility_id'])) {
                    $query->where('facility_id', $filters['facility_id']);
                }

                if (!empty($filters['province_id'])) {
                    $query->where('province_id', $filters['province_id']);
                }

                // Get all active cases
                $allCases = $query->get();

                // Define the case stages
                $stages = [
                    'submitted' => 'Submitted',
                    'supervisor_review' => 'Supervisor Review',
                    'dmo_review' => 'DMO Review',
                    'pmd_review' => 'PMD Review',
                    'hsc_resolution' => 'HSC Resolution',
                ];

                // Count cases by stage
                $metrics = [];
                $totalOpen = 0;

                foreach ($stages as $stageKey => $stageLabel) {
                    $count = $allCases
                        ->filter(fn($case) => strtolower(str_replace(' ', '_', $case->current_stage ?? '')) === $stageKey)
                        ->count();

                    $metrics[$stageKey] = [
                        'stage' => $stageLabel,
                        'count' => $count,
                        'percentage' => 0, // Will calculate after
                    ];

                    $totalOpen += $count;
                }

                // Calculate percentages
                foreach ($metrics as &$metric) {
                    $metric['percentage'] = $totalOpen > 0 ? round(($metric['count'] / $totalOpen) * 100, 2) : 0;
                }

                return [
                    'total_open' => $totalOpen,
                    'by_stage' => $metrics,
                    'cached_at' => now()->timestamp,
                ];
            } catch (\Exception $e) {
                Log::error('Failed to generate metrics by stage', [
                    'error' => $e->getMessage(),
                    'filters' => $filters,
                ]);
                throw $e;
            }
        });
    }

    /**
     * Calculate average TAT for a specific stage based on completed cases.
     *
     * @param string $stage
     * @param int $days Number of days to look back (default 90)
     * @return array TAT statistics including average, min, max
     */
    public function calculateAverageTAT(string $stage, int $days = 90): array
    {
        try {
            $since = Carbon::now()->subDays($days);

            // Query completed cases at this stage from past N days
            $cases = Incident::where('status', 'completed')
                ->where('created_at', '>=', $since)
                ->get();

            // Calculate TAT for each case if possible
            $tats = $cases->map(function ($case) {
                // TAT calculation: from stage entry to exit
                // For now, use total case duration divided by number of stages
                if ($case->updated_at && $case->created_at) {
                    return $case->created_at->diffInHours($case->updated_at);
                }
                return null;
            })->filter();

            if ($tats->isEmpty()) {
                return [
                    'stage' => $stage,
                    'average_hours' => 0,
                    'average_days' => 0,
                    'min_hours' => 0,
                    'max_hours' => 0,
                    'sample_size' => 0,
                    'data_available' => false,
                ];
            }

            $avgHours = round($tats->average(), 2);

            return [
                'stage' => $stage,
                'average_hours' => $avgHours,
                'average_days' => round($avgHours / 24, 2),
                'min_hours' => $tats->min(),
                'max_hours' => $tats->max(),
                'sample_size' => $tats->count(),
                'data_available' => true,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to calculate TAT', [
                'stage' => $stage,
                'days' => $days,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Identify all cases exceeding their SLA threshold.
     *
     * @param array $slaThresholds SLA thresholds in hours per stage
     * @return array Cases with SLA breaches
     */
    public function identifyBreaches(array $slaThresholds = []): array
    {
        // Default SLA thresholds if not provided (in hours)
        $defaults = [
            'submitted' => 24,
            'supervisor_review' => 48,
            'dmo_review' => 72,
            'pmd_review' => 96,
            'hsc_resolution' => 120,
        ];

        $thresholds = array_merge($defaults, $slaThresholds);

        try {
            $cases = Incident::active()->get();

            $breaches = [];

            foreach ($cases as $case) {
                if (!$case->created_at) {
                    continue;
                }

                $hoursAtCurrentStage = $case->created_at->diffInHours(now());
                $stageKey = strtolower(str_replace(' ', '_', $case->current_stage ?? ''));

                // Check if this case has exceeded its threshold
                if (isset($thresholds[$stageKey]) && $hoursAtCurrentStage > $thresholds[$stageKey]) {
                    $breaches[] = [
                        'case_id' => $case->id,
                        'reference' => $case->reference_number,
                        'current_stage' => $case->current_stage,
                        'hours_at_stage' => $hoursAtCurrentStage,
                        'sla_threshold_hours' => $thresholds[$stageKey],
                        'days_overdue' => round(($hoursAtCurrentStage - $thresholds[$stageKey]) / 24, 1),
                        'current_holder' => $case->current_holder_id,
                        'worker_name' => $case->first_name . ' ' . $case->surname,
                        'facility' => $case->facility?->name,
                    ];
                }
            }

            return array_values($breaches);
        } catch (\Exception $e) {
            Log::error('Failed to identify SLA breaches', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Apply filters to case data.
     *
     * @param array $cases Cases to filter
     * @param string|null $caseType Filter by case type
     * @param string|null $location Filter by location (facility_id or province_id)
     * @return array Filtered cases
     */
    public function applyFilters(array $cases, ?string $caseType = null, ?string $location = null): array
    {
        $filtered = $cases;

        if (!empty($caseType)) {
            $filtered = array_filter($filtered, fn($case) => $case['case_type'] === $caseType);
        }

        if (!empty($location)) {
            $filtered = array_filter($filtered, fn($case) =>
                ($case['facility_id'] ?? null) === $location ||
                ($case['province_id'] ?? null) === $location
            );
        }

        return array_values($filtered);
    }

    /**
     * Get TAT performance trends over N days.
     *
     * @param int $days Number of days to look back
     * @param string|null $stage Specific stage to analyze (null for all)
     * @return array Trend data with daily averages
     */
    public function getPerformanceTrends(int $days = 30, ?string $stage = null): array
    {
        try {
            $since = Carbon::now()->subDays($days);

            $query = Incident::where('created_at', '>=', $since);

            if (!empty($stage)) {
                $query->where('current_stage', $stage);
            }

            $cases = $query->get();

            // Group by date and calculate average TAT
            $trends = [];

            for ($i = $days; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->toDateString();
                $dayStart = Carbon::parse($date)->startOfDay();
                $dayEnd = Carbon::parse($date)->endOfDay();

                $dayCases = $cases->filter(fn($case) =>
                    $case->created_at >= $dayStart && $case->created_at <= $dayEnd
                );

                $avgTAT = 0;
                if (!$dayCases->isEmpty()) {
                    $tats = $dayCases->map(fn($case) =>
                        $case->created_at->diffInHours($case->updated_at ?? now())
                    );
                    $avgTAT = round($tats->average() / 24, 2); // Convert to days
                }

                $trends[] = [
                    'date' => $date,
                    'average_tat_days' => $avgTAT,
                    'case_count' => $dayCases->count(),
                ];
            }

            return [
                'period_days' => $days,
                'stage' => $stage,
                'trends' => $trends,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to calculate performance trends', [
                'days' => $days,
                'stage' => $stage,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Invalidate cached metrics.
     * Call this when cases are updated to refresh the dashboard.
     *
     * @return void
     */
    public function invalidateCache(): void
    {
        // Flush all metrics cache entries
        Cache::tags(['cos_metrics'])->flush();
        Log::info('CoS dashboard metrics cache invalidated');
    }
}
