<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentHRReviewNotification extends Notification
{
    use Queueable;

    protected Incident $incident;
    protected string $reviewPool;
    protected string $dashboardUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident, string $reviewPool)
    {
        $this->incident = $incident;
        $this->reviewPool = $reviewPool;

        // Determine dashboard URL based on review pool (all HR roles use unified HR dashboard)
        $this->dashboardUrl = route('dashboards.hr');
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
        $departmentName = $this->incident->department->name ?? 'N/A';
        $incidentDate = $this->incident->incident_date->format('M d, Y');
        $poolLabel = str_replace('_', ' ', $this->reviewPool);

        return (new MailMessage)
            ->subject("Incident HR Review Required - HSC Reference: {$this->incident->reference_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("An incident report requires your HR review as part of the {$poolLabel} process.")
            ->line("**Incident Summary:**")
            ->line("- Reference: {$this->incident->reference_number}")
            ->line("- Worker: {$workerName}")
            ->line("- Department: {$departmentName}")
            ->line("- Date: {$incidentDate}")
            ->line("- Facility: {$facilityName}")
            ->line("")
            ->line("**Status:** Supervisor verification completed")
            ->line("- Supervisor Confirmation: On-duty confirmed")
            ->line("- Witness Statement: Documented")
            ->line("")
            ->line("**Your Action Required:**")
            ->line("Please log in to the HSC system to review the incident details and proceed with HR assessment.")
            ->action('Review Incident', $this->dashboardUrl)
            ->line("If you did not expect this notification, please contact the HSC Occupational Health & Safety team.")
            ->salutation('Regards, HSC Occupational Health & Safety System');
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
            'review_pool' => $this->reviewPool,
            'message' => "Incident {$this->incident->reference_number} requires HR review",
            'action_url' => $this->dashboardUrl,
        ];
    }
}
