<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $temporaryPassword;

    public function __construct($temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Password Has Been Reset')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your password has been reset by a system administrator.')
            ->line('You can now log in with the temporary password below:')
            ->line('**Temporary Password:** ' . $this->temporaryPassword)
            ->line('Please log in and change your password immediately.')
            ->action('Log In to the System', url('/login'))
            ->line('For security reasons, this temporary password will expire after your first login.')
            ->line('If you did not request this password reset, please contact the system administrator immediately.')
            ->salutation('Best regards,')
            ->markdown('notifications.password-reset', [
                'user' => $notifiable,
                'temporaryPassword' => $this->temporaryPassword,
            ]);
    }
}
