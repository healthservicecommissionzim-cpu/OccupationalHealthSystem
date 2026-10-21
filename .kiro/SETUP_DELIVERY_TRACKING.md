# Quick Setup Guide - Delivery Tracking System

## 🚀 Quick Start

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Seed Sample Data
```bash
php artisan db:seed --class=CommunicationLogSeeder
```

### Step 3: Access Communication Logs
Navigate to: `https://yourapp.com/system/communication-logs`

## ✅ What's Ready

- ✅ Database migration with delivery tracking fields
- ✅ Enhanced CommunicationLogger service
- ✅ Updated CommunicationLog model
- ✅ Communication logs list view with delivery status column
- ✅ Detailed view with delivery timeline and failure info
- ✅ Sample data seeder with 10 realistic entries

## 📊 Sample Data Includes

| Type | Status | Provider | Details |
|------|--------|----------|---------|
| Registration | DELIVERED | Gmail | Success with webhook |
| Approval | BOUNCED | Gmail | Failed - invalid domain |
| Document Upload | DELIVERED | Econet SMS | SMS delivered |
| Incident Report | PENDING | Econet SMS | Awaiting confirmation |
| Rejection | FAILED | Gmail | With retry scheduled |
| HR Escalation | DELIVERED | Gmail | With webhook |
| Registration | UNDELIVERABLE | Econet SMS | Invalid phone |
| Milestone | DELIVERED | Gmail | With webhook |
| Document Upload | SENT | Gmail | Recent - pending webhook |
| Approval | DELIVERED | Gmail | Multiple retries before success |

## 🔧 What's Been Updated

### Notifications
All 8 notification classes now log with delivery tracking:
- UserApprovedNotification
- UserRejectedNotification
- DocumentUploadedNotification
- IncidentConfirmationNotification
- IncidentHRReviewNotification
- IncidentSupervisorAlertNotification
- ReplyReceivedNotification
- StatusChangedNotification

### Listeners
All 7 listeners log emails and SMS:
- SendDocumentUploadNotification
- SendSupervisorIncidentAlert
- SendIncidentConfirmationEmail
- SendMilestoneNotifications
- SendHRIncidentEscalationNotification
- NotifyOriginatorOfReply
- NotifyOriginatorOfStatusChange

### Services
- CommunicationLogger - 5 new methods
- WitnessSMSService - SMS logging to CommunicationLogger

## 📋 Key Features

### Delivery Statuses
- **PENDING** - Initial state, awaiting first send
- **SENT** - Accepted by provider, awaiting confirmation
- **DELIVERED** - Confirmed delivered via webhook
- **FAILED** - Failed to send, retry scheduled
- **BOUNCED** - Email bounced (hard failure)
- **UNDELIVERABLE** - SMS/Email unreachable (hard failure)

### Tracked Information
✅ Send status (SENT, FAILED, PENDING)
✅ Delivery status (6 states)
✅ Delivery attempts (count)
✅ Timestamps (first attempt, delivered, retry scheduled)
✅ Failure reasons (human-readable)
✅ Failure codes (provider error codes)
✅ Provider info (Gmail, Econet, etc.)
✅ Provider message IDs
✅ Provider metadata (JSON)
✅ Webhook confirmations
✅ Webhook data (JSON)
✅ Retry history (JSON array)

## 🎯 Views & Pages

### Communication Logs List
- URL: `/system/communication-logs`
- Shows: Type, Channel, Recipient, Subject, Status, Delivery, Sent At
- Filters: Type, Channel, Status, Date Range, Search
- Actions: View details, Export CSV

### Communication Log Details
- URL: `/system/communication-logs/{id}`
- Shows:
  - Type, Channel, Sent At
  - Send Status badge
  - Delivery Status badge
  - Delivery attempts count
  - Provider name
  - **Delivery Timeline** with all events
  - **Failure Information** (if failed)
  - **Retry History** (if retried)
  - **Provider Details** (message ID, metadata)
  - **Webhook Data** (if received)
  - Recipient information
  - Message preview
  - Full content
  - Related entity

## 🔌 API Methods

### Logging Communications
```php
// Email
CommunicationLogger::logEmail(
    type: 'REGISTRATION',
    recipientEmail: 'user@example.com',
    subject: 'Welcome',
    messagePreview: 'Account created',
    provider: 'Gmail',
    providerMessageId: 'msg_123'
);

// SMS
CommunicationLogger::logSms(
    type: 'REGISTRATION',
    recipientPhone: '+263771234567',
    message: 'Welcome to HSC-OHS',
    provider: 'Econet SMS',
    providerMessageId: 'sms_456'
);
```

### Updating Delivery Status
```php
// When webhook arrives
CommunicationLogger::updateDeliveryStatus(
    messageId: 'msg_123',
    deliveryStatus: 'DELIVERED',
    deliveryData: ['timestamp' => '...']
);

// For failures
CommunicationLogger::updateDeliveryStatus(
    messageId: 'msg_123',
    deliveryStatus: 'BOUNCED',
    failureReason: 'Invalid email',
    failureCode: 'ERR_550'
);
```

### Mark for Retry
```php
CommunicationLogger::markForRetry(
    logId: 1,
    retryReason: 'Server timeout'
);
```

### Get Statistics
```php
$stats = CommunicationLogger::getDeliveryStats(days: 30);
// Returns delivery performance by channel and status
```

## 🧪 Testing

### Test with Sample Data
```bash
# Reset and seed
php artisan migrate:fresh --seed

# View logs at
# https://yourapp.com/system/communication-logs
```

### Test Filters
1. Filter by Type: Select "REGISTRATION"
2. Filter by Channel: Select "EMAIL"
3. Filter by Status: Select "FAILED"
4. Filter by Delivery: Select "DELIVERED"
5. Filter by Date: Select "Last 7 days"

### Test Details View
1. Click "View" on any log
2. Check all sections render correctly
3. If delivery failed, verify failure reason shows
4. If has retries, verify retry history displays
5. Check timeline shows all events

## 📝 Database Fields Added

```sql
ALTER TABLE communication_logs ADD COLUMN (
    delivery_status ENUM('PENDING', 'SENT', 'DELIVERED', 'FAILED', 'BOUNCED', 'UNDELIVERABLE'),
    delivery_attempts INT DEFAULT 0,
    first_attempt_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    failure_reason VARCHAR(255) NULL,
    failure_code VARCHAR(50) NULL,
    delivery_response LONGTEXT NULL,
    provider VARCHAR(100) NULL,
    provider_message_id VARCHAR(255) NULL,
    provider_metadata JSON NULL,
    webhook_received BOOLEAN DEFAULT FALSE,
    webhook_received_at TIMESTAMP NULL,
    webhook_data JSON NULL,
    next_retry_at TIMESTAMP NULL,
    retry_history JSON NULL
);
```

## 🎓 Next Steps

1. **Configure Webhooks**
   - Set up email provider webhooks (Gmail, AWS SES, etc.)
   - Set up SMS provider webhooks (Econet, etc.)
   - Point to: `/api/webhooks/email-delivery` and `/api/webhooks/sms-delivery`

2. **Create Webhook Handler**
   - Create webhook routes/controller
   - Call `CommunicationLogger::updateDeliveryStatus()` when webhooks arrive

3. **Monitor Dashboard**
   - Check delivery statistics regularly
   - Investigate bounced/failed communications
   - Identify high failure rates

4. **Set Up Retries**
   - Create artisan command to check `next_retry_at`
   - Reschedule failed communications
   - Update delivery status

5. **Alerts**
   - Create alerts for high failure rates
   - Notify admins of critical delivery failures

## 📞 Support

For issues:
1. Check migration ran: `php artisan migrate:status`
2. Check seeder ran: `SELECT COUNT(*) FROM communication_logs;`
3. Check views accessible at `/system/communication-logs`
4. Check logs in `storage/logs/laravel.log`

## ✨ Features Summary

✅ Complete delivery tracking (send → delivery)
✅ 6 delivery statuses with visual badges
✅ Failure reasons and error codes
✅ Retry scheduling and history
✅ Provider integration support
✅ Webhook tracking
✅ Timeline visualization
✅ Filtering and search
✅ CSV export
✅ 10 sample communications for testing
