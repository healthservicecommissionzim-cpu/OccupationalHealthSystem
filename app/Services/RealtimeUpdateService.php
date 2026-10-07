<?php

namespace App\Services;

use App\Events\IncidentUpdated;
use App\Models\Incident;

class RealtimeUpdateService
{
    /**
     * Broadcast incident status change
     */
    public static function broadcastStatusChange(Incident $incident, string $oldStatus, string $newStatus): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'status_change',
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
            "Case status updated from {$oldStatus} to {$newStatus}"
        );
    }

    /**
     * Broadcast document upload
     */
    public static function broadcastDocumentUpload(Incident $incident, string $documentType): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'document_upload',
            [
                'document_type' => $documentType,
            ],
            "New document uploaded: {$documentType}"
        );
    }

    /**
     * Broadcast recommendation submission
     */
    public static function broadcastRecommendation(Incident $incident, string $recommendationType): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'recommendation',
            [
                'recommendation_type' => $recommendationType,
            ],
            "Executive recommendation submitted: {$recommendationType}"
        );
    }

    /**
     * Broadcast case escalation
     */
    public static function broadcastEscalation(Incident $incident, string $escalatedTo): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'escalation',
            [
                'escalated_to' => $escalatedTo,
            ],
            "Case escalated to: {$escalatedTo}"
        );
    }

    /**
     * Broadcast generic update
     */
    public static function broadcastUpdate(Incident $incident, string $updateType, array $changes, string $message): void
    {
        IncidentUpdated::dispatch($incident, $updateType, $changes, $message);
    }
}
