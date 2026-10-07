<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\Province;
use App\Models\District;
use App\Models\Facility;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $statuses = [
            'PENDING_SUPERVISOR_VERIFICATION',
            'VERIFIED_SUPERVISOR',
            'ESCALATED_TO_HR',
            'PENDING_HR_REVIEW',
            'APPROVED_BY_PMD',
            'REJECTED_BY_HR',
            'RESOLVED'
        ];

        $complianceStatuses = ['COMPLIANT', 'PARTIAL_COMPLIANT', 'NONCOMPLIANT'];
        $hrRouteTypes = ['DISTRICT_HR_REVIEW', 'HOSPITAL_HR_REVIEW', 'CENTRAL_HR_REVIEW'];

        $provinces = Province::all();
        $departments = Department::all();
        $designations = Designation::all();
        $supervisors = User::whereHas('roles', function($q) {
            $q->where('name', 'supervisor');
        })->get();

        if ($provinces->isEmpty()) {
            $this->command->warn('No provinces found. Run HealthFacilitiesSeeder first.');
            return;
        }

        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Run DepartmentSeeder first.');
            return;
        }

        if ($designations->isEmpty()) {
            $this->command->warn('No designations found. Run DesignationSeeder first.');
            return;
        }

        // Distribute 50 incidents across provinces
        $incidentsPerProvince = ceil(50 / $provinces->count());
        $createdCount = 0;

        foreach ($provinces as $province) {
            $districts = $province->districts;
            
            if ($districts->isEmpty()) {
                continue;
            }

            for ($i = 0; $i < $incidentsPerProvince && $createdCount < 50; $i++) {
                $district = $districts->random();
                $facilities = $district->facilities;

                if ($facilities->isEmpty()) {
                    continue;
                }

                $facility = $facilities->random();
                $department = $departments->random();
                $designation = $designations->random();
                $supervisor = $supervisors->isNotEmpty() ? $supervisors->random() : null;

                $incidentDate = $faker->dateTimeBetween('-6 months', 'now');
                $status = $faker->randomElement($statuses);

                $incident = Incident::create([
                    'reference_number' => 'INC-' . strtoupper(uniqid()),
                    'first_name' => $faker->firstName,
                    'surname' => $faker->lastName,
                    'designation_id' => $designation->id,
                    'department_id' => $department->id,
                    'id_number' => $faker->numerify('###-###-###-###'),
                    'ec_number' => $faker->numerify('EC-###-###-###'),
                    'home_address' => $faker->address,
                    'phone_number' => $faker->phoneNumber,
                    'email' => $faker->unique()->safeEmail,
                    'incident_date' => $incidentDate->format('Y-m-d'),
                    'incident_time' => $incidentDate->format('H:i'),
                    'incident_description' => $faker->paragraph,
                    'facility_type_id' => $facility->facility_type_id,
                    'province_id' => $province->id,
                    'district_id' => $district->id,
                    'facility_id' => $facility->id,
                    'supervisor_designation_id' => $designation->id,
                    'supervisor_name' => $faker->name,
                    'assigned_supervisor_id' => $supervisor?->id,
                    'status' => $status,
                    'compliance_status' => $faker->randomElement($complianceStatuses),
                    'hr_route_type' => $faker->randomElement($hrRouteTypes),
                    'review_pool' => $faker->randomElement($hrRouteTypes),
                    'supervisor_verified_at' => in_array($status, ['VERIFIED_SUPERVISOR', 'ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'REJECTED_BY_HR', 'RESOLVED']) 
                        ? (clone $incidentDate)->add(new \DateInterval('P1D'))
                        : null,
                    'hod_report_submitted_at' => in_array($status, ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'REJECTED_BY_HR', 'RESOLVED'])
                        ? (clone $incidentDate)->add(new \DateInterval('P3D'))
                        : null,
                    'escalated_to_hr_at' => in_array($status, ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'REJECTED_BY_HR', 'RESOLVED'])
                        ? (clone $incidentDate)->add(new \DateInterval('P4D'))
                        : null,
                    'hr_dossier_acknowledged_at' => in_array($status, ['PENDING_HR_REVIEW', 'APPROVED_BY_PMD', 'REJECTED_BY_HR', 'RESOLVED'])
                        ? (clone $incidentDate)->add(new \DateInterval('P5D'))
                        : null,
                    'hr_reviewed_at' => in_array($status, ['APPROVED_BY_PMD', 'REJECTED_BY_HR', 'RESOLVED'])
                        ? (clone $incidentDate)->add(new \DateInterval('P7D'))
                        : null,
                    'is_workplace_fatality' => $faker->boolean(10),
                    'is_road_traffic_accident' => $faker->boolean(15),
                    'hod_acknowledged_witness_statements' => $faker->boolean(70),
                    'first_aid_recorded' => $faker->boolean(80),
                    'created_at' => $incidentDate,
                    'updated_at' => $incidentDate,
                ]);

                $createdCount++;
                $this->command->line("Created incident {$createdCount}/50: {$incident->reference_number} in {$province->name}");
            }
        }

        $this->command->info("Successfully created {$createdCount} sample incidents across all provinces.");
    }
}
