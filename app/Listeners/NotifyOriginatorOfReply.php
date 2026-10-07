<?php

namespace App\Listeners;

use App\Events\TicketReplyAdded;
use App\Notifications\ReplyReceivedNotification;
use App\Models\TicketNotification;
use App\Services\SMSService;
use Illuminate\Support\Facades\Log;

class NotifyOriginatorOfReply
{
    /**
     * Handle the TicketReplyAdded event and notify originator.
     */
    public function handle(TicketReplyAdded $event): void
    {
        try {
            // Create notification record in database
            $notification = TicketNotification::create([
                'support_ticket_id' => $event->ticket->id,
                'recipient_user_id' => $event->originatorUser->id,
                'notification_type' => 'REPLY',
                'notification_channel' => 'email',
                'content' => $event->reply->message,
                'status' => 'PENDING',
            ]);

            // Send email notification to originator
            $event->originatorUser->notify(new ReplyReceivedNotification($event->ticket, $event->reply->message));

            // Send SMS notification if enabled and phone number exists
            if (config('services.sms.enabled', false) && $event->originatorUser->phone_number) {
                try {
                    $smsService = new SMSService();
                    $smsMessage = "HSC-OHS: New reply on support ticket {$event->ticket->ticket_number}. "
                                . "Title: {$event->ticket->title}. "
                                . "Please log in to review. Visit {$this->getSiteHost()}";
                    
                    if ($smsService->send($event->originatorUser->phone_number, $smsMessage)) {
                        Log::info('SMS notification sent to originator of reply', [
                            'ticket_id' => $event->ticket->id,
                            'ticket_number' => $event->ticket->ticket_number,
                            'originator_id' => $event->originatorUser->id,
                            'originator_phone' => $event->originatorUser->phone_number,
                        ]);
                    } else {
                        Log::warning('Failed to send SMS to originator', [
                            'ticket_id' => $event->ticket->id,
                            'originator_id' => $event->originatorUser->id,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('SMS service error for ticket reply notification', [
                        'ticket_id' => $event->ticket->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Update notification status to SENT
            $notification->update([
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            Log::info('Originator notified of reply', [
                'ticket_id' => $event->ticket->id,
                'ticket_number' => $event->ticket->ticket_number,
                'originator_id' => $event->originatorUser->id,
                'originator_email' => $event->originatorUser->email,
                'notification_id' => $notification->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify originator of reply', [
                'ticket_id' => $event->ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the site host from configuration.
     */
    private function getSiteHost(): string
    {
        return config('services.sms.site_host', 'injury.hsc.org.zw');
    }
}
