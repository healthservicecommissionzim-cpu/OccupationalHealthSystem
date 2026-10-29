-- Populate comprehensive test case for HSC Conditions Officer view
-- Reference: INC-6AD89A67CF348

-- First, ensure we have the necessary lookup data
SET @province_id = (SELECT id FROM provinces WHERE name = 'Harare' LIMIT 1);
SET @district_id = (SELECT id FROM districts WHERE name = 'Harare' AND province_id = @province_id LIMIT 1);
SET @facility_id = (SELECT id FROM facilities WHERE name = 'Parirenyatwa Group of Hospitals' LIMIT 1);
SET @facility_type_id = (SELECT id FROM facility_types WHERE name = 'Hospital' LIMIT 1);
SET @department_id = (SELECT id FROM departments WHERE name = 'Emergency Department' LIMIT 1);
SET @designation_id = (SELECT id FROM designations WHERE name = 'Nurse' LIMIT 1);
SET @supervisor_designation_id = (SELECT id FROM designations WHERE name = 'Senior Nursing Officer' LIMIT 1);
SET @incident_type_id = (SELECT id FROM incident_types WHERE name = 'Needle Stick Injury' LIMIT 1);
SET @supervisor_id = (SELECT id FROM users WHERE email = 'supervisor@test.com' LIMIT 1);
SET @hr_user_id = (SELECT id FROM users WHERE email = 'hr@test.com' LIMIT 1);

-- Insert main incident record with comprehensive data
INSERT INTO incidents (
    reference_number, first_name, surname, id_number, ec_number, 
    home_address, phone_number, email, incident_date, incident_time,
    incident_type_id, incident_description, facility_type_id, province_id, 
    district_id, facility_id, department_id, designation_id,
    supervisor_designation_id, assigned_supervisor_id, supervisor_name,
    status, compliance_status, on_duty_confirmation,
    supervisor_verified_at, hod_report, hod_report_submitted_at,
    escalated_to_hr_at, routed_to_hr_user_id,
    escalated_to_dmo_at, executive_audit_verified,
    escalated_to_pmd_at, hr_review_notes,
    compliance_notes, resolved_at, created_at, updated_at
) VALUES (
    'INC-6AD89A67CF348', 'John', 'Doe', '123456789', 'EC001',
    '45 Main Street, Harare', '+263712345678', 'john.doe@email.com', 
    DATE_SUB(NOW(), INTERVAL 15 DAY), '14:30',
    @incident_type_id, 
    'Healthcare worker sustained a needle stick injury while administering IV medication to a patient in the emergency department. The patient had not been screened for bloodborne pathogens.',
    @facility_type_id, @province_id, @district_id, @facility_id, 
    @department_id, @designation_id, @supervisor_designation_id,
    @supervisor_id, 'Senior Nursing Officer Smith',
    'ESCALATED_TO_PMD', 'COMPLIANT', 1,
    DATE_SUB(NOW(), INTERVAL 14 DAY), 
    'Employee was performing official duties at time of incident. All protocols were being followed. Incident was reported immediately. Employee has been placed on post-exposure prophylaxis (PEP) protocol as per standard procedure.',
    DATE_SUB(NOW(), INTERVAL 13 DAY),
    DATE_SUB(NOW(), INTERVAL 12 DAY), @hr_user_id,
    DATE_SUB(NOW(), INTERVAL 8 DAY), 1,
    DATE_SUB(NOW(), INTERVAL 2 DAY),
    'HR has reviewed all documentation. Employee has been following medical protocols. Case escalated to Provincial Medical Directorate for final determination. All documentation is complete and compliant.',
    'Case reviewed for occupational health compliance. All requirements met. Employee has been advised on post-exposure prophylaxis continuation and follow-up appointments scheduled.',
    DATE_SUB(NOW(), INTERVAL 1 DAY),
    NOW(), NOW()
) ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Get the incident ID
SET @incident_id = (SELECT id FROM incidents WHERE reference_number = 'INC-6AD89A67CF348' LIMIT 1);

-- Insert First Aid Record
INSERT INTO first_aid_records (
    incident_id, nurse_name, nurse_license_number, time_administered,
    location, severity_level, injury_description, first_aid_description,
    notes, status, reviewed_by_user_id, reviewed_at, created_at, updated_at
) VALUES (
    @incident_id,
    'Sister Mary Mthembu', 'RN/ZIM/2020/001234', DATE_SUB(NOW(), INTERVAL 14 DAY),
    'Emergency Department - Resuscitation Bay 2', 'MODERATE',
    'Needle stick injury to right index finger from contaminated needle. Minor bleeding observed. Immediate site washing and disinfection performed with 70% alcohol.',
    'Site washed thoroughly with soap and water for 15 minutes. Applied antiseptic solution (Betadine). Covered with sterile dressing. Blood expressed from wound to encourage cleansing. Employee guided to occupational health clinic for baseline testing and PEP protocol initiation.',
    'Employee reported no prior similar incidents. Vaccination status confirmed (Hepatitis B series complete). Baseline bloodwork ordered. Post-exposure prophylaxis regime commenced same day.',
    'approved', 1, NOW(), NOW(), NOW()
) ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Insert Witness Tokens (Witness Statements)
INSERT INTO incident_witness_tokens (
    incident_id, witness_name, phone_number, token, status,
    witness_statement, statement_submitted_at, created_at, updated_at
) VALUES 
(
    @incident_id,
    'Dr. Samuel Kondo', '+263718765432', 'WITNESS_TOKEN_001', 'COMPLETED',
    'I was present in the emergency department when the needle stick incident occurred. I observed Sister Mary Mthembu immediately report the incident and follow proper protocols. She washed the wound thoroughly and reported to occupational health immediately. The incident was handled professionally and in accordance with standard procedures.',
    DATE_SUB(NOW(), INTERVAL 11 DAY), NOW(), NOW()
),
(
    @incident_id,
    'Grace Zvandamwe', '+263715432109', 'WITNESS_TOKEN_002', 'COMPLETED',
    'I was assisting with patient care when the needle stick incident happened. The healthcare worker who was injured immediately notified the charge nurse. Proper first aid was administered. The employee was calm and professional throughout the incident response. All safety protocols were followed correctly.',
    DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), NOW()
);

-- Insert Supporting Documents (Attachments)
INSERT INTO incident_attachments (
    incident_id, document_type, file_name, file_path, mime_type,
    file_size, upload_status, upload_notes, uploaded_by_user_id, created_at, updated_at
) VALUES
(
    @incident_id,
    'doctors_report',
    'John_Doe_Doctors_Report_20260110.pdf',
    'storage/app/public/incident-attachments/' + CAST(@incident_id AS CHAR) + '/doctors_report_001.pdf',
    'application/pdf',
    245000,
    'VERIFIED',
    'Medical report from occupational health clinic. Employee baseline testing completed. PEP protocol initiated.',
    @hr_user_id,
    NOW(), NOW()
),
(
    @incident_id,
    'injury_report',
    'John_Doe_Injury_Report_Z1149_20260109.pdf',
    'storage/app/public/incident-attachments/' + CAST(@incident_id AS CHAR) + '/injury_report_001.pdf',
    'application/pdf',
    189500,
    'VERIFIED',
    'Z1149 Injury on Duty form completed and signed by employer and employee.',
    @hr_user_id,
    NOW(), NOW()
),
(
    @incident_id,
    'treatment_records',
    'John_Doe_Treatment_Records_20260108.pdf',
    'storage/app/public/incident-attachments/' + CAST(@incident_id AS CHAR) + '/treatment_records_001.pdf',
    'application/pdf',
    312000,
    'VERIFIED',
    'Complete treatment records from occupational health clinic including baseline HIV/Hepatitis B/Hepatitis C testing results.',
    @hr_user_id,
    NOW(), NOW()
);

-- Insert Incident Notifications (audit trail)
INSERT INTO incident_notifications (
    incident_id, notification_type, notification_channel, status,
    recipient_user_id, sent_at, created_at, updated_at
) VALUES
(
    @incident_id,
    'SUPERVISOR_VERIFICATION',
    'email',
    'SENT',
    @supervisor_id,
    DATE_SUB(NOW(), INTERVAL 14 DAY),
    NOW(), NOW()
),
(
    @incident_id,
    'ESCALATED_TO_HR',
    'email',
    'SENT',
    @hr_user_id,
    DATE_SUB(NOW(), INTERVAL 12 DAY),
    NOW(), NOW()
),
(
    @incident_id,
    'DOCUMENT_UPLOAD',
    'email',
    'SENT',
    @hr_user_id,
    DATE_SUB(NOW(), INTERVAL 11 DAY),
    NOW(), NOW()
),
(
    @incident_id,
    'ESCALATED_TO_DMO',
    'email',
    'SENT',
    @hr_user_id,
    DATE_SUB(NOW(), INTERVAL 8 DAY),
    NOW(), NOW()
);

SELECT 'Test case INC-6AD89A67CF348 has been successfully populated with comprehensive details!' AS status;
SELECT 'Visit the HSC Conditions view to see the complete case dossier.' AS next_step;
