<?php

namespace App\Listeners;

use App\Events\Case90DayMilestoneReached;
use App\Events\Case180DayMilestoneReached;
use App\Models\AuditLog;
use App\Models\IncidentLeaveMilestone;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendMilestoneNotifications
{
    /**
     * Handle the event - send notifications to appropriate medical officer.
     */
    public function handle(Case90DayMilestoneReached|Case180DayMilestoneReached $event): void
    {
        $incident = $event->incident;
        
        // Determine appropriate medical officer based on facility escalation hierarchy
        // PRIMARY/SECONDARY/TERTIARY → PMD
        // QUATERNARY (Central Hospital) → CMO
        $medicalOfficer = $this->getAppropriateMedicalOfficer($incident);
        
        if (!$medicalOfficer) {
            Log::warning('No medical officer found for milestone notification', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'facility_id' => $incident->facility_id,
                'facility_type' => $incident->facilityType?->name,
                'event_type' => $event::class,
            ]);
            return;
        }
        
        try {
            // Send email to assigned HR user
            if ($incident->assignedHRUser) {
                Mail::to($incident->assignedHRUser)
                    ->send(new \App\Mail\LeaveMilestoneNotification($incident, $medicalOfficer, $event::class));
                
                Log::info('Milestone notification sent to HR user', [
                    'incident_id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'hr_user_id' => $incident->assignedHRUser->id,
                    'event_type' => $event::class,
                ]);
            }
            
            // Send email to appropriate medical officer (PMD or CMO)
            Mail::to($medicalOfficer)
                ->send(new \App\Mail\LeaveMilestoneNotification($incident, $medicalOfficer, $event::class));
            
            Log::info('Milestone notification sent to medical officer', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'medical_officer_id' => $medicalOfficer->id,
                'medical_officer_role' => $medicalOfficer->getRoleNames()->first(),
                'event_type' => $event::class,
            ]);
            
            // Update notification_sent_at on milestone record
            IncidentLeaveMilestone::where('incident_id', $incident->id)
                ->latest()
                ->first()
                ?->update(['notification_sent_at' => now()]);
            
            // Create audit log with notification details
            AuditLog::create([
                'incident_id' => $incident->id,
                'action' => 'milestone_notification_sent',
                'changed_by_user_id' => null,
                'metadata' => json_encode([
                    'event_type' => $event::class,
                    'notified_medical_officer_id' => $medicalOfficer->id,
                    'medical_officer_role' => $medicalOfficer->getRoleNames()->first(),
                    'notified_hr_user_id' => $incident->routed_to_hr_user_id,
                    'facility_type' => $incident->facilityType?->name,
                ]),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send milestone notification', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'medical_officer_id' => $medicalOfficer->id,
                'event_type' => $event::class,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the appropriate medical officer based on facility-based escalation hierarchy.
     * 
     * PRIMARY/SECONDARY/TERTIARY facilities → PMD (Provincial Medical Officer)
     * QUATERNARY (Central Hospital) → CMO (Chief Medical Officer)
     */
    protected function getAppropriateMedicalOfficer($incident): ?User
    {
        $facility = $incident->facility;
        $facilityType = $facility?->facilityType?->name ?? '';
        
        return match($facilityType) {
            // PRIMARY LEVEL → PMD
            'Rural Health Centre', 'Health Post', 'Clinic', 'Polyclinic' => 
                $incident->routed_to_pmd_user_id 
                    ? User::find($incident->routed_to_pmd_user_id)
                    : User::role('pmd')->first(),
            
            // SECONDARY LEVEL → PMD
            'District Hospital', 'Rural Hospital', 'Mission Hospital' =>
                $incident->routed_to_pmd_user_id 
                    ? User::find($incident->routed_to_pmd_user_id)
                    : User::role('pmd')->first(),
            
            // TERTIARY LEVEL → PMD
            'Provincial Hospital', 'General Hospital', 'General Hospital / Designated District Referral' =>
                $incident->routed_to_pmd_user_id 
                    ? User::find($incident->routed_to_pmd_user_id)
                    : User::role('pmd')->first(),
            
            // QUATERNARY LEVEL → CMO
            'Central Hospital' =>
                $incident->routed_to_cmo_user_id 
                    ? User::find($incident->routed_to_cmo_user_id)
                    : User::role('cmo')->first(),
            
            default =>
                User::role('pmd')->first(),
        };
    }
}
