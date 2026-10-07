<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Database\Seeder;

class TestIncidentsSeeder extends Seeder
{
    public function run(): void
    {
        // Get incident types
        $incidentTypes = IncidentType::all();
        if ($incidentTypes->isEmpty()) {
            $this->command->warn('No incident types found. Please seed IncidentType first.');
            return;
        }

        // Get reporters (healthcare workers)
        $reporters = User::whereHas('roles', fn($q) => $q->whereIn('name', ['healthcare_worker', 'supervisor']))
            ->limit(20)
            ->get();

        if ($reporters->isEmpty()) {
            $this->command->warn('No reporters (healthcare workers) found.');
            return;
        }

        // Get facilities
        $facilities = Facility::limit(50)->get();
        if ($facilities->isEmpty()) {
            $this->command->warn('No facilities found.');
            return;
        }

        $incidentCount = 0;
        
        foreach ($reporters as $reporter) {
            // Create 3-5 incidents per reporter
            $numIncidents = rand(3, 5);
            
            for ($i = 0; $i < $numIncidents; $i++) {
                $incidentType = $incidentTypes->random();
                $facility = $facilities->random();
                
                Incident::create([
                    'reference_number' => 'INC-' . date('Y') . '-' . str_pad(Incident::count() + 1, 5, '0', STR_PAD_LEFT),
                    'reporter_id' => $reporter->id,
                    'facility_id' => $facility->id,
                    'incident_type_id' => $incidentType->id,
                    'date_of_incident' => now()->subDays(rand(1, 90)),
                    'time_of_incident' => now()->subHours(rand(1, 72)),
                    'location_of_incident' => 'Department ' . chr(65 + rand(0, 5)),
                    'incident_description' => 'Test incident - ' . fake()->sentence(),
                    'number_of_injured' => rand(1, 5),
                    'status' => 'PENDING_HOD_REVIEW',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $incidentCount++;
            }
        }
        
        $this->command->info("Test incidents seeded successfully! ({$incidentCount} incidents created)");
    }
}
