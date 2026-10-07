# IT Support Ticket Messaging System - Design Document

## Overview

The IT Support Ticket Messaging System provides a unified support communication platform that enables users across all roles to create tickets, communicate with super admins for resolution, and track ticket progress. The system integrates into existing dashboards, uses Laravel's event-listener architecture for notifications (mirroring the document upload notification pattern), and provides a conversation thread interface for transparent communication.

The design follows the existing incident management pattern in the system, adapting notification dispatch logic to support ticket workflows and reusing established database relationships and role-based access control.

## Architecture

### High-Level Flow

```
User (Any Role) → Creates Ticket → Event: TicketCreated Dispatched
                                      ↓
                                   Listener: RouteTicketToSuperAdmin
                                      ↓
                                   Assigns to Super Admin (Load-Balanced)
                                      ↓
                                   Super Admin Dashboard Shows Ticket
                                      ↓
                                   Super Admin Replies → Event: TicketReplyAdded
                                      ↓
                                   Listener: NotifyOriginatorOfReply
                                      ↓
                                   Originator Receives Notification
                                      ↓
                                   Originator Views Conversation Thread
```

### Event-Driven Architecture

The system uses Laravel events and listeners to decouple operations:

1. **TicketCreated Event**: Fired when a ticket is created
   - Triggers routing to super admin
   - Begins load-balancing assignment

2. **TicketReplyAdded Event**: Fired when super admin replies
   - Triggers originator notification
   - Updates ticket's last_updated timestamp

3. **TicketStatusChanged Event**: Fired when status is modified
   - Triggers originator notification with status information
   - Logs status transition for audit

### Integration Points

- **Existing SMS Service**: Reuses `SMSService` for optional SMS notifications
- **Role-Based Access Control**: Leverages existing `User::role()` and permission checking
- **Dashboard Navigation**: Integrates ticket quick-links into all dashboard layouts
- **Notification Framework**: Uses Laravel's notification system (email, database channels)

## Components and Interfaces

### 1. Database Components

#### Support_Tickets Table
```sql
CREATE TABLE support_tickets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    ticket_number VARCHAR(50) UNIQUE,
    originator_user_id BIGINT FOREIGN KEY references users(id),
    routed_to_super_admin_id BIGINT FOREIGN KEY references users(id),
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    description LONGTEXT NOT NULL,
    status ENUM('OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED') DEFAULT 'OPEN',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by_role VARCHAR(50),
    last_activity_at TIMESTAMP,
    INDEX idx_originator (originator_user_id),
    INDEX idx_super_admin (routed_to_super_admin_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
)
```

#### Ticket_Replies Table
```sql
CREATE TABLE ticket_replies (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    support_ticket_id BIGINT FOREIGN KEY references support_tickets(id),
    sender_user_id BIGINT FOREIGN KEY references users(id),
    message LONGTEXT NOT NULL,
    sender_role VARCHAR(50),
    status ENUM('PENDING', 'SENT') DEFAULT 'SENT',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ticket (support_ticket_id),
    INDEX idx_sender (sender_user_id),
    INDEX idx_created_at (created_at)
)
```

#### Ticket_Notifications Table
```sql
CREATE TABLE ticket_notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    support_ticket_id BIGINT FOREIGN KEY references support_tickets(id),
    recipient_user_id BIGINT FOREIGN KEY references users(id),
    notification_type VARCHAR(50),
    notification_channel VARCHAR(50),
    content LONGTEXT,
    status ENUM('PENDING', 'SENT', 'FAILED') DEFAULT 'PENDING',
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ticket (support_ticket_id),
    INDEX idx_recipient (recipient_user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
)
```

### 2. Controller Layer

#### TicketController
- **store()**: Create new support ticket
- **show()**: Display ticket details with conversation thread
- **index()**: List tickets (role-aware filtering)
- **update()**: Update ticket status
- **storeReply()**: Add reply to ticket

#### TicketManagementController (Super Admin)
- **dashboard()**: Display all tickets for super admin
- **getPendingCount()**: Get count of pending tickets
- **assignTicket()**: Manual assignment override (if needed)

### 3. Event and Listener Classes

#### Events
- **TicketCreated**: Dispatched when ticket is created
- **TicketReplyAdded**: Dispatched when super admin replies
- **TicketStatusChanged**: Dispatched when status changes

#### Listeners
- **RouteTicketToSuperAdmin**: Assigns ticket to appropriate super admin using load-balancing
- **NotifyOriginatorOfReply**: Sends notification to originator when reply added
- **NotifyOriginatorOfStatusChange**: Sends notification when status changes

### 4. Notification Classes

#### TicketCreatedNotification
- Email to super admin: New ticket assigned
- Database notification for dashboard indicator

#### ReplyReceivedNotification
- Email to originator: New reply on ticket
- Optional SMS: Quick notification
- Database notification for in-app alerts

#### StatusChangedNotification
- Email to originator: Status change information
- Database notification with status details

### 5. Service Classes

#### TicketService
```
createTicket(user, title, category, description)
  → Validates input
  → Creates Support_Ticket record
  → Dispatches TicketCreated event
  → Returns ticket with ID

replyToTicket(superAdminUser, ticketId, message)
  → Validates message not empty
  → Creates Ticket_Reply record
  → Dispatches TicketReplyAdded event
  → Returns reply confirmation

updateTicketStatus(superAdminUser, ticketId, newStatus)
  → Validates status value
  → Updates Support_Ticket status
  → Dispatches TicketStatusChanged event
  → Returns updated ticket

getOriginatorTickets(user, filters)
  → Returns tickets where originator_user_id = user.id
  → Applies filters (status, category, date range)
  → Returns paginated results

getSuperAdminTickets(superAdminUser, filters)
  → Returns tickets where routed_to_super_admin_id = superAdminUser.id
  → Applies filters
  → Returns paginated results
```

#### TicketAssignmentService
```
loadBalanceAssignToSuperAdmin(ticket)
  → Gets all super_admin role users
  → Counts pending tickets per super admin
  → Selects super admin with fewest cases
  → Updates ticket.routed_to_super_admin_id
  → Returns assigned super admin

getAssignmentMetrics()
  → Returns caseload per super admin
  → Returns pending vs resolved stats
  → Returns average response time
```

### 6. API Endpoints

#### Ticket Operations
```
POST /api/tickets
  Body: { title, category, description }
  Returns: { ticket_id, ticket_number, status, created_at }

GET /api/tickets/{id}
  Returns: { ticket_data, all_replies, originator_info, current_user_role }

GET /api/tickets/my
  Returns: [{ tickets for authenticated user }]

GET /api/tickets (Super Admin only)
  Query: ?status=OPEN&category=bug_report&page=1&per_page=20
  Returns: Paginated list of all tickets

PUT /api/tickets/{id}/status
  Body: { status: 'IN_PROGRESS|RESOLVED|CLOSED' }
  Returns: { updated_ticket, status_changed_at }

POST /api/tickets/{id}/replies
  Body: { message }
  Returns: { reply_id, created_at, sender_role }

GET /api/tickets/{id}/replies
  Returns: [{ all_replies for ticket }]
```

### 7. UI Components

#### Dashboard Integration
- **Ticket Quick Link**: Present in all dashboards (HR, Supervisor, PMD, etc.)
- **Notification Badge**: Shows count of pending tickets or unread replies
- **Quick Access Modal**: Optional inline ticket creation

#### Ticket List View
- Sortable columns: ID, Title, Category, Status, Created Date, Last Activity
- Filterable by: Status, Category, Date Range, Originator Name
- Searchable by: Ticket ID, Title Keywords, Originator Name
- Pagination: 20 items per page default

#### Ticket Details View
- Original ticket: Title, Category, Description, Originator Info, Status
- Conversation thread: Messages in chronological order
- Reply composer: Text area, send button (super admin only)
- Status updater: Dropdown to change status (super admin only)

#### Notification Display
- In-app badge: Red dot or number indicator
- Toast notification: Brief alert when new reply arrives
- Dashboard widget: List of recent activity

## Data Models

### SupportTicket Model
```php
class SupportTicket extends Model {
    protected $fillable = [
        'ticket_number', 'originator_user_id', 'routed_to_super_admin_id',
        'title', 'category', 'description', 'status', 'created_by_role'
    ];

    public function originator() { return $this->belongsTo(User::class, 'originator_user_id'); }
    public function assignedSuperAdmin() { return $this->belongsTo(User::class, 'routed_to_super_admin_id'); }
    public function replies() { return $this->hasMany(TicketReply::class, 'support_ticket_id'); }
    public function notifications() { return $this->hasMany(TicketNotification::class, 'support_ticket_id'); }
}
```

### TicketReply Model
```php
class TicketReply extends Model {
    protected $fillable = ['support_ticket_id', 'sender_user_id', 'message', 'sender_role', 'status'];

    public function ticket() { return $this->belongsTo(SupportTicket::class, 'support_ticket_id'); }
    public function sender() { return $this->belongsTo(User::class, 'sender_user_id'); }
}
```

### TicketNotification Model
```php
class TicketNotification extends Model {
    protected $fillable = ['support_ticket_id', 'recipient_user_id', 'notification_type', 'notification_channel', 'content', 'status', 'sent_at'];

    public function ticket() { return $this->belongsTo(SupportTicket::class, 'support_ticket_id'); }
    public function recipient() { return $this->belongsTo(User::class, 'recipient_user_id'); }
}
```

## Error Handling

### Validation Errors
- Title: Required, min 3 chars, max 255 chars
- Category: Required, must be valid category
- Description: Required, min 10 chars, max 5000 chars
- Message: Required for replies, min 2 chars, max 5000 chars

### Access Control Errors
- Non-super-admin users cannot view others' tickets or reply
- Originator can only view their own tickets
- Super admin can view all tickets
- Status updates restricted to super admin only

### Database Errors
- Foreign key violations: Return appropriate HTTP 404 or 422
- Duplicate ticket numbers: Should never occur with UUID/auto-increment
- Connection issues: Log and return HTTP 500 with user-friendly message

### Business Logic Errors
- Cannot reply to CLOSED tickets (optional: allow read-only viewing)
- Cannot create ticket without authenticated user
- Cannot assign ticket without available super admin (queue until available)

## Correctness Properties

A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.

### Property 1: Ticket Creation Invariant
*For any* authenticated user creating a valid support ticket, after creation the ticket's originator_user_id should equal the authenticated user's ID and the initial status should be OPEN.

**Validates: Requirements 1.3, 1.4**

### Property 2: Reply Thread Ordering
*For any* support ticket with multiple replies, when displayed in chronological order, the created_at timestamp of each reply should be greater than or equal to the previous reply's created_at timestamp.

**Validates: Requirements 4.3, 6.2, 8.1**

### Property 3: Load-Balanced Assignment
*For any* set of support tickets being assigned to multiple super admin users, the maximum difference in ticket count between any two super admins should not exceed 1 after all assignments are complete.

**Validates: Requirement 2.4**

### Property 4: Status Transition Validity
*For any* status change operation on a support ticket, the new status must be one of the valid enum values (OPEN, IN_PROGRESS, RESOLVED, CLOSED).

**Validates: Requirement 9.2**

### Property 5: Originator Notification Recipience
*For any* support ticket with a new reply or status change event, a corresponding notification record should be created with the originator's user ID as the recipient before the originator is notified.

**Validates: Requirements 11.1, 11.2, 11.3**

### Property 6: Unique Ticket Numbering
*For any* support ticket created in the system, the ticket_number field should be unique across all tickets in the support_tickets table.

**Validates: Requirement 12.1**

### Property 7: Conversation Thread Completeness
*For any* authenticated user accessing a support ticket they created or are assigned to manage, the returned ticket data should include all ticket_reply records linked to that ticket via foreign key relationship.

**Validates: Requirement 8.1**

### Property 8: Timestamp Consistency
*For any* support ticket or ticket reply record, the updated_at timestamp should be greater than or equal to the created_at timestamp.

**Validates: Requirement 12.3**

## Testing Strategy

### Unit Testing

Unit tests verify specific examples, edge cases, and error conditions:

- **Ticket Creation Tests**:
  - Valid ticket creation with all required fields
  - Title validation (too short, too long, empty)
  - Category validation (invalid category selection)
  - Description validation (minimum length enforcement)
  - Unauthorized user creation attempt

- **Reply Management Tests**:
  - Valid reply creation and persistence
  - Empty message rejection
  - Non-super-admin cannot reply
  - Reply ordering in database
  - Reply timestamp accuracy

- **Status Management Tests**:
  - Valid status transitions
  - Invalid status values rejected
  - Non-super-admin cannot change status
  - Status update timestamp recording

- **Access Control Tests**:
  - Originator can view own tickets
  - Originator cannot view other users' tickets
  - Super admin can view assigned tickets
  - Non-assigned super admin cannot view tickets

- **Notification Tests**:
  - Notification record creation on reply
  - Notification sent status tracking
  - Email notification content includes ticket details
  - SMS notification format compliance

- **Search and Filter Tests**:
  - Filter by status returns only matching tickets
  - Filter by category returns only matching tickets
  - Search by ticket ID exact match
  - Search by title keywords
  - Combined filters work correctly

### Property-Based Testing

Property-based tests verify universal properties across randomized inputs:

- **Property 1: Ticket Creation Invariant**
  - Generate random user, title, category, description
  - Create ticket
  - Assert originator_user_id equals user ID
  - Assert status equals 'OPEN'
  - Run 100+ iterations with random data

- **Property 2: Reply Thread Ordering**
  - Generate random ticket
  - Add 5-20 random replies
  - Fetch all replies
  - Assert timestamps are in non-decreasing order
  - Run 100+ iterations

- **Property 3: Load-Balanced Assignment**
  - Generate 3-10 random super admin users
  - Create 10-100 random tickets
  - Run assignment algorithm
  - Calculate caseload per admin
  - Assert max_caseload - min_caseload ≤ 1
  - Run 100+ iterations

- **Property 4: Status Transition Validity**
  - Generate random ticket
  - Generate random status value from enum
  - Update ticket status
  - Assert status update succeeds
  - Test with invalid enum values should fail gracefully
  - Run 100+ iterations

- **Property 5: Originator Notification Recipience**
  - Generate random ticket with originator
  - Generate random super admin
  - Add random reply as super admin
  - Query notification_tickets table
  - Assert recipient_user_id equals originator ID
  - Assert notification_type matches event type
  - Run 100+ iterations

- **Property 6: Unique Ticket Numbering**
  - Create 50-500 random tickets
  - Extract all ticket_number values
  - Assert no duplicates exist in list
  - Run 100+ iterations

- **Property 7: Conversation Thread Completeness**
  - Generate random ticket with originator
  - Add 0-10 random replies
  - Retrieve ticket with replies via API
  - Assert reply count matches database count
  - Assert all reply IDs present in returned data
  - Run 100+ iterations

- **Property 8: Timestamp Consistency**
  - Generate random ticket
  - Assert created_at <= updated_at
  - Modify ticket multiple times
  - Each modification: assert created_at stays same, updated_at increases
  - Run 100+ iterations

