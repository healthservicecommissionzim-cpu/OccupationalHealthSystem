<?php

namespace App\Notifications;

use App\Models\Incident;
use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentSupervisorAlertNotification extends Notification
{
    use Queueable;

    protected Incident $incident;
    protected string $dashboardUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
        $this->dashboardUrl = route('dashboards.supervisor');
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
        // Pre‑compute values to avoid inline null coalescing inside strings
        $departmentName = $this->incident->department->name ?? 'N/A';
        $incidentDate = $this->incident->incident_date->format('M d, Y');
        $incidentTime = $this->incident->incident_time;

        $mailMessage = (new MailMessage)
            ->subject("Incident Verification Required - HSC Reference: {$this->incident->reference_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("An incident report has been submitted requiring your verification as the Immediate Supervisor.")
            ->line("**Incident Details:**")
            ->line("- Reference: {$this->incident->reference_number}")
            ->line("- Worker: {$this->incident->first_name} {$this->incident->surname}")
            ->line("- Date/Time: {$incidentDate} at {$incidentTime}")
            ->line("- Department: {$departmentName}")
            ->line("")
            ->line("**Action Required:**")
            ->line("Please log in to the HSC system to review the incident details and submit your witness statement. This is required for HSB Circular No. 3 of 2017 compliance.")
            ->line("The required documents (NSSA Form W.C.A. 1 and initial medical treatment records) are awaiting your verification.")
            ->action('Review Incident', $this->dashboardUrl)
            ->line("If you did not expect this notification, please contact the HSC Occupational Health & Safety team immediately.")
            ->salutation('Regards, HSC Occupational Health & Safety System');

        // Log the email communication
        CommunicationLogger::logEmail(
            'INCIDENT_CONFIRMATION',
            $notifiable->email,
            "Incident Verification Required - HSC Reference: {$this->incident->reference_number}",
            "Incident {$this->incident->reference_number} requires your verification as Immediate Supervisor",
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
            'incident_date' => $this->incident->incident_date->format('Y-m-d H:i'),
            'message' => "Incident {$this->incident->reference_number} requires your verification as Immediate Supervisor",
            'action_url' => $this->dashboardUrl,
        ];
    }
}