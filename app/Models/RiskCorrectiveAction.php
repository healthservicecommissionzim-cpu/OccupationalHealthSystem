<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskCorrectiveAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_assessment_id',
        'action_type',
        'action_description',
        'priority',
        'status',
        'assigned_to',
        'due_date',
        'completed_date',
        'verified_by',
        'verified_at',
        'verification_notes',
        'completion_percentage',
        'progress_notes',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
        'verified_at' => 'datetime',
    ];

    protected $appends = ['is_overdue'];

    // Relationships
    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Check if overdue
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'COMPLETED' || $this->status === 'CANCELLED') {
            return false;
        }
        
        if (!$this->due_date) {
            return false;
        }

        return now()->isAfter($this->due_date);
    }

    // Scopes
    public function scopeOverdue($query)
    {
        return $query->whereNotIn('status', ['COMPLETED', 'CANCELLED'])
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'IN_PROGRESS');
    }
}
