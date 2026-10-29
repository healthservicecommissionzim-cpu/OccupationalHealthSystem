# Implementation Plan: Multi-Facility Roster Mapping

## Overview

This implementation plan breaks down the Multi-Facility Roster Mapping feature into discrete, incremental coding tasks. The system enables tracking of rotational staff, locum doctors, and nursing students across multiple facilities while maintaining accurate cost center attribution. Implementation proceeds in four phases: database setup, core services, UI/controllers, and reporting.

**Key Constraints**:
- Must not modify existing incident workflow routing logic
- Must not change supervisor or HR user assignment mechanisms
- Must maintain performance (< 100ms overhead for incident creation)
- All roster modifications must be audit logged

## Tasks

- [x] 1. Database schema and migrations
  - [x] 1.1 Create staff_rosters table migration
    - Create migration for staff_rosters table with all required fields and indexes
    - Add foreign key constraints to users and facilities tables
    - Include enum for staff_type (permanent, locum, student, rotational)
    - _Requirements: 1.1, 1.4_
  
  - [x] 1.2 Create roster_assignments table migration
    - Create migration for roster_assignments table with date range fields
    - Add foreign keys to staff_rosters, facilities, and users
    - Include enum for assignment_type (rotation, locum_contract, student_placement)
    - Include enum for status (active, completed, cancelled)
    - Add composite index on (facility_id, assignment_start_date, assignment_end_date, status) for performance
    - _Requirements: 2.1, 2.5, 3.4_
  
  - [x] 1.3 Create cost_centers table migration
    - Create migration for cost_centers table with cost center codes and names
    - Add foreign key to facilities table
    - Add unique constraint on cost_center_code
    - _Requirements: 8.1_
  
  - [x] 1.4 Create roster_audit_logs table migration
    - Create migration for roster_audit_logs table with polymorphic auditable fields
    - Include action_type enum (created, updated, deleted, deactivated, cancelled)
    - Store old_values and new_values as JSON columns
    - Add indexes on auditable_type, auditable_id, and created_at
    - _Requirements: 10.1, 10.2_
  
  - [x] 1.5 Add roster attribution fields to incidents table
    - Create migration to add worker_primary_facility_id, cost_center_facility_id, roster_assignment_id, and is_rotational_staff to incidents table
    - Add foreign key constraints with ON DELETE RESTRICT
    - Add indexes for reporting queries
    - _Requirements: 4.2, 4.3, 4.4, 4.5_
  
  - [x] 1.6 Run migrations and verify schema
    - Execute all migrations in development environment
    - Verify all tables, columns, indexes, and constraints exist
    - Test foreign key constraints with sample data
    - _Requirements: 1.1, 2.1_

- [-] 2. Eloquent models and relationships
  - [x] 2.1 Create StaffRoster model
    - Define StaffRoster model with fillable fields and casts
    - Add relationships: user(), primaryFacility(), createdBy(), assignments()
    - Add scopes: scopeActive(), scopeByStaffType(), scopeByFacility()
    - _Requirements: 1.1, 1.6_
  
  - [x] 2.2 Create RosterAssignment model
    - Define RosterAssignment model with fillable fields and date casts
    - Add relationships: staffRoster(), facility(), costCenterFacility(), supervisor(), createdBy(), incidents()
    - Add scopes: scopeActive(), scopeActiveOn(), scopeAtFacility(), scopeByStatus()
    - _Requirements: 2.1_
  
  - [x] 2.3 Create CostCenter model
    - Define CostCenter model with fillable fields
    - Add relationship: facility()
    - Add scope: scopeActive()
    - _Requirements: 8.1_
  
  - [x] 2.4 Create RosterAuditLog model
    - Define RosterAuditLog model with polymorphic morphTo relationship
    - Add cast for old_values and new_values as arrays
    - Add relationship: user()
    - _Requirements: 10.1_
  
  - [x] 2.5 Extend Incident model with roster relationships
    - Add workerPrimaryFacility(), costCenterFacility(), rosterAssignment() relationships to existing Incident model
    - Add scopeRotationalStaff() and scopeByCostCenter() scopes
    - Update fillable array and casts
    - _Requirements: 4.2, 4.3, 4.4_

- [x] 3. Validation service
  - [x] 3.1 Create ValidationService class
    - Create app/Services/Roster/ValidationService.php
    - Implement validateRosterData() method with Laravel validation rules
    - Implement validateAssignmentData() method
    - _Requirements: 1.2, 1.3, 1.4_
  
  - [x] 3.2 Implement date validation methods
    - Implement validateStartDate() to check 2-year future limit
    - Implement validateDateRange() to verify end_date after start_date
    - _Requirements: 2.2, 2.3_
  
  - [x] 3.3 Implement enum validation methods
    - Implement validateStaffType() with enum check
    - Implement validateAssignmentType() with enum check
    - Return specific validation error messages
    - _Requirements: 1.4, 2.5_
  
  - [ ]* 3.4 Write unit tests for ValidationService
    - Test valid and invalid date ranges
    - Test enum validation with valid and invalid values
    - Test foreign key validation
    - Test error message formatting
    - _Requirements: 1.2, 1.3, 1.4, 2.2, 2.3, 2.5_

- [x] 4. Audit service
  - [x] 4.1 Create AuditService class
    - Create app/Services/Roster/AuditService.php
    - Implement logRosterCreation() method to create audit log entries
    - Implement logRosterUpdate() with before/after value tracking
    - Implement logAssignmentCreation() and logAssignmentUpdate()
    - _Requirements: 10.1, 10.2, 10.4, 10.5_
  
  - [x] 4.2 Implement audit log retrieval
    - Implement getAuditLogs() method to retrieve logs by roster or assignment
    - Order logs by created_at descending
    - Include user relationship for display
    - _Requirements: 10.7_
  
  - [ ]* 4.3 Write property test for audit logging
    - **Property 6: Audit Trail for Modifications**
    - Generate random roster/assignment operations
    - Verify audit log is created for each operation with correct action_type and user_id
    - **Validates: Requirements 1.7, 1.8, 10.1, 10.3, 10.4, 10.6**
  
  - [ ]* 4.4 Write property test for before/after values
    - **Property 7: Audit Log Before/After Values**
    - Generate random roster/assignment updates
    - Verify audit log contains both original and new values
    - **Validates: Requirements 10.2, 10.5**

- [-] 5. Assignment service with overlap detection
  - [x] 5.1 Create AssignmentService class
    - Create app/Services/Roster/AssignmentService.php
    - Inject ValidationService and AuditService dependencies
    - _Requirements: 2.1_
  
  - [x] 5.2 Implement hasOverlappingAssignment() method
    - Query for assignments at same facility for same staff roster
    - Check date range overlap: (start1 <= end2) AND (end1 >= start2)
    - Exclude cancelled status assignments
    - Optionally exclude specific assignment ID for updates
    - _Requirements: 2.4_
  
  - [x] 5.3 Implement createAssignment() method
    - Validate input using ValidationService
    - Check for overlapping assignments using hasOverlappingAssignment()
    - Throw OverlapException if overlap detected
    - Create assignment within database transaction
    - Log creation using AuditService
    - _Requirements: 2.1, 2.4, 2.8_
  
  - [x] 5.4 Implement getActiveAssignment() method
    - Query assignments where query date between start and end dates
    - Filter by facility_id and staff roster's user_id
    - Exclude cancelled status
    - Return first matching assignment or null
    - _Requirements: 3.1, 3.2, 3.3_
  
  - [x] 5.5 Implement updateAssignment() method
    - Validate input
    - Check for overlaps if dates are being changed
    - Update assignment within transaction
    - Log update with before/after values
    - _Requirements: 2.6, 2.8_
  
  - [x] 5.6 Implement cancelAssignment() method
    - Set status to 'cancelled'
    - Log cancellation
    - _Requirements: 2.7, 2.8_
  
  - [ ]* 5.7 Write property test for overlap detection
    - **Property 10: Overlap Detection**
    - Generate existing assignment with date range
    - Generate overlapping date ranges (various patterns)
    - Verify all overlapping assignments are rejected
    - **Validates: Requirements 2.4**
  
  - [ ]* 5.8 Write property test for active assignment filtering
    - **Property 14: Active Assignment Date Filtering**
    - Generate random assignments with various date ranges
    - Generate random query dates
    - Verify only assignments containing query date are returned
    - **Validates: Requirements 3.1, 3.2**
  
  - [ ]* 5.9 Write property test for facility filtering
    - **Property 15: Active Assignment Facility Filtering**
    - Generate assignments at multiple facilities
    - Query by facility_id and date
    - Verify only matching facility assignments returned
    - **Validates: Requirements 3.3**

- [x] 6. Roster service
  - [x] 6.1 Create RosterService class
    - Create app/Services/Roster/RosterService.php
    - Inject ValidationService and AuditService
    - _Requirements: 1.1_
  
  - [x] 6.2 Implement createRoster() method
    - Validate input using ValidationService
    - Create roster record within transaction
    - Log creation using AuditService
    - Return created StaffRoster model
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.7, 1.8_
  
  - [x] 6.3 Implement updateRoster() method
    - Validate input
    - Capture original values before update
    - Update roster within transaction
    - Log update with before/after values
    - _Requirements: 1.5, 1.7, 1.8_
  
  - [x] 6.4 Implement deactivateRoster() method
    - Set active = false
    - Log deactivation
    - Do not delete record
    - _Requirements: 1.6_
  
  - [x] 6.5 Implement getRostersByUserId() method
    - Query rosters by user_id
    - Include relationships (primaryFacility, assignments)
    - _Requirements: 1.1_
  
  - [ ]* 6.6 Write property test for roster persistence
    - **Property 1: Roster Record Persistence**
    - Generate valid roster data (user_id, staff_type, primary_facility_id, dates)
    - Create roster, retrieve, verify all fields match
    - **Validates: Requirements 1.1**
  
  - [ ]* 6.7 Write property test for soft deletion
    - **Property 5: Soft Deletion via Inactive Status**
    - Generate active roster
    - Mark as inactive
    - Verify roster exists with active=false and all data preserved
    - **Validates: Requirements 1.6**

- [ ] 7. Cost attribution engine
  - [x] 7.1 Create CostAttributionEngine class
    - Create app/Services/Roster/CostAttributionEngine.php
    - Inject AssignmentService and Cache facade
    - _Requirements: 4.1_
  
  - [x] 7.2 Implement getPrimaryFacilityId() method
    - Query user's facility_id from users table
    - Return facility_id as primary facility
    - _Requirements: 4.6_
  
  - [x] 7.3 Implement getActiveAssignmentCached() method
    - Generate cache key from user_id, facility_id, date
    - Check cache first (5 minute TTL)
    - If miss, query using AssignmentService.getActiveAssignment()
    - Store result in cache
    - _Requirements: 3.5, 12.4_
  
  - [x] 7.4 Implement attributeIncident() method
    - Call getActiveAssignmentCached() to check for active assignment
    - If assignment found: return rotational staff attribution (primary facility from roster, cost center from assignment, assignment_id, is_rotational=true)
    - If no assignment: return default attribution (all fields = incident facility, is_rotational=false)
    - Ensure execution completes within 100ms
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.9_
  
  - [x] 7.5 Implement cache invalidation on assignment changes
    - Create invalidateCache() method
    - Call when assignments are created or modified
    - Clear cache for affected user_id and facility_id combinations
    - _Requirements: 12.5_
  
  - [ ]* 7.6 Write property test for rotational staff attribution
    - **Property 17: Rotational Staff Field Population**
    - Generate active roster assignment
    - Create incident at assignment facility during assignment period
    - Verify roster fields populated correctly and is_rotational_staff=true
    - **Validates: Requirements 4.2, 4.3, 4.4, 4.5**
  
  - [ ]* 7.7 Write property test for non-rotational attribution
    - **Property 18: Non-Rotational Default Field Population**
    - Generate incident without active assignment
    - Verify roster fields default to incident facility and is_rotational_staff=false
    - **Validates: Requirements 4.6, 4.7, 4.8**
  
  - [ ]* 7.8 Write property test for cache invalidation
    - **Property 16: Cache Invalidation on Assignment Changes**
    - Cache active assignment query result
    - Modify assignment
    - Verify cache is invalidated
    - **Validates: Requirements 12.5**

- [x] 8. Integrate cost attribution with incident creation
  - [x] 8.1 Modify IncidentController.store() method
    - Inject CostAttributionEngine dependency
    - After incident validation, call attributeIncident() method
    - Populate worker_primary_facility_id, cost_center_facility_id, roster_assignment_id, is_rotational_staff fields
    - Continue with existing workflow routing logic (supervisor, HR user assignment) unchanged
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.10_
  
  - [x] 8.2 Add error handling for attribution failures
    - Wrap attribution call in try-catch
    - If attribution fails (timeout, error), use default attribution (non-rotational)
    - Log error but allow incident creation to proceed
    - _Requirements: 4.9_
  
  - [ ]* 8.3 Write integration test for incident creation with roster
    - Create roster assignment
    - Create incident at assignment facility
    - Verify roster attribution fields populated
    - Verify workflow routing unchanged (supervisor, HR user based on incident location)
    - **Validates: Requirements 4.1, 4.2, 4.3, 4.4, 4.5, 4.10**
  
  - [ ]* 8.4 Write property test for workflow routing independence
    - **Property 19: Workflow Routing Independence**
    - Generate incidents with and without roster data at same facility
    - Verify assigned_supervisor_id and routed_to_hr_user_id are identical
    - **Validates: Requirements 4.10, 5.1, 5.2, 5.3, 5.5**

- [x] 9. Checkpoint - Core services complete
  - Verify all services are implemented and passing tests
  - Test end-to-end flow: create roster, create assignment, create incident
  - Verify audit logs are created correctly
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 10. Roster management routes and controllers
  - [x] 10.1 Create RosterController
    - Create app/Http/Controllers/System/RosterController.php
    - Add middleware for Super Admin authorization
    - Implement index() to list rosters with pagination and filtering
    - Implement create() to show create form
    - Implement store() to create roster using RosterService
    - Implement edit() to show edit form
    - Implement update() to update roster
    - Implement destroy() to deactivate roster (soft delete)
    - _Requirements: 6.1, 6.7, 6.8, 7.5_
  
  - [x] 10.2 Create AssignmentController
    - Create app/Http/Controllers/System/AssignmentController.php
    - Add middleware for Super Admin authorization
    - Implement index() to list assignments by facility with date ranges
    - Implement store() to create assignment using AssignmentService
    - Implement update() to update assignment
    - Implement cancel() to cancel assignment
    - Return validation errors with specific messages for overlaps
    - _Requirements: 6.1, 6.7, 6.8_
  
  - [x] 10.3 Create CostCenterController
    - Create app/Http/Controllers/System/CostCenterController.php
    - Add middleware for Super Admin authorization
    - Implement index() to list cost centers
    - Implement store() to create cost center
    - Implement update() to update cost center
    - _Requirements: 6.3_
  
  - [x] 10.4 Define roster management routes
    - Add routes in routes/web.php under /system/staff-roster prefix
    - Route /manage-rosters to RosterController
    - Route /facility-assignments to AssignmentController
    - Route /cost-centers to CostCenterController
    - Apply auth middleware and Super Admin role check
    - _Requirements: 6.1, 6.2, 6.3_

- [x] 11. Roster management UI (Blade views)
  - [x] 11.1 Create roster management index view
    - Create resources/views/system/roster/manage-rosters.blade.php
    - Display table of rosters with user name, staff type, primary facility, status
    - Add filters for staff type, facility, active status
    - Add "Create Roster" button
    - Add edit and deactivate actions
    - _Requirements: 6.1, 6.6_
  
  - [x] 11.2 Create roster create/edit form
    - Create resources/views/system/roster/form.blade.php
    - Fields: user (searchable dropdown), staff_type (select), primary_facility (select), start_date, end_date, notes
    - Display validation errors
    - Use Tailwind CSS for styling consistent with existing UI
    - _Requirements: 6.7_
  
  - [x] 11.3 Create facility assignments view
    - Create resources/views/system/roster/facility-assignments.blade.php
    - Display table grouped by facility showing current and upcoming assignments
    - Show assignment date ranges, staff member names, assignment types
    - Filter by facility and date range
    - Add "Create Assignment" button
    - _Requirements: 6.2_
  
  - [x] 11.4 Create assignment create/edit form
    - Create resources/views/system/roster/assignment-form.blade.php
    - Fields: staff_roster (select), facility, assignment dates, cost_center_facility, assignment_type, supervisor (optional), notes
    - Display overlap errors prominently
    - _Requirements: 6.7, 6.8_
  
  - [x] 11.5 Create cost center management view
    - Create resources/views/system/roster/cost-centers.blade.php
    - Display table of cost centers with facility, code, name, status
    - Add create and edit forms inline or modal
    - _Requirements: 6.3_
  
  - [x] 11.6 Add roster management link to Super Admin navigation
    - Update navigation blade template to include "Staff Roster" menu item
    - Link to /system/staff-roster/manage-rosters
    - Only visible to Super Admin role
    - _Requirements: 6.1_

- [x] 12. Read-only roster views for other roles
  - [x] 12.1 Create role-based roster filtering
    - Create app/Services/Roster/RosterAccessService.php
    - Implement getRostersForUser() method that filters by role:
      - Super Admin: all rosters
      - Central HR / MOHHC HR: all rosters nationally
      - District HR: rosters for facilities in their district
      - Hospital HR: current staff at their facility
      - HSC Conditions: all rosters (read-only)
    - _Requirements: 7.1, 7.2, 7.3, 7.4_
  
  - [x] 12.2 Create read-only roster view for HR roles
    - Create resources/views/roster/view.blade.php
    - Display roster information without edit buttons
    - Show current assignments for each staff member
    - Filter based on user role using RosterAccessService
    - _Requirements: 7.1, 7.2, 7.3, 7.4_
  
  - [x] 12.3 Add authorization checks to controllers
    - Add policy class app/Policies/RosterPolicy.php
    - Implement viewAny(), view(), create(), update(), delete() methods
    - Check role and scope (national, district, facility)
    - Return 403 for unauthorized actions
    - _Requirements: 7.5, 7.6_
  
  - [ ]* 12.4 Write unit test for authorization enforcement
    - **Property 21: Authorization Enforcement for Roster Modifications**
    - Attempt roster creation as non-Super Admin users
    - Verify authorization errors
    - **Validates: Requirements 7.5, 7.6**

- [ ] 13. Enhance incident case dossier with roster context
  - [x] 13.1 Update incident show view with roster context
    - Modify resources/views/incidents/show.blade.php
    - Add conditional section: if is_rotational_staff is true, display roster context
    - Show worker's primary facility name
    - Show cost center facility name
    - Show assignment type and date range from linked roster assignment
    - Use existing Laravel Blade components for consistent styling
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_
  
  - [x] 13.2 Create roster context partial
    - Create resources/views/incidents/partials/roster-context.blade.php
    - Display roster information in formatted card/panel
    - Include labels and values for all roster fields
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_
  
  - [ ]* 13.3 Write unit test for roster context display
    - Create incident with is_rotational_staff=true
    - Render view and verify roster context section is present
    - Create incident with is_rotational_staff=false
    - Verify roster context section is not present
    - **Validates: Requirements 9.1, 9.6**

- [x] 14. Checkpoint - UI and authorization complete
  - Test roster management UI as Super Admin
  - Test read-only access for HR roles
  - Test incident case dossier displays roster context
  - Ensure all tests pass, ask the user if questions arise.

- [-] 15. Report service and cost center reporting
  - [x] 15.1 Create ReportService class
    - Create app/Services/Roster/ReportService.php
    - _Requirements: 8.1_
  
  - [x] 15.2 Implement getIncidentsByCostCenter() method
    - Query incidents grouped by cost_center_facility_id
    - Include worker_primary_facility_id, incident facility_id, cost_center_facility_id
    - Include is_rotational_staff flag
    - Filter by date range if provided
    - Include roster assignment context for rotational incidents
    - _Requirements: 8.1, 8.2, 8.3, 8.6_
  
  - [x] 15.3 Implement cost center summary statistics
    - Calculate total incident count
    - Calculate rotational incident count (is_rotational_staff=true)
    - Group by cost_center_facility_id and count per facility
    - Return as array with counts
    - _Requirements: 8.5_
  
  - [x] 15.4 Implement getWorkforceDistribution() method
    - Query for date: count staff where primary_facility_id = facility OR active assignment at facility
    - Group results by facility
    - Return collection with facility names and counts
    - _Requirements: 13.5_
  
  - [x] 15.5 Implement getRotationMovements() method
    - Query assignments starting within date range (incoming)
    - Query assignments ending within date range (outgoing)
    - Filter by facility
    - Return array with 'incoming' and 'outgoing' collections
    - _Requirements: 13.6_
  
  - [x] 15.6 Implement exportRosterData() method
    - Query rosters and assignments based on filters
    - Include: staff name, staff_type, primary_facility, assigned_facility, dates, cost_center, status
    - Format as CSV with headers
    - Apply filters (date range, facility, staff_type, district)
    - _Requirements: 13.1, 13.2, 13.3, 13.4_
  
  - [ ]* 15.7 Write property test for cost center report grouping
    - **Property 22: Cost Center Report Grouping**
    - Generate incidents with various cost_center_facility_id values
    - Generate report
    - Verify incidents grouped correctly with required fields
    - **Validates: Requirements 8.1, 8.2, 8.3**
  
  - [ ]* 15.8 Write property test for statistics accuracy
    - **Property 23: Cost Center Report Statistics**
    - Generate random incidents (some rotational, some not)
    - Calculate expected counts
    - Generate summary statistics
    - Verify counts match expected
    - **Validates: Requirements 8.5**
  
  - [ ]* 15.9 Write property test for workforce distribution
    - **Property 31: Workforce Distribution Report Accuracy**
    - Generate staff with primary facilities and rotational assignments
    - Generate report for specific date
    - Verify staff counts match expected (primary + active rotational)
    - **Validates: Requirements 13.5**

- [x] 16. Report controllers and routes
  - [x] 16.1 Create ReportController
    - Create app/Http/Controllers/System/ReportController.php
    - Add middleware for Super Admin authorization
    - Implement costCenterReport() to display cost center incidents
    - Implement workforceDistribution() to display workforce by facility
    - Implement rotationMovements() to display incoming/outgoing staff
    - _Requirements: 6.5, 8.1, 13.5, 13.6_
  
  - [x] 16.2 Implement CSV export actions
    - Add exportCostCenterReport() action to export cost center data as CSV
    - Add exportRosterData() action to export roster/assignment data as CSV
    - Set appropriate headers for file download
    - Use ReportService.exportRosterData() method
    - _Requirements: 8.4, 13.1, 13.2_
  
  - [x] 16.3 Add report routes
    - Add routes in routes/web.php under /system/staff-roster/reports prefix
    - Route GET /cost-center to ReportController@costCenterReport
    - Route GET /workforce-distribution to ReportController@workforceDistribution
    - Route GET /rotation-movements to ReportController@rotationMovements
    - Route GET /export/cost-center to ReportController@exportCostCenterReport
    - Route GET /export/roster-data to ReportController@exportRosterData
    - _Requirements: 6.5, 8.4_

- [x] 17. Report UI views
  - [x] 17.1 Create cost center report view
    - Create resources/views/system/roster/reports/cost-center.blade.php
    - Display summary statistics at top (total incidents, rotational count, distribution)
    - Display table of incidents grouped by cost center facility
    - Show columns: reference, worker name, primary facility, incident facility, cost center facility, rotational flag, date
    - Add date range filter
    - Add "Export CSV" button
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_
  
  - [x] 17.2 Create roster context display for reports
    - For rotational incidents, display expandable row showing roster assignment context
    - Show assignment type, date range, primary facility, cost center
    - _Requirements: 8.6_
  
  - [x] 17.3 Create workforce distribution report view
    - Create resources/views/system/roster/reports/workforce-distribution.blade.php
    - Display chart or table showing staff count by facility for selected date
    - Break down by permanent and rotational staff
    - Add date selector
    - _Requirements: 13.5_
  
  - [x] 17.4 Create rotation movements report view
    - Create resources/views/system/roster/reports/rotation-movements.blade.php
    - Display two sections: incoming staff and outgoing staff
    - Show facility, date range, staff names, assignment types
    - Add facility and date range filters
    - _Requirements: 13.6_
  
  - [x] 17.5 Add reports link to navigation
    - Update navigation to include "Roster Reports" under Staff Roster menu
    - Link to /system/staff-roster/reports
    - Only visible to Super Admin role
    - _Requirements: 6.5_

- [ ] 18. Bulk import functionality
  - [x] 18.1 Create ImportService class
    - Create app/Services/Roster/ImportService.php
    - Inject RosterService and AssignmentService
    - _Requirements: 14.1_
  
  - [x] 18.2 Implement CSV parsing and validation
    - Implement importRostersCsv() method
    - Parse CSV file with expected columns: user_email, staff_type, primary_facility_code, start_date, end_date
    - Validate all records before committing
    - Collect validation errors with row numbers
    - Use database transaction for all-or-nothing commit
    - _Requirements: 14.2, 14.3_
  
  - [x] 18.3 Implement error reporting for import
    - Return detailed error report array
    - Include row number, field name, and error message for each validation failure
    - Format as JSON for API response or display in UI
    - _Requirements: 14.3_
  
  - [x] 18.4 Support backdated assignments
    - Allow assignment dates in the past during import
    - Do not retroactively modify existing incident attributions
    - _Requirements: 14.4, 14.5_
  
  - [x] 18.5 Add import action to RosterController
    - Add importForm() method to show import UI
    - Add processImport() method to handle file upload
    - Return success message or validation error report
    - _Requirements: 14.1_
  
  - [ ]* 18.6 Write property test for import transaction integrity
    - **Property 33: Bulk Import Transaction Integrity**
    - Generate CSV with mix of valid and invalid records
    - Attempt import
    - Verify no records committed if validation fails
    - **Validates: Requirements 14.2**
  
  - [ ]* 18.7 Write property test for backdated assignment non-retroactivity
    - **Property 35: Backdated Assignment Non-Retroactivity**
    - Create existing incident
    - Import backdated assignment that would have been active at incident date
    - Verify incident roster fields unchanged
    - **Validates: Requirements 14.5**

- [x] 19. Import UI
  - [x] 19.1 Create bulk import view
    - Create resources/views/system/roster/import.blade.php
    - Add file upload form for CSV
    - Display CSV format requirements and example
    - Show import validation errors if any (with row numbers)
    - Display success message on successful import
    - _Requirements: 14.1, 14.3_
  
  - [x] 19.2 Create CSV template download
    - Add route to download CSV template with headers
    - Include example rows in template
    - Link from import page
    - _Requirements: 14.1_
  
  - [x] 19.3 Add import link to roster management
    - Add "Bulk Import" button to roster management index page
    - Link to /system/staff-roster/import
    - _Requirements: 14.1_

- [x] 20. Data integrity constraints and prevention
  - [x] 20.1 Add referential integrity checks
    - Modify StaffRoster and RosterAssignment models
    - Override delete() method to check for linked incidents
    - Throw exception if roster/assignment is linked to incidents
    - Display user-friendly error message in UI
    - _Requirements: 11.6, 11.7_
  
  - [x] 20.2 Implement multiple assignment conflict detection
    - Add checkMultipleAssignments() method to AssignmentService
    - Query for staff with multiple active assignments on same date at different facilities
    - Flag as data error requiring Super Admin resolution
    - Display warnings on roster management dashboard
    - _Requirements: 11.2_
  
  - [ ]* 20.3 Write property test for referential integrity protection
    - **Property 28: Referential Integrity Protection**
    - Create roster/assignment linked to incident
    - Attempt deletion
    - Verify referential integrity error
    - **Validates: Requirements 11.6, 11.7**
  
  - [ ]* 20.4 Write property test for multiple assignment conflict detection
    - **Property 26: Multiple Assignment Conflict Detection**
    - Create multiple active assignments for same staff on same date at different facilities
    - Verify conflict error is raised
    - **Validates: Requirements 11.2**

- [x] 21. Performance optimization and caching
  - [x] 21.1 Verify database indexes are created
    - Review migration files to ensure all indexes from design are present
    - Test query execution times for active assignment lookups
    - Ensure queries complete within 50ms
    - _Requirements: 12.1, 12.2, 12.3_
  
  - [x] 21.2 Implement cache warming for common queries
    - Add console command to pre-cache active assignments for recent dates
    - Schedule to run daily via Laravel scheduler
    - _Requirements: 3.5, 12.4_
  
  - [x] 21.3 Add monitoring for attribution performance
    - Log execution time for Cost Attribution Engine calls
    - Alert if > 100ms threshold exceeded
    - _Requirements: 4.9, 12.6_
  
  - [ ]* 21.4 Write performance tests
    - Test active assignment query completes within 50ms
    - Test incident attribution adds < 100ms overhead
    - Test with large dataset (1000+ assignments)
    - **Validates: Requirements 3.4, 4.9, 12.3, 12.6**

- [ ] 22. Final integration testing and validation
  - [ ] 22.1 Write end-to-end integration tests
    - Test complete flow: create roster → create assignment → create incident → view report
    - Verify workflow routing unchanged (supervisor and HR assignment)
    - Verify notifications sent to correct users (incident location-based)
    - Test read-only access for various HR roles
    - _Requirements: 4.10, 5.1, 5.2, 5.3, 5.4, 5.5_
  
  - [ ]* 22.2 Write property test for notification routing independence
    - **Property 20: Notification Routing Independence**
    - Generate incident with roster attribution
    - Trigger notification
    - Verify recipients based on incident facility_id only
    - **Validates: Requirements 5.4**
  
  - [ ] 22.3 Manual testing checklist
    - Test as Super Admin: create rosters, assignments, view reports
    - Test as Central HR: verify national read-only access
    - Test as District HR: verify district-scoped access
    - Test as Hospital HR: verify facility-scoped access
    - Test incident creation with and without rotational staff
    - Test CSV import with valid and invalid data
    - Test overlap detection with various date patterns
    - Test cost center reports with filtering
    - _Requirements: All_

- [ ] 23. Documentation and deployment preparation
  - [ ] 23.1 Update API documentation
    - Document new roster management endpoints
    - Include request/response examples
    - Document error codes and messages
    - _Requirements: All_
  
  - [ ] 23.2 Create user guide for Super Admins
    - Document how to create rosters and assignments
    - Explain overlap detection and resolution
    - Document bulk import process
    - Explain cost center reporting
    - _Requirements: 6.1, 6.2, 6.3, 14.1_
  
  - [ ] 23.3 Create migration guide
    - Document how to migrate existing rotational staff data
    - Provide SQL queries for data verification
    - Document rollback procedure if needed
    - _Requirements: 14.1, 14.4_
  
  - [ ] 23.4 Update database backup procedures
    - Ensure new tables included in backup scripts
    - Test restore procedure with new schema
    - _Requirements: All_

- [ ] 24. Final checkpoint and deployment
  - Run all tests (unit, property, integration)
  - Verify test coverage meets targets (90% business logic, 80% overall)
  - Review audit logs for all operations
  - Deploy to staging environment and perform UAT
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional test-related sub-tasks and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation and user feedback opportunities
- Property tests use minimum 100 iterations to ensure comprehensive coverage
- All roster modifications are audit logged with user attribution
- Performance targets: < 50ms for active assignment queries, < 100ms overhead for incident attribution
- The system operates as a parallel tracking layer without modifying existing incident workflow routing
