<?php

namespace App\Notifications;

use App\Models\Incident;
use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentUploadedNotification extends Notification
{
    use Queueable;

    protected Incident $incident;
    protected string $documentType;
    protected int $uploadCount;
    protected string $dashboardUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident, string $documentType, int $uploadCount = 1)
    {
        $this->incident = $incident;
        $this->documentType = $documentType;
        $this->uploadCount = $uploadCount;
        $this->dashboardUrl = route('incidents.document_verification', ['incident' => $incident->id]);
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $workerName = "{$this->incident->first_name} {$this->incident->surname}";
        $facilityName = $this->incident->facility->name ?? 'N/A';
        $documentLabel = $this->getDocumentLabel();
        $uploadMessage = $this->uploadCount > 1 
            ? "{$this->uploadCount} new document(s) have been uploaded" 
            : "A new document has been uploaded";

        $mailMessage = (new MailMessage)
            ->subject("Documents Uploaded - HSC Reference: {$this->incident->reference_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("New documents have been submitted for incident verification.")
            ->line("**Incident Details:**")
            ->line("- Reference: {$this->incident->reference_number}")
            ->line("- Worker: {$workerName}")
            ->line("- Facility: {$facilityName}")
            ->line("")
            ->line("**Update:**")
            ->line("- {$uploadMessage}: {$documentLabel}")
            ->line("- Uploaded: " . now()->format('M d, Y H:i'))
            ->line("")
            ->line("**Action Required:**")
            ->line("Please log in to the HSC system to review and verify the uploaded documents.")
            ->action('Review Documents', $this->dashboardUrl)
            ->line("All documents must be verified before proceeding with case processing.")
            ->salutation('Regards, HSC Occupational Health & Safety System');

        // Log the email
        CommunicationLogger::logEmail(
            'DOCUMENT_UPLOAD',
            $notifiable->email,
            "Documents Uploaded - HSC Reference: {$this->incident->reference_number}",
            "{$uploadMessage} for incident {$this->incident->reference_number}",
            $mailMessage->render(),
            'SENT',
            null,
            'Incident',
            $this->incident->id,
            $notifiable->id
        );

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'incident_id' => $this->incident->id,
            'reference_number' => $this->incident->reference_number,
            'worker_name' => "{$this->incident->first_name} {$this->incident->surname}",
            'document_type' => $this->documentType,
            'upload_count' => $this->uploadCount,
            'message' => "{$this->uploadCount} document(s) uploaded for incident {$this->incident->reference_number}",
            'action_url' => $this->dashboardUrl,
        ];
    }

    /**
     * Get human-readable document type label.
     */
    private function getDocumentLabel(): string
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

        return $labels[$this->documentType] ?? ucfirst(str_replace('_', ' ', $this->documentType));
    }
}
