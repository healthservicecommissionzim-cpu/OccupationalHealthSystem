<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReplyReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public SupportTicket $ticket, public string $replyMessage)
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
        $mailMessage = (new MailMessage)
            ->subject("New Reply on Support Ticket - {$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("There is a new reply on your support ticket #{$this->ticket->ticket_number}.")
            ->line("**Ticket Title:** {$this->ticket->title}")
            ->line("**Category:** {$this->ticket->category}")
            ->line("**Super Admin Reply:**")
            ->line($this->replyMessage)
            ->action('View Full Conversation', route('support.ticket.show', $this->ticket->id))
            ->line('Thank you for using our system.');

        // Log the email communication
        CommunicationLogger::logEmail(
            'TICKET_REPLY',
            $notifiable->email,
            "New Reply on Support Ticket - {$this->ticket->ticket_number}",
            "New reply on your support ticket: " . substr($this->replyMessage, 0, 100),
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
            'message' => "New reply on ticket {$this->ticket->ticket_number}",
            'reply_preview' => substr($this->replyMessage, 0, 100) . '...',
            'notification_type' => 'REPLY_RECEIVED',
            'action_url' => route('support.ticket.show', $this->ticket->id),
        ];
    }
}
