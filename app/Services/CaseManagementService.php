<?php

namespace App\Services;

use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CaseManagementService
{
    /**
     * Get a case with its full timeline and related data.
     *
     * @param string $caseId
     * @return Incident Case with loaded relationships
     */
    public function getCaseWithTimeline(string $caseId): Incident
    {
        try {
            $case = Incident::with([
                'monitoringNotes.author',
                'complianceChecks',
            ])->findOrFail($caseId);

            return $case;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve case with timeline', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get current holder information for a case.
     *
     * @param string $caseId
     * @return array Current holder details including duration held
     */
    public function getCurrentHolder(string $caseId): array
    {
        try {
            $case = Incident::findOrFail($caseId);

            if (!$case->current_holder_id) {
                return [
                    'holder_id' => null,
                    'holder_name' => 'Not assigned',
                    'holder_designation' => null,
                    'current_stage' => $case->current_stage,
                    'duration_at_stage_hours' => 0,
                    'duration_at_stage_days' => 0,
                ];
            }

            $holder = $case->load('currentHolder');
            $durationHours = $this->calculateDurationAtStage($caseId);
            $durationDays = round($durationHours / 24, 2);

            return [
                'holder_id' => $case->current_holder_id,
                'holder_name' => $holder->currentHolder?->name ?? 'Unknown',
                'holder_designation' => $holder->currentHolder?->designation?->designation_name ?? 'Unknown',
                'current_stage' => $case->current_stage,
                'duration_at_stage_hours' => $durationHours,
                'duration_at_stage_days' => $durationDays,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get current holder', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculate duration a case has been at its current stage.
     *
     * @param string $caseId
     * @return int Duration in hours
     */
    public function calculateDurationAtStage(string $caseId): int
    {
        try {
            $case = Incident::findOrFail($caseId);

            // Use updated_at as the reference point for when the case was last in this stage
            // In a real implementation, you'd have a case_stage_history table
            if (!$case->updated_at) {
                return 0;
            }

            return $case->updated_at->diffInHours(now());
        } catch (\Exception $e) {
            Log::error('Failed to calculate stage duration', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    /**
     * Escalate a case to the next stage and update the current holder.
     *
     * @param string $caseId
     * @param string $nextStage The next stage to escalate to
     * @param int $nextHolderId The user ID of the next holder
     * @param string|null $reason Optional escalation reason
     * @return Incident Updated case
     */
    public function escalateCaseToNextStage(string $caseId, string $nextStage, int $nextHolderId, ?string $reason = null): Incident
    {
        try {
            $case = Incident::findOrFail($caseId);
            $previousStage = $case->current_stage;

            // Update the case
            $case->update([
                'current_stage' => $nextStage,
                'current_holder_id' => $nextHolderId,
                'updated_at' => now(),
            ]);

            // Log this escalation to the audit trail via AuditService
            $auditService = app(AuditService::class);
            $auditService->logAction('escalated', $caseId, [
                'action' => 'Case escalated',
                'old_values' => ['stage' => $previousStage, 'holder_id' => $case->current_holder_id],
                'new_values' => ['stage' => $nextStage, 'holder_id' => $nextHolderId],
                'affected_entity' => 'Case Stage',
            ]);

            Log::info('Case escalated', [
                'case_id' => $caseId,
                'from_stage' => $previousStage,
                'to_stage' => $nextStage,
                'next_holder_id' => $nextHolderId,
                'reason' => $reason,
            ]);

            return $case->refresh();
        } catch (\Exception $e) {
            Log::error('Failed to escalate case', [
                'case_id' => $caseId,
                'next_stage' => $nextStage,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Send a nudge (reminder) notification to the current case holder.
     *
     * @param string $caseId
     * @return bool Success status
     */
    public function sendNudgeToHolder(string $caseId): bool
    {
        try {
            $case = Incident::findOrFail($caseId);

            if (!$case->current_holder_id) {
                Log::warning('Cannot send nudge - no current holder assigned', ['case_id' => $caseId]);
                return false;
            }

            $holder = $case->currentHolder;
            if (!$holder) {
                Log::warning('Current holder not found', ['case_id' => $caseId, 'holder_id' => $case->current_holder_id]);
                return false;
            }

            // Send notification (email and SMS if available)
            $message = "Reminder: Case {$case->reference_number} requires your attention at stage {$case->current_stage}. "
                      . "Worker: {$case->first_name} {$case->surname}. "
                      . "Please review and take action.";

            // Log to audit trail
            $auditService = app(AuditService::class);
            $auditService->logAction('nudge_sent', $caseId, [
                'action' => 'Nudge sent to case holder',
                'affected_entity' => 'Case Holder Notification',
            ]);

            // Send SMS if holder has phone
            if ($holder->phone_number) {
                $smsService = app(\App\Services\SMSService::class);
                $smsService->send($holder->phone_number, $message);
            }

            Log::info('Nudge sent to case holder', [
                'case_id' => $caseId,
                'holder_id' => $case->current_holder_id,
                'holder_email' => $holder->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send nudge', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get case timeline with all transitions (if timeline table exists).
     * For now, returns minimal timeline based on current data.
     *
     * @param string $caseId
     * @return array Timeline events
     */
    public function getCaseTimeline(string $caseId): array
    {
        try {
            $case = Incident::findOrFail($caseId);

            $timeline = [];

            // Add case creation event
            if ($case->created_at) {
                $timeline[] = [
                    'timestamp' => $case->created_at,
                    'event' => 'Case Created',
                    'stage' => 'Submitted',
                    'actor' => 'System',
                    'description' => "Incident {$case->reference_number} was created",
                ];
            }

            // Add supervisor verification event
            if ($case->supervisor_verified_at) {
                $timeline[] = [
                    'timestamp' => $case->supervisor_verified_at,
                    'event' => 'Supervisor Verified',
                    'stage' => 'Supervisor Review',
                    'actor' => $case->assignedSupervisor?->name ?? 'Unknown',
                    'description' => 'Supervisor acknowledged and verified incident',
                ];
            }

            // Add HOD report submission event
            if ($case->hod_report_submitted_at) {
                $timeline[] = [
                    'timestamp' => $case->hod_report_submitted_at,
                    'event' => 'HOD Report Submitted',
                    'stage' => 'DMO Review',
                    'actor' => $case->hod_report_submitted_by ? \App\Models\User::find($case->hod_report_submitted_by)?->name : 'Unknown',
                    'description' => 'Head of Department submitted incident report',
                ];
            }

            // Add HR escalation event
            if ($case->escalated_to_hr_at) {
                $timeline[] = [
                    'timestamp' => $case->escalated_to_hr_at,
                    'event' => 'Escalated to HR',
                    'stage' => 'HR Review',
                    'actor' => $case->routed_to_hr_user_id ? \App\Models\User::find($case->routed_to_hr_user_id)?->name : 'Unknown',
                    'description' => 'Case escalated to HR for review',
                ];
            }

            // Sort by timestamp
            usort($timeline, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

            return $timeline;
        } catch (\Exception $e) {
            Log::error('Failed to get case timeline', [
                'case_id' => $caseId,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Check if a case is overdue at its current stage.
     *
     * @param string $caseId
     * @param int $thresholdHours
     * @return bool
     */
    public function isCaseOverdue(string $caseId, int $thresholdHours = 48): bool
    {
        $durationHours = $this->calculateDurationAtStage($caseId);
        return $durationHours > $thresholdHours;
    }
}
