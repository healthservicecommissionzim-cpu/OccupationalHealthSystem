<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\FirstAidRecord;
use App\Models\IncidentWitnessToken;
use App\Models\IncidentAttachment;
use App\Models\IncidentNotification;
use Carbon\Carbon;

class TestIncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create test users
        $supervisor = User::where('email', 'supervisor@test.com')->first() ?? User::create([
            'name' => 'John Smith',
            'email' => 'supervisor@test.com',
            'password' => bcrypt('password'),
            'phone_number' => '+263718765432',
        ]);

        $hrUser = User::where('email', 'hr@test.com')->first() ?? User::create([
            'name' => 'Jane HR Officer',
            'email' => 'hr@test.com',
            'password' => bcrypt('password'),
            'phone_number' => '+263787654321',
        ]);

        // Get lookup data
        $province = \App\Models\Province::where('name', 'Harare')->first();
        $district = \App\Models\District::where('name', 'Harare')->first();
        $facility = \App\Models\Facility::first();
        $facilityType = \App\Models\FacilityType::first();
        $department = \App\Models\Department::first();
        $designation = \App\Models\Designation::where('name', 'Nurse')->first() ?? \App\Models\Designation::first();
        $incidentType = \App\Models\IncidentType::first();

        // Create comprehensive test incident
        $incident = Incident::updateOrCreate(
            ['reference_number' => 'INC-6AD89A67CF348'],
            [
                'first_name' => 'John',
                'surname' => 'Doe',
                'id_number' => '123456789',
                'ec_number' => 'EC001',
                'home_address' => '45 Main Street, Harare',
                'phone_number' => '+263712345678',
                'email' => 'john.doe@email.com',
                'incident_date' => Carbon::now()->subDays(15),
                'incident_time' => '14:30',
                'incident_type_id' => $incidentType->id,
                'incident_description' => 'Healthcare worker sustained a needle stick injury while administering IV medication to a patient in the emergency department. The patient had not been screened for bloodborne pathogens.',
                'facility_type_id' => $facilityType->id,
                'province_id' => $province->id,
                'district_id' => $district->id,
                'facility_id' => $facility->id,
                'department_id' => $department->id,
                'designation_id' => $designation->id,
                'supervisor_designation_id' => $designation->id,
                'assigned_supervisor_id' => $supervisor->id,
                'supervisor_name' => $supervisor->name,
                'status' => 'ESCALATED_TO_PMD',
                'compliance_status' => 'COMPLIANT',
                'on_duty_confirmation' => true,
                'supervisor_verified_at' => Carbon::now()->subDays(14),
                'hod_report' => 'Employee was performing official duties at time of incident. All protocols were being followed. Incident was reported immediately. Employee has been placed on post-exposure prophylaxis (PEP) protocol as per standard procedure.',
                'hod_report_submitted_at' => Carbon::now()->subDays(13),
                'escalated_to_hr_at' => Carbon::now()->subDays(12),
                'routed_to_hr_user_id' => $hrUser->id,
                'escalated_to_dmo_at' => Carbon::now()->subDays(8),
                'executive_audit_verified' => true,
                'escalated_to_pmd_at' => Carbon::now()->subDays(2),
                'hr_review_notes' => 'HR has reviewed all documentation. Employee has been following medical protocols. Case escalated to Provincial Medical Directorate for final determination. All documentation is complete and compliant.',
                'compliance_notes' => 'Case reviewed for occupational health compliance. All requirements met. Employee has been advised on post-exposure prophylaxis continuation and follow-up appointments scheduled.',
                'resolved_at' => Carbon::now()->subDays(1),
            ]
        );

        // Create First Aid Record
        FirstAidRecord::updateOrCreate(
            ['incident_id' => $incident->id],
            [
                'nurse_name' => 'Sister Mary Mthembu',
                'nurse_license_number' => 'RN/ZIM/2020/001234',
                'time_administered' => Carbon::now()->subDays(14),
                'location' => 'Emergency Department - Resuscitation Bay 2',
                'severity_level' => 'MODERATE',
                'injury_description' => 'Needle stick injury to right index finger from contaminated needle. Minor bleeding observed. Immediate site washing and disinfection performed with 70% alcohol.',
                'first_aid_description' => 'Site washed thoroughly with soap and water for 15 minutes. Applied antiseptic solution (Betadine). Covered with sterile dressing. Blood expressed from wound to encourage cleansing. Employee guided to occupational health clinic for baseline testing and PEP protocol initiation.',
                'notes' => 'Employee reported no prior similar incidents. Vaccination status confirmed (Hepatitis B series complete). Baseline bloodwork ordered. Post-exposure prophylaxis regime commenced same day.',
                'status' => 'approved',
                'reviewed_by_user_id' => $hrUser->id,
                'reviewed_at' => Carbon::now()->subDays(10),
            ]
        );

        // Create Witness Statements
        $witnesses = [
            [
                'witness_name' => 'Dr. Samuel Kondo',
                'phone_number' => '+263718765432',
                'token' => 'WITNESS_TOKEN_001',
                'status' => 'COMPLETED',
                'witness_statement' => 'I was present in the emergency department when the needle stick incident occurred. I observed Sister Mary Mthembu immediately report the incident and follow proper protocols. She washed the wound thoroughly and reported to occupational health immediately. The incident was handled professionally and in accordance with standard procedures.',
                'statement_submitted_at' => Carbon::now()->subDays(11),
            ],
            [
                'witness_name' => 'Grace Zvandamwe',
                'phone_number' => '+263715432109',
                'token' => 'WITNESS_TOKEN_002',
                'status' => 'COMPLETED',
                'witness_statement' => 'I was assisting with patient care when the needle stick incident happened. The healthcare worker who was injured immediately notified the charge nurse. Proper first aid was administered. The employee was calm and professional throughout the incident response. All safety protocols were followed correctly.',
                'statement_submitted_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($witnesses as $witness) {
            IncidentWitnessToken::updateOrCreate(
                [
                    'incident_id' => $incident->id,
                    'witness_name' => $witness['witness_name'],
                ],
                $witness
            );
        }

        // Create Attachments
        $attachments = [
            [
                'document_type' => 'doctors_report',
                'file_name' => 'John_Doe_Doctors_Report_20260110.pdf',
                'file_path' => 'storage/app/public/incident-attachments/' . $incident->id . '/doctors_report_001.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 245000,
                'upload_status' => 'VERIFIED',
                'upload_notes' => 'Medical report from occupational health clinic. Employee baseline testing completed. PEP protocol initiated.',
            ],
            [
                'document_type' => 'injury_report',
                'file_name' => 'John_Doe_Injury_Report_Z1149_20260109.pdf',
                'file_path' => 'storage/app/public/incident-attachments/' . $incident->id . '/injury_report_001.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 189500,
                'upload_status' => 'VERIFIED',
                'upload_notes' => 'Z1149 Injury on Duty form completed and signed by employer and employee.',
            ],
            [
                'document_type' => 'treatment_records',
                'file_name' => 'John_Doe_Treatment_Records_20260108.pdf',
                'file_path' => 'storage/app/public/incident-attachments/' . $incident->id . '/treatment_records_001.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 312000,
                'upload_status' => 'VERIFIED',
                'upload_notes' => 'Complete treatment records from occupational health clinic including baseline HIV/Hepatitis B/Hepatitis C testing results.',
            ],
        ];

        foreach ($attachments as $attachment) {
            IncidentAttachment::updateOrCreate(
                [
                    'incident_id' => $incident->id,
                    'document_type' => $attachment['document_type'],
                ],
                array_merge($attachment, [
                    'uploaded_by_user_id' => $hrUser->id,
                ])
            );
        }

        // Create Notifications (Audit Trail)
        $notifications = [
            [
                'notification_type' => 'SUPERVISOR_VERIFICATION',
                'notification_channel' => 'email',
                'status' => 'SENT',
                'recipient_user_id' => $supervisor->id,
                'sent_at' => Carbon::now()->subDays(14),
            ],
            [
                'notification_type' => 'ESCALATED_TO_HR',
                'notification_channel' => 'email',
                'status' => 'SENT',
                'recipient_user_id' => $hrUser->id,
                'sent_at' => Carbon::now()->subDays(12),
            ],
            [
                'notification_type' => 'DOCUMENT_UPLOAD',
                'notification_channel' => 'email',
                'status' => 'SENT',
                'recipient_user_id' => $hrUser->id,
                'sent_at' => Carbon::now()->subDays(11),
            ],
            [
                'notification_type' => 'ESCALATED_TO_DMO',
                'notification_channel' => 'email',
                'status' => 'SENT',
                'recipient_user_id' => $hrUser->id,
                'sent_at' => Carbon::now()->subDays(8),
            ],
        ];

        foreach ($notifications as $notification) {
            IncidentNotification::updateOrCreate(
                [
                    'incident_id' => $incident->id,
                    'notification_type' => $notification['notification_type'],
                ],
                $notification
            );
        }

        $this->command->info('Test incident INC-6AD89A67CF348 has been successfully created with comprehensive details!');
    }
}
