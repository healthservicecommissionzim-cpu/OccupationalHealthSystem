<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateDistrictHRAndIncidentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create District HR user
        $hrUser = User::where('email', 'districthr@hsc.org.zw')->first();
        
        if (!$hrUser) {
            $this->command->info('District HR user not found. Creating...');
            
            // Get Harare district
            $districtId = DB::table('districts')->where('name', 'Harare')->value('id');
            $provinceId = DB::table('provinces')->where('name', 'Harare')->value('id');
            
            if (!$districtId || !$provinceId) {
                $this->command->error('Harare province/district not found.');
                return;
            }
            
            $hrUser = User::create([
                'name' => 'District HR',
                'email' => 'districthr@hsc.org.zw',
                'password' => bcrypt('password'),
                'phone_number' => '+263712000000',
                'province_id' => $provinceId,
                'district_id' => $districtId,
                'status' => 'approved',
            ]);
            
            // Assign district_hr role
            $hrRoleId = DB::table('roles')->where('name', 'district_hr')->value('id');
            if ($hrRoleId) {
                DB::table('role_user')->insert([
                    'role_id' => $hrRoleId,
                    'user_id' => $hrUser->id,
                ]);
            }
            
            $this->command->info("✓ Created District HR user (ID: {$hrUser->id})");
        } else {
            $this->command->info("✓ Found existing District HR user (ID: {$hrUser->id})");
        }

        // Get facility and department IDs
        $facility = DB::table('facilities')
            ->where('district_id', $hrUser->district_id)
            ->first();

        if (!$facility) {
            $this->command->error('No facility found in Harare district.');
            return;
        }

        $department = DB::table('departments')->first();
        $designation = DB::table('designations')->first();
        $facilityType = DB::table('facility_types')->first();
        
        // Get different incident types
        $incidentTypes = DB::table('incident_types')->pluck('id', 'name')->toArray();
        $incidentType1 = DB::table('incident_types')->first();
        $incidentType2 = DB::table('incident_types')->skip(1)->first() ?? $incidentType1;

        if (!$department || !$designation) {
            $this->command->error('Missing department or designation.');
            return;
        }

        $this->command->info("Creating 2 pending HR review incidents for {$hrUser->name}...");

        // Create 2 pending incidents
        $incidents = [
            [
                'reference_number' => 'DIST-HR-001',
                'first_name' => 'Michael',
                'surname' => 'Banda',
                'incident_date' => Carbon::now()->subDays(10),
                'incident_description' => 'Electric shock from faulty equipment in surgical ward',
            ],
            [
                'reference_number' => 'DIST-HR-002',
                'first_name' => 'Grace',
                'surname' => 'Mwale',
                'incident_date' => Carbon::now()->subDays(5),
                'incident_description' => 'Back injury from patient handling in rehabilitation unit',
            ],
        ];

        foreach ($incidents as $index => $data) {
            // Check if incident already exists
            if (Incident::where('reference_number', $data['reference_number'])->exists()) {
                $this->command->line("  - {$data['reference_number']} already exists, skipping");
                continue;
            }

            // Use different incident type for each incident
            $incidentType = $index === 0 ? $incidentType1 : $incidentType2;

            $incident = Incident::create([
                'reference_number' => $data['reference_number'],
                'first_name' => $data['first_name'],
                'surname' => $data['surname'],
                'id_number' => '63-' . rand(100000, 999999) . ' Z ' . rand(10, 99),
                'ec_number' => 'EC' . rand(100000, 999999),
                'email' => strtolower($data['first_name'] . '.' . $data['surname']) . '@health.gov.zw',
                'phone_number' => '+2637712' . rand(10000, 99999),
                'home_address' => rand(1, 999) . ' Test Road, Harare',
                'incident_date' => $data['incident_date']->toDateString(),
                'incident_time' => $data['incident_date']->format('H:i:s'),
                'incident_description' => $data['incident_description'],
                'facility_id' => $facility->id,
                'facility_type_id' => $facilityType->id,
                'department_id' => $department->id,
                'designation_id' => $designation->id,
                'supervisor_designation_id' => $designation->id,
                'supervisor_name' => 'Supervisor Name',
                'province_id' => $hrUser->province_id,
                'district_id' => $hrUser->district_id,
                'incident_type_id' => $incidentType?->id,
                'status' => 'VERIFIED_SUPERVISOR',
                'routed_to_hr_user_id' => $hrUser->id,
                'supervisor_verified_at' => $data['incident_date']->copy()->addDays(1),
                'created_at' => $data['incident_date'],
                'updated_at' => $data['incident_date']->copy()->addDays(1),
            ]);

            $this->command->info("  ✓ Created: {$data['reference_number']} - {$data['first_name']} {$data['surname']}");
        }

        // Verify creation
        $count = Incident::where('routed_to_hr_user_id', $hrUser->id)
            ->where('status', 'VERIFIED_SUPERVISOR')
            ->count();

        $this->command->info("✓ Total pending HR review incidents: {$count}");
    }
}
