<?php

namespace App\Listeners;

use App\Events\TicketStatusChanged;
use App\Notifications\StatusChangedNotification;
use App\Models\TicketNotification;
use App\Services\SMSService;
use App\Services\CommunicationLogger;
use Illuminate\Support\Facades\Log;

class NotifyOriginatorOfStatusChange
{
    /**
     * Handle the TicketStatusChanged event and notify originator.
     */
    public function handle(TicketStatusChanged $event): void
    {
        try {
            // Create notification record in database
            $notification = TicketNotification::create([
                'support_ticket_id' => $event->ticket->id,
                'recipient_user_id' => $event->originatorUser->id,
                'notification_type' => 'STATUS_CHANGE',
                'notification_channel' => 'email',
                'content' => "Status changed from {$event->oldStatus} to {$event->newStatus}",
                'status' => 'PENDING',
            ]);

            // Send email notification to originator
            $event->originatorUser->notify(new StatusChangedNotification($event->ticket, $event->oldStatus, $event->newStatus));

            // Log the email communication
            CommunicationLogger::logEmail(
                'TICKET_STATUS_CHANGE',
                $event->originatorUser->email,
                "Support Ticket {$event->ticket->ticket_number} Status Updated",
                "Your support ticket status has been changed from {$event->oldStatus} to {$event->newStatus}",
                null,
                'SENT',
                null,
                'SupportTicket',
                $event->ticket->id,
                $event->originatorUser->id
            );

            // Send SMS notification if enabled and phone number exists
            if (config('services.sms.enabled', false) && $event->originatorUser->phone_number) {
                try {
                    $smsService = new SMSService();
                    $smsMessage = "HSC-OHS: Ticket {$event->ticket->ticket_number} status updated to {$event->newStatus}. "
                                . "Please log in for details. Visit {$this->getSiteHost()}";
                    
                    if ($smsService->send($event->originatorUser->phone_number, $smsMessage)) {
                        Log::info('SMS notification sent for status change', [
                            'ticket_id' => $event->ticket->id,
                            'ticket_number' => $event->ticket->ticket_number,
                            'originator_id' => $event->originatorUser->id,
                            'originator_phone' => $event->originatorUser->phone_number,
                            'new_status' => $event->newStatus,
                        ]);

                        // Log SMS communication
                        CommunicationLogger::logSms(
                            'TICKET_STATUS_CHANGE',
                            $event->originatorUser->phone_number,
                            $smsMessage,
                            'SENT',
                            null,
                            'SupportTicket',
                            $event->ticket->id,
                            $event->originatorUser->id
                        );
                    } else {
                        Log::warning('Failed to send SMS for status change', [
                            'ticket_id' => $event->ticket->id,
                            'originator_id' => $event->originatorUser->id,
                        ]);

                        // Log failed SMS
                        CommunicationLogger::logSms(
                            'TICKET_STATUS_CHANGE',
                            $event->originatorUser->phone_number,
                            $smsMessage,
                            'FAILED',
                            'SMS service returned false',
                            'SupportTicket',
                            $event->ticket->id,
                            $event->originatorUser->id
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('SMS service error for status change notification', [
                        'ticket_id' => $event->ticket->id,
                        'error' => $e->getMessage(),
                    ]);

                    // Log failed SMS
                    CommunicationLogger::logSms(
                        'TICKET_STATUS_CHANGE',
                        $event->originatorUser->phone_number,
                        $smsMessage ?? '',
                        'FAILED',
                        $e->getMessage(),
                        'SupportTicket',
                        $event->ticket->id,
                        $event->originatorUser->id
                    );
                }
            }

            // Update notification status to SENT
            $notification->update([
                'status' => 'SENT',
                'sent_at' => now(),
            ]);

            Log::info('Originator notified of status change', [
                'ticket_id' => $event->ticket->id,
                'ticket_number' => $event->ticket->ticket_number,
                'originator_id' => $event->originatorUser->id,
                'originator_email' => $event->originatorUser->email,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'notification_id' => $notification->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify originator of status change', [
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
