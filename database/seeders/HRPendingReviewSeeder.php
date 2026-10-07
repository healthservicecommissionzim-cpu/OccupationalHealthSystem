<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Facility;
use App\Models\District;
use App\Models\Province;
use App\Models\Department;
use Carbon\Carbon;

class HRPendingReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create Harare Province and District
        $harareProvince = Province::firstOrCreate(
            ['name' => 'Harare'],
            ['code' => 'HAR']
        );

        $harareDistrict = District::firstOrCreate(
            ['name' => 'Harare', 'province_id' => $harareProvince->id],
            ['code' => 'HAR-DIST']
        );

        // Find or create Samuel Faindani as HR user in Harare
        $hrUser = User::where('email', 'samuel.faindani@health.gov.zw')->first();
        
        if (!$hrUser) {
            $hrUser = User::create([
                'name' => 'Samuel Faindani',
                'email' => 'samuel.faindani@health.gov.zw',
                'password' => bcrypt('password'),
                'province_id' => $harareProvince->id,
                'district_id' => $harareDistrict->id,
                'status' => 'approved',
            ]);
            
            // Assign HR role
            $hrRole = \App\Models\Role::where('name', 'district_hr')->first();
            if ($hrRole) {
                $hrUser->roles()->attach($hrRole->id);
            }
            
            $this->command->info('✓ Created HR user: Samuel Faindani');
        } else {
            $this->command->info('✓ Found existing HR user: Samuel Faindani');
        }

        // Get or create a facility in Harare district
        $facility = Facility::where('district_id', $harareDistrict->id)->first();
        
        if (!$facility) {
            $facilityType = \App\Models\FacilityType::first();
            $facility = Facility::create([
                'name' => 'Harare Central Hospital',
                'district_id' => $harareDistrict->id,
                'facility_type_id' => $facilityType->id ?? 1,
                'code' => 'HCH-001',
            ]);
        }

        // Get a department
        $department = Department::first();
        if (!$department) {
            $department = Department::create([
                'name' => 'Emergency Department',
                'code' => 'EMERG-01',
            ]);
        }

        // Get or create a supervisor user
        $supervisorUser = User::where('email', 'supervisor.harare@health.gov.zw')->first();
        if (!$supervisorUser) {
            $supervisorUser = User::create([
                'name' => 'John Supervisor',
                'email' => 'supervisor.harare@health.gov.zw',
                'password' => bcrypt('password'),
                'district_id' => $harareDistrict->id,
                'facility_id' => $facility->id,
                'status' => 'approved',
            ]);
            
            // Assign supervisor role
            $supervisorRole = \App\Models\Role::where('name', 'supervisor')->first();
            if ($supervisorRole) {
                $supervisorUser->roles()->attach($supervisorRole->id);
            }
        }

        // Create 5 incidents pending HR review
        $incidentTypes = [
            ['Slip and Fall', 'Slipped on wet floor in corridor'],
            ['Needle Stick', 'Accidental needle stick while disposing sharps'],
            ['Chemical Exposure', 'Exposed to cleaning chemicals in storage room'],
            ['Back Strain', 'Lifted heavy patient without assistance'],
            ['Burn Injury', 'Minor burn from autoclave equipment']
        ];

        $employeeNames = [
            ['Mary', 'Chikwanha'],
            ['Tendai', 'Moyo'],
            ['Grace', 'Ndlovu'],
            ['Blessing', 'Mutasa'],
            ['Peter', 'Ncube']
        ];

        $createdCount = 0;
        foreach ($incidentTypes as $index => $incidentInfo) {
            $incidentDate = Carbon::now()->subDays(rand(10, 25));
            $refNumber = 'HR-PEND-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
            
            // Check if incident already exists
            if (Incident::where('reference_number', $refNumber)->exists()) {
                continue;
            }
            
            // Get a designation
            $designation = \App\Models\Designation::first();
            if (!$designation) {
                $designation = \App\Models\Designation::create([
                    'name' => 'Registered Nurse',
                    'code' => 'RN-001',
                ]);
            }
            
            Incident::create([
                'reference_number' => $refNumber,
                'first_name' => $employeeNames[$index][0],
                'surname' => $employeeNames[$index][1],
                'designation_id' => $designation->id,
                'department_id' => $department->id,
                'id_number' => '63-' . rand(100000, 999999) . ' Z ' . rand(10, 99),
                'ec_number' => 'EC' . rand(100000, 999999),
                'home_address' => rand(1, 999) . ' ' . ['Borrowdale', 'Highlands', 'Avondale', 'Greendale', 'Mount Pleasant'][rand(0, 4)] . ', Harare',
                'phone_number' => '+2637712' . rand(10000, 99999),
                'email' => strtolower($employeeNames[$index][0] . '.' . $employeeNames[$index][1]) . '@health.gov.zw',
                'incident_date' => $incidentDate,
                'incident_time' => $incidentDate->format('H:i:s'),
                'incident_description' => $incidentInfo[1],
                'facility_type_id' => $facility->facility_type_id,
                'province_id' => $harareProvince->id,
                'district_id' => $harareDistrict->id,
                'facility_id' => $facility->id,
                'supervisor_designation_id' => $designation->id,
                'supervisor_name' => $supervisorUser->name,
                'status' => 'VERIFIED_SUPERVISOR',
                'hr_route_type' => 'DISTRICT_HR_REVIEW', // Set HR route type for district HR
                'assigned_supervisor_id' => $supervisorUser->id,
                'supervisor_verified_at' => $incidentDate->copy()->addDays(2),
                'created_at' => $incidentDate,
                'updated_at' => $incidentDate->copy()->addDays(2),
            ]);
            
            $createdCount++;
        }

        $this->command->info("✓ Created {$createdCount} incidents pending HR review for Samuel Faindani in Harare District, Harare Province");
    }
}
