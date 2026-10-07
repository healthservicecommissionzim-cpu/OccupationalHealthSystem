<?php

namespace App\Mail;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExecutiveRecommendationLetter extends Mailable
{
    use Queueable, SerializesModels;

    public Incident $incident;
    public string $letterHtml;
    public string $customMessage;

    public function __construct(Incident $incident, string $letterHtml, string $customMessage = '')
    {
        $this->incident = $incident;
        $this->letterHtml = $letterHtml;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        $workerName = "{$this->incident->first_name} {$this->incident->surname}";
        $subject = "Executive Recommendation Letter - Case {$this->incident->reference_number} - Worker: {$workerName}";
        
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.executive_letter',
            with: [
                'incident' => $this->incident,
                'customMessage' => $this->customMessage,
            ]
        );
    }

    public function attachments(): array
    {
        try {
            // Generate PDF from HTML
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($this->letterHtml);
            
            // Create specific filename with case reference and recipient
            $recipientName = preg_replace('/[^a-zA-Z0-9]/', '_', $this->incident->first_name . '_' . $this->incident->surname);
            $fileName = "Executive_Recommendation_{$this->incident->reference_number}_for_{$recipientName}.pdf";
            
            return [
                Attachment::fromData(fn () => $pdf->output(), $fileName)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate PDF attachment', [
                'incident_id' => $this->incident->id,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
}
