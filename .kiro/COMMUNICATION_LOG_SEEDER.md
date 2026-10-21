# Communication Log Seeder

## Overview

Sample seeder that creates 10 realistic communication log entries with various statuses and scenarios for testing and demonstration purposes.

## Sample Data Created

The seeder creates 10 communication logs with different scenarios:

### 1. Successful Email Registration (DELIVERED)
- Type: REGISTRATION
- Channel: EMAIL
- Status: SENT → DELIVERED
- Webhook received: Yes
- Time: 5 days ago

### 2. Bounced Email - Invalid Domain (BOUNCED)
- Type: APPROVAL
- Channel: EMAIL
- Status: SENT → BOUNCED
- Failure Code: ERR_550
- Reason: Domain does not exist
- Retry scheduled: Yes
- Time: 4 days ago

### 3. Successful SMS - Document Upload (DELIVERED)
- Type: DOCUMENT_UPLOAD
- Channel: SMS
- Status: SENT → DELIVERED
- Provider: Econet SMS
- Time: 3 days ago

### 4. Pending SMS - No Webhook Yet (PENDING)
- Type: INCIDENT_CONFIRMATION
- Channel: SMS
- Status: SENT (awaiting confirmation)
- Webhook received: No
- Time: 2 hours ago

### 5. Failed Email - Retry Scheduled (FAILED)
- Type: REJECTION
- Channel: EMAIL
- Status: SENT → FAILED
- Failure Code: ERR_503
- Reason: Server temporarily unavailable
- Retry attempts: 2
- Next retry: 1 hour later

### 6. Successful Email - HR Escalation (DELIVERED)
- Type: HR_ESCALATION
- Channel: EMAIL
- Status: SENT → DELIVERED
- Webhook received: Yes
- Time: 6 hours ago

### 7. Undeliverable SMS - Invalid Number (UNDELIVERABLE)
- Type: REGISTRATION
- Channel: SMS
- Status: SENT → UNDELIVERABLE
- Failure Code: ERR_INVALID_NUMBER
- Reason: Number does not exist or inactive
- Time: 1 day ago

### 8. Successful Email - Milestone (DELIVERED)
- Type: MILESTONE_ALERT
- Channel: EMAIL
- Status: SENT → DELIVERED
- Webhook received: Yes
- Time: 12 hours ago

### 9. Recent Email - Sent (SENT)
- Type: DOCUMENT_UPLOAD
- Channel: EMAIL
- Status: SENT (recent, awaiting webhook)
- Webhook received: No
- Time: 30 minutes ago

### 10. Multiple Retries - Finally Delivered (DELIVERED)
- Type: APPROVAL
- Channel: EMAIL
- Status: SENT → FAILED → FAILED → DELIVERED
- Retry attempts: 3
- Reasons: Server timeout, Connection timeout
- Finally delivered: 4 hours ago
- Complete retry history preserved

## Running the Seeder

### Option 1: Run All Seeders
```bash
php artisan db:seed
```
This will run all seeders including CommunicationLogSeeder.

### Option 2: Run Only CommunicationLogSeeder
```bash
php artisan db:seed --class=CommunicationLogSeeder
```

### Option 3: Run Fresh Migration + Seeding
```bash
php artisan migrate:fresh --seed
```
⚠️ **Warning**: This will drop all tables and reseed them. Use only in development!

## Viewing the Sample Data

After seeding, view the communication logs at:
```
https://yourapp.com/system/communication-logs
```

### Filter Examples

**View all delivered emails:**
- Channel: Email
- Delivery Status: DELIVERED

**View all failed communications:**
- Status: FAILED

**View pending SMS:**
- Channel: SMS
- Delivery Status: PENDING

**View bounced emails:**
- Delivery Status: BOUNCED

**View retry history:**
- Click "View" on log with 3 delivery attempts

## Sample Data Includes

✅ Multiple communication types:
- REGISTRATION
- APPROVAL
- REJECTION
- INCIDENT_CONFIRMATION
- DOCUMENT_UPLOAD
- HR_ESCALATION
- MILESTONE_ALERT

✅ All delivery statuses:
- DELIVERED (confirmed by webhook)
- SENT (awaiting confirmation)
- FAILED (with retry scheduled)
- BOUNCED (permanent failure)
- UNDELIVERABLE (invalid recipient)
- PENDING (initial state)

✅ Provider variations:
- Gmail SMTP (emails)
- Econet SMS (SMS messages)

✅ Complete metadata:
- Provider message IDs
- Provider metadata (JSON)
- Webhook data
- Failure codes and reasons
- Retry history

✅ Realistic timelines:
- Some from 5 days ago
- Some from recent (30 minutes ago)
- Multiple retry attempts with timestamps

## Testing Scenarios

Use the sample data to test:

1. **Delivery Status Display**
   - Check colors and icons for each status
   - Verify tooltips show failure reasons

2. **Timeline View**
   - View delivery timeline for logs with multiple attempts
   - Check retry history display

3. **Filtering**
   - Filter by communication type
   - Filter by channel
   - Filter by delivery status
   - Filter by date range

4. **Export**
   - Export filtered logs to CSV
   - Verify all columns are exported

5. **Details View**
   - Click on various logs
   - Check all sections display correctly
   - Verify failure reasons visible
   - Check provider metadata display
   - View webhook data if present

## Resetting Sample Data

To clear and reseed the communication logs:

```bash
php artisan db:seed --class=CommunicationLogSeeder
```

Or to completely fresh start:
```bash
php artisan migrate:fresh --seed
```

## Notes

- Sample data uses real user IDs from the database (limits to 5 users)
- If no users exist, the seeder will create 5 dummy users
- All timestamps are relative to current time
- Provider message IDs are unique
- All JSON fields are properly formatted

## Customizing Sample Data

Edit `database/seeders/CommunicationLogSeeder.php` to:
- Add more communication logs
- Change communication types
- Modify timestamps (use `now()->subDays()`, etc.)
- Customize failure reasons
- Add additional provider types

Example:
```php
'communication_type' => 'CUSTOM_TYPE',
'channel' => 'email',
'delivery_status' => 'DELIVERED',
// ... other fields
```

Then run:
```bash
php artisan db:seed --class=CommunicationLogSeeder
```
