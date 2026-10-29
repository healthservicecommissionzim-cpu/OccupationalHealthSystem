<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RosterAttributionMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roster:metrics 
                            {--lines=100 : Number of log lines to analyze}
                            {--threshold=100 : Performance threshold in ms}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze cost attribution performance metrics from logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lines = (int) $this->option('lines');
        $threshold = (int) $this->option('threshold');
        
        $this->info("Analyzing Cost Attribution Performance Metrics");
        $this->info("Log lines to analyze: {$lines}");
        $this->info("Performance threshold: {$threshold}ms");
        $this->newLine();

        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            $this->error("Log file not found at: {$logPath}");
            return 1;
        }

        // Read log file and extract attribution metrics
        $metrics = $this->parseLogMetrics($logPath, $lines);
        
        if (empty($metrics)) {
            $this->warn("No cost attribution metrics found in recent logs.");
            $this->info("Metrics will be logged when incidents are created.");
            return 0;
        }

        $this->displayMetricsSummary($metrics, $threshold);
        $this->displaySlowQueries($metrics, $threshold);
        
        return 0;
    }

    /**
     * Parse log file for attribution metrics
     */
    protected function parseLogMetrics(string $logPath, int $maxLines): array
    {
        $metrics = [];
        $lines = [];
        
        // Read last N lines from log file
        $file = new \SplFileObject($logPath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        $startLine = max(0, $lastLine - $maxLines);
        
        $file->seek($startLine);
        while (!$file->eof()) {
            $lines[] = $file->fgets();
        }
        
        // Parse lines for attribution metrics
        foreach ($lines as $line) {
            if (strpos($line, 'Cost attribution') !== false) {
                $metric = $this->parseMetricLine($line);
                if ($metric) {
                    $metrics[] = $metric;
                }
            }
        }
        
        return $metrics;
    }

    /**
     * Parse a single metric line
     */
    protected function parseMetricLine(string $line): ?array
    {
        // Extract JSON from log line
        if (preg_match('/\{.*\}/', $line, $matches)) {
            $json = json_decode($matches[0], true);
            
            if (isset($json['execution_time_ms'])) {
                return [
                    'timestamp' => $this->extractTimestamp($line),
                    'execution_time' => (float) $json['execution_time_ms'],
                    'success' => $json['success'] ?? true,
                    'user_id' => $json['user_id'] ?? null,
                    'facility_id' => $json['facility_id'] ?? null,
                    'exceeded_threshold' => isset($json['threshold_exceeded_by_ms']),
                ];
            }
        }
        
        return null;
    }

    /**
     * Extract timestamp from log line
     */
    protected function extractTimestamp(string $line): ?string
    {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Display metrics summary
     */
    protected function displayMetricsSummary(array $metrics, int $threshold): void
    {
        $executionTimes = array_column($metrics, 'execution_time');
        $successCount = count(array_filter($metrics, fn($m) => $m['success']));
        $failureCount = count($metrics) - $successCount;
        $exceededCount = count(array_filter($metrics, fn($m) => $m['exceeded_threshold']));
        
        $avgTime = array_sum($executionTimes) / count($executionTimes);
        $minTime = min($executionTimes);
        $maxTime = max($executionTimes);
        $p95Time = $this->percentile($executionTimes, 95);
        $p99Time = $this->percentile($executionTimes, 99);

        $this->info("Performance Summary");
        $this->table(
            ['Metric', 'Value', 'Status'],
            [
                ['Total Attributions', count($metrics), '-'],
                ['Successful', $successCount, '✓'],
                ['Failed', $failureCount, $failureCount > 0 ? '⚠' : '✓'],
                ['Exceeded Threshold', $exceededCount, $exceededCount > 0 ? '⚠' : '✓'],
                ['', '', ''],
                ['Average Time', number_format($avgTime, 2) . ' ms', $avgTime < $threshold ? '✓' : '✗'],
                ['Minimum Time', number_format($minTime, 2) . ' ms', '-'],
                ['Maximum Time', number_format($maxTime, 2) . ' ms', $maxTime < $threshold ? '✓' : '⚠'],
                ['95th Percentile', number_format($p95Time, 2) . ' ms', $p95Time < $threshold ? '✓' : '✗'],
                ['99th Percentile', number_format($p99Time, 2) . ' ms', $p99Time < $threshold ? '✓' : '✗'],
                ['Threshold', "{$threshold} ms", '-'],
            ]
        );
        $this->newLine();

        if ($exceededCount > 0) {
            $percentage = round(($exceededCount / count($metrics)) * 100, 2);
            $this->warn("⚠️  {$exceededCount} attribution(s) ({$percentage}%) exceeded the {$threshold}ms threshold");
        } else {
            $this->info("✓ All attributions completed within the {$threshold}ms threshold");
        }
        
        $this->newLine();
    }

    /**
     * Display slow queries
     */
    protected function displaySlowQueries(array $metrics, int $threshold): void
    {
        $slowQueries = array_filter($metrics, fn($m) => $m['exceeded_threshold']);
        
        if (empty($slowQueries)) {
            return;
        }

        $this->info("Slow Attributions (Exceeded Threshold)");
        
        $tableData = [];
        foreach ($slowQueries as $query) {
            $tableData[] = [
                $query['timestamp'] ?? 'N/A',
                number_format($query['execution_time'], 2) . ' ms',
                $query['user_id'] ?? 'N/A',
                $query['facility_id'] ?? 'N/A',
                $query['success'] ? '✓' : '✗',
            ];
        }
        
        $this->table(
            ['Timestamp', 'Execution Time', 'User ID', 'Facility ID', 'Success'],
            $tableData
        );
        $this->newLine();
    }

    /**
     * Calculate percentile
     */
    protected function percentile(array $data, float $percentile): float
    {
        sort($data);
        $index = ceil(($percentile / 100) * count($data)) - 1;
        return $data[max(0, $index)];
    }
}
