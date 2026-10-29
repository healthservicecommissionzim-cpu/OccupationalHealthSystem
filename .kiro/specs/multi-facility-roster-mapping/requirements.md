# Requirements Document: Multi-Facility Roster Mapping

## Introduction

This system enables tracking of rotational staff, locum doctors, and nursing students across multiple public hospitals and clinics while maintaining accurate cost center attribution for incidents. The system operates as a parallel tracking layer that does not modify existing incident workflow routing or supervisor/HR assignment logic.

## Glossary

- **Roster_Management_System**: The subsystem responsible for creating and managing staff roster records and facility assignments
- **Cost_Attribution_Engine**: The subsystem that determines which facility is financially responsible for incidents involving rotational staff
- **Incident_Workflow_Engine**: The existing system that routes incidents to supervisors and HR users based on incident location
- **Active_Assignment**: A roster assignment where the current date falls between assignment_start_date and assignment_end_date
- **Primary_Facility**: The worker's home base or permanent facility assignment
- **Cost_Center_Facility**: The facility financially responsible for an incident
- **Rotational_Staff**: Staff members with temporary assignments to facilities other than their primary facility
- **Staff_Type**: Classification of staff (permanent, locum, student, rotational)
- **Super_Admin**: User role with full system access and roster management permissions
- **District_HR**: User role with read access to rosters within their assigned district
- **Hospital_HR**: User role with read access to current staff at their facility
- **Central_HR**: User role with national-level read access to all roster data
- **MOHHC_HR**: Ministry of Health HR role with national-level workforce planning access
- **HSC_Conditions**: Compliance monitoring role with read-only access to roster data

## Requirements

### Requirement 1: Roster Record Management

**User Story:** As a Super Admin, I want to create and manage staff roster records, so that I can track staff assignments across multiple facilities.

#### Acceptance Criteria

1. WHEN a Super Admin creates a roster record, THE Roster_Management_System SHALL store the staff member's user_id, staff_type, primary_facility_id, start_date, and optional end_date
2. WHEN a roster record is created, THE Roster_Management_System SHALL validate that the user_id references a valid user account
3. WHEN a roster record is created, THE Roster_Management_System SHALL validate that the primary_facility_id references a valid facility
4. WHEN a roster record is created, THE Roster_Management_System SHALL validate that staff_type is one of: permanent, locum, student, or rotational
5. THE Roster_Management_System SHALL allow Super Admins to update roster records including staff_type, primary_facility_id, and active status
6. THE Roster_Management_System SHALL allow Super Admins to mark roster records as inactive without deleting historical data
7. WHEN a roster record is modified, THE Roster_Management_System SHALL record the user_id of the Super Admin making the change
8. WHEN a roster record is modified, THE Roster_Management_System SHALL update the modification timestamp

### Requirement 2: Facility Assignment Management

**User Story:** As a Super Admin, I want to assign staff to multiple facilities with specific time ranges, so that I can track temporary and rotational assignments.

#### Acceptance Criteria

1. WHEN a Super Admin creates a facility assignment, THE Roster_Management_System SHALL store the staff_roster_id, facility_id, assignment_start_date, assignment_end_date, cost_center_facility_id, and assignment_type
2. WHEN a facility assignment is created, THE Roster_Management_System SHALL validate that assignment_start_date is not more than 2 years in the future
3. WHEN a facility assignment is created, THE Roster_Management_System SHALL validate that assignment_end_date is after assignment_start_date
4. WHEN a facility assignment is created with dates overlapping an existing active assignment at the same facility for the same staff member, THE Roster_Management_System SHALL reject the assignment and return an error
5. WHEN a facility assignment is created, THE Roster_Management_System SHALL validate that assignment_type is one of: rotation, locum_contract, or student_placement
6. THE Roster_Management_System SHALL allow Super Admins to update facility assignments including dates, cost_center_facility_id, and status
7. THE Roster_Management_System SHALL allow Super Admins to cancel facility assignments by setting status to cancelled
8. WHEN a facility assignment is modified, THE Roster_Management_System SHALL record the user_id of the Super Admin making the change

### Requirement 3: Active Assignment Lookup

**User Story:** As the system, I need to efficiently determine if a staff member has an active assignment at a specific facility on a given date, so that incidents can be correctly attributed.

#### Acceptance Criteria

1. WHEN queried for active assignments, THE Roster_Management_System SHALL return all assignments where the query date falls between assignment_start_date and assignment_end_date
2. WHEN queried for active assignments, THE Roster_Management_System SHALL exclude assignments with status set to cancelled
3. WHEN queried for active assignments at a specific facility, THE Roster_Management_System SHALL filter results by facility_id
4. THE Roster_Management_System SHALL return active assignment queries within 50 milliseconds using database indexes
5. THE Roster_Management_System SHALL cache active assignment results for 5 minutes

### Requirement 4: Incident Cost Attribution

**User Story:** As the system, I need to automatically determine cost center attribution when incidents are created, so that financial liability is accurately tracked.

#### Acceptance Criteria

1. WHEN an incident is created, THE Cost_Attribution_Engine SHALL check for active roster assignments for the worker at the incident location on the incident date
2. WHEN an active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set worker_primary_facility_id to the staff member's primary_facility_id
3. WHEN an active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set cost_center_facility_id to the assignment's cost_center_facility_id
4. WHEN an active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set roster_assignment_id to the active assignment's id
5. WHEN an active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set is_rotational_staff to true
6. WHEN no active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set worker_primary_facility_id to the incident's facility_id
7. WHEN no active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set cost_center_facility_id to the incident's facility_id
8. WHEN no active roster assignment exists for the worker at incident creation time, THE Cost_Attribution_Engine SHALL set is_rotational_staff to false
9. WHEN incident cost attribution is performed, THE Cost_Attribution_Engine SHALL complete within 100 milliseconds
10. WHEN incident cost attribution is performed, THE Incident_Workflow_Engine SHALL continue routing supervisors and HR users based on incident location without modification

### Requirement 5: Workflow Isolation

**User Story:** As a system architect, I need to ensure roster tracking does not interfere with existing incident workflows, so that case management continues to function correctly.

#### Acceptance Criteria

1. WHEN an incident is created with roster attribution, THE Incident_Workflow_Engine SHALL assign supervisors based on incident location facility_id
2. WHEN an incident is created with roster attribution, THE Incident_Workflow_Engine SHALL assign HR users via load-balancing based on incident location facility type
3. WHEN an incident is routed to HR, THE Incident_Workflow_Engine SHALL populate routed_to_hr_user_id based on incident location without considering roster data
4. WHEN notifications are sent for incident events, THE Incident_Workflow_Engine SHALL send to users assigned based on incident location
5. WHEN HR users filter their dashboard, THE Incident_Workflow_Engine SHALL filter by routed_to_hr_user_id without considering roster data

### Requirement 6: Super Admin Roster Interface

**User Story:** As a Super Admin, I want a dedicated interface for managing rosters, so that I can efficiently track and update staff assignments.

#### Acceptance Criteria

1. THE Roster_Management_System SHALL provide a web interface at /system/staff-roster/manage-rosters for CRUD operations on roster records
2. THE Roster_Management_System SHALL provide a web interface at /system/staff-roster/facility-assignments for viewing staff by facility with date ranges
3. THE Roster_Management_System SHALL provide a web interface at /system/staff-roster/cost-centers for configuring cost center rules
4. THE Roster_Management_System SHALL provide a web interface at /system/staff-roster/staff-types for managing staff type classifications
5. THE Roster_Management_System SHALL provide a web interface at /system/staff-roster/reports for roster analytics and reporting
6. WHEN a Super Admin accesses roster management interfaces, THE Roster_Management_System SHALL display current assignments and historical assignments separately
7. WHEN a Super Admin creates or updates a roster, THE Roster_Management_System SHALL validate all inputs and display clear error messages for validation failures
8. WHEN a Super Admin attempts to create overlapping assignments, THE Roster_Management_System SHALL prevent the operation and display a specific error message

### Requirement 7: Role-Based Roster Access

**User Story:** As an HR user, I want to view roster information relevant to my scope, so that I can understand staffing patterns without modifying critical data.

#### Acceptance Criteria

1. WHEN a Central_HR or MOHHC_HR user accesses roster interfaces, THE Roster_Management_System SHALL display all roster records and assignments nationally
2. WHEN a District_HR user accesses roster interfaces, THE Roster_Management_System SHALL display only roster records and assignments for facilities within their assigned district
3. WHEN a Hospital_HR user accesses roster interfaces, THE Roster_Management_System SHALL display only current staff at their assigned facility including permanent and rotational staff
4. WHEN a HSC_Conditions user accesses roster interfaces, THE Roster_Management_System SHALL display roster data in read-only mode for compliance monitoring
5. WHEN a non-Super Admin user attempts to create or modify roster records, THE Roster_Management_System SHALL reject the request and return an authorization error
6. THE Roster_Management_System SHALL enforce read-only access for Central_HR, MOHHC_HR, District_HR, Hospital_HR, and HSC_Conditions roles at the controller level

### Requirement 8: Cost Center Reporting

**User Story:** As a Super Admin, I want to view incidents grouped by cost center, so that I can track financial liability across facilities.

#### Acceptance Criteria

1. THE Roster_Management_System SHALL provide a cost center report showing all incidents grouped by cost_center_facility_id
2. WHEN displaying cost center reports, THE Roster_Management_System SHALL show worker_primary_facility_id, incident facility_id, and cost_center_facility_id for each incident
3. WHEN displaying cost center reports, THE Roster_Management_System SHALL indicate whether incidents involve rotational staff using the is_rotational_staff flag
4. THE Roster_Management_System SHALL allow Super Admins to export cost center reports in CSV format
5. THE Roster_Management_System SHALL display cost center summary statistics including total incidents, rotational incidents, and cost distribution
6. WHEN generating cost center reports, THE Roster_Management_System SHALL include roster assignment context for incidents flagged as rotational

### Requirement 9: Incident Case Dossier Enhancement

**User Story:** As an HR user reviewing an incident, I want to see roster context when applicable, so that I understand the worker's assignment status at the time of injury.

#### Acceptance Criteria

1. WHEN is_rotational_staff is true for an incident, THE Incident_Workflow_Engine SHALL display roster context in the case dossier
2. WHEN displaying roster context, THE Incident_Workflow_Engine SHALL show the worker's primary_facility name
3. WHEN displaying roster context, THE Incident_Workflow_Engine SHALL show the cost_center_facility name
4. WHEN displaying roster context, THE Incident_Workflow_Engine SHALL show the assignment_type from the linked roster assignment
5. WHEN displaying roster context, THE Incident_Workflow_Engine SHALL show the assignment date range from the linked roster assignment
6. WHEN is_rotational_staff is false for an incident, THE Incident_Workflow_Engine SHALL not display roster context sections

### Requirement 10: Audit Logging for Roster Changes

**User Story:** As a system administrator, I want all roster changes logged, so that I can audit modifications and ensure data integrity.

#### Acceptance Criteria

1. WHEN a roster record is created, THE Roster_Management_System SHALL create an audit log entry with the action type, user_id, timestamp, and changed data
2. WHEN a roster record is updated, THE Roster_Management_System SHALL create an audit log entry with the action type, user_id, timestamp, and before/after values
3. WHEN a roster record is marked inactive, THE Roster_Management_System SHALL create an audit log entry with the action type, user_id, and timestamp
4. WHEN a facility assignment is created, THE Roster_Management_System SHALL create an audit log entry with the action type, user_id, timestamp, and assignment details
5. WHEN a facility assignment is modified, THE Roster_Management_System SHALL create an audit log entry with the action type, user_id, timestamp, and before/after values
6. WHEN a facility assignment is cancelled, THE Roster_Management_System SHALL create an audit log entry with the action type, user_id, and timestamp
7. THE Roster_Management_System SHALL allow Super Admins to view audit logs for roster-related changes
8. THE Roster_Management_System SHALL retain audit logs indefinitely for compliance purposes

### Requirement 11: Data Validation and Integrity

**User Story:** As a system architect, I want strict data validation on roster records, so that data integrity is maintained and edge cases are handled correctly.

#### Acceptance Criteria

1. WHEN a facility assignment end_date is provided, THE Roster_Management_System SHALL validate that it is after the assignment_start_date
2. WHEN multiple active assignments exist for the same staff member on the same date at different facilities, THE Roster_Management_System SHALL flag this as a data error requiring Super Admin resolution
3. WHEN a cost_center_facility_id is provided, THE Roster_Management_System SHALL validate that it references a valid facility
4. WHEN a staff member has no active assignments at the incident location, THE Cost_Attribution_Engine SHALL treat the incident as a regular incident at the worker's primary facility
5. WHEN a roster assignment end_date occurs after an incident date but the assignment was active at the time of injury, THE Cost_Attribution_Engine SHALL still link the incident to the roster assignment
6. THE Roster_Management_System SHALL prevent deletion of roster records that are linked to incidents
7. THE Roster_Management_System SHALL prevent deletion of facility assignments that are linked to incidents

### Requirement 12: Performance and Caching

**User Story:** As a system architect, I want roster lookups to be performant, so that incident creation is not slowed down.

#### Acceptance Criteria

1. THE Roster_Management_System SHALL create database indexes on staff_rosters.user_id, roster_assignments.staff_roster_id, roster_assignments.facility_id, and roster_assignments.assignment_start_date
2. THE Roster_Management_System SHALL create database indexes on incidents.worker_primary_facility_id, incidents.cost_center_facility_id, and incidents.roster_assignment_id
3. WHEN active assignment queries are executed, THE Roster_Management_System SHALL complete within 50 milliseconds
4. THE Roster_Management_System SHALL cache active assignment results for 5 minutes using the application cache
5. WHEN the cache is used, THE Roster_Management_System SHALL invalidate cached results when roster assignments are created or modified
6. WHEN incident cost attribution adds roster data, THE Cost_Attribution_Engine SHALL add no more than 100 milliseconds to total incident creation time

### Requirement 13: Export and Reporting

**User Story:** As a Central HR user, I want to export roster data and reports, so that I can perform workforce planning analysis.

#### Acceptance Criteria

1. THE Roster_Management_System SHALL allow authorized users to export current roster assignments in CSV format
2. THE Roster_Management_System SHALL allow authorized users to export historical roster assignments in CSV format
3. WHEN exporting roster data, THE Roster_Management_System SHALL include staff name, staff_type, primary_facility, assigned_facility, assignment dates, cost_center, and status
4. THE Roster_Management_System SHALL allow authorized users to filter export data by date range, facility, staff_type, and district
5. THE Roster_Management_System SHALL generate workforce distribution reports showing staff counts by facility over time
6. THE Roster_Management_System SHALL generate rotation pattern reports showing incoming and outgoing staff movements by facility

### Requirement 14: Migration and Historical Data

**User Story:** As a Super Admin, I want to migrate existing rotational staff data into the new system, so that historical context is preserved.

#### Acceptance Criteria

1. THE Roster_Management_System SHALL provide a bulk import interface for roster records from CSV files
2. WHEN importing roster data, THE Roster_Management_System SHALL validate all records before committing any to the database
3. WHEN import validation fails, THE Roster_Management_System SHALL provide a detailed error report identifying invalid records and reasons
4. THE Roster_Management_System SHALL allow Super Admins to backdate roster assignments to capture historical rotations
5. WHEN backdated roster assignments are created, THE Roster_Management_System SHALL not retroactively modify existing incident cost attributions
6. THE Roster_Management_System SHALL preserve all historical roster data even when staff leave or facilities close
