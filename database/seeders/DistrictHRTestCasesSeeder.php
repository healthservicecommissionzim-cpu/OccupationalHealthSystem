<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DistrictHRTestCasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create District HR user
        $hrUser = User::where('email', 'districthr@hsc.org.zw')->first();
        
        if (!$hrUser) {
            $this->command->error('District HR user not found. Please run CreateDistrictHRAndIncidentsSeeder first.');
            return;
        }

        $this->command->info("Creating 15 test HR review cases for {$hrUser->name}...");

        // Get facility and other required data
        $facility = DB::table('facilities')
            ->where('district_id', $hrUser->district_id)
            ->first();

        if (!$facility) {
            $this->command->error('No facility found in district.');
            return;
        }

        $departments = DB::table('departments')->pluck('id')->toArray();
        $designations = DB::table('designations')->pluck('id')->toArray();
        $incidentTypes = DB::table('incident_types')->pluck('id')->toArray();
        $facilityType = DB::table('facility_types')->first();

        if (empty($departments) || empty($designations) || empty($incidentTypes)) {
            $this->command->error('Missing required data: departments, designations, or incident types.');
            return;
        }

        $workerNames = [
            ['Michael', 'Banda'],
            ['Grace', 'Mwale'],
            ['James', 'Chimusoro'],
            ['Sarah', 'Mutongwizo'],
            ['Daniel', 'Moyo'],
            ['Alice', 'Nyamutanga'],
            ['Peter', 'Chaziwa'],
            ['Janet', 'Zvobgo'],
            ['David', 'Haruperi'],
            ['Linda', 'Chindanya'],
            ['Charles', 'Makwimbi'],
            ['Rose', 'Ncube'],
            ['Robert', 'Magomere'],
            ['Emma', 'Chinake'],
            ['Thomas', 'Murehwa'],
        ];

        $descriptions = [
            'Electric shock from faulty equipment in surgical ward',
            'Back injury from patient handling in rehabilitation unit',
            'Chemical burn from cleaning solution in laboratory',
            'Needlestick injury in ICU',
            'Fall from ladder while performing maintenance work',
            'Repetitive strain injury in medical records department',
            'Exposure to bloodborne pathogens during emergency procedure',
            'Burn injury from autoclave equipment malfunction',
            'Fracture from slip and fall on wet floor',
            'Respiratory exposure from chemical spill',
            'Crush injury in equipment handling',
            'Thermal burn from sterilization equipment',
            'Eye injury from splashing of irritant substance',
            'Musculoskeletal strain from improper lifting technique',
            'Contact dermatitis from latex exposure',
        ];

        $caseReferences = [];

        for ($i = 1; $i <= 15; $i++) {
            // Generate unique reference number
            $refNumber = 'OHS-' . date('Y') . '-HR-' . str_pad($i + 50, 3, '0', STR_PAD_LEFT);
            
            // Skip if already exists
            if (Incident::where('reference_number', $refNumber)->exists()) {
                $this->command->line("  - {$refNumber} already exists, skipping");
                continue;
            }

            $worker = $workerNames[$i - 1];
            $incidentDate = Carbon::now()->subDays(rand(2, 45));
            $deptId = $departments[rand(0, count($departments) - 1)];
            $designId = $designations[rand(0, count($designations) - 1)];
            $typeId = $incidentTypes[rand(0, count($incidentTypes) - 1)];

            $incident = Incident::create([
                'reference_number' => $refNumber,
                'first_name' => $worker[0],
                'surname' => $worker[1],
                'id_number' => '63-' . rand(100000, 999999) . ' Z ' . rand(10, 99),
                'ec_number' => 'EC' . rand(100000, 999999),
                'email' => strtolower($worker[0] . '.' . $worker[1]) . '@health.gov.zw',
                'phone_number' => '+2637712' . rand(10000, 99999),
                'home_address' => rand(1, 999) . ' Test Road, Harare',
                'incident_date' => $incidentDate->toDateString(),
                'incident_time' => $incidentDate->format('H:i:s'),
                'incident_description' => $descriptions[$i - 1],
                'facility_id' => $facility->id,
                'facility_type_id' => $facilityType->id,
                'department_id' => $deptId,
                'designation_id' => $designId,
                'supervisor_designation_id' => $designId,
                'supervisor_name' => 'Department Supervisor',
                'province_id' => $hrUser->province_id,
                'district_id' => $hrUser->district_id,
                'incident_type_id' => $typeId,
                'status' => 'VERIFIED_SUPERVISOR',
                'routed_to_hr_user_id' => $hrUser->id,
                'supervisor_verified_at' => $incidentDate->copy()->addDays(1),
                'created_at' => $incidentDate,
                'updated_at' => $incidentDate->copy()->addDays(1),
            ]);

            $caseReferences[] = $refNumber;
            $this->command->info("  ✓ Created: {$refNumber} - {$worker[0]} {$worker[1]} ({$incidentDate->format('M d, Y')})");
        }

        // Verify creation
        $count = Incident::where('routed_to_hr_user_id', $hrUser->id)
            ->where('status', 'VERIFIED_SUPERVISOR')
            ->count();

        $this->command->info("\n✓ Successfully created test cases!");
        $this->command->info("✓ Total pending HR review incidents for districthr@hsc.org.zw: {$count}");
    }
}
