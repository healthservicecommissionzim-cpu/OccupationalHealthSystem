# Requirements Document: Witness Collection Workflow Redesign

## Introduction

This document specifies a redesigned witness collection workflow for the HSC Injury Management System. The current process requires supervisors to manually enter witness details. The new approach enables workers to provide witness information during incident reporting, with automated SMS notifications inviting witnesses to submit statements via a public link. Supervisors can then monitor witness submission status and send reminder SMS messages to pending witnesses.

## Glossary

- **System**: The HSC Injury Management System (witness collection module)
- **Worker**: Employee who reports an incident
- **Supervisor**: Person responsible for supervising the worker and witness collection
- **Witness**: Person who observed the incident and provides a statement
- **SMS**: Short Message Service (text message via Econet SMS service)
- **Public Link**: The web URL accessible without authentication that allows witnesses to submit statements
- **Witness Token**: A secure identifier that associates a witness with an incident and provides authenticated access to the public submission page
- **Statement**: A written account of the incident provided by a witness
- **Reminder**: A subsequent SMS sent to a witness who has not yet submitted a statement
- **HR User**: Administrative user from Human Resources assigned to review escalated incidents
- **Incident Reference**: The unique identifier for an incident in the format OHS-YYYY-MM-XXXXX

## Requirements

### Requirement 1: Worker Incident Reporting with Witness Details

**User Story:** As a worker, I want to enter witness information when reporting an incident, so that I can immediately notify them about submitting statements.

#### Acceptance Criteria

1. WHEN a worker fills out the incident report form THEN the form SHALL include a section to add witness details
2. WHEN adding a witness THEN the worker SHALL enter the witness's full name and phone number
3. WHERE the worker is adding the first witness THEN the system SHALL enable adding a second witness (maximum 2 witnesses)
4. WHEN a witness is added with valid name and phone number THEN the system SHALL create a witness record for that incident
5. WHEN the incident form is submitted THEN the system SHALL create witness tokens and prepare SMS notifications for all witnesses
6. IF the form is submitted with incomplete witness details THEN the system SHALL display a validation error and prevent submission

### Requirement 2: Automatic SMS Notification to Witnesses

**User Story:** As a witness, I want to receive an SMS with a public link to submit my statement, so that I can provide my account without additional follow-up.

#### Acceptance Criteria

1. WHEN an incident is submitted with witness details THEN THE System SHALL dispatch SMS messages to all witnesses with valid phone numbers
2. WHEN an SMS is sent to a witness THEN the message SHALL contain the incident reference number, a public link to the submission page, and instructions
3. WHEN an SMS is successfully sent THEN THE System SHALL record the send timestamp in the witness record
4. IF an SMS fails to send THEN the system SHALL log the failure and allow the supervisor to retry
5. WHEN a witness receives the SMS THEN the public link SHALL provide secure access without requiring authentication

### Requirement 3: Supervisor Witness Management Dashboard

**User Story:** As a supervisor, I want to view all witnesses for an incident with their submission status, so that I can monitor progress and follow up as needed.

#### Acceptance Criteria

1. WHEN a supervisor views the witness management page for an incident THEN THE System SHALL display a list of all witnesses
2. FOR EACH witness displayed THEN the system SHALL show the witness name, phone number, and current submission status
3. WHEN a witness has submitted a statement THEN the system SHALL display a "Statement Submitted" badge with the submission date and time
4. WHEN a witness has NOT submitted a statement THEN the system SHALL display a "Pending" badge with options to send a reminder SMS
5. WHEN the supervisor views the witness list THEN the system SHALL show the count of witnesses who have submitted versus the total number of witnesses
6. WHEN a statement has been submitted THEN the supervisor SHALL be able to view and download the statement document

### Requirement 4: Reminder SMS Functionality

**User Story:** As a supervisor, I want to send reminder SMS messages to witnesses who have not yet submitted statements, so that I can encourage timely submission.

#### Acceptance Criteria

1. WHEN a witness has not submitted a statement THEN the supervisor SHALL be able to click "Send Reminder SMS" for that witness
2. WHEN the supervisor sends a reminder SMS THEN THE System SHALL send an SMS to the witness with the same public link and updated messaging
3. WHEN a reminder SMS is sent THEN THE System SHALL record the timestamp of the reminder in the witness record
4. WHERE the supervisor attempts to send multiple reminders THEN THE System SHALL allow sending reminders but track the last reminder timestamp
5. IF an SMS fails to send THEN the system SHALL notify the supervisor of the failure and allow retry

### Requirement 5: SMS Send History and Tracking

**User Story:** As a supervisor, I want to see the history of SMS communications with witnesses, so that I can understand what follow-up has been done.

#### Acceptance Criteria

1. WHEN viewing a witness record THEN the system SHALL display all SMS send events with timestamps
2. FOR EACH SMS event THEN the system SHALL show whether it was initial notification, a reminder, and whether it was successful
3. WHEN an SMS was successful THEN the system SHALL display confirmation status and timestamp
4. WHEN an SMS failed THEN the system SHALL display the failure status and allow the supervisor to retry
5. WHEN a reminder is sent THEN the system SHALL update the `witness_last_reminder_sent_at` field

### Requirement 6: Statement Submission Tracking

**User Story:** As a supervisor, I want to know exactly when a witness submitted their statement, so that I can track the timeline of witness collection.

#### Acceptance Criteria

1. WHEN a witness submits a statement via the public link THEN THE System SHALL record the submission timestamp
2. WHEN the supervisor views the witness THEN the system SHALL display the exact date and time the statement was submitted
3. WHEN a statement is submitted THEN the system SHALL update the `witness_statement_submitted_at` field
4. WHERE a witness has not submitted THEN the field SHALL remain null and the supervisor SHALL see the "Pending" status
5. WHEN viewing supervisor witness page THEN the system SHALL display statements with their file names and allow download

### Requirement 7: Database Schema Updates

**User Story:** As a system architect, I want the database to track all witness-related information and timelines, so that the system maintains data integrity and historical records.

#### Acceptance Criteria

1. WHEN a witness record is created THEN the database record SHALL include `witness_statement_submitted_at` field (nullable timestamp)
2. WHEN a witness record is created THEN the database record SHALL include `witness_last_reminder_sent_at` field (nullable timestamp)
3. WHEN a witness submits a statement THEN the `witness_statement_submitted_at` field SHALL be updated with the current timestamp
4. WHEN a reminder SMS is sent THEN the `witness_last_reminder_sent_at` field SHALL be updated with the current timestamp
5. THE System SHALL maintain historical records of all SMS send attempts in the `incident_notifications` table
6. WHERE witness records already exist in the system THEN migration SHALL add new fields with null as default values (backwards compatible)

### Requirement 8: SMS Message Content and Formatting

**User Story:** As a system administrator, I want the SMS messages to be professional, clear, and contain all necessary information, so that witnesses understand exactly what they need to do.

#### Acceptance Criteria

1. THE initial SMS message SHALL include: incident reference number, a brief description of the request, the public link URL, and instructions
2. THE reminder SMS message SHALL include: incident reference number, a note that this is a reminder, the same public link, and deadline information if applicable
3. THE SMS content SHALL NOT exceed 160 characters for optimal delivery (or split into multiple messages if necessary)
4. WHERE a phone number uses a specific country code THEN the system SHALL format the SMS appropriately for that region
5. THE system SHALL use the SMS service configured in environment variables (Econet SMS service)

### Requirement 9: Security and Access Control

**User Story:** As a system administrator, I want to ensure that only authorized supervisors can access witness information and send SMS messages, so that the system maintains data integrity and privacy.

#### Acceptance Criteria

1. WHERE a user accesses the witness management page THEN the system SHALL verify the user has the supervisor role
2. WHEN a supervisor attempts to access a witness list THEN the system SHALL only show witnesses for incidents they are responsible for
3. WHERE a supervisor attempts to send an SMS THEN the system SHALL verify they have the required permissions before allowing the action
4. WHEN an SMS is sent by a supervisor THEN the system SHALL log which supervisor initiated the send for audit purposes
5. WHEN witness data is displayed THEN the system SHALL NOT expose raw SMS credentials or API keys

