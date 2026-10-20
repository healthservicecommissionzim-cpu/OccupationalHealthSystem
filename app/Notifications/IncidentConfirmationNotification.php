<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class IncidentConfirmationNotification extends Notification
{
    use Queueable;

    protected Incident $incident;
    protected Collection $witnessTokens;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident, Collection $witnessTokens)
    {
        $this->incident = $incident;
        $this->witnessTokens = $witnessTokens;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reporterName = $this->incident->first_name;
        $facilityName = $this->incident->facility->name ?? 'N/A';
        $incidentDate = $this->incident->incident_date ? \Carbon\Carbon::parse($this->incident->incident_date)->format('M d, Y') : 'N/A';
        $trackingUrl = route('incidents.track', ['reference' => $this->incident->reference_number]);

        $mail = (new MailMessage)
            ->subject("Incident Report Submitted - Reference: {$this->incident->reference_number}")
            ->greeting("Hello {$reporterName},")
            ->line("Your incident report has been successfully submitted to the Health Service Commission.")
            ->line("")
            ->line("**Incident Details:**")
            ->line("- Reference Number: **{$this->incident->reference_number}**")
            ->line("- Incident Date: {$incidentDate}")
            ->line("- Facility: {$facilityName}")
            ->line("- Status: Pending Supervisor Verification")
            ->line("")
            ->line("**What Happens Next:**")
            ->line("1. Your report will be reviewed by your supervisor")
            ->line("2. You can track the status anytime using your reference number")
            ->line("3. Witness statements must be submitted to move the case forward")
            ->line("")
            ->action('Track Your Report', $trackingUrl)
            ->line("")
            ->line("**Witness Statement Links:**");

        // Add witness statement links
        if ($this->witnessTokens->isNotEmpty()) {
            $mail->line("The following witness(es) need to submit their statements. Please provide them with the appropriate links below:");
            $mail->line("");

            $this->witnessTokens->each(function($witnessToken, $index) use (&$mail) {
                $witnessName = $witnessToken->witness_name;
                $witnessUrl = route('incidents.witness_statement', ['token' => $witnessToken->token]);
                $mail->line("**Witness " . ($index + 1) . ": {$witnessName}**");
                $mail->action("Submit Statement", $witnessUrl);
                $mail->line("");
            });
        } else {
            $mail->line("No witnesses have been registered for this incident.");
            $mail->line("");
        }

        return $mail
            ->line("**Important Notes:**")
            ->line("- Keep this email for your records")
            ->line("- Your reference number is needed for all future inquiries")
            ->line("- Incident reports must be submitted within 12 months of the incident date")
            ->line("")
            ->salutation('Regards,')
            ->salutation('Health Service Commission - Occupational Health & Safety System');
    }
}
