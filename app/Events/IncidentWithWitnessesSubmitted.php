<?php

namespace App\Events;

use App\Models\Incident;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class IncidentWithWitnessesSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Incident $incident;
    public Collection $witnesses;

    /**
     * Create a new event instance.
     * Fires when an incident is submitted with witness information.
     * 
     * @param Incident $incident The incident that was submitted
     * @param Collection $witnesses Collection of IncidentWitnessToken models
     */
    public function __construct(Incident $incident, Collection $witnesses)
    {
        $this->incident = $incident;
        $this->witnesses = $witnesses;
    }
}
