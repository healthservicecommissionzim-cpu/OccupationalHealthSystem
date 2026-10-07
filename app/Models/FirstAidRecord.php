<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirstAidRecord extends Model
{
    protected $fillable = [
        'incident_id',
        'nurse_name',
        'nurse_license_number',
        'injury_description',
        'first_aid_description',
        'time_administered',
        'location',
        'severity_level',
        'notes',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_comments',
    ];

    protected $casts = [
        'time_administered' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the incident that this first aid record belongs to.
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the user who reviewed this record.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if record is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Approve the record.
     */
    public function approve(int $userId, string $comments = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'review_comments' => $comments,
        ]);
    }

    /**
     * Reject the record.
     */
    public function reject(int $userId, string $comments = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'review_comments' => $comments,
        ]);
    }
}
