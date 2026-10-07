<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\Facility;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Province;
use App\Models\District;
use App\Models\FacilityType;
use Faker\Factory as Faker;

class SupervisorTestCasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Find Noel Nyamunokora
        $supervisor = User::where('name', 'like', '%Noel%')
            ->orWhere('name', 'like', '%Nyamunokora%')
            ->first();

        if (!$supervisor) {
            $this->command->error('Supervisor "Noel Nyamunokora" not found!');
            return;
        }

        $this->command->info('Found supervisor: ' . $supervisor->name . ' (ID: ' . $supervisor->id . ')');

        // Get required related data
        $incidentTypes = IncidentType::where('is_active', true)->get();
        $facilities = Facility::all();
        $designations = Designation::all();
        $departments = Department::all();
        $provinces = Province::all();
        $districts = District::all();
        $facilityTypes = FacilityType::all();

        // Validate required data exists
        if ($incidentTypes->isEmpty()) {
            $this->command->error('No active incident types found!');
            return;
        }
        if ($facilities->isEmpty()) {
            $this->command->error('No facilities found!');
            return;
        }
        if ($designations->isEmpty()) {
            $this->command->error('No designations found!');
            return;
        }
        if ($departments->isEmpty()) {
            $this->command->error('No departments found!');
            return;
        }
        if ($provinces->isEmpty()) {
            $this->command->error('No provinces found!');
            return;
        }
        if ($districts->isEmpty()) {
            $this->command->error('No districts found!');
            return;
        }
        if ($facilityTypes->isEmpty()) {
            $this->command->error('No facility types found!');
            return;
        }

        $this->command->info('Creating 10 test incidents for supervisor review...');
        $this->command->info('Total incident types: ' . $incidentTypes->count());
        $this->command->info('Total facilities: ' . $facilities->count());

        // Create 10 incidents (7 pending, 3 verified)
        for ($i = 1; $i <= 10; $i++) {
            $facility = $facilities->random();
            $incident = Incident::create([
                'reference_number' => 'TEST-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'first_name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'designation_id' => $designations->random()->id,
                'department_id' => $departments->random()->id,
                'id_number' => $faker->numerify('########'),
                'ec_number' => $faker->numerify('EC########'),
                'home_address' => $faker->address(),
                'phone_number' => $faker->phoneNumber(),
                'email' => $faker->email(),
                'incident_date' => $faker->dateTimeBetween('-30 days', 'now'),
                'incident_time' => $faker->time(),
                'incident_description' => $faker->sentence(10),
                'facility_type_id' => $facilityTypes->random()->id,
                'province_id' => $provinces->random()->id,
                'district_id' => $districts->random()->id,
                'facility_id' => $facility->id,
                'supervisor_designation_id' => $designations->random()->id,
                'supervisor_name' => $supervisor->name,
                'incident_type_id' => $incidentTypes->random()->id,
                'assigned_supervisor_id' => $supervisor->id,
                'status' => 'PENDING_SUPERVISOR_VERIFICATION',
                'compliance_status' => 'NONCOMPLIANT',
            ]);

            $createdIncidents[] = $incident;

            $this->command->line('✓ Created: ' . $incident->reference_number . ' | ' . $incident->first_name . ' ' . $incident->surname . ' | Type: ' . ($incident->incidentType?->name ?? 'N/A'));
        }

        // Add first aid records to first 3 incidents
        $this->command->info('Adding first aid records to first 3 incidents...');
        for ($i = 0; $i < 3; $i++) {
            $incident = $createdIncidents[$i];
            
            // Add 1-2 first aid records per incident
            $recordCount = $faker->numberBetween(1, 2);
            for ($j = 0; $j < $recordCount; $j++) {
                $incident->firstAidRecords()->create([
                    'nurse_name' => $faker->name(),
                    'nurse_license_number' => $faker->numerify('LN########'),
                    'location' => $faker->word() . ' Ward',
                    'injury_description' => $faker->sentence(6),
                    'first_aid_description' => $faker->sentence(8),
                    'severity_level' => $faker->randomElement(['MINOR', 'MODERATE', 'SEVERE']),
                    'time_administered' => $faker->dateTimeBetween('-7 days', 'now'),
                    'notes' => $faker->sentence(5),
                ]);
            }

            $this->command->line('✓ Added ' . $recordCount . ' first aid record(s) to: ' . $incident->reference_number);
        }

        $this->command->info('✅ Successfully created 10 test incidents for ' . $supervisor->name . '!');
    }
}
