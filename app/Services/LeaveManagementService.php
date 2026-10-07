<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\AuditLog;
use App\Models\IncidentLeaveMilestone;
use App\Events\Case90DayMilestoneReached;
use App\Events\Case180DayMilestoneReached;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LeaveManagementService
{
    /**
     * Save leave information for an incident with validation and audit logging.
     */
    public function saveLeaveInformation(Incident $incident, array $data, User $user): void
    {
        // Validate leave data
        $this->validateLeaveData($incident, $data);
        
        // Store old state for audit
        $oldState = [
            'sick_leave_start_date' => $incident->sick_leave_start_date,
            'medical_certificate_provided' => $incident->medical_certificate_provided,
            'expected_recovery_date' => $incident->expected_recovery_date,
            'leave_payment_stage' => $incident->leave_payment_stage,
        ];
        
        // Calculate payment stage
        $paymentStage = $this->calculatePaymentStage($incident, $data);
        
        // Update incident
        $incident->update([
            'sick_leave_start_date' => $data['sick_leave_start_date'],
            'medical_certificate_provided' => $data['medical_certificate_provided'] ?? false,
            'expected_recovery_date' => $data['expected_recovery_date'] ?? null,
            'leave_payment_stage' => $paymentStage,
            'leave_information_saved_at' => now(),
            'saved_by_user_id' => $user->id,
        ]);
        
        // Create audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'leave_information_saved',
            'changed_by_user_id' => $user->id,
            'old_values' => json_encode($oldState),
            'new_values' => json_encode($incident->only([
                'sick_leave_start_date',
                'medical_certificate_provided',
                'expected_recovery_date',
                'leave_payment_stage',
            ])),
        ]);
        
        Log::info('Leave information saved', [
            'incident_id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'saved_by_user_id' => $user->id,
            'payment_stage' => $paymentStage,
        ]);
    }

    /**
     * Validate leave data according to HSB 3/2017 regulations.
     */
    protected function validateLeaveData(Incident $incident, array $data): void
    {
        $rules = [
            'sick_leave_start_date' => 'required|date|before_or_equal:today|after_or_equal:' . $incident->incident_date->format('Y-m-d'),
            'medical_certificate_provided' => 'boolean',
            'expected_recovery_date' => 'nullable|date|after:sick_leave_start_date|before_or_equal:' . now()->addDays(365)->format('Y-m-d'),
        ];
        
        // If medical certificate is provided, recovery date is required
        if ($data['medical_certificate_provided'] ?? false) {
            $rules['expected_recovery_date'] = 'required|' . $rules['expected_recovery_date'];
        }

        Validator::make($data, $rules)->validate();
    }

    /**
     * Calculate the leave payment stage based on days elapsed and medical certificate.
     */
    protected function calculatePaymentStage(Incident $incident, array $data): string
    {
        $startDate = Carbon::parse($data['sick_leave_start_date']);
        $daysElapsed = now()->diffInDays($startDate);
        
        if ($daysElapsed <= 90) {
            return 'FULL_PAY';
        }
        
        if ($daysElapsed <= 180 && ($data['medical_certificate_provided'] ?? false)) {
            return 'HALF_PAY';
        }
        
        return 'FULL_PAY';
    }

    /**
     * Check and apply milestone flags for all incidents with active leave.
     * Called daily by scheduler.
     */
    public function checkAndApplyMilestones(): array
    {
        $results = ['flagged_90' => 0, 'flagged_180' => 0, 'errors' => []];
        
        $incidentsWithLeave = Incident::whereNotNull('sick_leave_start_date')
            ->where('leave_status', 'ACTIVE')
            ->get();
        
        foreach ($incidentsWithLeave as $incident) {
            try {
                if ($incident->is90DayMilestonePending()) {
                    $this->apply90DayMilestone($incident);
                    $results['flagged_90']++;
                }
                
                if ($incident->is180DayMilestonePending()) {
                    $this->apply180DayMilestone($incident);
                    $results['flagged_180']++;
                }
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'incident_id' => $incident->id,
                    'error' => $e->getMessage(),
                ];
                
                Log::error('Error processing leave milestones', [
                    'incident_id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'exception' => $e->getMessage(),
                ]);
            }
        }
        
        return $results;
    }

    /**
     * Apply 90-day milestone flag and notify appropriate medical officer.
     */
    protected function apply90DayMilestone(Incident $incident): void
    {
        // Update incident status
        $incident->update([
            'status' => 'REFERRED_TO_MEDICAL_BOARD',
            'leave_payment_stage' => $incident->medical_certificate_provided ? 'HALF_PAY' : 'FULL_PAY',
        ]);
        
        // Record milestone
        IncidentLeaveMilestone::create([
            'incident_id' => $incident->id,
            'milestone_type' => '90_DAY',
            'days_elapsed_at_flag' => $incident->days_elapsed,
            'flag_reason' => '90_DAY_MILESTONE',
            'applied_by_system' => true,
        ]);
        
        // Create audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'milestone_flag_applied',
            'changed_by_user_id' => null,
            'old_values' => json_encode(['status' => 'Under_HR_Review']),
            'new_values' => json_encode(['status' => 'REFERRED_TO_MEDICAL_BOARD']),
            'metadata' => json_encode(['milestone_type' => '90_DAY']),
        ]);
        
        // Dispatch event to notify medical officer
        event(new Case90DayMilestoneReached($incident));
        
        Log::info('90-day leave milestone applied', [
            'incident_id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'days_elapsed' => $incident->days_elapsed,
        ]);
    }

    /**
     * Apply 180-day milestone flag and notify appropriate medical officer.
     */
    protected function apply180DayMilestone(Incident $incident): void
    {
        // Update incident status
        $incident->update([
            'status' => 'MEDICAL_DISCHARGE_ELIGIBLE',
        ]);
        
        // Record milestone
        IncidentLeaveMilestone::create([
            'incident_id' => $incident->id,
            'milestone_type' => '180_DAY',
            'days_elapsed_at_flag' => $incident->days_elapsed,
            'flag_reason' => '180_DAY_MILESTONE',
            'applied_by_system' => true,
        ]);
        
        // Create audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'milestone_flag_applied',
            'changed_by_user_id' => null,
            'old_values' => json_encode(['status' => 'REFERRED_TO_MEDICAL_BOARD']),
            'new_values' => json_encode(['status' => 'MEDICAL_DISCHARGE_ELIGIBLE']),
            'metadata' => json_encode(['milestone_type' => '180_DAY']),
        ]);
        
        // Dispatch event to notify medical officer
        event(new Case180DayMilestoneReached($incident));
        
        Log::info('180-day leave milestone applied', [
            'incident_id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'days_elapsed' => $incident->days_elapsed,
        ]);
    }
}
