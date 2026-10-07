<?php

namespace App\Events;

use App\Models\Incident;
use App\Models\IncidentWitnessToken;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WitnessStatementSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Incident $incident;
    public IncidentWitnessToken $witnessToken;
    public string $submissionTimestamp;

    /**
     * Create a new event instance.
     * Fires when a witness submits their statement via public link or document upload.
     */
    public function __construct(Incident $incident, IncidentWitnessToken $witnessToken, string $submissionTimestamp = null)
    {
        $this->incident = $incident;
        $this->witnessToken = $witnessToken;
        $this->submissionTimestamp = $submissionTimestamp ?? now()->toIso8601String();
    }
}
