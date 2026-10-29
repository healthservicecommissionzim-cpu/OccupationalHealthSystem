# Performance Monitoring Guide

## Overview

The Multi-Facility Roster Mapping system includes comprehensive performance monitoring to ensure cost attribution operations complete within the required 100ms threshold. This document describes the monitoring tools and procedures.

## Performance Requirements

- **Active Assignment Lookup**: Must complete within 50ms
- **Cost Attribution**: Must add < 100ms overhead to incident creation
- **Database Indexes**: All required indexes must be present and utilized

## Monitoring Tools

### 1. Performance Testing Command

Tests query performance and verifies database indexes are present.

```bash
php artisan roster:test-performance --iterations=100
```

**What it tests:**
- Active assignment lookup queries
- Cost attribution queries (including user lookup)
- Database index presence verification

**Output:**
- Average, minimum, maximum execution times
- 95th and 99th percentile timings
- Pass/Fail status against thresholds
- Index verification for all required tables

**When to use:**
- After deployment to production
- After database migrations
- When investigating performance issues
- During load testing

### 2. Cache Warming Command

Pre-caches active assignment queries for optimal performance.

```bash
php artisan roster:warm-cache --days=7
```

**What it does:**
- Queries all active roster assignments for a date range
- Pre-populates cache with assignment data
- Improves incident creation performance

**Parameters:**
- `--days`: Number of days before and after today to warm cache for (default: 7)

**Scheduled execution:**
- Runs daily at 1:00 AM via Laravel scheduler
- Configured in `app/Console/Kernel.php`

**When to run manually:**
- After bulk roster imports
- After assignment modifications
- Before expected high-volume incident periods

### 3. Attribution Metrics Command

Analyzes cost attribution performance from application logs.

```bash
php artisan roster:metrics --lines=100 --threshold=100
```

**What it analyzes:**
- Execution times for all cost attribution calls
- Success/failure rates
- Threshold violations
- Performance trends

**Parameters:**
- `--lines`: Number of log lines to analyze (default: 100)
- `--threshold`: Performance threshold in ms (default: 100)

**Output:**
- Total attributions count
- Success/failure statistics
- Average, min, max, p95, p99 execution times
- List of slow attributions that exceeded threshold

**When to use:**
- Daily performance review
- After production deployments
- When investigating performance degradation
- During capacity planning

## Performance Logging

### Automatic Logging

The `CostAttributionEngine` automatically logs performance metrics for every attribution:

**Success Log (within threshold):**
```
[INFO] Cost attribution completed
{
    "execution_time_ms": 45.23,
    "user_id": 123,
    "facility_id": 5,
    "date": "2026-10-27",
    "success": true,
    "threshold_ms": 100
}
```

**Warning Log (exceeded threshold):**
```
[WARNING] Cost attribution engine exceeded performance threshold
{
    "execution_time_ms": 152.67,
    "user_id": 456,
    "facility_id": 12,
    "date": "2026-10-27",
    "success": true,
    "threshold_ms": 100,
    "threshold_exceeded_by_ms": 52.67
}
```

**Error Log (attribution failed):**
```
[ERROR] Cost attribution engine failed
{
    "user_id": 789,
    "facility_id": 8,
    "date": "2026-10-27",
    "error": "Connection timeout"
}
```

### Log Location

All logs are written to: `storage/logs/laravel.log`

## Performance Thresholds

### Critical Thresholds

| Metric | Threshold | Action if Exceeded |
|--------|-----------|-------------------|
| Average attribution time | 100ms | Investigate immediately |
| 95th percentile | 100ms | Review within 24 hours |
| 99th percentile | 150ms | Monitor trend |
| Failure rate | 1% | Investigate immediately |

### Warning Indicators

- More than 5% of attributions exceed threshold
- Maximum time > 500ms
- Increasing trend in average execution time
- Database index missing warnings

## Optimization Strategies

### When Performance Degrades

1. **Verify Indexes**
   ```bash
   php artisan roster:test-performance
   ```
   Ensure all required indexes are present.

2. **Warm Cache**
   ```bash
   php artisan roster:warm-cache --days=14
   ```
   Pre-populate cache with assignment data.

3. **Check Cache Configuration**
   - Verify cache driver is configured (Redis recommended)
   - Check cache connection performance
   - Ensure cache TTL is appropriate (5 minutes)

4. **Analyze Slow Queries**
   ```bash
   php artisan roster:metrics --lines=500
   ```
   Identify patterns in slow attributions.

5. **Database Optimization**
   - Run `ANALYZE TABLE` on roster tables
   - Check for table fragmentation
   - Verify MySQL/MariaDB query cache settings

6. **Review Assignment Data**
   - Check for excessive overlapping assignments
   - Verify data integrity (no orphaned records)
   - Consider archiving old assignments

## Monitoring Dashboard Integration

### Key Metrics to Track

1. **Attribution Performance**
   - Average execution time (target: < 50ms)
   - p95 execution time (target: < 100ms)
   - Threshold violations per day

2. **Cache Performance**
   - Cache hit rate (target: > 95%)
   - Cache misses per hour
   - Cache eviction rate

3. **Database Performance**
   - Active assignment query time
   - Cost center lookup query time
   - Index usage statistics

4. **System Health**
   - Attribution success rate (target: > 99%)
   - Fallback attribution rate
   - Error rate

## Alerting Rules

### Critical Alerts (Immediate Action)

- Average attribution time > 150ms for 5 minutes
- Attribution failure rate > 5% for 1 minute
- Required database index missing
- Cache service unavailable

### Warning Alerts (Review Within 24 Hours)

- Average attribution time > 100ms for 15 minutes
- p95 > 150ms for 1 hour
- Cache hit rate < 90% for 1 hour
- More than 10 threshold violations per hour

## Troubleshooting Guide

### Problem: Slow Attribution Times

**Symptoms:**
- Average > 100ms
- Frequent threshold warnings in logs

**Diagnosis:**
1. Check database indexes: `php artisan roster:test-performance`
2. Check cache hit rate in application metrics
3. Review slow query log

**Solutions:**
- Warm cache: `php artisan roster:warm-cache`
- Verify Redis/cache service is running
- Add missing indexes if any
- Optimize database server resources

### Problem: High Failure Rate

**Symptoms:**
- Attribution errors in logs
- Incidents defaulting to non-rotational attribution

**Diagnosis:**
1. Check error logs for specific failures
2. Verify database connectivity
3. Check for data integrity issues

**Solutions:**
- Fix database connection issues
- Repair corrupted roster data
- Increase timeout values if network latency is high

### Problem: Cache Not Working

**Symptoms:**
- All queries hitting database
- Slow attribution despite good indexes

**Diagnosis:**
1. Check cache driver configuration in `.env`
2. Verify cache service is running (Redis/Memcached)
3. Check cache logs for errors

**Solutions:**
- Restart cache service
- Verify cache credentials
- Switch to database cache driver as fallback

## Best Practices

1. **Regular Monitoring**
   - Run `roster:metrics` daily
   - Review performance trends weekly
   - Set up automated alerts

2. **Proactive Cache Warming**
   - Run before bulk incident imports
   - Schedule more frequently during peak periods
   - Extend date range during rotation season

3. **Database Maintenance**
   - Run performance tests after migrations
   - Analyze tables monthly
   - Monitor index usage

4. **Capacity Planning**
   - Track attribution volume trends
   - Scale cache resources before peak periods
   - Plan for roster assignment growth

## Command Reference

| Command | Purpose | Frequency |
|---------|---------|-----------|
| `roster:test-performance` | Verify performance and indexes | On-demand, after migrations |
| `roster:warm-cache` | Pre-populate cache | Daily (scheduled), before peaks |
| `roster:metrics` | Analyze performance logs | Daily, during investigation |

## Integration with Existing Monitoring

The roster performance monitoring integrates seamlessly with the existing incident management system:

- **Non-invasive**: Monitoring adds < 1ms overhead
- **Graceful degradation**: System continues if monitoring fails
- **Structured logging**: JSON format for easy parsing
- **Standard Laravel logs**: Uses existing log infrastructure

## Support

For performance issues or questions:
1. Check this guide first
2. Run diagnostic commands
3. Review application logs
4. Contact system administrator with metrics output
