<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringNote extends Model
{
    protected $fillable = [
        'case_id',
        'author_id',
        'content',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the case (incident) that this monitoring note belongs to.
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'case_id', 'id');
    }

    /**
     * Get the author (CoS staff) who created this note.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Scope to get monitoring notes for a specific case ordered chronologically.
     */
    public function scopeForCase($query, $caseId)
    {
        return $query->where('case_id', $caseId)->latest('created_at');
    }

    /**
     * Scope to get notes added by a specific author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope to get notes within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
