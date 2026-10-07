# Implementation Plan: IT Support Ticket Messaging System

## Overview

The IT Support Ticket Messaging System will be implemented following a phased approach starting with database schema and core models, then building out the ticket management logic, followed by the notification system, and finally integrating ticket quick-access across all dashboards. Each phase builds incrementally with integrated testing to ensure correctness at each step.

## Tasks

- [ ] 1. Create database migrations and models
  - [x] 1.1 Create migration for support_tickets table
    - Define ticket_number, originator_user_id, routed_to_super_admin_id, title, category, description, status fields
    - Add indexes on originator_user_id, routed_to_super_admin_id, status, created_at for query performance
    - _Requirements: 1.3, 1.4, 12.1_
  
  - [x] 1.2 Create migration for ticket_replies table
    - Define support_ticket_id, sender_user_id, message, sender_role, status fields
    - Add foreign key constraint to support_tickets table
    - Add indexes on support_ticket_id, sender_user_id, created_at
    - _Requirements: 3.1, 3.2, 12.1_
  
  - [x] 1.3 Create migration for ticket_notifications table
    - Define support_ticket_id, recipient_user_id, notification_type, notification_channel, content, status, sent_at fields
    - Add foreign key constraints to support_tickets and users tables
    - Add indexes on support_ticket_id, recipient_user_id, status, created_at
    - _Requirements: 11.3, 12.1_
  
  - [x] 1.4 Create SupportTicket Eloquent model with relationships
    - Define belongsTo relationships to User (originator, assignedSuperAdmin)
    - Define hasMany relationships to TicketReply and TicketNotification
    - Add protected $fillable array and $casts for type safety
    - _Requirements: 12.1, 12.2_
  
  - [x] 1.5 Create TicketReply Eloquent model with relationships
    - Define belongsTo relationships to SupportTicket and User (sender)
    - Add $fillable and $casts attributes
    - _Requirements: 12.1, 12.2_
  
  - [x] 1.6 Create TicketNotification Eloquent model with relationships
    - Define belongsTo relationships to SupportTicket and User (recipient)
    - Add $fillable and $casts attributes
    - _Requirements: 12.1, 12.2_
  
  - [ ]* 1.7 Write property test for ticket creation invariant
    - **Property 1: Ticket Creation Invariant**
    - **Validates: Requirements 1.3, 1.4**
  
  - [ ]* 1.8 Write unit tests for models
    - Test model relationships (originator, replies, notifications)
    - Test mass assignment protection
    - _Requirements: 12.1_

- [x] 2. Implement ticket creation and validation
  - [x] 2.1 Create TicketService class with createTicket method
    - Accept user, title, category, description parameters
    - Generate unique ticket_number using prefix + ID pattern (e.g., TICKET-2025-0001)
    - Validate title (min 3, max 255), category (valid enum), description (min 10, max 5000)
    - Create SupportTicket record with OPEN status and originator_user_id
    - Return created ticket instance
    - _Requirements: 1.2, 1.3, 1.4_
  
  - [x] 2.2 Create TicketController with store method
    - Implement POST handler that receives form data
    - Call TicketService::createTicket with validated input
    - Return JSON response with success status and ticket data
    - _Requirements: 1.2, 1.3_
  
  - [x] 2.3 Create TicketRequest form request class
    - Define validation rules for title, category, description
    - Implement authorize() to verify user is authenticated
    - Define messages for validation errors
    - _Requirements: 1.2, 1.5_
  
  - [x] 2.4 Create ticket creation API endpoint route
    - POST /api/tickets mapped to TicketController@store
    - Apply api middleware and auth:sanctum middleware
    - _Requirements: 13.1_
  
  - [ ]* 2.5 Write property test for ticket creation invariant
    - **Property 1: Ticket Creation Invariant**
    - **Validates: Requirements 1.3, 1.4**
  
  - [ ]* 2.6 Write unit tests for ticket creation
    - Test valid ticket creation with all required fields
    - Test title validation (too short, too long, empty)
    - Test category validation (invalid values)
    - Test description validation (minimum length)
    - Test unauthorized user rejection
    - _Requirements: 1.2, 1.3, 1.5_

- [x] 3. Implement ticket routing to super admin
  - [x] 3.1 Create TicketCreated event class
    - Add properties for ticket, originator user, ticket_number
    - Make event shouldQueue to support async processing
    - _Requirements: 2.2_
  
  - [x] 3.2 Create RouteTicketToSuperAdmin listener class
    - Implement handle method that receives TicketCreated event
    - Call TicketAssignmentService::loadBalanceAssignToSuperAdmin
    - Update ticket.routed_to_super_admin_id with assigned super admin ID
    - Save ticket and return
    - _Requirements: 2.1, 2.2, 2.4_
  
  - [x] 3.3 Create TicketAssignmentService with load-balancing logic
    - Implement loadBalanceAssignToSuperAdmin method
    - Query all users with super_admin role
    - Count pending tickets per super admin (status != RESOLVED/CLOSED)
    - Select super admin with minimum pending count
    - Handle edge case where all super admins are equally loaded (use first)
    - Return assigned super admin user instance
    - _Requirements: 2.4_
  
  - [x] 3.4 Register RouteTicketToSuperAdmin listener in EventServiceProvider
    - Map TicketCreated event to RouteTicketToSuperAdmin listener
    - Enable listener queuing for async processing
    - _Requirements: 2.2_
  
  - [x] 3.5 Dispatch TicketCreated event from TicketService::createTicket
    - After ticket record is created and saved, dispatch event
    - Pass ticket instance to event
    - _Requirements: 2.1, 2.2_
  
  - [ ]* 3.6 Write property test for load-balanced assignment
    - **Property 3: Load-Balanced Assignment**
    - **Validates: Requirement 2.4**
  
  - [ ]* 3.7 Write unit tests for ticket routing
    - Test TicketCreated event dispatch
    - Test RouteTicketToSuperAdmin listener assigns ticket
    - Test load-balancing selects super admin with fewer tickets
    - _Requirements: 2.4_

- [x] 4. Implement super admin ticket dashboard
  - [x] 4.1 Create SuperAdminTicketController with dashboard methods
    - Implement index method to list all routed tickets
    - Accept query parameters for filtering (status, category, date range) and pagination
    - Filter tickets by routed_to_super_admin_id if role is not global admin
    - Return paginated list with ticket summary data
    - _Requirements: 3.1, 3.2_
  
  - [x] 4.2 Create ticket list dashboard view
    - Display tickets in table format with columns: ID, Originator, Title, Category, Status, Created Date, Last Updated
    - Add sortable column headers (click to sort by created_at, status, etc.)
    - Add filter controls for status, category, date range
    - Add search box for ticket ID/title/originator name
    - Add pagination controls (20 items per page)
    - Add click handler to view ticket details
    - _Requirements: 3.1, 3.2_
  
  - [x] 4.3 Create GET /api/tickets endpoint (super admin)
    - Accept query params: status, category, created_from, created_to, page, per_page
    - Return paginated list of tickets assigned to authenticated super admin
    - Include originator info (name, email, role) for each ticket
    - _Requirements: 13.2_
  
  - [x] 4.4 Integrate ticket dashboard link in super admin sidebar
    - Add "Support Tickets" navigation item with icon
    - Link to super admin ticket dashboard view
    - Apply same styling as existing incident management links
    - _Requirements: 3.1_
  
  - [ ]* 4.5 Write unit tests for super admin dashboard
    - Test index method returns only assigned tickets
    - Test filtering by status
    - Test filtering by category
    - Test pagination works correctly
    - _Requirements: 3.1, 3.2_

- [x] 5. Implement ticket details view
  - [x] 5.1 Create ticket details view template
    - Display originator information section (name, email, role, department)
    - Display ticket header (ID, title, category, status, created/updated timestamps)
    - Display original description in readable format
    - Display conversation thread section below
    - _Requirements: 4.1, 4.2_
  
  - [x] 5.2 Create TicketController::show method
    - Accept ticket ID parameter
    - Retrieve ticket with eager-loaded replies and originator user
    - Verify current user is either originator or assigned super admin
    - Return view with ticket and replies data
    - _Requirements: 4.1, 4.2_
  
  - [x] 5.3 Create GET /api/tickets/{id} endpoint
    - Retrieve ticket by ID with all replies
    - Include originator info and assigned super admin info
    - Include current user's role for UI visibility control
    - Return 404 if ticket not found
    - Return 403 if user not authorized to view
    - _Requirements: 13.2_
  
  - [x] 5.4 Create conversation thread display component
    - Render original ticket description as first message
    - Render all ticket replies in chronological order (oldest first)
    - For each reply: show sender name, role, message content, timestamp
    - Distinguish between originator messages and super admin responses with styling/badges
    - _Requirements: 4.3, 4.4, 8.1, 8.3_
  
  - [ ]* 5.5 Write property test for reply thread ordering
    - **Property 2: Reply Thread Ordering**
    - **Validates: Requirements 4.3, 6.2, 8.1**
  
  - [ ]* 5.6 Write unit tests for ticket details view
    - Test authorized user can view ticket
    - Test unauthorized user gets 403 error
    - Test replies display in chronological order
    - _Requirements: 4.1, 4.2, 4.3_

- [x] 6. Implement super admin reply functionality
  - [x] 6.1 Create reply composition UI component
    - Display text area for message input
    - Display character counter (current/max characters)
    - Display Send button (disabled until text entered)
    - Apply same styling as ticket creation form
    - _Requirements: 5.1, 5.2_
  
  - [x] 6.2 Implement TicketService::replyToTicket method
    - Accept super_admin_user, ticket_id, message parameters
    - Validate message is not empty and within character limit
    - Verify sender is super admin role
    - Verify ticket is in replyable status (not CLOSED)
    - Create TicketReply record with sender_user_id and message
    - Update parent ticket's updated_at timestamp
    - Dispatch TicketReplyAdded event
    - Return created reply instance
    - _Requirements: 5.2, 5.3, 5.4_
  
  - [x] 6.3 Create POST /api/tickets/{id}/replies endpoint
    - Accept message in request body
    - Verify current user is super admin
    - Call TicketService::replyToTicket with authenticated user
    - Return success response with reply data
    - Return 422 if validation fails
    - _Requirements: 13.4_
  
  - [x] 6.4 Create TicketReplyRequest form request validation class
    - Define rules for message field (required, min 2, max 5000)
    - Implement authorize() to check super admin role
    - _Requirements: 5.2_
  
  - [x] 6.5 Add reply submission handler in ticket details view
    - Implement form submission via AJAX to POST /api/tickets/{id}/replies
    - On success: append new reply to conversation thread, clear text area, disable button
    - On error: display validation errors inline
    - Show loading state during submission
    - _Requirements: 5.2_
  
  - [ ]* 6.6 Write property test for ticket creation invariant
    - **Property 1: Ticket Creation Invariant**
    - **Validates: Requirements 1.3, 1.4**
  
  - [ ]* 6.7 Write unit tests for reply creation
    - Test valid reply creation and persistence
    - Test empty message rejection
    - Test non-super-admin cannot reply
    - Test reply is linked to correct ticket
    - _Requirements: 5.2, 5.3, 5.4_

- [x] 7. Implement ticket status management
  - [x] 7.1 Create status update UI in ticket details
    - Display current status badge with color coding (OPEN=blue, IN_PROGRESS=yellow, RESOLVED=green, CLOSED=gray)
    - Display status dropdown (super admin only) with available transitions
    - Add button to save new status
    - _Requirements: 9.1_
  
  - [x] 7.2 Implement TicketService::updateTicketStatus method
    - Accept super_admin_user, ticket_id, new_status parameters
    - Validate new_status is valid enum value (OPEN, IN_PROGRESS, RESOLVED, CLOSED)
    - Verify user is super admin
    - Update ticket.status field
    - Update ticket.updated_at timestamp
    - Dispatch TicketStatusChanged event
    - Return updated ticket
    - _Requirements: 9.2_
  
  - [x] 7.3 Create PUT /api/tickets/{id}/status endpoint
    - Accept status value in request body
    - Call TicketService::updateTicketStatus
    - Return success response with updated ticket data
    - Return 422 if validation fails
    - _Requirements: 13.5_
  
  - [x] 7.4 Create TicketStatusRequest validation class
    - Define rule for status field (required, in:OPEN,IN_PROGRESS,RESOLVED,CLOSED)
    - Implement authorize() to check super admin role
    - _Requirements: 9.2_
  
  - [ ]* 7.5 Write property test for status transition validity
    - **Property 4: Status Transition Validity**
    - **Validates: Requirement 9.2**
  
  - [ ]* 7.6 Write unit tests for status management
    - Test valid status transitions
    - Test invalid status values rejected
    - Test non-super-admin cannot change status
    - _Requirements: 9.1, 9.2_

- [x] 8. Implement notification system for originators
  - [x] 8.1 Create TicketReplyAdded event class
    - Add properties for ticket, reply, originator_user
    - Make event shouldQueue
    - _Requirements: 11.1_
  
  - [x] 8.2 Create TicketStatusChanged event class
    - Add properties for ticket, old_status, new_status, originator_user
    - Make event shouldQueue
    - _Requirements: 11.2_
  
  - [x] 8.3 Create NotifyOriginatorOfReply listener class
    - Implement handle method to receive TicketReplyAdded event
    - Retrieve ticket originator user
    - Create TicketNotification record with notification_type='REPLY', status='PENDING'
    - Send notification via channels (email minimum, SMS optional)
    - Update notification status to 'SENT'
    - _Requirements: 11.1, 11.3_
  
  - [x] 8.4 Create NotifyOriginatorOfStatusChange listener class
    - Implement handle method to receive TicketStatusChanged event
    - Retrieve ticket originator user
    - Create TicketNotification record with notification_type='STATUS_CHANGE', status='PENDING'
    - Send notification with status information
    - Update notification status to 'SENT'
    - _Requirements: 11.2, 11.3_
  
  - [x] 8.5 Create ReplyReceivedNotification notification class
    - Implement via() to specify channels (email, database)
    - Implement toEmail() to format email with ticket details, reply content, action link
    - Implement toDatabase() to store in-app notification payload
    - _Requirements: 11.1, 11.4_
  
  - [x] 8.6 Create StatusChangedNotification notification class
    - Implement via() to specify channels (email, database)
    - Implement toEmail() to format email with old/new status information
    - Implement toDatabase() to store status change details
    - _Requirements: 11.2, 11.4_
  
  - [x] 8.7 Register notification listeners in EventServiceProvider
    - Map TicketReplyAdded to NotifyOriginatorOfReply listener
    - Map TicketStatusChanged to NotifyOriginatorOfStatusChange listener
    - _Requirements: 11.1, 11.2_
  
  - [x] 8.8 Dispatch events from service methods
    - Dispatch TicketReplyAdded after TicketService::replyToTicket success
    - Dispatch TicketStatusChanged after TicketService::updateTicketStatus success
    - _Requirements: 11.1, 11.2_
  
  - [ ]* 8.9 Write property test for originator notification recipience
    - **Property 5: Originator Notification Recipience**
    - **Validates: Requirements 11.1, 11.2, 11.3**
  
  - [ ]* 8.10 Write unit tests for notification system
    - Test notification record is created on reply
    - Test originator receives email notification
    - Test notification contains correct ticket details
    - Test status change notifications are created and sent
    - _Requirements: 11.1, 11.2, 11.3_

- [x] 9. Implement originator ticket viewing
  - [x] 9.1 Create TicketController::myTickets method
    - Retrieve all tickets where originator_user_id equals authenticated user ID
    - Apply sorting: OPEN/IN_PROGRESS first, then RESOLVED/CLOSED
    - Apply pagination (20 per page)
    - Return view with originator's tickets
    - _Requirements: 7.1, 7.2, 7.3_
  
  - [x] 9.2 Create originator ticket list view
    - Display user's tickets in table format
    - Show: Ticket ID, Title, Category, Status, Created Date, Last Updated
    - Color-code status badges
    - Add filter controls (status, category)
    - Add search by ticket ID or title
    - Make rows clickable to view details
    - _Requirements: 7.2, 7.3_
  
  - [x] 9.3 Create GET /api/tickets/my endpoint
    - Return all tickets created by authenticated user
    - Accept query params: status, category, page, per_page
    - Return paginated list with ticket data
    - _Requirements: 13.6_
  
  - [x] 9.4 Integrate ticket list link in all user dashboards
    - Add "My Support Tickets" quick-access link in navigation
    - Link to originator ticket list view
    - Use consistent styling with existing dashboard links
    - _Requirements: 10.1, 10.2_
  
  - [ ]* 9.5 Write unit tests for originator ticket viewing
    - Test originator can view own tickets
    - Test originator cannot view other users' tickets
    - Test filtering and sorting works
    - _Requirements: 7.1, 7.2, 7.3_

- [x] 10. Implement dashboard integration and quick access
  - [x] 10.1 Create ticket quick-link component
    - Render "Create Support Ticket" button
    - Render "My Tickets" link
    - Render notification badge if pending tickets/unread replies
    - Apply inline styling for dashboard integration
    - _Requirements: 10.1, 10.2, 10.3_
  
  - [x] 10.2 Create ticket indicator service
    - Implement method to get pending ticket count for user
    - Implement method to check for unread replies
    - Cache results for 5 minutes to avoid excessive queries
    - _Requirements: 10.3, 10.4_
  
  - [x] 10.3 Add ticket component to HR dashboard
    - Insert ticket quick-link component in dashboard sidebar
    - Display notification badge with pending ticket count
    - Integrate styling with existing dashboard design
    - _Requirements: 10.1_
  
  - [x] 10.4 Add ticket component to Supervisor dashboard
    - Repeat integration for supervisor dashboard
    - Ensure consistency with HR dashboard integration
    - _Requirements: 10.1_
  
  - [x] 10.5 Add ticket component to PMD dashboard
    - Repeat integration for PMD dashboard
    - _Requirements: 10.1_
  
  - [x] 10.6 Add ticket component to other dashboards
    - Identify all other dashboard types in system
    - Integrate ticket component consistently
    - _Requirements: 10.1_
  
  - [ ]* 10.7 Write unit tests for dashboard integration
    - Test ticket link appears in dashboard
    - Test notification badge shows correct count
    - _Requirements: 10.1, 10.3_

- [x] 11. Implement ticket search and filtering
  - [x] 11.1 Add ticket search functionality
    - Implement search by ticket ID (exact match)
    - Implement search by title keywords (contains match)
    - Implement search by originator name (contains match)
    - Apply search across all visible tickets (respecting user permissions)
    - _Requirements: 14.1_
  
  - [x] 11.2 Add filter controls to ticket lists
    - Add filter by status (multiple select)
    - Add filter by category (multiple select)
    - Add filter by date range (from/to date pickers)
    - Add filter by originator (for super admin list)
    - Apply filters in real-time as user changes selections
    - _Requirements: 14.2_
  
  - [x] 11.3 Implement API filtering endpoint
    - Update GET /api/tickets to support all filter parameters
    - Update GET /api/tickets/my to support filter parameters
    - Return empty results with clear message if no matches
    - _Requirements: 14.1, 14.2, 14.3_
  
  - [ ]* 11.4 Write unit tests for search and filtering
    - Test search returns matching tickets
    - Test search returns empty for non-matching queries
    - Test multiple filters combined work correctly
    - _Requirements: 14.1, 14.2_

- [x] 12. Checkpoint - Database and core functionality
  - Ensure all database migrations run successfully
  - Ensure all models load without errors
  - Ensure ticket creation flow works end-to-end
  - Ensure routing to super admin completes
  - Ask the user if questions arise about the implementation so far.
  - _Requirements: 1.0, 2.0, 3.0_

- [x] 13. Implement ticket retrieval and conversation threads
  - [x] 13.1 Create comprehensive TicketController::show method
    - Load ticket with all relationships (replies, originator, assignedSuperAdmin)
    - Check authorization (originator or assigned super admin)
    - Format replies in conversation thread format
    - _Requirements: 4.2, 8.1_
  
  - [x] 13.2 Create detailed ticket view template
    - Display ticket metadata (ID, status, category, timestamps)
    - Render conversation thread with chronological ordering
    - Distinguish originator vs super admin messages visually
    - _Requirements: 4.2, 4.3, 4.4, 8.1, 8.2, 8.3_
  
  - [x] 13.3 Create GET /api/tickets/{id}/replies endpoint
    - Return all replies for a ticket in chronological order
    - Include sender information (name, role) for each reply
    - _Requirements: 6.2, 8.1_
  
  - [ ]* 13.4 Write property test for conversation thread completeness
    - **Property 7: Conversation Thread Completeness**
    - **Validates: Requirement 8.1**
  
  - [ ]* 13.5 Write unit tests for conversation threads
    - Test all replies returned in order
    - Test sender information included correctly
    - _Requirements: 6.2, 8.1_

- [x] 14. Implement optional SMS notifications (if enabled)
  - [x] 14.1 Extend NotifyOriginatorOfReply listener
    - Check if SMS_ENABLED in configuration
    - If enabled, send SMS via existing SMSService
    - Handle SMS failure gracefully (log but don't block email)
    - _Requirements: 11.4_
  
  - [x] 14.2 Extend NotifyOriginatorOfStatusChange listener
    - Check SMS configuration
    - Send SMS with status change information if enabled
    - _Requirements: 11.4_
  
  - [x] 14.3 Create SMS message formatting
    - Format brief notification message (max 160 chars recommended)
    - Include ticket ID, key information, and action link
    - _Requirements: 11.4_

- [x] 15. Final checkpoint - All tests passing
  - Ensure all property-based tests pass (100+ iterations each)
  - Ensure all unit tests pass
  - Ensure no test failures or errors
  - Ask the user if questions arise.
  - _Requirements: All_

- [x] 16. Documentation and cleanup
  - [x] 16.1 Add database schema documentation
    - Document support_tickets, ticket_replies, ticket_notifications tables
    - Document relationships and constraints
  
  - [x] 16.2 Add API documentation
    - Document all endpoints with examples
    - Document required authentication and authorization
    - Document response formats
  
  - [x] 16.3 Add inline code documentation
    - Add PHPDoc comments to all public methods
    - Add comments for complex business logic
    - Document event and listener flow

## Notes

- Tasks marked with `*` are optional testing tasks and can be skipped for faster MVP delivery
- Core implementation tasks (without `*`) are required for full functionality
- Each phase includes integrated testing to catch issues early
- Checkpoints ensure system functionality at intermediate stages
- Database queries are optimized with indexes at step 1 to prevent performance issues later
- Property-based tests validate universal correctness across randomized inputs
- Unit tests validate specific examples and edge cases
- Notification system integrates with existing Laravel notification infrastructure
- All dashboards get integrated ticket quick-access for consistent user experience

