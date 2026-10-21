<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Mail\IncidentConfirmationMail;
use App\Services\CommunicationLogger;
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

            // Log the email communication
            CommunicationLogger::logEmail(
                'INCIDENT_CONFIRMATION',
                $incident->email,
                "Incident Confirmation - HSC Reference: {$incident->reference_number}",
                "Thank you for submitting your incident report. Your reference number is {$incident->reference_number}",
                null,
                'SENT',
                null,
                'Incident',
                $incident->id,
                null
            );

            Log::info('Incident confirmation email sent successfully', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'reporter_email' => $incident->email,
                'witness_count' => $witnessTokens->count(),
            ]);

        } catch (\Exception $e) {
            // Log the failed email communication
            CommunicationLogger::logEmail(
                'INCIDENT_CONFIRMATION',
                $incident->email,
                "Incident Confirmation - HSC Reference: {$incident->reference_number}",
                "Thank you for submitting your incident report. Your reference number is {$incident->reference_number}",
                null,
                'FAILED',
                $e->getMessage(),
                'Incident',
                $incident->id,
                null
            );

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

