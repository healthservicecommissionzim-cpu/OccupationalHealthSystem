<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Facility;
use App\Models\IncidentType;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Str;

class DMOTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Find Deon Hunda (DMO)
        $dmo = User::where('name', 'like', '%Deon%')->first();
        
        if (!$dmo) {
            $this->command->error('DMO user "Deon Hunda" not found!');
            return;
        }

        $this->command->info("Creating test incidents for DMO: {$dmo->name}");
        $this->command->info("District ID: {$dmo->district_id}, Province ID: {$dmo->province_id}");

        // Get facilities in Harare district
        $facilities = Facility::where('district_id', $dmo->district_id)->get();
        
        if ($facilities->isEmpty()) {
            $this->command->error('No facilities found in Harare district!');
            return;
        }

        $incidentTypes = IncidentType::active()->pluck('id')->toArray();
        $departments = Department::pluck('id')->toArray();
        $designations = Designation::pluck('id')->toArray();
        
        $statuses = ['ESCALATED_TO_DMO']; // Only DMO escalated cases
        $complianceStatuses = ['COMPLIANT', 'PARTIAL_COMPLIANT', 'NONCOMPLIANT'];
        
        $createdCount = 0;
        
        // Create 15 incidents escalated to DMO
        for ($i = 1; $i <= 15; $i++) {
            $facility = $facilities->random();
            $status = $statuses[array_rand($statuses)];
            
            $incident = Incident::create([
                'reference_number' => 'DMO-' . strtoupper(Str::random(10)),
                'incident_type_id' => $incidentTypes[array_rand($incidentTypes)],
                'first_name' => 'Worker' . $i,
                'surname' => 'Harare',
                'designation_id' => $designations[array_rand($designations)],
                'department_id' => $departments[array_rand($departments)],
                'id_number' => '63-' . rand(100000, 999999) . ' A' . rand(10, 99),
                'ec_number' => 'EC-' . rand(100, 999) . '-' . rand(100, 999),
                'home_address' => rand(1, 999) . ' Test Street, Harare',
                'phone_number' => '+263 77 ' . rand(1000000, 9999999),
                'email' => 'worker' . $i . '@test.com',
                'incident_date' => now()->subDays(rand(1, 60)),
                'incident_time' => now()->subHours(rand(1, 24))->format('H:i:s'),
                'incident_description' => 'Test incident escalated to DMO for review and approval. This case requires district-level assessment and decision.',
                'facility_type_id' => $facility->facility_type_id,
                'province_id' => $facility->province_id,
                'district_id' => $facility->district_id,
                'facility_id' => $facility->id,
                'supervisor_designation_id' => $designations[array_rand($designations)],
                'supervisor_name' => 'Supervisor ' . rand(1, 10),
                'status' => $status,
                'compliance_status' => $complianceStatuses[array_rand($complianceStatuses)],
                'is_workplace_fatality' => false,
                'is_road_traffic_accident' => false,
                'assigned_supervisor_id' => 2, // Makanaka as supervisor
                'routed_to_dmo_user_id' => $dmo->id, // Route to Deon Hunda
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
            
            $createdCount++;
            $this->command->info("Created: {$incident->reference_number} - Status: {$status} - Facility: {$facility->name}");
        }
        
        $this->command->info("\n✓ Successfully created {$createdCount} incidents for DMO review!");
        $this->command->info("DMO: {$dmo->name} ({$dmo->email})");
        $this->command->info("District: Harare");
    }
}
