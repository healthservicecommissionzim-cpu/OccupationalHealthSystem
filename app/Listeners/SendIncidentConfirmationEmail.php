<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Notifications\IncidentConfirmationNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendIncidentConfirmationEmail
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

        // Get witness tokens for this incident
        $witnessTokens = $incident->witnessTokens()->get();

        try {
            // Send confirmation notification to the reporter's email
            Notification::route('mail', $incident->email)
                ->notify(new IncidentConfirmationNotification($incident, $witnessTokens));

            Log::info('Incident confirmation email sent successfully', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'reporter_email' => $incident->email,
                'witness_count' => $witnessTokens->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send incident confirmation email', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'reporter_email' => $incident->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

