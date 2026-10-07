# Requirements Document: Conditions of Service Department Dashboard

## Introduction

The Conditions of Service (CoS) Department Dashboard is a governance and oversight platform for CoS staff who function as referees and auditors. Unlike case resolvers, CoS staff monitor case flow, enforce SLAs, ensure compliance, and maintain audit trails. The dashboard provides visibility into all active cases across all stages while preventing clinical decision-making or case closure actions.

## Glossary

- **CoS_Department_User**: Staff member with governance/audit permissions
- **Case**: An incident record progressing through the workflow
- **Stage**: Progression point: Submitted, Supervisor Review, DMO Review, PMD Review, HSC Resolution
- **Current_Holder**: Person/department responsible for the case at current stage
- **SLA**: Service Level Agreement; contractual response time requirement
- **TAT**: Turnaround Time; actual time to complete a stage
- **Bottleneck**: Case stuck at a stage beyond normal TAT threshold
- **Monitoring_Note**: Governance-level observation by CoS staff
- **Compliance_Rule**: Procedural requirement (IOD form, 48-hour reporting window)
- **Audit_Log**: Immutable chronological record of all actions

## Requirements

### Requirement 1: Access Control and Permission Enforcement

**User Story:** As a CoS staff member, I want the dashboard to enforce my permissions at both controller and view layers, so that I cannot modify clinical decisions.

#### Acceptance Criteria

1. WHEN a CoS_Department_User accesses a dashboard page, THEN the System SHALL verify VIEW_ALL_CASES permission at controller level before rendering content
2. WHEN viewing action buttons, THEN the System SHALL conditionally render only allowed actions (VIEW_ALL_CASES, VIEW_TIMELINE, ADD_MONITORING_NOTE, SEND_REMINDER, ESCALATE_CASE, RUN_COMPLIANCE_CHECKLIST, GENERATE_REPORTS, EXPORT_DATA, VIEW_AUDIT_LOG, BROADCAST_ANNOUNCEMENT)
3. WHEN accessing denied actions, THEN the System SHALL deny access and log the unauthorized attempt in Audit_Log with user ID and timestamp
4. IF permission check fails, THEN the System SHALL return HTTP 403 Forbidden with explanatory message

---

### Requirement 2: Executive Dashboard Overview

**User Story:** As a CoS manager, I want a read-only executive overview showing case distribution and SLA status, so that I can identify problem areas.

#### Acceptance Criteria

1. WHEN loading the Executive Dashboard, THEN the System SHALL display metric cards showing open case count at each stage (Submitted, Supervisor Review, PMD Review, HSC Resolution)
2. WHEN the dashboard renders, THEN the System SHALL display average TAT for each stage based on completed cases from past 90 days
3. WHEN viewing the dashboard, THEN the System SHALL highlight cases in red if any case exceeds its SLA threshold
4. WHEN applying filters, THEN the System SHALL update metrics based on case type (injury/illness/hazard_exposure/mental_health) and location (province/institution)
5. WHEN the dashboard loads, THEN the System SHALL display a performance chart showing TAT trends over past 30 days

---

### Requirement 3: Case Flow Tracker and Timeline Visualization

**User Story:** As a CoS auditor, I want to see a visual timeline for each case showing stage progression and current holder, so that I can identify stuck cases.

#### Acceptance Criteria

1. WHEN selecting a case, THEN the System SHALL display a visual timeline showing all stages with current stage highlighted
2. WHEN viewing the timeline, THEN the System SHALL show Current_Holder identity and duration held at current stage
3. WHEN examining the timeline, THEN the System SHALL display previous transitions chronologically with user and timestamp
4. WHEN viewing the timeline, IF a case exceeds standard TAT, THEN the System SHALL display a visual warning (red border or warning icon)

---

### Requirement 4: Governance Notes and Commentary

**User Story:** As a CoS staff member, I want to add monitoring notes to cases without modifying clinical data, so that I can document governance observations.

#### Acceptance Criteria

1. WHEN viewing a case, THEN the System SHALL display an "Add Monitoring Note" text field for governance-level commentary
2. WHEN saving a note, THEN the System SHALL create an Audit_Log entry recording content, author, timestamp, and case reference
3. WHEN the note is saved, THEN the System SHALL display all previous monitoring notes chronologically (newest first)
4. WHILE adding a note, THE System SHALL prevent modification of clinical fields

---

### Requirement 5: SLA Breach Alerts and Escalation

**User Story:** As a CoS staff member, I want to see SLA breach alerts and escalate overdue cases, so that I can maintain accountability and timely resolution.

#### Acceptance Criteria

1. WHEN the dashboard loads, THEN the System SHALL identify all cases exceeding SLA threshold and display breach alert with case reference and days overdue
2. WHEN a breach alert is displayed, THEN the System SHALL include a "Send Nudge/System Reminder" button that sends automated message to Current_Holder
3. WHEN the nudge is sent, THEN the System SHALL dispatch email + SMS to Current_Holder with case reference and link
4. WHEN the escalate button is clicked, THEN the System SHALL advance case routing to next stage and update current holder
5. WHEN escalation occurs, THEN the System SHALL create Audit_Log entry recording the action with timestamp and reason

---

### Requirement 6: Compliance and Policy Alignment Checklist

**User Story:** As a compliance officer, I want to run checklists to verify procedural requirements are met, so that I can audit non-compliant cases.

#### Acceptance Criteria

1. WHEN clicking "Run Compliance Checklist", THEN the System SHALL display a checklist form with items: IOD form completed, 48-hour reporting window met, incident date documented, supervisor notified, medical provider assigned
2. WHEN the checklist displays, THEN the System SHALL auto-populate items by querying case record
3. WHEN an item is checked, THEN the System SHALL display corresponding evidence (e.g., "IOD form completed on 2026-08-15")
4. WHEN checklist is complete, THEN the System SHALL save result with status (COMPLIANT or NON_COMPLIANT) and timestamp
5. IF any item fails, THEN the System SHALL flag the case as NON_COMPLIANT and display in "Flagged for Audit" section

---

### Requirement 7: Reports, Analytics, and Data Export

**User Story:** As a CoS manager, I want to generate and export reports with case trends and compensation impact, so that I can fulfill governance obligations.

#### Acceptance Criteria

1. WHEN clicking "Generate Report", THEN the System SHALL display form to select type (monthly/quarterly/annual) and date range
2. WHEN submitting report parameters, THEN the System SHALL aggregate data: total cases, average TAT, SLA breach count, non-compliant count, institutional volume, case type distribution
3. WHEN the report generates, THEN the System SHALL calculate impact metrics: total sick leave days, light duty allocations, compensation payments, and trends
4. WHEN the report displays, THEN the System SHALL include trend charts showing volume, seasonality, and TAT trends
5. WHEN clicking "Export", THEN the System SHALL generate PDF or Excel file with complete report
6. WHEN data is exported, THEN the System SHALL anonymize or redact personally identifiable information

---

### Requirement 8: Audit Trail and Document Repository

**User Story:** As an auditor, I want complete read-only access to case documents and audit logs, so that I can verify compliance without altering records.

#### Acceptance Criteria

1. WHEN viewing a case, THEN the System SHALL display "Documents" tab with all uploaded attachments in chronological order
2. WHEN documents are displayed, THEN the System SHALL show filename, upload timestamp, uploader name, file type, and file size
3. WHEN clicking a document, THEN the System SHALL display or download without allowing modifications
4. WHILE viewing documents, THE System SHALL prevent delete operations and only allow view/download
5. WHEN clicking "View Audit Log", THEN the System SHALL display chronological list of all case actions with actor name, action type, timestamp, and data changes
6. WHEN audit log displays, THEN the System SHALL mark each entry with user ID and timestamp for accountability
7. WHEN filtering the audit log, THEN the System SHALL allow filtering by date range, action type, and actor

---

### Requirement 9: Notifications and Alerts

**User Story:** As a CoS staff member, I want automated alerts for case creation/completion, so that I can stay informed of case flow.

#### Acceptance Criteria

1. WHEN a new incident is created and escalated to HR, THEN the System SHALL dispatch alert to all CoS_Department_Users with incident reference, type, and location
2. WHEN a case reaches HSC Resolution, THEN the System SHALL dispatch completion alert with case summary and status
3. WHEN an alert triggers, THEN the System SHALL send notifications via email and in-app notification
4. WHEN logging into dashboard, THEN the System SHALL display Notifications hub with recent alerts and unread count
5. WHEN clicking a notification, THEN the System SHALL navigate to related case detail
6. WHILE viewing Notifications, THEN the System SHALL mark notifications as read

---

### Requirement 10: Broadcast Announcements and Policy Communication

**User Story:** As a CoS department head, I want to broadcast policy announcements to supervisors and PMDs, so that I can ensure consistent understanding of procedures.

#### Acceptance Criteria

1. WHEN clicking "New Announcement", THEN the System SHALL display form to compose message with title, body text, target audience (supervisors/PMDs/doctors/all), and scheduling (immediate or future date)
2. WHEN the announcement is submitted, THEN the System SHALL store the record and mark for distribution to audience
3. WHEN distributed, THEN the System SHALL dispatch email notifications with announcement title, content, and resources
4. WHEN an announcement is active, THEN the System SHALL display it as banner on dashboard (dismissible or auto-dismiss after 7 days)
5. WHEN viewing Announcements archive, THEN the System SHALL display all past announcements in reverse chronological order
6. WHEN an announcement is sent, THEN the System SHALL log action in Audit_Log with content, audience, timestamp, and author

---

### Requirement 11: Real-Time Data Consistency and Performance

**User Story:** As a CoS staff member, I want current data and fast dashboard response, so that I can make timely decisions.

#### Acceptance Criteria

1. WHEN the dashboard loads, THEN the System SHALL load visible data within 2 seconds under normal conditions
2. WHEN a case transitions, THEN the System SHALL reflect the change in view within 60 seconds via polling or WebSocket
3. WHEN navigating between pages, THEN the System SHALL cache non-critical data with invalidation on changes
4. WHEN the dashboard first loads, THEN the System SHALL fetch data from database (not rely solely on cache)
5. WHEN generating large exports or reports, THEN the System SHALL queue the operation asynchronously and notify via email

---

### Requirement 12: Multi-Location and Filter Support

**User Story:** As a CoS user managing multiple facilities, I want to filter cases by location and type, so that I can focus on specific areas.

#### Acceptance Criteria

1. WHEN applying location filter, THEN the System SHALL update all dashboard data to show only matching cases
2. WHEN applying case type filter, THEN the System SHALL update data to show only selected types (injury/illness/hazard_exposure/mental_health)
3. WHEN filters are applied, THEN the System SHALL display active selections prominently with "Clear Filters" button
4. WHEN navigating to case details, THEN the System SHALL retain filters so return maintains view context
5. WHILE filters are active, THEN the System SHALL update all metrics and charts for filtered data only
