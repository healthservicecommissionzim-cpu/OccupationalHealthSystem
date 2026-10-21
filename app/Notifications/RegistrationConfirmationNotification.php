<?php

namespace App\Notifications;

use App\Services\CommunicationLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
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
            ->subject('Welcome to HSC-OHS - Account Registration Confirmation')
            ->greeting("Hello {$this->user->name},")
            ->line('Your account has been successfully created on the HSC-OHS platform.')
            ->line('**Account Details:**')
            ->line("• Email: {$this->user->email}")
            ->line("• Phone: {$this->user->phone_number}")
            ->line("• Status: Awaiting Admin Approval")
            ->line('')
            ->line('**Next Steps:**')
            ->line('1. Your account is currently pending administrator verification')
            ->line('2. You will receive an SMS notification once your account is approved')
            ->line('3. After approval, you will be able to log in and access the full system')
            ->line('4. If you have any questions, please contact your facility administrator or the HSC support team')
            ->line('')
            ->line('Thank you for registering with HSC-OHS. We are committed to supporting the occupational health and safety of Zimbabwe\'s healthcare workforce.')
            ->action('Visit HSC-OHS', config('app.url'))
            ->line('Best regards,')
            ->line('HSC-OHS Support Team');

        // Log the email
        CommunicationLogger::logEmail(
            'REGISTRATION',
            $this->user->email,
            'Welcome to HSC-OHS - Account Registration Confirmation',
            'Your account has been successfully created and is awaiting admin approval.',
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
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Account Registration Confirmation',
            'message' => 'Your HSC-OHS account has been successfully created and is awaiting admin approval.',
            'type' => 'REGISTRATION',
            'user_id' => $this->user->id,
        ];
    }
}
