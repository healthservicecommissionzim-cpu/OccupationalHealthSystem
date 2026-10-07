# IT Support Ticket Messaging System - Documentation

## Overview

The IT Support Ticket Messaging System provides a comprehensive communication platform enabling all system users to create support tickets, maintain conversation threads with super admins, and receive notifications about ticket progress.

## Database Schema

### support_tickets Table

Stores the main ticket records for all support requests.

```sql
CREATE TABLE support_tickets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    ticket_number VARCHAR(50) UNIQUE,
    originator_user_id BIGINT NOT NULL,
    routed_to_super_admin_id BIGINT,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    description LONGTEXT NOT NULL,
    status ENUM('OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED') DEFAULT 'OPEN',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    created_by_role VARCHAR(50),
    last_activity_at TIMESTAMP,
    FOREIGN KEY (originator_user_id) REFERENCES users(id),
    FOREIGN KEY (routed_to_super_admin_id) REFERENCES users(id),
    INDEX idx_originator (originator_user_id),
    INDEX idx_super_admin (routed_to_super_admin_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

**Key Fields:**
- `ticket_number`: Unique identifier in format TICKET-YYYY-NNNN (e.g., TICKET-2026-0042)
- `originator_user_id`: User who created the ticket
- `routed_to_super_admin_id`: Super admin assigned via load-balancing
- `status`: Current state (OPEN, IN_PROGRESS, RESOLVED, CLOSED)
- `created_by_role`: Role of originating user for audit purposes

### ticket_replies Table

Stores all replies/responses to support tickets.

```sql
CREATE TABLE ticket_replies (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    support_ticket_id BIGINT NOT NULL,
    sender_user_id BIGINT NOT NULL,
    message LONGTEXT NOT NULL,
    sender_role VARCHAR(50),
    status ENUM('PENDING', 'SENT') DEFAULT 'SENT',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (support_ticket_id) REFERENCES support_tickets(id),
    FOREIGN KEY (sender_user_id) REFERENCES users(id),
    INDEX idx_ticket (support_ticket_id),
    INDEX idx_sender (sender_user_id),
    INDEX idx_created_at (created_at)
);
```

**Key Fields:**
- `support_ticket_id`: Links reply to parent ticket
- `sender_user_id`: Super admin sending the reply
- `message`: Reply content (up to 5000 characters)
- `status`: SENT when delivered

### ticket_notifications Table

Tracks all notifications sent to originators.

```sql
CREATE TABLE ticket_notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    support_ticket_id BIGINT NOT NULL,
    recipient_user_id BIGINT NOT NULL,
    notification_type VARCHAR(50),
    notification_channel VARCHAR(50),
    content LONGTEXT,
    status ENUM('PENDING', 'SENT', 'FAILED') DEFAULT 'PENDING',
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    FOREIGN KEY (support_ticket_id) REFERENCES support_tickets(id),
    FOREIGN KEY (recipient_user_id) REFERENCES users(id),
    INDEX idx_ticket (support_ticket_id),
    INDEX idx_recipient (recipient_user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

**Key Fields:**
- `notification_type`: REPLY or STATUS_CHANGE
- `notification_channel`: email (SMS tracked separately via SMSService)
- `status`: PENDING, SENT, or FAILED
- `sent_at`: Timestamp when notification was delivered

## API Endpoints

### Ticket Creation

**POST /api/tickets**

Create a new support ticket.

**Request:**
```json
{
    "title": "System login issue",
    "category": "System Issue",
    "description": "I cannot login to the system. Error message shows invalid credentials even though I entered correct password."
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Support ticket created successfully.",
    "data": {
        "ticket_id": 42,
        "ticket_number": "TICKET-2026-0042",
        "status": "OPEN",
        "created_at": "2026-09-13T10:30:00Z"
    }
}
```

**Validation Rules:**
- title: Required, 3-255 characters
- category: Required, valid category value
- description: Required, 10-5000 characters

---

### Get User's Tickets

**GET /api/tickets/my**

Retrieve authenticated user's support tickets.

**Query Parameters:**
- `status`: Filter by status (OPEN, IN_PROGRESS, RESOLVED, CLOSED) - can be array
- `category`: Filter by category - can be array
- `created_from`: Filter by creation date (YYYY-MM-DD format)
- `created_to`: Filter by creation date (YYYY-MM-DD format)
- `search`: Search by ticket ID or title keywords
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 20)

**Response (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 42,
            "ticket_number": "TICKET-2026-0042",
            "title": "System login issue",
            "category": "System Issue",
            "status": "OPEN",
            "created_at": "2026-09-13T10:30:00Z",
            "updated_at": "2026-09-13T10:30:00Z"
        }
    ],
    "pagination": {
        "total": 15,
        "per_page": 20,
        "current_page": 1,
        "last_page": 1
    }
}
```

---

### Get Ticket Details

**GET /api/tickets/{id}**

Retrieve complete ticket details with conversation thread.

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 42,
        "ticket_number": "TICKET-2026-0042",
        "title": "System login issue",
        "category": "System Issue",
        "description": "I cannot login to the system...",
        "status": "IN_PROGRESS",
        "originator": {
            "id": 5,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "assigned_super_admin": {
            "id": 2,
            "name": "Admin User",
            "email": "admin@example.com"
        },
        "replies": [
            {
                "id": 1,
                "message": "We are looking into this issue...",
                "sender": {
                    "id": 2,
                    "name": "Admin User",
                    "role": "super_admin"
                },
                "created_at": "2026-09-13T11:00:00Z"
            }
        ],
        "created_at": "2026-09-13T10:30:00Z",
        "updated_at": "2026-09-13T11:00:00Z",
        "current_user_role": "hr"
    }
}
```

**Authorization:**
- Originator can view their own tickets
- Assigned super admin can view assigned tickets
- Other super admins can view all tickets
- Returns 403 if unauthorized

---

### Get Ticket Replies

**GET /api/tickets/{id}/replies**

Retrieve all replies for a ticket in chronological order.

**Response (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "support_ticket_id": 42,
            "message": "We are looking into this issue...",
            "sender": {
                "id": 2,
                "name": "Admin User",
                "email": "admin@example.com",
                "role": "super_admin"
            },
            "created_at": "2026-09-13T11:00:00Z",
            "updated_at": "2026-09-13T11:00:00Z"
        }
    ]
}
```

---

### Add Reply to Ticket

**POST /api/tickets/{id}/replies**

Add a response to a support ticket (super admin only).

**Request:**
```json
{
    "message": "We have reset your password. Please check your email for temporary credentials."
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Reply sent successfully.",
    "data": {
        "reply_id": 1,
        "message": "We have reset your password...",
        "sender": "Admin User",
        "created_at": "2026-09-13T11:00:00Z"
    }
}
```

**Validation Rules:**
- message: Required, 2-5000 characters
- User must have super_admin role
- Ticket cannot be CLOSED

**Triggers:**
- TicketReplyAdded event dispatched
- Originator receives email notification
- Originator receives SMS notification (if enabled and phone exists)
- Ticket updated_at timestamp updated

---

### Update Ticket Status

**PUT /api/tickets/{id}/status**

Change the status of a support ticket (super admin only).

**Request:**
```json
{
    "status": "RESOLVED"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Ticket status updated successfully.",
    "data": {
        "ticket_id": 42,
        "ticket_number": "TICKET-2026-0042",
        "status": "RESOLVED",
        "updated_at": "2026-09-13T12:00:00Z"
    }
}
```

**Validation Rules:**
- status: Required, must be one of: OPEN, IN_PROGRESS, RESOLVED, CLOSED
- User must have super_admin role

**Triggers:**
- TicketStatusChanged event dispatched
- Originator receives notification with new status
- SMS notification sent to originator (if enabled)

---

### Super Admin Ticket List

**GET /api/tickets** (Super Admin Only)

Retrieve paginated list of all tickets assigned to authenticated super admin.

**Query Parameters:**
- `status`: Filter by status
- `category`: Filter by category
- `created_from`: Filter by creation date
- `created_to`: Filter by creation date
- `search`: Search by ticket ID, title, or originator name
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 20)

**Response (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 42,
            "ticket_number": "TICKET-2026-0042",
            "title": "System login issue",
            "category": "System Issue",
            "status": "OPEN",
            "originator": {
                "name": "John Doe",
                "email": "john@example.com"
            },
            "created_at": "2026-09-13T10:30:00Z",
            "updated_at": "2026-09-13T10:30:00Z"
        }
    ],
    "pagination": {
        "total": 24,
        "per_page": 20,
        "current_page": 1,
        "last_page": 2
    }
}
```

---

### Get Pending Ticket Count

**GET /api/tickets/pending-count** (Super Admin Only)

Get count of pending tickets assigned to authenticated super admin.

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "pending_count": 5,
        "total_assigned": 24
    }
}
```

## Models

### SupportTicket Model

**Location:** `app/Models/SupportTicket.php`

**Relationships:**
- `originator()`: Belongs to User (ticket creator)
- `assignedSuperAdmin()`: Belongs to User (assigned super admin)
- `replies()`: Has many TicketReply
- `notifications()`: Has many TicketNotification

**Attributes:**
```php
protected $fillable = [
    'ticket_number',
    'originator_user_id',
    'routed_to_super_admin_id',
    'title',
    'category',
    'description',
    'status',
    'created_by_role',
    'last_activity_at',
];

protected $casts = [
    'last_activity_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
```

### TicketReply Model

**Location:** `app/Models/TicketReply.php`

**Relationships:**
- `ticket()`: Belongs to SupportTicket
- `sender()`: Belongs to User

### TicketNotification Model

**Location:** `app/Models/TicketNotification.php`

**Relationships:**
- `ticket()`: Belongs to SupportTicket
- `recipient()`: Belongs to User

## Services

### TicketService

**Location:** `app/Services/TicketService.php`

Core business logic for ticket operations.

**Methods:**

#### createTicket()
```php
public function createTicket(User $user, string $title, string $category, string $description): SupportTicket
```
- Generates unique ticket number
- Creates SupportTicket record
- Dispatches TicketCreated event
- Returns created ticket instance

#### replyToTicket()
```php
public function replyToTicket(User $superAdminUser, int $ticketId, string $message): TicketReply
```
- Validates message content
- Creates TicketReply record
- Updates ticket's updated_at timestamp
- Dispatches TicketReplyAdded event
- Returns created reply instance

#### updateTicketStatus()
```php
public function updateTicketStatus(User $superAdminUser, int $ticketId, string $newStatus): SupportTicket
```
- Validates new status value
- Updates ticket status
- Dispatches TicketStatusChanged event
- Returns updated ticket instance

### TicketAssignmentService

**Location:** `app/Services/TicketAssignmentService.php`

Handles load-balancing assignment of tickets to super admins.

**Methods:**

#### loadBalanceAssignToSuperAdmin()
```php
public function loadBalanceAssignToSuperAdmin(SupportTicket $ticket): User
```
- Retrieves all super_admin role users
- Counts pending tickets per super admin
- Selects super admin with fewest pending tickets
- Updates ticket.routed_to_super_admin_id
- Returns assigned super admin User instance

**Algorithm:**
1. Query all users with super_admin role
2. For each super admin, count tickets with status != RESOLVED and != CLOSED
3. Select super admin with minimum pending count
4. If tie, select first super admin
5. Update ticket with assignment

### TicketIndicatorService

**Location:** `app/Services/TicketIndicatorService.php`

Provides cached ticket metrics for dashboard integration.

**Methods:**

#### getPendingTicketCount()
```php
public function getPendingTicketCount(User $user): int
```
- Returns count of OPEN and IN_PROGRESS tickets for user
- Results cached for 5 minutes
- Cache invalidated on ticket creation or status change

## Events and Listeners

### TicketCreated Event

**Location:** `app/Events/TicketCreated.php`

Fired when a support ticket is created.

**Properties:**
- `ticket`: SupportTicket instance
- `originatorUser`: User who created ticket
- `ticketNumber`: Generated ticket number

**Listener:** `RouteTicketToSuperAdmin`
- Calls TicketAssignmentService to load-balance assign to super admin
- Logs assignment with ticket and super admin details

### TicketReplyAdded Event

**Location:** `app/Events/TicketReplyAdded.php`

Fired when super admin adds a reply.

**Properties:**
- `ticket`: SupportTicket instance
- `reply`: TicketReply instance
- `originatorUser`: Ticket creator (originator)

**Listener:** `NotifyOriginatorOfReply`
- Creates TicketNotification record
- Sends email via ReplyReceivedNotification
- Sends SMS notification (if enabled)
- Updates notification status to SENT

### TicketStatusChanged Event

**Location:** `app/Events/TicketStatusChanged.php`

Fired when ticket status is updated.

**Properties:**
- `ticket`: SupportTicket instance
- `oldStatus`: Previous status
- `newStatus`: New status
- `originatorUser`: Ticket creator

**Listener:** `NotifyOriginatorOfStatusChange`
- Creates TicketNotification record
- Sends email via StatusChangedNotification
- Sends SMS notification (if enabled)
- Updates notification status to SENT

## Notifications

### ReplyReceivedNotification

**Location:** `app/Notifications/ReplyReceivedNotification.php`

Sent to originator when super admin replies.

**Channels:**
- email: Formatted email with ticket details and reply content
- database: In-app notification record

### StatusChangedNotification

**Location:** `app/Notifications/StatusChangedNotification.php`

Sent to originator when ticket status changes.

**Channels:**
- email: Formatted email with old/new status
- database: In-app notification record

## SMS Configuration

SMS notifications require environment variables in `.env`:

```env
SMS_ENABLED=true
ECONET_SMS_API_KEY=7be8bd35e16a66b5
ECONET_SMS_BASE_URL=https://mobilemessaging.econet.co.zw
ECONET_SMS_SENDER_ID="HSC SMS"
SMS_SITE_HOST=injury.hsc.org.zw
SMS_MAX_LENGTH=480
```

**SMS Messages:**

**Reply Notification:**
```
HSC-OHS: New reply on support ticket TICKET-2026-0042. 
Title: System login issue. 
Please log in to review. Visit injury.hsc.org.zw
```

**Status Change Notification:**
```
HSC-OHS: Ticket TICKET-2026-0042 status updated to RESOLVED. 
Please log in for details. Visit injury.hsc.org.zw
```

## Dashboard Integration

The ticket system is integrated into all user dashboards via the `ticket-quick-access` component.

**Location:** `resources/views/components/ticket-quick-access.blade.php`

**Features:**
- "Create Support Ticket" button
- "My Support Tickets" link
- Notification badge showing pending ticket count
- Caches indicator data for 5 minutes

**Dashboard Pages Updated:**
- HR Dashboard
- Supervisor Dashboard
- PMD Dashboard
- Labour & Social Dashboard
- Medical Board Dashboard
- MOHHC HR Dashboard
- Clinical Executive Dashboard
- HSC Conditions Dashboard

## Web Routes

### Ticket Management Routes

- **GET /support/tickets** - Super admin dashboard
- **GET /support/my-tickets** - User's ticket list
- **GET /support/ticket/{id}** - Ticket details view
- **POST /support/tickets** - Create new ticket (form)
- **PUT /support/tickets/{id}/status** - Update status (form)
- **POST /support/tickets/{id}/replies** - Add reply (form)

## Error Handling

### HTTP Status Codes

- **201 Created:** Successful ticket/reply creation
- **200 OK:** Successful data retrieval or status update
- **400 Bad Request:** Missing or invalid parameters
- **403 Forbidden:** User not authorized to perform action
- **404 Not Found:** Ticket or reply not found
- **422 Unprocessable Entity:** Validation error
- **500 Internal Server Error:** Server error with details in response

### Validation Errors

Response format for validation errors:

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["The title must be at least 3 characters."],
        "description": ["The description must be at least 10 characters."]
    }
}
```

## Logging

All ticket operations logged to `storage/logs/laravel.log`:

**Ticket Creation:**
```
[INFO] Ticket created successfully
ticket_id: 42
ticket_number: TICKET-2026-0042
originator_id: 5
category: System Issue
```

**Ticket Assignment:**
```
[INFO] Ticket routed to super admin
ticket_id: 42
ticket_number: TICKET-2026-0042
assigned_to_super_admin_id: 2
assigned_to_super_admin_name: Admin User
```

**SMS Notifications:**
```
[INFO] SMS notification sent to originator of reply
ticket_id: 42
ticket_number: TICKET-2026-0042
originator_id: 5
originator_phone: +263771234567
```

**Errors:**
```
[ERROR] Failed to notify originator of reply
ticket_id: 42
error: [exception message]
```

## Testing

### Manual Testing

1. **Create Ticket:** POST to /api/tickets with valid data
2. **View Tickets:** GET /api/tickets/my to see created tickets
3. **View Details:** GET /api/tickets/{id} to see full details
4. **Add Reply:** POST to /api/tickets/{id}/replies (super admin only)
5. **Update Status:** PUT to /api/tickets/{id}/status (super admin only)
6. **Check Notifications:** Verify email and SMS notifications sent

### Load Balancing Test

1. Create 3+ super admin users
2. Create multiple tickets (POST /api/tickets)
3. Query database: Check routed_to_super_admin_id distribution
4. Verify each super admin received approximately equal tickets

### SMS Test

1. Configure SMS credentials in .env
2. Set SMS_ENABLED=true
3. Create ticket with super admin user that has phone number
4. Add reply - should trigger SMS to originator's phone
5. Check SMS logs for delivery status

## Performance Optimization

- Ticket queries use indexes on (originator_user_id, routed_to_super_admin_id, status, created_at)
- Eager loading used to prevent N+1 queries (load originator, super admin, replies)
- Pagination default 20 items per page
- Indicator service caches ticket counts for 5 minutes
- Load balancing uses single COUNT query per super admin

## Future Enhancements

1. **Ticket Priority Levels:** Add priority field (Low, Medium, High, Critical)
2. **Ticket Comments:** Allow originators to add comments to closed tickets
3. **Attachments:** Support file uploads with virus scanning
4. **Assignment Override:** Allow manual reassignment by other super admins
5. **SLA Tracking:** Track response time and resolution time SLAs
6. **Reporting:** Dashboard showing ticket metrics and trends
7. **Batch Operations:** Close multiple tickets at once
8. **Archival:** Archive resolved tickets older than 90 days
