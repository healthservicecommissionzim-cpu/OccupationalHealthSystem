<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public SupportTicket $ticket, public string $oldStatus, public string $newStatus)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $statusLabel = [
            'OPEN' => 'Open',
            'IN_PROGRESS' => 'In Progress',
            'RESOLVED' => 'Resolved',
            'CLOSED' => 'Closed',
        ];

        $oldStatusLabel = $statusLabel[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabel[$this->newStatus] ?? $this->newStatus;

        $mailMessage = (new MailMessage)
            ->subject("Support Ticket Status Updated - {$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("The status of your support ticket has been updated.")
            ->line("**Ticket:** {$this->ticket->ticket_number}")
            ->line("**Title:** {$this->ticket->title}")
            ->line("**Previous Status:** {$oldStatusLabel}")
            ->line("**New Status:** {$newStatusLabel}")
            ->action('View Ticket Details', route('support.ticket.show', $this->ticket->id))
            ->line('Thank you for your patience.');

        // Log the email communication
        CommunicationLogger::logEmail(
            'TICKET_STATUS_CHANGE',
            $notifiable->email,
            "Support Ticket Status Updated - {$this->ticket->ticket_number}",
            "Ticket status changed from {$oldStatusLabel} to {$newStatusLabel}",
            $mailMessage->render(),
            'SENT',
            null,
            'SupportTicket',
            $this->ticket->id,
            $notifiable->id
        );

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'message' => "Ticket {$this->ticket->ticket_number} status changed to {$this->newStatus}",
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'notification_type' => 'STATUS_CHANGED',
            'action_url' => route('support.ticket.show', $this->ticket->id),
        ];
    }
}
