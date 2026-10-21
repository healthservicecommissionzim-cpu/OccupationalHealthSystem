<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Role;
use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserApprovedNotification extends Notification
{
    use Queueable;

    protected User $user;
    protected Role $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
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
        $loginUrl = route('login');

        $mailMessage = (new MailMessage)
            ->subject('Your Account Has Been Approved')
            ->greeting("Hello {$this->user->name},")
            ->line('Your account registration has been approved!')
            ->line("You have been assigned the role: **{$this->role->display_name}**")
            ->line('You can now log in to your account and access the system.')
            ->action('Log In', $loginUrl)
            ->line('If you did not request this approval, please contact support immediately.')
            ->salutation('Best regards,');

        // Log the email
        CommunicationLogger::logEmail(
            'APPROVAL',
            $this->user->email,
            'Your Account Has Been Approved',
            'Your account registration has been approved and you can now log in.',
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
            'role_id' => $this->role->id,
            'role_name' => $this->role->display_name,
            'message' => "Your account has been approved with role: {$this->role->display_name}",
        ];
    }
}
