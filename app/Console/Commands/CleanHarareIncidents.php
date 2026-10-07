<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\District;
use App\Models\Incident;

class CleanHarareIncidents extends Command
{
    protected $signature = 'harare:clean';
    protected $description = 'Clean all incidents from Harare district';

    public function handle()
    {
        $harareDistrict = District::where('name', 'Harare')->first();
        
        if (!$harareDistrict) {
            $this->error('Harare district not found');
            return 1;
        }

        $count = Incident::whereHas('facility', function($q) use ($harareDistrict) {
            $q->where('district_id', $harareDistrict->id);
        })->count();

        if ($count === 0) {
            $this->info('No incidents found in Harare district');
            return 0;
        }

        $this->info("Found {$count} incidents in Harare district");
        
        if ($this->confirm('Do you want to delete all these incidents?')) {
            $deleted = Incident::whereHas('facility', function($q) use ($harareDistrict) {
                $q->where('district_id', $harareDistrict->id);
            })->delete();

            $this->info("Successfully deleted {$deleted} incidents from Harare district");
        } else {
            $this->info('Operation cancelled');
        }

        return 0;
    }
}
