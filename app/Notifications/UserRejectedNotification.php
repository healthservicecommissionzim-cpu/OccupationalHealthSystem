<?php

namespace App\Notifications;

use App\Models\User;
use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserRejectedNotification extends Notification
{
    use Queueable;

    protected User $user;
    protected string $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $rejectionReason)
    {
        $this->user = $user;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $reapplyDate = $notifiable->can_reapply_at?->format('M d, Y') ?? 'N/A';

        $mailMessage = (new MailMessage)
            ->subject('Your Account Registration Was Not Approved')
            ->greeting("Hello {$this->user->name},")
            ->line('Unfortunately, your account registration was not approved.')
            ->line('**Reason:**')
            ->line($this->rejectionReason)
            ->line("You can reapply for an account on **{$reapplyDate}** or later.")
            ->line('If you believe this is an error or would like to appeal this decision, please contact support.')
            ->salutation('Best regards,');

        // Log the email
        CommunicationLogger::logEmail(
            'REJECTION',
            $this->user->email,
            'Your Account Registration Was Not Approved',
            'Your account registration was not approved. Reason: ' . $this->rejectionReason,
            $mailMessage->render(),
            'SENT',
            null,
            'User',
            $this->user->id,
            $this->user->id
        );

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'rejection_reason' => $this->rejectionReason,
            'message' => 'Your account registration was not approved.',
        ];
    }
}
