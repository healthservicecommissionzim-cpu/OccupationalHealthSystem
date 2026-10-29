# Witness Email Fallback System

## Overview

Enhanced the witness notification system to support **email fallback** when SMS fails. Supervisors can now contact witnesses via email instead of SMS from the witness management page.

---

## Changes Made

### 1. Database Schema Update

**Migration**: `database/migrations/2026_10_28_add_email_to_incident_witness_tokens.php`

- Added `email` column to `incident_witness_tokens` table
- Allows storing witness email addresses for fallback notifications

### 2. Updated Model

**File**: `app/Models/IncidentWitnessToken.php`

- Added `email` to `$fillable` array
- Witnesses can now have email addresses stored

### 3. Email Template Created

**File**: `resources/views/emails/witness_statement_reminder.blade.php`

- Professional HTML email template
- Includes:
  - Incident details (reference, date, facility)
  - Direct link to submit statement
  - Copy-paste link option
  - Branded HSC-OHS styling

### 4. Mail Class

**File**: `app/Mail/WitnessStatementReminderMail.php`

- Mailable class for witness statement reminders
- Automatically generates statement link
- Subject: "Witness Statement Required - [REFERENCE]"

### 5. Controller Method

**File**: `app/Http/Controllers/IncidentController.php`

**Method**: `sendWitnessEmailReminder()`

- Verifies supervisor authorization
- Checks if witness has submitted
- Validates email address exists
- Sends email using Laravel Mail
- Logs notification in `incident_notifications` table
- Updates `witness_last_reminder_sent_at` timestamp

### 6. Route Added

**File**: `routes/web.php`

```php
Route::post('/witness/{tokenId}/email-reminder', [IncidentController::class, 'sendWitnessEmailReminder'])
    ->name('send_witness_email_reminder');
```

### 7. UI Enhanced

**File**: `resources/views/incidents/supervisor_witness.blade.php`

#### Button Changed:
- **Before**: "Resend SMS" 
- **After**: "Contact by Email"

#### New Features:
- **Email Contact**: If witness has email, sends email reminder
- **Manual Contact Modal**: If no email, shows modal with:
  - WhatsApp share button (opens WhatsApp with pre-filled message)
  - Copy statement link button
  - Display of full statement link
- **Improved UX**: Better fallback options when automated notifications fail

---

## How It Works

### With Email Address

1. Supervisor clicks "Contact by Email"
2. System checks if witness has email
3. Sends professional email with statement link
4. Logs notification
5. Shows success message

### Without Email Address

1. Supervisor clicks "Contact by Email"
2. Modal opens with manual options:
   - **WhatsApp**: Opens WhatsApp with pre-filled message to witness phone
   - **Copy Link**: Copies statement link to clipboard
   - **View Link**: Shows full link for manual sharing

---

## Benefits

✅ **SMS-Independent**: No longer dependent on SMS service  
✅ **Multiple Fallbacks**: Email → WhatsApp → Manual copy  
✅ **Professional Communication**: Branded email templates  
✅ **Audit Trail**: All attempts logged in database  
✅ **User-Friendly**: Simple one-click actions for supervisors  
✅ **WhatsApp Integration**: Direct link to send via WhatsApp  
✅ **Cost-Effective**: Email is free, WhatsApp uses witness's data  

---

## Usage

### For Supervisors

**Page**: `https://injury.hsc.org.zw/supervisor/incidents/{id}/witness`

1. View witness list
2. Click "Contact by Email" for pending witnesses
3. If email exists: Email sent automatically
4. If no email: Choose manual contact method (WhatsApp or copy link)

### For Future Incident Submissions

To enable email fallback for new witnesses, collect email addresses during incident submission:

```php
// Future enhancement: Update incident submission form
Witness 1:
- Name: ________
- Phone: ________
- Email: ________ (optional - for fallback if SMS fails)
```

---

## Testing

### Test Email Notification

1. Add email to existing witness:
   ```sql
   UPDATE incident_witness_tokens 
   SET email = 'test@example.com' 
   WHERE id = [witness_id];
   ```

2. Go to supervisor witness page
3. Click "Contact by Email"
4. Check email inbox

### Test Manual Fallback

1. Witness without email
2. Click "Contact by Email"
3. Modal appears with WhatsApp/Copy options
4. Test WhatsApp link opens correctly
5. Test copy link functionality

---

## Future Enhancements

1. **Collect email during incident submission** - Add email field to witness form
2. **Auto-retry logic** - Try SMS → wait → try Email → wait → manual intervention
3. **Email delivery tracking** - Track open/click rates
4. **SMS via WhatsApp Business API** - Official WhatsApp integration
5. **Batch notifications** - Send reminders to all pending witnesses at once

---

## Database Tracking

All email notifications logged in `incident_notifications`:

```sql
SELECT 
    notification_type,
    notification_channel,
    recipient_email,
    status,
    sent_at,
    error_message
FROM incident_notifications
WHERE notification_type = 'WITNESS_REMINDER_EMAIL'
ORDER BY sent_at DESC;
```

---

## Related Files

- Migration: `database/migrations/2026_10_28_add_email_to_incident_witness_tokens.php`
- Model: `app/Models/IncidentWitnessToken.php`
- Controller: `app/Http/Controllers/IncidentController.php` (sendWitnessEmailReminder)
- Mail: `app/Mail/WitnessStatementReminderMail.php`
- Template: `resources/views/emails/witness_statement_reminder.blade.php`
- View: `resources/views/incidents/supervisor_witness.blade.php`
- Route: `routes/web.php`

---

## Support

For issues with email delivery, check:

1. Laravel logs: `storage/logs/laravel.log`
2. Mail configuration: `.env` MAIL_* settings
3. Database: `incident_notifications` table for status
4. SMTP settings: Gmail SMTP enabled and credentials correct
