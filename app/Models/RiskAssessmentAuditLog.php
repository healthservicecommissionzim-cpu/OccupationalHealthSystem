<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessmentAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_assessment_id',
        'event_type',
        'event_description',
        'old_values',
        'new_values',
        'triggered_by_incident_id',
        'user_id',
        'user_name',
        'user_role',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relationships
    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function triggeredByIncident()
    {
        return $this->belongsTo(Incident::class, 'triggered_by_incident_id');
    }
}
