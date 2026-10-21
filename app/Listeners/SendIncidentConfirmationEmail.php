<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Mail\IncidentConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        // Reload incident with relationships to ensure all data is loaded
        $incident = $incident->load(['department', 'facility', 'designation', 'province', 'incidentType', 'witnessTokens']);

        // Get witness tokens for this incident
        $witnessTokens = $incident->witnessTokens;

        try {
            // Send confirmation email using Mailable
            Mail::to($incident->email)
                ->send(new IncidentConfirmationMail($incident, $witnessTokens));

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
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}

