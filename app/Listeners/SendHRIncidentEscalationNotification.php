<?php

namespace App\Listeners;

use App\Events\IncidentEscalatedToHR;
use App\Models\IncidentNotification;
use App\Notifications\IncidentHRReviewNotification;
use App\Models\User;
use App\Services\CommunicationLogger;
use Illuminate\Support\Facades\Log;

class SendHRIncidentEscalationNotification
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
            // Send notification to the specifically assigned HR user only
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

            // Log the notification in database
            IncidentNotification::create([
                'incident_id' => $incident->id,
                'recipient_user_id' => $assignedHRUser->id,
                'notification_type' => 'HR_ESCALATION',
                'notification_channel' => 'in_app',
                'status' => 'UNREAD',
            ]);

            Log::info("Incident escalated to assigned HR user successfully", [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'review_pool' => $reviewPool,
                'assigned_hr_user_id' => $assignedHRUser->id,
                'assigned_hr_user_name' => $assignedHRUser->name,
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
