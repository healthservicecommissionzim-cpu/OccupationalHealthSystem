<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document Verification · HR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Inter, 'Segoe UI', Arial, sans-serif; background: #f5f7fa; padding: 2rem; min-height: 100vh; color: #263149; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem; }
        .header-content { display: flex; gap: 1.5rem; align-items: flex-start; }
        .header-icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; flex-shrink: 0; }
        .header h1 { font-size: 24px; font-weight: 750; color: #101a31; margin: 0; }
        .header p { font-size: 13px; color: #718096; margin-top: 4px; }
        .back-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 10px 17px; background: white; color: #101a31; text-decoration: none; border-radius: 8px; font-size: 12px; font-weight: 650; border: 1px solid #ccd6df; transition: all 0.2s ease; }
        .back-btn:hover { background: #f8fafb; }
        .card { background: white; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 1.5rem; margin-bottom: 1.5rem; border-top: 3px solid #079b6d; }
        .card h3 { font-size: 12px; font-weight: 750; color: #101a31; margin-bottom: 1rem; display: flex; align-items: center; gap: 9px; text-transform: uppercase; letter-spacing: .55px; }
        .card h3 i { color: #079b6d; font-size: 14px; }
        .alert { padding: 18px 22px; border-radius: 10px; border-left: 4px solid; margin-bottom: 1rem; display: flex; gap: 14px; }
        .alert-info { background: #eff6ff; border-color: #3b82f6; }
        .alert-info p, .alert-warning p { font-size: 11.5px; }
        .alert-info strong, .alert-warning strong { font-weight: 700; font-size: 12.5px; }
        .alert-warning { background: #fff8e7; border-color: #f59e0b; }
        .alert-info strong { color: #1e40af; }
        .alert-warning strong { color: #624300; }
        .alert-info p { color: #1e3a8a; }
        .alert-warning p { color: #7d6332; }
        .alerticon { width: 36px; height: 36px; border-radius: 9px; display: grid; place-items: center; flex-shrink: 0; font-size: 16px; }
        .alert-info .alerticon { background: #dbeafe; color: #1e40af; }
        .alert-warning .alerticon { background: #fff0c9; color: #c67a00; }
        .doc-item { padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 8px; }
        .doc-item:last-child { margin-bottom: 0; }
        .doc-label { font-size: 12px; font-weight: 600; color: #0f172a; }
        .doc-type { font-size: 11px; color: #64748b; margin-top: 4px; }
        .optional { opacity: 0.75; }
        .actions { display: flex; gap: 8px; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; flex-wrap: wrap; }
        .btn { padding: 10px 17px; border-radius: 8px; border: none; font-size: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: #079b6d; color: white; flex: 1; justify-content: center; border-color: #079b6d; }
        .btn-primary:hover:not(:disabled) { background: #067c58; }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-secondary { background: white; color: #64748b; border: 1px solid #e2e8f0; flex: 1; justify-content: center; }
        .btn-secondary:hover { background: #f8fafc; }
        .btn-warning { background: #f59e0b; color: white; flex: 1; justify-content: center; }
        .btn-warning:hover:not(:disabled) { background: #d97706; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal-overlay.active { display: flex; }
        .modal { background: white; border-radius: 14px; max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; margin: auto; }
        .modal-header { padding: 20px 24px; border-bottom: 2px solid #079b6d; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); border-radius: 14px 14px 0 0; }
        .modal-header h2 { font-size: 16px; font-weight: 750; color: white; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; font-size: 20px; cursor: pointer; color: white; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-content { padding: 24px; }
        .modal-actions { padding: 20px 24px; border-top: 1px solid #e1e7ee; display: flex; gap: 8px; justify-content: flex-end; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .7px; color: #8a95a5; }
        .form-group textarea { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: Inter, 'Segoe UI', sans-serif; font-size: 12px; resize: vertical; background: #fcfdfe; color: #263149; }
        .form-group textarea:focus { outline: none; border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.12); background: white; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: Inter, 'Segoe UI', sans-serif; font-size: 12px; background: #f8fafc; cursor: not-allowed; opacity: 0.7; }
        .modal-icon { width: 60px; height: 60px; margin: 0 auto 1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .modal-icon.loading { background: #e0f2fe; color: #0284c7; animation: spin 2s linear infinite; }
        .modal-icon.success { background: #d1fae5; color: #059669; }
        .modal-title { font-size: 1rem; font-weight: 750; color: #101a31; margin-bottom: 0.5rem; text-align: center; }
        .modal-message { font-size: 0.9rem; color: #64748b; text-align: center; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @media (max-width: 768px) { body { padding: 1rem; } .header { flex-direction: column; gap: 1rem; } .doc-item { flex-direction: column; align-items: flex-start !important; } .doc-item > div:last-child { width: 100%; display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; margin-left: 0 !important; flex-shrink: 1 !important; } .doc-item .btn { flex: 1 1 calc(50% - 3px); font-size: 9px; padding: 5px 8px; white-space: nowrap; } }
    </style>
</head>
<body>
    <!-- Rest of page content -->
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div class="header-icon"><i class="fa-solid fa-check-double"></i></div>
                <div>
                    <h1>Document Verification</h1>
                    <p>Final verification of all uploaded documents for HSB Circular No. 3 of 2017 compliance</p>
                    <span style="display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700;">CASE: {{ $incident->reference_number }}</span>
                </div>
            </div>
            <a href="{{ route('incidents.hr_dossier', $incident) }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dossier
            </a>
        </div>

        <div class="card">
            <h3><i class="fa-solid fa-info-circle"></i> Document Verification Overview</h3>
            <div class="alert alert-info">
                <div class="alerticon"><i class="fa-solid fa-circle-info"></i></div>
                <div>
                    <strong>Stage 6: Document Verification</strong>
                    <p style="margin-top: 8px;">This is the final verification step. Please review all uploaded documents and accept/reject based on HSB Circular No. 3 of 2017 compliance requirements.</p>
                </div>
            </div>
        </div>

        <div class="card">
            @php
                $uploadedByType = [];
                foreach($attachments as $attachment) {
                    $uploadedByType[$attachment->document_type] = $attachment;
                }
                $uploadedMandatoryCount = 0;
                $verifiedMandatoryCount = 0;
                $missingDocuments = [];
                
                foreach($mandatoryDocuments as $key => $label) {
                    if(isset($uploadedByType[$key])) {
                        $uploadedMandatoryCount++;
                        if($uploadedByType[$key]->upload_status === 'VERIFIED') {
                            $verifiedMandatoryCount++;
                        }
                    } else {
                        $missingDocuments[] = $label;
                    }
                }
                foreach($mandatoryConditional as $key => $label) {
                    if(isset($uploadedByType[$key])) {
                        $uploadedMandatoryCount++;
                        if($uploadedByType[$key]->upload_status === 'VERIFIED') {
                            $verifiedMandatoryCount++;
                        }
                    } else {
                        $missingDocuments[] = $label;
                    }
                }
                
                $totalMandatory = count($mandatoryDocuments) + count($mandatoryConditional);
                $hasAllDocs = $uploadedMandatoryCount === $totalMandatory;
                $verificationPercentage = $uploadedMandatoryCount > 0 ? round(($verifiedMandatoryCount / $uploadedMandatoryCount) * 100) : 0;
            @endphp
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <h3 style="margin: 0; display: flex; align-items: center; gap: 9px;"><i class="fa-solid fa-file-check"></i> Mandatory Documents ({{ $uploadedMandatoryCount }}/{{ $totalMandatory }} Uploaded)</h3>
                    <span style="font-size: 12px; font-weight: 700; color: #079b6d;">{{ $verificationPercentage }}% Verified</span>
                </div>
                <div style="width: 100%; height: 24px; background: #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="height: 100%; background: linear-gradient(90deg, #079b6d 0%, #067c58 100%); width: {{ $verificationPercentage }}%; transition: width 0.3s ease; display: flex; align-items: center; justify-content: center;">
                        @if($verificationPercentage > 10)
                            <span style="color: white; font-size: 11px; font-weight: 700;">{{ $verifiedMandatoryCount }}/{{ $uploadedMandatoryCount }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
            @if(!$hasAllDocs)
            <div class="alert alert-warning">
                <div class="alerticon"><i class="fa-solid fa-exclamation-circle"></i></div>
                <div>
                    <strong>Missing Documents</strong>
                    <p style="margin-top: 8px;">{{ count($missingDocuments) }} required document(s) are missing. Contact the case initiator to upload the missing documents.</p>
                </div>
            </div>
            @endif
            
            <div>
                @foreach($mandatoryDocuments as $key => $label)
                    @php
                        $uploaded = $uploadedByType[$key] ?? null;
                        $isVerified = $uploaded && $uploaded->upload_status === 'VERIFIED';
                    @endphp
                    <div class="doc-item" style="display: flex; justify-content: space-between; align-items: center; @if(!$uploaded) background: #fee2e2; border-color: #dc2626; @else background: #f8fafc; border-color: #e2e8f0; @endif">
                        <div style="flex: 1;">
                            <div class="doc-label">{{ $label }}</div>
                            <div class="doc-type">Required - HSB Circular No. 3 of 2017</div>
                            @if($uploaded)
                                <div style="margin-top: 6px; font-size: 11px; color: #079b6d;">
                                    <i class="fa-solid fa-file mr-1"></i>{{ $uploaded->file_name ?? 'Document' }}
                                </div>
                            @endif
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-left: 12px; flex-shrink: 0;">
                            @if($uploaded)
                                @php
                                    $isRejected = $uploaded->upload_status === 'REJECTED';
                                @endphp
                                @if($isVerified)
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #d1fae5; color: #065f46;">
                                        <i class="fa-solid fa-check"></i> Verified
                                    </span>
                                @elseif($isRejected)
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                                        <i class="fa-solid fa-times"></i> Rejected
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 0; color: #92400e; font-weight: 600; font-size: 11px;">
                                        <i class="fa-solid fa-clock"></i> Pending Review
                                    </span>
                                @endif
                                <a href="{{ route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $uploaded]) }}" class="btn" style="padding: 6px 12px; font-size: 10px; background: #079b6d; color: white; border-radius: 6px; text-decoration: none; flex: 0; justify-content: center; border: none; display: inline-flex; align-items: center; gap: 4px;" title="View Document" target="_blank" onclick="viewedDocuments.add({{ $uploaded->id }}); updateVerifyButtonState({{ $uploaded->id }});">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                <a href="{{ route('incidents.download_attachment', ['incident' => $incident, 'attachment' => $uploaded]) }}" class="btn" style="padding: 6px 12px; font-size: 10px; background: #079b6d; color: white; border-radius: 6px; text-decoration: none; flex: 0; justify-content: center; border: none; display: inline-flex; align-items: center; gap: 4px;" title="Download Document">
                                    <i class="fa-solid fa-download"></i> Download
                                </a>
                                @if(!$isVerified && !$isRejected)
                                <button onclick="verifyDocument({{ $uploaded->id }}, {{ $incident->id }})" class="btn verify-btn" data-attachment-id="{{ $uploaded->id }}" style="padding: 6px 12px; font-size: 10px; background: #079b6d; color: white; border-radius: 6px; border: none; cursor: not-allowed; flex: 0; justify-content: center; opacity: 0.5;" title="View document first before verifying" disabled>
                                    <i class="fa-solid fa-check"></i> Verify
                                </button>
                                <button onclick="rejectDocument({{ $uploaded->id }}, {{ $incident->id }}, '{{ $label }}')" class="btn reject-btn" data-attachment-id="{{ $uploaded->id }}" style="padding: 6px 12px; font-size: 10px; background: #dc4c4c; color: white; border-radius: 6px; border: none; cursor: not-allowed; flex: 0; justify-content: center; opacity: 0.5;" title="View document first before rejecting" disabled>
                                    <i class="fa-solid fa-times"></i> Reject
                                </button>
                                @endif
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                                    <i class="fa-solid fa-x"></i> Missing
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if(count($mandatoryConditional) > 0)
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e1e7ee;">
                        @foreach($mandatoryConditional as $key => $label)
                            @php
                                $uploaded = $uploadedByType[$key] ?? null;
                                $isVerified = $uploaded && $uploaded->upload_status === 'VERIFIED';
                            @endphp
                            <div class="doc-item" style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; @if(!$uploaded) background: #fee2e2; border-color: #dc2626; @else background: #f8fafc; border-color: #e1e7ee; @endif">
                                <div style="flex: 1;">
                                    <div class="doc-label">{{ $label }}</div>
                                    <div class="doc-type">Conditional Mandatory (based on incident flags)</div>
                                    @if($uploaded)
                                        <div style="margin-top: 6px; font-size: 11px; color: #079b6d;">
                                            <i class="fa-solid fa-file mr-1"></i>{{ $uploaded->file_name ?? 'Document' }}
                                        </div>
                                    @endif
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; margin-left: 12px; flex-shrink: 0;">
                                    @if($uploaded)
                                        @php
                                            $isRejected = $uploaded->upload_status === 'REJECTED';
                                        @endphp
                                        @if($isVerified)
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #d1fae5; color: #065f46;">
                                                <i class="fa-solid fa-check"></i> Verified
                                            </span>
                                        @elseif($isRejected)
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                                                <i class="fa-solid fa-times"></i> Rejected
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 0; color: #92400e; font-weight: 600; font-size: 11px;">
                                                <i class="fa-solid fa-clock"></i> Pending Review
                                            </span>
                                        @endif
                                        <a href="{{ route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $uploaded]) }}" class="btn" style="padding: 6px 12px; font-size: 10px; background: #079b6d; color: white; border-radius: 6px; text-decoration: none; flex: 0; justify-content: center; border: none; display: inline-flex; align-items: center; gap: 4px;" title="View Document" target="_blank" onclick="viewedDocuments.add({{ $uploaded->id }}); updateVerifyButtonState({{ $uploaded->id }});">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('incidents.download_attachment', ['incident' => $incident, 'attachment' => $uploaded]) }}" class="btn" style="padding: 6px 12px; font-size: 10px; background: #079b6d; color: white; border-radius: 6px; text-decoration: none; flex: 0; justify-content: center; border: none; display: inline-flex; align-items: center; gap: 4px;" title="Download Document">
                                            <i class="fa-solid fa-download"></i> Download
                                        </a>
                                        @if(!$isVerified && !$isRejected)
                                        <button onclick="verifyDocument({{ $uploaded->id }}, {{ $incident->id }})" class="btn verify-btn" data-attachment-id="{{ $uploaded->id }}" style="padding: 6px 12px; font-size: 10px; background: #079b6d; color: white; border-radius: 6px; border: none; cursor: not-allowed; flex: 0; justify-content: center; opacity: 0.5;" title="View document first before verifying" disabled>
                                            <i class="fa-solid fa-check"></i> Verify
                                        </button>
                                        <button onclick="rejectDocument({{ $uploaded->id }}, {{ $incident->id }}, '{{ $label }}')" class="btn reject-btn" data-attachment-id="{{ $uploaded->id }}" style="padding: 6px 12px; font-size: 10px; background: #dc4c4c; color: white; border-radius: 6px; border: none; cursor: not-allowed; flex: 0; justify-content: center; opacity: 0.5;" title="View document first before rejecting" disabled>
                                            <i class="fa-solid fa-times"></i> Reject
                                        </button>
                                        @endif
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                                            <i class="fa-solid fa-x"></i> Missing
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if(count($optionalDocuments) > 0)
        <div class="card">
            <h3><i class="fa-solid fa-file"></i> Optional Documents</h3>
            <div>
                @foreach($optionalDocuments as $key => $label)
                <div class="doc-item optional">
                    <div class="doc-label">{{ $label }}</div>
                    <div class="doc-type">Supporting evidence (optional)</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="actions">
            <a href="{{ route('incidents.hr_dossier', $incident) }}" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left"></i> Back to Dossier
            </a>
            @if(!$hasAllDocs)
            <button type="button" class="btn btn-primary" onclick="openContactModal()">
                <i class="fa-solid fa-message"></i> Contact Reporter
            </button>
            <button type="button" class="btn btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                <i class="fa-solid fa-lock"></i> Complete Verification (Locked)
            </button>
            @else
            @php
                // Check if there are any rejected documents in the latest versions
                // Group by document_type and get the latest one for each type
                $latestByType = [];
                foreach($attachments as $attachment) {
                    if (!isset($latestByType[$attachment->document_type]) || 
                        $attachment->created_at > $latestByType[$attachment->document_type]->created_at) {
                        $latestByType[$attachment->document_type] = $attachment;
                    }
                }
                
                // Check if any of the latest versions are rejected
                $hasRejectedDocs = collect($latestByType)->whereIn('upload_status', ['REJECTED'])->count() > 0;
            @endphp
            @if($hasRejectedDocs)
            <button type="button" class="btn btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                <i class="fa-solid fa-lock"></i> Waiting for Corrections (Locked)
            </button>
            @else
            @php
                // Check if all uploaded mandatory documents are verified
                $allVerified = true;
                $totalUploaded = 0;
                $totalVerified = 0;
                
                foreach($mandatoryDocuments as $key => $label) {
                    $uploaded = $uploadedByType[$key] ?? null;
                    if($uploaded) {
                        $totalUploaded++;
                        if($uploaded->upload_status === 'VERIFIED') {
                            $totalVerified++;
                        }
                    }
                }
                
                foreach($mandatoryConditional as $key => $label) {
                    $uploaded = $uploadedByType[$key] ?? null;
                    if($uploaded) {
                        $totalUploaded++;
                        if($uploaded->upload_status === 'VERIFIED') {
                            $totalVerified++;
                        }
                    }
                }
                
                $allDocumentsVerified = $totalUploaded > 0 && $totalVerified === $totalUploaded;
            @endphp
            <button type="button" class="btn btn-primary" onclick="completeVerification()" @if(!$allDocumentsVerified) disabled style="opacity: 0.6; cursor: not-allowed;" @endif id="nextBtn">
                @if($allDocumentsVerified)
                    <i class="fa-solid fa-arrow-right"></i> Next
                @else
                    <i class="fa-solid fa-lock"></i> Verify All Documents (Locked)
                @endif
            </button>
            @endif
            @endif
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal-overlay" id="rejectionModal">
        <div class="modal">
            <div class="modal-header">
                <h2><i class="fa-solid fa-exclamation-triangle"></i> Reject Document</h2>
                <button class="modal-close" onclick="closeRejectionModal()">×</button>
            </div>
            <div class="modal-content">
                <div class="form-group">
                    <label>Document Type</label>
                    <input type="text" id="rejectionDocType" readonly>
                </div>
                <div class="form-group">
                    <label>Reason for Rejection <span style="color: #dc4c4c;">*</span></label>
                    <textarea id="rejectionReason" rows="6" placeholder="Please provide a detailed reason for rejecting this document. The reporter will receive this message and will need to correct and re-upload the document." maxlength="500"></textarea>
                    <p style="font-size: 10px; color: #8a95a5; margin-top: 8px; text-align: right;">Maximum 500 characters. Be specific about what needs to be corrected.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeRejectionModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitRejection()" id="submitRejectionBtn">
                    <i class="fa-solid fa-times"></i> Reject Document
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="modal" style="text-align: center;">
            <div class="modal-content">
                <div class="modal-icon success" id="successIcon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="modal-title" id="successTitle">Document Rejected</div>
                <div class="modal-message" id="successMessage">The document has been rejected and the reporter has been notified.</div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal-overlay" id="contactModal">
        <div class="modal">
            <div class="modal-header">
                <h2><i class="fa-solid fa-paper-plane"></i> Contact Case Initiator</h2>
                <button class="modal-close" onclick="closeContactModal()">×</button>
            </div>
            <div class="modal-content">
                <div class="form-group">
                    <label>Reporter's Phone Number</label>
                    <input type="text" id="initiatorPhone" disabled>
                </div>
                <div class="form-group">
                    <label>Message to Send <span style="color: #dc4c4c;">*</span></label>
                    <textarea id="messageText" rows="6" placeholder="Message will be pre-filled with missing documents..."></textarea>
                    <p style="font-size: 10px; color: #8a95a5; margin-top: 8px;">Missing documents will be listed in the message.</p>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeContactModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendMessage()" id="sendMessageBtn">
                    <i class="fa-solid fa-paper-plane"></i> Send Message
                </button>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal-overlay" id="statusModal">
        <div class="modal" style="text-align: center;">
            <div class="modal-content">
                <div class="modal-icon loading" id="statusIcon">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div class="modal-title" id="statusTitle">Sending SMS...</div>
                <div class="modal-message" id="statusMessage"></div>
            </div>
        </div>
    </div>

    <script>
        let pendingRejectionData = null;
        let viewedDocuments = new Set(); // Track which documents have been viewed

        function verifyDocument(attachmentId, incidentId) {
            // Check if document has been viewed
            if (!viewedDocuments.has(attachmentId)) {
                alert('Please view the document before verifying it.');
                return;
            }

            const btn = event.target.closest('button');
            
            // Show loading state
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite;"></i> Verifying...';
            
            fetch('{{ route("incidents.verify_attachment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    attachment_id: attachmentId,
                    incident_id: incidentId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to refresh everything
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to verify document'));
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Verify';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while verifying the document');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Verify';
            });
        }

        function updateProgressBar() {
            // Count verified documents
            const verifiedBadges = document.querySelectorAll('span:contains("Verified")').length;
            const allStatusSpans = document.querySelectorAll('[style*="background: #d1fae5"], [style*="background: #fef3c7"], [style*="background: #fee2e2"]');
            
            // Count uploaded documents (excluding missing ones)
            const uploadedDocs = document.querySelectorAll('.doc-item').length;
            const missingDocs = document.querySelectorAll('[style*="background: #fee2e2"]').length;
            const actualUploaded = uploadedDocs - missingDocs;
            
            // Count verified (green badges)
            const verifiedDocs = document.querySelectorAll('span[style*="background: #d1fae5"]').length;
            
            // Calculate percentage
            const percentage = actualUploaded > 0 ? Math.round((verifiedDocs / actualUploaded) * 100) : 0;
            
            // Update the progress bar
            const progressBar = document.querySelector('div[style*="background: linear-gradient"]');
            if (progressBar) {
                progressBar.style.width = percentage + '%';
                
                // Update the text inside the bar
                const barText = progressBar.querySelector('span');
                if (barText) {
                    barText.textContent = verifiedDocs + '/' + actualUploaded;
                    // Show text only if percentage > 10
                    if (percentage <= 10) {
                        barText.style.display = 'none';
                    }
                } else if (percentage > 10) {
                    const textSpan = document.createElement('span');
                    textSpan.style.cssText = 'color: white; font-size: 11px; font-weight: 700;';
                    textSpan.textContent = verifiedDocs + '/' + actualUploaded;
                    progressBar.appendChild(textSpan);
                }
            }
            
            // Update percentage text
            const percentageText = document.querySelector('span:contains("%")');
            if (percentageText) {
                percentageText.textContent = percentage + '% Verified';
            }
        }

        function rejectDocument(attachmentId, incidentId, docType) {
            pendingRejectionData = {
                attachment_id: attachmentId,
                incident_id: incidentId,
                doc_type: docType
            };
            document.getElementById('rejectionDocType').value = docType;
            document.getElementById('rejectionReason').value = '';
            document.getElementById('rejectionModal').classList.add('active');
        }

        function closeRejectionModal() {
            document.getElementById('rejectionModal').classList.remove('active');
            pendingRejectionData = null;
        }

        function submitRejection() {
            const reason = document.getElementById('rejectionReason').value.trim();
            
            if (!reason) {
                alert('Please provide a reason for rejection');
                return;
            }

            if (reason.length < 5) {
                alert('Reason must be at least 5 characters');
                return;
            }

            document.getElementById('submitRejectionBtn').disabled = true;
            document.getElementById('submitRejectionBtn').innerHTML = '<i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite;"></i> Processing...';

            fetch('{{ route("incidents.reject_attachment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    attachment_id: pendingRejectionData.attachment_id,
                    incident_id: pendingRejectionData.incident_id,
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close rejection modal
                    closeRejectionModal();
                    
                    // Show success modal
                    document.getElementById('successTitle').textContent = 'Document Rejected';
                    document.getElementById('successMessage').textContent = `${pendingRejectionData.doc_type} has been rejected. The reporter has been notified and can re-upload the corrected document.`;
                    document.getElementById('successModal').classList.add('active');
                    
                    // Reload after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + (data.message || 'Failed to reject document'));
                    document.getElementById('submitRejectionBtn').disabled = false;
                    document.getElementById('submitRejectionBtn').innerHTML = '<i class="fa-solid fa-times"></i> Reject Document';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rejecting the document');
                document.getElementById('submitRejectionBtn').disabled = false;
                document.getElementById('submitRejectionBtn').innerHTML = '<i class="fa-solid fa-times"></i> Reject Document';
            });
        }

        function openContactModal() {
            const missingDocs = [
                @foreach($missingDocuments as $doc)
                    '{{ $doc }}',
                @endforeach
            ];
            
            const initiatorName = '{{ $incident->first_name ?? $incident->employee?->name ?? 'Case Initiator' }}';
            const initiatorPhone = '{{ $incident->phone_number ?? $incident->employee?->phone_number ?? '' }}';
            const caseReference = '{{ $incident->reference_number }}';
            
            const message = `Dear ${initiatorName},\n\nThe following required documents are missing from case ${caseReference}:\n\n${missingDocs.map((doc, i) => `${i + 1}. ${doc}`).join('\n')}\n\nPlease upload these documents as soon as possible to proceed with case processing.\n\nThank you.`;
            
            document.getElementById('initiatorPhone').value = initiatorPhone;
            document.getElementById('messageText').value = message;
            document.getElementById('contactModal').classList.add('active');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.remove('active');
        }

        function sendMessage() {
            const phone = document.getElementById('initiatorPhone').value;
            const message = document.getElementById('messageText').value;
            
            if (!phone || !message) {
                alert('Phone number and message are required');
                return;
            }

            // Show loading modal
            document.getElementById('statusTitle').textContent = 'Sending SMS...';
            document.getElementById('statusMessage').textContent = '';
            document.getElementById('statusIcon').className = 'modal-icon loading';
            document.getElementById('statusIcon').innerHTML = '<i class="fa-solid fa-spinner"></i>';
            document.getElementById('statusModal').classList.add('active');
            
            // Disable send button
            document.getElementById('sendMessageBtn').disabled = true;

            // Send SMS via backend
            fetch('{{ route("incidents.send_sms") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    phone: phone,
                    message: message,
                    incident_id: {{ $incident->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success state
                    document.getElementById('statusTitle').textContent = 'SMS Sent Successfully';
                    document.getElementById('statusMessage').textContent = 'The case initiator has been notified.';
                    document.getElementById('statusIcon').className = 'modal-icon success';
                    document.getElementById('statusIcon').innerHTML = '<i class="fa-solid fa-check"></i>';
                    
                    // Auto-close after 2 seconds
                    setTimeout(() => {
                        document.getElementById('statusModal').classList.remove('active');
                        closeContactModal();
                    }, 2000);
                } else {
                    alert('Error: ' + (data.message || 'Failed to send SMS'));
                    document.getElementById('statusModal').classList.remove('active');
                    document.getElementById('sendMessageBtn').disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending SMS');
                document.getElementById('statusModal').classList.remove('active');
                document.getElementById('sendMessageBtn').disabled = false;
            });
        }

        function updateVerifyButtonState(attachmentId) {
            const verifyBtn = document.querySelector(`button[data-attachment-id="${attachmentId}"][onclick*="verifyDocument"]`);
            if (verifyBtn) {
                verifyBtn.disabled = false;
                verifyBtn.style.opacity = '1';
                verifyBtn.style.cursor = 'pointer';
                verifyBtn.title = 'Verify Document';
            }
            
            const rejectBtn = document.querySelector(`button[data-attachment-id="${attachmentId}"][onclick*="rejectDocument"]`);
            if (rejectBtn) {
                rejectBtn.disabled = false;
                rejectBtn.style.opacity = '1';
                rejectBtn.style.cursor = 'pointer';
                rejectBtn.title = 'Reject Document';
            }
        }

        function completeVerification() {
            // Redirect to leave information capture page
            window.location.href = '{{ route("incidents.leave_information_capture", $incident) }}';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verificationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite;"></i> Processing...';
                    }
                });
            }
        });

        // Set up PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let currentPdfDocument = null;
        let currentPage = 1;

        // Function to open document inline in modal
        function openInline(url) {
            const filename = url.split('/').pop().split('?')[0];
            document.getElementById('pdfTitle').textContent = 'Viewing: Document';
            
            // Fetch the file from the controller
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/pdf'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error('Failed to fetch document: ' + response.statusText);
                }
                return response.arrayBuffer();
            })
            .then(arrayBuffer => {
                console.log('Received arrayBuffer, size:', arrayBuffer.byteLength);
                
                if (arrayBuffer.byteLength === 0) {
                    throw new Error('File is empty');
                }
                
                // Load PDF with PDF.js
                const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
                return loadingTask.promise;
            })
            .then(pdf => {
                console.log('PDF loaded successfully, pages:', pdf.numPages);
                currentPdfDocument = pdf;
                currentPage = 1;
                document.getElementById('pdfModal').classList.add('active');
                renderPdfPage(1);
            })
            .catch(error => {
                console.error('Full error:', error);
                alert('Failed to load document: ' + error.message);
            });
        }

        // Function to render a specific page of the PDF
        function renderPdfPage(pageNum) {
            if (!currentPdfDocument) return;

            currentPdfDocument.getPage(pageNum).then(page => {
                const viewer = document.getElementById('pdfViewer');
                const scale = 1;
                const viewport = page.getViewport({ scale: scale });
                const canvas = document.getElementById('pdfCanvas');
                const context = canvas.getContext('2d');
                
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                
                page.render(renderContext).promise.then(() => {
                    console.log('Page rendered successfully');
                }).catch(err => {
                    console.error('Error rendering page:', err);
                });
            });
        }
        
        // Function to close PDF modal
        function closePdfModal() {
            document.getElementById('pdfModal').classList.remove('active');
            currentPdfDocument = null;
            currentPage = 1;
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('pdfModal');
            if (event.target == modal) {
                closePdfModal();
            }
        }
    </script>
</body>
</html>
