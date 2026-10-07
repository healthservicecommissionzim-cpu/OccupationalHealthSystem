# Complete Case Flow: From Polyclinic to PMD Review

## Overview
This document traces the complete journey of an occupational health and safety incident from initial reporting at a polyclinic through to final review by the Provincial Medical Director (PMD) and beyond.

---

## STAGE 1: INCIDENT REPORTING (Worker/Polyclinic)

### 1.1 Initial Injury Report
**Where**: Polyclinic/Healthcare Facility  
**Who**: Worker or Reporter (facility staff)  
**Status**: `INCIDENT_REPORTED`  
**What Happens**:
- Worker reports injury at polyclinic
- Facility staff completes initial incident report with:
  - Employee details (name, ID, department, designation)
  - Injury date and time
  - Incident description and circumstances
  - Injury type and severity
  - Medical observations
- System generates unique reference number (HSC-YYYY-XXXXX)
- Incident created in database with status `INCIDENT_REPORTED`
- Public tracking link generated for worker updates

### 1.2 Public Document Upload
**Who**: Worker/Reporter  
**How**: Public track page (`/incidents/{id}/track`)  
**What Happens**:
- Worker accesses public link to upload supporting documents
- Uploads evidence: photos, medical reports, witness statements
- Documents stored in `storage/app/public/incident-attachments/{incident_id}/`
- **Triggers**: `IncidentDocumentUploaded` event
- **Notification**: SMS + Email sent to assigned HR user (via `SendDocumentUploadNotification` listener)

---

## STAGE 2: SUPERVISOR VERIFICATION

**Who**: Immediate Supervisor  
**Status**: Waiting for `SUPERVISOR_VERIFIED_AT`  
**Timeline**: Should be within 24 hours of incident  
**What Happens**:
- Supervisor logs into system
- Reviews initial incident details
- Verifies accuracy of worker information and incident description
- Approves or requests clarifications
- Provides supervisor's incident account and observations
- Marks as verified
- **Result**: `supervisor_verified_at` timestamp recorded

**Key Validation**:
- All mandatory fields completed
- Incident details coherent and plausible
- Worker details correct
- Department and designation align with system records

---

## STAGE 3: HOD REPORT SUBMISSION

**Who**: Head of Department (HOD)  
**Status**: Moves toward `HOD_REPORT_SUBMITTED`  
**Timeline**: Within 48 hours of supervisor verification  
**What Happens**:
- HOD receives incident for review
- HOD reviews supervisor account and all documents
- Completes formal HOD incident report including:
  - Analysis of incident cause
  - Immediate corrective actions taken
  - Preventive measures for future
  - Risk assessment
  - Departmental context
- Submits HOD report
- **Result**: 
  - `hod_report_submitted_at` timestamp recorded
  - `hod_report_submitted_by` user ID stored
  - **ESCALATES TO HR**: System triggers HR user assignment via load-balancing

### 3.1 HR User Assignment (Load-Balancing)
**Function**: `assignHRUserForIncident()`  
**Logic**:
- Queries all district_hr users
- Counts pending case assignments for each
- Assigns to HR user with **fewest pending cases**
- **Result**: `incidents.routed_to_hr_user_id` populated
- Ensures even distribution of workload

---

## STAGE 4: HR DOSSIER REVIEW

**Who**: Assigned HR User (determined by load-balancing)  
**Status**: `HR_DOSSIER_RECEIVED` → `HR_REVIEW_IN_PROGRESS` → `HR_REVIEWED`  
**Timeline**: 3-5 business days  
**What Happens**:

### 4.1 HR Acknowledges Dossier
- HR user notified via email/SMS
- HR logs in and reviews complete dossier:
  - Initial incident report
  - Supervisor verification
  - HOD report
  - All worker-uploaded documents
- HR acknowledges receipt
- **Result**: `hr_dossier_acknowledged_at` timestamp recorded

### 4.2 HR Conducts Compliance Review
- HR validates incident against HSE regulations
- Checks for policy violations
- Assesses organizational liability
- Reviews injury severity and documentation
- Issues compliance determination:
  - **COMPLIANT**: Incident handled per policy
  - **NON_COMPLIANT**: Policy violations detected
  - **NEEDS_CLARIFICATION**: Missing information
- **Result**: `hr_compliance_status` recorded

### 4.3 HR Prepares For Executive Review
- Compiles executive summary
- Flags critical issues
- Prepares case for clinical review
- **Result**: `hr_reviewed_at` timestamp recorded
- Case moves to CMO/DMO stage

### 4.4 Document Upload Notifications (During Stage 4)
- **When**: Worker uploads additional documents during HR review
- **Process**: 
  - New `IncidentAttachment` created
  - `IncidentDocumentUploaded` event fires
  - `SendDocumentUploadNotification` listener executes
  - **Gets assigned HR user** from `incident.routed_to_hr_user_id`
  - Sends email + SMS to the **specific assigned HR user only** (not all HR staff)
- **Notification Content**:
  - Incident reference, worker name, facility
  - Document type uploaded
  - Link to review in HR dashboard
- **Tracking**: Logged in `incident_notifications` table with status `SENT`

---

## STAGE 5: CMO/DMO CLINICAL REVIEW

**Who**: Chief Medical Officer (CMO) and District Medical Officer (DMO)  
**Status**: `CMO_REVIEW_IN_PROGRESS`  
**Timeline**: 3-7 business days  
**What Happens**:

### 5.1 Case Routed to CMO
- HR recommendation forwarded
- CMO assigned based on facility location
- **Result**: `routed_to_cmo_user_id` populated

### 5.2 CMO Clinical Assessment
- Reviews medical records and injury severity
- Assesses medical evidence quality
- Evaluates first aid provision appropriateness
- Determines if specialist referral needed
- Makes clinical recommendations

### 5.3 Case Routed to DMO
- CMO forwards to DMO for district-level review
- **Result**: `routed_to_dmo_user_id` populated

### 5.4 DMO Executive Audit
- DMO conducts comprehensive executive audit
- Verifies all procedural compliance
- Cross-checks against district safety standards
- Validates injury categorization
- **Result**: 
  - `executive_audit_verified` set to `true`
  - `executive_audit_verified_at` timestamp recorded

### 5.5 Executive Recommendation Letter
- DMO generates formal recommendation letter
- Includes:
  - Clinical findings summary
  - Organizational compliance assessment
  - Recommended injury leave duration
  - Any disciplinary or corrective recommendations
  - Risk mitigation suggestions
- **Result**: 
  - `executive_recommendation_submitted_at` timestamp recorded
  - Letter stored in system

---

## STAGE 6: PMD FINAL REVIEW

**Who**: Provincial Medical Director (PMD)  
**Status**: `PMD_REVIEW` → `PMD_APPROVED` → `READY_FOR_SOCIAL_SERVICES`  
**Timeline**: 5-10 business days  
**What Happens**:

### 6.1 PMD Assignment
- Case forwarded to Provincial level
- PMD assigned case
- **Result**: `routed_to_pmd_user_id` populated
- **Initial Status**: `routed_to_pmd_status` = `PENDING`

### 6.2 PMD Conducts Final Review
- PMD reviews entire case file
- Validates all prior reviews (HR, CMO, DMO)
- Makes final medical authority determination
- Can:
  - **APPROVE**: Accept all prior recommendations
  - **REJECT**: Request re-evaluation
  - **MODIFY**: Adjust injury classification or leave duration
- **Result**: `routed_to_pmd_status` updated with decision

### 6.3 PMD Decision Options

| Decision | Action | Next Stage |
|----------|--------|-----------|
| APPROVED | Accepts recommendations, authorizes injury leave | Social Services |
| REJECTED | Returns to CMO for reconsideration | Back to CMO |
| MODIFIED | Adjusts parameters but approves | Social Services |
| REQUIRES_SPECIALIST | Refers to medical specialist | Specialist Review |

### 6.4 PMD Sign-Off
- PMD digitally approves final determination
- Case marked as clinically reviewed
- Status moves to `PMD_APPROVED`

---

## STAGE 7: SOCIAL SERVICES HANDOFF

**Who**: Social Services Department  
**Status**: `SUBMITTED_TO_SOCIAL_SERVICES` → `SOCIAL_SERVICES_IN_PROGRESS` → `CASE_CLOSED`  
**Timeline**: Final processing stage  
**What Happens**:

### 7.1 Case Submission to Social Services
- Formatted case package prepared with:
  - PMD final determination
  - Executive recommendation letter
  - Approved injury leave duration
  - Worker beneficiary information
  - Payment authorization details
- Case submitted to Social Services
- **Result**: 
  - `submitted_to_social_services_at` timestamp recorded
  - `submission_status` set to `SUBMITTED`

### 7.2 Physical Handoff
- Printed case file prepared
- Documents packaged
- Tracked handoff occurs
- **Result**: `physical_handoff_timestamp` recorded

### 7.3 Signed Manifest
- Social Services signs receipt manifest
- Confirms all documents received
- Manifest uploaded to system
- **Result**: `signed_manifest_uploaded_at` timestamp recorded

### 7.4 Injury Leave Processing
- Social Services calculates benefit amount
- Schedules worker payments
- Creates benefit disbursement schedule
- **Result**: `total_injury_leave_days` recorded in case

---

## STAGE 8: MEDICAL BOARD (Conditional)

**When**: Required for serious injuries or disputed claims  
**Who**: Medical Board Panel  
**What Happens**:
- Case referred to specialized medical board if needed
- Additional clinical evaluation conducted
- Final determination on injury classification
- Appeals process available

---

## COMPLETE STATUS FLOW

```
INCIDENT_REPORTED
    ↓
SUPERVISOR_VERIFIED_AT (timestamp)
    ↓
HOD_REPORT_SUBMITTED_AT (timestamp) + HR User Assignment
    ↓
HR_DOSSIER_ACKNOWLEDGED_AT (timestamp)
    ↓
HR_REVIEWED_AT (timestamp) + HR_COMPLIANCE_STATUS
    ↓
ROUTED_TO_CMO_USER_ID + ROUTED_TO_DMO_USER_ID
    ↓
EXECUTIVE_AUDIT_VERIFIED_AT (timestamp)
    ↓
EXECUTIVE_RECOMMENDATION_SUBMITTED_AT (timestamp)
    ↓
ROUTED_TO_PMD_USER_ID + ROUTED_TO_PMD_STATUS (PENDING → APPROVED/REJECTED/MODIFIED)
    ↓
SUBMITTED_TO_SOCIAL_SERVICES_AT (timestamp)
    ↓
PHYSICAL_HANDOFF_TIMESTAMP + SIGNED_MANIFEST_UPLOADED_AT (timestamp)
    ↓
TOTAL_INJURY_LEAVE_DAYS (recorded)
    ↓
CASE_CLOSED
```

---

## KEY FEATURES & SYSTEM INTEGRATIONS

### Real-time Notifications
- **Email**: Sent at each stage transition
- **SMS**: For urgent updates and document uploads
- **Database Notifications**: Tracked in `incident_notifications` table
- **In-App Alerts**: Dashboard notifications for assigned users

### Document Upload Handling
- Worker can upload at any stage via public link
- Documents trigger immediate HR notification
- **SMS Content**: 
  ```
  HSC-OHS: New documents uploaded for incident [REF] - Worker: [NAME]. 
  Please log in to review. Visit injury.hsc.org.zw
  ```
- **Email**: Includes incident reference, worker name, facility, and review link

### Audit Trail
- Every stage change logged in `AuditLog` table
- User IDs and timestamps recorded for all actions
- Document upload history tracked
- Notifications logged with status (SENT/FAILED)

### Load-Balancing Algorithm
- Prevents overloading single HR staff member
- Distributes cases based on current workload
- Ensures fair case assignment
- Improves response times and quality

### Escalation Paths
- Cases can move backward if PMD rejects
- Specialist referrals available for complex cases
- Appeal mechanism for disputed determinations

---

## TYPICAL TIMELINE

| Stage | Typical Duration | Cumulative |
|-------|------------------|-----------|
| Incident Report → Supervisor Verification | 24 hours | 24 hours |
| Supervisor Verification → HOD Report | 48 hours | 3 days |
| HOD Report → HR Dossier Acknowledged | 12 hours | 3.5 days |
| HR Dossier → HR Review Complete | 3-5 days | 8-10 days |
| HR Review → CMO/DMO Review | 1 day | 9-11 days |
| CMO/DMO → Executive Audit | 3-5 days | 12-16 days |
| Executive Audit → PMD Review | 5-10 days | 17-26 days |
| PMD → Social Services | 1-2 days | 18-28 days |
| Social Services → Case Closed | 7-14 days | 25-42 days |

**Total Average Processing Time**: 3-6 weeks from initial report to case closure

---

## CRITICAL SYSTEM FIELDS

```php
$incident->created_at                          // Initial report timestamp
$incident->supervisor_verified_at              // Supervisor verification
$incident->hod_report_submitted_at             // HOD report timestamp
$incident->hod_report_submitted_by             // HOD user ID
$incident->routed_to_hr_user_id                // Assigned HR user (load-balanced)
$incident->hr_dossier_acknowledged_at          // HR acknowledgment
$incident->hr_reviewed_at                      // HR review completion
$incident->hr_compliance_status                // HR compliance determination
$incident->routed_to_cmo_user_id               // Assigned CMO
$incident->routed_to_dmo_user_id               // Assigned DMO
$incident->executive_audit_verified            // Boolean flag
$incident->executive_audit_verified_at         // Audit timestamp
$incident->executive_recommendation_submitted_at // Letter timestamp
$incident->routed_to_pmd_user_id               // Assigned PMD
$incident->routed_to_pmd_status                // PMD decision status
$incident->submitted_to_social_services_at    // Social services timestamp
$incident->submission_status                   // Submission status
$incident->physical_handoff_timestamp          // Physical handoff
$incident->signed_manifest_uploaded_at         // Manifest upload
$incident->total_injury_leave_days             // Approved leave days
$incident->status                              // Current overall status
```

---

## NOTIFICATION RULES

### Document Upload Notifications
- **Triggers**: When worker uploads documents via public track page
- **Recipients**: Only the **specific HR user assigned** to the case (load-balanced)
- **Channels**: Email + SMS (if phone available)
- **Content**: Incident reference, worker name, facility, document type
- **Logging**: Tracked in `incident_notifications` with type `DOCUMENT_UPLOAD`

### Stage Transition Notifications
- **HR Dossier Received**: Notification to assigned HR user
- **HR Review Complete**: Notification to CMO/DMO
- **Executive Audit Complete**: Notification to PMD
- **PMD Decision Made**: Notification to Social Services

---

## ERROR HANDLING & EDGE CASES

### Document Upload Fails
- Email still sent if SMS fails
- Failure logged but processing continues
- Case not blocked

### HR User Overloaded
- Load-balancing algorithm detects and redistributes
- New cases assigned to less-busy HR staff
- Workload rebalances automatically

### PMD Rejects Case
- Case reverts to CMO for reconsideration
- Timeline extends
- Notifications sent to CMO about rejection reason

### Missing Information
- HR can mark as `NEEDS_CLARIFICATION`
- Worker requested to provide additional documents
- Public link allows continued uploads
- Case cannot proceed to CMO until clarified

---

## TESTING THE COMPLETE FLOW

### Quick Status Check Script
```php
php check_case_flow.php  // Displays complete stage progression
```

### Test Incident Reference
```
HSC-2026-000045  // Example case in system logs
```

### Verification Points
1. Incident created with reference number
2. Supervisor verified timestamp recorded
3. HOD report submitted and HR user assigned
4. HR user received notification of document uploads
5. HR compliance status determined
6. CMO/DMO assigned and case forwarded
7. Executive audit verified and letter generated
8. PMD made final determination
9. Case submitted to Social Services
10. Physical handoff documented with signed manifest