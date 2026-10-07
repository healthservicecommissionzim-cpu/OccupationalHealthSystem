<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Case Dossier · HR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1180px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Alert */
        .alert { display: flex; gap: 14px; padding: 18px 22px; margin-bottom: 22px; border-radius: 10px; border-left: 4px solid; }
        .alert-warning { background: #fff8e7; border-color: #f59e0b; }
        .alert-warning strong { color: #624300; }
        .alert-warning p { color: #7d6332; }
        .alert-critical { background: #fee2e2; border-color: #dc4c4c; }
        .alert-critical strong { color: #7d1a1a; }
        .alert-critical p { color: #7d1a1a; }
        .alert-success { background: #30106b; border-color: #30106b; }
        .alert-success strong { color: #ffffff; }
        .alert-success p { color: #ffffff; }
        .alerticon { width: 36px; height: 36px; border-radius: 9px; display: grid; place-items: center; flex-shrink: 0; font-size: 18px; }
        .alert-warning .alerticon { background: #fff0c9; color: #c67a00; }
        .alert-critical .alerticon { background: #fecaca; color: #991b1b; }
        .alert-success .alerticon { background: rgba(255, 255, 255, 0.2); color: #ffffff; }
        .alert strong { display: block; font-size: 13px; margin-bottom: 6px; font-weight: 700; }
        .alert p { margin: 0; font-size: 12px; line-height: 1.65; }
        
        /* Grid Layout */
        .grid { display: grid; grid-template-columns: minmax(0, 1fr) 290px; gap: 22px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; border-top: 3px solid #079b6d; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .body { padding: 23px; }
        
        /* Details Grid */
        .details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 35px; }
        .detail label { display: block; color: #8a95a5; font-size: 9px; font-weight: 750; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 5px; }
        .detail strong { color: #101a31; font-size: 13px; background: #f8fafb; padding: 10px 12px; border-radius: 8px; display: block; border: 1px solid #e4e9ee; }
        
        /* Form Card */
        .intro { margin-bottom: 24px; color: #6e7a8d; font-size: 11.5px; line-height: 1.7; padding: 14px 16px; background: #f8fafb; border-left: 3px solid #079b6d; border-radius: 8px; }
        .labelrow { margin-bottom: 7px; color: #101a31; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; gap: 4px; }
        .required { color: #dc4c4c; font-size: 14px; }
        
        /* Checkboxes */
        .checkbox-group { display: flex; align-items: flex-start; gap: 12px; padding: 12px; background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 8px; margin-bottom: 12px; }
        .checkbox-group input[type="checkbox"] { margin-top: 2px; accent-color: #079b6d; cursor: pointer; }
        .checkbox-group label { font-size: 12px; color: #475569; line-height: 1.65; cursor: pointer; }
        
        /* Actions */
        .actions { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .buttons { display: flex; gap: 9px; }
        .btn { border-radius: 8px; padding: 10px 17px; font-size: 10px; font-weight: 700; border: 1px solid #d9e0e7; background: white; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #263149; }
        .btn:hover { background: #f8fafb; }
        .btn-primary { background: #079b6d; color: white; border-color: #079b6d; }
        .btn-primary:hover { background: #067c58; }
        
        /* Enhanced Witness & First Aid Buttons */
        .btn-secondary-lg { padding: 11px 18px; background: #079b6d; border: none; border-radius: 8px; font-size: 12px; font-weight: 700; color: white; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(7, 155, 109, 0.2); }
        .btn-secondary-lg:hover { background: #067c58; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(7, 155, 109, 0.3); }
        .btn-secondary-lg:active { transform: translateY(0); }
        .btn-secondary-lg i { font-size: 15px; }
        
        /* Modal */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal-overlay.active { display: flex; }
        .modal { background: white; border-radius: 14px; max-width: 800px; width: 100%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; max-height: 90vh; overflow-y: auto; margin: auto; }
        .modal-header { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; }
        .modal-header h2 { color: white; font-size: 16px; font-weight: 750; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-content { padding: 24px; }
        .modal-actions { padding: 20px 24px; border-top: 1px solid #e1e7ee; display: flex; gap: 8px; justify-content: flex-end; }
        
        .section-label { font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.7px; margin-bottom: 12px; display: block; }
        .field-label { font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 5px; }
        .field-value { font-size: 13px; font-weight: 600; color: #101a31; }
        
        /* Doc Item */
        .doc-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 8px; margin-bottom: 10px; }
        .doc-item i { color: #8a95a5; font-size: 16px; flex-shrink: 0; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-type { font-size: 12px; font-weight: 650; color: #101a31; margin-bottom: 3px; }
        .doc-name { font-size: 11px; color: #8a95a5; }
        .doc-status { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 9px; font-weight: 700; flex-shrink: 0; text-transform: uppercase; }
        .doc-status.verified { background: #d1f2eb; color: #0f766e; }
        .doc-status.pending { background: #fef3c7; color: #92400e; }
        
        @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        /* Responsive */
        @media (max-width: 900px) {
            .details { grid-template-columns: 1fr; }
            .grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-briefcase"></i></div>
                <div>
                    <h1 class="title">Case Dossier</h1>
                    <div class="sub">{{ $incident->reference_number }}</div>
                    <span class="case">HR Review Stage</span>
                </div>
            </div>
            <a href="{{ route('dashboards.hr') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Statutory Compliance Alerts -->
        @php
            $daysRemaining = $incident->getDaysRemainingUntilExpiry();
            $expiryDate = $incident->getExpiryDate();
            $monthsSince = $incident->getMonthsSinceIncident();
        @endphp

        @if($incident->isClaimExpired())
        <div class="alert alert-critical">
            <div class="alerticon"><i class="fa-solid fa-ban"></i></div>
            <div>
                <strong>CLAIM EXPIRED - STATUTORY DEADLINE EXCEEDED</strong>
                <p>This case is beyond the mandatory 12-month submission window and is no longer eligible for processing. This incident occurred {{ $monthsSince }} months ago and expired on {{ $expiryDate->format('M d, Y') }}.</p>
            </div>
        </div>
        @elseif($incident->isInCriticalRiskZone())
        <div class="alert alert-critical">
            <div class="alerticon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <strong>CRITICAL RISK: STATUTORY DEADLINE URGENT</strong>
                <p>This application is {{ $monthsSince }} months old and has only {{ $daysRemaining }} days remaining. It must be physically delivered to the Department of Social Services before {{ $expiryDate->format('M d, Y') }} to avoid statutory rejection.</p>
            </div>
        </div>
        @elseif($incident->isInWarningZone())
        <div class="alert alert-warning">
            <div class="alerticon"><i class="fa-solid fa-circle-exclamation"></i></div>
            <div>
                <strong>WARNING: APPROACHING STATUTORY DEADLINE</strong>
                <p>This application is {{ $monthsSince }} months old with {{ $daysRemaining }} days remaining before the 12-month statutory deadline. Plan for timely delivery by {{ $expiryDate->format('M d, Y') }}.</p>
            </div>
        </div>
        @else
        <div class="alert alert-success">
            <div class="alerticon"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <strong>COMPLIANT: SAFE SUBMISSION WINDOW</strong>
                <p>This application is within the safe submission window ({{ $monthsSince }} months old). Expiry date: {{ $expiryDate->format('M d, Y') }} ({{ $daysRemaining }} days remaining).</p>
            </div>
        </div>
        @endif

        <!-- Worker & Incident Details -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-user"></i> WORKER & INCIDENT INFORMATION</div>
                <div style="display: flex; gap: 12px;">
                    <button type="button" onclick="openWitnessModal()" class="btn-secondary-lg">
                        <i class="fa-solid fa-people-line"></i> Witness Statements
                    </button>
                    <button type="button" onclick="openFirstAidModal()" class="btn-secondary-lg">
                        <i class="fa-solid fa-heartbeat"></i> First Aid Records
                    </button>
                </div>
            </div>
            <div class="body">
                <span class="section-label">Worker Information</span>
                <div class="details" style="margin-bottom: 24px;">
                    <div class="detail">
                        <label>Full Name</label>
                        <strong>{{ $incident->first_name ?? $incident->employee?->name }} {{ $incident->surname ?? '' }}</strong>
                    </div>
                    <div class="detail">
                        <label>ID Number</label>
                        <strong>{{ $incident->id_number ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Department</label>
                        <strong>{{ $incident->department->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Designation</label>
                        <strong>{{ $incident->designation->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Phone Number</label>
                        <strong>{{ $incident->phone_number ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Email</label>
                        <strong>{{ $incident->email ?? 'N/A' }}</strong>
                    </div>
                </div>

                <span class="section-label">Incident Information</span>
                <div class="details">
                    <div class="detail">
                        <label>Incident Date</label>
                        <strong>{{ $incident->incident_date ? $incident->incident_date->format('d M Y') : 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Incident Time</label>
                        <strong>{{ $incident->incident_time ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Incident Type</label>
                        <strong>{{ $incident->incidentType->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Date Reported</label>
                        <strong>{{ $incident->created_at->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOD Report & Verification -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-clipboard-check"></i> HOD REPORT & SUPERVISOR VERIFICATION</div>
            </div>
            <div class="body">
                <div class="details" style="margin-bottom: 24px;">
                    <div class="detail">
                        <label>Supervisor</label>
                        <strong>{{ $incident->assignedSupervisor?->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail">
                        <label>Verified At</label>
                        <strong>{{ $incident->supervisor_verified_at ? $incident->supervisor_verified_at->format('d M Y H:i') : 'Pending' }}</strong>
                    </div>
                </div>

                @if($incident->hod_report)
                <span class="section-label">HOD Report</span>
                <div id="hodReportContainer" style="background: #f8fafb; padding: 14px 16px; border: 1px solid #e4e9ee; border-radius: 8px; margin-bottom: 12px; line-height: 1.7; color: #4e596c; font-size: 13px; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; white-space: pre-wrap; max-height: 200px; overflow: hidden; transition: max-height 0.3s ease;">
                    {{ $incident->hod_report }}
                </div>
                @php
                    $hodReportLength = strlen($incident->hod_report);
                    $shouldShowToggle = $hodReportLength > 300;
                @endphp
                @if($shouldShowToggle)
                <button type="button" onclick="toggleHodReport()" id="hodToggleBtn" style="background: white; border: 1px solid #e4e9ee; color: #079b6d; padding: 8px 14px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-chevron-down"></i> Show More
                </button>
                @endif
                <p style="font-size: 11px; color: #8a95a5;"><i class="fa-solid fa-user"></i> Submitted by {{ $incident->hodReportSubmittedBy?->name ?? 'Unknown' }} on {{ $incident->hod_report_submitted_at?->format('d M Y H:i') ?? 'N/A' }}</p>
                @else
                <p style="font-size: 13px; color: #8a95a5; font-style: italic;">No HOD report submitted yet</p>
                @endif
            </div>
        </div>

        <!-- HR Case File Acknowledgment -->
        @if(!$incident->hr_dossier_acknowledged_at)
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-file-signature"></i> ACKNOWLEDGE CASE FILE</div>
            </div>
            <div class="body">
                <form action="{{ route('incidents.acknowledge_dossier', ['incident' => $incident]) }}" method="POST">
                    @csrf
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="acknowledged" name="acknowledged" value="1" required>
                        <label for="acknowledged">I have reviewed all case file information including worker details, incident description, supervisor verification, HOD report, first aid records, and witness statements. I acknowledge receipt of this case file and am ready to proceed to document verification.</label>
                    </div>
                    @error('acknowledged')
                        <div style="color: #dc4c4c; font-size: 12px; margin-top: 8px;"><i class="fa-solid fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror

                    <div class="actions" style="margin-top: 20px;">
                        <a href="{{ route('dashboards.hr') }}" class="btn">Back to Dashboard</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Acknowledge & Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <!-- HR Review Confirmation -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-thumbs-up"></i> HR DOCUMENT VERIFICATION</div>
            </div>
            <div class="body">
                @if($incident->status === 'HR_APPROVED')
                <div class="alert alert-success">
                    <div class="alerticon"><i class="fa-solid fa-check-circle"></i></div>
                    <div>
                        <strong>Case Approved</strong>
                        <p>This case has been reviewed and approved. All documents have been verified for compliance.</p>
                    </div>
                </div>
                @else
                <div style="background: #eff6ff; padding: 16px; border-radius: 8px; border-left: 3px solid #3b82f6; margin-bottom: 20px;">
                    <p style="font-size: 13px; color: #1e40af; margin: 0;"><strong>Acknowledged on {{ $incident->hr_dossier_acknowledged_at?->format('d M Y H:i') }}</strong> - Ready for document verification.</p>
                </div>
                @endif

                @if($incident->attachments->count() > 0)
                <span class="section-label">Uploaded Documents</span>
                @php
                    $documentTypeLabels = [
                        'doctors_report' => "Doctor's Report",
                        'injury_report' => 'Injury Report',
                        'sick_leave' => 'Sick Leave Forms',
                        'treatment_records' => 'Treatment Records',
                        'national_id' => 'National ID',
                        'banking_details' => 'Banking Details',
                    ];
                @endphp
                @foreach($incident->attachments as $attachment)
                <div class="doc-item">
                    <i class="fa-solid fa-file-pdf"></i>
                    <div class="doc-info">
                        <div class="doc-type">{{ $documentTypeLabels[$attachment->document_type] ?? $attachment->document_type }}</div>
                        <div class="doc-name">{{ $attachment->file_name }}</div>
                    </div>
                    <span class="doc-status {{ $attachment->upload_status === 'VERIFIED' ? 'verified' : 'pending' }}">
                        <i class="fa-solid fa-{{ $attachment->upload_status === 'VERIFIED' ? 'check' : 'clock' }}"></i>
                        {{ $attachment->upload_status === 'VERIFIED' ? 'Verified' : 'Pending' }}
                    </span>
                </div>
                @endforeach
                @endif

                <div class="actions" style="margin-top: 20px;">
                    <a href="{{ route('dashboards.hr') }}" class="btn">Back to Dashboard</a>
                    @if($incident->status !== 'HR_APPROVED')
                    <a href="{{ route('incidents.document_verification', ['incident' => $incident]) }}" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Proceed to Verification
                    </a>
                    @else
                    <div class="btn btn-primary" style="opacity: 1; cursor: default; background: #22c55e;">
                        <i class="fa-solid fa-check-circle"></i> Approved & Verified
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Witness Statements Modal -->
    <div class="modal-overlay" id="witnessModal">
        <div class="modal" style="animation: slideIn 0.3s ease;">
            <div class="modal-header">
                <h2><i class="fa-solid fa-people-line"></i> Witness Statements</h2>
                <button class="modal-close" onclick="closeWitnessModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-content">
                @if($incident->witnessTokens && $incident->witnessTokens->count() > 0)
                    @foreach($incident->witnessTokens as $witness)
                    <div style="background: #f8fafb; padding: 12px; border-radius: 8px; margin-bottom: 10px; border: 1px solid #e4e9ee;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                            <div>
                                <div style="font-size: 12px; font-weight: 650; color: #101a31;">{{ $witness->witness_name ?? 'Witness' }}</div>
                                <div style="font-size: 11px; color: #8a95a5; margin-top: 2px;">{{ $witness->phone_number ?? 'No phone' }}</div>
                            </div>
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 9px; font-weight: 700; background: {{ $witness->statement_submitted_at ? '#d1f2eb' : '#fef3c7' }}; color: {{ $witness->statement_submitted_at ? '#0f766e' : '#92400e' }}; text-transform: uppercase;">
                                <i class="fa-solid fa-{{ $witness->statement_submitted_at ? 'check' : 'clock' }}"></i> {{ $witness->statement_submitted_at ? 'Completed' : 'Pending' }}
                            </span>
                        </div>
                        <p style="font-size: 12px; color: #4e596c; line-height: 1.6; margin-top: 8px;">{{ trim($witness->statement) ?? 'No statement provided' }}</p>
                    </div>
                    @endforeach
                @else
                    <p style="font-size: 13px; color: #8a95a5; text-align: center; padding: 30px;">No witness statements yet</p>
                @endif
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" onclick="closeWitnessModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- First Aid Records Modal -->
    <div class="modal-overlay" id="firstAidModal">
        <div class="modal" style="animation: slideIn 0.3s ease;">
            <div class="modal-header">
                <h2><i class="fa-solid fa-heartbeat"></i> First Aid Records</h2>
                <button class="modal-close" onclick="closeFirstAidModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-content">
                @if($incident->firstAidRecords && $incident->firstAidRecords->count() > 0)
                    @foreach($incident->firstAidRecords as $record)
                    <div style="background: #f8fafb; padding: 14px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e4e9ee;">
                        <div style="font-size: 11px; font-weight: 700; color: #079b6d; text-transform: uppercase; margin-bottom: 12px;">Record #{{ $loop->iteration }}</div>
                        
                        <div style="font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 8px;">Treatment Provider</div>
                        <div style="font-size: 12px; color: #101a31; margin-bottom: 12px;">{{ $record->nurse_name ?? 'N/A' }} ({{ $record->nurse_license_number ?? 'N/A' }})</div>

                        <div style="font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 8px;">Injury Description</div>
                        <p style="font-size: 12px; color: #4e596c; margin: 0 0 12px 0; line-height: 1.6;">{{ $record->injury_description ?? 'N/A' }}</p>

                        <div style="font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 8px;">First Aid Provided</div>
                        <p style="font-size: 12px; color: #4e596c; margin: 0 0 12px 0; line-height: 1.6;">{{ $record->first_aid_description ?? 'N/A' }}</p>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 12px;">
                            <div>
                                <div style="font-size: 10px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 4px;">Time Administered</div>
                                <div style="color: #101a31;">{{ $record->time_administered ? $record->time_administered->format('d M Y H:i') : 'N/A' }}</div>
                            </div>
                            <div>
                                <div style="font-size: 10px; font-weight: 700; color: #8a95a5; text-transform: uppercase; margin-bottom: 4px;">Severity Level</div>
                                <div style="color: #101a31;">{{ $record->severity_level ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p style="font-size: 13px; color: #8a95a5; text-align: center; padding: 30px;">No first aid records yet</p>
                @endif
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" onclick="closeFirstAidModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openWitnessModal() {
            document.getElementById('witnessModal').classList.add('active');
        }
        function closeWitnessModal() {
            document.getElementById('witnessModal').classList.remove('active');
        }
        function openFirstAidModal() {
            document.getElementById('firstAidModal').classList.add('active');
        }
        function closeFirstAidModal() {
            document.getElementById('firstAidModal').classList.remove('active');
        }

        // HOD Report Show More/Less Toggle
        function toggleHodReport() {
            const container = document.getElementById('hodReportContainer');
            const btn = document.getElementById('hodToggleBtn');
            
            if (container.style.maxHeight === '200px') {
                container.style.maxHeight = 'none';
                btn.innerHTML = '<i class="fa-solid fa-chevron-up"></i> Show Less';
            } else {
                container.style.maxHeight = '200px';
                btn.innerHTML = '<i class="fa-solid fa-chevron-down"></i> Show More';
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            const witnessModal = document.getElementById('witnessModal');
            const firstAidModal = document.getElementById('firstAidModal');
            if (e.target === witnessModal) closeWitnessModal();
            if (e.target === firstAidModal) closeFirstAidModal();
        });

        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWitnessModal();
                closeFirstAidModal();
            }
        });
    </script>
</body>
</html>
