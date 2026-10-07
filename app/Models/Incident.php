<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Incident extends Model
{
    protected $fillable = [
        'reference_number',
        'first_name',
        'surname',
        'designation_id',
        'department_id',
        'id_number',
        'ec_number',
        'home_address',
        'phone_number',
        'email',
        'incident_date',
        'incident_time',
        'incident_type_id',
        'incident_description',
        'facility_type_id',
        'province_id',
        'district_id',
        'facility_id',
        'supervisor_designation_id',
        'supervisor_name',
        'assigned_supervisor_id',
        'supervisor_verified_at',
        'supervisor_witness_statement',
        'supervisor_witness_data',
        'is_work_related',
        'status',
        'compliance_status',
        'first_aid_token',
        'first_aid_token_expires_at',
        'first_aid_recorded',
        'review_pool',
        'hod_report',
        'hod_report_submitted_at',
        'hod_report_submitted_by',
        'hod_acknowledged_witness_statements',
        'hr_route_type',
        'hr_assigned_group_id',
        'escalated_to_hr_at',
        'is_workplace_fatality',
        'is_road_traffic_accident',
        'hr_dossier_acknowledged_at',
        'hr_dossier_acknowledged_by',
        'hr_reviewed_at',
        'hr_reviewed_by',
        'hr_compliance_status',
        'hr_rejection_reasons',
        'hr_compliance_notes',
        'hr_compliance_notes_saved_at',
        'social_services_receiving_official',
        'physical_handoff_timestamp',
        'physical_handoff_by_user_id',
        'signed_manifest_file_path',
        'signed_manifest_uploaded_at',
        'submitted_to_social_services_at',
        'submission_status',
        'routed_to_dmo_user_id',
        'routed_to_medical_superintendent_user_id',
        'routed_to_cmo_user_id',
        'routed_to_hr_user_id',
        'executive_audit_verified',
        'executive_audit_verified_at',
        'executive_audit_verified_by_user_id',
        'executive_recommendation_text',
        'executive_recommendation_submitted_at',
        'executive_recommendation_submitted_by_user_id',
        'routed_to_pmd_status',
        'routed_to_pmd_user_id',
        'sick_leave_start_date',
        'medical_certificate_provided',
        'expected_recovery_date',
        'leave_payment_stage',
        'leave_status',
        'leave_information_saved_at',
        'saved_by_user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'incident_date' => 'date',
        'supervisor_verified_at' => 'datetime',
        'supervisor_witness_data' => 'array',
        'first_aid_token_expires_at' => 'datetime',
        'first_aid_recorded' => 'boolean',
        'is_work_related' => 'boolean',
        'hod_report_submitted_at' => 'datetime',
        'hod_acknowledged_witness_statements' => 'boolean',
        'escalated_to_hr_at' => 'datetime',
        'is_workplace_fatality' => 'boolean',
        'is_road_traffic_accident' => 'boolean',
        'hr_dossier_acknowledged_at' => 'datetime',
        'hr_reviewed_at' => 'datetime',
        'hr_rejection_reasons' => 'array',
        'hr_compliance_notes_saved_at' => 'datetime',
        'physical_handoff_timestamp' => 'datetime',
        'signed_manifest_uploaded_at' => 'datetime',
        'submitted_to_social_services_at' => 'datetime',
        'executive_audit_verified' => 'boolean',
        'executive_audit_verified_at' => 'datetime',
        'executive_recommendation_submitted_at' => 'datetime',
        'injury_leave_start_date' => 'date',
        'injury_leave_end_date' => 'date',
        'rtw_start_date' => 'date',
        'rtw_end_date' => 'date',
        'rtw_last_review_date' => 'date',
        'rtw_next_review_date' => 'date',
        'rtw_doctor_clearance_received' => 'boolean',
        'rtw_plan_signed_off' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sick_leave_start_date' => 'date',
        'expected_recovery_date' => 'date',
        'leave_information_saved_at' => 'datetime',
        'medical_certificate_provided' => 'boolean',
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }

    public function incidentType()
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function supervisorDesignation()
    {
        return $this->belongsTo(Designation::class, 'supervisor_designation_id');
    }

    /**
     * Get the assigned supervisor (actual User account).
     */
    public function assignedSupervisor()
    {
        return $this->belongsTo(User::class, 'assigned_supervisor_id');
    }

    /**
     * Get the user who submitted the HOD report.
     */
    public function hodReportSubmittedBy()
    {
        return $this->belongsTo(User::class, 'hod_report_submitted_by');
    }

    /**
     * Get the assigned HR user for this incident.
     */
    public function assignedHRUser()
    {
        return $this->belongsTo(User::class, 'routed_to_hr_user_id');
    }

    /**
     * Get first aid records for this incident.
     */
    public function firstAidRecords()
    {
        return $this->hasMany(FirstAidRecord::class);
    }

    /**
     * Get all witness tokens for this incident.
     */
    public function witnessTokens()
    {
        return $this->hasMany(IncidentWitnessToken::class);
    }

    /**
     * Get all attachments for this incident.
     */
    public function attachments()
    {
        return $this->hasMany(IncidentAttachment::class);
    }

    /**
     * Get notifications sent for this incident.
     */
    public function notifications()
    {
        return $this->hasMany(IncidentNotification::class);
    }

    /**
     * Get the user who saved the leave information.
     */
    public function leaveInformationSavedBy()
    {
        return $this->belongsTo(User::class, 'saved_by_user_id');
    }

    /**
     * Get all leave milestones for this incident.
     */
    public function leaveMilestones()
    {
        return $this->hasMany(IncidentLeaveMilestone::class);
    }

    /**
     * Check if incident has required NSSA form.
     */
    public function hasNSSAForm(): bool
    {
        return $this->attachments()
            ->where('document_type', 'NSSA_FORM_WCA1')
            ->where('upload_status', 'VERIFIED')
            ->exists();
    }

    /**
     * ─── LEAVE MANAGEMENT METHODS ───
     * Track and manage sick leave for compliance with HSB 3/2017 regulations.
     */

    /**
     * Get the calculated days elapsed since sick leave start date.
     */
    public function getDaysElapsedAttribute(): ?int
    {
        if (!$this->sick_leave_start_date) {
            return null;
        }
        return now()->diffInDays($this->sick_leave_start_date);
    }

    /**
     * Get the calculated days remaining until expected recovery date.
     */
    public function getRecoveryDaysRemainingAttribute(): ?int
    {
        if (!$this->expected_recovery_date) {
            return null;
        }
        $remaining = now()->diffInDays($this->expected_recovery_date, false);
        return $remaining;
    }

    /**
     * Check if this case is in full pay stage (0-90 days).
     */
    public function isFullPayStage(): bool
    {
        return $this->days_elapsed !== null && $this->days_elapsed <= 90;
    }

    /**
     * Check if this case is in half pay stage (91-180 days).
     */
    public function isHalfPayStage(): bool
    {
        return $this->days_elapsed !== null && $this->days_elapsed > 90 && $this->days_elapsed <= 180;
    }

    /**
     * Check if this case is eligible for medical discharge (180+ days).
     */
    public function isMedicalDischargeEligible(): bool
    {
        return $this->days_elapsed !== null && $this->days_elapsed > 180;
    }

    /**
     * Check if this case has active leave (start date is set and status permits).
     */
    public function hasActiveLeave(): bool
    {
        return $this->sick_leave_start_date !== null && 
               in_array($this->leave_status, ['ACTIVE', 'SUSPENDED']);
    }

    /**
     * Check if the 90-day milestone is pending (reached but not yet flagged).
     */
    public function is90DayMilestonePending(): bool
    {
        return $this->days_elapsed == 90 && 
               !$this->leaveMilestones()->where('milestone_type', '90_DAY')->exists();
    }

    /**
     * Check if the 180-day milestone is pending (reached but not yet flagged).
     */
    public function is180DayMilestonePending(): bool
    {
        return $this->days_elapsed >= 180 && 
               !$this->leaveMilestones()->where('milestone_type', '180_DAY')->exists();
    }

    /**
     * Calculate the current leave payment stage based on days elapsed and medical certificate.
     */
    public function getLeavePaymentStage(): string
    {
        if ($this->days_elapsed === null) {
            return 'UNKNOWN';
        }
        
        if ($this->days_elapsed <= 90) {
            return 'FULL_PAY';
        }
        
        if ($this->days_elapsed <= 180) {
            return $this->medical_certificate_provided ? 'HALF_PAY' : 'FULL_PAY';
        }
        
        return 'MEDICAL_DISCHARGE_ELIGIBLE';
    }

    /**
     * Check if incident has initial medical treatment records.
     */
    public function hasMedicalRecords(): bool
    {
        return $this->attachments()
            ->where('document_type', 'INITIAL_MEDICAL_TREATMENT')
            ->where('upload_status', 'VERIFIED')
            ->exists();
    }

    /**
     * Check if all required documents are verified (HSB Circular No. 3 compliance).
     */
    public function allDocumentsVerified(): bool
    {
        return $this->hasNSSAForm() && $this->hasMedicalRecords();
    }

    /**
     * Check if supervisor has verified the incident.
     */
    public function isSupervisorVerified(): bool
    {
        return $this->supervisor_verified_at !== null && !empty($this->supervisor_witness_statement);
    }

    /**
     * Update compliance status based on document verification.
     */
    public function updateComplianceStatus(): void
    {
        $nssaForm = $this->hasNSSAForm();
        $medicalRecords = $this->hasMedicalRecords();

        if ($nssaForm && $medicalRecords) {
            $this->compliance_status = 'COMPLIANT';
        } elseif ($nssaForm || $medicalRecords) {
            $this->compliance_status = 'PARTIAL_COMPLIANT';
        } else {
            $this->compliance_status = 'NONCOMPLIANT';
        }

        $this->save();
    }

    /**
     * Generate a unique 4-digit token for first aid access.
     */
    public function generateFirstAidToken(): string
    {
        do {
            $token = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('first_aid_token', $token)->exists());

        $this->update([
            'first_aid_token' => $token,
            'first_aid_token_expires_at' => now()->addDays(7),
        ]);

        return $token;
    }

    /**
     * Check if the first aid token is valid.
     */
    public function isFirstAidTokenValid(): bool
    {
        return $this->first_aid_token 
            && $this->first_aid_token_expires_at 
            && now()->lessThan($this->first_aid_token_expires_at)
            && !$this->first_aid_recorded;
    }

    /**
     * Verify and consume the first aid token.
     */
    public static function verifyFirstAidToken(string $token): ?self
    {
        $incident = self::where('first_aid_token', $token)
            ->where('first_aid_token_expires_at', '>', now())
            ->where('first_aid_recorded', false)
            ->first();

        return $incident;
    }

    /**
     * Mark first aid as recorded and expire the token.
     */
    public function markFirstAidRecorded(): void
    {
        $this->update([
            'first_aid_recorded' => true,
            'first_aid_token' => null,
            'first_aid_token_expires_at' => null,
        ]);
    }

    /**
     * Determine the HR review pool based on facility type (Smart Conditional Routing).
     */
    public function determineReviewPool(): string
    {
        $facilityType = $this->facilityType?->name ?? '';
        $facility = $this->facility;

        /**
         * Zimbabwe Health Facility Hierarchy & Routing Logic
         * 
         * PRIMARY LEVEL (No onsite HR) → District HR → DMO → PMD
         * - Rural Health Centres
         * - Health Posts
         * - Clinics
         * - Polyclinics
         * 
         * SECONDARY LEVEL - Conditional Routing Based on Facility
         * 
         * SCENARIO A: District Hospital (Has Resident Local HR)
         * → Resident Local HR → District HR → DMO → PMD
         * 
         * SCENARIO B: Rural Hospital (No onsite HR)
         * → District HR → DMO → PMD
         * 
         * SCENARIO C: Mission Hospital (No onsite HR)
         * → District HR → DMO → PMD
         * 
         * TERTIARY LEVEL (Has Hospital HR)
         * → Hospital HR → Medical Superintendent → PMD
         * - Provincial Hospitals
         * - General Hospitals
         * 
         * QUATERNARY LEVEL (Has Central HR)
         * → Hospital HR → CMO
         * - Central Hospital
         */

        // Map facility types to HR review pools based on Zimbabwe health facility levels
        $poolMapping = [
            // PRIMARY LEVEL - No onsite HR, routes through District
            'Rural Health Centre' => 'DISTRICT_HR_REVIEW',
            'Health Post' => 'DISTRICT_HR_REVIEW',
            'Clinic' => 'DISTRICT_HR_REVIEW',
            'Polyclinic' => 'DISTRICT_HR_REVIEW',
            
            // SECONDARY LEVEL - Conditional routing based on facility type
            'District Hospital' => 'RESIDENT_LOCAL_HR_REVIEW',  // Has resident local HR
            'Rural Hospital' => 'DISTRICT_HR_REVIEW',             // No onsite HR
            'Mission Hospital' => 'DISTRICT_HR_REVIEW',           // No onsite HR
            
            // TERTIARY LEVEL - Has Hospital HR, escalates to Medical Superintendent
            'Provincial Hospital' => 'HOSPITAL_HR_REVIEW',
            'General Hospital' => 'HOSPITAL_HR_REVIEW',
            'General Hospital / Designated District Referral' => 'HOSPITAL_HR_REVIEW',
            
            // QUATERNARY LEVEL - Has Central HR, routes to CMO
            'Central Hospital' => 'CENTRAL_HR_REVIEW',
        ];

        return $poolMapping[$facilityType] ?? 'DISTRICT_HR_REVIEW'; // Default to district if not found
    }

    /**
     * Check if all witness statements have been submitted (CRITICAL: Stage 4 Unlock Gate).
     * This is the MAIN BLOCKER that prevents HOD report access until witnesses submit.
     */
    public function allWitnessStatementsReceived(): bool
    {
        $witnessTokens = $this->witnessTokens()->get();
        
        if ($witnessTokens->isEmpty()) {
            return false; // No witnesses yet
        }

        // All witness tokens must have a submitted_at timestamp
        return $witnessTokens->every(function ($token) {
            return $token->statement_submitted_at !== null;
        });
    }

    /**
     * Get witness tokens that are still awaiting statements.
     */
    public function getPendingWitnessStatements()
    {
        return $this->witnessTokens()
            ->whereNull('statement_submitted_at')
            ->get();
    }

    /**
     * Get the count of witness statements still pending.
     */
    public function getPendingWitnessCount(): int
    {
        return $this->getPendingWitnessStatements()->count();
    }

    /**
     * Get all submitted witness statements for the HOD report.
     */
    public function getSubmittedWitnessStatements()
    {
        return $this->witnessTokens()
            ->whereNotNull('statement_submitted_at')
            ->get();
    }

    /**
     * ─── STATUTORY 12-MONTH COMPLIANCE ENGINE ───
     * Calculates days remaining before IOD claim expires per Department of Social Services requirement.
     */
    
    /**
     * Calculate the number of days since incident occurred.
     */
    public function getDaysSinceIncident(): int
    {
        if (!$this->incident_date) {
            return 0;
        }
        
        return $this->incident_date->diffInDays(now());
    }

    /**
     * Calculate the number of days remaining until claim expires (365 days from incident date).
     */
    public function getDaysRemainingUntilExpiry(): int
    {
        if (!$this->incident_date) {
            return 0;
        }
        
        $expiryDate = $this->incident_date->addDays(365);
        $daysRemaining = now()->diffInDays($expiryDate, false);
        
        return max(0, $daysRemaining); // Return 0 if already expired
    }

    /**
     * Get the exact expiry date for this claim.
     */
    public function getExpiryDate()
    {
        if (!$this->incident_date) {
            return null;
        }
        
        return $this->incident_date->addDays(365);
    }

    /**
     * Check if the claim has expired (older than 365 days).
     */
    public function isClaimExpired(): bool
    {
        if (!$this->incident_date) {
            return false;
        }
        
        return $this->incident_date->addDays(365)->isPast();
    }

    /**
     * Check if claim is in critical risk zone (older than 9 months).
     */
    public function isInCriticalRiskZone(): bool
    {
        return $this->getDaysSinceIncident() > 270; // 9 months ≈ 270 days
    }

    /**
     * Check if claim is in warning zone (6-9 months old).
     */
    public function isInWarningZone(): bool
    {
        $daysSince = $this->getDaysSinceIncident();
        return $daysSince >= 180 && $daysSince <= 270; // 6-9 months
    }

    /**
     * Check if claim is in safe zone (less than 6 months old).
     */
    public function isInSafeZone(): bool
    {
        return $this->getDaysSinceIncident() < 180; // Less than 6 months
    }

    /**
     * Get the compliance status color code.
     */
    public function getComplianceColorCode(): string
    {
        if ($this->isClaimExpired()) {
            return 'expired'; // Gray - no longer valid
        } elseif ($this->isInCriticalRiskZone()) {
            return 'red'; // Critical risk
        } elseif ($this->isInWarningZone()) {
            return 'amber'; // Warning
        }
        
        return 'green'; // Safe zone
    }

    /**
     * Get the compliance status text.
     */
    public function getComplianceStatusText(): string
    {
        if ($this->isClaimExpired()) {
            return 'EXPIRED - Claim is beyond the 12-month statutory timeframe';
        } elseif ($this->isInCriticalRiskZone()) {
            $daysRemaining = $this->getDaysRemainingUntilExpiry();
            return "CRITICAL RISK - {$daysRemaining} days remaining until statutory expiry";
        } elseif ($this->isInWarningZone()) {
            $daysRemaining = $this->getDaysRemainingUntilExpiry();
            return "WARNING - {$daysRemaining} days remaining until statutory expiry";
        }
        
        return 'COMPLIANT - Within safe submission window (less than 6 months)';
    }

    /**
     * Get months since incident for display.
     */
    public function getMonthsSinceIncident(): float
    {
        return round($this->getDaysSinceIncident() / 30.44, 1); // Average days per month
    }

    /**
     * RTW Tracking Methods
     */

    public function getRTWDaysElapsed(): ?int
    {
        if (!$this->rtw_start_date) {
            return null;
        }
        return now()->diffInDays($this->rtw_start_date);
    }

    public function getRTWDaysRemaining(): ?int
    {
        if (!$this->rtw_end_date) {
            return null;
        }
        $remaining = $this->rtw_end_date->diffInDays(now());
        return max(0, $remaining);
    }

    public function getRTWProgressPercentage(): ?int
    {
        if (!$this->rtw_start_date || !$this->rtw_end_date) {
            return null;
        }
        $total = $this->rtw_start_date->diffInDays($this->rtw_end_date);
        if ($total === 0) {
            return 100;
        }
        $elapsed = $this->getRTWDaysElapsed();
        $percentage = min(100, (int)round(($elapsed / $total) * 100));
        return max(0, $percentage);
    }

    public function isRTWReviewOverdue(): bool
    {
        if (!$this->rtw_next_review_date) {
            return false;
        }
        return now()->isAfter($this->rtw_next_review_date);
    }

    public function getMedicalRestrictions(): array
    {
        if (!$this->rtw_medical_restrictions) {
            return [];
        }
        return json_decode($this->rtw_medical_restrictions, true) ?? [];
    }

    public function getModifiedDuties(): array
    {
        if (!$this->rtw_modified_duties) {
            return [];
        }
        return json_decode($this->rtw_modified_duties, true) ?? [];
    }

    public function getWorkSchedule(): array
    {
        if (!$this->rtw_schedule) {
            return [];
        }
        return json_decode($this->rtw_schedule, true) ?? [];
    }

    public function getRTWStatusColor(): string
    {
        return match($this->rtw_status) {
            'not_started' => 'slate',
            'active' => 'blue',
            'on_track' => 'green',
            'requires_intervention' => 'orange',
            'completed' => 'emerald',
            'terminated' => 'red',
            default => 'slate',
        };
    }

    public function getRTWStatusLabel(): string
    {
        return match($this->rtw_status) {
            'not_started' => 'Not Started',
            'active' => 'Active',
            'on_track' => 'On Track',
            'requires_intervention' => 'Requires Intervention',
            'completed' => 'Completed',
            'terminated' => 'Terminated',
            default => 'Unknown',
        };
    }

    /**
     * Get all monitoring notes for this case.
     */
    public function monitoringNotes()
    {
        return $this->hasMany(MonitoringNote::class, 'case_id', 'id');
    }

    /**
     * Get all compliance checks for this case.
     */
    public function complianceChecks()
    {
        return $this->hasMany(ComplianceCheck::class, 'case_id', 'id');
    }

    /**
     * Get the current case holder (user responsible for the case).
     */
    public function currentHolder()
    {
        return $this->belongsTo(User::class, 'current_holder_id', 'id');
    }

    /**
     * Scope to filter by current stage.
     */
    public function scopeByStage($query, $stage)
    {
        return $query->where('current_stage', $stage);
    }

    /**
     * Scope to filter by current holder.
     */
    public function scopeByHolder($query, $holderId)
    {
        return $query->where('current_holder_id', $holderId);
    }

    /**
     * Scope to filter by case type.
     */
    public function scopeByCaseType($query, $caseType)
    {
        return $query->where('case_type', $caseType);
    }

    /**
     * Scope to get active cases.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
