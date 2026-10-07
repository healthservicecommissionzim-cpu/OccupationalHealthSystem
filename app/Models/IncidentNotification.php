<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentNotification extends Model
{
    protected $fillable = [
        'incident_id',
        'witness_token_id',
        'recipient_user_id',
        'recipient_phone',
        'notification_type',
        'notification_channel',
        'message_content',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'failure_reason',
        'error_message',
        'initiated_by_user_id', // Task 10.3: Audit logging - track which supervisor initiated SMS
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    const NOTIFICATION_TYPES = [
        'SUPERVISOR_ALERT' => 'Supervisor Alert',
        'VERIFICATION_REMINDER' => 'Verification Reminder',
        'WORKFLOW_UPDATE' => 'Workflow Status Update',
        'WITNESS_INITIAL_SMS' => 'Witness Initial SMS',
        'WITNESS_REMINDER_SMS' => 'Witness Reminder SMS',
        'DOCUMENT_UPLOAD' => 'Document Upload',
    ];

    const NOTIFICATION_CHANNELS = [
        'in_app' => 'In-App',
        'email' => 'Email',
        'sms' => 'SMS',
    ];

    const STATUSES = [
        'SENT' => 'Sent',
        'DELIVERED' => 'Delivered',
        'READ' => 'Read',
        'FAILED' => 'Failed',
    ];

    /**
     * Get the incident this notification is about.
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the recipient user.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    /**
     * Get the witness token associated with this notification.
     */
    public function witnessToken(): BelongsTo
    {
        return $this->belongsTo(IncidentWitnessToken::class, 'witness_token_id');
    }

    /**
     * Get the user who initiated this notification (e.g., supervisor sending reminder).
     */
    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by_user_id');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'READ',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as delivered.
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'DELIVERED',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark notification as failed.
     */
    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 'FAILED',
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Get human-readable notification type.
     */
    public function getNotificationTypeLabel(): string
    {
        return self::NOTIFICATION_TYPES[$this->notification_type] ?? $this->notification_type;
    }
}
