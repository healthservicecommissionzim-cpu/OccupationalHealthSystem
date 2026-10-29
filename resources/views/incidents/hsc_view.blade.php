<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HSC Review · Incident</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem; }
        .header-left { display: flex; align-items: center; gap: 1.5rem; }
        .header-icon { width: 52px; height: 52px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 24px; flex-shrink: 0; }
        .header h1 { font-size: 28px; font-weight: 750; color: #101a31; }
        .header p { font-size: 13px; color: #718096; margin-top: 6px; }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.5rem;
            background: white;
            color: #079b6d;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 650;
            border: 1px solid #ccd6df;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .back-btn:hover {
            background: #f8fafb;
            border-color: #079b6d;
        }
        .back-btn i { font-size: 14px; }
        .card { background: white; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 2rem; }
        .card-header { padding: 1.5rem 1.75rem; border-bottom: 1px solid #e1e7ee; background: #f8fafb; }
        .card-header h3 { font-size: 12px; font-weight: 750; color: #101a31; text-transform: uppercase; letter-spacing: .55px; margin: 0; display: flex; align-items: center; gap: 10px; }
        .card-header h3 i { color: #079b6d; font-size: 16px; }
        .card-body { padding: 1.75rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .field { display: flex; flex-direction: column; }
        .field-label { font-size: 10px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .field-value { font-size: 13px; font-weight: 600; color: #263149; }
        .field-desc { font-size: 13px; color: #4e596c; line-height: 1.6; margin-top: 6px; }
        .col-span-2 { grid-column: 1 / -1; }
        .alert { padding: 14px 16px; border-radius: 8px; border-left: 4px solid; margin-bottom: 1rem; font-size: 12px; }
        .alert-info { background: #eff6ff; border-color: #3b82f6; }
        .alert-info p { color: #1e40af; margin: 0; }
        .alert-info strong { font-weight: 700; }
        .alert-success { background: #dcfce7; border-color: #22c55e; }
        .alert-success p { color: #15803d; margin: 0; }
        .doc-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafb; border: 1px solid #e1e7ee; border-radius: 8px; margin-bottom: 8px; }
        .doc-item:last-child { margin-bottom: 0; }
        .doc-item i { color: #a5aab8; font-size: 16px; flex-shrink: 0; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-type { font-size: 12px; font-weight: 600; color: #101a31; margin-bottom: 2px; }
        .doc-name { font-size: 11px; color: #718096; }
        .doc-status { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; flex-shrink: 0; }
        .doc-status.verified { background: #dcfce7; color: #15803d; }
        .doc-status.pending { background: #fef3c7; color: #92400e; }
        .witness-item { display: flex; flex-direction: column; padding: 12px; background: #f8fafb; border: 1px solid #e1e7ee; border-radius: 8px; margin-bottom: 8px; }
        .witness-item:last-child { margin-bottom: 0; }
        .witness-name { font-size: 12px; font-weight: 600; color: #101a31; margin-bottom: 6px; }
        .witness-statement { font-size: 12px; color: #4e596c; line-height: 1.6; margin: 8px 0; }
        .witness-date { font-size: 11px; color: #a5aab8; }
        .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .status-completed { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .read-only-badge { display: inline-flex; align-items: center; gap: 6px; padding: 10px 14px; background: #f0f4f8; border: 1px solid #d4dce6; border-radius: 8px; font-size: 11px; color: #4e596c; font-weight: 600; margin-bottom: 1.5rem; }
        .read-only-badge i { color: #8a95a5; }
        .link-btn { padding: 7px 14px; font-size: 11px; background: #079b6d; color: white; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; font-weight: 600; }
        .link-btn:hover { background: #067c58; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-icon"><i class="fa-solid fa-eye"></i></div>
                <div>
                    <h1>Case Dossier</h1>
                    <p>{{ $incident->reference_number }}</p>
                </div>
            </div>
            <a href="{{ route('dashboards.hsc_conditions') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Read-Only Badge -->
        <div class="read-only-badge">
            <i class="fa-solid fa-lock"></i>
            <span>Read-Only Access — You can view this case but cannot make modifications</span>
        </div>

        <!-- Roster Context (if rotational staff) -->
        @include('incidents.partials.roster-context', ['incident' => $incident])

        <!-- Incident Details Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-file-lines"></i> Incident Details</h3>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Worker Name</span>
                        <span class="field-value">{{ $incident->first_name }} {{ $incident->surname }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">ID Number</span>
                        <span class="field-value">{{ $incident->id_number }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Department</span>
                        <span class="field-value">{{ $incident->department->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Designation</span>
                        <span class="field-value">{{ $incident->designation->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Incident Date</span>
                        <span class="field-value">{{ $incident->incident_date->format('d M Y') }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Incident Time</span>
                        <span class="field-value">{{ $incident->incident_time }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Incident Type</span>
                        <span class="field-value">{{ $incident->incidentType->name ?? 'Not Specified' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Facility</span>
                        <span class="field-value">{{ $incident->facility->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Current Status</span>
                        <span class="field-value">{{ str_replace('_', ' ', $incident->status) }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Reported Date</span>
                        <span class="field-value">{{ $incident->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="field col-span-2">
                        <span class="field-label">Description</span>
                        <span class="field-desc">{{ $incident->incident_description }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supervisor Verification Card -->
        @if($incident->supervisor_verified_at)
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-user-check"></i> Supervisor Verification</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    <p><i class="fa-solid fa-check-circle"></i> Verified by Supervisor</p>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Verified By</span>
                        <span class="field-value">{{ $incident->assignedSupervisor->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Verification Date</span>
                        <span class="field-value">{{ $incident->supervisor_verified_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">On-Duty Status</span>
                        <span class="field-value">
                            @if($incident->on_duty_confirmation)
                                <span style="color: #22c55e; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Yes, On Duty</span>
                            @else
                                <span style="color: #ef4444; font-weight: 600;"><i class="fa-solid fa-times-circle"></i> No, Not On Duty</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Head of Department Report Card -->
        @if($incident->hod_report)
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-file-signature"></i> Head of Department Report</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                    <p><i class="fa-solid fa-check-circle"></i> Report Submitted</p>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Submitted By</span>
                        <span class="field-value">{{ $incident->hodReportSubmittedBy->name ?? 'N/A' }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Submitted Date</span>
                        <span class="field-value">{{ $incident->hod_report_submitted_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="field col-span-2">
                        <span class="field-label">Report Content</span>
                        <span class="field-desc">{{ $incident->hod_report }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Witness Statements Card -->
        @if($incident->witnessTokens && $incident->witnessTokens->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-users"></i> Witness Statements ({{ $incident->witnessTokens->count() }})</h3>
            </div>
            <div class="card-body">
                @foreach($incident->witnessTokens as $token)
                <div class="witness-item">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <span class="witness-name">{{ $token->witness_name ?? 'Witness' }}</span>
                        @if($token->status === 'COMPLETED')
                        <span class="status-badge status-completed">
                            <i class="fa-solid fa-check-circle"></i> Completed
                        </span>
                        @else
                        <span class="status-badge status-pending">
                            <i class="fa-solid fa-clock"></i> Pending
                        </span>
                        @endif
                    </div>
                    @if($token->witness_statement)
                    <div class="witness-statement">{{ $token->witness_statement }}</div>
                    @endif
                    @if($token->created_at)
                    <div class="witness-date">{{ $token->created_at->diffForHumans() }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- First Aid Records Card -->
        @php
            $firstAidRecord = $incident->firstAidRecords->first();
        @endphp
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-heart-pulse"></i> First Aid Record</h3>
            </div>
            <div class="card-body">
                @if($firstAidRecord)
                    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                        <p><i class="fa-solid fa-check-circle"></i> First Aid Record Submitted</p>
                    </div>
                    
                    <div class="grid-2">
                        <div class="field">
                            <span class="field-label">Nurse/First Aider Name</span>
                            <span class="field-value">{{ $firstAidRecord->nurse_name ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">License Number</span>
                            <span class="field-value">{{ $firstAidRecord->nurse_license_number ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Time Administered</span>
                            <span class="field-value">{{ $firstAidRecord->time_administered?->format('d M Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Location of First Aid</span>
                            <span class="field-value">{{ $firstAidRecord->location ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Severity Level</span>
                            <span class="field-value">
                                @if($firstAidRecord->severity_level)
                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600;
                                        @if($firstAidRecord->severity_level === 'CRITICAL') background: #fee2e2; color: #991b1b;
                                        @elseif($firstAidRecord->severity_level === 'SEVERE') background: #fecaca; color: #7c2d12;
                                        @elseif($firstAidRecord->severity_level === 'MODERATE') background: #fcd34d; color: #92400e;
                                        @else background: #dcfce7; color: #065f46;
                                        @endif
                                    ">
                                        {{ ucfirst(strtolower($firstAidRecord->severity_level)) }}
                                    </span>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="field">
                            <span class="field-label">Record Status</span>
                            <span class="field-value">
                                @if($firstAidRecord->status === 'approved')
                                    <span style="color: #22c55e; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Approved</span>
                                @elseif($firstAidRecord->status === 'rejected')
                                    <span style="color: #ef4444; font-weight: 600;"><i class="fa-solid fa-times-circle"></i> Rejected</span>
                                @else
                                    <span style="color: #f59e0b; font-weight: 600;"><i class="fa-solid fa-clock"></i> Pending Review</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="field col-span-2">
                            <span class="field-label">Injury Description</span>
                            <span class="field-desc">{{ $firstAidRecord->injury_description ?? 'No description provided' }}</span>
                        </div>
                        
                        <div class="field col-span-2">
                            <span class="field-label">First Aid Provided</span>
                            <span class="field-desc">{{ $firstAidRecord->first_aid_description ?? 'No description provided' }}</span>
                        </div>
                        
                        @if($firstAidRecord->notes)
                        <div class="field col-span-2">
                            <span class="field-label">Additional Notes</span>
                            <span class="field-desc">{{ $firstAidRecord->notes }}</span>
                        </div>
                        @endif
                        
                        @if($firstAidRecord->status !== 'pending' && $firstAidRecord->reviewed_by)
                        <div class="field col-span-2">
                            <span class="field-label">Review Information</span>
                            <div style="font-size: 13px; color: #4e596c; line-height: 1.6; margin-top: 6px;">
                                <p style="margin: 0 0 6px 0;"><strong>Reviewed By:</strong> {{ $firstAidRecord->reviewer?->name ?? 'Unknown' }}</p>
                                <p style="margin: 0 0 6px 0;"><strong>Reviewed At:</strong> {{ $firstAidRecord->reviewed_at?->format('d M Y H:i') ?? 'N/A' }}</p>
                                @if($firstAidRecord->review_comments)
                                <p style="margin: 0;"><strong>Comments:</strong> {{ $firstAidRecord->review_comments }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-info">
                        <p><i class="fa-solid fa-info-circle"></i> <strong>No First Aid Record</strong> — The nurse or person who rendered first aid must submit their record using the provided First Aid Token.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Supporting Documents Card -->
        @php
            $allDocuments = $incident->attachments ?? collect();
        @endphp
        @if($allDocuments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-paperclip"></i> Supporting Documents ({{ $allDocuments->count() }})</h3>
            </div>
            <div class="card-body">
                @foreach($allDocuments as $attachment)
                <div class="doc-item">
                    <i class="fa-solid fa-file"></i>
                    <div class="doc-info">
                        <div class="doc-type">{{ \App\Models\IncidentAttachment::DOCUMENT_TYPES[$attachment->document_type] ?? 'Document' }}</div>
                        <div class="doc-name">{{ $attachment->file_name }}</div>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
                        <span class="doc-status {{ strtolower($attachment->upload_status) }}">
                            <i class="fa-solid fa-{{ $attachment->upload_status === 'VERIFIED' ? 'check' : 'clock' }}"></i> {{ $attachment->upload_status }}
                        </span>
                        <a href="{{ route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment]) }}" target="_blank" class="link-btn">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        <a href="{{ route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment]) }}" download class="link-btn" style="background: #f8fafb; color: #079b6d; border: 1px solid #d4dce6;">
                            <i class="fa-solid fa-download"></i> Download
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- HR Review Notes (if available) -->
        @if($incident->hr_review_notes)
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-clipboard-check"></i> HR Review Notes</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                    <p><i class="fa-solid fa-check-circle"></i> HR Review Completed</p>
                </div>
                <div class="grid-2">
                    <div class="field col-span-2">
                        <span class="field-label">Review Notes</span>
                        <span class="field-desc">{{ $incident->hr_review_notes }}</span>
                    </div>
                    @if($incident->hr_review_date)
                    <div class="field">
                        <span class="field-label">Reviewed Date</span>
                        <span class="field-value">{{ $incident->hr_review_date->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Clinical Executive Review (if available) -->
        @if($incident->status && in_array($incident->status, ['ESCALATED_TO_DMO', 'ESCALATED_TO_MEDICAL_SUPERINTENDENT', 'ESCALATED_TO_CMO']))
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-stethoscope"></i> Clinical Executive Review</h3>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Current Status</span>
                        <span class="field-value">{{ str_replace('_', ' ', $incident->status) }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Escalated Date</span>
                        <span class="field-value">{{ $incident->escalated_to_dmo_at?->format('d M Y H:i') ?? 'N/A' }}</span>
                    </div>
                    @if($incident->executive_audit_verified)
                    <div class="field">
                        <span class="field-label">Audit Status</span>
                        <span class="field-value">
                            <span style="color: #22c55e; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Verified</span>
                        </span>
                    </div>
                    @endif
                    @if($incident->executive_recommendation_submitted_at)
                    <div class="field">
                        <span class="field-label">Recommendation Submitted</span>
                        <span class="field-value">{{ $incident->executive_recommendation_submitted_at->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- PMD Review (if available) -->
        @if($incident->status && in_array($incident->status, ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW']))
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-briefcase-medical"></i> Provincial Medical Directorate Review</h3>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Current Status</span>
                        <span class="field-value">{{ str_replace('_', ' ', $incident->status) }}</span>
                    </div>
                    <div class="field">
                        <span class="field-label">Escalated to PMD</span>
                        <span class="field-value">{{ $incident->escalated_to_pmd_at?->format('d M Y H:i') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Case Resolution (if resolved or closed) -->
        @if($incident->status && in_array($incident->status, ['RESOLVED', 'CLOSED', 'APPROVED_BY_PMD']))
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-circle-check"></i> Case Resolution</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    <p><i class="fa-solid fa-check-circle"></i> Case Resolved</p>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Final Status</span>
                        <span class="field-value">
                            <span style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #15803d;">
                                {{ str_replace('_', ' ', $incident->status) }}
                            </span>
                        </span>
                    </div>
                    <div class="field">
                        <span class="field-label">Resolved Date</span>
                        <span class="field-value">{{ $incident->resolved_at?->format('d M Y H:i') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Case Rejection (if rejected) -->
        @if($incident->status && in_array($incident->status, ['REJECTED_BY_HR', 'REJECTED', 'REJECTED_SUPERVISOR']))
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-circle-xmark"></i> Case Rejection</h3>
            </div>
            <div class="card-body">
                <div style="padding: 14px 16px; border-radius: 8px; border-left: 4px solid; background: #fee2e2; border-color: #ef4444; margin-bottom: 1.5rem;">
                    <p style="color: #991b1b; margin: 0; font-size: 12px;"><i class="fa-solid fa-times-circle"></i> Case Rejected</p>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <span class="field-label">Rejection Status</span>
                        <span class="field-value">
                            <span style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                                {{ str_replace('_', ' ', $incident->status) }}
                            </span>
                        </span>
                    </div>
                    <div class="field">
                        <span class="field-label">Rejection Date</span>
                        <span class="field-value">{{ $incident->rejected_at?->format('d M Y H:i') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Compliance Notes (if available) -->
        @if($incident->compliance_notes)
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-clipboard"></i> Compliance Notes</h3>
            </div>
            <div class="card-body">
                <div class="field col-span-2">
                    <span class="field-desc">{{ $incident->compliance_notes }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Workflow Timeline Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-timeline"></i> Complete Workflow Timeline</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if($incident->created_at)
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #079b6d; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-file"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Stage 1: Incident Reported</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->supervisor_verified_at)
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #079b6d; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-check"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Stage 2: Supervisor Verified</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->supervisor_verified_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->hod_report_submitted_at)
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #079b6d; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-file-signature"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Stage 3: HOD Report Submitted</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->hod_report_submitted_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->escalated_to_hr_at)
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #079b6d; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-arrow-up"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Stage 4: Escalated to HR</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->escalated_to_hr_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->status && in_array($incident->status, ['ESCALATED_TO_DMO', 'ESCALATED_TO_MEDICAL_SUPERINTENDENT', 'ESCALATED_TO_CMO', 'ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW', 'RESOLVED', 'CLOSED', 'APPROVED_BY_PMD']))
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #079b6d; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-stethoscope"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Stage 5: Clinical Executive Review</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->escalated_to_dmo_at?->format('d M Y H:i') ?? 'Pending' }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->status && in_array($incident->status, ['ESCALATED_TO_PMD', 'PENDING_PMD_REVIEW', 'RESOLVED', 'CLOSED', 'APPROVED_BY_PMD']))
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #079b6d; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-briefcase-medical"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Stage 6: PMD Review</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->escalated_to_pmd_at?->format('d M Y H:i') ?? 'Pending' }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->status && in_array($incident->status, ['RESOLVED', 'CLOSED', 'APPROVED_BY_PMD']))
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #22c55e; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-circle-check"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Case Closed/Resolved</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->resolved_at?->format('d M Y H:i') ?? 'Completed' }}</div>
                        </div>
                    </div>
                    @endif

                    @if($incident->status && in_array($incident->status, ['REJECTED_BY_HR', 'REJECTED', 'REJECTED_SUPERVISOR']))
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="width: 20px; height: 20px; background: #ef4444; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;"><i class="fa-solid fa-circle-xmark"></i></div>
                        <div>
                            <div style="font-size: 12px; font-weight: 600; color: #101a31;">Case Rejected</div>
                            <div style="font-size: 11px; color: #8a95a5;">{{ $incident->rejected_at?->format('d M Y H:i') ?? 'Rejected' }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e1e7ee; text-align: center;">
            <p style="font-size: 11px; color: #8a95a5;">
                <i class="fa-solid fa-info-circle"></i> This is a comprehensive read-only view showing the complete case history from initial report through final closure.
            </p>
        </div>
    </div>
</body>
</html>
