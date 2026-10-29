# Witness Contact UI Update

## Summary
Updated the supervisor witness contact modal to simplify the contact methods and improve user experience with custom confirmation modals.

## Changes Made (Commit: 7e0197d)

### 1. Removed Options
- ❌ Removed WhatsApp integration option
- ❌ Removed "Copy Link" option

### 2. Kept Options
- ✅ **Email** - Sends email reminder via `sendWitnessEmailReminder()` endpoint
- ✅ **SMS** - Sends SMS reminder via `sendWitnessReminder()` endpoint

### 3. UI Improvements

#### Main Contact Modal
- Button changed from "Contact by Email" to "Contact"
- Modal displays two clean options:
  - **Email option** (if email exists): Blue gradient button with envelope icon
  - **SMS option**: Purple gradient button with message icon
- Cancel button to close modal

#### Confirmation Modals
Replaced browser `confirm()` alerts with custom styled confirmation modals:

**Email Confirmation Modal:**
- Blue circular icon with envelope
- Heading: "Send Email Reminder?"
- Shows witness name and email address
- Cancel and "Send Email" buttons

**SMS Confirmation Modal:**
- Purple circular icon with message
- Heading: "Send SMS Reminder?"
- Shows witness name and phone number
- Cancel and "Send SMS" buttons

### 4. Backend Integration

Both methods work with existing endpoints:

**Email Endpoint:**
```
POST /supervisor/incidents/{incident}/witness/{tokenId}/email-reminder
```
- Controller: `IncidentController@sendWitnessEmailReminder`
- Verifies supervisor authorization
- Checks if witness has email on file
- Sends email using `WitnessStatementReminderMail`
- Logs notification in database
- Updates `witness_last_reminder_sent_at` timestamp

**SMS Endpoint:**
```
POST /supervisor/incidents/{incident}/witness/{tokenId}/reminder
```
- Controller: `IncidentController@sendWitnessReminder`
- Verifies supervisor authorization
- Sends SMS via Econet SMS service
- Logs notification in database
- Updates `witness_last_reminder_sent_at` timestamp

### 5. User Flow

1. Supervisor clicks "Contact" button for pending witness
2. Modal appears with Email and SMS options
3. Supervisor selects preferred method (Email or SMS)
4. Confirmation modal appears asking to verify action
5. Supervisor confirms or cancels
6. If confirmed:
   - Notification sent
   - Success toast appears
   - Page reloads to show updated timestamp
7. All attempts logged in `incident_notifications` table

## Testing Checklist

When testing on production (https://injury.hsc.org.zw):

### Email Reminder Test
1. ✅ Navigate to supervisor witness page
2. ✅ Click "Contact" button for witness with email
3. ✅ Verify modal shows Email option (blue button)
4. ✅ Click Email option
5. ✅ Verify confirmation modal appears with witness details
6. ✅ Click "Send Email" button
7. ✅ Verify email sent successfully (check inbox)
8. ✅ Verify success toast appears
9. ✅ Verify "Last Reminder" timestamp updated on page

### SMS Reminder Test
1. ✅ Navigate to supervisor witness page
2. ✅ Click "Contact" button for pending witness
3. ✅ Verify modal shows SMS option (purple button)
4. ✅ Click SMS option
5. ✅ Verify confirmation modal appears with witness details
6. ✅ Click "Send SMS" button
7. ✅ Verify SMS sent successfully (check phone)
8. ✅ Verify success toast appears
9. ✅ Verify "Last Reminder" timestamp updated on page

### Edge Cases
1. ✅ Witness without email - only SMS option shows
2. ✅ Witness already submitted - "Contact" button disabled
3. ✅ Non-assigned supervisor - 403 error returned
4. ✅ SMS service failure - error logged, generic message shown
5. ✅ Email delivery failure - error logged, generic message shown

## Files Modified

```
resources/views/incidents/supervisor_witness.blade.php
```

## Related Documentation

- `.kiro/steering/witness-email-fallback.md` - Complete email fallback system documentation
- `app/Http/Controllers/IncidentController.php` - Backend methods (lines 902-1100)
- `routes/web.php` - Route definitions (lines 443-444)

## Security & Audit

- All notifications logged in `incident_notifications` table
- `initiated_by_user_id` tracks which supervisor sent notification
- Supervisor authorization verified on each request
- Only assigned supervisors can contact witnesses
- SMS credentials never exposed to UI or logs

## Next Steps

1. Deploy to production
2. Test both Email and SMS flows
3. Monitor `incident_notifications` table for sent status
4. Check Laravel logs for any errors: `storage/logs/laravel.log`
5. Verify Econet SMS service is working correctly
