<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WarmRosterCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roster:warm-cache 
                            {--days=7 : Number of days to warm cache for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-cache active assignment queries for recent and upcoming dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $startDate = Carbon::now()->subDays($days);
        $endDate = Carbon::now()->addDays($days);
        
        $this->info("Warming roster cache for dates: {$startDate->toDateString()} to {$endDate->toDateString()}");
        $this->newLine();

        $warmedCount = 0;
        $failedCount = 0;

        // Get all active facilities
        $facilities = DB::table('facilities')
            ->select('id')
            ->get();

        $this->info("Processing " . count($facilities) . " facilities...");
        $this->newLine();

        $progressBar = $this->output->createProgressBar(count($facilities) * ($days * 2 + 1));

        // For each facility and date in range, warm the cache
        foreach ($facilities as $facility) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                try {
                    // Get all users who might have assignments at this facility on this date
                    $users = DB::table('roster_assignments')
                        ->join('staff_rosters', 'roster_assignments.staff_roster_id', '=', 'staff_rosters.id')
                        ->where('roster_assignments.facility_id', $facility->id)
                        ->where('roster_assignments.assignment_start_date', '<=', $currentDate)
                        ->where('roster_assignments.assignment_end_date', '>=', $currentDate)
                        ->where('roster_assignments.status', '!=', 'cancelled')
                        ->where('staff_rosters.active', true)
                        ->select('staff_rosters.user_id')
                        ->distinct()
                        ->get();

                    // Cache each user's active assignment for this facility and date
                    foreach ($users as $user) {
                        $this->cacheActiveAssignment($user->user_id, $facility->id, $currentDate);
                        $warmedCount++;
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    $this->error("Failed to warm cache for facility {$facility->id} on {$currentDate->toDateString()}: {$e->getMessage()}");
                }

                $progressBar->advance();
                $currentDate->addDay();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Cache warming complete!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Cache entries warmed', $warmedCount],
                ['Failed attempts', $failedCount],
                ['Date range', "{$days} days before and after today"],
            ]
        );
    }

    /**
     * Cache an active assignment query result
     */
    protected function cacheActiveAssignment(int $userId, int $facilityId, Carbon $date): void
    {
        $cacheKey = "roster_assignment:{$userId}:{$facilityId}:{$date->toDateString()}";
        
        // Query the active assignment
        $assignment = DB::table('roster_assignments')
            ->join('staff_rosters', 'roster_assignments.staff_roster_id', '=', 'staff_rosters.id')
            ->where('staff_rosters.user_id', $userId)
            ->where('roster_assignments.facility_id', $facilityId)
            ->where('roster_assignments.assignment_start_date', '<=', $date)
            ->where('roster_assignments.assignment_end_date', '>=', $date)
            ->where('roster_assignments.status', '!=', 'cancelled')
            ->where('staff_rosters.active', true)
            ->select('roster_assignments.*')
            ->first();

        // Cache for 5 minutes (matching the design requirement)
        Cache::put($cacheKey, $assignment, now()->addMinutes(5));
    }
}
