<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Province;
use App\Models\District;
use App\Models\Facility;
use App\Models\IncidentType;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Str;

class PMDTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Find Mpho Hunda (PMD)
        $pmd = User::where('name', 'like', '%Mpho%')->first();
        
        if (!$pmd) {
            $this->command->error('PMD user "Mpho Hunda" not found!');
            return;
        }

        $this->command->info("Creating test incidents for PMD: {$pmd->name}");
        $this->command->info("Province ID: {$pmd->province_id}");

        // Get the province
        $province = Province::find($pmd->province_id);
        if (!$province) {
            $this->command->error('Province not found!');
            return;
        }

        $this->command->info("Province: {$province->name}");

        // Get all districts in this province
        $districts = District::where('province_id', $pmd->province_id)->get();
        
        if ($districts->isEmpty()) {
            $this->command->error('No districts found in this province!');
            return;
        }

        $this->command->info("Districts found: {$districts->count()}");

        $incidentTypes = IncidentType::active()->pluck('id')->toArray();
        $departments = Department::pluck('id')->toArray();
        $designations = Designation::pluck('id')->toArray();
        
        $statuses = ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'];
        $complianceStatuses = ['COMPLIANT', 'PARTIAL_COMPLIANT', 'NONCOMPLIANT'];
        
        $createdCount = 0;
        
        // Create incidents across all districts in the province
        foreach ($districts as $district) {
            $facilities = Facility::where('district_id', $district->id)->get();
            
            if ($facilities->isEmpty()) {
                $this->command->warn("No facilities in {$district->name}, skipping...");
                continue;
            }

            // Create 5-10 incidents per district for variety
            $incidentsToCreate = rand(5, 10);
            
            $this->command->info("\nCreating {$incidentsToCreate} incidents for {$district->name} district...");
            
            for ($i = 1; $i <= $incidentsToCreate; $i++) {
                $facility = $facilities->random();
                $status = $statuses[array_rand($statuses)];
                
                $incident = Incident::create([
                    'reference_number' => 'PMD-' . strtoupper(Str::random(10)),
                    'incident_type_id' => $incidentTypes[array_rand($incidentTypes)],
                    'first_name' => 'Worker' . $i,
                    'surname' => $district->name,
                    'designation_id' => $designations[array_rand($designations)],
                    'department_id' => $departments[array_rand($departments)],
                    'id_number' => '63-' . rand(100000, 999999) . ' A' . rand(10, 99),
                    'ec_number' => 'EC-' . rand(100, 999) . '-' . rand(100, 999),
                    'home_address' => rand(1, 999) . ' Test Street, ' . $district->name,
                    'phone_number' => '+263 77 ' . rand(1000000, 9999999),
                    'email' => 'worker' . $createdCount . '@test.com',
                    'incident_date' => now()->subDays(rand(1, 90)),
                    'incident_time' => now()->subHours(rand(1, 24))->format('H:i:s'),
                    'incident_description' => 'Provincial-level incident requiring PMD review and approval. Case escalated from ' . $district->name . ' district for provincial medical director assessment.',
                    'facility_type_id' => $facility->facility_type_id,
                    'province_id' => $facility->province_id,
                    'district_id' => $facility->district_id,
                    'facility_id' => $facility->id,
                    'supervisor_designation_id' => $designations[array_rand($designations)],
                    'supervisor_name' => 'Supervisor ' . rand(1, 20),
                    'status' => $status,
                    'compliance_status' => $complianceStatuses[array_rand($complianceStatuses)],
                    'is_workplace_fatality' => false,
                    'is_road_traffic_accident' => false,
                    'assigned_supervisor_id' => 2, // Makanaka as supervisor
                    'routed_to_pmd_user_id' => $pmd->id, // Route to Mpho Hunda
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now()->subDays(rand(1, 60)),
                ]);
                
                $createdCount++;
                $facilityTypeName = $facility->facilityType->name ?? 'Unknown';
                $this->command->info("  ✓ {$incident->reference_number} - {$facility->name} ({$facilityTypeName})");
            }
        }
        
        $this->command->info("\n" . str_repeat("=", 80));
        $this->command->info("✓ Successfully created {$createdCount} incidents for PMD review!");
        $this->command->info("PMD: {$pmd->name} ({$pmd->email})");
        $this->command->info("Province: {$province->name}");
        $this->command->info("Coverage: {$districts->count()} districts");
        
        // Show summary by district
        $this->command->info("\nIncident Distribution:");
        foreach ($districts as $district) {
            $count = Incident::whereIn('status', ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW'])
                ->where('routed_to_pmd_user_id', $pmd->id)
                ->where('district_id', $district->id)
                ->count();
            $this->command->info("  - {$district->name}: {$count} incidents");
        }
    }
}
