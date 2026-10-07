<?php

namespace App\Events;

use App\Models\Incident;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Incident $incident;
    public string $updateType;
    public array $changes;
    public ?string $message;

    public function __construct(Incident $incident, string $updateType, array $changes = [], ?string $message = null)
    {
        $this->incident = $incident;
        $this->updateType = $updateType; // 'status_change', 'document_upload', 'recommendation', 'escalation'
        $this->changes = $changes;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("incident.{$this->incident->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'incident.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'incident_id' => $this->incident->id,
            'reference_number' => $this->incident->reference_number,
            'update_type' => $this->updateType,
            'changes' => $this->changes,
            'message' => $this->message,
            'timestamp' => now()->toIso8601String(),
            'status' => $this->incident->status,
        ];
    }
}
