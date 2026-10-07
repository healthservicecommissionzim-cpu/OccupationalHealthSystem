<?php

namespace App\Events;

use App\Models\Incident;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentEscalatedToHR
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Incident $incident;
    public ?int $hrGroupId;
    public string $routeType;

    /**
     * Create a new event instance.
     * Fires when an incident is escalated from supervisor to HR after HOD report submission.
     */
    public function __construct(Incident $incident, ?int $hrGroupId, string $routeType)
    {
        $this->incident = $incident;
        $this->hrGroupId = $hrGroupId;
        $this->routeType = $routeType;
    }
}
