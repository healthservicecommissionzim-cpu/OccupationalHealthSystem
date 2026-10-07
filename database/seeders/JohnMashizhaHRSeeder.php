<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class JohnMashizhaHRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or find John Mashizha user
        $hrUser = User::where('email', 'john.mashizha@health.gov.zw')->first();
        
        if (!$hrUser) {
            $this->command->info('John Mashizha user not found. Creating...');
            
            // Get Harare district
            $districtId = DB::table('districts')->where('name', 'Harare')->value('id');
            $provinceId = DB::table('provinces')->where('name', 'Harare')->value('id');
            
            if (!$districtId || !$provinceId) {
                $this->command->error('Harare province/district not found. Run base seeders first.');
                return;
            }
            
            $hrUser = User::create([
                'name' => 'John Mashizha',
                'email' => 'john.mashizha@health.gov.zw',
                'password' => bcrypt('password'),
                'phone_number' => '+263712345678',
                'province_id' => $provinceId,
                'district_id' => $districtId,
                'status' => 'approved',
            ]);
            
            // Assign HR role
            $hrRoleId = DB::table('roles')->where('name', 'district_hr')->value('id');
            if ($hrRoleId) {
                DB::table('role_user')->insert([
                    'role_id' => $hrRoleId,
                    'user_id' => $hrUser->id,
                ]);
            }
            
            $this->command->info('✓ Created HR user: John Mashizha');
        } else {
            $this->command->info('✓ Found existing HR user: John Mashizha');
        }

        // Get facility and department IDs
        $facilityId = DB::table('facilities')->where('district_id', $hrUser->district_id)->value('id');
        $departmentId = DB::table('departments')->value('id');
        $designationId = DB::table('designations')->value('id');

        if (!$facilityId || !$departmentId || !$designationId) {
            $this->command->error('Missing facility, department, or designation. Run base seeders first.');
            return;
        }

        // Get supervisor
        $supervisorId = DB::table('users')->where('email', 'supervisor.mashizha@health.gov.zw')->value('id');
        
        // Test incident data - 2 pending HR review incidents
        $testIncidents = [
            [
                'ref' => 'JM-PND-00001',
                'first_name' => 'Alice',
                'surname' => 'Mukundane',
                'description' => 'Slipped on wet floor in corridor near maternity ward',
                'days_ago' => 15,
            ],
            [
                'ref' => 'JM-PND-00002',
                'first_name' => 'Robert',
                'surname' => 'Chirwa',
                'description' => 'Accidental needle stick injury while disposing sharps in ICU',
                'days_ago' => 12,
            ],
        ];

        $createdCount = 0;
        foreach ($testIncidents as $testData) {
            // Check if incident already exists
            if (DB::table('incidents')->where('reference_number', $testData['ref'])->exists()) {
                continue;
            }

            $incidentDate = Carbon::now()->subDays($testData['days_ago']);
            
            DB::table('incidents')->insert([
                'reference_number' => $testData['ref'],
                'first_name' => $testData['first_name'],
                'surname' => $testData['surname'],
                'designation_id' => $designationId,
                'department_id' => $departmentId,
                'id_number' => '63-' . rand(100000, 999999) . ' Z ' . rand(10, 99),
                'ec_number' => 'EC' . rand(100000, 999999),
                'home_address' => rand(1, 999) . ' ' . ['Borrowdale', 'Highlands', 'Avondale', 'Greendale'][rand(0, 3)] . ', Harare',
                'phone_number' => '+2637712' . rand(10000, 99999),
                'email' => strtolower($testData['first_name'] . '.' . $testData['surname']) . '@health.gov.zw',
                'incident_date' => $incidentDate->toDateString(),
                'incident_time' => $incidentDate->format('H:i:s'),
                'incident_description' => $testData['description'],
                'facility_id' => $facilityId,
                'province_id' => $hrUser->province_id,
                'district_id' => $hrUser->district_id,
                'status' => 'VERIFIED_SUPERVISOR',
                'assigned_supervisor_id' => $supervisorId,
                'routed_to_hr_user_id' => $hrUser->id,
                'supervisor_verified_at' => $incidentDate->copy()->addDays(1),
                'created_at' => $incidentDate,
                'updated_at' => $incidentDate->copy()->addDays(1),
            ]);
            
            $createdCount++;
        }

        $this->command->info("✓ Created {$createdCount} pending incidents for John Mashizha");
    }
}
