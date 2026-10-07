<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketNotification extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'support_ticket_id',
        'recipient_user_id',
        'notification_type',
        'notification_channel',
        'content',
        'status',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the support ticket this notification is for.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    /**
     * Get the recipient user who should receive this notification.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
