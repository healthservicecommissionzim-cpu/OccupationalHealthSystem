# Leave Management System - Configuration Documentation

## Overview

The Leave Management System requires minimal configuration as it integrates with existing HSC-OHS systems. This document outlines all configuration options and requirements.

## Environment Variables (.env)

The Leave Management System uses the following existing environment variables. No new variables are required.

### SMS Service Configuration (for milestone notifications)

```env
# SMS Service Settings (Already Configured)
SMS_ENABLED=true
ECONET_SMS_API_KEY=7be8bd35e16a66b5
ECONET_SMS_BASE_URL=https://mobilemessaging.econet.co.zw
ECONET_SMS_SENDER_ID="HSC SMS"

# Mail Service Settings (for leave milestone notifications)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@hsc.org.zw
MAIL_FROM_NAME="HSC Occupational Health System"

# Database Connection
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=occupational_health
DB_USERNAME=root
DB_PASSWORD=
```

### Administrator Email Configuration

For milestone summary emails (daily scheduler):

```env
# Admin Email for Leave Management Summaries
ADMIN_EMAIL=admin@hsc.org.zw
LEAVE_MANAGEMENT_ADMIN_EMAIL=hr-management@hsc.org.zw
```

## Scheduler Configuration

### Kernel Configuration

The leave management scheduler is configured in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Check leave milestones daily at 2:00 AM
    $schedule->job(new CheckLeaveMilestonesJob)
        ->dailyAt('02:00')
        ->withoutOverlapping()
        ->onOneServer();
}
```

### Scheduler Setup for Production

**Option 1: Linux Cron Job**

Add this line to crontab (`sudo crontab -e`):

```bash
* * * * * cd /path/to/occupational-health && php artisan schedule:run >> /dev/null 2>&1
```

**Option 2: Supervisor Process** (Recommended for background jobs)

Create `/etc/supervisor/conf.d/occupational-health-queue.conf`:

```ini
[program:occupational-health-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/occupational-health/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/occupational-health/queue.log
stopasgroup=true
```

Then restart supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start occupational-health-queue:*
```

**Option 3: Cron with Laravel's Dedicated Process Manager**

```bash
# Run schedule every minute
* * * * * cd /path/to/occupational-health && php artisan schedule:run >> /dev/null 2>&1

# OR use artisan schedule worker
php artisan schedule:work
```

### Verify Scheduler is Running

```bash
# Check if cron job is registered
crontab -l | grep "artisan schedule:run"

# Or check supervisor status
sudo supervisorctl status occupational-health-queue

# Test scheduler manually
php artisan schedule:run

# Monitor scheduler logs
tail -f storage/logs/laravel.log | grep "CheckLeaveMilestonesJob"
```

## Email Configuration

### Milestone Notification Emails

Leave milestone notifications are sent to:
- **HR User** (assigned to the case via `routed_to_hr_user_id`)
- **Medical Officer** (PMD or CMO based on facility type escalation)

Email templates are located in:
- `resources/views/emails/leave-milestone-notification.blade.php` - Milestone notification email
- `resources/views/emails/leave-milestone-summary.blade.php` - Daily scheduler summary for admin
- `resources/views/emails/leave-job-failure-alert.blade.php` - Scheduler failure alert

### Email Configuration in AppServiceProvider

Email sending is configured in `app/Providers/AppServiceProvider.php`:

```php
// Milestone notifications sent via email
Notification::route('mail', $medicalOfficer->email)
    ->notify(new LeaveMilestoneNotification($incident, $milestone));
```

### Testing Email Configuration

```bash
# Send test email
php artisan tinker
>>> Mail::to('test@example.com')->send(new \App\Mail\LeaveMilestoneNotification(...));

# Or from artisan command
php artisan mail:send
```

## SMS Configuration

### SMS Service Settings

The SMS service uses Econet Zimbabwe API. Configuration is in `app/Services/SMSService.php`:

```php
class SMSService
{
    protected $apiKey = env('ECONET_SMS_API_KEY');
    protected $baseUrl = env('ECONET_SMS_BASE_URL');
    protected $senderId = env('ECONET_SMS_SENDER_ID');
}
```

### SMS Message Templates

SMS messages are sent in addition to emails when milestone notifications are dispatched:

**Template**: `app/Listeners/SendMilestoneNotifications.php`

```
HSC-OHS: Incident [REFERENCE] milestone reached. 
Days elapsed: [DAYS]. Payment stage: [STAGE]. 
Please log in to review. [LINK]
```

### Testing SMS Configuration

```bash
# Test SMS delivery
php artisan tinker
>>> app(\App\Services\SMSService::class)->send('+263712345678', 'Test message');
```

## Event Listener Registration

### AppServiceProvider Configuration

Event listeners are registered in `app/Providers/AppServiceProvider.php`:

```php
public function boot(): void
{
    // Leave management milestone notifications
    Event::listen(
        Case90DayMilestoneReached::class,
        SendMilestoneNotifications::class
    );
    
    Event::listen(
        Case180DayMilestoneReached::class,
        SendMilestoneNotifications::class
    );
}
```

## Audit Logging Configuration

Leave management actions are automatically logged to the `audit_logs` table with:
- `action`: leave_information_saved, milestone_flag_applied, medical_board_decision
- `incident_id`: Which incident was modified
- `changed_by_user_id`: Who made the change (NULL if system)
- `old_values`: JSON of before state
- `new_values`: JSON of after state
- `metadata`: JSON containing additional context

No additional configuration needed—audit logging uses the existing system.

## Role-Based Access Control

The Leave Management System respects existing role-based permissions:

### HR User Roles
- Can view leave capture form in HR dossier
- Can submit leave information for cases
- Receive email/SMS notifications when milestones reached

### Medical Board Roles
- Can access Medical Board dashboard
- Can review flagged cases
- Can submit medical board decisions

### PMD/CMO Roles
- Receive milestone notification emails/SMS
- Can review cases referred for medical board review

### Super Admin Role
- Can access all dashboards and reports
- Can manage system configuration
- Can view all audit logs

No role configuration changes required—system integrates with existing permissions.

## Database Configuration

### Connection Pool Settings

For high-traffic scenarios, adjust connection pool settings in `config/database.php`:

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', 3306),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    ]) : [],
    'pool' => [
        'min' => 5,
        'max' => 20,
    ],
],
```

### Query Optimization

Indexes are automatically created on:
- `incidents.sick_leave_start_date` - For leave dashboard queries
- `incidents.leave_payment_stage` - For payment stage filtering
- `incidents.leave_status` - For active leave queries
- `incident_leave_milestones.milestone_type` - For milestone queries
- `incident_leave_milestones.applied_at` - For chronological sorting

## Cache Configuration

For dashboard performance, consider enabling query caching in `config/cache.php`:

```php
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
    ],
],
```

Cache leave dashboard statistics:

```php
// In LeaveManagementDashboardController
$stats = Cache::remember('leave_dashboard_stats', 3600, function () {
    return [
        'total_active' => Incident::where('leave_status', 'ACTIVE')->count(),
        // ... more stats
    ];
});
```

## Logging Configuration

Leave management actions are logged to `storage/logs/laravel.log`:

Configure logging in `config/logging.php`:

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
        'ignore_fill_exception_codes' => [
            // exception codes to ignore
        ],
    ],
],
```

### Log Levels

Leave management uses:
- **INFO**: Successful leave capture, milestone flagging, decisions
- **WARNING**: Validation warnings, SMS delivery failures
- **ERROR**: Critical failures (database errors, notification failures)

Monitor logs:
```bash
tail -f storage/logs/laravel.log | grep "leave\|milestone\|medical_board"
```

## Security Configuration

### Data Protection

All leave information is encrypted at rest using Laravel's built-in encryption (if enabled):

```env
APP_KEY=base64:your-encryption-key-here
```

### Access Control

Leave data is protected by existing HSC-OHS access control:
- Only assigned HR user can view case documents
- Only Medical Board members can access Medical Board dashboard
- Audit logs track all access and modifications

### CSRF Protection

All forms include CSRF tokens:
```html
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

## Performance Tuning

### Dashboard Query Optimization

For large datasets, use pagination (default 20 per page):

```php
// In LeaveManagementDashboardController
$incidents = $query->paginate(20);
```

### Scheduler Optimization

For systems with thousands of cases, split the milestone check:

```php
// Process in batches
$incidents = Incident::whereNotNull('sick_leave_start_date')
    ->where('leave_status', 'ACTIVE')
    ->chunk(100, function ($batch) {
        foreach ($batch as $incident) {
            // Process milestone check
        }
    });
```

### Memory Limits

Ensure PHP memory limit is sufficient for large batch operations:

```php
// In php.ini
memory_limit = 256M

// Or temporarily in code
ini_set('memory_limit', '512M');
```

## Troubleshooting

### Scheduler Not Running

1. Check cron job: `crontab -l`
2. Verify Laravel installation: `php artisan list`
3. Check logs: `tail -f storage/logs/laravel.log`
4. Test manually: `php artisan schedule:run`

### Email Not Sending

1. Verify MAIL_* environment variables in `.env`
2. Test mail configuration: `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('test@example.com'));`
3. Check logs for mail driver errors
4. Verify mail service is running (if using local testing)

### SMS Not Sending

1. Verify Econet credentials in `.env`
2. Check SMS service logs
3. Test API connectivity: `php artisan tinker` → `app(\App\Services\SMSService::class)->send(...)`

### Database Errors

1. Verify database connection in `.env`
2. Run migrations: `php artisan migrate --status`
3. Check migration logs: `php artisan migrate:status`

## Support and Maintenance

### Monitoring

Set up alerts for:
- Scheduler failures (check logs daily)
- Email delivery failures (monitor bounce rates)
- SMS delivery failures (check SMSService logs)
- Database query performance (monitor slow query log)

### Backups

Include leave management tables in database backups:
- `incidents` (leave fields)
- `incident_leave_milestones`
- `audit_logs`

### Updates

When updating the system:
1. Run migrations
2. Clear caches: `php artisan cache:clear`
3. Restart scheduler/queue workers
4. Monitor logs for errors

---

**Last Updated**: September 15, 2026  
**Version**: 1.0.0  
**Maintenance**: Monitor logs weekly, verify scheduler daily
