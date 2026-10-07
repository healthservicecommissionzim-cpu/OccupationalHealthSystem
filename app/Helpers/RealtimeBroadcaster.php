<?php

namespace App\Helpers;

use App\Events\IncidentUpdated;
use App\Models\Incident;

class RealtimeBroadcaster
{
    /**
     * Broadcast a status change
     */
    public static function statusChanged(Incident $incident, string $oldStatus, string $newStatus): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'status_change',
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
            "Status changed from {$oldStatus} to {$newStatus}"
        );
    }

    /**
     * Broadcast a document upload
     */
    public static function documentUploaded(Incident $incident, string $documentType, string $fileName): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'document_upload',
            [
                'document_type' => $documentType,
                'file_name' => $fileName,
            ],
            "Document uploaded: {$documentType} - {$fileName}"
        );
    }

    /**
     * Broadcast a recommendation
     */
    public static function recommendationSubmitted(Incident $incident, string $recommendedBy): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'recommendation',
            [
                'recommended_by' => $recommendedBy,
            ],
            "Executive recommendation submitted by {$recommendedBy}"
        );
    }

    /**
     * Broadcast an escalation
     */
    public static function escalated(Incident $incident, string $escalatedTo, string $escalatedBy = null): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'escalation',
            [
                'escalated_to' => $escalatedTo,
                'escalated_by' => $escalatedBy,
            ],
            "Case escalated to {$escalatedTo}"
        );
    }

    /**
     * Broadcast verification
     */
    public static function verified(Incident $incident, string $verifiedBy): void
    {
        IncidentUpdated::dispatch(
            $incident,
            'verification',
            [
                'verified_by' => $verifiedBy,
            ],
            "Case verified by {$verifiedBy}"
        );
    }

    /**
     * Generic broadcast
     */
    public static function broadcast(Incident $incident, string $type, array $changes, string $message): void
    {
        IncidentUpdated::dispatch($incident, $type, $changes, $message);
    }
}
