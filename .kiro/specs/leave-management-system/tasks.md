# Leave Management System - Implementation Plan

## Overview

This implementation plan breaks down the Leave Management System into discrete coding tasks that build incrementally, starting with database schema and data model enhancements, followed by core business logic services, then UI components for HR and Medical Board workflows, and finally the automated scheduler. Each task validates core functionality through code, with property-based tests written alongside implementation to catch issues early.

## Tasks

- [x] 1. Database Schema and Model Setup
  - [x] 1.1 Create migration for leave management fields on incidents table
    - Add sick_leave_start_date, medical_certificate_provided, expected_recovery_date, leave_payment_stage, leave_status, leave_information_saved_at, saved_by_user_id columns
    - Add indexes on sick_leave_start_date, leave_payment_stage, leave_status for dashboard query performance
    - _Requirements: 1.1, 1.7_

  - [x] 1.2 Create migration for incident_leave_milestones table
    - Create table with id, incident_id, milestone_type, days_elapsed_at_flag, flag_reason, applied_by_system, applied_by_user_id, applied_at, notification_sent_at columns
    - Add foreign key to incidents table and indexes on milestone_type and applied_at
    - _Requirements: 3.1, 3.2, 4.1, 4.2_

  - [x] 1.3 Enhance Incident model with leave-related methods and relationships
    - Add fillable fields for all leave columns
    - Add relationships: leaveInformationSavedBy(), leaveMilestones()
    - Add calculated properties: getDaysElapsedAttribute(), getRecoveryDaysRemainingAttribute()
    - Add helper methods: isFullPayStage(), isHalfPayStage(), isMedicalDischargeEligible(), hasActiveLeave()
    - Add milestone detection methods: is90DayMilestonePending(), is180DayMilestonePending(), getLeavePaymentStage()
    - _Requirements: 1.1, 1.3, 1.6, 2.1, 3.1, 3.3, 4.1, 10.1_

  - [ ]* 1.4 Write property tests for Incident model calculated properties
    - **Property 2: Days Elapsed Calculation Accuracy**
    - **Property 6: Payment Stage Determination Logic**
    - **Validates: Requirements 1.3, 1.6, 10.1**

- [x] 2. Leave Management Service and Core Logic
  - [x] 2.1 Create LeaveManagementService class with leave data validation and persistence
    - Implement saveLeaveInformation() method with validation
    - Implement validateLeaveData() with all business rules from requirements
    - Implement calculatePaymentStage() with logic for Full_Pay/Half_Pay determination
    - Create audit log entry on successful save with old/new values in JSON format
    - _Requirements: 1.1, 1.2, 1.4, 1.5, 1.6, 1.7, 1.8, 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 7.8_

  - [ ]* 2.2 Write property tests for LeaveManagementService validation
    - **Property 1: Leave Data Persistence Round Trip**
    - **Property 3: Leave Start Date Must Not Precede Incident Date**
    - **Property 4: Medical Certificate Required Field Validation**
    - **Property 5: Recovery Date Must Be After Leave Start**
    - **Property 24: Validation Rejects Over-Future Recovery Dates**
    - **Property 25: Validation Rejects Future Leave Start Dates**
    - **Validates: Requirements 1.2, 1.4, 1.5, 1.7, 7.1, 7.2, 7.3, 7.4, 7.5**

  - [x] 2.3 Implement milestone checking and flagging logic in LeaveManagementService
    - Implement checkAndApplyMilestones() method that queries all incidents with active leave
    - Implement apply90DayMilestone() to update status, create milestone record, create audit log
    - Implement apply180DayMilestone() to update status, create milestone record, create audit log
    - Ensure milestone methods dispatch events (CaseReferredToMedicalBoard, CaseEligibleForMedicalDischarge)
    - Add error handling to log failures but continue processing remaining cases
    - _Requirements: 3.1, 3.2, 3.3, 3.6, 3.7, 4.1, 4.2, 4.5, 4.6, 4.7, 5.2, 5.3, 5.4, 5.5, 5.6_

  - [ ]* 2.4 Write property tests for milestone flagging logic
    - **Property 11: 90-Day Milestone Automatically Flags Case**
    - **Property 12: 90-Day Flag Payment Stage Update**
    - **Property 13: 90-Day Flag Creates Audit Log**
    - **Property 14: 180-Day Milestone Automatically Flags Case**
    - **Property 15: Duplicate 180-Day Flagging Prevented**
    - **Property 16: 180-Day Flag Preserves Leave Data**
    - **Validates: Requirements 3.1, 3.2, 3.3, 4.1, 4.2, 4.6, 4.7**

- [x] 3. Events and Notifications
  - [x] 3.1 Create Case90DayMilestoneReached and Case180DayMilestoneReached events
    - Create both event classes under app/Events with Incident property
    - _Requirements: 3.4, 3.5, 4.3, 4.4_

  - [x] 3.2 Create SendMilestoneNotifications listener with facility-based escalation routing
    - Implement listener that handles both Case90DayMilestoneReached and Case180DayMilestoneReached events
    - Implement getAppropriateMedicalOfficer() method to route for medical board review request:
      - PRIMARY level (Rural Health Centre, Health Post, Clinic, Polyclinic) → PMD
      - SECONDARY level (District Hospital, Rural Hospital, Mission Hospital) → PMD
      - TERTIARY level (Provincial Hospital, General Hospital) → PMD
      - QUATERNARY level (Central Hospital) → CMO
    - Send email to assigned HR user and appropriate medical officer (PMD or CMO) with medical board review decision request
    - Update notification_sent_at on milestone record
    - Create audit log entry with medical officer role and details
    - _Requirements: 3.5, 3.6, 4.4, 4.5_

  - [x] 3.3 Register listener in AppServiceProvider
    - Wire listener to both Case90DayMilestoneReached and Case180DayMilestoneReached events in boot() method
    - _Requirements: 3.4, 4.3_

- [x] 4. Scheduler Job for Automated Milestone Checking
  - [x] 4.1 Create CheckLeaveMilestonesJob
    - Implement ShouldQueue interface for background processing
    - Call LeaveManagementService::checkAndApplyMilestones() in handle() method
    - Log job completion with counts of flagged_90, flagged_180, and any errors
    - Send summary email to admin with results
    - Catch and log exceptions, then re-throw for queue retry
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7_

  - [x] 4.2 Create/update console kernel to schedule daily job
    - Schedule CheckLeaveMilestonesJob to run daily at 2:00 AM
    - Add schedule() method call in Console/Kernel.php
    - _Requirements: 5.1_

  - [ ]* 4.3 Write property tests for scheduler integration
    - **Property 17: Scheduler Processes All Active Leave Cases**
    - **Property 18: Scheduler Continues on Individual Case Failure**
    - **Validates: Requirements 5.1, 5.2, 5.3, 5.6**

- [x] 5. HR Leave Information Capture Form and UI
  - [x] 5.1 Create LeaveInformationController with form display and save methods
    - Create show() method to display leave form in HR dossier context
    - Create store() method to handle form submission via AJAX
    - Authorize user has HR_User role
    - Delegate validation and persistence to LeaveManagementService
    - Return JSON response with success/error messages
    - _Requirements: 1.1, 1.7, 1.8, 9.1, 9.2_

  - [x] 5.2 Create leave information form Blade template/component
    - Add fields: sick_leave_start_date (date picker), medical_certificate_provided (toggle), expected_recovery_date (date picker)
    - Show calculated days_elapsed and payment_stage as read-only fields
    - Include date validation messages and conditional field display
    - Add submit button and error display
    - _Requirements: 1.1, 1.3_

  - [x] 5.3 Update HR dossier view to include leave information form
    - Integrate leave form component into Stage 5 (Document Verification) section
    - Show form only if case is in appropriate status for leave capture
    - Display existing leave data if already saved
    - _Requirements: 1.1, 9.1_

  - [ ]* 5.4 Write unit tests for HR leave capture form
    - Test form displays required fields
    - Test successful leave information save
    - Test validation errors display correctly
    - Test read-only fields don't change on form save

- [x] 6. Leave Management Dashboard
  - [x] 6.1 Create LeaveManagementController with dashboard method
    - Query all incidents with non-null sick_leave_start_date
    - Implement filtering by facility_id, district_id, province_id
    - Implement search by reference_number or worker name
    - Paginate results (20 per page)
    - Calculate statistics: full_pay_count, half_pay_count, discharge_eligible_count
    - Return view with incidents and stats
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.9, 2.10_

  - [x] 6.2 Create Leave Management Dashboard Blade template
    - Display summary cards with payment stage counts
    - Create table with columns: reference_number, worker_name, facility_name, days_elapsed, payment_stage, status, actions
    - Group cases into Full_Pay, Half_Pay, Medical_Discharge_Eligible sections with visual separators
    - Highlight rows with yellow background if days_elapsed==90, red background if days_elapsed>=180
    - Add filter dropdowns for facility/district and search box
    - Add link to case details
    - _Requirements: 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.9, 2.10_

  - [ ]* 6.3 Write property tests for dashboard filtering and grouping
    - **Property 8: Dashboard Filters Return Correct Cases**
    - **Property 9: Dashboard Groups Cases by Payment Stage**
    - **Property 10: Dashboard Summary Counts Match Actual Cases**
    - **Validates: Requirements 2.1, 2.3, 2.4, 2.9, 2.10**

- [x] 7. Medical Board Dashboard and Decision Recording
  - [x] 7.1 Create MedicalBoardController with dashboard and decision methods
    - Implement dashboard() to show cases in REFERRED_TO_MEDICAL_BOARD or MEDICAL_DISCHARGE_ELIGIBLE status
    - Sort by flagged_at ascending (newest first)
    - Load milestone and incident relationships
    - Paginate results (20 per page)
    - Calculate stats: pending_90_day_count, pending_discharge_count
    - Implement submitDecision() to record Medical Board decisions
    - Validate decision type and reason
    - Update case status based on decision type
    - Create audit log with decision details
    - Return response with success message
    - _Requirements: 6.1, 6.2, 6.3, 6.7, 6.8, 6.9_

  - [x] 7.2 Create Medical Board Dashboard Blade template
    - Display summary cards with pending case counts
    - Create table with columns: reference_number, worker_name, days_elapsed, flag_reason, flagged_at, action buttons
    - Sort by flagged_at with newest first
    - Prominently display flag_reason ('90_DAY_MILESTONE' or '180_DAY_MILESTONE')
    - Add "Review" buttons to navigate to decision form
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.9_

  - [x] 7.3 Create case review detail view with decision form
    - Display incident details (reference, worker name, facility, incident details)
    - Display leave information (start date, expected recovery date, medical certificate, days elapsed)
    - Display audit trail of all leave-related changes
    - Show decision form with radio buttons: Approve_Return_To_Work, Recommend_Extended_Leave, Recommend_Medical_Discharge
    - Add textarea for decision_reason (required)
    - Prevent form submission without reason
    - Display confirmation before submitting
    - _Requirements: 6.5, 6.6, 6.7, 6.8, 8.5, 8.6, 8.7_

  - [ ]* 7.4 Write property tests for Medical Board dashboard and decision recording
    - **Property 19: Medical Board Dashboard Shows Only Flagged Cases**
    - **Property 20: Medical Board Decision Recording**
    - **Property 21: Medical Board Decision Audit Log**
    - **Validates: Requirements 6.1, 6.7, 6.8**

- [ ] 8. Audit Trail Display and Compliance
  - [x] 8.1 Create audit trail display component
    - Query audit_logs for incident with action IN ('leave_information_saved', 'milestone_flag_applied', 'medical_board_decision')
    - Sort by created_at descending (newest first)
    - For each entry, display: action description, user name (or "System" if null), timestamp, changed fields with before/after values highlighted
    - Show JSON metadata for flag_reason or decision_reason
    - Apply permission checks to show only if user can view incident
    - _Requirements: 8.5, 8.6, 8.7_

  - [x] 8.2 Integrate audit trail into case detail view
    - Add "Audit Trail" section below decision form
    - Display audit trail for current case
    - _Requirements: 8.5, 8.6_

  - [ ]* 8.3 Write unit tests for audit trail display
    - Test audit trail shows only leave-related actions
    - Test old/new values displayed correctly
    - Test permissions prevent unauthorized viewing

- [ ] 9. Integration Testing and Workflow Validation
  - [x] 9.1 Verify HR leave capture doesn't affect existing incident data
    - Create incident in HR review status
    - Add leave information
    - Verify all non-leave fields (description, supervisor verification, documents) unchanged
    - _Requirements: 9.3, 9.4, 9.5_

  - [x] 9.2 Verify leave data preserved through milestone flagging
    - Create incident with leave information
    - Manually trigger 90-day milestone
    - Verify all leave fields unchanged after flagging
    - _Requirements: 3.7, 4.7, 9.2, 9.4, 9.6_

  - [x] 9.3 Verify case status transitions work correctly
    - Create incident and add leave data
    - Run scheduler to trigger 90-day flag
    - Verify status changed to REFERRED_TO_MEDICAL_BOARD
    - Submit Medical Board decision
    - Verify status changed to selected decision status
    - _Requirements: 3.1, 4.1, 6.7, 9.2_

  - [ ]* 9.4 Write integration tests for complete workflow
    - Test HR captures leave, scheduler flags at 90 days, Medical Board reviews and decides
    - Verify notifications sent at each milestone
    - Verify audit trail contains all actions

- [x] 10. Checkpoint - Ensure all tests pass
  - Ensure all unit tests pass
  - Ensure all property-based tests pass (minimum 100 iterations each)
  - Ensure integration tests pass
  - Ask the user if questions arise

- [ ] 11. Documentation and Deployment Preparation
  - [x] 11.1 Create database migrations execution checklist
    - Document migration order and dependencies
    - Verify no conflicts with existing migrations

  - [x] 11.2 Create configuration documentation
    - Document admin email configuration for milestone summaries
    - Document SMS configuration reuse for notifications (if needed)
    - Document scheduler configuration

  - [-] 11.3 Create user documentation
    - Document HR workflow for capturing leave information
    - Document Medical Board workflow for reviewing flagged cases
    - Document administrator scheduler monitoring

- [x] 12. Final Checkpoint - Ensure all tests pass and system ready for integration
  - Run full test suite
  - Verify all migrations execute successfully
  - Verify scheduler can execute successfully on test data
  - Verify notifications dispatch correctly
  - Ask the user if questions arise

## Notes

- Tasks marked with `*` are optional testing sub-tasks and can be skipped for faster MVP
- Property-based tests (marked as optional) use fast-check or similar PBT framework for PHP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation before moving to next phase
- Leave calculation uses exact day count (DATEDIFF), not approximate days
- All timestamps in UTC for consistency
- Audit trail provides full forensic capability for compliance audits
- Notifications integrate with existing document-upload notification system patterns

