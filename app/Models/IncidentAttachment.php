<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentAttachment extends Model
{
    protected $fillable = [
        'incident_id',
        'witness_token_id',
        'document_type',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'upload_status',
        'upload_notes',
        'uploaded_by_user_id',
        'verified_by_user_id',
        'verified_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    const DOCUMENT_TYPES = [
        'doctors_report' => "Doctor's Report",
        'injury_report' => 'Injury Report from Z1149',
        'sick_leave' => 'Sick Leave Forms',
        'treatment_records' => 'Certified Copies of Initial Treatment Records',
        'national_id' => 'Certified Copy of National Registration Card',
        'banking_details' => 'Banking Details Forms',
        'bank_statement' => 'Bank Statements (Nostro & ZiG)',
        'police_report' => 'Police Report',
        'death_certificate' => 'Death Certificate',
        'heir_certificate' => 'Certificate of Heir/Letter of Administration/Certificate of Guardianship',
        'db8_form' => 'DB8 Form',
        'termination_letter' => 'Letter of Termination of Service',
        'marriage_certificate' => 'Certified Copy of Marriage Certificate/Marriage Confirmation',
        'NSSA_FORM_WCA1' => 'NSSA Form W.C.A. 1 (Workers\' Compensation Claim)',
        'INITIAL_MEDICAL_TREATMENT' => 'Initial Medical Treatment Records',
        'OTHER' => 'Other Documentation',
    ];

    const UPLOAD_STATUSES = [
        'PENDING' => 'Pending Verification',
        'VERIFIED' => 'Verified',
        'REJECTED' => 'Rejected',
    ];

    /**
     * Get the incident that this attachment belongs to.
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Get the user who uploaded this attachment.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    /**
     * Get the user who verified this attachment.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Check if attachment is verified.
     */
    public function isVerified(): bool
    {
        return $this->upload_status === 'VERIFIED';
    }

    /**
     * Verify the attachment.
     */
    public function verify(int $userId, ?string $notes = null): void
    {
        $this->update([
            'upload_status' => 'VERIFIED',
            'verified_by_user_id' => $userId,
            'verified_at' => now(),
            'upload_notes' => $notes,
        ]);
    }

    /**
     * Reject the attachment.
     */
    public function reject(int $userId, string $reason): void
    {
        $this->update([
            'upload_status' => 'REJECTED',
            'verified_by_user_id' => $userId,
            'verified_at' => now(),
            'upload_notes' => $reason,
        ]);
    }

    /**
     * Get human-readable document type.
     */
    public function getDocumentTypeLabel(): string
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get file download URL.
     */
    public function getDownloadUrl(): string
    {
        return route('incident-attachments.download', $this->id);
    }
}
