<?php

namespace App\Listeners;

use App\Events\IncidentDocumentUploaded;
use App\Models\IncidentNotification;
use App\Notifications\DocumentUploadedNotification;
use App\Services\SMSService;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendDocumentUploadNotification
{
    private SMSService $smsService;

    /**
     * Create the event listener.
     */
    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(IncidentDocumentUploaded $event): void
    {
        $incident = $event->incident;

        // Check if incident is in a state where HR needs to be notified
        $notifiableStatuses = [
            'VERIFIED_SUPERVISOR',           // HR review pending
            'HR_RETURNED_FOR_CORRECTIONS',   // Documents being re-uploaded after rejection
            'PENDING_SUPERVISOR',            // Initial documents being uploaded
            'DRAFT',                         // Any early status
        ];

        if (!in_array($incident->status, $notifiableStatuses)) {
            Log::info('Document upload notification skipped - incorrect incident status', [
                'incident_id' => $incident->id,
                'status' => $incident->status,
            ]);
            return;
        }

        // Get the assigned HR user
        $hrUser = $incident->assignedHRUser;

        // If no specific HR user assigned yet, log and skip
        if (!$hrUser) {
            Log::info('Document upload notification skipped - no assigned HR user', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
            ]);
            return;
        }

        $workerName = "{$incident->first_name} {$incident->surname}";
        $documentLabel = $this->getDocumentLabel($event->documentType);
        $uploadMessage = $event->uploadCount > 1 
            ? "{$event->uploadCount} documents" 
            : "documents";

        try {
            // Send email notification to assigned HR user
            $hrUser->notify(new DocumentUploadedNotification($incident, $event->documentType, $event->uploadCount));

            // Send SMS notification if phone number exists
            if ($hrUser->phone_number) {
                $smsMessage = "HSC-OHS: New documents uploaded for incident {$incident->reference_number} - Worker: {$workerName}. Please log in to review. Visit injury.hsc.org.zw";
                
                $smsSent = $this->smsService->send($hrUser->phone_number, $smsMessage);
                
                if ($smsSent) {
                    Log::info('SMS notification sent to assigned HR', [
                        'incident_id' => $incident->id,
                        'hr_user_id' => $hrUser->id,
                        'phone' => substr($hrUser->phone_number, -4),
                    ]);
                } else {
                    Log::warning('Failed to send SMS notification to assigned HR', [
                        'incident_id' => $incident->id,
                        'hr_user_id' => $hrUser->id,
                        'phone' => substr($hrUser->phone_number, -4),
                    ]);
                }
            }

            // Log the notification
            IncidentNotification::create([
                'incident_id' => $incident->id,
                'recipient_user_id' => $hrUser->id,
                'notification_type' => 'DOCUMENT_UPLOAD',
                'notification_channel' => 'email',
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            Log::info('Document upload notification sent to assigned HR', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'document_type' => $event->documentType,
                'upload_count' => $event->uploadCount,
                'hr_user_id' => $hrUser->id,
                'hr_user_name' => $hrUser->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send document upload notification', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'document_type' => $event->documentType,
                'hr_user_id' => $hrUser->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get human-readable document type label.
     */
    private function getDocumentLabel(string $documentType): string
    {
        $labels = [
            'doctors_report' => "Doctor's Report",
            'injury_report' => 'Injury Report from Z1149',
            'sick_leave' => 'Sick Leave Forms',
            'treatment_records' => 'Treatment Records',
            'national_id' => 'National Registration Card',
            'banking_details' => 'Banking Details Forms',
            'bank_statement' => 'Bank Statements',
            'police_report' => 'Police Report',
            'death_certificate' => 'Death Certificate',
            'heir_certificate' => 'Heir Certificate',
            'db8_form' => 'DB8 Form',
            'termination_letter' => 'Termination Letter',
            'marriage_certificate' => 'Marriage Certificate',
        ];

        return $labels[$documentType] ?? ucfirst(str_replace('_', ' ', $documentType));
    }
}
