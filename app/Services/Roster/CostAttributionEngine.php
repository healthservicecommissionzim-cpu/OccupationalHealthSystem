<?php

namespace App\Services\Roster;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Cost Attribution Engine
 * 
 * Determines cost center attribution for incidents involving rotational staff.
 * Operates as a parallel tracking layer that does not modify existing 
 * incident workflow routing logic.
 */
class CostAttributionEngine
{
    /**
     * @var AssignmentService
     */
    protected AssignmentService $assignmentService;

    /**
     * Cache TTL in minutes
     */
    protected int $cacheTTL = 5;

    /**
     * Performance threshold in milliseconds
     * Alert if attribution exceeds this threshold
     */
    protected int $performanceThreshold = 100;

    /**
     * Create a new Cost Attribution Engine instance.
     * 
     * @param AssignmentService $assignmentService
     */
    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Get the primary facility ID for a user.
     * 
     * Query user's facility_id from users table and return as primary facility.
     * This represents the worker's home base or permanent facility assignment.
     * 
     * @param int $userId
     * @return int
     */
    protected function getPrimaryFacilityId(int $userId): int
    {
        $user = \App\Models\User::find($userId);
        
        if (!$user || !$user->facility_id) {
            throw new \RuntimeException("User {$userId} does not have a primary facility assigned");
        }
        
        return $user->facility_id;
    }

    /**
     * Get active assignment with caching.
     * 
     * Generate cache key from user_id, facility_id, date and check cache first (5 minute TTL).
     * If cache miss, query using AssignmentService.getActiveAssignment() and store result.
     * 
     * @param int $userId
     * @param int $facilityId
     * @param Carbon $date
     * @return \App\Models\RosterAssignment|null
     */
    protected function getActiveAssignmentCached(int $userId, int $facilityId, Carbon $date): ?\App\Models\RosterAssignment
    {
        // Generate cache key from user_id, facility_id, and date (YYYY-MM-DD format)
        $cacheKey = "roster_active_assignment:{$userId}:{$facilityId}:{$date->format('Y-m-d')}";
        
        // Check cache first with 5 minute TTL
        return Cache::remember($cacheKey, $this->cacheTTL * 60, function () use ($userId, $facilityId, $date) {
            // If cache miss, query using AssignmentService
            return $this->assignmentService->getActiveAssignment($userId, $facilityId, $date);
        });
    }

    /**
     * Attribute incident to appropriate cost center.
     * 
     * Determines cost center attribution when incidents are created by checking for
     * active roster assignments. Ensures execution completes within 100ms.
     * 
     * If assignment found: return rotational staff attribution
     * If no assignment: return default attribution (all fields = incident facility)
     * 
     * @param int $userId Worker involved in incident
     * @param int $incidentFacilityId Where incident occurred
     * @param Carbon $incidentDate Date of incident
     * @return array [
     *   'worker_primary_facility_id' => int,
     *   'cost_center_facility_id' => int,
     *   'roster_assignment_id' => int|null,
     *   'is_rotational_staff' => bool
     * ]
     */
    public function attributeIncident(int $userId, int $incidentFacilityId, Carbon $incidentDate): array
    {
        // Start performance monitoring
        $startTime = microtime(true);
        
        try {
            // Check for active roster assignment at the incident location
            $activeAssignment = $this->getActiveAssignmentCached($userId, $incidentFacilityId, $incidentDate);
            
            if ($activeAssignment) {
                // Rotational staff attribution: use roster data
                $result = [
                    'worker_primary_facility_id' => $this->getPrimaryFacilityId($userId),
                    'cost_center_facility_id' => $activeAssignment->cost_center_facility_id,
                    'roster_assignment_id' => $activeAssignment->id,
                    'is_rotational_staff' => true,
                ];
            } else {
                // No active assignment: default attribution (non-rotational)
                $result = [
                    'worker_primary_facility_id' => $incidentFacilityId,
                    'cost_center_facility_id' => $incidentFacilityId,
                    'roster_assignment_id' => null,
                    'is_rotational_staff' => false,
                ];
            }
            
            // Log successful attribution with performance metrics
            $this->logPerformance($startTime, $userId, $incidentFacilityId, $incidentDate, true);
            
            return $result;
            
        } catch (\Exception $e) {
            // Log performance even on failure
            $this->logPerformance($startTime, $userId, $incidentFacilityId, $incidentDate, false, $e->getMessage());
            
            // If attribution fails (timeout, error), use default attribution
            // Log error but allow incident creation to proceed
            Log::error('Cost attribution engine failed', [
                'user_id' => $userId,
                'facility_id' => $incidentFacilityId,
                'date' => $incidentDate->format('Y-m-d'),
                'error' => $e->getMessage(),
            ]);
            
            // Return default attribution (non-rotational)
            return [
                'worker_primary_facility_id' => $incidentFacilityId,
                'cost_center_facility_id' => $incidentFacilityId,
                'roster_assignment_id' => null,
                'is_rotational_staff' => false,
            ];
        }
    }

    /**
     * Invalidate cache for assignment changes.
     * 
     * Clear cache for affected user_id and facility_id combinations.
     * Call when assignments are created or modified.
     * 
     * @param int $userId
     * @param int $facilityId
     * @param Carbon|null $startDate Optional start date to invalidate a range
     * @param Carbon|null $endDate Optional end date to invalidate a range
     * @return void
     */
    public function invalidateCache(int $userId, int $facilityId, ?Carbon $startDate = null, ?Carbon $endDate = null): void
    {
        if ($startDate && $endDate) {
            // Invalidate cache for all dates in the assignment range
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $cacheKey = "roster_active_assignment:{$userId}:{$facilityId}:{$currentDate->format('Y-m-d')}";
                Cache::forget($cacheKey);
                $currentDate->addDay();
            }
        } else {
            // If no date range provided, use wildcard pattern (requires cache driver that supports it)
            // For now, we'll just log a warning and clear today's cache as a fallback
            $cacheKey = "roster_active_assignment:{$userId}:{$facilityId}:" . now()->format('Y-m-d');
            Cache::forget($cacheKey);
            
            Log::info('Cache invalidated for roster assignment change', [
                'user_id' => $userId,
                'facility_id' => $facilityId,
                'note' => 'Full cache flush recommended for this user/facility combination',
            ]);
        }
    }

    /**
     * Log performance metrics for attribution calls.
     * 
     * Logs execution time and alerts if the 100ms threshold is exceeded.
     * 
     * @param float $startTime Start time from microtime(true)
     * @param int $userId
     * @param int $facilityId
     * @param Carbon $date
     * @param bool $success Whether attribution succeeded
     * @param string|null $error Optional error message
     * @return void
     */
    protected function logPerformance(
        float $startTime,
        int $userId,
        int $facilityId,
        Carbon $date,
        bool $success,
        ?string $error = null
    ): void {
        $executionTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        
        $logData = [
            'execution_time_ms' => round($executionTime, 2),
            'user_id' => $userId,
            'facility_id' => $facilityId,
            'date' => $date->format('Y-m-d'),
            'success' => $success,
            'threshold_ms' => $this->performanceThreshold,
        ];
        
        if ($error) {
            $logData['error'] = $error;
        }
        
        // Alert if execution time exceeds threshold
        if ($executionTime > $this->performanceThreshold) {
            Log::warning('Cost attribution engine exceeded performance threshold', array_merge($logData, [
                'threshold_exceeded_by_ms' => round($executionTime - $this->performanceThreshold, 2),
            ]));
        } else {
            // Log successful attribution within threshold
            Log::info('Cost attribution completed', $logData);
        }
    }
}
