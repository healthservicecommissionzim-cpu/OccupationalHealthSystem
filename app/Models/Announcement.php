<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'target_audience',
        'scheduled_for',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the author (CoS staff) who created this announcement.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Scope to get active (published) announcements.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('scheduled_for')
                ->orWhere('scheduled_for', '<=', now());
        })->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope to get scheduled (not yet published) announcements.
     */
    public function scopeScheduled($query)
    {
        return $query->where('scheduled_for', '>', now());
    }

    /**
     * Scope to get announcements for a specific target audience.
     */
    public function scopeForAudience($query, $audience)
    {
        return $query->where(function ($q) use ($audience) {
            $q->where('target_audience', $audience)
                ->orWhere('target_audience', 'all_staff');
        });
    }

    /**
     * Scope to get published announcements.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Scope to get announcements created by a specific author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope to get announcements in reverse chronological order.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Check if this announcement is currently active.
     */
    public function isActive(): bool
    {
        $isPublished = $this->scheduled_for === null || $this->scheduled_for <= now();
        $notExpired = $this->expires_at === null || $this->expires_at > now();

        return $isPublished && $notExpired;
    }

    /**
     * Check if this announcement targets a specific audience.
     */
    public function targetsAudience($audience): bool
    {
        return $this->target_audience === 'all_staff' || $this->target_audience === $audience;
    }
}
