<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IODDocumentChecklist extends Model
{
    protected $table = 'iod_document_checklists';

    protected $fillable = [
        'incident_id',
        'doctors_report_uploaded',
        'injury_report_uploaded',
        'sick_leave_uploaded',
        'witness_statements_2plus_uploaded',
        'job_duties_uploaded',
        'national_id_uploaded',
        'bank_statements_uploaded',
        'treatment_records_uploaded',
        'banking_details_uploaded',
        'contact_details_uploaded',
        'late_submission_reason_uploaded',
        'doctors_report_verified',
        'injury_report_verified',
        'sick_leave_verified',
        'witness_statements_verified',
        'job_duties_verified',
        'national_id_verified',
        'bank_statements_verified',
        'treatment_records_verified',
        'banking_details_verified',
        'checklist_status',
        'documents_uploaded_count',
        'documents_verified_count',
        'total_required_documents',
        'completion_percentage',
        'worker_notified_incomplete_at',
        'worker_reminder_sent_at',
        'all_documents_submitted',
        'all_submitted_at',
        'verified_by_user_id',
        'verification_completed_at',
        'verification_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'doctors_report_uploaded' => 'boolean',
        'injury_report_uploaded' => 'boolean',
        'sick_leave_uploaded' => 'boolean',
        'witness_statements_2plus_uploaded' => 'boolean',
        'job_duties_uploaded' => 'boolean',
        'national_id_uploaded' => 'boolean',
        'bank_statements_uploaded' => 'boolean',
        'treatment_records_uploaded' => 'boolean',
        'banking_details_uploaded' => 'boolean',
        'contact_details_uploaded' => 'boolean',
        'late_submission_reason_uploaded' => 'boolean',
        'doctors_report_verified' => 'boolean',
        'injury_report_verified' => 'boolean',
        'sick_leave_verified' => 'boolean',
        'witness_statements_verified' => 'boolean',
        'job_duties_verified' => 'boolean',
        'national_id_verified' => 'boolean',
        'bank_statements_verified' => 'boolean',
        'treatment_records_verified' => 'boolean',
        'banking_details_verified' => 'boolean',
        'all_documents_submitted' => 'boolean',
        'worker_notified_incomplete_at' => 'datetime',
        'worker_reminder_sent_at' => 'datetime',
        'all_submitted_at' => 'datetime',
        'verification_completed_at' => 'datetime',
    ];

    /**
     * Get the incident this checklist belongs to
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the user who verified this checklist
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Check if all required documents are uploaded
     */
    public function allRequiredDocumentsUploaded(): bool
    {
        return $this->doctors_report_uploaded &&
               $this->injury_report_uploaded &&
               $this->sick_leave_uploaded &&
               $this->witness_statements_2plus_uploaded &&
               $this->job_duties_uploaded &&
               $this->national_id_uploaded &&
               $this->bank_statements_uploaded &&
               $this->treatment_records_uploaded &&
               $this->banking_details_uploaded &&
               $this->contact_details_uploaded;
    }

    /**
     * Check if all required documents are verified
     */
    public function allRequiredDocumentsVerified(): bool
    {
        return $this->doctors_report_verified &&
               $this->injury_report_verified &&
               $this->sick_leave_verified &&
               $this->witness_statements_verified &&
               $this->job_duties_verified &&
               $this->national_id_verified &&
               $this->bank_statements_verified &&
               $this->treatment_records_verified &&
               $this->banking_details_verified;
    }

    /**
     * Calculate completion percentage
     */
    public function calculateCompletionPercentage(): int
    {
        $uploaded = 0;
        $required = 10; // Total required documents (excluding late submission reason which is conditional)

        if ($this->doctors_report_uploaded) $uploaded++;
        if ($this->injury_report_uploaded) $uploaded++;
        if ($this->sick_leave_uploaded) $uploaded++;
        if ($this->witness_statements_2plus_uploaded) $uploaded++;
        if ($this->job_duties_uploaded) $uploaded++;
        if ($this->national_id_uploaded) $uploaded++;
        if ($this->bank_statements_uploaded) $uploaded++;
        if ($this->treatment_records_uploaded) $uploaded++;
        if ($this->banking_details_uploaded) $uploaded++;
        if ($this->contact_details_uploaded) $uploaded++;

        return intval(($uploaded / $required) * 100);
    }

    /**
     * Get list of missing documents
     */
    public function getMissingDocuments(): array
    {
        $missing = [];

        if (!$this->doctors_report_uploaded) {
            $missing[] = 'Doctor\'s Report (with degree of disability)';
        }
        if (!$this->injury_report_uploaded) {
            $missing[] = 'Injury Report Form ZII49 (duly completed)';
        }
        if (!$this->sick_leave_uploaded) {
            $missing[] = 'Duly Completed Sick Leave Forms';
        }
        if (!$this->witness_statements_2plus_uploaded) {
            $missing[] = 'Eye Witness Statements (at least 2 required)';
        }
        if (!$this->job_duties_uploaded) {
            $missing[] = 'Description of Duties at Time of Injury (from Head of Department)';
        }
        if (!$this->national_id_uploaded) {
            $missing[] = 'Certified Copy of National Registration Card';
        }
        if (!$this->bank_statements_uploaded) {
            $missing[] = 'Bank Statements (ZiG and USD) with Name and Account Number';
        }
        if (!$this->treatment_records_uploaded) {
            $missing[] = 'Certified Copies of Initial Treatment Cards';
        }
        if (!$this->banking_details_uploaded) {
            $missing[] = 'Banking Details Form (Completed by Member)';
        }
        if (!$this->contact_details_uploaded) {
            $missing[] = 'Contact Details (Cell Number and Address)';
        }

        return $missing;
    }

    /**
     * Get missing documents as formatted string
     */
    public function getMissingDocumentsString(): string
    {
        $missing = $this->getMissingDocuments();
        if (empty($missing)) {
            return 'All required documents uploaded';
        }
        return implode(', ', $missing);
    }

    /**
     * Update checklist status based on uploaded documents
     */
    public function updateStatus(): void
    {
        $this->documents_uploaded_count = collect([
            $this->doctors_report_uploaded,
            $this->injury_report_uploaded,
            $this->sick_leave_uploaded,
            $this->witness_statements_2plus_uploaded,
            $this->job_duties_uploaded,
            $this->national_id_uploaded,
            $this->bank_statements_uploaded,
            $this->treatment_records_uploaded,
            $this->banking_details_uploaded,
            $this->contact_details_uploaded,
        ])->filter()->count();

        $this->documents_verified_count = collect([
            $this->doctors_report_verified,
            $this->injury_report_verified,
            $this->sick_leave_verified,
            $this->witness_statements_verified,
            $this->job_duties_verified,
            $this->national_id_verified,
            $this->bank_statements_verified,
            $this->treatment_records_verified,
            $this->banking_details_verified,
        ])->filter()->count();

        $this->completion_percentage = $this->calculateCompletionPercentage();

        if ($this->allRequiredDocumentsUploaded()) {
            $this->all_documents_submitted = true;
            $this->all_submitted_at = now();
            $this->checklist_status = 'PENDING_VERIFICATION';
        } elseif ($this->documents_uploaded_count > 0) {
            $this->checklist_status = 'INCOMPLETE';
        }

        if ($this->allRequiredDocumentsVerified()) {
            $this->checklist_status = 'VERIFIED';
        }

        $this->save();
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match ($this->checklist_status) {
            'VERIFIED' => 'green',
            'PENDING_VERIFICATION' => 'blue',
            'INCOMPLETE' => 'orange',
            'REJECTED' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope for incomplete checklists
     */
    public function scopeIncomplete($query)
    {
        return $query->where('checklist_status', 'INCOMPLETE');
    }

    /**
     * Scope for pending verification
     */
    public function scopePendingVerification($query)
    {
        return $query->where('checklist_status', 'PENDING_VERIFICATION');
    }

    /**
     * Scope for verified checklists
     */
    public function scopeVerified($query)
    {
        return $query->where('checklist_status', 'VERIFIED');
    }

    /**
     * Scope for overdue checklists (>30 days without completion)
     */
    public function scopeOverdue($query)
    {
        return $query->where('checklist_status', '!=', 'VERIFIED')
                    ->where('created_at', '<', now()->subDays(30));
    }
}
