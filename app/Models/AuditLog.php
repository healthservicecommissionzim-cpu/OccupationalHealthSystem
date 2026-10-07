<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    
    protected $fillable = [
        'event_id',
        'correlation_id',
        'request_id',
        'user_id',
        'session_id',
        'impersonated_by_user_id',
        'impersonated_by_username',
        'action',
        'action_type',
        'case_id',
        'component',
        'affected_entity',
        'http_method',
        'http_code',
        'duration_ms',
        'status',
        'error_message',
        'integrity_hash',
        'content_hash',
        'hash_algorithm',
        'hash_verified',
        'signature',
        'signature_timestamp',
        'resource_type',
        'resource_path',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'data_before',
        'data_after',
        'fields_modified',
        'change_magnitude',
        'ip_address',
        'ip_version',
        'hostname',
        'geo_location',
        'geo_country',
        'geo_city',
        'geo_latitude',
        'geo_longitude',
        'user_agent',
        'timestamp_utc',
        'requires_notification',
        'notification_recipients',
        'retention_policy',
        'classification',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'data_before' => 'array',
        'data_after' => 'array',
        'fields_modified' => 'array',
        'notification_recipients' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'timestamp_utc' => 'datetime',
        'signature_timestamp' => 'datetime',
        'requires_notification' => 'boolean',
        'hash_verified' => 'boolean',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who impersonated (if applicable).
     */
    public function impersonatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'impersonated_by_user_id');
    }

    /**
     * Get the case (incident) this audit log is related to.
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'case_id', 'id');
    }

    /**
     * Scope to get audit logs for a specific case.
     */
    public function scopeForCase($query, $caseId)
    {
        return $query->where('case_id', $caseId);
    }

    /**
     * Scope to filter by action type.
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope to get logs within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get logs by a specific actor (user).
     */
    public function scopeByActor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get logs ordered by creation time.
     */
    public function scopeChronological($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Create comprehensive audit log with full forensic data.
     */
    public static function logForensicActivity(array $data = []): self
    {
        try {
            $user = auth()->user();
            $request = request();
            
            // Generate unique identifiers
            $eventId = 'evt_' . \Illuminate\Support\Str::ulid();
            $requestId = $request->header('X-Request-ID') ?? 'req_' . \Illuminate\Support\Str::ulid();
            
            // Get session info
            $sessionId = session()->getId();
            
            // Detect impersonation
            $impersonatedByUserId = session()->get('impersonated_by_user_id');
            $impersonatedByUsername = session()->get('impersonated_by_username');
            
            // Determine IP version
            $ipAddress = $request->ip() ?? '0.0.0.0';
            $ipVersion = strpos($ipAddress, ':') !== false ? 'IPv6' : 'IPv4';
            
            // Sanitize before/after data
            $dataBefore = self::sanitizeData($data['old_values'] ?? null);
            $dataAfter = self::sanitizeData($data['new_values'] ?? null);
            
            // Detect fields modified
            $fieldsModified = self::detectModifiedFields($dataBefore, $dataAfter);
            
            // Determine change magnitude
            $changeMagnitude = self::calculateChangeMagnitude($fieldsModified, $dataAfter);
            
            // Generate content hash for integrity
            $contentHash = self::generateContentHash([
                'user_id' => $user?->id ?? $data['user_id'] ?? null,
                'action' => $data['action'] ?? null,
                'data_before' => $dataBefore,
                'data_after' => $dataAfter,
                'timestamp' => now()->toIso8601String(),
            ]);
            
            // Determine classification
            $classification = self::classifyLog($data['action'] ?? null, $changeMagnitude);
            
            // Determine retention policy
            $retentionPolicy = self::determineRetentionPolicy($classification);
            
            // Check if notification required
            $requiresNotification = $classification === 'AUDIT_CRITICAL' || $classification === 'SECURITY_SENSITIVE';
            $notificationRecipients = $requiresNotification ? ['security@hsc.org.zw', 'audit@hsc.org.zw'] : [];
            
            return self::create([
                'event_id' => $eventId,
                'correlation_id' => $data['correlation_id'] ?? \Illuminate\Support\Str::uuid(),
                'request_id' => $requestId,
                'user_id' => $user?->id ?? $data['user_id'] ?? null,
                'session_id' => $sessionId,
                'impersonated_by_user_id' => $impersonatedByUserId,
                'impersonated_by_username' => $impersonatedByUsername,
                'action' => $data['action'] ?? 'UNKNOWN',
                'component' => $data['component'] ?? (class_basename($request->route()?->getController() ?? 'Unknown')),
                'affected_entity' => $data['affected_entity'] ?? null,
                'http_method' => $request->method(),
                'http_code' => $data['http_code'] ?? 200,
                'duration_ms' => $data['duration_ms'] ?? 0,
                'status' => ($data['http_code'] ?? 200) >= 200 && ($data['http_code'] ?? 200) < 300 ? 'SUCCESS' : 'FAILED',
                'error_message' => $data['error_message'] ?? null,
                'resource_type' => $data['resource_type'] ?? null,
                'resource_path' => $data['resource_path'] ?? $request->path(),
                'auditable_type' => $data['auditable_type'] ?? null,
                'auditable_id' => $data['auditable_id'] ?? null,
                'old_values' => $data['old_values'] ? json_encode($data['old_values']) : null,
                'new_values' => $data['new_values'] ? json_encode($data['new_values']) : null,
                'data_before' => $dataBefore ? json_encode($dataBefore) : null,
                'data_after' => $dataAfter ? json_encode($dataAfter) : null,
                'fields_modified' => $fieldsModified ? json_encode($fieldsModified) : null,
                'change_magnitude' => $changeMagnitude,
                'ip_address' => $ipAddress,
                'ip_version' => $ipVersion,
                'hostname' => gethostname() ?: null,
                'geo_location' => $data['geo_location'] ?? 'UNKNOWN',
                'geo_country' => $data['geo_country'] ?? null,
                'geo_city' => $data['geo_city'] ?? null,
                'geo_latitude' => $data['geo_latitude'] ?? null,
                'geo_longitude' => $data['geo_longitude'] ?? null,
                'user_agent' => $request->userAgent(),
                'timestamp_utc' => now(),
                'content_hash' => $contentHash,
                'hash_algorithm' => 'SHA256',
                'hash_verified' => false,
                'integrity_hash' => $data['integrity_hash'] ?? null,
                'requires_notification' => $requiresNotification,
                'notification_recipients' => $notificationRecipients,
                'retention_policy' => $retentionPolicy,
                'classification' => $classification,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to create audit log', [
                'error' => $e->getMessage(),
                'action' => $data['action'] ?? 'UNKNOWN',
            ]);
            throw $e;
        }
    }

    /**
     * Sanitize sensitive data before storing (remove PII patterns).
     */
    private static function sanitizeData(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        $sensitivePatterns = [
            'password', 'token', 'api_key', 'secret', 'ssn', 'national_id',
            'credit_card', 'pin', 'otp', 'security_code',
        ];

        $sanitized = $data;
        foreach ($sensitivePatterns as $pattern) {
            foreach ($sanitized as $key => $value) {
                if (stripos($key, $pattern) !== false) {
                    $sanitized[$key] = '[REDACTED_' . strtoupper($pattern) . ']';
                }
            }
        }

        return $sanitized;
    }

    /**
     * Detect which fields were modified between before/after states.
     */
    private static function detectModifiedFields(?array $before, ?array $after): ?array
    {
        if (!$before || !$after) {
            return null;
        }

        $modified = [];
        $allKeys = array_unique(array_merge(array_keys($before), array_keys($after)));

        foreach ($allKeys as $key) {
            $beforeVal = $before[$key] ?? null;
            $afterVal = $after[$key] ?? null;

            if ($beforeVal !== $afterVal) {
                $modified[] = $key;
            }
        }

        return $modified ?: null;
    }

    /**
     * Calculate change magnitude based on fields modified.
     */
    private static function calculateChangeMagnitude(?array $fieldsModified, ?array $dataAfter): string
    {
        if (!$fieldsModified) {
            return 'MINOR';
        }

        $criticalFields = ['status', 'approved_by', 'rejected_by', 'role', 'permission', 'is_admin'];
        $securityFields = ['password', 'two_factor', 'api_key', 'session', 'token'];

        $hasCritical = array_intersect($fieldsModified, $criticalFields);
        $hasSecurity = array_intersect($fieldsModified, $securityFields);

        if ($hasSecurity) {
            return 'CRITICAL';
        }

        if ($hasCritical) {
            return 'SIGNIFICANT';
        }

        return count($fieldsModified) > 3 ? 'MODERATE' : 'MINOR';
    }

    /**
     * Generate SHA256 hash of log content for integrity verification.
     */
    private static function generateContentHash(array $content): string
    {
        $jsonContent = json_encode($content, JSON_UNESCAPED_SLASHES);
        return hash('sha256', $jsonContent);
    }

    /**
     * Classify log based on action and change magnitude.
     */
    private static function classifyLog(?string $action, string $changeMagnitude): string
    {
        $criticalActions = [
            'ROLE_ASSIGNMENT', 'PERMISSION_GRANT', 'USER_REJECTION',
            'APPROVAL_OVERRIDE', 'PRIVILEGE_ESCALATION', 'ACCOUNT_DISABLE',
        ];

        if (in_array($action, $criticalActions) || $changeMagnitude === 'CRITICAL') {
            return 'AUDIT_CRITICAL';
        }

        if ($changeMagnitude === 'SIGNIFICANT') {
            return 'SECURITY_SENSITIVE';
        }

        if ($changeMagnitude === 'MODERATE') {
            return 'IMPORTANT';
        }

        return 'ROUTINE';
    }

    /**
     * Determine retention policy based on classification.
     */
    private static function determineRetentionPolicy(string $classification): string
    {
        return match($classification) {
            'AUDIT_CRITICAL' => 'CRITICAL_ACTION_7_YEARS',
            'SECURITY_SENSITIVE' => 'EXTENDED_3_YEARS',
            'IMPORTANT' => 'EXTENDED_3_YEARS',
            default => 'STANDARD_1_YEAR',
        };
    }

    /**
     * Legacy method for backward compatibility.
     */
    public static function logActivity(
        $user,
        $action,
        $resourceType,
        $resourcePath = null,
        $auditable_type = null,
        $auditable_id = null,
        $oldValues = null,
        $newValues = null,
        $httpMethod = 'POST',
        $httpCode = 200,
        $durationMs = 0
    ) {
        return self::logForensicActivity([
            'user' => $user,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_path' => $resourcePath,
            'auditable_type' => $auditable_type,
            'auditable_id' => $auditable_id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'http_method' => $httpMethod,
            'http_code' => $httpCode,
            'duration_ms' => $durationMs,
        ]);
    }
}
