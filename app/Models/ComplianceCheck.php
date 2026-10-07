<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceCheck extends Model
{
    protected $fillable = [
        'case_id',
        'evaluated_by_id',
        'checklist_items',
        'overall_status',
    ];

    protected $casts = [
        'checklist_items' => 'array',
        'created_at' => 'datetime',
    ];

    // Disable updated_at since compliance checks are immutable after creation
    public const UPDATED_AT = null;

    /**
     * Get the case (incident) that this compliance check is for.
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'case_id', 'id');
    }

    /**
     * Get the user who conducted this compliance check.
     */
    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by_id', 'id');
    }

    /**
     * Scope to get non-compliant cases.
     */
    public function scopeNonCompliant($query)
    {
        return $query->where('overall_status', 'NON_COMPLIANT');
    }

    /**
     * Scope to get compliant cases.
     */
    public function scopeCompliant($query)
    {
        return $query->where('overall_status', 'COMPLIANT');
    }

    /**
     * Scope to get compliance checks for a specific case.
     */
    public function scopeForCase($query, $caseId)
    {
        return $query->where('case_id', $caseId)->latest('created_at');
    }

    /**
     * Scope to get checks conducted by a specific evaluator.
     */
    public function scopeByEvaluator($query, $evaluatorId)
    {
        return $query->where('evaluated_by_id', $evaluatorId);
    }

    /**
     * Scope to get checks within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the latest compliance check for a case.
     */
    public static function latestForCase($caseId)
    {
        return static::where('case_id', $caseId)->latest('created_at')->first();
    }
}
