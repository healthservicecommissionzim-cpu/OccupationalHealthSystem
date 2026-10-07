<?php

namespace App\Jobs;

use App\Services\LeaveManagementService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckLeaveMilestonesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 300; // 5 minutes

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(LeaveManagementService $leaveService): void
    {
        try {
            Log::info('Leave milestone check job started');
            
            // Check and apply milestones for all active leave cases
            $results = $leaveService->checkAndApplyMilestones();
            
            Log::info('Leave milestone check completed', [
                'flagged_90' => $results['flagged_90'],
                'flagged_180' => $results['flagged_180'],
                'error_count' => count($results['errors']),
            ]);
            
            // Log any errors that occurred during processing
            if (!empty($results['errors'])) {
                Log::warning('Leave milestone check encountered errors', [
                    'errors' => $results['errors'],
                ]);
            }
            
            // Send summary email to admin
            $adminEmail = config('app.admin_email', 'admin@hsc.org.zw');
            if ($adminEmail) {
                try {
                    Mail::to($adminEmail)->send(new \App\Mail\LeaveMilestoneSummaryMail($results));
                    
                    Log::info('Leave milestone summary email sent', [
                        'recipient' => $adminEmail,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send leave milestone summary email', [
                        'recipient' => $adminEmail,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Leave milestone check job failed', [
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Leave milestone check job permanently failed', [
            'error' => $exception->getMessage(),
            'exception' => $exception,
        ]);
        
        // Optionally send alert notification
        $adminEmail = config('app.admin_email', 'admin@hsc.org.zw');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new \App\Mail\LeaveJobFailureAlert($exception));
            } catch (\Exception $e) {
                Log::error('Failed to send job failure alert', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
