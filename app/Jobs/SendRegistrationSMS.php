<?php

namespace App\Jobs;

use App\Services\SMSService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendRegistrationSMS implements ShouldQueue
{
    use Queueable;

    private $phone;
    private $firstName;
    private $lastName;
    private $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($phone, $firstName, $lastName, $userId)
    {
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $smsService = new SMSService();
            $smsService->sendRegistrationConfirmation($this->phone, $this->firstName, $this->lastName);
            Log::info('Registration confirmation SMS sent successfully', [
                'user_id' => $this->userId,
                'phone' => $this->phone
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration SMS', [
                'user_id' => $this->userId,
                'phone' => $this->phone,
                'error' => $e->getMessage()
            ]);
            // Job will be retried automatically based on queue configuration
            throw $e;
        }
    }
}
