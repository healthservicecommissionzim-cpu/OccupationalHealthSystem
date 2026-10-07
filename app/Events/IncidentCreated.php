<?php

namespace App\Events;

use App\Models\Incident;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Incident $incident;

    /**
     * Create a new event instance.
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }
}
