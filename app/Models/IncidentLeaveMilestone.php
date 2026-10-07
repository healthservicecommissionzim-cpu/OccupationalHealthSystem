<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentLeaveMilestone extends Model
{
    protected $fillable = [
        'incident_id',
        'milestone_type',
        'days_elapsed_at_flag',
        'flag_reason',
        'applied_by_system',
        'applied_by_user_id',
        'applied_at',
        'notification_sent_at',
    ];

    protected $casts = [
        'applied_by_system' => 'boolean',
        'applied_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the incident this milestone belongs to.
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the user who applied this milestone (if not system-applied).
     */
    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by_user_id');
    }

    /**
     * Check if this milestone was applied by the system.
     */
    public function isSystemApplied(): bool
    {
        return $this->applied_by_system;
    }

    /**
     * Get a human-readable description of this milestone.
     */
    public function getDescription(): string
    {
        $type = $this->milestone_type === '90_DAY' ? '90-Day' : '180-Day';
        $appliedBy = $this->isSystemApplied() ? 'System' : ($this->appliedBy?->name ?? 'Unknown User');
        
        return "{$type} Milestone - {$this->days_elapsed_at_flag} days elapsed (flagged by {$appliedBy})";
    }
}
