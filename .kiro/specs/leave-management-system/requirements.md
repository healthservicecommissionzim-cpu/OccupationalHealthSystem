# Leave Management System - Requirements Document

## Introduction

The Leave Management System integrates with the existing incident management workflow to automatically track and manage sick leave for work-related injury cases in compliance with HSB 3/2017 regulations. The system captures leave information during HR acknowledgement, displays leave status on a dedicated dashboard, automatically flags cases at critical leave milestones (90 and 180 days), and routes flagged cases to the Medical Board for review and decision-making. This ensures that workers receive appropriate compensation and medical oversight throughout their leave periods, with full audit trails of all decisions and transitions.

## Glossary

- **Incident**: A work-related injury case that has been reported and documented in the system
- **HR_User**: An HR staff member assigned to review and manage an incident case
- **Medical_Board**: A team of medical professionals responsible for reviewing long-term leave cases and making discharge recommendations
- **Sick_Leave_Start_Date**: The date when an approved sick leave begins for an injured worker
- **Medical_Certificate**: A document from a medical professional certifying the worker's condition and expected recovery date
- **Full_Pay_Stage**: Leave period from day 0-90 where the worker receives full salary
- **Half_Pay_Stage**: Leave period from day 91-180 where the worker receives half salary (with doctor certification of likely recovery)
- **Medical_Discharge**: The process of formally ending a worker's employment on medical grounds when recovery is not likely
- **Leave_Payment_Stage**: An enum indicating whether the worker is in the Full_Pay or Half_Pay compensation stage
- **Milestone_Flag**: An automatic system-generated flag indicating a case has reached a critical leave threshold (90 or 180 days)
- **Medical_Board_Review**: The process of Medical Board members evaluating a flagged case to determine if medical discharge should proceed
- **Audit_Log**: A record tracking when flags are applied, decisions made, and users involved in leave management actions
- **Days_Elapsed**: The calculated number of days since leave started, used to determine payment stage and trigger milestones

## Requirements

### Requirement 1: Capture Leave Information During HR Acknowledgement

**User Story:** As an HR staff member, I want to capture and record leave details when reviewing an incident case, so that the system can accurately track leave duration and determine appropriate compensation.

#### Acceptance Criteria

1. WHEN an HR user reviews a case in the HR dossier during Stage 5 (Document Verification) THEN THE System SHALL display a leave information form with required fields
2. WHEN an HR user completes the leave form THEN THE System SHALL validate that sick_leave_start_date is a valid past date not before the incident_date
3. WHEN an HR user provides a sick_leave_start_date THEN THE System SHALL calculate and display total_injury_leave_days automatically as a read-only value
4. WHEN an HR user sets medical_certificate_provided to true THEN THE System SHALL require the expected_recovery_date field to be completed
5. WHEN an HR user sets expected_recovery_date THEN THE System SHALL validate the date is after sick_leave_start_date and not more than 365 days in the future
6. WHEN an HR user selects leave_payment_stage THEN THE System SHALL validate that the selected stage matches the current days_elapsed threshold (Full_Pay for 0-90 days, Half_Pay for 91-180 days)
7. WHEN an HR user saves leave information THEN THE System SHALL persist all leave fields to the incidents table with a timestamp of when saved
8. WHEN leave information is saved THEN THE System SHALL create an audit log entry recording the HR user who made the change and all field values

### Requirement 2: Display Leave Management Dashboard

**User Story:** As an HR manager or Medical Board member, I want to view all cases with active sick leave in a centralized dashboard, so that I can monitor leave progress and identify cases needing intervention.

#### Acceptance Criteria

1. WHEN a user with HR_Manager or Medical_Board role accesses the Leave Management Dashboard THEN THE System SHALL display all incidents with non-null sick_leave_start_date
2. WHEN the Leave Management Dashboard loads THEN THE System SHALL display a table with columns: reference_number, worker_name, facility_name, days_elapsed, payment_stage, status, and actions
3. WHEN displaying cases THEN THE System SHALL group cases into three visible sections: Full_Pay (0-90 days), Half_Pay (91-180 days), and Medical_Discharge_Eligible (181+ days)
4. WHEN the dashboard loads THEN THE System SHALL display a summary card showing count of cases in each payment stage and total active leave cases
5. WHEN calculating days_elapsed THEN THE System SHALL show the exact number of days since sick_leave_start_date to today, rounded to nearest whole number
6. WHEN days_elapsed reaches 90 THEN THE System SHALL highlight that case row with a yellow/warning background color to indicate approaching milestone
7. WHEN days_elapsed reaches 180 THEN THE System SHALL highlight that case row with a red/critical background color to indicate medical discharge eligibility
8. WHEN an HR user clicks on a case row THEN THE System SHALL navigate to the case details view showing leave information and audit trail
9. WHEN the dashboard is viewed THEN THE System SHALL provide a filter to show cases by facility, district, or province
10. WHEN the dashboard loads THEN THE System SHALL provide a search box to find cases by reference_number or worker_name

### Requirement 3: Automatic 90-Day Milestone Flagging with Facility-Based Escalation

**User Story:** As a system administrator, I want cases to be automatically flagged for medical review when they reach 90 days of sick leave, so that the appropriate facility-level medical officer can initiate medical board review procedures.

#### Acceptance Criteria

1. WHEN a case's days_elapsed reaches exactly 90 THEN THE System SHALL automatically set case_status to 'REFERRED_TO_MEDICAL_BOARD' and case_flag to '90_DAY_MILESTONE'
2. WHEN the 90-day flag is applied THEN THE System SHALL create an audit log entry with flag_reason='90_DAY_MILESTONE', flag_applied_at=current_timestamp, and applied_by_system=true
3. WHEN the 90-day flag is applied THEN THE System SHALL update leave_payment_stage to 'HALF_PAY' if a medical certificate exists
4. WHEN a case reaches 90 days THEN THE System SHALL determine the appropriate medical officer based on the facility's escalation level: DMO for district facilities, Medical Superintendent for tertiary facilities, or CMO for Central Hospital
5. WHEN the flag is applied THEN THE System SHALL dispatch a notification event 'Case90DayMilestoneReached' with the assigned medical officer's details
6. WHEN the flag is applied THEN THE System SHALL send an email notification to the assigned HR user and PMD (or CMO for Central Hospital) showing the case reference, worker name, days elapsed, leave information, and request for medical board review decision
7. WHEN the 90-day check runs THEN THE System SHALL only flag cases that have a sick_leave_start_date AND are currently in 'Under_HR_Review' or 'Active_Leave' status
8. WHEN the flag is applied THEN THE System SHALL preserve all existing leave data (sick_leave_start_date, expected_recovery_date, medical_certificate_provided) unchanged

### Requirement 4: Automatic 180-Day Milestone Flagging and Medical Discharge Eligibility with Escalation

**User Story:** As the system, I want cases that reach 180 days of sick leave to be automatically flagged as eligible for medical discharge with notification to the appropriate facility-level medical officer, so that discharge procedures can be initiated through the correct governance channel.

#### Acceptance Criteria

1. WHEN a case's days_elapsed reaches exactly 180 THEN THE System SHALL automatically set case_status to 'MEDICAL_DISCHARGE_ELIGIBLE' and case_flag to '180_DAY_MILESTONE'
2. WHEN the 180-day flag is applied THEN THE System SHALL create an audit log entry with flag_reason='180_DAY_MILESTONE', flag_applied_at=current_timestamp, and applied_by_system=true
3. WHEN a case reaches 180 days THEN THE System SHALL determine the appropriate medical officer based on the facility's escalation level: DMO for district facilities, Medical Superintendent for tertiary facilities, or CMO for Central Hospital
4. WHEN the flag is applied THEN THE System SHALL dispatch a notification event 'Case180DayMilestoneReached' with the assigned medical officer's details
5. WHEN the flag is applied THEN THE System SHALL send an email notification to the assigned HR user and PMD (or CMO for Central Hospital) showing case details, days elapsed, leave information, and request for medical board review decision
6. WHEN the flag is applied THEN THE System SHALL NOT automatically terminate employment; it only marks the case as eligible for the discharge process
7. WHEN the 180-day check runs THEN THE System SHALL only flag cases where days_elapsed >= 180 AND case_flag != '180_DAY_MILESTONE' (to avoid duplicate flagging)
8. WHEN the flag is applied THEN THE System SHALL preserve all leave data and mark the case for medical officer decision-making without modifying leave fields

### Requirement 5: Automated Daily Leave Status Check

**User Story:** As a system administrator, I want leave milestones to be checked automatically via a scheduler, so that cases are flagged promptly without manual intervention.

#### Acceptance Criteria

1. WHEN the daily scheduler runs THEN THE System SHALL execute a leave milestone check job for all incidents with active sick leave
2. WHEN the job runs THEN THE System SHALL identify cases where days_elapsed = 90 and apply the 90-day flag
3. WHEN the job runs THEN THE System SHALL identify cases where days_elapsed >= 180 and apply the 180-day flag
4. WHEN the check runs THEN THE System SHALL log all actions to the application log with timestamp, count of cases flagged, and any errors
5. WHEN a case is flagged THEN THE System SHALL create an entry in the audit_logs table with job_run_id for traceability
6. WHEN the job runs THEN THE System SHALL continue processing even if one case fails, logging the error but moving to the next case
7. WHEN the scheduler job completes THEN THE System SHALL send a summary report to the system administrator with total cases checked and total cases flagged

### Requirement 6: Medical Board Dashboard for Flagged Cases

**User Story:** As a Medical Board member, I want to view all flagged cases needing medical review, so that I can assess each case and make informed discharge recommendations.

#### Acceptance Criteria

1. WHEN a Medical_Board_Member user accesses the Medical Board Dashboard THEN THE System SHALL display all cases where case_status IN ('REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE')
2. WHEN the dashboard loads THEN THE System SHALL display a table with columns: reference_number, worker_name, days_elapsed, flag_reason, flagged_at, and action buttons
3. WHEN displaying cases THEN THE System SHALL sort by flagged_at in ascending order so newest flags appear first
4. WHEN displaying a case THEN THE System SHALL show the flag_reason ('90_DAY_MILESTONE' or '180_DAY_MILESTONE') prominently
5. WHEN a Medical Board member clicks on a case THEN THE System SHALL display a detailed review view showing: incident details, leave information, medical certificate (if provided), expected_recovery_date, and audit trail
6. WHEN reviewing a case THEN THE System SHALL display a decision form with options: 'Approve_Return_To_Work', 'Recommend_Extended_Leave', 'Recommend_Medical_Discharge'
7. WHEN a Medical Board member submits a decision THEN THE System SHALL record the decision with decision_by_user_id, decision_timestamp, decision_reason text field, and update case_status accordingly
8. WHEN a decision is recorded THEN THE System SHALL create an audit log entry with the decision details
9. WHEN the dashboard loads THEN THE System SHALL display a count of pending cases needing decision and average days in review state

### Requirement 7: Leave Information Validation Rules

**User Story:** As the system, I want to validate all leave data to ensure compliance with HSB 3/2017 regulations, so that incorrect leave calculations cannot be recorded.

#### Acceptance Criteria

1. WHEN an HR user attempts to save leave information THEN THE System SHALL validate sick_leave_start_date is not after today's date
2. WHEN an HR user attempts to save leave information THEN THE System SHALL validate sick_leave_start_date is not before the incident_date
3. WHEN medical_certificate_provided is true THEN THE System SHALL require expected_recovery_date to be provided
4. WHEN medical_certificate_provided is false THEN THE System SHALL allow expected_recovery_date to be null
5. WHEN calculating leave_payment_stage THEN THE System SHALL set to 'FULL_PAY' if days_elapsed is 0-90
6. WHEN calculating leave_payment_stage THEN THE System SHALL set to 'HALF_PAY' if days_elapsed is 91-180 AND medical_certificate_provided is true
7. IF days_elapsed > 180 AND medical_certificate_provided is false THEN THE System SHALL prevent saving and display error message requiring medical certificate
8. WHEN days_elapsed changes due to time passing THEN THE System SHALL NOT retroactively recalculate leave_payment_stage; it shall only update on user-initiated save

### Requirement 8: Audit Trail for Leave Management

**User Story:** As an administrator, I want complete audit trails of all leave-related changes, so that I can track who made changes and when for compliance purposes.

#### Acceptance Criteria

1. WHEN leave information is created or updated THEN THE System SHALL create an audit log entry with incident_id, changed_by_user_id, change_timestamp, old_values, and new_values
2. WHEN a flag is automatically applied THEN THE System SHALL create an audit log entry with flag_reason, flag_applied_at, applied_by_system=true, and affected_fields
3. WHEN a Medical Board decision is made THEN THE System SHALL create an audit log entry with decision_type, decided_by_user_id, decision_timestamp, and decision_reason
4. WHEN an audit log is created THEN THE System SHALL store all values as JSON to preserve complete history
5. WHEN viewing case details THEN THE System SHALL display an audit trail section showing all leave-related changes in reverse chronological order
6. WHEN viewing the audit trail THEN THE System SHALL display each entry with: action description, user name, timestamp, and changed fields (before/after values highlighted)
7. WHEN a user views the audit trail THEN THE System SHALL only show entries where the user has permission to view incident details

### Requirement 9: Integration with Existing Incident Workflow

**User Story:** As a system, I want leave management to integrate seamlessly with the existing incident workflow, so that leave tracking doesn't disrupt current processes.

#### Acceptance Criteria

1. WHEN an HR user is reviewing a case in Stage 5 (Document Verification) THEN THE System SHALL display the leave information form alongside existing case documents
2. WHEN a case transitions from HR_Review to Medical_Board_Review via 90-day flag THEN THE System SHALL update the case status while preserving all existing data
3. WHEN leave information is captured THEN THE System SHALL NOT affect supervisor verification, witness statements, or first aid records
4. WHEN a case is returned from Medical Board to HR for additional information THEN THE System SHALL preserve all leave data and only update the case status
5. WHEN an incident is in any status THEN THE System SHALL allow HR users to view and update leave information if the case has active sick leave
6. WHEN a case progresses through milestones THEN THE System SHALL maintain all existing incident data, attachments, and verification status unchanged

### Requirement 10: Leave Status Calculations

**User Story:** As the system, I want to accurately calculate all leave-related metrics, so that payment stages and milestones are determined correctly.

#### Acceptance Criteria

1. WHEN days_elapsed is calculated THEN THE System SHALL use the formula: days_elapsed = DATEDIFF(today, sick_leave_start_date)
2. WHEN days_elapsed is 0 THEN THE System SHALL classify as day 1 of sick leave (not day 0)
3. WHEN calculating payment stage THEN THE System SHALL use: days 1-90 = Full_Pay, days 91-180 = Half_Pay, 181+ = Medical_Discharge_Eligible
4. WHEN a user views a case THEN THE System SHALL display days_elapsed as the actual elapsed days and also show the date range (e.g., "45 days (Jan 15 - Mar 1, 2024)")
5. WHEN expected_recovery_date is in the past THEN THE System SHALL display a warning that recovery date has passed
6. WHEN expected_recovery_date is provided THEN THE System SHALL calculate recovery_days_remaining = DATEDIFF(expected_recovery_date, today)
7. WHEN recovery_days_remaining is negative THEN THE System SHALL display "Overdue for recovery: X days" instead of a positive number
8. WHEN days_elapsed is calculated THEN THE System SHALL recalculate on every dashboard page load to ensure real-time accuracy

