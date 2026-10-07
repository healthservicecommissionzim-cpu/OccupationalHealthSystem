# Return-to-Work (RTW) Tracking System

## Overview

The Return-to-Work Tracking system enables supervisors to monitor and manage workers' gradual return to full duties following injury or medical restrictions. It provides comprehensive tracking of medical restrictions, modified duties, work schedule progression, and review dates.

## Database Schema

### RTW Fields Added to `incidents` Table

```sql
-- Timeline
rtw_start_date                    DATE      -- When modified duties began
rtw_end_date                      DATE      -- Expected full return date

-- Medical & Duties Information
rtw_medical_restrictions         TEXT      -- JSON: Doctor-imposed restrictions
rtw_modified_duties              TEXT      -- JSON: Specific transitional tasks
rtw_schedule                     TEXT      -- JSON: Graduated work hours progression
rtw_accommodations               TEXT      -- Physical/equipment accommodations

-- Review Tracking
rtw_last_review_date             DATE      -- Last supervisor review
rtw_next_review_date             DATE      -- Next scheduled review
rtw_status                       ENUM      -- Current RTW status
rtw_review_notes                 TEXT      -- Notes from reviews

-- Documentation Status
rtw_doctor_clearance_received    BOOLEAN   -- Doctor approval obtained
rtw_plan_signed_off              BOOLEAN   -- All stakeholders signed
```

### RTW Status Values

- `not_started` - RTW plan not yet initiated
- `active` - RTW in progress
- `on_track` - Worker progressing as planned
- `requires_intervention` - Issues detected, needs attention
- `completed` - Worker returned to full duties
- `terminated` - RTW plan ended (early/unsuccessful)

## Migration

Run the migration to add RTW fields:

```bash
php artisan migrate
```

Migration file: `database/migrations/2026_09_09_add_return_to_work_tracking_to_incidents_table.php`

## Model Methods

### Incident Model Methods

```php
// Get days since RTW started
$incident->getRTWDaysElapsed()           // Returns: int|null

// Get days until full return
$incident->getRTWDaysRemaining()         // Returns: int|null

// Get RTW progress percentage (0-100)
$incident->getRTWProgressPercentage()    // Returns: int|null

// Check if review is overdue
$incident->isRTWReviewOverdue()          // Returns: bool

// Get medical restrictions array
$incident->getMedicalRestrictions()      // Returns: array

// Get modified duties array
$incident->getModifiedDuties()           // Returns: array

// Get work schedule progression
$incident->getWorkSchedule()             // Returns: array

// Get status color code for UI
$incident->getRTWStatusColor()           // Returns: 'slate'|'blue'|'green'|'orange'|'emerald'|'red'

// Get formatted status label
$incident->getRTWStatusLabel()           // Returns: 'Active', 'On Track', etc.
```

## Routes & Controllers

### Supervisor Routes

```
GET /supervisor/reports/return-to-work-tracking  →  DashboardController@supervisorReturnToWorkTracking
```

### Controller Method

```php
public function supervisorReturnToWorkTracking()
{
    // Returns paginated RTW cases assigned to current supervisor
    // Filters: incidents with RTW data or injury leave info
    // Order: by status (not_started first), then next review date
}
```

## View Template

Location: `resources/views/supervisor/return_to_work_tracking.blade.php`

### Display Elements

**Summary Cards:**
- Total RTW Cases
- Active Cases
- Requires Intervention
- Completed Cases

**Main Table Columns:**
1. Reference Number
2. Worker Name & ID
3. Facility
4. RTW Status (with overdue indicator)
5. Progress Bar (% complete)
6. Days Elapsed / Days Remaining
7. Next Review Date
8. View Details Action

**Expandable Details:**
- Medical Restrictions list
- Modified Duties list
- Work Schedule Progression
- Accommodations & Documentation Status
- Review Notes
- Last/Next Review Dates

## Data Entry

When setting up RTW data, store JSON for complex fields:

```php
// Medical Restrictions Example
$incident->rtw_medical_restrictions = json_encode([
    'Cannot lift more than 5kg',
    'Must sit for 30 minutes every hour',
    'Avoid heights and confined spaces',
]);

// Modified Duties Example
$incident->rtw_modified_duties = json_encode([
    'Administrative data entry',
    'Light clerical work',
    'Telephone reception duties',
]);

// Work Schedule Example
$incident->rtw_schedule = json_encode([
    'Week 1' => '4 hours per day',
    'Week 2' => '6 hours per day',
    'Week 3' => '8 hours per day (full time)',
]);
```

## Testing RTW System

### Seed Test Data

```bash
php artisan db:seed --class=ReturnToWorkSeeder
```

This populates 5 random incidents with sample RTW data.

### Manual Test Cases

**Test 1: View RTW Dashboard**
1. Log in as Supervisor
2. Navigate to `/supervisor/reports/return-to-work-tracking`
3. Verify: Summary cards show data, table displays RTW cases
4. Expected: Cases sorted by status then review date

**Test 2: View Case Details**
1. Click "View" on any RTW case
2. Verify: Details row expands showing all RTW information
3. Check: Medical restrictions, duties, schedule all display correctly
4. Expected: Progress bar shows percentage, dates formatted correctly

**Test 3: Review Overdue Detection**
1. Find case where `rtw_next_review_date` < today
2. Verify: "⚠ Review Overdue" warning displays
3. Check: Review date shows in orange with overdue indicator
4. Expected: Alert visible to prompt supervisor action

**Test 4: Progress Calculation**
1. Open case with RTW dates set
2. Verify: Progress % calculated correctly
3. Formula: (days_elapsed / total_days) × 100
4. Expected: Progress increases daily, reaches 100% on end date

## API/Programmatic Usage

```php
// Get incidents requiring RTW review today
$overdueReviews = Incident::where('rtw_status', 'active')
    ->where('rtw_next_review_date', '<=', today())
    ->get();

// Get incidents with high progress
$nearCompletion = Incident::where('rtw_status', 'on_track')
    ->whereBetween('rtw_end_date', [now(), now()->addDays(7)])
    ->get();

// Update RTW status after review
$incident->update([
    'rtw_status' => 'on_track',
    'rtw_last_review_date' => now(),
    'rtw_next_review_date' => now()->addWeeks(2),
    'rtw_review_notes' => 'Progress excellent. No pain reported. Increase hours next week.',
]);
```

## Key Features

✓ **Progress Tracking** - Visual progress bars showing days elapsed vs. remaining
✓ **Medical Restrictions** - Clearly list doctor-imposed limitations
✓ **Graduated Schedules** - Track week-by-week work hour progression
✓ **Review Management** - Track review dates and alert on overdue reviews
✓ **Documentation Status** - Verify doctor clearance and stakeholder sign-off
✓ **Intervention Alerts** - Flag cases requiring immediate attention
✓ **Audit Trail** - Track review notes and decision history

## Future Enhancements

1. **RTW Plan Upload** - Allow PDF/document upload for formal RTW plans
2. **Email Notifications** - Alert supervisors of upcoming reviews
3. **Worker Portal** - Let workers view their own RTW status and restrictions
4. **HR Dashboard** - Aggregate RTW metrics across organization
5. **Comparative Analysis** - Track RTW success rates and typical duration
6. **Integration with Payroll** - Auto-adjust hours in payroll system based on schedule
7. **SMS Reminders** - Send review date reminders to supervisors
8. **Accommodation Tracking** - Track accommodation costs and usage

## Related Systems

- **Injury Leave Tracking** - `injury_leave_start_date`, `injury_leave_end_date`, `total_injury_leave_days`
- **Document Upload Notifications** - Notifies assigned HR user when worker uploads documents
- **Incident Status Workflow** - RTW is part of overall incident lifecycle
- **Audit Logging** - All RTW changes logged for compliance
