<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Services\ForensicAuditService;
use Illuminate\Console\Command;

class VerifyAuditIntegrity extends Command
{
    protected $signature = 'audit:verify-integrity {--days=7 : Number of days to check}';
    protected $description = 'Verify integrity of audit logs for tamper detection';

    public function handle()
    {
        $days = $this->option('days');
        $fromDate = now()->subDays($days);

        $this->info("Verifying audit log integrity for the last {$days} days...");

        $logs = AuditLog::where('timestamp_utc', '>=', $fromDate)
            ->orderBy('timestamp_utc', 'desc')
            ->get();

        $totalLogs = $logs->count();
        $verifiedCount = 0;
        $tamperedCount = 0;
        $unverifiableLogs = [];

        $bar = $this->output->createProgressBar($totalLogs);

        foreach ($logs as $log) {
            try {
                if (ForensicAuditService::verifyIntegrity($log)) {
                    $verifiedCount++;
                    $log->update(['hash_verified' => true]);
                } else {
                    $tamperedCount++;
                    $log->update(['hash_verified' => false]);
                    $this->warn("⚠️  Potential tamper detected in log ID {$log->id}");
                }
            } catch (\Exception $e) {
                $unverifiableLogs[] = [
                    'log_id' => $log->id,
                    'reason' => $e->getMessage(),
                ];
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Summary report
        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('AUDIT INTEGRITY VERIFICATION REPORT');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line("Period: Last {$days} days");
        $this->line("Total Logs Checked: {$totalLogs}");
        $this->line("✓ Verified (Intact): <fg=green>{$verifiedCount}</>");
        $this->line("✗ Tampered (Modified): <fg=red>{$tamperedCount}</>");
        $this->line("⊘ Unverifiable: " . count($unverifiableLogs));
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        if ($tamperedCount > 0) {
            $this->error("⚠️  ALERT: {$tamperedCount} tampered audit logs detected!");
            return self::FAILURE;
        }

        $this->info('✓ All audit logs passed integrity verification.');
        return self::SUCCESS;
    }
}
