<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ticket_number',
        'originator_user_id',
        'originator_name',
        'originator_email',
        'originator_phone',
        'routed_to_super_admin_id',
        'title',
        'category',
        'description',
        'status',
        'created_by_role',
        'last_activity_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'last_activity_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the originator user who created this ticket.
     */
    public function originator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'originator_user_id');
    }

    /**
     * Get the super admin user assigned to this ticket.
     */
    public function assignedSuperAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'routed_to_super_admin_id');
    }

    /**
     * Get all replies for this ticket.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'support_ticket_id');
    }

    /**
     * Get all notifications for this ticket.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(TicketNotification::class, 'support_ticket_id');
    }
}
