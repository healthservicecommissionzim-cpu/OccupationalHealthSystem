<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\IncidentWitnessToken;
use Illuminate\Support\Facades\Log;

class WitnessSMSService
{
    private $smsService;
    private $siteHost;
    private $maxLength;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
        $this->siteHost = config('services.sms.site_host');
        $this->maxLength = config('services.sms.max_length', 480);
    }

    /**
     * Format initial SMS message to witness
     * 
     * @param Incident $incident
     * @param string $witnessName
     * @param string $token
     * @return string
     */
    public function formatInitialSMS(Incident $incident, string $witnessName, string $token): string
    {
        $reference = $incident->reference_number;
        $link = $this->buildPublicLink($incident->id, $token);
        
        $message = "Hello {$witnessName}, You are listed as a witness for incident {$reference}. "
                 . "Please submit your statement: {$link}";
        
        return $this->validateMessageLength($message);
    }

    /**
     * Format reminder SMS message to witness
     * 
     * @param Incident $incident
     * @param string $witnessName
     * @param string $token
     * @return string
     */
    public function formatReminderSMS(Incident $incident, string $witnessName, string $token): string
    {
        $reference = $incident->reference_number;
        $link = $this->buildPublicLink($incident->id, $token);
        
        $message = "REMINDER: {$witnessName}, your witness statement for incident {$reference} is still pending. "
                 . "Please submit: {$link}";
        
        return $this->validateMessageLength($message);
    }

    /**
     * Validate message length and truncate if necessary
     * 
     * @param string $message
     * @return string
     */
    public function validateMessageLength(string $message): string
    {
        if (strlen($message) > $this->maxLength) {
            Log::warning('SMS message exceeds max length', [
                'length' => strlen($message),
                'max' => $this->maxLength
            ]);
            return substr($message, 0, $this->maxLength);
        }
        
        return $message;
    }

    /**
     * Build public link for witness statement submission
     * 
     * @param int $incidentId
     * @param string $token
     * @return string
     */
    private function buildPublicLink(int $incidentId, string $token): string
    {
        return "{$this->siteHost}/incidents/{$incidentId}/witness/{$token}";
    }

    /**
     * Send initial SMS notifications to all witnesses for an incident
     * 
     * @param Incident $incident
     * @return array ['success' => int, 'failed' => int, 'errors' => array]
     */
    public function sendInitialNotifications(Incident $incident): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $witnesses = $incident->witnessTokens()->get();

        if ($witnesses->isEmpty()) {
            Log::warning('No witnesses found for incident', [
                'incident_id' => $incident->id,
                'reference' => $incident->reference_number
            ]);
            return $results;
        }

        foreach ($witnesses as $witness) {
            try {
                $message = $this->formatInitialSMS($incident, $witness->witness_name, $witness->token);
                $sent = $this->smsService->send($witness->phone_number, $message);

                if ($sent) {
                    // Update witness token_sent_at timestamp
                    $witness->update(['token_sent_at' => now()]);
                    
                    // Log successful SMS
                    $this->logSMSHistory(
                        $incident->id,
                        $witness->id,
                        'WITNESS_INITIAL_SMS',
                        'SENT',
                        $witness->phone_number,
                        $message
                    );
                    
                    $results['success']++;
                } else {
                    // Log failure
                    $this->logSMSHistory(
                        $incident->id,
                        $witness->id,
                        'WITNESS_INITIAL_SMS',
                        'FAILED',
                        $witness->phone_number,
                        $message,
                        'SMS service returned false'
                    );
                    
                    $results['failed']++;
                    $results['errors'][] = "Failed to send SMS to {$witness->witness_name}";
                }
            } catch (\Exception $e) {
                Log::error('Exception sending witness initial SMS', [
                    'incident_id' => $incident->id,
                    'witness_id' => $witness->id,
                    'error' => $e->getMessage()
                ]);
                
                $this->logSMSHistory(
                    $incident->id,
                    $witness->id,
                    'WITNESS_INITIAL_SMS',
                    'FAILED',
                    $witness->phone_number,
                    $message ?? '',
                    $e->getMessage()
                );
                
                $results['failed']++;
                $results['errors'][] = "Exception for {$witness->witness_name}: {$e->getMessage()}";
            }
        }

        Log::info('Witness initial SMS batch completed', [
            'incident_id' => $incident->id,
            'reference' => $incident->reference_number,
            'success' => $results['success'],
            'failed' => $results['failed']
        ]);

        return $results;
    }

    /**
     * Send reminder SMS to a specific witness
     * 
     * @param IncidentWitnessToken $witnessToken
     * @param Incident $incident
     * @return bool
     */
    public function sendReminder(IncidentWitnessToken $witnessToken, Incident $incident): bool
    {
        try {
            // Check if witness has already submitted
            if ($witnessToken->hasSubmitted()) {
                Log::info('Reminder SMS not sent - witness already submitted', [
                    'witness_id' => $witnessToken->id,
                    'incident_id' => $incident->id
                ]);
                return false;
            }

            $message = $this->formatReminderSMS($incident, $witnessToken->witness_name, $witnessToken->token);
            $sent = $this->smsService->send($witnessToken->phone_number, $message);

            if ($sent) {
                // Update last reminder timestamp
                $witnessToken->update(['witness_last_reminder_sent_at' => now()]);
                
                // Log successful reminder
                $this->logSMSHistory(
                    $incident->id,
                    $witnessToken->id,
                    'WITNESS_REMINDER_SMS',
                    'SENT',
                    $witnessToken->phone_number,
                    $message
                );

                Log::info('Reminder SMS sent successfully', [
                    'witness_id' => $witnessToken->id,
                    'incident_id' => $incident->id
                ]);

                return true;
            } else {
                // Log failure
                $this->logSMSHistory(
                    $incident->id,
                    $witnessToken->id,
                    'WITNESS_REMINDER_SMS',
                    'FAILED',
                    $witnessToken->phone_number,
                    $message,
                    'SMS service returned false'
                );

                Log::warning('Reminder SMS failed to send', [
                    'witness_id' => $witnessToken->id,
                    'incident_id' => $incident->id
                ]);

                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception sending witness reminder SMS', [
                'incident_id' => $incident->id,
                'witness_id' => $witnessToken->id,
                'error' => $e->getMessage()
            ]);

            $this->logSMSHistory(
                $incident->id,
                $witnessToken->id,
                'WITNESS_REMINDER_SMS',
                'FAILED',
                $witnessToken->phone_number,
                $message ?? '',
                $e->getMessage()
            );

            return false;
        }
    }

    /**
     * Log SMS history in incident_notifications table
     * 
     * Logs each SMS attempt with:
     * - incident_id, witness_token_id
     * - type (WITNESS_INITIAL_SMS/WITNESS_REMINDER_SMS)
     * - status (SENT/FAILED)
     * - timestamp, phone, message content
     * - error message if failed
     * 
     * @param int $incidentId
     * @param int $witnessTokenId
     * @param string $type
     * @param string $status
     * @param string $phone
     * @param string $message
     * @param string|null $errorMessage
     * @return void
     */
    private function logSMSHistory(
        int $incidentId,
        int $witnessTokenId,
        string $type,
        string $status,
        string $phone,
        string $message,
        ?string $errorMessage = null
    ): void {
        try {
            \App\Models\IncidentNotification::create([
                'incident_id' => $incidentId,
                'witness_token_id' => $witnessTokenId,
                'notification_type' => $type,
                'notification_channel' => 'sms',
                'recipient_phone' => $phone,
                'message_content' => $message,
                'status' => $status,
                'sent_at' => now(),
                'error_message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log SMS history', [
                'incident_id' => $incidentId,
                'witness_token_id' => $witnessTokenId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get SMS history for a specific witness
     * Allows supervisor to see all SMS communications
     * 
     * @param int $witnessTokenId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWitnessSMSHistory(int $witnessTokenId)
    {
        return \App\Models\IncidentNotification::where('witness_token_id', $witnessTokenId)
            ->where('notification_channel', 'sms')
            ->whereIn('notification_type', ['WITNESS_INITIAL_SMS', 'WITNESS_REMINDER_SMS'])
            ->orderBy('sent_at', 'desc')
            ->get();
    }

    /**
     * Get all SMS history for an incident
     * 
     * @param int $incidentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getIncidentSMSHistory(int $incidentId)
    {
        return \App\Models\IncidentNotification::where('incident_id', $incidentId)
            ->where('notification_channel', 'sms')
            ->whereIn('notification_type', ['WITNESS_INITIAL_SMS', 'WITNESS_REMINDER_SMS'])
            ->with('witnessToken')
            ->orderBy('sent_at', 'desc')
            ->get();
    }
}
