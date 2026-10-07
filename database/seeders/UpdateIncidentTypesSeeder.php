<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\IncidentType;

class UpdateIncidentTypesSeeder extends Seeder
{
    public function run(): void
    {
        $incidentTypes = IncidentType::active()->pluck('id')->toArray();
        
        if (empty($incidentTypes)) {
            $this->command->error('No incident types found. Please run migrations first.');
            return;
        }

        $incidents = Incident::whereNull('incident_type_id')->get();
        
        $this->command->info("Updating {$incidents->count()} incidents with random incident types...");
        
        foreach ($incidents as $incident) {
            $incident->update([
                'incident_type_id' => $incidentTypes[array_rand($incidentTypes)]
            ]);
        }
        
        $this->command->info("Successfully updated {$incidents->count()} incidents!");
    }
}
