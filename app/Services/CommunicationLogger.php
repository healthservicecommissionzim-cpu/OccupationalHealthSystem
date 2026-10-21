<?php

namespace App\Services;

use App\Models\CommunicationLog;
use Illuminate\Support\Facades\Log;

class CommunicationLogger
{
    /**
     * Log an email communication with delivery tracking
     */
    public static function logEmail(
        $type,
        $recipientEmail,
        $subject,
        $messagePreview = null,
        $fullContent = null,
        $status = 'SENT',
        $errorMessage = null,
        $relatedEntityType = null,
        $relatedEntityId = null,
        $recipientUserId = null,
        $provider = null,
        $providerMessageId = null,
        $providerMetadata = null
    ) {
        try {
            $log = CommunicationLog::create([
                'communication_type' => $type,
                'channel' => 'email',
                'recipient_email' => $recipientEmail,
                'recipient_user_id' => $recipientUserId,
                'subject' => $subject,
                'message_preview' => $messagePreview,
                'full_content' => $fullContent,
                'status' => $status,
                'delivery_status' => self::mapStatusToDeliveryStatus($status),
                'error_message' => $errorMessage,
                'sent_at' => $status === 'SENT' ? now() : null,
                'first_attempt_at' => now(),
                'delivered_at' => $status === 'SENT' ? now() : null,
                'delivery_attempts' => 1,
                'failure_reason' => $status === 'FAILED' ? ($errorMessage ?? 'Unknown error') : null,
                'related_entity_type' => $relatedEntityType,
                'related_entity_id' => $relatedEntityId,
                'provider' => $provider ?? 'Laravel Mail',
                'provider_message_id' => $providerMessageId,
                'provider_metadata' => $providerMetadata ? json_encode($providerMetadata) : null,
            ]);

            Log::info('Email communication logged', [
                'log_id' => $log->id,
                'type' => $type,
                'recipient' => $recipientEmail,
                'status' => $status,
            ]);

            return $log;
        } catch (\Exception $e) {
            Log::error('Failed to log email communication', [
                'error' => $e->getMessage(),
                'type' => $type,
                'recipient' => $recipientEmail,
            ]);
        }
    }

    /**
     * Log an SMS communication with delivery tracking
     */
    public static function logSms(
        $type,
        $recipientPhone,
        $message,
        $status = 'SENT',
        $errorMessage = null,
        $relatedEntityType = null,
        $relatedEntityId = null,
        $recipientUserId = null,
        $provider = null,
        $providerMessageId = null,
        $providerMetadata = null
    ) {
        try {
            $log = CommunicationLog::create([
                'communication_type' => $type,
                'channel' => 'sms',
                'recipient_phone' => $recipientPhone,
                'recipient_user_id' => $recipientUserId,
                'subject' => 'SMS: ' . substr($message, 0, 50),
                'message_preview' => $message,
                'full_content' => $message,
                'status' => $status,
                'delivery_status' => self::mapStatusToDeliveryStatus($status),
                'error_message' => $errorMessage,
                'sent_at' => $status === 'SENT' ? now() : null,
                'first_attempt_at' => now(),
                'delivered_at' => $status === 'SENT' ? now() : null,
                'delivery_attempts' => 1,
                'failure_reason' => $status === 'FAILED' ? ($errorMessage ?? 'Unknown error') : null,
                'related_entity_type' => $relatedEntityType,
                'related_entity_id' => $relatedEntityId,
                'provider' => $provider ?? 'Econet SMS',
                'provider_message_id' => $providerMessageId,
                'provider_metadata' => $providerMetadata ? json_encode($providerMetadata) : null,
            ]);

            Log::info('SMS communication logged', [
                'log_id' => $log->id,
                'type' => $type,
                'recipient' => $recipientPhone,
                'status' => $status,
            ]);

            return $log;
        } catch (\Exception $e) {
            Log::error('Failed to log SMS communication', [
                'error' => $e->getMessage(),
                'type' => $type,
                'recipient' => $recipientPhone,
            ]);
        }
    }

    /**
     * Log a database notification
     */
    public static function logDatabaseNotification(
        $type,
        $recipientUserId,
        $subject,
        $message,
        $status = 'SENT',
        $relatedEntityType = null,
        $relatedEntityId = null
    ) {
        try {
            $log = CommunicationLog::create([
                'communication_type' => $type,
                'channel' => 'database',
                'recipient_user_id' => $recipientUserId,
                'subject' => $subject,
                'message_preview' => $message,
                'full_content' => $message,
                'status' => $status,
                'delivery_status' => self::mapStatusToDeliveryStatus($status),
                'sent_at' => $status === 'SENT' ? now() : null,
                'first_attempt_at' => now(),
                'delivered_at' => $status === 'SENT' ? now() : null,
                'delivery_attempts' => 1,
                'related_entity_type' => $relatedEntityType,
                'related_entity_id' => $relatedEntityId,
            ]);

            Log::info('Database notification logged', [
                'log_id' => $log->id,
                'type' => $type,
                'user_id' => $recipientUserId,
                'status' => $status,
            ]);

            return $log;
        } catch (\Exception $e) {
            Log::error('Failed to log database notification', [
                'error' => $e->getMessage(),
                'type' => $type,
                'user_id' => $recipientUserId,
            ]);
        }
    }

    /**
     * Update delivery status based on webhook or polling
     * 
     * Used for tracking email delivery via provider webhooks or SMS delivery receipts
     */
    public static function updateDeliveryStatus(
        $messageId,
        $deliveryStatus,
        $deliveryData = null,
        $failureReason = null,
        $failureCode = null
    ) {
        try {
            $log = CommunicationLog::where('provider_message_id', $messageId)->first();

            if (!$log) {
                Log::warning('Communication log not found for delivery update', ['message_id' => $messageId]);
                return false;
            }

            $updates = [
                'delivery_status' => $deliveryStatus,
                'webhook_received' => true,
                'webhook_received_at' => now(),
            ];

            if ($deliveryData) {
                $updates['webhook_data'] = json_encode($deliveryData);
            }

            if ($deliveryStatus === 'DELIVERED') {
                $updates['delivered_at'] = now();
                $log->update($updates);
                Log::info('Email/SMS delivered successfully', ['log_id' => $log->id, 'message_id' => $messageId]);
                return true;
            } elseif ($deliveryStatus === 'FAILED' || $deliveryStatus === 'BOUNCED' || $deliveryStatus === 'UNDELIVERABLE') {
                $updates['failure_reason'] = $failureReason ?? 'Delivery failed';
                $updates['failure_code'] = $failureCode;
                $log->update($updates);
                Log::error('Email/SMS delivery failed', [
                    'log_id' => $log->id,
                    'message_id' => $messageId,
                    'reason' => $failureReason,
                    'code' => $failureCode,
                ]);
                return false;
            }

            $log->update($updates);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update delivery status', [
                'error' => $e->getMessage(),
                'message_id' => $messageId,
            ]);
            return false;
        }
    }

    /**
     * Mark for retry if delivery failed
     */
    public static function markForRetry($logId, $retryReason = null)
    {
        try {
            $log = CommunicationLog::find($logId);

            if (!$log) {
                Log::warning('Communication log not found for retry', ['log_id' => $logId]);
                return false;
            }

            $retryHistory = $log->retry_history ? json_decode($log->retry_history, true) : [];
            $retryHistory[] = [
                'attempt' => $log->delivery_attempts,
                'status' => $log->delivery_status,
                'reason' => $retryReason,
                'timestamp' => now()->toIso8601String(),
            ];

            $log->update([
                'delivery_status' => 'PENDING',
                'delivery_attempts' => $log->delivery_attempts + 1,
                'next_retry_at' => now()->addHours(1),
                'retry_history' => json_encode($retryHistory),
            ]);

            Log::info('Communication marked for retry', ['log_id' => $logId, 'attempts' => $log->delivery_attempts]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to mark communication for retry', [
                'error' => $e->getMessage(),
                'log_id' => $logId,
            ]);
            return false;
        }
    }

    /**
     * Get delivery statistics
     */
    public static function getDeliveryStats($days = 30)
    {
        return CommunicationLog::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('
                channel,
                delivery_status,
                COUNT(*) as count,
                ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM communication_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)), 2) as percentage
            ', [$days])
            ->groupBy('channel', 'delivery_status')
            ->get();
    }

    /**
     * Map status to delivery status
     */
    private static function mapStatusToDeliveryStatus($status)
    {
        return match($status) {
            'SENT' => 'SENT',
            'FAILED' => 'FAILED',
            'PENDING' => 'PENDING',
            default => 'PENDING',
        };
    }
}

