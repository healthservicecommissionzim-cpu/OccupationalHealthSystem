# Leave Management System - Migrations Execution Checklist

## Overview
This document outlines the steps required to execute the Leave Management System migrations and verify successful deployment.

## Pre-Deployment Steps

- [ ] **Backup Database**: Create a full backup of the production database before running migrations
- [ ] **Test Environment**: Run migrations on staging/test environment first
- [ ] **Verify PHP Version**: Ensure PHP 8.1+ is installed (`php --version`)
- [ ] **Check Database Connection**: Verify database credentials in `.env` file
- [ ] **Review Migrations**: Read through all migration files to understand changes

## Migrations to Execute

### 1. Add Leave Management Fields to Incidents Table
**File**: `database/migrations/2026_09_15_add_leave_management_fields_to_incidents_table.php`
**Purpose**: Add 7 new columns to track leave data and HR assignment
**Impact**: Non-destructive (all columns nullable or have defaults)
**Execution Order**: First

```bash
php artisan migrate --path=database/migrations/2026_09_15_add_leave_management_fields_to_incidents_table.php
```

**Columns Added**:
- `sick_leave_start_date` (DATE, nullable)
- `medical_certificate_provided` (BOOLEAN, default FALSE)
- `expected_recovery_date` (DATE, nullable)
- `leave_payment_stage` (ENUM: FULL_PAY, HALF_PAY, nullable)
- `leave_status` (VARCHAR, default ACTIVE)
- `leave_information_saved_at` (TIMESTAMP, nullable)
- `saved_by_user_id` (BIGINT UNSIGNED, nullable, foreign key)

**Indexes Added**:
- `idx_sick_leave_start_date` on `sick_leave_start_date`
- `idx_leave_payment_stage` on `leave_payment_stage`
- `idx_leave_status` on `leave_status`

### 2. Create Incident Leave Milestones Table
**File**: `database/migrations/2026_09_15_create_incident_leave_milestones_table.php`
**Purpose**: Track 90-day and 180-day milestone flags for each case
**Impact**: Creates new table, no existing data affected
**Execution Order**: Second (depends on incidents table)

```bash
php artisan migrate --path=database/migrations/2026_09_15_create_incident_leave_milestones_table.php
```

**Table Structure**:
- `id` (BIGINT UNSIGNED, primary key)
- `incident_id` (BIGINT UNSIGNED, foreign key → incidents.id)
- `milestone_type` (ENUM: 90_DAY, 180_DAY)
- `days_elapsed_at_flag` (INT)
- `flag_reason` (VARCHAR)
- `applied_by_system` (BOOLEAN, default TRUE)
- `applied_by_user_id` (BIGINT UNSIGNED, nullable, foreign key → users.id)
- `applied_at` (TIMESTAMP, default CURRENT_TIMESTAMP)
- `notification_sent_at` (TIMESTAMP, nullable)
- `created_at` (TIMESTAMP, default CURRENT_TIMESTAMP)

**Indexes Added**:
- `idx_milestone_type` on `milestone_type`
- `idx_applied_at` on `applied_at`

## Full Migration Sequence

```bash
# Run both migrations together
php artisan migrate

# Or run individually in order
php artisan migrate --path=database/migrations/2026_09_15_add_leave_management_fields_to_incidents_table.php
php artisan migrate --path=database/migrations/2026_09_15_create_incident_leave_milestones_table.php
```

## Rollback Instructions

If issues occur, rollback migrations in reverse order:

```bash
# Rollback last 2 migrations
php artisan migrate:rollback

# Or rollback specific migrations
php artisan migrate:rollback --path=database/migrations/2026_09_15_create_incident_leave_milestones_table.php
php artisan migrate:rollback --path=database/migrations/2026_09_15_add_leave_management_fields_to_incidents_table.php
```

## Post-Migration Verification

- [ ] **Check Migration Status**: Run `php artisan migrate:status` and verify both migrations show "Ran"
- [ ] **Verify Table Structure**: 
  ```sql
  DESCRIBE incidents; -- verify new columns exist
  DESCRIBE incident_leave_milestones; -- verify new table exists
  ```
- [ ] **Verify Indexes**: Confirm all indexes were created
- [ ] **Test Data Integrity**: Run SELECT queries to ensure existing data wasn't corrupted
- [ ] **Test Leave Capture**: Manually test leaving capturing in HR dossier
- [ ] **Test Milestone Check**: Run scheduler manually to verify milestone logic works

## Manual Database Verification Queries

### Verify Incidents Table Changes
```sql
SELECT 
    COLUMN_NAME, 
    COLUMN_TYPE, 
    IS_NULLABLE, 
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'incidents' 
  AND COLUMN_NAME IN (
    'sick_leave_start_date',
    'medical_certificate_provided',
    'expected_recovery_date',
    'leave_payment_stage',
    'leave_status',
    'leave_information_saved_at',
    'saved_by_user_id'
  );
```

### Verify Milestones Table Exists
```sql
SELECT * FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_NAME = 'incident_leave_milestones';
```

### Verify Indexes
```sql
SELECT * FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_NAME IN ('incidents', 'incident_leave_milestones')
  AND INDEX_NAME IN (
    'idx_sick_leave_start_date',
    'idx_leave_payment_stage',
    'idx_leave_status',
    'idx_milestone_type',
    'idx_applied_at'
  );
```

## Configuration Requirements

No additional configuration needed. The system uses:
- Existing database connection configured in `.env`
- Existing audit log system (no new configuration)
- Existing SMS service (already configured)
- Existing notification framework (Laravel Notifications)

## Testing After Deployment

### Test 1: HR Leave Capture
1. Navigate to a case in HR Document Verification stage
2. Fill in leave information form
3. Verify data saves without errors
4. Check database: `SELECT * FROM incidents WHERE reference_number = 'OHS-XX-XXXXX'`

### Test 2: Milestone Flagging
1. Create test incident with leave start date 91 days ago
2. Run scheduler manually: `php artisan schedule:run`
3. Verify incident status changed to REFERRED_TO_MEDICAL_BOARD
4. Check database: `SELECT * FROM incident_leave_milestones WHERE incident_id = XX`

### Test 3: Medical Board Decision
1. Go to Medical Board dashboard
2. Review flagged case
3. Submit decision
4. Verify status changed appropriately
5. Check audit log: `SELECT * FROM audit_logs WHERE incident_id = XX AND action = 'medical_board_decision'`

## Deployment Checklist

### Pre-Deployment
- [ ] Database backup created
- [ ] Migrations tested on staging environment
- [ ] All PHP dependencies installed
- [ ] `.env` database credentials verified
- [ ] Application cache cleared

### Deployment
- [ ] Stop supervisor/queue workers (if running background jobs)
- [ ] Put application in maintenance mode: `php artisan down`
- [ ] Run migrations: `php artisan migrate`
- [ ] Clear caches: `php artisan cache:clear`
- [ ] Restart supervisor/queue workers
- [ ] Take application out of maintenance mode: `php artisan up`

### Post-Deployment
- [ ] Verify all migrations ran successfully
- [ ] Test HR leave capture
- [ ] Test Medical Board dashboard
- [ ] Monitor logs for errors: `tail -f storage/logs/laravel.log`
- [ ] Verify scheduler running: `php artisan schedule:work` (or check cron)

## Rollback Procedure

If critical issues occur:

1. Put application in maintenance mode: `php artisan down`
2. Backup current database state
3. Rollback migrations: `php artisan migrate:rollback`
4. Verify rollback successful: `php artisan migrate:status`
5. Verify data integrity with manual queries
6. Take application out of maintenance mode: `php artisan up`
7. Notify stakeholders of rollback and plan remediation

## Support Contacts

For migration issues:
- Database Administrator: [contact]
- System Administrator: [contact]
- Development Team: [contact]

## Notes

- Migrations are idempotent and safe to run multiple times
- All changes are forward-compatible with existing incident workflow
- No breaking changes to existing APIs or UI
- Leave fields only populated when HR explicitly captures leave information
- Milestone logic only executes when leave_start_date is set
