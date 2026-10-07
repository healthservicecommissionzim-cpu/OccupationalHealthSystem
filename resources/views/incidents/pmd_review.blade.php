<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PMD Case Review - Complete History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start; }
        .header h1 { font-size: 20px; font-weight: 700; }
        .header p { font-size: 12px; color: #64748b; margin-top: 4px; }
        .back-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.25rem; background: white; color: #1D4533; text-decoration: none; border-radius: 8px; font-size: 12px; font-weight: 600; border: 2px solid #1D4533; transition: all 0.3s ease; }
        .back-btn:hover { background: #1D4533; color: white; transform: translateX(-2px); }
        
        /* Tabs */
        .tabs-container { background: white; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 2rem; overflow: hidden; max-width: 900px; margin-left: auto; margin-right: auto; }
        .tabs { display: flex; border-bottom: 1px solid #e2e8f0; overflow-x: auto; }
        .tab { flex: 1; min-width: 140px; padding: 1rem; text-align: center; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; font-size: 12px; font-weight: 600; color: #64748b; white-space: nowrap; position: relative; }
        .tab:hover { background: #f8fafc; }
        .tab.active { color: #1D4533; border-bottom-color: #1D4533; background: #f0fdf4; }
        .tab.locked { cursor: not-allowed; opacity: 0.5; }
        .tab.locked:hover { background: transparent; }
        .tab.completed::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; top: 8px; right: 8px; color: #059669; font-size: 10px; }
        .tab i { margin-right: 6px; }
        .tab-content { display: none; padding: 2rem; max-width: 900px; margin: 0 auto; }
        .tab-content.active { display: block; }
        
        /* Acknowledgment Section */
        .acknowledgment-section { background: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 1.25rem; margin-top: 2rem; }
        .acknowledgment-section.completed { background: #d1fae5; border-color: #059669; }
        .acknowledgment-checkbox { display: flex; align-items: start; gap: 12px; margin-bottom: 1rem; }
        .acknowledgment-checkbox input[type="checkbox"] { width: 18px; height: 18px; margin-top: 2px; cursor: pointer; }
        .acknowledgment-checkbox label { font-size: 12px; font-weight: 600; color: #0f172a; cursor: pointer; line-height: 1.5; }
        .btn-prev { background: #64748b; color: white; padding: 10px 24px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .btn-prev:hover { background: #475569; }
        
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #1D4533; border-radius: 2px 2px 0 0; margin: -1.5rem -1.5rem 1rem -1.5rem; }
        .card h3 { font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
        .card h3 i { color: #1D4533; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .field { display: flex; flex-direction: column; }
        .field-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-bottom: 4px; }
        .field-value { font-size: 12px; font-weight: 600; color: #0f172a; }
        .field-desc { font-size: 12px; color: #475569; line-height: 1.5; margin-top: 4px; }
        .col-span-2 { grid-column: 1 / -1; }
        
        .alert { padding: 12px; border-radius: 6px; border-left: 4px solid; margin-bottom: 1rem; }
        .alert-warning { background: #fef3c7; border-color: #f59e0b; }
        .alert-warning p { font-size: 12px; color: #92400e; }
        .alert-danger { background: #fee2e2; border-color: #ef4444; }
        .alert-danger p { font-size: 12px; color: #991b1b; }
        .alert-info { background: #dbeafe; border-color: #3b82f6; }
        .alert-info p { font-size: 12px; color: #1e40af; }
        
        .status-badge { display: inline-block; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        
        .document-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .document-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem; text-align: center; }
        .document-item i { font-size: 24px; color: #1D4533; margin-bottom: 8px; display: block; }
        .document-item-name { font-size: 11px; font-weight: 600; color: #0f172a; margin-bottom: 4px; word-break: break-word; }
        .document-item-status { font-size: 10px; color: #64748b; margin-bottom: 8px; }
        
        .timeline { position: relative; padding-left: 2rem; }
        .timeline-item { margin-bottom: 2rem; position: relative; }
        .timeline-item::before { content: ''; position: absolute; left: -2.5rem; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #1D4533; border: 3px solid white; box-shadow: 0 0 0 2px #1D4533; }
        .timeline-item.last::after { display: none; }
        .timeline-item-title { font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .timeline-item-date { font-size: 11px; color: #64748b; margin-bottom: 8px; }
        .timeline-item-content { font-size: 12px; color: #475569; line-height: 1.6; background: #f8fafc; padding: 12px; border-radius: 6px; white-space: pre-wrap; word-break: break-word; }
        
        .textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; resize: vertical; }
        .textarea:focus { outline: none; border-color: #1D4533; box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1); }
        .select { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; }
        .select:focus { outline: none; border-color: #1D4533; box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1); }
        
        .actions { display: flex; gap: 8px; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; justify-content: flex-end; }
        .btn { padding: 10px 20px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: #1D4533; color: white; }
        .btn-primary:hover:not(:disabled) { background: #0f2818; }
        .btn-primary:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
        
        .mandatory-warning { background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .mandatory-warning h2 { color: #991b1b; font-size: 14px; margin-bottom: 1rem; }
        .mandatory-warning .box { background: white; border: 1px solid #fca5a5; border-radius: 6px; padding: 12px; margin-bottom: 12px; }
        .mandatory-warning .box p { font-size: 11px; color: #7f1d1d; line-height: 1.6; }
        
        .char-count { font-size: 11px; color: #64748b; margin-top: 4px; }
        .char-count.valid { color: #059669; }
        .char-count.invalid { color: #dc2626; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 8px; }
        .required { color: #dc2626; }
        
        .empty-state { text-align: center; padding: 3rem 1rem; color: #64748b; }
        .empty-state i { font-size: 32px; color: #cbd5e1; margin-bottom: 1rem; display: block; }
        
        /* Document Viewer Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); }
        .modal.show { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 8px; max-width: 90%; max-height: 90vh; width: 90%; overflow: auto; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
        .modal-header h2 { font-size: 14px; font-weight: 700; color: #0f172a; }
        .modal-close { font-size: 24px; color: #64748b; cursor: pointer; background: none; border: none; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; }
        .modal-close:hover { color: #0f172a; }
        .modal-body { padding: 2rem; }
        .document-viewer { width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 6px; }
        .document-loading { display: flex; align-items: center; justify-content: center; height: 600px; color: #64748b; }
        .document-error { background: #fee2e2; border: 1px solid #fecaca; border-radius: 6px; padding: 2rem; text-align: center; color: #991b1b; }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-stethoscope"></i> PMD Case Review - Complete History</h1>
                <p>Case {{ $incident->reference_number ?? 'N/A' }} - Review all case progression steps</p>
            </div>
            <a href="{{ url()->previous() }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        @if(!isset($incident))
            <div class="alert alert-danger">
                <p><strong>Error:</strong> Incident data not found.</p>
            </div>
        @else
            <!-- Tabs Navigation -->
            <div class="tabs-container">
                <div class="tabs">
                    <div class="tab active" data-tab="initial" onclick="switchTab(event, 'initial')">
                        <i class="fa-solid fa-file-circle-check"></i>Initial Report
                    </div>
                    <div class="tab locked" data-tab="supervisor" onclick="switchTab(event, 'supervisor')">
                        <i class="fa-solid fa-users"></i>Supervisor Review
                    </div>
                    <div class="tab locked" data-tab="hr" onclick="switchTab(event, 'hr')">
                        <i class="fa-solid fa-briefcase"></i>HR Review
                    </div>
                    <div class="tab locked" data-tab="executive" onclick="switchTab(event, 'executive')">
                        <i class="fa-solid fa-user-tie"></i>Executive Review
                    </div>
                    <div class="tab locked" data-tab="decision" onclick="switchTab(event, 'decision')">
                        <i class="fa-solid fa-check-double"></i>PMD Decision
                    </div>
                </div>
            </div>

            <!-- TAB 1: Initial Report -->
            <div id="initial" class="tab-content active">
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-file-lines"></i> Initial Incident Report</h3>
                    <div class="grid-2">
                        <div class="field">
                            <span class="field-label">Reference Number</span>
                            <span class="field-value">{{ $incident->reference_number ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Incident Date</span>
                            <span class="field-value">{{ isset($incident->incident_date) ? \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Employee Name</span>
                            <span class="field-value">{{ $incident->first_name ?? '' }} {{ $incident->surname ?? '' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Employee ID</span>
                            <span class="field-value">{{ $incident->employee_id ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Facility</span>
                            <span class="field-value">{{ $incident->facility->name ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">District</span>
                            <span class="field-value">{{ $incident->facility->district->name ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Injury Type</span>
                            <span class="field-value">{{ $incident->incidentType->name ?? 'N/A' }}</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Injury Date Reported</span>
                            <span class="field-value">{{ isset($incident->created_at) ? $incident->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Single Card with Tabbed Content Inside -->
                <div class="card">
                    <div class="card-top"></div>
                    
                    <!-- Button Tabs Inside Card -->
                    <div style="display: flex; gap: 12px; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
                        <button onclick="switchContentTab(event, 'description')" id="btn-description" style="flex: 0 0 auto; padding: 10px 20px; background: #f0fdf4; border: 2px solid #1D4533; color: #1D4533; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-align-left"></i> Initial Description
                        </button>
                        <button onclick="switchContentTab(event, 'witness')" id="btn-witness" style="flex: 0 0 auto; padding: 10px 20px; background: white; border: 2px solid #e2e8f0; color: #64748b; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-users"></i> Witness Statements
                        </button>
                        <button onclick="switchContentTab(event, 'firstaid')" id="btn-firstaid" style="flex: 0 0 auto; padding: 10px 20px; background: white; border: 2px solid #e2e8f0; color: #64748b; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-heart-pulse"></i> First Aid Record
                        </button>
                    </div>

                    <!-- Initial Description Content -->
                    <div id="content-description" class="content-tab active">
                        <h3 style="margin-top: 0;"><i class="fa-solid fa-align-left"></i> Initial Description</h3>
                        <div class="timeline-item-content">{{ $incident->incident_description ?? 'No description provided' }}</div>
                    </div>

                    <!-- Witness Statements Content -->
                    <div id="content-witness" class="content-tab" style="display: none;">
                        <h3 style="margin-top: 0;"><i class="fa-solid fa-users"></i> Witness Statements</h3>
                        @php
                            $submittedWitnesses = $incident->getSubmittedWitnessStatements();
                            $pendingWitnesses = $incident->getPendingWitnessStatements();
                        @endphp

                        @if($submittedWitnesses->count() > 0)
                            <div style="margin-bottom: 1.5rem;">
                                <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-check-circle" style="color: #059669;"></i> Submitted Statements ({{ $submittedWitnesses->count() }})
                                </h4>
                                <div style="display: grid; gap: 10px;">
                                    @foreach($submittedWitnesses as $witness)
                                        <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 12px; display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <p style="font-size: 12px; font-weight: 600; color: #0f172a;">{{ $witness->witness_name ?? 'Anonymous' }}</p>
                                                <p style="font-size: 11px; color: #64748b;">Submitted: {{ $witness->submitted_at ? $witness->submitted_at->format('M d, Y H:i') : 'N/A' }}</p>
                                            </div>
                                            <button onclick="viewWitnessModal({{ $witness->id }}, '{{ $witness->witness_name }}')" style="background: #1D4533; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($pendingWitnesses->count() > 0)
                            <div>
                                <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-clock" style="color: #f59e0b;"></i> Pending Statements ({{ $pendingWitnesses->count() }})
                                </h4>
                                <div style="display: grid; gap: 10px;">
                                    @foreach($pendingWitnesses as $witness)
                                        <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 12px; display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <p style="font-size: 12px; font-weight: 600; color: #78350f;">{{ $witness->witness_name ?? 'Anonymous' }}</p>
                                                <p style="font-size: 11px; color: #92400e;">Expires: {{ $witness->token_expires_at ? $witness->token_expires_at->format('M d, Y') : 'N/A' }}</p>
                                            </div>
                                            <span style="background: #f59e0b; color: white; padding: 6px 12px; border-radius: 4px; font-size: 10px; font-weight: 600;">Awaiting</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($submittedWitnesses->count() === 0 && $pendingWitnesses->count() === 0)
                            <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8;">
                                <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 1rem; display: block;"></i>
                                <p style="font-size: 12px;">No witness statements collected</p>
                            </div>
                        @endif
                    </div>

                    <!-- First Aid Record Content -->
                    <div id="content-firstaid" class="content-tab" style="display: none;">
                        <h3 style="margin-top: 0;"><i class="fa-solid fa-heart-pulse"></i> First Aid Record</h3>
                        @php
                            $firstAidRecords = $incident->firstAidRecords()->orderBy('created_at', 'desc')->get();
                        @endphp

                        @if($firstAidRecords->count() > 0)
                            <div style="display: grid; gap: 12px;">
                                @foreach($firstAidRecords as $record)
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; display: flex; justify-content: space-between; align-items: start;">
                                        <div style="flex: 1;">
                                            <p style="font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 4px;">First Aid Record</p>
                                            <p style="font-size: 11px; color: #64748b; margin-bottom: 8px;">
                                                <i class="fa-solid fa-user"></i> {{ $record->responder_name ?? 'N/A' }}
                                            </p>
                                            @if($record->injury_description)
                                                <p style="font-size: 11px; color: #475569; line-height: 1.5; margin-bottom: 8px;">{{ Str::limit($record->injury_description, 100) }}</p>
                                            @endif
                                            <p style="font-size: 10px; color: #94a3b8;">Recorded: {{ $record->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                        <button onclick="viewFirstAidModal({{ $record->id }})" style="background: #1D4533; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap; margin-left: 12px;">
                                            <i class="fa-solid fa-eye"></i> View
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8;">
                                <i class="fa-solid fa-heart-pulse" style="font-size: 32px; margin-bottom: 1rem; display: block;"></i>
                                <p style="font-size: 12px;">No first aid records submitted</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($incident->total_injury_leave_days)
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-calendar-days"></i> Leave Information</h3>
                    <div class="grid-2">
                        <div class="field">
                            <span class="field-label">Total Leave Days</span>
                            <span class="field-value" style="font-size: 14px; color: {{ $incident->total_injury_leave_days > 90 ? '#dc2626' : '#059669' }};">{{ $incident->total_injury_leave_days }} days</span>
                        </div>
                        <div class="field">
                            <span class="field-label">Status</span>
                            <span class="status-badge {{ $incident->total_injury_leave_days > 90 ? 'badge-danger' : 'badge-completed' }}">
                                {{ $incident->total_injury_leave_days > 90 ? 'Exceeds 90 Days' : 'Within Threshold' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Acknowledgment Section -->
                <div class="acknowledgment-section" id="ack-initial">
                    <div class="acknowledgment-checkbox">
                        <input type="checkbox" id="check-initial" onchange="acknowledgeTab('initial')">
                        <label for="check-initial">I have reviewed all initial incident information including the employee details, incident description, and leave information above. I confirm I understand the case context before proceeding.</label>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Supervisor Review -->
            <div id="supervisor" class="tab-content">
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-users"></i> Supervisor Review & Witness Statements</h3>
                    
                    @if($incident->hod_report_submitted_at)
                        <div class="timeline-item">
                            <div class="timeline-item-title">Head of Department Report</div>
                            <div class="timeline-item-date">Submitted: {{ $incident->hod_report_submitted_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-item-content">{{ $incident->hod_report ?? 'No report provided' }}</div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p><i class="fa-solid fa-info-circle"></i> Head of Department report not yet submitted</p>
                        </div>
                    @endif
                </div>
                
                <!-- Acknowledgment Section -->
                <div class="acknowledgment-section" id="ack-supervisor">
                    <div class="acknowledgment-checkbox">
                        <input type="checkbox" id="check-supervisor" onchange="acknowledgeTab('supervisor')">
                        <label for="check-supervisor">I have reviewed the Head of Department report and supervisor's assessment of this incident. I confirm understanding of the supervisory review before proceeding.</label>
                    </div>
                </div>
            </div>

            <!-- TAB 3: HR Review -->
            <div id="hr" class="tab-content">
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-briefcase"></i> HR Review & Verified Documents</h3>
                    
                    @if($incident->escalated_to_hr_at)
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">ESCALATED TO HR</p>
                            <p style="font-size: 12px; color: #0f172a; font-weight: 600;">{{ $incident->escalated_to_hr_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                @php
                    $verifiedDocuments = $incident->attachments->where('upload_status', 'VERIFIED');
                    $rejectedDocuments = $incident->attachments->where('upload_status', 'REJECTED');
                @endphp

                @if($verifiedDocuments->count() > 0)
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-file-check"></i> Verified Documents ({{ $verifiedDocuments->count() }})</h3>
                    
                    <div style="margin-bottom: 1rem;">
                        @php $counter = 1; @endphp
                        @foreach($verifiedDocuments as $attachment)
                            <div style="display: flex; align-items: center; padding: 12px; background: #f8fafc; border-radius: 6px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s;" 
                                 onclick="viewDocument({{ $attachment->id }}, '{{ $attachment->document_type }}')"
                                 onmouseover="this.style.background='#f1f5f9'" 
                                 onmouseout="this.style.background='#f8fafc'">
                                <div style="width: 30px; font-weight: 700; color: #64748b; font-size: 12px;">{{ $counter++ }}.</div>
                                <div style="flex: 1;">
                                    <p style="font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 2px;">{{ ucfirst(str_replace('_', ' ', $attachment->document_type)) }}</p>
                                    <p style="font-size: 10px; color: #64748b;">Click to view document</p>
                                </div>
                                <i class="fa-solid fa-eye" style="color: #1D4533; font-size: 16px;"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($rejectedDocuments->count() > 0)
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-file-xmark"></i> Rejected Documents ({{ $rejectedDocuments->count() }})</h3>
                    
                    <div style="margin-bottom: 1rem;">
                        @php $counter = 1; @endphp
                        @foreach($rejectedDocuments as $attachment)
                            <div style="display: flex; align-items: center; padding: 12px; background: #fef2f2; border-radius: 6px; margin-bottom: 8px;">
                                <div style="width: 30px; font-weight: 700; color: #991b1b; font-size: 12px;">{{ $counter++ }}.</div>
                                <div style="flex: 1;">
                                    <p style="font-size: 12px; font-weight: 600; color: #991b1b; margin-bottom: 2px;">{{ ucfirst(str_replace('_', ' ', $attachment->document_type)) }}</p>
                                    <p style="font-size: 10px; color: #dc2626;">Document was rejected by HR</p>
                                </div>
                                <i class="fa-solid fa-times-circle" style="color: #dc2626; font-size: 16px;"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($verifiedDocuments->count() === 0 && $rejectedDocuments->count() === 0)
                <div class="card">
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <p>No verified or rejected documents yet</p>
                        <p style="font-size: 11px; margin-top: 8px; color: #94a3b8;">Documents are pending HR verification</p>
                    </div>
                </div>
                @endif
                
                <!-- Acknowledgment Section -->
                <div class="acknowledgment-section" id="ack-hr">
                    <div class="acknowledgment-checkbox">
                        <input type="checkbox" id="check-hr" onchange="acknowledgeTab('hr')">
                        <label for="check-hr">I have reviewed all HR verified and rejected documents. I confirm understanding of the HR document verification process and outcomes before proceeding.</label>
                    </div>
                </div>
            </div>

            <!-- TAB 4: Executive Review -->
            <div id="executive" class="tab-content">
                <div class="card">
                    <div class="card-top"></div>
                    <h3><i class="fa-solid fa-user-tie"></i> Executive/Clinical Review</h3>
                    
                    @if(!empty($incident->executive_recommendation_text))
                        <div class="timeline-item">
                            <div class="timeline-item-title">Executive Recommendation</div>
                            <div class="timeline-item-date">Submitted: {{ $incident->executive_recommendation_submitted_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-item-content">{{ $incident->executive_recommendation_text }}</div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p><i class="fa-solid fa-info-circle"></i> Executive review not yet completed</p>
                        </div>
                    @endif
                </div>
                
                <!-- Acknowledgment Section -->
                <div class="acknowledgment-section" id="ack-executive">
                    <div class="acknowledgment-checkbox">
                        <input type="checkbox" id="check-executive" onchange="acknowledgeTab('executive')">
                        <label for="check-executive">I have reviewed the executive/clinical recommendations for this case. I confirm understanding of all clinical assessments before making my PMD decision.</label>
                    </div>
                </div>
            </div>

            <!-- TAB 5: PMD Decision & Mandatory Warnings -->
            <div id="decision" class="tab-content">
                <!-- Mandatory Medical Board Warning -->
                @if($incident->total_injury_leave_days && $incident->total_injury_leave_days > 90)
                <div class="mandatory-warning">
                    <h2><i class="fa-solid fa-user-doctor"></i> MANDATORY MEDICAL BOARD REFERRAL</h2>
                    <div class="box">
                        <p><strong>HSB Circular No. 3 of 2017 - Compliance Requirement</strong></p>
                        <p style="margin-top: 8px;">Any employee who has been on sick leave for more than 90 calendar days in aggregate within a 12-month period MUST be referred to the Medical Board for comprehensive medical assessment and fitness-for-duty determination.</p>
                    </div>
                    <div class="box" style="border-color: #fecaca;">
                        <p><strong>Case Status:</strong> This employee has taken <strong style="color: #991b1b; font-size: 13px;">{{ $incident->total_injury_leave_days }} days</strong> of injury-related leave, which <strong>exceeds the 90-day threshold</strong>.</p>
                    </div>
                    <p style="font-size: 12px; color: #991b1b;"><i class="fa-solid fa-ban"></i> <strong>Direct approval is NOT PERMITTED</strong>. This case MUST be referred to the Medical Board per regulatory requirements.</p>
                </div>
                @elseif($incident->total_injury_leave_days)
                <div class="alert alert-warning">
                    <p><strong>Leave Status:</strong> This employee has taken {{ $incident->total_injury_leave_days }} calendar days of injury leave (within the 90-day threshold). All review levels completed. PMD decision required.</p>
                </div>
                @endif

                <!-- PMD Review Form -->
                <form method="POST" action="{{ route('pmd.submit_review', $incident) }}" id="reviewForm">
                    @csrf

                    <div class="card">
                        <div class="card-top"></div>
                        <h3><i class="fa-solid fa-pen-to-square"></i> PMD Review & Final Decision</h3>

                        <div class="form-group">
                            <label>
                                Review Notes <span class="required">*</span>
                                <span style="font-size: 11px; font-weight: 400; color: #64748b;">(minimum 50 characters)</span>
                            </label>
                            <textarea 
                                name="pmd_review_notes" 
                                id="reviewNotes"
                                maxlength="5000"
                                rows="6"
                                placeholder="Provide your comprehensive review of this case, including clinical assessment, recommendations, and next steps..."
                                class="textarea"
                                oninput="updateCharCount()"
                                required
                            ></textarea>
                            <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                                <span id="charWarning" class="char-count invalid"><span id="remainingChars">50</span> more characters required</span>
                                <span id="charCount" class="char-count">0 / 5000</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Decision <span class="required">*</span>
                            </label>
                            
                            @if($incident->total_injury_leave_days && $incident->total_injury_leave_days > 90)
                                <!-- MANDATORY MEDICAL BOARD REFERRAL -->
                                <input type="hidden" name="pmd_decision" value="refer_medical_board">
                                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px;">
                                    <p style="font-size: 12px; font-weight: 600; color: #991b1b; margin-bottom: 8px;">
                                        <i class="fa-solid fa-user-doctor"></i> AUTOMATIC REFERRAL TO MEDICAL BOARD
                                    </p>
                                    <p style="font-size: 11px; color: #7f1d1d; line-height: 1.5;">
                                        Due to the extended leave period ({{ $incident->total_injury_leave_days }} days), this case will be automatically routed to the Medical Board upon submission. No other decision options are available per HSB Circular No. 3 of 2017.
                                    </p>
                                </div>
                            @else
                                <!-- STANDARD DECISION OPTIONS (≤90 days) -->
                                <select 
                                    name="pmd_decision" 
                                    id="pmdDecision"
                                    class="select"
                                    required
                                >
                                    <option value="">— Select Decision —</option>
                                    <option value="approve">Approve & Close Case</option>
                                    <option value="refer_ministry">Refer to Ministry HQ</option>
                                    <option value="refer_medical_board">Refer to Medical Board</option>
                                    <option value="return">Return for Additional Information</option>
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <button 
                                type="button"
                                class="btn-prev" 
                                onclick="goToPreviousTab('decision', 'executive')"
                            >
                                <i class="fa-solid fa-arrow-left"></i> Previous
                            </button>
                            <div style="text-align: right;">
                                <p style="font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 4px;" id="statusMessage">Complete the form to submit</p>
                                <p style="font-size: 11px; color: #64748b;">Your decision will be recorded and the case updated</p>
                            </div>
                            <button 
                                type="submit" 
                                id="submitBtn"
                                disabled
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-check"></i> Submit PMD Review
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Info Note -->
                <div class="alert alert-info" style="margin-top: 1rem;">
                    <p><i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> You have reviewed all case progression steps. Complete the form above and submit your final PMD decision.</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Track which tabs have been acknowledged
        const acknowledgedTabs = new Set(['initial']); // Initial tab is unlocked by default
        
        function switchTab(event, tabName) {
            event.preventDefault();
            
            const tabElement = event.target.closest('.tab');
            
            // Prevent switching to locked tabs
            if (tabElement.classList.contains('locked')) {
                return;
            }
            
            // Hide all tabs
            const allTabs = document.querySelectorAll('.tab-content');
            allTabs.forEach(tab => tab.classList.remove('active'));
            
            // Remove active class from all buttons
            const allTabButtons = document.querySelectorAll('.tab');
            allTabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            tabElement.classList.add('active');
        }

        function acknowledgeTab(tabName) {
            const checkbox = document.getElementById(`check-${tabName}`);
            const ackSection = document.getElementById(`ack-${tabName}`);
            
            if (checkbox.checked) {
                ackSection.classList.add('completed');
                acknowledgedTabs.add(tabName);
                
                // Mark tab as completed
                const tabElement = document.querySelector(`.tab[data-tab="${tabName}"]`);
                tabElement.classList.add('completed');
                
                // Automatically go to next tab after a short delay
                setTimeout(() => {
                    const nextTabMap = {
                        'initial': 'supervisor',
                        'supervisor': 'hr',
                        'hr': 'executive',
                        'executive': 'decision'
                    };
                    const nextTab = nextTabMap[tabName];
                    if (nextTab) {
                        goToNextTab(tabName, nextTab);
                    }
                }, 300);
            } else {
                ackSection.classList.remove('completed');
                acknowledgedTabs.delete(tabName);
                
                // Remove completed mark
                const tabElement = document.querySelector(`.tab[data-tab="${tabName}"]`);
                tabElement.classList.remove('completed');
            }
        }

        function goToNextTab(currentTab, nextTab) {
            // Unlock the next tab
            const nextTabElement = document.querySelector(`.tab[data-tab="${nextTab}"]`);
            nextTabElement.classList.remove('locked');
            
            // Hide current tab
            document.getElementById(currentTab).classList.remove('active');
            document.querySelector(`.tab[data-tab="${currentTab}"]`).classList.remove('active');
            
            // Show next tab
            document.getElementById(nextTab).classList.add('active');
            nextTabElement.classList.add('active');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goToPreviousTab(currentTab, previousTab) {
            // Hide current tab
            document.getElementById(currentTab).classList.remove('active');
            document.querySelector(`.tab[data-tab="${currentTab}"]`).classList.remove('active');
            
            // Show previous tab
            document.getElementById(previousTab).classList.add('active');
            document.querySelector(`.tab[data-tab="${previousTab}"]`).classList.add('active');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateCharCount() {
            const textarea = document.getElementById('reviewNotes');
            const charCount = document.getElementById('charCount');
            const charWarning = document.getElementById('charWarning');
            const remainingChars = document.getElementById('remainingChars');
            const length = textarea.value.length;
            
            charCount.textContent = length + ' / 5000';
            
            if (length < 50) {
                charCount.className = 'char-count invalid';
                charWarning.className = 'char-count invalid';
                remainingChars.textContent = (50 - length);
            } else {
                charCount.className = 'char-count valid';
                charWarning.className = 'char-count valid';
                charWarning.innerHTML = '✓ Valid - ' + length + ' characters';
            }
            
            updateSubmitButton();
        }

        function updateSubmitButton() {
            const textarea = document.getElementById('reviewNotes');
            const decision = document.getElementById('pmdDecision');
            const submitBtn = document.getElementById('submitBtn');
            const statusMsg = document.getElementById('statusMessage');
            const length = textarea.value.length;
            
            const isForced = decision === null || decision.type === 'hidden';
            
            if (length >= 50 && (isForced || (decision && decision.value))) {
                submitBtn.disabled = false;
                statusMsg.textContent = '✓ Ready to submit';
                statusMsg.style.color = '#059669';
            } else {
                submitBtn.disabled = true;
                if (length < 50) {
                    statusMsg.textContent = 'Write at least 50 characters in review notes';
                } else if (!isForced && (!decision || !decision.value)) {
                    statusMsg.textContent = 'Select a decision';
                }
                statusMsg.style.color = '#dc2626';
            }
        }

        const decisionSelect = document.getElementById('pmdDecision');
        if (decisionSelect) {
            decisionSelect.addEventListener('change', updateSubmitButton);
        }

        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            const textarea = document.getElementById('reviewNotes');
            const decision = document.getElementById('pmdDecision');
            const isForced = decision === null || decision.type === 'hidden';
            
            if (textarea.value.length < 50 || (!isForced && !decision.value)) {
                e.preventDefault();
                alert('Please complete all required fields');
            }
        });

        // Content tab switching function
        function switchContentTab(event, tabName) {
            event.preventDefault();
            
            // Hide all content tabs
            const allContentTabs = document.querySelectorAll('.content-tab');
            allContentTabs.forEach(tab => tab.style.display = 'none');
            
            // Remove active style from all buttons
            const allButtons = document.querySelectorAll('[id^="btn-"]');
            allButtons.forEach(btn => {
                btn.style.background = 'white';
                btn.style.borderColor = '#e2e8f0';
                btn.style.color = '#64748b';
            });
            
            // Show selected content tab
            document.getElementById('content-' + tabName).style.display = 'block';
            
            // Style active button
            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.style.background = '#f0fdf4';
            activeBtn.style.borderColor = '#1D4533';
            activeBtn.style.color = '#1D4533';
        }

        // Switch to Witness tab from link
        function switchToWitnessTab(event) {
            if (event) event.preventDefault();
            switchContentTab({ preventDefault: () => {} }, 'witness');
        }

        // Switch to First Aid tab from link
        function switchToFirstAidTab(event) {
            if (event) event.preventDefault();
            switchContentTab({ preventDefault: () => {} }, 'firstaid');
        }

        // Witness Statement Modal
        function viewWitnessModal(witnessTokenId, witnessName) {
            const modal = document.getElementById('witnessModal');
            const viewer = document.getElementById('witnessViewer');
            const title = document.getElementById('witnessTitle');
            
            title.textContent = 'Statement from ' + witnessName;
            viewer.innerHTML = '<div class="document-loading"><i class="fa-solid fa-spinner"></i> Loading statement...</div>';
            modal.classList.add('show');
            
            fetch(`/incidents/witness/${witnessTokenId}/statement`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load statement');
                    return response.json();
                })
                .then(data => {
                    viewer.innerHTML = `
                        <div style="padding: 1.5rem;">
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">WITNESS NAME</p>
                                <p style="font-size: 12px; font-weight: 600; color: #0f172a;">${data.witness_name || 'N/A'}</p>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">STATEMENT</p>
                                <p style="font-size: 12px; color: #475569; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">${data.statement || 'No statement provided'}</p>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">SUBMITTED ON</p>
                                <p style="font-size: 12px; font-weight: 600; color: #0f172a;">${data.submitted_at || 'N/A'}</p>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    viewer.innerHTML = '<div class="document-error"><i class="fa-solid fa-exclamation-circle"></i><p>Error loading statement: ' + error.message + '</p></div>';
                    console.error('Error loading statement:', error);
                });
        }

        // First Aid Record Modal
        function viewFirstAidModal(recordId) {
            const modal = document.getElementById('firstAidModal');
            const viewer = document.getElementById('firstAidViewer');
            
            viewer.innerHTML = '<div class="document-loading"><i class="fa-solid fa-spinner"></i> Loading record...</div>';
            modal.classList.add('show');
            
            fetch(`/incidents/first-aid/${recordId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load record');
                    return response.json();
                })
                .then(data => {
                    viewer.innerHTML = `
                        <div style="padding: 1.5rem;">
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">RESPONDER NAME</p>
                                <p style="font-size: 12px; font-weight: 600; color: #0f172a;">${data.responder_name || 'N/A'}</p>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">INJURY DESCRIPTION</p>
                                <p style="font-size: 12px; color: #475569; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">${data.injury_description || 'No description provided'}</p>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">FIRST AID PROVIDED</p>
                                <p style="font-size: 12px; color: #475569; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">${data.first_aid_provided || 'None recorded'}</p>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">RECORDED ON</p>
                                <p style="font-size: 12px; font-weight: 600; color: #0f172a;">${data.created_at || 'N/A'}</p>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    viewer.innerHTML = '<div class="document-error"><i class="fa-solid fa-exclamation-circle"></i><p>Error loading record: ' + error.message + '</p></div>';
                    console.error('Error loading record:', error);
                });
        }

        function closeWitnessModal() {
            document.getElementById('witnessModal').classList.remove('show');
        }

        function closeFirstAidModal() {
            document.getElementById('firstAidModal').classList.remove('show');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const witnessModal = document.getElementById('witnessModal');
            const firstAidModal = document.getElementById('firstAidModal');
            if (event.target === witnessModal) closeWitnessModal();
            if (event.target === firstAidModal) closeFirstAidModal();
        }
    </script>

    <!-- Document Viewer Modal -->
    <div id="documentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="documentTitle">Document Viewer</h2>
                <button class="modal-close" onclick="closeDocument()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="documentViewer" class="document-viewer"></div>
            </div>
        </div>
    </div>

    <script>
        function viewDocument(attachmentId, documentType) {
            const modal = document.getElementById('documentModal');
            const viewer = document.getElementById('documentViewer');
            const title = document.getElementById('documentTitle');
            
            title.textContent = documentType;
            viewer.innerHTML = '<div class="document-loading"><i class="fa-solid fa-spinner"></i> Loading document...</div>';
            modal.classList.add('show');
            
            // Get incident ID from URL
            const incidentId = window.location.pathname.split('/').pop();
            
            // Fetch document URL - use the existing viewAttachment route
            fetch(`/incidents/${incidentId}/attachments/${attachmentId}/view`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load document');
                    return response.blob();
                })
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const extension = getFileExtension(documentType).toLowerCase();
                    
                    if (extension === 'pdf') {
                        viewer.innerHTML = `<embed src="${url}" type="application/pdf" width="100%" height="100%">`;
                    } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                        viewer.innerHTML = `<img src="${url}" style="max-width: 100%; max-height: 600px; margin: 0 auto; display: block;">`;
                    } else if (extension === 'txt') {
                        return blob.text().then(text => {
                            viewer.innerHTML = `<pre style="padding: 1rem; background: #f8fafc; border-radius: 6px; overflow: auto; max-height: 600px; font-size: 11px;">${text}</pre>`;
                        });
                    } else {
                        viewer.innerHTML = '<div class="document-error"><i class="fa-solid fa-file"></i><p>Preview not available for this file type (.'+extension+')</p></div>';
                    }
                })
                .catch(error => {
                    viewer.innerHTML = '<div class="document-error"><i class="fa-solid fa-exclamation-circle"></i><p>Error loading document: ' + error.message + '</p></div>';
                    console.error('Error loading document:', error);
                });
        }

        function closeDocument() {
            document.getElementById('documentModal').classList.remove('show');
            document.getElementById('documentViewer').innerHTML = '';
        }

        function getFileExtension(filename) {
            return filename.split('.').pop().toLowerCase();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('documentModal');
            if (event.target === modal) {
                closeDocument();
            }
        }
    </script>

    <!-- Witness Statement Modal -->
    <div id="witnessModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="witnessTitle">Witness Statement</h2>
                <button class="modal-close" onclick="closeWitnessModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="witnessViewer" class="document-viewer"></div>
            </div>
        </div>
    </div>

    <!-- First Aid Record Modal -->
    <div id="firstAidModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>First Aid Record</h2>
                <button class="modal-close" onclick="closeFirstAidModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="firstAidViewer" class="document-viewer"></div>
            </div>
        </div>
    </div>
</body>
</html>
