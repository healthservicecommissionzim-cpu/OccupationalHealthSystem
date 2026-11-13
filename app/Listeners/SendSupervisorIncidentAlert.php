<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Models\IncidentNotification;
use App\Notifications\IncidentSupervisorAlertNotification;
use App\Services\CommunicationLogger;
use App\Services\SMSService;
use Illuminate\Support\Facades\Log;

class SendSupervisorIncidentAlert
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
    public function handle(IncidentCreated $event): void
    {
        $incident = $event->incident;

        // Only send if supervisor is assigned
        if (!$incident->assigned_supervisor_id) {
            return;
        }

        $supervisor = $incident->assignedSupervisor;

        if (!$supervisor) {
            return;
        }

        try {
            // Send email notification
            $supervisor->notify(new IncidentSupervisorAlertNotification($incident));

            // Log the email communication
            CommunicationLogger::logEmail(
                'INCIDENT_CONFIRMATION',
                $supervisor->email,
                "Incident Alert - HSC Reference: {$incident->reference_number}",
                "New incident report submitted by {$incident->first_name} {$incident->surname}",
                null,
                'SENT',
                null,
                'Incident',
                $incident->id,
                $supervisor->id
            );

            // Send SMS notification if phone number exists
            if (!empty($supervisor->phone)) {
                $workerName = trim(($incident->first_name ?? '') . ' ' . ($incident->surname ?? ''));
                $facilityName = $incident->facility->name ?? 'Unknown Facility';
                
                $smsSent = $this->smsService->sendIncidentAlertToSupervisor(
                    $supervisor->phone,
                    $supervisor->first_name ?? 'Supervisor',
                    $workerName,
                    $incident->reference_number,
                    $facilityName
                );

                // Log SMS communication
                CommunicationLogger::logSMS(
                    'SUPERVISOR_INCIDENT_ALERT',
                    $supervisor->phone,
                    "New incident requires your attention. Employee: {$workerName}, Reference: {$incident->reference_number}",
                    $smsSent ? 'SENT' : 'FAILED',
                    $smsSent ? null : 'SMS sending failed',
                    'Incident',
                    $incident->id,
                    $supervisor->id
                );

                if ($smsSent) {
                    Log::info("SMS alert sent to supervisor", [
                        'incident_id' => $incident->id,
                        'supervisor_id' => $supervisor->id,
                        'phone' => $supervisor->phone,
                    ]);
                }
            }

            // Log the notification
            IncidentNotification::create([
                'incident_id' => $incident->id,
                'recipient_user_id' => $supervisor->id,
                'notification_type' => 'SUPERVISOR_ALERT',
                'notification_channel' => 'email,sms',
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            Log::info("Supervisor notified of new incident", [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'supervisor_id' => $supervisor->id,
                'email_sent' => true,
                'sms_sent' => !empty($supervisor->phone),
            ]);
        } catch (\Exception $e) {
            // Log failure to communication logs
            CommunicationLogger::logEmail(
                'INCIDENT_CONFIRMATION',
                $supervisor->email,
                "Incident Alert - HSC Reference: {$incident->reference_number}",
                "New incident report submitted by {$incident->first_name} {$incident->surname}",
                null,
                'FAILED',
                $e->getMessage(),
                'Incident',
                $incident->id,
                $supervisor->id
            );

            // Log failure
            IncidentNotification::create([
                'incident_id' => $incident->id,
                'recipient_user_id' => $supervisor->id,
                'notification_type' => 'SUPERVISOR_ALERT',
                'notification_channel' => 'email',
                'status' => 'FAILED',
                'sent_at' => now(),
                'failure_reason' => $e->getMessage(),
            ]);

            Log::error('Failed to send supervisor incident alert', [
                'incident_id' => $incident->id,
                'supervisor_id' => $supervisor->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
