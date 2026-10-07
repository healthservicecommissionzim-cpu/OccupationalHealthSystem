# Implementation Plan: Conditions of Service Department Dashboard

## Overview

Implementation follows a layered approach: database foundation → core services → API controllers → view layer → testing. Each step validates core functionality early and integrates incrementally. Tasks are organized to build the complete dashboard system while maintaining separation of concerns and enabling comprehensive testing.

## Tasks

- [x] 1. Database Setup and Migrations
  - [x] 1.1 Create migration for monitoring_notes table
    - Create table with case_id, author_id, content, timestamps
    - Add foreign keys and indices for queries
    - _Requirements: 4.1, 4.2_

  - [x] 1.2 Create migration for compliance_checks table
    - Create table with case_id, evaluated_by_id, checklist_items (JSON), overall_status
    - Add audit tracking fields
    - _Requirements: 6.1, 6.2_

  - [x] 1.3 Create migration for announcements table
    - Create table with title, content, author_id, target_audience, scheduling fields
    - Support scheduled/immediate dispatch
    - _Requirements: 10.1, 10.2_

  - [x] 1.4 Modify incident_notifications table migration
    - Add recipient_user_id, notification_type, channel columns
    - Add unique constraints for duplicate prevention
    - _Requirements: 9.1, 9.2_

  - [x] 1.5 Modify audit_logs table migration
    - Add case_id foreign key and indices
    - Support case-specific audit filtering
    - _Requirements: 8.5, 8.6_

- [x] 2. Create Eloquent Models and Relationships
  - [x] 2.1 Create MonitoringNote model with relationships
    - Define belongsTo relationships (Case, User)
    - Add scope for case timeline queries
    - _Requirements: 4.1, 4.2_

  - [x] 2.2 Create ComplianceCheck model
    - Define relationships (Case, User)
    - Add scope for non-compliant case retrieval
    - _Requirements: 6.1, 6.4_

  - [x] 2.3 Create Announcement model
    - Define relationships (User, scheduled status scopes)
    - Add scope for active announcements per audience
    - _Requirements: 10.1, 10.5_

  - [x] 2.4 Extend Incident model relationships
    - Add hasMany relationships to MonitoringNote, ComplianceCheck
    - Add scope for filtering by stage/holder
    - _Requirements: 3.1, 3.2_

  - [x] 2.5 Extend AuditLog model with case relationships
    - Add belongsTo Case relationship
    - Add scopes for filtering by action type and date range
    - _Requirements: 8.5, 8.6_

- [x] 3. Implement Permission and Authorization System
  - [x] 3.1 Register CoS Dashboard permissions in Spatie
    - Create permissions: VIEW_ALL_CASES, VIEW_TIMELINE, ADD_MONITORING_NOTE, SEND_REMINDER, ESCALATE_CASE, RUN_COMPLIANCE_CHECKLIST, GENERATE_REPORTS, EXPORT_DATA, VIEW_AUDIT_LOG, BROADCAST_ANNOUNCEMENT
    - Assign permissions to CoS_Department role
    - _Requirements: 1.1, 1.2_

  - [x] 3.2 Create RestrictedCoSDashboardPolicy
    - Implement policy methods matching each CoS permission
    - Enforce denial of APPROVE_TREATMENT, REJECT_TREATMENT, CLOSE_CASE, EDIT_CLINICAL_DATA, ASSIGN_DOCTOR
    - _Requirements: 1.3, 1.4_

  - [x] 3.3 Create CoSDashboard middleware
    - Verify user has CoS role before dashboard access
    - Redirect unauthorized users with 403 Forbidden
    - _Requirements: 1.1_

  - [x] 3.4 Update RouteServiceProvider to register dashboard routes
    - Apply CoSDashboard middleware to all /cos-dashboard routes
    - Register permission checks on protected endpoints
    - _Requirements: 1.1, 1.2_

- [x] 4. Implement Core Service Layer - Part 1: Dashboard & Case Services
  - [x] 4.1 Create CoSDashboardService
    - Implement getMetricsByStage(filters): returns open case counts by stage
    - Implement calculateAverageTAT(stage, days): calculates average turnaround time
    - Implement identifyBreaches(sla_thresholds): identifies SLA breaches
    - Implement applyFilters(data, caseType, location): applies case type and location filters
    - _Requirements: 2.1, 2.2, 2.4_

  - [x] 4.2 Create CaseManagementService
    - Implement getCaseWithTimeline(caseId): returns case with full transition history
    - Implement getCurrentHolder(caseId): returns current case holder identity and duration
    - Implement calculateDurationAtStage(caseId): calculates time held at current stage
    - Implement escalateCaseToNextStage(caseId, reason): advances case routing
    - _Requirements: 3.1, 3.2, 5.5_

  - [x] 4.3 Create AuditService
    - Implement logAction(userId, actionType, caseId, data): creates audit log entry
    - Implement getAuditLog(caseId, filters): retrieves action history with filtering
    - Implement generateAuditReport(dateRange): comprehensive audit report
    - _Requirements: 8.5, 8.6_

  - [ ]* 4.4 Write property tests for CoSDashboardService
    - **Property 1: Metric Consistency Invariant**
    - **Validates: Requirements 2.1, 2.4**

  - [ ]* 4.5 Write property tests for CaseManagementService
    - **Property 4: Timeline Immutability Round Trip**
    - **Validates: Requirements 3.1, 3.3**

- [x] 5. Implement Core Service Layer - Part 2: Compliance & Notifications
  - [x] 5.1 Create ComplianceService
    - Implement getComplianceChecklist(): returns checklist item definitions
    - Implement evaluateCase(caseId): auto-populates checklist by querying case state
    - Implement saveChecklistResult(caseId, items, status): stores compliance result
    - Implement getComplianceReport(): retrieves flagged non-compliant case