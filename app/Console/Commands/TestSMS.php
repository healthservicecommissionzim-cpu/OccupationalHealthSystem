<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SMSService;

class TestSMS extends Command
{
    protected $signature = 'sms:test {phone} {message}';
    protected $description = 'Test SMS sending';

    public function handle()
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->info("Sending SMS to: $phone");
        $this->info("Message: $message");

        $smsService = new SMSService();
        $result = $smsService->send($phone, $message);

        if ($result) {
            $this->info('✓ SMS sent successfully!');
        } else {
            $this->error('✗ SMS sending failed. Check logs.');
        }

        $this->info('Check storage/logs/laravel.log for details.');
    }
}
