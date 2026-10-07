<?php

namespace App\Mail;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveMilestoneNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Incident $incident,
        public User $medicalOfficer,
        public string $eventType
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $milestoneType = $this->eventType === 'App\Events\Case90DayMilestoneReached' ? '90-Day' : '180-Day';
        
        return new Envelope(
            subject: "Medical Board Review Request - {$milestoneType} Milestone - HSC Reference: {$this->incident->reference_number}"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $isCMO = $this->medicalOfficer->hasRole('cmo');
        $milestoneType = $this->eventType === 'App\Events\Case90DayMilestoneReached' ? '90-Day' : '180-Day';
        
        return new Content(
            view: 'emails.leave-milestone-notification',
            with: [
                'incident' => $this->incident,
                'medicalOfficer' => $this->medicalOfficer,
                'milestoneType' => $milestoneType,
                'daysElapsed' => $this->incident->days_elapsed,
                'paymentStage' => $this->incident->leave_payment_stage,
                'isCMO' => $isCMO,
                'recoveryDate' => $this->incident->expected_recovery_date?->format('Y-m-d'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
