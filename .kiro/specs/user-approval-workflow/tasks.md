0o;o0.;pl.# Implementation Plan: User Approval and Role Assignment Workflow

## Overview

This implementation plan converts the user approval workflow design into actionable tasks. The system will require users to register with pending status, allow Super Admin to review and assign roles with automatic permission grants, and route approved users to their role-specific dashboards.

## Programming Language

**PHP (Laravel Framework)**

## Tasks

- [x] 1. Create database migrations for user approval status and permissions
  - Create migration for user status column (pending, approved, rejected) and approval tracking
  - Create permissions table with id, name, slug, description, category
  - Create role_permission pivot table for mapping roles to permissions
  - Create role_assignments audit table for tracking role changes
  - _Requirements: 1, 2, 4_

- [x] 2. Seed initial permissions and role-permission mappings
  - Create seeder that defines all system permissions (view_incidents, create_incidents, approve_incidents, view_users, manage_users, view_reports, export_reports, view_first_aid, manage_first_aid)
  - Map permissions to each role (Supervisor, HSC Officer, District HR, DMO, PMD, HR (MoHCC), Labour & Social Welfare, Super Admin)
  - _Requirements: 5_

- [x] 3. Update User model with status and approval methods
  - Add status, approved_by, approved_at, rejection_reason to $fillable
  - Add isApproved(), isPending(), isRejected() status check methods
  - Add hasPermission($slug) method to check if user has a specific permission
  - Add getAllPermissions() method to retrieve all user permissions through roles
  - _Requirements: 1, 4, 6_

- [x] 4. Update Role model with permission relationships
  - Add permissions() belongsToMany relationship to Permission model
  - Create Permission model with fillable properties: name, slug, description, category
  - Add getRolePermissions() method to retrieve all permissions for a role
  - _Requirements: 5_

- [x] 5. Update registration controller to create pending users
  - Modify RegisterController::store() to set status = 'pending' for all new users
  - Remove automatic role assignment from assignRoleByDesignation()
  - Do not attach any roles to users during registration
  - _Requirements: 1_

- [x] 6. Create UserApprovalController for Super Admin approval workflow
  - Create pending() method to list all pending users with filtering and search by name/email/organization
  - Create show($user) method to display full user profile with all registration details
  - Create approve(Request $request, $userId) method to approve user, assign role, and grant permissions
  - Create reject(Request $request, $userId) method to reject user with rejection reason
  - Create assignRole(Request $request, $userId) method to attach role to user and update status to approved
  - Create index() method to show all users (approved, pending, rejected) with filtering
  - _Requirements: 2, 3, 4_

- [x] 7. Create approval views for Super Admin
  - Create resources/views/super-admin/pending-users/index.blade.php with list of pending users, search/filter options, and action buttons
  - Create resources/views/super-admin/pending-users/show.blade.php with full user details, role assignment form, and approve/reject buttons
  - Create resources/views/super-admin/pending-users/partials/user-card.blade.php for reusable user profile card component
  - _Requirements: 2, 3, 4_

- [x] 8. Create CheckApprovalStatus middleware
  - Create app/Http/Middleware/CheckApprovalStatus.php
  - Check if user is approved before accessing protected routes
  - Redirect pending/rejected users to appropriate pending screen with message
  - Allow Super Admin to bypass (they approve users)
  - _Requirements: 1, 6_

- [x] 9. Create CheckPermission middleware
  - Create app/Http/Middleware/CheckPermission.php
  - Accept permission slug as parameter
  - Check if user has required permission using User::hasPermission()
  - Redirect unauthorized users with 403 error
  - _Requirements: 6_

- [x] 10. Update authentication flow to check user approval status
  - Modify LoginController or authentication middleware to check if user is approved
  - Redirect pending users to pending screen instead of dashboard
  - Log login attempts from pending/rejected users for audit
  - _Requirements: 1, 6_

- [x] 11. Implement role-based dashboard routing
  - Update User::getDashboardRoute() to return correct route for each role
  - Map roles to blade templates: supervisor→supervisor.blade.php, hsc_conditions→hsc_conditions.blade.php, district_hr→district_hr.blade.php, dmo→dmo.blade.php, pmd→pmd.blade.php, mohhc_hr→mohcc_hr.blade.php, labour_social→labour_social.blade.php, super_admin→super_admin.blade.php
  - Create or update DashboardController to route users to their role-specific view
  - _Requirements: 6_

- [x] 12. Create district_hr.blade.php dashboard template
  - Create resources/views/dashboards/district_hr.blade.php for District HR role
  - Display role name, organization, and accessible features based on permissions
  - _Requirements: 6_

- [x] 13. Checkpoint - Database and authentication setup complete
  - Ensure all migrations run without errors
  - Ensure user can register and status is set to pending
  - Ensure pending user cannot log in (redirected to pending screen)
  - Verify auth middleware properly checks approval status
  - Ask the user if there are any questions

- [x] 14. Register CheckApprovalStatus middleware in HTTP kernel
  - Add CheckApprovalStatus and CheckPermission middleware to app/Http/Kernel.php in protected routes group
  - Apply CheckApprovalStatus to all protected routes except Super Admin approval panel
  - _Requirements: 1, 6_

- [x] 15. Create approval notification system
  - Send notification when user is approved with role assignment details
  - Send notification when user is rejected with rejection reason
  - Include login link in approval notification
  - _Requirements: 4_

- [x] 16. Create audit logging for role assignments
  - Log all role assignments with timestamp, assigned_by, assigned_to, and role_id
  - Log all rejections with reason and timestamp
  - Make logs accessible to Super Admin in audit view
  - _Requirements: 4_

- [x] 17. Update existing dashboards to respect permissions
  - Apply CheckPermission middleware to all dashboard routes
  - Add permission checks to view permissions (@can directives in blade templates)
  - Hide features user doesn't have permission for
  - _Requirements: 6_

- [x] 18. Create pending user view/screen
  - Create resources/views/pending-user.blade.php showing pending status with message
  - Display expected approval timeline
  - Provide link to logout
  - _Requirements: 1_

- [x] 19. Checkpoint - Approval workflow complete
  - Ensure Super Admin can view pending users
  - Ensure Super Admin can approve user and assign role
  - Ensure approved user can log in and see correct dashboard
  - Ensure permissions are correctly applied
  - Verify pending user cannot access protected routes
  - Ask the user if there are any questions

- [x] 20. Create organization dashboard pivot logic
  - Create admin panel for viewing approval statistics (pending count, approved count, rejected count)
  - Create view for Super Admin to see approval metrics
  - _Requirements: 2_

- [x] 21. Add rejection retry mechanism
  - Allow rejected users to re-apply after certain period (e.g., 7 days)
  - Send rejection notification with instructions to reapply
  - _Requirements: 1_

- [x] 22. Final checkpoint - Complete workflow tested end-to-end
  - Test complete flow: registration → pending status → Super Admin approval → role assignment → user login → dashboard access
  - Test permission enforcement on all protected routes
  - Test rejection and pending user access restrictions
  - Verify audit logs contain all role assignments and rejections
  - Ask the user if there are any questions

## Notes

- Tasks marked with `*` are optional and can be implemented in phase 2
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation of the workflow
- Permission system provides fine-grained access control
- All role assignments are audited for compliance and security
