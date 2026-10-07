# Design Document: Conditions of Service Department Dashboard

## Overview

The Conditions of Service Department Dashboard is a governance platform for CoS staff to monitor case flow, enforce SLAs, ensure compliance, and maintain audit trails across the incident management system. The system provides comprehensive visibility into all case stages (Submitted → Supervisor Review → DMO Review → PMD Review → HSC Resolution) while preventing clinical decision-making through role-based permission enforcement at both controller and view layers.

The architecture leverages existing Laravel infrastructure (authentication, authorization, notifications, storage) and integrates with the incident management system without modifying clinical workflows. The design prioritizes security through layered permission checks, comprehensive audit logging, and read-only data access for governance functions.

## Architecture

### High-Level Flow

```
CoS Department User
        ↓
    [Middleware: Verify CoS Role]
        ↓
    [Controller: Check Permissions]
        ↓
    [View: Conditionally Render Actions]
        ↓
    [Service Layer: Query Data, Log Actions]
        ↓
    [Database: Read Case Data, Write Audit Logs]
```

### Key Components

1. **Middleware (Permission Gate)**
   - Verifies user has CoS role before accessing dashboard routes
   - Redirects unauthorized users to access-denied page
   - Enforces early termination of unpermitted requests

2. **Controller Layer (Action-Level Enforcement)**
   - Dashboard endpoints (executive, case detail, reports, etc.)
   - Each endpoint verifies specific permissions (VIEW_TIMELINE, SEND_REMINDER, etc.)
   - Returns 403 Forbidden if permission missing
   - Logs all permission checks to audit trail

3. **View Layer (UI-Level Enforcement)**
   - Conditionally renders buttons/forms based on user permissions
   - Prevents rendering of denied action UI elements
   - Serves as user-experience enforcement (not security)

4. **Service Layer (Business Logic)**
   - CoS Dashboard Service: Aggregates metrics, filters data
   - Case Management Service: Queries case data, calculates TAT
   - Notification Service: Dispatches alerts and notifications
   - Audit Service: Logs all governance actions
   - Compliance Service: Evaluates compliance checklists
   - Report Service: Generates analytics and exports

5. **Models and Relationships**
   - Case: Central incident record with stage, current_holder, metadata
   - CaseTimeline: Immutable event log of case transitions
   - AuditLog: Records all CoS department actions for compliance
   - MonitoringNote: Governance observations added by CoS staff
   - ComplianceCheck: Results of compliance checklist evaluations
   - Announcement: Policy communications to staff

### Permission Enforcement Strategy

**Role-Based Access Control (RBAC) via Spatie Laravel Permission**

Permissions granted to CoS_Department role:
- VIEW_ALL_CASES
- VIEW_TIMELINE
- ADD_MONITORING_NOTE
- SEND_REMINDER
- ESCALATE_CASE
- RUN_COMPLIANCE_CHECKLIST
- GENERATE_REPORTS
- EXPORT_DATA
- VIEW_AUDIT_LOG
- BROADCAST_ANNOUNCEMENT

Permissions explicitly denied (not in CoS role):
- APPROVE_TREATMENT
- REJECT_TREATMENT
- CLOSE_CASE
- EDIT_CLINICAL_DATA
- ASSIGN_DOCTOR

**Enforcement Points**

1. Route Middleware: `auth`, `can:view_all_cases`
2. Controller Methods: `$this->authorize('view_all_cases')` or `Gate::denies()`
3. View Conditionals: `@can('send_reminder')`
4. API Policy: RestrictedCoSDashboardPolicy

## Components and Interfaces

### Dashboard Components

**ExecutiveDashboardController**
- Aggregates open case counts by stage
- Calculates average TAT for each stage
- Identifies SLA breaches
- Returns metrics with active filters applied
- Caches results for 60 seconds

**CaseFlowTrackerController**
- Queries single case with full timeline
- Returns current stage, holder, and duration
- Includes all transitions with actor/timestamp
- Fetches monitoring notes
- Calculates overdue status

**ComplianceDashboardController**
- Retrieves compliance checklist definition
- Queries case data to auto-populate checklist items
- Saves checklist results with status
- Flags non-compliant cases

**ReportGenerationController**
- Accepts report parameters (type, date range, filters)
- Queues background job for report generation
- Returns report metadata and link
- Tracks report generation status

**AuditLogController**
- Queries all actions for a specific case
- Filters by date range, action type, actor
- Returns immutable audit trail
- Supports export to CSV

**NotificationController**
- Retrieves unread notifications for user
- Marks notifications as read
- Handles notification creation (from events)

**AnnouncementController**
- Creates new announcement with composition form
- Stores announcement record
- Queues notification dispatch to audience
- Retrieves announcement archive
- Supports scheduling future announcements

### Service Layer

**CoSDashboardService**
```
Methods:
- getMetricsByStage(filters): Returns open case counts
- calculateAverageTAT(stage, days): Returns TAT for stage
- identifyBreaches(slathresholds): Returns cases exceeding SLA
- applyFilters(data, caseType, location): Filters metrics
- getPerformanceTrends(days): Returns TAT trends
```

**CaseManagementService**
```
Methods:
- getCaseWithTimeline(caseId): Full case with transitions
- getCurrentHolder(caseId): Current case holder info
- calculateDurationAtStage(caseId): Duration held calculation
- escalateCaseToNextStage(caseId, reason): Advances case routing
- sendNudgeToHolder(caseId): Dispatches reminder notification
```

**ComplianceService**
```
Methods:
- getComplianceChecklist(): Returns checklist definition
- evaluateCase(caseId): Auto-populates checklist from case data
- saveChecklistResult(caseId, items, status): Stores result
- getComplianceReport(): Returns non-compliant cases
```

**ReportService**
```
Methods:
- generateReport(params): Queues report generation job
- aggregateCaseData(dateRange, filters): Data aggregation
- calculateImpactMetrics(cases): Sick leave, light duty, compensation
- exportToFormat(reportId, format): Generates PDF/Excel
```

**AuditService**
```
Methods:
- logAction(userId, actionType, caseId, data): Creates audit log entry
- getAuditLog(caseId, filters): Retrieves action history
- generateAuditReport(dateRange): Compliance audit report
```

**NotificationService**
```
Methods:
- dispatchCaseCreatedAlert(caseId): Alert to CoS staff
- dispatchCaseCompletedAlert(caseId): Completion notification
- dispatchNudgeNotification(userId, caseId): Reminder to holder
- logNotification(userId, type, status): Tracks notification delivery
```

### Data Models

**Case (Incident)**
```
Fields:
- id: UUID
- incident_reference: string (unique)
- current_stage: enum (Submitted, Supervisor_Review, DMO, PMD, HSC_Resolution)
- current_holder_id: User ID
- case_type: enum (injury, illness, hazard_exposure, mental_health)
- facility_id: FK
- status: enum (active, completed, closed)
- created_at, updated_at: timestamps
- routed_to_hr_user_id: Assigned HR user (from existing system)
```

**CaseTimeline**
```
Fields:
- id: UUID
- case_id: FK
- from_stage: enum
- to_stage: enum
- transitioned_by_id: User ID
- transitioned_at: timestamp
- reason: text (optional)
```

**MonitoringNote**
```
Fields:
- id: UUID
- case_id: FK
- author_id: FK (CoS staff)
- content: text
- created_at: timestamp
- updated_at: null (immutable)
```

**AuditLog**
```
Fields:
- id: UUID
- case_id: FK
- action_type: enum (created, escalated, nudge_sent, monitoring_note_added, checklist_run, etc.)
- actor_id: User ID
- old_values: json (for updates)
- new_values: json (for updates)
- action_data: json (metadata)
- created_at: timestamp
```

**ComplianceCheck**
```
Fields:
- id: UUID
- case_id: FK
- evaluated_by_id: User ID
- checklist_items: json (array of {item, status, evidence})
- overall_status: enum (COMPLIANT, NON_COMPLIANT)
- created_at: timestamp
```

**Announcement**
```
Fields:
- id: UUID
- title: string
- content: text
- author_id: FK (CoS staff)
- target_audience: enum (supervisors, pmds, doctors, all_staff)
- scheduled_for: timestamp (nullable, immediate if null)
- published_at: timestamp (nullable, set on dispatch)
- expires_at: timestamp (for auto-dismiss)
- created_at: timestamp
```

**IncidentNotification**
```
Fields:
- id: UUID
- case_id: FK
- recipient_user_id: FK
- notification_type: enum (CASE_CREATED, CASE_COMPLETED, SLA_BREACH, NUDGE_SENT)
- channel: enum (email, database, sms)
- status: enum (PENDING, SENT, FAILED)
- sent_at: timestamp
- created_at: timestamp
```

## Routes and Endpoints

### Dashboard Routes (with permission middleware)

```
GET /cos-dashboard
  → Show executive dashboard with metrics, charts, filters
  → Requires: VIEW_ALL_CASES

GET /cos-dashboard/case/{id}
  → Show case detail with timeline, monitoring notes, documents
  → Requires: VIEW_TIMELINE

POST /cos-dashboard/case/{id}/monitoring-note
  → Add monitoring note to case
  → Requires: ADD_MONITORING_NOTE
  → Logs to audit trail

POST /cos-dashboard/case/{id}/nudge
  → Send reminder to current holder
  → Requires: SEND_REMINDER
  → Dispatches email/SMS

POST /cos-dashboard/case/{id}/escalate
  → Escalate case to next stage
  → Requires: ESCALATE_CASE
  → Logs to audit trail, updates current holder

GET /cos-dashboard/compliance
  → Show compliance checklist form
  → Requires: RUN_COMPLIANCE_CHECKLIST

POST /cos-dashboard/compliance/{id}
  → Save compliance checklist result
  → Requires: RUN_COMPLIANCE_CHECKLIST
  → Flags non-compliant cases

GET /cos-dashboard/reports
  → Show report generation form
  → Requires: GENERATE_REPORTS

POST /cos-dashboard/reports/generate
  → Queue report generation
  → Requires: GENERATE_REPORTS
  → Background job queued

GET /cos-dashboard/reports/{id}/download
  → Download generated report (PDF/Excel)
  → Requires: EXPORT_DATA

GET /cos-dashboard/audit-log/{case_id}
  → Retrieve audit log for case
  → Requires: VIEW_AUDIT_LOG

GET /cos-dashboard/announcements
  → Show announcements and archive
  → Requires: BROADCAST_ANNOUNCEMENT

POST /cos-dashboard/announcements
  → Create new announcement
  → Requires: BROADCAST_ANNOUNCEMENT
  → Queues notification dispatch

GET /cos-dashboard/notifications
  → Retrieve unread notifications
  → Requires: VIEW_ALL_CASES

PATCH /cos-dashboard/notifications/{id}/read
  → Mark notification as read
  → Requires: VIEW_ALL_CASES
```

## Data Models

### Database Schema Additions/Modifications

```sql
-- New tables for CoS Dashboard

CREATE TABLE monitoring_notes (
  id CHAR(36) PRIMARY KEY,
  case_id CHAR(36) NOT NULL,
  author_id BIGINT UNSIGNED NOT NULL,
  content LONGTEXT NOT NULL,
  created_at TIMESTAMP,
  FOREIGN KEY (case_id) REFERENCES incidents(id) ON DELETE CASCADE,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE compliance_checks (
  id CHAR(36) PRIMARY KEY,
  case_id CHAR(36) NOT NULL,
  evaluated_by_id BIGINT UNSIGNED NOT NULL,
  checklist_items JSON NOT NULL,
  overall_status ENUM('COMPLIANT', 'NON_COMPLIANT') NOT NULL,
  created_at TIMESTAMP,
  FOREIGN KEY (case_id) REFERENCES incidents(id) ON DELETE CASCADE,
  FOREIGN KEY (evaluated_by_id) REFERENCES users(id)
);

CREATE TABLE announcements (
  id CHAR(36) PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT NOT NULL,
  author_id BIGINT UNSIGNED NOT NULL,
  target_audience ENUM('supervisors', 'pmds', 'doctors', 'all_staff') NOT NULL,
  scheduled_for TIMESTAMP NULL,
  published_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Modify existing incident_notifications table
ALTER TABLE incident_notifications
ADD COLUMN recipient_user_id BIGINT UNSIGNED NULL,
ADD COLUMN notification_type ENUM('CASE_CREATED', 'CASE_COMPLETED', 'SLA_BREACH', 'DOCUMENT_UPLOAD', 'NUDGE_SENT') NOT NULL DEFAULT 'CASE_CREATED',
ADD COLUMN channel ENUM('email', 'database', 'sms') NOT NULL DEFAULT 'email',
ADD UNIQUE KEY(case_id, recipient_user_id, notification_type, channel);

-- Audit log integration (add case_id if not exists)
ALTER TABLE audit_logs
ADD COLUMN case_id CHAR(36) NULL,
ADD FOREIGN KEY (case_id) REFERENCES incidents(id) ON DELETE SET NULL,
ADD INDEX (case_id, action_type, created_at);
```

## Error Handling

### Permission Errors
- **403 Forbidden**: User lacks required permission
- **Response**: JSON error with permission name and link to documentation
- **Logging**: Unauthorized access attempt logged to audit trail with user ID and attempted action

### Data Access Errors
- **404 Not Found**: Case or resource not found
- **Response**: User-friendly message "Case not found or you lack access"
- **Logging**: Attempted access to non-existent or unauthorized resource logged

### Notification Failures
- **Email Failure**: Logged in Laravel logs; queued for retry
- **SMS Failure**: Logged; email still sent; case processing continues
- **UI**: User notified of successful nudge dispatch; backend handles delivery

### Report Generation Failures
- **Queue Failure**: Job retried automatically; user notified via email if ultimately fails
- **Data Aggregation**: Graceful degradation; partial data shown if some queries timeout
- **Export Timeout**: Large exports fail gracefully with message to reduce date range

### Validation Errors
- **Missing Filters**: Defaults to show all locations/case types
- **Invalid Date Range**: Returns validation error with guidance
- **Malformed Monitoring Note**: Required field validation; error message shown

## Testing Strategy

### Unit Testing Approach
- Test individual service methods (CoSDashboardService, ComplianceService, ReportService)
- Test permission checks in policies
- Test data model relationships and scopes
- Test compliance checklist evaluation logic
- Test filter logic and metric calculations

### Property-Based Testing Approach
- Verify that metrics calculated from any valid dataset are always consistent with raw data
- Verify that case filtering produces a subset of original data
- Verify that TAT calculations are always non-negative and less than case total duration
- Verify that audit log entries are immutable once created (create-only)
- Verify that monitoring note additions don't affect case state or clinical data
- Verify that escalating cases through multiple stages produces consistent timeline
- Verify that compliance checklist results are deterministic given same case state
- Verify that multiple filters combined produce same results as filtering sequentially
- Verify that notification dispatch doesn't modify underlying case data
- Verify that exported data is consistent with in-app metrics (round-trip: export then reimport)

### Integration Testing Approach
- Test complete workflows: View dashboard → Filter → Select case → Add note → Escalate case
- Test notification dispatch (email, SMS) with real or test credentials
- Test report generation and file output
- Test permission enforcement across multiple endpoints
- Test concurrent case access and timeline updates

### Test Coverage Goals
- Permission enforcement: 100% (all permission checks tested)
- Service layer logic: 85%+ (metrics, compliance, reporting)
- Routes/Controllers: 80%+ (main workflows and error cases)
- View rendering: Manual testing (permission-based conditionals)

## Security Considerations

### Permission Enforcement
- Two-layer enforcement: Controller (HTTP) + View (UI)
- All state-changing actions require explicit permission check
- Permission checks cannot be bypassed via direct API calls

### Data Access Control
- CoS staff can only view case data; never modify clinical content
- Read-only access enforced in queries (no update/delete for case clinical fields)
- Audit log provides compliance proof of all access and actions

### Notification Security
- Email notifications include case reference but not sensitive clinical details
- SMS messages use abbreviated format to protect privacy
- Notification logs track successful delivery for audit

### Audit Trail Integrity
- Audit logs are immutable; can only be read
- All actions logged with user ID, timestamp, and IP address (if available)
- Compliance reports generated from audit logs for governance proof

## Performance Optimization

### Caching Strategy
- Dashboard metrics cached for 60 seconds (invalidated on case update)
- Case timeline queried fresh (not cached) for real-time accuracy
- Compliance checklist definition cached (rarely changes)
- Report generation uses database aggregations (not in-memory)

### Query Optimization
- Index on (case_id, action_type, created_at) for audit log queries
- Index on (current_stage, current_holder_id) for dashboard filtering
- Index on (facility_id, case_type) for institutional volume analysis
- Eager loading of relationships (case → holder, transitions → actor)

### Async Operations
- Report generation queued as background job (ShouldQueue)
- Notification dispatch queued (not blocking HTTP requests)
- Announcement distribution queued per recipient
- Large exports (>10k records) processed in background

## Correctness Properties

A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.

### Property 1: Metric Consistency Invariant
*For any* valid dashboard filter (case type, location), the sum of open cases at all stages should equal the total count returned by the metric card "Total Open Cases". This ensures metrics are internally consistent regardless of filter selection.

**Validates: Requirements 2.1, 2.4**

### Property 2: TAT Non-Negative Invariant
*For any* completed case stage, the calculated TAT should be non-negative and less than the total case duration. TAT cannot be negative and cannot exceed the total time from case creation to current time.

**Validates: Requirements 2.2**

### Property 3: Case Filter Subset Property
*For any* active filter set (location AND case type), the count of filtered cases should be less than or equal to the total unfiltered case count. Filtering produces a subset of the original data.

**Validates: Requirements 2.4, 12.1, 12.2**

### Property 4: Timeline Immutability Round Trip
*For any* case, querying the timeline, then serializing it to JSON, then deserializing back should produce a timeline equivalent to the original query. Timeline data structure is stable through serialization.

**Validates: Requirements 3.1, 3.3**

### Property 5: Monitoring Note Non-Interference
*For any* case with clinical data, adding a monitoring note should not modify any clinical fields (diagnosis, treatment, doctor assignments). Governance notes are completely isolated from clinical state.

**Validates: Requirement 4.4**

### Property 6: SLA Breach Identification Accuracy
*For any* case and SLA threshold, if a case's duration at current stage exceeds the threshold, the system should identify it as a breach. Conversely, if duration is within threshold, it should not be flagged as breach.

**Validates: Requirement 5.1**

### Property 7: Case Escalation Consistency
*For any* case escalated from stage A to stage B, the case's previous stage should be stage A and current stage should be stage B after escalation. Escalation produces deterministic stage transitions.

**Validates: Requirement 5.5**

### Property 8: Compliance Checklist Determinism
*For any* case state, running the compliance checklist multiple times should produce the same result (same checklist items, same evidence, same overall status). Compliance evaluation is deterministic.

**Validates: Requirement 6.2, 6.3**

### Property 9: Audit Log Append-Only Invariant
*For any* audit log entry, once created with a given timestamp and action, the entry should never be modified or deleted. Audit log can only grow, never be altered retroactively.

**Validates: Requirement 8.5, 8.6**

### Property 10: Filter Commutativity
*For any* case data and filter combinations (location AND case type), filtering by location then case type should produce the same result as filtering by case type then location. Filter order should not affect outcome.

**Validates: Requirement 12.1, 12.2**

### Property 11: Permission Enforcement Completeness
*For any* denied permission (APPROVE_TREATMENT, REJECT_TREATMENT, CLOSE_CASE, EDIT_CLINICAL_DATA, ASSIGN_DOCTOR), a CoS_Department_User attempting that action should receive 403 Forbidden. All denied permissions must be enforced equally.

**Validates: Requirement 1.3**

### Property 12: Notification Idempotency
*For any* notification sent to a user for a given case, sending the same notification again (same recipient, case, notification type) should not create duplicate notifications or double-send emails. Notification dispatch is idempotent.

**Validates: Requirement 9.3**

### Property 13: Export Data Round Trip
*For any* generated report exported to CSV/Excel and reimported, the key metrics (total cases, average TAT, breach count) should match the originally displayed values. Export/import cycle preserves data integrity.

**Validates: Requirement 7.5**

### Property 14: Role-Based Visibility Isolation
*For any* two CoS_Department_Users with different permission sets, neither should be able to see actions or data for which they lack explicit permission. Permission sets are isolated; no permission leakage.

**Validates: Requirement 1.1, 1.2**

