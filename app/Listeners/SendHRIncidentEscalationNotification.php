<?php

namespace App\Listeners;

use App\Events\IncidentEscalatedToHR;
use App\Models\IncidentNotification;
use App\Notifications\IncidentHRReviewNotification;
use App\Models\User;
use App\Services\CommunicationLogger;
use App\Services\SMSService;
use Illuminate\Support\Facades\Log;

class SendHRIncidentEscalationNotification
{
    protected $smsService;

    /**
     * Create the event listener.
     */
    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(IncidentEscalatedToHR $event): void
    {
        $incident = $event->incident;
        $reviewPool = $event->routeType;

        // Get the specifically assigned HR user from routed_to_hr_user_id
        if (!$incident->routed_to_hr_user_id) {
            Log::warning("No specific HR user assigned for incident", [
                'incident_id' => $incident->id,
                'review_pool' => $reviewPool,
            ]);
            return;
        }

        $assignedHRUser = User::find($incident->routed_to_hr_user_id);

        if (!$assignedHRUser) {
            Log::warning("Assigned HR user not found", [
                'incident_id' => $incident->id,
                'assigned_hr_user_id' => $incident->routed_to_hr_user_id,
            ]);
            return;
        }

        try {
            // Send email notification to the specifically assigned HR user
            $assignedHRUser->notify(new IncidentHRReviewNotification($incident, $reviewPool));

            // Log the email communication
            CommunicationLogger::logEmail(
                'HR_ESCALATION',
                $assignedHRUser->email,
                "Incident Escalated to HR Review - HSC Reference: {$incident->reference_number}",
                "Incident {$incident->reference_number} has been escalated to HR for review",
                null,
                'SENT',
                null,
                'Incident',
                $incident->id,
                $assignedHRUser->id
            );

            // Send SMS notification if phone number exists
            if (!empty($assignedHRUser->phone)) {
                $workerName = trim(($incident->first_name ?? '') . ' ' . ($incident->surname ?? ''));
                $facilityName = $incident->facility->name ?? 'Unknown Facility';
                
                $smsMessage = "Dear {$assignedHRUser->first_name}, A new occupational health incident has been escalated to you for HR review. "
                            . "Employee: {$workerName}, Reference: {$incident->reference_number} at {$facilityName}. "
                            . "Please log into HSC-OHS to review. Thank you.";

                $smsSent = $this->smsService->send($assignedHRUser->phone, $smsMessage);

                // Log SMS communication
                CommunicationLogger::logSMS(
                    'HR_ESCALATION',
                    $assignedHRUser->phone,
                    $smsMessage,
                    $smsSent ? 'SENT' : 'FAILED',
                    $smsSent ? null : 'SMS sending failed',
                    'Incident',
                    $incident->id,
                    $assignedHRUser->id
                );

                if ($smsSent) {
                    Log::info("SMS notification sent to HR user", [
                        'incident_id' => $incident->id,
                        'hr_user_id' => $assignedHRUser->id,
                        'phone' => $assignedHRUser->phone,
                    ]);
                }
            }

            // Log the notification in database
            IncidentNotification::create([
                'incident_id' => $incident->id,
                'recipient_user_id' => $assignedHRUser->id,
                'notification_type' => 'HR_ESCALATION',
                'notification_channel' => 'email,sms',
                'status' => 'UNREAD',
            ]);

            Log::info("Incident escalated to assigned HR user successfully", [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'review_pool' => $reviewPool,
                'assigned_hr_user_id' => $assignedHRUser->id,
                'assigned_hr_user_name' => $assignedHRUser->name,
                'email_sent' => true,
                'sms_sent' => !empty($assignedHRUser->phone),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send HR escalation notification to assigned user', [
                'incident_id' => $incident->id,
                'assigned_hr_user_id' => $incident->routed_to_hr_user_id,
                'review_pool' => $reviewPool,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
