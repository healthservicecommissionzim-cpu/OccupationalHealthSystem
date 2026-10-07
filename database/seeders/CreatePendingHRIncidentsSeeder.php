<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreatePendingHRIncidentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get district HR user
        $hrUser = User::where('email', 'districthr@hsc.org.zw')->first();
        
        if (!$hrUser) {
            $this->command->error('District HR user (districthr@hsc.org.zw) not found.');
            return;
        }

        $this->command->info("Found HR User: {$hrUser->name} (ID: {$hrUser->id})");

        // Get facility in Harare district
        $facility = DB::table('facilities')
            ->where('district_id', $hrUser->district_id)
            ->first();

        if (!$facility) {
            $this->command->error('No facility found in Harare district.');
            return;
        }

        // Get facility type
        $facilityType = DB::table('facility_types')->first();

        // Get department and designation
        $department = DB::table('departments')->first();
        $designation = DB::table('designations')->first();
        $incidentType = DB::table('incident_types')->first();

        if (!$department || !$designation) {
            $this->command->error('Missing department or designation.');
            return;
        }

        // Clear existing test incidents for this user
        DB::table('incidents')
            ->where('routed_to_hr_user_id', $hrUser->id)
            ->where('status', 'VERIFIED_SUPERVISOR')
            ->whereIn('reference_number', ['TEST-PND-001', 'TEST-PND-002'])
            ->delete();

        $this->command->info('Creating 2 pending HR review incidents...');

        // Create 2 pending incidents
        $incidents = [
            [
                'reference_number' => 'TEST-PND-001',
                'first_name' => 'John',
                'surname' => 'Mupako',
                'incident_date' => Carbon::now()->subDays(10),
                'incident_description' => 'Cut injury from broken glass in patient ward',
            ],
            [
                'reference_number' => 'TEST-PND-002',
                'first_name' => 'Maria',
                'surname' => 'Nhamo',
                'incident_date' => Carbon::now()->subDays(7),
                'incident_description' => 'Strain injury from lifting heavy medical equipment',
            ],
        ];

        foreach ($incidents as $data) {
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

            $this->command->info("✓ Created incident: {$data['reference_number']} - {$data['first_name']} {$data['surname']}");
        }

        // Verify creation
        $count = Incident::where('routed_to_hr_user_id', $hrUser->id)
            ->where('status', 'VERIFIED_SUPERVISOR')
            ->count();

        $this->command->info("✓ Total pending HR review incidents for {$hrUser->name}: {$count}");
    }
}
