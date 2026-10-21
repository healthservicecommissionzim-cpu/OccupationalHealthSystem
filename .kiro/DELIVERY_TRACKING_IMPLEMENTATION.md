# Email & SMS Delivery Tracking Implementation

## Overview

Complete delivery tracking system for monitoring email and SMS communications throughout the system. Tracks not just whether we sent a message, but whether it actually reached the recipient.

## What's Implemented

### 1. Database Migration
**File:** `database/migrations/2026_10_21_add_delivery_tracking_to_communication_logs.php`

New fields added to `communication_logs` table:
- `delivery_status` - PENDING, SENT, DELIVERED, FAILED, BOUNCED, UNDELIVERABLE
- `delivery_attempts` - Number of send attempts
- `first_attempt_at` - When first attempt was made
- `delivered_at` - When confirmed delivered
- `failure_reason` - Human-readable failure reason
- `failure_code` - Provider error code
- `delivery_response` - Raw response from provider
- `provider` - Which provider (Econet SMS, Gmail, etc.)
- `provider_message_id` - Provider's unique message ID
- `provider_metadata` - Additional provider data (JSON)
- `webhook_received` - Whether we received provider webhook
- `webhook_received_at` - When webhook arrived
- `webhook_data` - Full webhook payload (JSON)
- `next_retry_at` - When next retry is scheduled
- `retry_history` - History of all retry attempts (JSON)

### 2. Enhanced CommunicationLogger Service
**File:** `app/Services/CommunicationLogger.php`

New methods:
- `logEmail()` - Enhanced with delivery tracking fields
- `logSms()` - Enhanced with delivery tracking fields
- `updateDeliveryStatus()` - Called when webhooks arrive from providers
- `markForRetry()` - Reschedules failed communications for retry
- `getDeliveryStats()` - Dashboard statistics on delivery performance

### 3. Updated CommunicationLog Model
**File:** `app/Models/CommunicationLog.php`

New helper methods:
- `isDelivered()` - Check if confirmed delivered
- `isDeliveryFailed()` - Check if delivery failed
- `isDeliveryPending()` - Check if still pending
- `getDeliveryStatusColorAttribute()` - UI badge colors

### 4. Updated Views

#### Logs List View
**File:** `resources/views/super-admin/communications/logs.blade.php`
- Added "Delivery" column showing delivery status
- Color-coded badges: DELIVERED (emerald), SENT (sky), PENDING (amber), FAILED (red), BOUNCED (orange), UNDELIVERABLE (rose)
- Icons for quick visual identification
- Tooltips showing failure reasons

#### Details View
**File:** `resources/views/super-admin/communications/show.blade.php`

Now displays:
- **Delivery Status Section** - Send status vs. Delivery status at a glance
- **Delivery Timeline** - Visual timeline of all delivery events
  - First Attempt
  - Delivered confirmation
  - Failed status
  - Next Retry scheduled
  - Webhook received
- **Failure Information** - When delivery fails:
  - Reason
  - Error code
  - Provider response
- **Retry History** - Complete history of retry attempts
- **Provider Details** - Message ID and provider metadata
- **Webhook Data** - Raw webhook payload from provider

## How It Works

### Initial Logging
```php
CommunicationLogger::logEmail(
    type: 'REGISTRATION',
    recipientEmail: 'user@example.com',
    // ... other fields ...
    provider: 'Gmail',
    providerMessageId: '0x12345abcde'
);
```

Sets:
- `status` = SENT (we sent from system)
- `delivery_status` = SENT (initial state)
- `first_attempt_at` = now()
- `delivered_at` = now() (optimistically set)
- `delivery_attempts` = 1

### When Provider Sends Webhook
```php
CommunicationLogger::updateDeliveryStatus(
    messageId: '0x12345abcde',
    deliveryStatus: 'DELIVERED',
    deliveryData: ['timestamp' => '2026-10-21 14:30:00', ...]
);
```

Updates:
- `delivery_status` = DELIVERED (confirmed)
- `webhook_received` = true
- `webhook_received_at` = now()
- `webhook_data` = full webhook payload

### When Delivery Fails
```php
CommunicationLogger::updateDeliveryStatus(
    messageId: '0x12345abcde',
    deliveryStatus: 'BOUNCED',
    failureReason: 'Invalid email address',
    failureCode: 'ERR_550'
);
```

Then mark for retry:
```php
CommunicationLogger::markForRetry(
    logId: $log->id,
    retryReason: 'Email bounced - will retry'
);
```

Sets:
- `delivery_status` = PENDING (retry scheduled)
- `delivery_attempts` = 2
- `next_retry_at` = now()->addHours(1)
- `retry_history` = JSON array of previous attempts

## Dashboard Statistics

Get delivery performance:
```php
$stats = CommunicationLogger::getDeliveryStats(days: 30);

// Returns:
// [
//   {channel: 'email', delivery_status: 'DELIVERED', count: 145, percentage: 92.3},
//   {channel: 'email', delivery_status: 'FAILED', count: 10, percentage: 6.4},
//   {channel: 'sms', delivery_status: 'DELIVERED', count: 203, percentage: 95.8},
//   ...
// ]
```

## Key Features

✅ **Comprehensive Tracking** - From send to confirmed delivery
✅ **Multiple Delivery States** - PENDING, SENT, DELIVERED, FAILED, BOUNCED, UNDELIVERABLE
✅ **Failure Details** - Reason, code, provider response
✅ **Retry Management** - Automatic retry scheduling with history
✅ **Provider Integration** - Support for multiple providers (Econet SMS, Gmail, AWS SES, etc.)
✅ **Webhook Support** - Automatic status updates from providers
✅ **Timeline View** - Visual delivery journey
✅ **Provider Data** - Store provider message IDs and metadata
✅ **Statistics** - Delivery performance metrics

## Integration Points

All notifications already updated to use enhanced logging:
- UserApprovedNotification
- UserRejectedNotification
- DocumentUploadedNotification
- IncidentConfirmationNotification
- IncidentHRReviewNotification
- IncidentSupervisorAlertNotification
- ReplyReceivedNotification
- StatusChangedNotification
- SendDocumentUploadNotification (SMS logging)
- SendSupervisorIncidentAlert (email logging)
- SendIncidentConfirmationEmail (email logging)
- SendMilestoneNotifications (email logging)
- SendHRIncidentEscalationNotification (email logging)
- NotifyOriginatorOfReply (email + SMS logging)
- NotifyOriginatorOfStatusChange (email + SMS logging)
- WitnessSMSService (SMS logging)

## Next Steps

1. Run migration: `php artisan migrate`
2. Test the communication logs page
3. Set up webhooks in your email/SMS providers to update delivery status
4. Monitor delivery statistics in the Super Admin dashboard

## Provider Webhook Setup

### Econet SMS Webhooks
Configure in `.env`:
```
SMS_WEBHOOK_URL=https://yourapp.com/api/webhooks/sms-delivery
```

### Email Provider Webhooks
Set provider callback URLs to:
```
https://yourapp.com/api/webhooks/email-delivery
```

Webhook handlers should call:
```php
CommunicationLogger::updateDeliveryStatus(
    $messageId,
    $status,
    $providerData,
    $failureReason,
    $failureCode
);
```
