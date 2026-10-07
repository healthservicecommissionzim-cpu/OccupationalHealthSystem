<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    private $apiKey;
    private $baseUrl;
    private $senderId;
    private $siteHost;
    private $maxLength;
    private $enabled;

    public function __construct()
    {
        $this->enabled = config('services.sms.enabled', false);
        $this->apiKey = config('services.sms.api_key');
        $this->baseUrl = config('services.sms.base_url');
        $this->senderId = config('services.sms.sender_id');
        $this->siteHost = config('services.sms.site_host');
        $this->maxLength = config('services.sms.max_length', 480);
    }

    /**
     * Send SMS message
     */
    public function send($phoneNumber, $message)
    {
        if (!$this->enabled) {
            Log::info('SMS sending is disabled');
            return false;
        }

        if (!$this->isValidPhoneNumber($phoneNumber)) {
            Log::warning('Invalid phone number provided', ['phone' => $phoneNumber]);
            return false;
        }

        // Truncate message if it exceeds max length
        if (strlen($message) > $this->maxLength) {
            $message = substr($message, 0, $this->maxLength);
        }

        try {
            // Format phone number (remove + and spaces for Econet API)
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            $formattedPhone = str_replace(['+', ' ', '-'], '', $formattedPhone);

            // Build query parameters for Econet API
            $endpoint = $this->baseUrl . '/client/api/sendmessage';
            $params = [
                'apikey' => $this->apiKey,
                'mobiles' => $formattedPhone,
                'sms' => $message,
                'senderid' => $this->senderId,
            ];

            Log::info('Attempting to send SMS', [
                'endpoint' => $endpoint,
                'phone' => $formattedPhone,
                'from' => $this->senderId,
                'message_length' => strlen($message),
                // Task 10.4: Log only the length of API key, never the actual key
                'api_key_configured' => !empty($this->apiKey),
            ]);

            // Use GET request with query parameters as per Econet API
            $response = Http::timeout(30)->get($endpoint, $params);

            Log::info('SMS API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            // Parse the response to check for actual success
            $responseData = $response->json();
            $errorCode = $responseData['status']['error-code'] ?? null;
            
            if ($response->successful() && $errorCode === '000') {
                Log::info('SMS sent successfully', [
                    'phone' => $formattedPhone,
                    'length' => strlen($message),
                    'response' => $response->body()
                ]);
                return true;
            } else {
                $errorDesc = $responseData['status']['error-description'] ?? 'Unknown error';
                Log::error('SMS sending failed', [
                    'phone' => $formattedPhone,
                    'status' => $response->status(),
                    'error_code' => $errorCode,
                    'error_description' => $errorDesc,
                    'response' => $response->body(),
                    'endpoint' => $endpoint
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'endpoint' => $this->baseUrl . '/client/api/sendmessage',
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send witness notification SMS
     */
    public function sendWitnessNotification($phoneNumber, $incidentReference, $workerName, $incidentDate)
    {
        $message = "You have been listed as a witness for incident {$incidentReference} involving {$workerName} on {$incidentDate}. "
                 . "Please contact your supervisor or HR for statement details. "
                 . "Visit {$this->siteHost} for more information.";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Validate phone number
     */
    private function isValidPhoneNumber($phoneNumber)
    {
        // Basic validation - must start with + or numeric, at least 10 digits
        $cleaned = preg_replace('/\D+/', '', $phoneNumber);
        return strlen($cleaned) >= 10;
    }

    /**
     * Format phone number to international format
     */
    public function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters except +
        $cleaned = preg_replace('/[^\d+]/', '', $phoneNumber);

        // If doesn't start with +, add country code for Zimbabwe (+263)
        if (!str_starts_with($cleaned, '+')) {
            // Remove leading zeros to get the number without country code
            $withoutZero = preg_replace('/^0+/', '', $cleaned);
            // Add Zimbabwe country code
            $cleaned = '+263' . $withoutZero;
        }

        return $cleaned;
    }

    /**
     * Send witness token notification SMS with link
     */
    public function sendWitnessTokenNotification($phoneNumber, $witnessName, $incidentReference, $workerName, $incidentDate, $witnessLink, $facilityName)
    {
        $message = "Hello {$witnessName}, You have been listed as a witness for an incident involving {$workerName} on {$incidentDate} at {$facilityName}. "
                 . "Please use this link to provide your statement: {$witnessLink}";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send account registration confirmation SMS
     */
    public function sendRegistrationConfirmation($phoneNumber, $firstName, $surname)
    {
        $fullName = "{$firstName} {$surname}";
        $message = "Dear {$fullName}, Welcome to HSC-OHS. Your account has been successfully created. "
                 . "You are now awaiting admin approval for full access. "
                 . "You will be notified via SMS once your account is approved. Thank you.";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send account approval SMS
     */
    public function sendApprovalNotification($phoneNumber, $firstName, $surname)
    {
        $fullName = "{$firstName} {$surname}";
        $message = "Dear {$fullName}, Congratulations! Your HSC-OHS account has been approved. "
                 . "You can now log in to the system. Visit {$this->siteHost} to access your account. Welcome onboard!";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send incident report confirmation SMS to the reporter
     */
    public function sendIncidentReportConfirmation($phoneNumber, $firstName, $incidentReference, $facilityName)
    {
        $message = "Dear {$firstName}, Your occupational health incident has been successfully reported. "
                 . "Reference: {$incidentReference} at {$facilityName}. "
                 . "Your supervisor will be notified and will contact you shortly. Thank you.";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send incident alert SMS to the assigned supervisor
     */
    public function sendIncidentAlertToSupervisor($phoneNumber, $supervisorName, $workerName, $incidentReference, $facilityName)
    {
        $message = "Dear {$supervisorName}, A new occupational health incident requires your attention. "
                 . "Employee: {$workerName}, Reference: {$incidentReference} at {$facilityName}. "
                 . "Please log into HSC-OHS to review and take action. Thank you.";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send first aid token SMS to the incident reporter
     */
    public function sendFirstAidTokenNotification($phoneNumber, $firstName, $incidentReference, $facilityName, $firstAidToken)
    {
        $message = "Dear {$firstName}, Your incident report {$incidentReference} at {$facilityName} has been successfully recorded. "
                 . "Please provide this 4-digit First Aid Code to the Nurse or person who rendered first aid within 7 days: {$firstAidToken}. "
                 . "They will use this code to record the first aid details in the system.";

        return $this->send($phoneNumber, $message);
    }
}
