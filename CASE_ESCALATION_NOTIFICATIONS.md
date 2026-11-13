# Case Escalation Email & SMS Notifications

## Overview

The system now sends **both email and SMS notifications** to users when:
1. A case is assigned/escalated to them
2. An incident requires their action

---

## Notification Scenarios

### 1. **Incident Created → Supervisor Notified**

**When:** Worker/Reporter submits a new incident report

**Who Receives Notification:** Assigned Supervisor

**Channels:**
- ✅ Email (via Laravel notification)
- ✅ SMS (via Econet SMS service)

**SMS Message:**
```
Dear [Supervisor Name], A new occupational health incident requires your attention. 
Employee: [Worker Name], Reference: [INCIDENT_REF] at [Facility Name]. 
Please log into HSC-OHS to review and take action. Thank you.
```

**Email:** Full incident details with link to supervisor dashboard

**Implementation:**
- Event: `IncidentCreated`
- Listener: `SendSupervisorIncidentAlert`
- File: `app/Listeners/SendSupervisorIncidentAlert.php`

---

### 2. **Incident Escalated to HR → HR User Notified**

**When:** Supervisor submits HOD report and escalates case to HR

**Who Receives Notification:** Assigned HR User (load-balanced)

**Channels:**
- ✅ Email (via Laravel notification)
- ✅ SMS (via Econet SMS service)

**SMS Message:**
```
Dear [HR User First Name], A new occupational health incident has been escalated to you for HR review. 
Employee: [Worker Name], Reference: [INCIDENT_REF] at [Facility Name]. 
Please log into HSC-OHS to review. Thank you.
```

**Email:** Complete case dossier with link to HR review dashboard

**Implementation:**
- Event: `IncidentEscalatedToHR`
- Listener: `SendHRIncidentEscalationNotification`
- File: `app/Listeners/SendHRIncidentEscalationNotification.php`

---

## Technical Implementation

### Key Components

1. **SMSService** (`app/Services/SMSService.php`)
   - Handles SMS sending via Econet API
   - Formats phone numbers to +263 format
   - Logs all SMS attempts

2. **CommunicationLogger** (`app/Services/CommunicationLogger.php`)
   - Logs all email and SMS communications
   - Stores in `communication_logs` table
   - Tracks delivery status

3. **IncidentNotification** Model
   - Records notifications in `incident_notifications` table
   - Tracks notification type, channel, and status
   - Links to user and incident

### Event → Listener Flow

```
┌─────────────────────┐
│  Event Dispatched   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Listener Handle()  │
└──────────┬──────────┘
           │
           ├──► Send Email Notification
           │
           ├──► Log Email Communication
           │
           ├──► Send SMS (if phone exists)
           │
           ├──► Log SMS Communication
           │
           └──► Create IncidentNotification Record
```

### SMS Service Integration

```php
// Check if user has phone number
if (!empty($user->phone)) {
    // Send SMS
    $smsSent = $this->smsService->send($user->phone, $message);
    
    // Log SMS
    CommunicationLogger::logSMS(
        'NOTIFICATION_TYPE',
        $user->phone,
        $message,
        $smsSent ? 'SENT' : 'FAILED',
        $smsSent ? null : 'SMS sending failed',
        'Incident',
        $incident->id,
        $user->id
    );
}
```

---

## Configuration

### SMS Settings (`.env`)

```env
SMS_ENABLED=true
ECONET_SMS_API_KEY=7be8bd35e16a66b5
ECONET_SMS_BASE_URL=https://mobilemessaging.econet.co.zw
ECONET_SMS_SENDER_ID="HSC SMS"
```

### Email Settings (`.env`)

Already configured for Gmail SMTP.

---

## Database Tracking

### Communication Logs Table

All notifications logged in `communication_logs`:

```sql
SELECT 
    communication_type,
    recipient_email,
    recipient_phone,
    status,
    sent_at,
    error_message
FROM communication_logs
WHERE related_entity_type = 'Incident'
AND related_entity_id = [incident_id]
ORDER BY sent_at DESC;
```

### Incident Notifications Table

Notification records in `incident_notifications`:

```sql
SELECT 
    notification_type,
    notification_channel,
    recipient_user_id,
    status,
    sent_at,
    failure_reason
FROM incident_notifications
WHERE incident_id = [incident_id]
ORDER BY sent_at DESC;
```

---

## Notification Types

| Type | Description | Recipients | Channels |
|------|-------------|-----------|----------|
| `SUPERVISOR_ALERT` | New incident assigned | Supervisor | Email + SMS |
| `HR_ESCALATION` | Case escalated to HR | Assigned HR User | Email + SMS |
| `DOCUMENT_UPLOAD` | Documents uploaded | Assigned HR User | Email + SMS |
| `WITNESS_REMINDER` | Witness statement reminder | Witness | Email (if available) |

---

## User Experience

### Supervisor Receives Notification

1. **Email arrives** - Opens in inbox with incident details
2. **SMS arrives** - Text message on phone with reference number
3. **Logs in** - Clicks notification or manually logs in
4. **Views incident** - Opens case from dashboard
5. **Takes action** - Verifies, adds witnesses, submits HOD report

### HR User Receives Notification

1. **Email arrives** - Complete dossier with all case details
2. **SMS arrives** - Alert text with incident reference
3. **Logs in** - Accesses HR review dashboard
4. **Reviews case** - Examines supervisor report, witness statements
5. **Makes decision** - Approves, escalates to DMO, or requests more info

---

## Benefits

✅ **Immediate Awareness** - Users notified instantly via email and SMS  
✅ **Faster Response Times** - SMS ensures notification even when email not checked  
✅ **Audit Trail** - All communications logged for compliance  
✅ **Reduced Delays** - No need to manually inform users of assignments  
✅ **Professional Communication** - Consistent, branded messaging  
✅ **Multi-Channel Redundancy** - Email OR SMS ensures delivery  

---

## Testing

### Test Supervisor Notification

1. Submit new incident report as worker
2. Check if assigned supervisor receives:
   - Email with incident details
   - SMS alert on phone
3. Verify logs in `communication_logs` table
4. Confirm notification in `incident_notifications` table

### Test HR Escalation Notification

1. Log in as supervisor
2. Complete HOD report on pending incident
3. Submit dossier to HR
4. Check if assigned HR user receives:
   - Email with complete dossier
   - SMS alert on phone
5. Verify both communication logs

### Check SMS Logs

```sql
-- View recent SMS notifications
SELECT 
    communication_type,
    recipient_phone,
    message_content,
    status,
    sent_at,
    error_message
FROM communication_logs
WHERE communication_type IN ('SUPERVISOR_INCIDENT_ALERT', 'HR_ESCALATION')
AND recipient_phone IS NOT NULL
ORDER BY sent_at DESC
LIMIT 20;
```

---

## Troubleshooting

### SMS Not Sending

**Check:**
1. `SMS_ENABLED=true` in `.env`
2. Valid API key configured
3. User has phone number in database
4. Phone number format (should be +263...)
5. Econet API status (check logs)

**Logs Location:**
```
storage/logs/laravel.log
```

**Look for:**
```
SMS sent successfully
SMS sending failed
```

### Email Not Sending

**Check:**
1. Mail configuration in `.env`
2. Gmail SMTP credentials
3. User has valid email address
4. Laravel queue running (if using queues)

---

## Future Enhancements

1. **WhatsApp Integration** - Send notifications via WhatsApp Business API
2. **Push Notifications** - Browser push for logged-in users
3. **Notification Preferences** - Allow users to choose channels
4. **Digest Mode** - Batch multiple notifications into daily digest
5. **Read Receipts** - Track when users open emails/click links
6. **SMS Delivery Reports** - Track actual delivery from Econet

---

## Related Files

- `app/Listeners/SendSupervisorIncidentAlert.php` - Supervisor notifications
- `app/Listeners/SendHRIncidentEscalationNotification.php` - HR notifications
- `app/Services/SMSService.php` - SMS sending service
- `app/Services/CommunicationLogger.php` - Communication logging
- `app/Events/IncidentCreated.php` - Incident creation event
- `app/Events/IncidentEscalatedToHR.php` - HR escalation event

---

**Status:** ✅ COMPLETE  
**Last Updated:** November 4, 2026  
**Production Ready:** Yes
