<?php

namespace App\Mail;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class IncidentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Incident $incident;
    public Collection $witnessTokens;

    /**
     * Create a new message instance.
     */
    public function __construct(Incident $incident, Collection $witnessTokens)
    {
        $this->incident = $incident;
        $this->witnessTokens = $witnessTokens;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Incident Report Submitted - Reference: {$this->incident->reference_number}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.incident-confirmation',
            with: [
                'incident' => $this->incident,
                'witnessTokens' => $this->witnessTokens,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        try {
            // Generate PDF using DomPDF
            $pdf = \PDF::loadView('pdfs.incident-confirmation', [
                'incident' => $this->incident,
            ]);

            // Store PDF temporarily
            $filename = 'incident-confirmation-' . $this->incident->reference_number . '.pdf';
            $pdfPath = storage_path('app/temp/' . $filename);
            
            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $pdf->save($pdfPath);

            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($pdfPath)
                    ->as($filename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate PDF for incident confirmation email', [
                'incident_id' => $this->incident->id,
                'error' => $e->getMessage(),
            ]);
            
            // Return no attachments if PDF generation fails
            // Email will still be sent without PDF
            return [];
        }
    }
}
