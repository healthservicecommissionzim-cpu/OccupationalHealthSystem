<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class ForensicAuditService
{
    /**
     * Log a forensic audit event with complete context.
     */
    public static function log(array $data = []): ?AuditLog
    {
        try {
            $user = auth()->user();
            $request = request();
            
            $auditLog = new AuditLog();
            $auditLog->event_id = 'evt_' . \Illuminate\Support\Str::ulid();
            $auditLog->correlation_id = \Illuminate\Support\Str::uuid();
            $auditLog->request_id = 'req_' . \Illuminate\Support\Str::ulid();
            $auditLog->user_id = $user?->id;
            $auditLog->action = $data['action'] ?? 'UNKNOWN';
            $auditLog->component = $data['component'] ?? 'Unknown';
            $auditLog->affected_entity = $data['affected_entity'] ?? null;
            $auditLog->http_method = $request->method();
            $auditLog->http_code = $data['http_code'] ?? 200;
            $auditLog->status = ($data['http_code'] ?? 200) >= 200 && ($data['http_code'] ?? 200) < 300 ? 'SUCCESS' : 'FAILED';
            $auditLog->error_message = $data['error_message'] ?? null;
            $auditLog->resource_type = $data['resource_type'] ?? null;
            $auditLog->resource_path = $data['resource_path'] ?? $request->path();
            $auditLog->auditable_type = $data['auditable_type'] ?? null;
            $auditLog->auditable_id = $data['auditable_id'] ?? null;
            $auditLog->ip_address = $request->ip() ?? '0.0.0.0';
            $auditLog->ip_version = strpos($auditLog->ip_address, ':') !== false ? 'IPv6' : 'IPv4';
            $auditLog->user_agent = $request->userAgent();
            $auditLog->classification = $data['classification'] ?? 'ROUTINE';
            $auditLog->retention_policy = $data['retention_policy'] ?? 'STANDARD_1_YEAR';
            $auditLog->timestamp_utc = now();
            
            $auditLog->save();
            
            \Illuminate\Support\Facades\Log::info('Audit log created', [
                'event_id' => $auditLog->event_id,
                'action' => $auditLog->action,
                'user_id' => $auditLog->user_id,
            ]);
            
            return $auditLog;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Forensic audit logging failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'action' => $data['action'] ?? 'UNKNOWN',
            ]);
            return null;
        }
    }

    /**
     * Log a user approval action with full context.
     */
    public static function logUserApproval(array $data = []): AuditLog
    {
        return self::log(array_merge([
            'action' => 'USER_APPROVED',
            'classification' => 'AUDIT_CRITICAL',
            'requires_notification' => true,
        ], $data));
    }

    /**
     * Log a user rejection with full context.
     */
    public static function logUserRejection(array $data = []): AuditLog
    {
        return self::log(array_merge([
            'action' => 'USER_REJECTED',
            'classification' => 'AUDIT_CRITICAL',
            'requires_notification' => true,
        ], $data));
    }

    /**
     * Log a role assignment with full context.
     */
    public static function logRoleAssignment(array $data = []): AuditLog
    {
        return self::log(array_merge([
            'action' => 'ROLE_ASSIGNMENT',
            'classification' => 'AUDIT_CRITICAL',
            'requires_notification' => true,
        ], $data));
    }

    /**
     * Log a permission change.
     */
    public static function logPermissionChange(array $data = []): AuditLog
    {
        return self::log(array_merge([
            'action' => 'PERMISSION_CHANGE',
            'classification' => 'SECURITY_SENSITIVE',
        ], $data));
    }

    /**
     * Log a data access event.
     */
    public static function logDataAccess(array $data = []): AuditLog
    {
        return self::log(array_merge([
            'action' => 'DATA_ACCESS',
            'classification' => 'ROUTINE',
        ], $data));
    }

    /**
     * Get formatted audit report for compliance.
     */
    public static function getForensicReport(AuditLog $log): array
    {
        return [
            'forensic_summary' => [
                'event_id' => $log->event_id,
                'request_id' => $log->request_id,
                'correlation_id' => $log->correlation_id,
                'timestamp_utc' => $log->timestamp_utc->toIso8601String(),
                'timestamp_readable' => $log->timestamp_utc->format('Y-m-d H:i:s.u') . ' UTC',
            ],
            'actor' => [
                'user_id' => $log->user_id,
                'username' => $log->user->username ?? $log->user->email ?? 'SYSTEM',
                'real_name' => $log->user->name ?? 'SYSTEM',
                'impersonated_by' => $log->impersonated_by_user_id ? [
                    'user_id' => $log->impersonated_by_user_id,
                    'username' => $log->impersonatedByUser->username ?? $log->impersonatedByUser->email ?? 'UNKNOWN',
                ] : null,
            ],
            'action' => [
                'type' => $log->action,
                'component' => $log->component,
                'resource_type' => $log->resource_type,
                'affected_entity' => $log->affected_entity,
                'description' => "User performed {$log->action} action via {$log->component}",
            ],
            'source' => [
                'ip_address' => $log->ip_address,
                'ip_version' => $log->ip_version,
                'hostname' => $log->hostname,
                'user_agent' => $log->user_agent,
                'geo_location' => $log->geo_location,
                'geo_coordinates' => [
                    'country' => $log->geo_country,
                    'city' => $log->geo_city,
                    'latitude' => $log->geo_latitude,
                    'longitude' => $log->geo_longitude,
                ],
            ],
            'http_context' => [
                'method' => $log->http_method,
                'status_code' => $log->http_code,
                'path' => $log->resource_path,
                'duration_ms' => $log->duration_ms,
            ],
            'data_change' => [
                'status' => $log->status,
                'before' => $log->data_before,
                'after' => $log->data_after,
                'fields_modified' => $log->fields_modified,
                'change_magnitude' => $log->change_magnitude,
            ],
            'integrity' => [
                'content_hash' => $log->content_hash,
                'hash_algorithm' => $log->hash_algorithm,
                'hash_verified' => $log->hash_verified,
                'signature' => $log->signature ? '[REDACTED]' : null,
                'signature_timestamp' => $log->signature_timestamp,
            ],
            'compliance' => [
                'classification' => $log->classification,
                'requires_notification' => $log->requires_notification,
                'notification_recipients' => $log->notification_recipients,
                'retention_policy' => $log->retention_policy,
            ],
            'session' => [
                'session_id' => $log->session_id,
                'error_message' => $log->error_message,
                'success' => $log->status === 'SUCCESS',
            ],
        ];
    }

    /**
     * Export audit logs as JSON for compliance/audit purposes.
     */
    public static function exportLogs(array $filters = [], int $limit = 10000): string
    {
        $query = AuditLog::query();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['classification'])) {
            $query->where('classification', $filters['classification']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('timestamp_utc', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('timestamp_utc', '<=', $filters['date_to']);
        }

        $logs = $query->with('user', 'impersonatedByUser')
            ->orderBy('timestamp_utc', 'desc')
            ->limit($limit)
            ->get();

        $forensicData = $logs->map(fn($log) => self::getForensicReport($log))->toArray();

        return json_encode([
            'export_metadata' => [
                'export_timestamp' => now()->toIso8601String(),
                'total_records' => count($forensicData),
                'filters_applied' => $filters,
            ],
            'audit_events' => $forensicData,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Verify audit log integrity using content hash.
     */
    public static function verifyIntegrity(AuditLog $log): bool
    {
        $reconstructedHash = hash('sha256', json_encode([
            'user_id' => $log->user_id,
            'action' => $log->action,
            'data_before' => $log->data_before,
            'data_after' => $log->data_after,
            'timestamp' => $log->timestamp_utc->toIso8601String(),
        ], JSON_SORT_KEYS));

        return $reconstructedHash === $log->content_hash;
    }

    /**
     * Get audit events for a specific user over a date range.
     */
    public static function getUserAuditTrail(int $userId, $fromDate = null, $toDate = null): array
    {
        $query = AuditLog::where('user_id', $userId);

        if ($fromDate) {
            $query->whereDate('timestamp_utc', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('timestamp_utc', '<=', $toDate);
        }

        return $query->with('user', 'impersonatedByUser')
            ->orderBy('timestamp_utc', 'desc')
            ->get()
            ->map(fn($log) => self::getForensicReport($log))
            ->toArray();
    }

    /**
     * Check for suspicious activity patterns.
     */
    public static function detectSuspiciousActivity(int $userId, int $minutesWindow = 5): array
    {
        $fromTime = now()->subMinutes($minutesWindow);

        $logs = AuditLog::where('user_id', $userId)
            ->where('timestamp_utc', '>=', $fromTime)
            ->orderBy('timestamp_utc', 'asc')
            ->get();

        $suspiciousPatterns = [];

        // Pattern 1: Multiple failed login attempts
        $failedAttempts = $logs->where('action', 'LOGIN_FAILED')->count();
        if ($failedAttempts >= 3) {
            $suspiciousPatterns[] = [
                'pattern' => 'MULTIPLE_FAILED_LOGINS',
                'severity' => 'HIGH',
                'count' => $failedAttempts,
                'description' => "{$failedAttempts} failed login attempts in {$minutesWindow} minutes",
            ];
        }

        // Pattern 2: Rapid privilege escalation
        $privEscalations = $logs->where('action', 'ROLE_ASSIGNMENT')->count();
        if ($privEscalations >= 2) {
            $suspiciousPatterns[] = [
                'pattern' => 'RAPID_PRIVILEGE_ESCALATION',
                'severity' => 'CRITICAL',
                'count' => $privEscalations,
                'description' => "{$privEscalations} role assignments in {$minutesWindow} minutes",
            ];
        }

        // Pattern 3: Access from unusual location
        $locations = $logs->pluck('geo_location')->unique();
        if ($locations->count() >= 2) {
            $suspiciousPatterns[] = [
                'pattern' => 'MULTIPLE_LOCATIONS',
                'severity' => 'MEDIUM',
                'locations' => $locations->toArray(),
                'description' => "Access from {$locations->count()} different locations in {$minutesWindow} minutes",
            ];
        }

        // Pattern 4: Bulk data access
        $dataAccess = $logs->where('action', 'DATA_ACCESS')->count();
        if ($dataAccess >= 5) {
            $suspiciousPatterns[] = [
                'pattern' => 'BULK_DATA_ACCESS',
                'severity' => 'HIGH',
                'count' => $dataAccess,
                'description' => "{$dataAccess} data access events in {$minutesWindow} minutes",
            ];
        }

        return $suspiciousPatterns;
    }
}
