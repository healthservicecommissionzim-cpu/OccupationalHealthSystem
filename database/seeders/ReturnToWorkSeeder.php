<?php

namespace Database\Seeders;

use App\Models\Incident;
use Illuminate\Database\Seeder;

class ReturnToWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample RTW data for testing
        $incidents = Incident::whereNotNull('assigned_supervisor_id')
            ->limit(5)
            ->get();

        foreach ($incidents as $incident) {
            $startDate = now()->subWeeks(2);
            $endDate = $startDate->copy()->addWeeks(4);

            $incident->update([
                'rtw_start_date' => $startDate,
                'rtw_end_date' => $endDate,
                'rtw_status' => fake()->randomElement(['active', 'on_track', 'requires_intervention']),
                'rtw_medical_restrictions' => json_encode([
                    'Cannot lift more than 5kg',
                    'Must sit for at least 30 minutes every hour',
                    'Avoid heights and confined spaces',
                    'No heavy machinery operation',
                ]),
                'rtw_modified_duties' => json_encode([
                    'Administrative data entry',
                    'Light clerical work',
                    'Telephone reception duties',
                    'Quality assurance checks',
                ]),
                'rtw_schedule' => json_encode([
                    'Week 1' => '4 hours per day',
                    'Week 2' => '6 hours per day',
                    'Week 3' => '8 hours per day (full time)',
                ]),
                'rtw_accommodations' => 'Ergonomic chair with lumbar support, sit-stand desk arrangement, proximity parking',
                'rtw_last_review_date' => now()->subDays(3),
                'rtw_next_review_date' => now()->addDays(7),
                'rtw_doctor_clearance_received' => true,
                'rtw_plan_signed_off' => true,
                'rtw_review_notes' => 'Worker progressing well. Pain levels decreasing. Ready to increase hours next week.',
            ]);
        }
    }
}
