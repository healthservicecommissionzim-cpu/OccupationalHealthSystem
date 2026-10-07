<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Models\IncidentNotification;
use App\Notifications\IncidentSupervisorAlertNotification;

class SendSupervisorIncidentAlert
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
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
            // Send notification
            $supervisor->notify(new IncidentSupervisorAlertNotification($incident));

            // Log the notification
            IncidentNotification::create([
                'incident_id' => $incident->id,
                'recipient_user_id' => $supervisor->id,
                'notification_type' => 'SUPERVISOR_ALERT',
                'notification_channel' => 'email',
                'status' => 'SENT',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
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
        }
    }
}
