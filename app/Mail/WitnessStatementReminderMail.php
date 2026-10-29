<?php

namespace App\Mail;

use App\Models\Incident;
use App\Models\IncidentWitnessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WitnessStatementReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $witnessToken;
    public $incident;
    public $statementLink;

    /**
     * Create a new message instance.
     */
    public function __construct(IncidentWitnessToken $witnessToken, Incident $incident)
    {
        $this->witnessToken = $witnessToken;
        $this->incident = $incident;
        $this->statementLink = url("/incidents/{$incident->id}/witness/{$witnessToken->token}");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Witness Statement Required - {$this->incident->reference_number}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.witness_statement_reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
