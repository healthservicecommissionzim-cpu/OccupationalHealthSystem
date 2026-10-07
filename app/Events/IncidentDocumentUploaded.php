<?php

namespace App\Events;

use App\Models\Incident;
use App\Models\IncidentAttachment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentDocumentUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Incident $incident;
    public IncidentAttachment $attachment;
    public string $documentType;
    public int $uploadCount;

    /**
     * Create a new event instance.
     * Fires when a worker/reporter uploads a document for HR review.
     */
    public function __construct(Incident $incident, IncidentAttachment $attachment, string $documentType, int $uploadCount = 1)
    {
        $this->incident = $incident;
        $this->attachment = $attachment;
        $this->documentType = $documentType;
        $this->uploadCount = $uploadCount;
    }
}
