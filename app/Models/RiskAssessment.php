<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'facility_id',
        'department_id',
        'specific_zone',
        'task_type',
        'equipment_involved',
        'hazard_description',
        'hazard_category',
        'inherent_likelihood',
        'inherent_severity',
        'elimination_controls',
        'substitution_controls',
        'engineering_controls',
        'administrative_controls',
        'ppe_controls',
        'control_measures',
        'residual_likelihood',
        'residual_severity',
        'predicted_likelihood',
        'predicted_severity',
        'escalation_warning',
        'predictive_insights',
        'linked_incidents_30d',
        'linked_incidents_90d',
        'linked_incidents_365d',
        'linked_dod_total',
        'probable_causes',
        'status',
        'last_reviewed_date',
        'next_review_date',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'control_measures' => 'array',
        'escalation_warning' => 'boolean',
        'last_reviewed_date' => 'date',
        'next_review_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'inherent_risk_level',
        'residual_risk_level',
        'predicted_risk_level',
        'risk_reduction_percentage',
    ];

    // Relationships
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function correctiveActions()
    {
        return $this->hasMany(RiskCorrectiveAction::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(RiskAssessmentAuditLog::class);
    }

    // Computed Risk Score
    public function getInherentRiskScoreAttribute()
    {
        return $this->inherent_likelihood * $this->inherent_severity;
    }

    public function getResidualRiskScoreAttribute()
    {
        return $this->residual_likelihood * $this->residual_severity;
    }

    public function getPredictedRiskScoreAttribute()
    {
        if (!$this->predicted_likelihood || !$this->predicted_severity) {
            return null;
        }
        return $this->predicted_likelihood * $this->predicted_severity;
    }

    // Risk Level Labels
    public function getInherentRiskLevelAttribute()
    {
        return $this->getRiskLevel($this->inherent_risk_score);
    }

    public function getResidualRiskLevelAttribute()
    {
        return $this->getRiskLevel($this->residual_risk_score);
    }

    public function getPredictedRiskLevelAttribute()
    {
        if (!$this->predicted_risk_score) return null;
        return $this->getRiskLevel($this->predicted_risk_score);
    }

    private function getRiskLevel($score)
    {
        if ($score >= 20) return 'CRITICAL';
        if ($score >= 15) return 'HIGH';
        if ($score >= 10) return 'MEDIUM';
        if ($score >= 5) return 'LOW';
        return 'VERY_LOW';
    }

    public function getRiskReductionPercentageAttribute()
    {
        if ($this->inherent_risk_score == 0) return 0;
        $reduction = (($this->inherent_risk_score - $this->residual_risk_score) / $this->inherent_risk_score) * 100;
        return round($reduction, 1);
    }

    // Scope for filtering
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeHighRisk($query)
    {
        return $query->whereRaw('residual_likelihood * residual_severity >= 15');
    }

    public function scopeNeedsReview($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'NEEDS_REVIEW')
              ->orWhere('next_review_date', '<=', now())
              ->orWhere('escalation_warning', true);
        });
    }
}
