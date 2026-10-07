# Implementation Plan: Witness Collection Workflow Redesign

## Overview

This implementation plan converts the witness collection design into discrete, incremental coding tasks. The approach builds from database schema through models, services, controllers, and finally the UI layer. Each task integrates with previous work, building a complete feature with embedded testing. Testing tasks (marked with `*`) are optional for MVP but essential for production quality.

## Tasks

- [x] 1. Set up database schema and models
  - [x] 1.1 Create migration for witness_tokens table
    - Add fields: incident_id, witness_name, phone_number, witness_token, token_sent_at, witness_statement_submitted_at, witness_last_reminder_sent_at
    - Add indexes on incident_id and witness_token
    - Ensure all timestamp fields are nullable
    - _Requirements: 7.1, 7.2, 7.6_
  
  - [x] 1.2 Create WitnessToken model with relationships and helper methods
    - Define fillable attributes and casts for timestamps
    - Add incident() relationship
    - Implement hasSubmitted() and isPending() helper methods
    - _Requirements: 7.1, 7.2_
  
  - [x] 1.3 Create IncidentNotification migration for SMS history tracking
    - Add fields: incident_id, witness_token_id, notification_type, notification_channel, recipient_phone, status, message_content, sent_at, error_message
    - Add indexes on incident_id and witness_token_id
    - _Requirements: 5.1, 7.5_
  
  - [ ]* 1.4 Write property test for schema initialization
    - **Property: Schema fields are correctly initialized**
    - **Validates: Requirements 7.1, 7.2**
    - Test that new witness tokens have null submission timestamps
    - Test that all required fields are present on model

- [x] 2. Implement SMS service for witness notifications
  - [x] 2.1 Create WitnessSMSService with SMS formatting methods
    - Method: formatInitialSMS($incident, $witnessName) - returns message string with reference, link, instructions
    - Method: formatReminderSMS($incident, $witnessName) - returns reminder message
    - Method: validateMessageLength() - verifies message is within SMS limits
    - Use incident reference in message (e.g., "OHS-2026-08-001")
    - Include public link format: /incidents/{id}/witness/{token}
    - _Requirements: 2.2, 8.1, 8.2, 8.3_
  
  - [x] 2.2 Implement SMS dispatch methods in WitnessSMSService
    - Method: sendInitialNotifications($incident) - sends SMS to all witnesses
    - Method: sendReminder($witnessToken, $incident) - sends reminder SMS to single witness
    - Use existing SMSService for actual dispatch
    - Handle SMS failures gracefully (don't block form)
    - _Requirements: 2.1, 2.3, 2.4, 4.2_
  
  - [x] 2.3 Create SMS history logging in notification tracker
    - Log each SMS attempt: incident_id, witness_token_id, type (INITIAL/REMINDER), status (SENT/FAILED), timestamp
    - Record error messages if SMS fails
    - Allow supervisor to see all SMS history for a witness
    - _Requirements: 5.1, 5.2, 5.3, 5.4_
  
  - [ ]* 2.4 Write property test for SMS message content
    - **Property: SMS messages contain required elements**
    - **Validates: Requirements 2.2, 8.1, 8.2**
    - Test initial SMS contains incident reference, link, instructions
    - Test reminder SMS contains reminder indicator
    - Test public link is accessible without authentication

  - [ ]* 2.5 Write property test for SMS delivery and timestamp recording
    - **Property: SMS send timestamp is recorded when sent**
    - **Validates: Requirements 2.3, 5.3**
    - Generate random incident and witnesses
    - Send SMS and verify token_sent_at is updated
    - Verify SMS is logged in incident_notifications table

- [x] 3. Create events and listeners for witness SMS dispatch
  - [x] 3.1 Create IncidentWithWitnessesSubmitted event
    - Accept incident and witness collection in constructor
    - Make event broadcastable (optional for async handling)
    - _Requirements: 2.1_
  
  - [x] 3.2 Create SendWitnessNotifications listener
    - Handle IncidentWithWitnessesSubmitted event
    - Call WitnessSMSService to dispatch SMS for each witness
    - Update token_sent_at on successful send
    - Log errors without failing form submission
    - _Requirements: 2.1, 2.3, 2.4_
  
  - [x] 3.3 Register listener in AppServiceProvider or EventServiceProvider
    - Wire IncidentWithWitnessesSubmitted → SendWitnessNotifications
    - Configure listener to run async via queue
    - _Requirements: 2.1_
  
  - [ ]* 3.4 Write property test for event dispatch and listener
    - **Property: Event dispatch triggers SMS for all witnesses**
    - **Validates: Requirements 2.1, 2.3**
    - Dispatch event with multiple witnesses
    - Verify SMS is called for each witness
    - Verify timestamps are recorded

- [x] 4. Enhance incident form with witness collection
  - [x] 4.1 Add witness fields to incident report form view
    - Add section: "Witness Information"
    - Add two witness input groups (name, phone) with Add/Remove buttons
    - Implement client-side validation for non-empty name and valid phone format
    - Show witness count (e.g., "1/2 witnesses added")
    - _Requirements: 1.1, 1.2_
  
  - [x] 4.2 Implement form validation in IncidentController
    - Validate at least one witness is provided
    - Validate witness name is non-empty string
    - Validate phone number is non-empty and valid format (+263 or 07xx)
    - Return validation errors if incomplete
    - _Requirements: 1.6_
  
  - [x] 4.3 Create witness records from form submission
    - In IncidentController store() method, create WitnessToken for each witness
    - Generate secure random token using Str::random(32)
    - Save witness_name, phone_number, incident_id, witness_token
    - Set all timestamps to null initially
    - _Requirements: 1.4, 1.5_
  
  - [x] 4.4 Dispatch IncidentWithWitnessesSubmitted event
    - After witnesses are created, dispatch event with incident and witnesses
    - Event fires SMS dispatch in listener
    - _Requirements: 1.5, 2.1_
  
  - [ ]* 4.5 Write property test for witness form validation
    - **Property: Valid witness input creates persistent records**
    - **Validates: Requirements 1.2, 1.4**
    - Test with valid name and phone, verify witness record exists
    - Test with invalid phone format, verify validation error
    - Test maximum 2 witnesses constraint

  - [ ]* 4.6 Write property test for SMS dispatch on form submission
    - **Property: Form submission with witnesses triggers SMS notifications**
    - **Validates: Requirements 1.5, 2.1**
    - Submit incident form with witnesses
    - Verify SMS is sent to each witness's phone number
    - Verify token_sent_at is recorded

- [x] 5. Create supervisor witness management dashboard
  - [x] 5.1 Create supervisor witness dashboard view
    - Route: GET /supervisor/incidents/{incident}/witnesses
    - Display incident reference and basic info
    - Show witness count: "X submitted / Y total"
    - Create table with columns: Name, Phone, Status, Submitted Date, Last Reminder, Actions
    - _Requirements: 3.1, 3.2, 3.5_
  
  - [x] 5.2 Implement witness status badge rendering
    - For each witness, show status badge:
      - "Pending" (yellow) if witness_statement_submitted_at IS NULL
      - "Submitted" (green) with date if witness_statement_submitted_at IS NOT NULL
    - Include last reminder date if witness_last_reminder_sent_at exists
    - _Requirements: 3.3, 3.4_
  
  - [x] 5.3 Add supervisor authorization and filtering
    - Verify supervisor role on route access
    - Filter witnesses to only those for incidents supervisor manages
    - Block access to other supervisors' incidents
    - _Requirements: 9.1, 9.2_
  
  - [x] 5.4 Add statement download functionality
    - If witness has submitted, show "View/Download" button for statement
    - Link to existing statement download endpoint
    - Verify supervisor authorization before download
    - _Requirements: 3.6_
  
  - [ ]* 5.5 Write property test for witness list display
    - **Property: Supervisor dashboard displays all witnesses with correct status**
    - **Validates: Requirements 3.1, 3.2, 3.3, 3.4**
    - Create incident with mixed witness states
    - Load dashboard, verify all witnesses displayed
    - Verify submitted witnesses show "Submitted" badge with date
    - Verify pending witnesses show "Pending" badge

- [x] 6. Implement reminder SMS functionality
  - [x] 6.1 Create reminder SMS endpoint
    - Route: POST /supervisor/incidents/{incident}/witness/{tokenId}/reminder
    - Verify supervisor authorization
    - Check witness has not submitted (isPending())
    - _Requirements: 4.1, 9.3_
  
  - [x] 6.2 Dispatch reminder SMS via WitnessSMSService
    - Call WitnessSMSService->sendReminder($witnessToken, $incident)
    - Update witness_last_reminder_sent_at to current timestamp
    - Log SMS send in incident_notifications table
    - Return JSON response with status
    - _Requirements: 4.2, 4.3, 4.4_
  
  - [x] 6.3 Add reminder SMS button to dashboard
    - Show "Send Reminder" button for pending witnesses
    - Button disabled for submitted witnesses
    - Click handler sends reminder via AJAX
    - Show success/failure toast notification
    - Update last reminder date on success
    - _Requirements: 4.1, 4.2_
  
  - [x] 6.4 Add error handling for failed reminders
    - If SMS fails, display error message
    - Log failure with error details
    - Allow supervisor to retry
    - _Requirements: 4.5_
  
  - [ ]* 6.5 Write property test for reminder SMS functionality
    - **Property: Reminder SMS updates timestamp and is logged**
    - **Validates: Requirements 4.2, 4.3, 4.4**
    - Send reminder SMS to pending witness
    - Verify witness_last_reminder_sent_at is updated
    - Verify SMS is logged with type='REMINDER'
    - Send second reminder, verify timestamp increases

- [x] 7. Add SMS history display to supervisor dashboard
  - [x] 7.1 Create SMS history table/panel for each witness
    - Show all SMS events for witness (initial + reminders)
    - Display columns: Date, Type (Initial/Reminder), Status (Sent/Failed), Phone
    - Show error message if SMS failed
    - Allow retry button if SMS failed
    - _Requirements: 5.1, 5.2, 5.3, 5.4_
  
  - [x] 7.2 Query incident_notifications table for SMS history
    - Fetch all SMS records for witness_token_id
    - Order by sent_at DESC (newest first)
    - Format dates for display
    - _Requirements: 5.1_
  
  - [x] 7.3 Add audit logging for SMS sent by supervisor
    - Log supervisor user_id who initiated SMS send
    - Log timestamp of initiation
    - Store in incident_notifications.initiated_by_user_id
    - _Requirements: 9.4_
  
  - [ ]* 7.4 Write property test for SMS history completeness
    - **Property: All SMS events are logged and retrievable**
    - **Validates: Requirements 5.1, 5.2, 5.5**
    - Send multiple SMS (initial + reminders)
    - Query incident_notifications for witness
    - Verify all sends are logged with correct type and status

- [x] 8. Update statement submission flow to track timestamps
  - [x] 8.1 Hook into existing publicUploadDocuments() endpoint
    - After document upload is successful, update witness record
    - Set witness_statement_submitted_at = now()
    - Find witness via incident_id and document origin
    - _Requirements: 6.1, 6.3_
  
  - [x] 8.2 Dispatch event when statement is submitted
    - Create WitnessStatementSubmitted event
    - Include witness token and submission timestamp
    - Event can trigger supervisor notification (email/dashboard badge) in future
    - _Requirements: 6.1_
  
  - [x] 8.3 Persist submission timestamp to database
    - Verify timestamp is stored in witness_statement_submitted_at
    - Timestamp should be updated only once (on first submission)
    - _Requirements: 6.3_
  
  - [ ]* 8.4 Write property test for statement submission timestamp
    - **Property: Statement submission timestamp is recorded and persists**
    - **Validates: Requirements 6.1, 6.2, 6.3**
    - Submit statement via public link
    - Verify witness_statement_submitted_at is not null
    - Query database, verify timestamp persists
    - Verify dashboard shows "Submitted" badge with correct date

- [x] 9. Enhance supervisor dashboard to display statement metadata
  - [x] 9.1 Add submission date/time display to witness list
    - Show "Submitted on [date] [time]" for submitted witnesses
    - Format timestamp for readability (e.g., "15 Dec 2026, 10:30 AM")
    - Leave empty for pending witnesses
    - _Requirements: 6.2_
  
  - [x] 9.2 Add attachment metadata to witness display
    - Show statement file name if available
    - Show file size if available
    - Show upload timestamp matching witness_statement_submitted_at
    - _Requirements: 3.6_
  
  - [ ]* 9.3 Write property test for submission timestamp display
    - **Property: Submission timestamp displays correctly in dashboard**
    - **Validates: Requirements 6.2**
    - Create witness with submission_at timestamp
    - Load dashboard, verify timestamp appears
    - Verify formatting is correct

- [x] 10. Add authorization and security checks
  - [x] 10.1 Verify supervisor role on all witness routes
    - Middleware: check user->hasRole('supervisor')
    - Block non-supervisors from accessing witness pages
    - Return 403 Forbidden if unauthorized
    - _Requirements: 9.1, 9.3_
  
  - [x] 10.2 Filter witnesses by supervisor responsibility
    - Only show witnesses for incidents assigned to this supervisor
    - Check incident->assigned_supervisor_id or similar
    - Block cross-supervisor access
    - _Requirements: 9.2_
  
  - [x] 10.3 Audit log all supervisor SMS actions
    - Log supervisor user ID for each SMS send
    - Log timestamp of action initiation
    - Store action type (INITIAL, REMINDER)
    - _Requirements: 9.4_
  
  - [x] 10.4 Ensure SMS credentials not exposed
    - Don't log raw API keys
    - Don't expose SMS service details to UI
    - Only show status and timestamp to supervisor
    - _Requirements: 9.5_
  
  - [ ]* 10.5 Write property test for authorization boundaries
    - **Property: Non-supervisors cannot access witness data**
    - **Validates: Requirements 9.1, 9.3**
    - Attempt access as non-supervisor user
    - Verify 403 response
    - Verify no data is leaked

- [x] 11. Checkpoint - All tests pass and core features work
  - Ensure all non-optional tasks are complete
  - Run full test suite (units and properties)
  - Verify form submission creates witnesses and sends SMS
  - Verify supervisor dashboard displays all witnesses correctly
  - Verify reminder SMS works
  - Verify statement submission updates status
  - Ask the user if questions arise about any feature behavior

- [x] 12. Create UI integration and polish
  - [x] 12.1 Add loading states and user feedback
    - Show spinner during SMS send
    - Display toast notifications for success/failure
    - Disable buttons during pending actions
    - _Requirements: All_
  
  - [x] 12.2 Add client-side validation to witness form
    - Validate phone number format before submit
    - Show validation errors inline
    - Require at least one witness
    - _Requirements: 1.2, 1.6_
  
  - [x] 12.3 Add responsive design for mobile supervisor access
    - Mobile-friendly table layout for dashboard
    - Touch-friendly button sizes
    - Readable text on small screens
    - _Requirements: 3.1_
  
  - [ ]* 12.4 Write UI integration tests
    - Test form submission flow end-to-end
    - Test dashboard displays correctly
    - Test reminder button sends SMS
    - _Requirements: All_

- [ ] 13. Documentation and environment setup
  - [ ] 13.1 Document environment variables needed
    - SMS_ENABLED=true
    - ECONET_SMS_API_KEY=[key]
    - ECONET_SMS_BASE_URL=[url]
    - ECONET_SMS_SENDER_ID="HSC SMS"
  
  - [ ] 13.2 Create database seeder for testing
    - Seed sample incidents with witnesses
    - Seed witnesses with mixed submission states
  
  - [ ] 13.3 Document API endpoints for external integrations
    - POST /incidents/{id}/witness (worker form)
    - POST /supervisor/incidents/{id}/witness/{tokenId}/reminder (supervisor)
    - GET /supervisor/incidents/{id}/witnesses (dashboard)

- [ ] 14. Final checkpoint - All tests pass and feature complete
  - Ensure all tasks complete (optional sub-tasks may be skipped)
  - Run full integration test
  - Verify no regressions in existing functionality
  - Test SMS sending with real credentials in staging (if available)
  - Ask the user if questions arise or if any refinements are needed

## Notes

- Tasks marked with `*` are optional and can be skipped for MVP deployments
- Property-based tests should use fast-check (JavaScript) or Hypothesis (Python) equivalents
- SMS sending is async via queues to avoid blocking form submission
- All timestamps are timezone-aware and stored in UTC
- The public witness link is already implemented, this feature adds SMS dispatch
- SMS history must be preserved for audit purposes
- Multiple reminders to same witness are allowed but tracked
- Submission timestamps are write-once (never updated after first submission)

