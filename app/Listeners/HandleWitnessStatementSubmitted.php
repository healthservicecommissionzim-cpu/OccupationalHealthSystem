<?php

namespace App\Listeners;

use App\Events\WitnessStatementSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleWitnessStatementSubmitted implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * 
     * This listener handles witness statement submissions and can trigger:
     * - Supervisor notifications (email/dashboard badge) in future
     * - Updates to incident status if all witnesses have submitted
     * - Audit logging of witness submission
     */
    public function handle(WitnessStatementSubmitted $event): void
    {
        $incident = $event->incident;
        $witnessToken = $event->witnessToken;

        // Log the witness statement submission for audit purposes
        Log::info('Witness statement submitted', [
            'incident_id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'witness_name' => $witnessToken->witness_name,
            'witness_token' => substr($witnessToken->token, 0, 8) . '...', // Log partial token for security
            'submission_timestamp' => $witnessToken->statement_submitted_at,
        ]);

        // Check if all witnesses for this incident have submitted
        $totalWitnesses = $incident->witnessTokens()->count();
        $submittedWitnesses = $incident->witnessTokens()
            ->whereNotNull('statement_submitted_at')
            ->count();

        // If all witnesses have submitted, you could trigger additional actions here
        if ($totalWitnesses > 0 && $submittedWitnesses === $totalWitnesses) {
            Log::info('All witnesses have submitted statements', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'total_witnesses' => $totalWitnesses,
            ]);
            // Future enhancement: trigger supervisor notification
        }
    }
}
