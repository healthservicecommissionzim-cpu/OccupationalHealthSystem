<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\RiskAssessment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RiskPredictiveAnalytics
{
    /**
     * Calculate dynamic risk forecast for a specific location and task
     */
    public function calculatePredictiveRisk($facilityId, $departmentId = null, $specificZone = null, $taskType = null)
    {
        $prediction = [
            'predicted_likelihood' => 3, // Default medium
            'predicted_severity' => 3,
            'escalation_warning' => false,
            'predictive_insights' => '',
            'linked_incidents_30d' => 0,
            'linked_incidents_90d' => 0,
            'linked_incidents_365d' => 0,
            'linked_dod_total' => 0,
            'probable_causes' => '',
            'frequency_trend' => 'STABLE',
        ];

        // Build incident query for this location
        $baseQuery = Incident::where('facility_id', $facilityId);
        
        if ($departmentId) {
            $baseQuery->where('department_id', $departmentId);
        }

        // Calculate incident frequencies
        $prediction['linked_incidents_30d'] = (clone $baseQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $prediction['linked_incidents_90d'] = (clone $baseQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(90))
            ->count();

        $prediction['linked_incidents_365d'] = (clone $baseQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(365))
            ->count();

        // Check for any DODs at this location (all time)
        // Check status for death-related keywords
        $prediction['linked_dod_total'] = (clone $baseQuery)
            ->where(function($q) {
                $q->where('status', 'like', '%DEATH%')
                  ->orWhere('status', 'like', '%FATAL%')
                  ->orWhere('status', 'like', '%DOD%');
            })
            ->count();

        // Escalate likelihood based on frequency thresholds
        if ($prediction['linked_incidents_90d'] >= 5) {
            $prediction['predicted_likelihood'] = 5; // Very likely
            $prediction['escalation_warning'] = true;
        } elseif ($prediction['linked_incidents_90d'] >= 3) {
            $prediction['predicted_likelihood'] = 4; // Likely
            $prediction['escalation_warning'] = true;
        } elseif ($prediction['linked_incidents_90d'] >= 2) {
            $prediction['predicted_likelihood'] = 4;
        } elseif ($prediction['linked_incidents_365d'] >= 4) {
            $prediction['predicted_likelihood'] = 3;
        } elseif ($prediction['linked_incidents_365d'] >= 2) {
            $prediction['predicted_likelihood'] = 3;
        } else {
            $prediction['predicted_likelihood'] = 2; // Unlikely
        }

        // DOD presence automatically elevates severity
        if ($prediction['linked_dod_total'] > 0) {
            $prediction['predicted_severity'] = 5; // Catastrophic
            $prediction['escalation_warning'] = true;
        } else {
            // Calculate average severity from historical incidents
            $avgSeverity = $this->calculateHistoricalSeverity($baseQuery);
            $prediction['predicted_severity'] = $avgSeverity;
        }

        // Analyze frequency trend
        $prediction['frequency_trend'] = $this->analyzeTrend(
            $prediction['linked_incidents_30d'],
            $prediction['linked_incidents_90d'],
            $prediction['linked_incidents_365d']
        );

        if ($prediction['frequency_trend'] === 'INCREASING') {
            $prediction['escalation_warning'] = true;
        }

        // Generate pattern analysis and probable causes
        $prediction['probable_causes'] = $this->extractProbableCauses($baseQuery, $specificZone, $taskType);

        // Generate predictive insights text
        $prediction['predictive_insights'] = $this->generateInsights($prediction, $specificZone, $taskType);

        return $prediction;
    }

    /**
     * Calculate historical severity average
     */
    private function calculateHistoricalSeverity($query)
    {
        $incidents = (clone $query)
            ->where('created_at', '>=', Carbon::now()->subDays(365))
            ->get();

        if ($incidents->isEmpty()) {
            return 3; // Default medium
        }

        $severityScore = 0;
        $count = 0;

        foreach ($incidents as $incident) {
            // Map incident outcomes to severity
            $status = strtoupper($incident->status ?? '');
            $description = strtolower($incident->incident_description ?? '');
            
            if (stripos($status, 'DEATH') !== false || 
                stripos($status, 'FATAL') !== false ||
                stripos($status, 'DOD') !== false) {
                $severityScore += 5;
            } elseif (stripos($description, 'fracture') !== false || 
                      stripos($description, 'amputation') !== false ||
                      stripos($description, 'severe') !== false ||
                      stripos($description, 'major') !== false) {
                $severityScore += 4;
            } elseif (stripos($description, 'injury') !== false ||
                      stripos($description, 'cut') !== false ||
                      stripos($description, 'burn') !== false) {
                $severityScore += 3;
            } else {
                $severityScore += 2;
            }
            $count++;
        }

        $average = $count > 0 ? $severityScore / $count : 3;
        return max(1, min(5, round($average)));
    }

    /**
     * Analyze trend from frequency data
     */
    private function analyzeTrend($count30d, $count90d, $count365d)
    {
        // Calculate monthly rates
        $rate30d = $count30d;
        $rate90d = $count90d / 3;
        $rate365d = $count365d / 12;

        // Trending upward if recent rate significantly higher
        if ($rate30d > ($rate90d * 1.5)) {
            return 'INCREASING';
        } elseif ($rate30d < ($rate90d * 0.5)) {
            return 'DECREASING';
        } else {
            return 'STABLE';
        }
    }

    /**
     * Extract probable root causes from incident patterns
     */
    private function extractProbableCauses($query, $specificZone = null, $taskType = null)
    {
        $incidents = (clone $query)
            ->where('created_at', '>=', Carbon::now()->subDays(180))
            ->get();

        if ($incidents->isEmpty()) {
            return 'No historical incidents found for this location.';
        }

        // Analyze incident descriptions for patterns
        $patterns = [];
        $keywords = ['slip', 'fall', 'cut', 'burn', 'struck', 'caught', 'crush', 'vehicle', 'machinery', 
                     'chemical', 'electric', 'needle', 'sharps', 'back', 'lifting', 'night shift'];

        foreach ($keywords as $keyword) {
            $count = 0;
            foreach ($incidents as $incident) {
                if (stripos($incident->incident_description, $keyword) !== false) {
                    $count++;
                }
            }
            if ($count > 0) {
                $percentage = round(($count / $incidents->count()) * 100);
                if ($percentage >= 20) { // Only significant patterns
                    $patterns[] = "$percentage% involve $keyword-related incidents";
                }
            }
        }

        // Analyze incident types
        $typeGroups = $incidents->groupBy('incident_type_id')
            ->map(function ($group) use ($incidents) {
                return [
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / $incidents->count()) * 100),
                    'type' => $group->first()->incidentType->name ?? 'Unknown'
                ];
            })
            ->sortByDesc('count')
            ->take(3);

        foreach ($typeGroups as $group) {
            if ($group['percentage'] >= 15) {
                $patterns[] = "{$group['percentage']}% are {$group['type']} incidents";
            }
        }

        if (empty($patterns)) {
            return "Total {$incidents->count()} incidents recorded. Patterns under analysis.";
        }

        return implode('; ', $patterns) . '.';
    }

    /**
     * Generate human-readable insights
     */
    private function generateInsights($prediction, $specificZone = null, $taskType = null)
    {
        $insights = [];

        // Location context
        $location = $specificZone ? "Zone: $specificZone" : "this location";
        
        // Frequency analysis
        if ($prediction['linked_incidents_30d'] > 0) {
            $insights[] = "⚠️ {$prediction['linked_incidents_30d']} incident(s) occurred at $location in the last 30 days";
        }

        if ($prediction['linked_incidents_90d'] >= 3) {
            $insights[] = "📊 High incident frequency detected: {$prediction['linked_incidents_90d']} cases in 90 days - this is above the safe threshold";
        }

        // DOD warning
        if ($prediction['linked_dod_total'] > 0) {
            $insights[] = "🚨 CRITICAL: {$prediction['linked_dod_total']} Death on Duty incident(s) recorded at this location";
        }

        // Trend analysis
        if ($prediction['frequency_trend'] === 'INCREASING') {
            $insights[] = "📈 Risk trend is INCREASING - incidents becoming more frequent";
        } elseif ($prediction['frequency_trend'] === 'DECREASING') {
            $insights[] = "📉 Positive trend: incident frequency is decreasing";
        }

        // Risk level warning
        $predictedScore = $prediction['predicted_likelihood'] * $prediction['predicted_severity'];
        if ($predictedScore >= 20) {
            $insights[] = "🔴 CRITICAL RISK - Immediate intervention required";
        } elseif ($predictedScore >= 15) {
            $insights[] = "🟠 HIGH RISK - Priority attention needed";
        }

        // Pattern insights
        if (!empty($prediction['probable_causes'])) {
            $insights[] = "📋 Pattern Analysis: " . $prediction['probable_causes'];
        }

        // Default message if no incidents
        if (empty($insights)) {
            $insights[] = "✅ No recent incidents recorded at this location. Continue monitoring and maintaining safety controls.";
        }

        return implode("\n\n", $insights);
    }

    /**
     * Update risk assessment with predictive data
     */
    public function updateRiskAssessmentPredictions(RiskAssessment $riskAssessment)
    {
        $prediction = $this->calculatePredictiveRisk(
            $riskAssessment->facility_id,
            $riskAssessment->department_id,
            $riskAssessment->specific_zone,
            $riskAssessment->task_type
        );

        $riskAssessment->update([
            'predicted_likelihood' => $prediction['predicted_likelihood'],
            'predicted_severity' => $prediction['predicted_severity'],
            'escalation_warning' => $prediction['escalation_warning'],
            'predictive_insights' => $prediction['predictive_insights'],
            'linked_incidents_30d' => $prediction['linked_incidents_30d'],
            'linked_incidents_90d' => $prediction['linked_incidents_90d'],
            'linked_incidents_365d' => $prediction['linked_incidents_365d'],
            'linked_dod_total' => $prediction['linked_dod_total'],
            'probable_causes' => $prediction['probable_causes'],
        ]);

        // Auto-trigger review if escalation warning
        if ($prediction['escalation_warning'] && $riskAssessment->status === 'ACTIVE') {
            $riskAssessment->update(['status' => 'NEEDS_REVIEW']);
        }

        return $prediction;
    }
}
