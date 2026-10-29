<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestRosterQueryPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roster:test-performance 
                            {--iterations=100 : Number of test iterations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test roster query performance and verify execution times meet requirements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $iterations = (int) $this->option('iterations');
        
        $this->info("Testing Roster Query Performance");
        $this->info("Iterations: {$iterations}");
        $this->newLine();

        // Test 1: Active assignment lookup query
        $this->testActiveAssignmentQuery($iterations);

        // Test 2: Cost center attribution query
        $this->testCostAttributionQuery($iterations);

        // Test 3: Verify indexes exist
        $this->verifyIndexes();

        $this->newLine();
        $this->info("Performance testing complete!");
    }

    /**
     * Test active assignment lookup query performance
     */
    protected function testActiveAssignmentQuery(int $iterations): void
    {
        $this->info("Test 1: Active Assignment Lookup Query");
        $this->info("Requirement: Must complete within 50ms");
        $this->newLine();

        $times = [];
        $testDate = Carbon::now();
        $facilityId = 1; // Use a valid facility ID

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            
            DB::table('roster_assignments')
                ->join('staff_rosters', 'roster_assignments.staff_roster_id', '=', 'staff_rosters.id')
                ->where('roster_assignments.facility_id', $facilityId)
                ->where('roster_assignments.assignment_start_date', '<=', $testDate)
                ->where('roster_assignments.assignment_end_date', '>=', $testDate)
                ->where('roster_assignments.status', '!=', 'cancelled')
                ->where('staff_rosters.active', true)
                ->select('roster_assignments.*')
                ->first();
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000; // Convert to milliseconds
        }

        $this->displayPerformanceResults($times, 50);
    }

    /**
     * Test cost attribution query performance
     */
    protected function testCostAttributionQuery(int $iterations): void
    {
        $this->info("Test 2: Cost Attribution Query");
        $this->info("Requirement: Must add < 100ms overhead to incident creation");
        $this->newLine();

        $times = [];
        $testDate = Carbon::now();
        $userId = 1; // Use a valid user ID
        $facilityId = 1; // Use a valid facility ID

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            
            // Get primary facility
            DB::table('users')
                ->where('id', $userId)
                ->value('facility_id');
            
            // Check for active assignment
            DB::table('roster_assignments')
                ->join('staff_rosters', 'roster_assignments.staff_roster_id', '=', 'staff_rosters.id')
                ->where('staff_rosters.user_id', $userId)
                ->where('roster_assignments.facility_id', $facilityId)
                ->where('roster_assignments.assignment_start_date', '<=', $testDate)
                ->where('roster_assignments.assignment_end_date', '>=', $testDate)
                ->where('roster_assignments.status', '!=', 'cancelled')
                ->where('staff_rosters.active', true)
                ->select('roster_assignments.*')
                ->first();
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000; // Convert to milliseconds
        }

        $this->displayPerformanceResults($times, 100);
    }

    /**
     * Display performance results
     */
    protected function displayPerformanceResults(array $times, float $threshold): void
    {
        $avgTime = array_sum($times) / count($times);
        $minTime = min($times);
        $maxTime = max($times);
        $p95Time = $this->percentile($times, 95);
        $p99Time = $this->percentile($times, 99);

        $this->table(
            ['Metric', 'Value', 'Status'],
            [
                ['Average', number_format($avgTime, 2) . ' ms', $avgTime < $threshold ? '✓ PASS' : '✗ FAIL'],
                ['Minimum', number_format($minTime, 2) . ' ms', '-'],
                ['Maximum', number_format($maxTime, 2) . ' ms', '-'],
                ['95th Percentile', number_format($p95Time, 2) . ' ms', $p95Time < $threshold ? '✓ PASS' : '✗ FAIL'],
                ['99th Percentile', number_format($p99Time, 2) . ' ms', $p99Time < $threshold ? '✓ PASS' : '✗ FAIL'],
                ['Threshold', number_format($threshold, 2) . ' ms', '-'],
            ]
        );

        $this->newLine();

        if ($avgTime >= $threshold) {
            $this->warn("⚠️  Average execution time exceeds threshold!");
        } else {
            $this->info("✓ Performance requirement met");
        }

        $this->newLine();
    }

    /**
     * Calculate percentile
     */
    protected function percentile(array $data, float $percentile): float
    {
        sort($data);
        $index = ceil(($percentile / 100) * count($data)) - 1;
        return $data[$index];
    }

    /**
     * Verify all required indexes exist
     */
    protected function verifyIndexes(): void
    {
        $this->info("Test 3: Verify Database Indexes");
        $this->newLine();

        $requiredIndexes = [
            'staff_rosters' => [
                'idx_staff_rosters_user_id',
                'idx_staff_rosters_primary_facility_id',
                'idx_staff_rosters_staff_type',
                'idx_staff_rosters_active',
            ],
            'roster_assignments' => [
                'idx_roster_assignments_staff_roster_id',
                'idx_roster_assignments_facility_id',
                'idx_roster_assignments_dates',
                'idx_roster_assignments_cost_center',
                'idx_roster_assignments_status',
                'idx_roster_assignments_active_lookup',
            ],
            'cost_centers' => [
                'idx_cost_centers_facility_id',
                'idx_cost_centers_is_active',
            ],
            'incidents' => [
                'idx_incidents_worker_primary_facility',
                'idx_incidents_cost_center_facility',
                'idx_incidents_roster_assignment',
                'idx_incidents_is_rotational_staff',
            ],
        ];

        $allIndexesPresent = true;

        foreach ($requiredIndexes as $table => $indexes) {
            $this->line("Table: <fg=cyan>{$table}</>");
            
            $tableIndexes = $this->getTableIndexes($table);
            
            foreach ($indexes as $indexName) {
                $exists = in_array($indexName, $tableIndexes);
                $status = $exists ? '<fg=green>✓ EXISTS</>' : '<fg=red>✗ MISSING</>';
                $this->line("  {$indexName}: {$status}");
                
                if (!$exists) {
                    $allIndexesPresent = false;
                }
            }
            
            $this->newLine();
        }

        if ($allIndexesPresent) {
            $this->info("✓ All required indexes are present");
        } else {
            $this->error("✗ Some indexes are missing. Run migrations to create them.");
        }
    }

    /**
     * Get indexes for a table
     */
    protected function getTableIndexes(string $table): array
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        return array_unique(array_column($indexes, 'Key_name'));
    }
}
