<?php

namespace App\Listeners;

use App\Events\IncidentWithWitnessesSubmitted;
use App\Services\WitnessSMSService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendWitnessNotifications implements ShouldQueue
{
    private WitnessSMSService $witnessSMSService;

    /**
     * Create the event listener.
     */
    public function __construct(WitnessSMSService $witnessSMSService)
    {
        $this->witnessSMSService = $witnessSMSService;
    }

    /**
     * Handle the event.
     * 
     * Dispatches SMS notifications to all witnesses for the incident.
     * Updates token_sent_at on successful sends.
     * Logs errors without failing form submission.
     * 
     * @param IncidentWithWitnessesSubmitted $event
     * @return void
     */
    public function handle(IncidentWithWitnessesSubmitted $event): void
    {
        $incident = $event->incident;
        $witnesses = $event->witnesses;

        // Log the event being handled
        Log::info('Processing witness SMS notifications', [
            'incident_id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'witness_count' => $witnesses->count(),
        ]);

        // If no witnesses, nothing to do
        if ($witnesses->isEmpty()) {
            Log::warning('No witnesses provided for incident', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
            ]);
            return;
        }

        try {
            // Send initial SMS notifications to all witnesses
            $results = $this->witnessSMSService->sendInitialNotifications($incident);

            // Log overall results
            Log::info('Witness SMS notifications completed', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'success_count' => $results['success'],
                'failed_count' => $results['failed'],
                'errors' => $results['errors'],
            ]);

            // Note: We don't throw exceptions even if some SMS failed
            // This ensures the incident submission is not blocked
            if ($results['failed'] > 0) {
                Log::warning('Some witness SMS notifications failed', [
                    'incident_id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'failed_count' => $results['failed'],
                    'errors' => $results['errors'],
                ]);
            }

        } catch (\Exception $e) {
            // Log the error but don't throw - we don't want to block form submission
            Log::error('Exception handling witness SMS notifications', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Note: Exception is caught and logged, but not re-thrown
            // This ensures the incident submission completes successfully
            // even if SMS dispatch fails
        }
    }

    /**
     * Handle a job failure.
     * 
     * @param IncidentWithWitnessesSubmitted $event
     * @param \Throwable $exception
     * @return void
     */
    public function failed(IncidentWithWitnessesSubmitted $event, \Throwable $exception): void
    {
        Log::error('Witness notification job failed', [
            'incident_id' => $event->incident->id,
            'reference_number' => $event->incident->reference_number,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
