# Document Upload Notification System

## Overview

The system now automatically notifies the **specific HR user assigned** to a case via **SMS and Email** when a worker/reporter uploads documents for review.

## How It Works

### 1. HR User Assignment

When an incident is escalated to HR (HOD report submitted):
- System determines routing type from facility type
- Uses **load-balancing** to assign to HR user with fewest pending cases
- Specific HR user ID stored in `incidents.routed_to_hr_user_id`

### 2. Document Upload Flow

When a worker uploads documents via the public track page (`/incidents/{id}/track`):

1. Worker selects documents and clicks "Upload"
2. Documents are validated and stored in `storage/app/public/incident-attachments/{incident_id}/`
3. `IncidentAttachment` records are created with status = `PENDING`
4. **Event is dispatched**: `IncidentDocumentUploaded` event fires for each document

### 3. Notification Dispatch

The `SendDocumentUploadNotification` listener handles the event:

1. **Verifies Incident Status**: Only sends if incident is in active HR review
2. **Gets Assigned HR User**: Retrieves the specific user from `routed_to_hr_user_id`
3. **Sends Notifications** to the assigned HR user only:
   - **Email**: Using `DocumentUploadedNotification` class
   - **SMS**: Via Econet SMS service (if phone number exists)
   - **Database**: Logs notification record with status tracking

### 3. Notification Content

**Email Subject:**
```
Documents Uploaded - HSC Reference: [INCIDENT_REFERENCE]
```

**Email Body includes:**
- Incident reference number
- Worker name
- Facility name
- Document type that was uploaded
- Link to review and verify documents in HR dashboard

**SMS Message (via Econet):**
```
HSC-OHS: New documents uploaded for incident [REF] - Worker: [NAME]. 
Please log in to review. Visit injury.hsc.org.zw
```

## Key Components

### 1. Event: `IncidentDocumentUploaded`
- **Location**: `app/Events/IncidentDocumentUploaded.php`
- **Properties**: incident, attachment, documentType, uploadCount
- **Dispatch**: Called after each document is created in `publicUploadDocuments()`

### 2. Notification: `DocumentUploadedNotification`
- **Location**: `app/Notifications/DocumentUploadedNotification.php`
- **Channels**: email, database
- **Content**: Formatted email with incident details and action link

### 3. Listener: `SendDocumentUploadNotification`
- **Location**: `app/Listeners/SendDocumentUploadNotification.php`
- **Actions**:
  - Gets the specific assigned HR user from `incident.routed_to_hr_user_id`
  - Skips if no HR user assigned yet
  - Sends email notification to assigned HR user only
  - Sends SMS notification if phone exists
  - Logs notification in `incident_notifications` table

### 4. HR User Assignment: `assignHRUserForIncident()`
- **Location**: `app/Http/Controllers/IncidentController.php`
- **Logic**: Load-balances cases to HR user with fewest pending assignments
- **Called**: When HOD report submitted (case escalated to HR)
- **Result**: Stores user ID in `incidents.routed_to_hr_user_id`

### 5. Registration: `AppServiceProvider`
- **Location**: `app/Providers/AppServiceProvider.php`
- **Wiring**: Event listener registered in `boot()` method

### 6. Database Field
- **Location**: Migration `2026_08_26_add_routed_to_hr_user_id_to_incidents.php`
- **Field**: `incidents.routed_to_hr_user_id` - stores the assigned HR user ID
- **Relationship**: `incident->assignedHRUser()` returns the User object

## Notification Status Tracking

All notifications are tracked in the `incident_notifications` table:

| Column | Value | Purpose |
|--------|-------|---------|
| notification_type | `DOCUMENT_UPLOAD` | Identifies type of notification |
| notification_channel | `email` | Primary channel (SMS logged separately) |
| status | `SENT` | Current status |
| recipient_user_id | [HR_USER_ID] | Which HR user was notified |
| sent_at | [TIMESTAMP] | When notification was sent |

## SMS Requirements

The system requires valid SMS credentials in `.env`:
```
SMS_ENABLED=true
ECONET_SMS_API_KEY=7be8bd35e16a66b5
ECONET_SMS_BASE_URL=https://mobilemessaging.econet.co.zw
ECONET_SMS_SENDER_ID="HSC SMS"
```

If SMS fails:
- Email notification still succeeds
- Failure is logged in Laravel logs
- Case processing continues normally

## Testing the System

### Test Case 1: Document Upload Notifies Assigned HR User

1. Submit HOD report on incident (escalates to HR)
2. Log in as the **assigned HR user** (check `routed_to_hr_user_id` in database)
3. Go to worker's public track page
4. Upload a document
5. **Expected**: Assigned HR user receives email + SMS notification (not all HR staff)

### Test Case 2: Load Balancing Works

1. System has 3 district_hr users
2. Submit 3 different incidents (HOD reports)
3. Check database - each should be assigned to different HR user
4. If one has fewer cases, next incident should go to them
5. **Expected**: Cases distributed evenly among HR staff

### Test Case 3: SMS Works When Assigned

1. Assigned HR user has valid phone number
2. Worker uploads document
3. **Expected**: Specific HR user receives SMS immediately

## Logging

All notifications logged to `storage/logs/laravel.log`:

**Success Log:**
```
[INFO] Document upload notifications sent successfully
incident_id: 45
reference_number: OHS-2026-08-001
document_type: doctors_report
hr_users_notified: 2
```

**Failure Log:**
```
[ERROR] Failed to send document upload notifications
incident_id: 45
error: [exception message]
```

## Configuration

No additional configuration needed. The system:
- Uses existing SMS service (`app/Services/SMSService.php`)
- Uses existing notification framework (Laravel Notifications)
- Uses existing incident routing logic
- Reads HR user assignments from role-based access control

## Future Enhancements

1. **SMS to SMS**: Add SMS as direct notification channel (not just fallback)
2. **HR Preferences**: Allow HR users to configure notification channels
3. **Batch Notifications**: Group multiple documents into single notification
4. **In-App Badges**: Show unread notification count in HR dashboard
5. **Delayed Notifications**: Aggregate notifications from same incident within time window
