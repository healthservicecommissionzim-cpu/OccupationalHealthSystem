<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoelNyamunokora_RTWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds RTW data for all incidents assigned to Noel Nyamunokora (supervisor)
     */
    public function run(): void
    {
        // Find Noel Nyamunokora
        $noel = User::where('name', 'like', '%Noel%Nyamunokora%')->first();
        
        if (!$noel) {
            $this->command->warn('Noel Nyamunokora not found in users table');
            return;
        }

        $this->command->info("Found user: {$noel->name}");

        // Get all incidents assigned to Noel as supervisor
        $incidents = Incident::where('assigned_supervisor_id', $noel->id)->get();

        if ($incidents->isEmpty()) {
            $this->command->warn('No incidents found assigned to ' . $noel->name);
            return;
        }

        $this->command->info("Found {$incidents->count()} incidents assigned to {$noel->name}");

        // RTW data templates
        $rtwTemplates = [
            [
                'restrictions' => [
                    'Cannot lift more than 5kg',
                    'Must sit for at least 30 minutes every hour',
                    'Avoid heights and confined spaces',
                ],
                'duties' => [
                    'Administrative data entry',
                    'Light clerical work',
                    'Telephone reception duties',
                ],
                'schedule' => [
                    'Week 1' => '4 hours per day',
                    'Week 2' => '6 hours per day',
                    'Week 3-4' => '8 hours per day (full time)',
                ],
                'accommodations' => 'Ergonomic chair with lumbar support, sit-stand desk arrangement',
            ],
            [
                'restrictions' => [
                    'No heavy lifting (max 2kg)',
                    'Limited standing (maximum 2 hours at a time)',
                    'Must avoid repetitive hand movements',
                ],
                'duties' => [
                    'Quality assurance checks',
                    'Report compilation',
                    'Filing and documentation',
                ],
                'schedule' => [
                    'Week 1-2' => '3 hours per day',
                    'Week 3' => '5 hours per day',
                    'Week 4' => '8 hours per day (full time)',
                ],
                'accommodations' => 'Anti-fatigue mat, voice recognition software for data entry, regular breaks scheduled',
            ],
            [
                'restrictions' => [
                    'Cannot work at heights',
                    'Must avoid machinery operation',
                    'No outdoor duties during extreme weather',
                ],
                'duties' => [
                    'Indoor facility inspection',
                    'Documentation review',
                    'Team training and mentoring',
                ],
                'schedule' => [
                    'Week 1' => '5 hours per day',
                    'Week 2-3' => '7 hours per day',
                    'Week 4' => '8 hours per day (full time)',
                ],
                'accommodations' => 'Climate-controlled workspace, flexible break schedule',
            ],
        ];

        $count = 0;
        foreach ($incidents as $index => $incident) {
            // Use template based on incident index
            $template = $rtwTemplates[$index % count($rtwTemplates)];
            
            $startDate = now()->subWeeks(rand(1, 3));
            $endDate = $startDate->copy()->addWeeks(rand(3, 5));
            
            $statuses = ['active', 'on_track', 'requires_intervention', 'completed'];
            
            $incident->update([
                'rtw_start_date' => $startDate,
                'rtw_end_date' => $endDate,
                'rtw_status' => $statuses[array_rand($statuses)],
                'rtw_medical_restrictions' => json_encode($template['restrictions']),
                'rtw_modified_duties' => json_encode($template['duties']),
                'rtw_schedule' => json_encode($template['schedule']),
                'rtw_accommodations' => $template['accommodations'],
                'rtw_last_review_date' => now()->subDays(rand(1, 7)),
                'rtw_next_review_date' => now()->addDays(rand(1, 14)),
                'rtw_doctor_clearance_received' => (bool) rand(0, 1),
                'rtw_plan_signed_off' => (bool) rand(0, 1),
                'rtw_review_notes' => $this->getRandomReviewNote(),
            ]);
            
            $count++;
        }

        $this->command->info("✓ Successfully seeded RTW data for {$count} incidents");
    }

    private function getRandomReviewNote(): string
    {
        $notes = [
            'Worker progressing well. Pain levels decreasing. Ready to increase hours next week.',
            'Slight discomfort reported during extended sitting. Recommend additional ergonomic support.',
            'Excellent recovery trajectory. Worker managing modified duties effectively.',
            'Some difficulty with concentration. Recommend continued medical support and mental health check-in.',
            'Worker demonstrates full compliance with restrictions. Progressing ahead of schedule.',
            'Review by occupational therapist recommended before final clearance.',
            'Worker reports improved mobility and pain management. Continue current schedule.',
            'Meeting all objectives. Clear to progress to next phase of duties.',
        ];

        return $notes[array_rand($notes)];
    }
}
