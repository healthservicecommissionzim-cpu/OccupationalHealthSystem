<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunicationLog extends Model
{
    protected $table = 'communication_logs';

    protected $fillable = [
        'communication_type',
        'channel',
        'recipient_email',
        'recipient_phone',
        'recipient_user_id',
        'subject',
        'message_preview',
        'full_content',
        'status',
        'delivery_status',
        'delivery_attempts',
        'first_attempt_at',
        'delivered_at',
        'error_message',
        'failure_reason',
        'failure_code',
        'delivery_response',
        'sent_at',
        'provider',
        'provider_message_id',
        'provider_metadata',
        'webhook_received',
        'webhook_received_at',
        'webhook_data',
        'next_retry_at',
        'retry_history',
        'related_entity_type',
        'related_entity_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'first_attempt_at' => 'datetime',
        'delivered_at' => 'datetime',
        'webhook_received_at' => 'datetime',
        'next_retry_at' => 'datetime',
        'webhook_data' => 'json',
        'provider_metadata' => 'json',
        'retry_history' => 'json',
        'webhook_received' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the recipient user
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    /**
     * Scope: Filter by communication type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('communication_type', $type);
    }

    /**
     * Scope: Filter by channel (email or sms)
     */
    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get recent communications
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Get communication type badge color
     */
    public function getTypeColorAttribute()
    {
        return match($this->communication_type) {
            'REGISTRATION' => 'bg-blue-100 text-blue-800',
            'APPROVAL' => 'bg-green-100 text-green-800',
            'REJECTION' => 'bg-red-100 text-red-800',
            'INCIDENT_CONFIRMATION' => 'bg-purple-100 text-purple-800',
            'DOCUMENT_UPLOAD' => 'bg-yellow-100 text-yellow-800',
            'PASSWORD_RESET' => 'bg-orange-100 text-orange-800',
            'MILESTONE_ALERT' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get channel badge color
     */
    public function getChannelColorAttribute()
    {
        return match($this->channel) {
            'email' => 'bg-sky-100 text-sky-800',
            'sms' => 'bg-teal-100 text-teal-800',
            'database' => 'bg-slate-100 text-slate-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'SENT' => 'bg-green-100 text-green-800',
            'FAILED' => 'bg-red-100 text-red-800',
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get delivery status badge color
     */
    public function getDeliveryStatusColorAttribute()
    {
        return match($this->delivery_status ?? 'PENDING') {
            'DELIVERED' => 'bg-emerald-100 text-emerald-800',
            'SENT' => 'bg-sky-100 text-sky-800',
            'PENDING' => 'bg-amber-100 text-amber-800',
            'FAILED' => 'bg-red-100 text-red-800',
            'BOUNCED' => 'bg-orange-100 text-orange-800',
            'UNDELIVERABLE' => 'bg-rose-100 text-rose-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if delivery failed
     */
    public function isDeliveryFailed()
    {
        return in_array($this->delivery_status, ['FAILED', 'BOUNCED', 'UNDELIVERABLE']);
    }

    /**
     * Check if delivery is pending
     */
    public function isDeliveryPending()
    {
        return $this->delivery_status === 'PENDING';
    }

    /**
     * Check if delivery is confirmed
     */
    public function isDelivered()
    {
        return $this->delivery_status === 'DELIVERED';
    }
}
