<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskAssessment;
use App\Models\RiskCorrectiveAction;
use App\Models\Facility;
use App\Models\Department;
use App\Models\User;
use App\Services\RiskPredictiveAnalytics;
use Carbon\Carbon;

class RiskAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $analytics = new RiskPredictiveAnalytics();
        
        // Get some facilities
        $facilities = Facility::take(5)->get();
        $departments = Department::take(3)->get();
        
        // Find HSC user by checking role_user pivot table
        $hscUser = User::whereHas('roles', function($query) {
            $query->where('name', 'hsc_conditions');
        })->first();
        
        // Fallback to any user if HSC role not found
        if (!$hscUser) {
            $hscUser = User::first();
        }

        if ($facilities->isEmpty() || !$hscUser) {
            $this->command->warn('Need facilities and user to seed risk assessments');
            return;
        }

        $hazards = [
            [
                'hazard_description' => 'Slips, trips and falls in hospital corridors due to wet floors from cleaning operations',
                'hazard_category' => 'Physical',
                'specific_zone' => 'Main Corridor - Ward Block A',
                'task_type' => 'Cleaning and Maintenance',
                'equipment_involved' => 'Mop and bucket, floor polishing machine',
                'inherent_likelihood' => 4,
                'inherent_severity' => 3,
                'residual_likelihood' => 2,
                'residual_severity' => 2,
                'elimination_controls' => null,
                'substitution_controls' => 'Use quick-dry cleaning solutions',
                'engineering_controls' => 'Install non-slip flooring in high-traffic areas',
                'administrative_controls' => 'Wet floor signage protocol, clean during low-traffic hours (10pm-5am), staff training on proper cleaning techniques',
                'ppe_controls' => 'Non-slip footwear for cleaning staff',
            ],
            [
                'hazard_description' => 'Needlestick injuries during blood collection and IV administration in outpatient department',
                'hazard_category' => 'Biological',
                'specific_zone' => 'Outpatient Department - Treatment Room',
                'task_type' => 'Phlebotomy and IV Therapy',
                'equipment_involved' => 'Syringes, needles, IV cannulas, sharps containers',
                'inherent_likelihood' => 5,
                'inherent_severity' => 4,
                'residual_likelihood' => 2,
                'residual_severity' => 3,
                'elimination_controls' => null,
                'substitution_controls' => 'Use safety-engineered needles with retractable mechanisms',
                'engineering_controls' => 'Sharps disposal containers at point of use, hands-free bin opening',
                'administrative_controls' => 'No recapping policy, sharps injury reporting protocol, Hepatitis B vaccination for all staff, monthly safety audits',
                'ppe_controls' => 'Nitrile gloves, eye protection when splash risk',
            ],
            [
                'hazard_description' => 'Manual handling injuries from lifting patients in geriatric ward without mechanical assistance',
                'hazard_category' => 'Ergonomic',
                'specific_zone' => 'Geriatric Ward - Patient Bays 1-6',
                'task_type' => 'Patient Transfer and Repositioning',
                'equipment_involved' => 'Hospital beds, wheelchairs, transfer boards',
                'inherent_likelihood' => 5,
                'inherent_severity' => 4,
                'residual_likelihood' => 3,
                'residual_severity' => 3,
                'elimination_controls' => null,
                'substitution_controls' => null,
                'engineering_controls' => 'Install ceiling-mounted patient lifts, provide slide sheets and transfer boards, height-adjustable beds',
                'administrative_controls' => 'Two-person lift policy for patients >50kg, manual handling training every 6 months, risk assessment before each lift',
                'ppe_controls' => 'Back support belts (optional, not primary control)',
            ],
            [
                'hazard_description' => 'Chemical exposure to formaldehyde during specimen preservation in pathology laboratory',
                'hazard_category' => 'Chemical',
                'specific_zone' => 'Pathology Lab - Specimen Processing Area',
                'task_type' => 'Tissue Fixation and Preservation',
                'equipment_involved' => 'Formaldehyde solution (37%), specimen jars, fume cupboard',
                'inherent_likelihood' => 4,
                'inherent_severity' => 4,
                'residual_likelihood' => 2,
                'residual_severity' => 3,
                'elimination_controls' => null,
                'substitution_controls' => 'Investigate alcohol-based fixatives for suitable specimen types',
                'engineering_controls' => 'Fully enclosed fume cupboard with face velocity >0.5m/s, local exhaust ventilation, secondary containment trays',
                'administrative_controls' => 'SDS available, chemical hygiene plan, annual air monitoring, health surveillance (lung function tests), work rotation to minimize exposure',
                'ppe_controls' => 'Nitrile gloves, chemical splash goggles, lab coat, respirator (FFP3) for spill cleanup',
            ],
            [
                'hazard_description' => 'Violence and aggression from patients in psychiatric emergency department',
                'hazard_category' => 'Psychosocial',
                'specific_zone' => 'Psychiatric Emergency - Assessment Rooms',
                'task_type' => 'Patient Assessment and Crisis Intervention',
                'equipment_involved' => 'Interview furniture, panic alarms',
                'inherent_likelihood' => 5,
                'inherent_severity' => 3,
                'residual_likelihood' => 3,
                'residual_severity' => 2,
                'elimination_controls' => null,
                'substitution_controls' => null,
                'engineering_controls' => 'Duress alarms linked to security, CCTV monitoring, interview rooms with two exits, safety glazing on windows',
                'administrative_controls' => 'Always have two staff present for high-risk patients, de-escalation training, patient risk assessment on arrival, security response protocol <2 minutes',
                'ppe_controls' => 'Personal alarm devices for all staff',
            ],
            [
                'hazard_description' => 'Electrical shock hazard from damaged power cables and overloaded sockets in operating theatre',
                'hazard_category' => 'Physical',
                'specific_zone' => 'Main Operating Theatre - OT 3',
                'task_type' => 'Surgical Procedures',
                'equipment_involved' => 'Surgical equipment, monitoring devices, power distribution',
                'inherent_likelihood' => 3,
                'inherent_severity' => 5,
                'residual_likelihood' => 1,
                'residual_severity' => 4,
                'elimination_controls' => null,
                'substitution_controls' => null,
                'engineering_controls' => 'RCD protection on all circuits, regular electrical testing (PAT), dedicated circuits for critical equipment, cable management systems',
                'administrative_controls' => 'Daily pre-use equipment checks, quarterly electrical inspections by qualified electrician, fault reporting system, no daisy-chaining of extension leads',
                'ppe_controls' => 'Insulated tools for maintenance staff',
            ],
        ];

        foreach ($hazards as $index => $hazardData) {
            $facility = $facilities->random();
            $department = $departments->random();

            $riskData = array_merge($hazardData, [
                'facility_id' => $facility->id,
                'department_id' => $department->id,
                'status' => 'ACTIVE',
                'last_reviewed_date' => Carbon::now()->subMonths(rand(1, 5)),
                'next_review_date' => Carbon::now()->addMonths(rand(6, 12)),
                'created_by' => $hscUser->id,
                'approved_by' => $hscUser->id,
                'approved_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            $assessment = RiskAssessment::create($riskData);

            // Run AI predictions
            $analytics->updateRiskAssessmentPredictions($assessment);

            // Add some corrective actions
            if ($assessment->residual_risk_score >= 10 || $assessment->escalation_warning) {
                $actions = [
                    [
                        'action_type' => 'CORRECTIVE',
                        'action_description' => 'Conduct comprehensive safety inspection and document all hazards',
                        'priority' => 'HIGH',
                        'status' => 'IN_PROGRESS',
                        'completion_percentage' => rand(30, 70),
                        'due_date' => Carbon::now()->addDays(rand(7, 21)),
                    ],
                    [
                        'action_type' => 'PREVENTIVE',
                        'action_description' => 'Implement enhanced monitoring and regular safety audits',
                        'priority' => 'MEDIUM',
                        'status' => 'PENDING',
                        'completion_percentage' => 0,
                        'due_date' => Carbon::now()->addDays(rand(30, 60)),
                    ],
                ];

                foreach ($actions as $actionData) {
                    $actionData['risk_assessment_id'] = $assessment->id;
                    $actionData['assigned_to'] = $hscUser->id;
                    $actionData['created_by'] = $hscUser->id;
                    
                    RiskCorrectiveAction::create($actionData);
                }
            }
        }

        $this->command->info('Created ' . count($hazards) . ' risk assessments with AI predictions and corrective actions');
    }
}
