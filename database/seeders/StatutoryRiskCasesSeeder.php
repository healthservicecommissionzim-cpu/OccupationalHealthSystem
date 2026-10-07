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

class StatutoryRiskCasesSeeder extends Seeder
{
    /**
     * Seed statutory risk cases across all risk zones.
     * 
     * Risk Zones:
     * - Expired: > 365 days (beyond statutory timeframe)
     * - Critical: 270-365 days (9-12 months)
     * - Warning: 180-270 days (6-9 months)
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $statuses = [
            'PENDING_SUPERVISOR_VERIFICATION',
            'VERIFIED_SUPERVISOR',
            'ESCALATED_TO_HR',
            'PENDING_HR_REVIEW'
        ];

        $provinces = Province::all();
        $departments = Department::all();
        $designations = Designation::all();
        $supervisors = User::whereHas('roles', function($q) {
            $q->where('name', 'supervisor');
        })->get();

        if ($provinces->isEmpty() || $departments->isEmpty() || $designations->isEmpty()) {
            $this->command->error('Required data missing. Run base seeders first.');
            return;
        }

        $totalCases = 30;
        $createdCount = 0;

        // Distribution across risk zones
        $riskZones = [
            'expired' => ['count' => 10, 'dayRange' => [366, 450]], // Beyond 12 months
            'critical' => ['count' => 10, 'dayRange' => [271, 365]], // 9-12 months
            'warning' => ['count' => 10, 'dayRange' => [181, 270]], // 6-9 months
        ];

        foreach ($riskZones as $zoneName => $zoneConfig) {
            for ($i = 0; $i < $zoneConfig['count'] && $createdCount < $totalCases; $i++) {
                $province = $provinces->random();
                $districts = $province->districts;
                
                if ($districts->isEmpty()) {
                    continue;
                }

                $district = $districts->random();
                $facilities = $district->facilities;

                if ($facilities->isEmpty()) {
                    continue;
                }

                $facility = $facilities->random();
                $department = $departments->random();
                $designation = $designations->random();
                $supervisor = $supervisors->isNotEmpty() ? $supervisors->random() : null;

                // Calculate incident date to place in specific risk zone
                $daysOld = $faker->numberBetween($zoneConfig['dayRange'][0], $zoneConfig['dayRange'][1]);
                $incidentDate = Carbon::now()->subDays($daysOld);

                $status = $faker->randomElement($statuses);

                $incident = Incident::create([
                    'reference_number' => 'RISK-' . strtoupper(uniqid()),
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
                    'incident_description' => $faker->paragraph . ' [STATUTORY RISK - ' . strtoupper($zoneName) . ' ZONE]',
                    'facility_type_id' => $facility->facility_type_id,
                    'province_id' => $province->id,
                    'district_id' => $district->id,
                    'facility_id' => $facility->id,
                    'supervisor_designation_id' => $designation->id,
                    'supervisor_name' => $faker->name,
                    'assigned_supervisor_id' => $supervisor?->id,
                    'status' => $status,
                    'compliance_status' => 'NONCOMPLIANT',
                    'hr_route_type' => $faker->randomElement(['DISTRICT_HR_REVIEW', 'HOSPITAL_HR_REVIEW', 'CENTRAL_HR_REVIEW']),
                    'review_pool' => $faker->randomElement(['DISTRICT_HR_REVIEW', 'HOSPITAL_HR_REVIEW', 'CENTRAL_HR_REVIEW']),
                    'supervisor_verified_at' => in_array($status, ['VERIFIED_SUPERVISOR', 'ESCALATED_TO_HR', 'PENDING_HR_REVIEW']) 
                        ? $incidentDate->copy()->addDays(2)
                        : null,
                    'hod_report_submitted_at' => in_array($status, ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW'])
                        ? $incidentDate->copy()->addDays(5)
                        : null,
                    'escalated_to_hr_at' => in_array($status, ['ESCALATED_TO_HR', 'PENDING_HR_REVIEW'])
                        ? $incidentDate->copy()->addDays(6)
                        : null,
                    'is_workplace_fatality' => $faker->boolean(15),
                    'is_road_traffic_accident' => $faker->boolean(20),
                    'hod_acknowledged_witness_statements' => $faker->boolean(50),
                    'first_aid_recorded' => $faker->boolean(70),
                    'created_at' => $incidentDate,
                    'updated_at' => $incidentDate,
                ]);

                $createdCount++;
                
                // Calculate actual risk metrics
                $daysRemaining = $incident->getDaysRemainingUntilExpiry();
                $riskStatus = $incident->isClaimExpired() ? 'EXPIRED' 
                    : ($incident->isInCriticalRiskZone() ? 'CRITICAL' 
                    : ($incident->isInWarningZone() ? 'WARNING' : 'SAFE'));

                $this->command->line(sprintf(
                    "Created %d/%d: %s in %s | Zone: %s | Days old: %d | Days remaining: %d | Status: %s",
                    $createdCount,
                    $totalCases,
                    $incident->reference_number,
                    $province->name,
                    strtoupper($zoneName),
                    $daysOld,
                    $daysRemaining,
                    $riskStatus
                ));
            }
        }

        $this->command->info("\n✓ Successfully created {$createdCount} statutory risk cases:");
        $this->command->line("  • 10 EXPIRED cases (beyond 12 months)");
        $this->command->line("  • 10 CRITICAL RISK cases (9-12 months)");
        $this->command->line("  • 10 WARNING cases (6-9 months)");
        $this->command->line("\nThese cases are flagged for urgent attention due to statutory timeframe risks.");
    }
}
