<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Case Review · HSC Conditions of Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        .alert-warning { background: #fffbeb; border-color: #f59e0b; }
        .alert-warning p { color: #92400e; margin-top: 6px; margin-bottom: 0; }
        .alert-warning strong { font-weight: 700; }
        .alert-success { background: #dcfce7; border-color: #22c55e; }
        .alert-success p { color: #15803d; margin: 0; }
        .witness-item { display: flex; flex-direction: column; padding: 14px; background: #f8fafb; border: 1px solid #e1e7ee; border-radius: 8px; margin-bottom: 10px; }
        .witness-item:last-child { margin-bottom: 0; }
        .witness-name { font-size: 12px; font-weight: 600; color: #101a31; margin-bottom: 6px; }
        .witness-statement { font-size: 12px; color: #4e596c; line-height: 1.6; margin: 8px 0; }
        .witness-date { font-size: 11px; color: #a5aab8; }
        .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .status-completed { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .tab-button {
            padding: 14px 24px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s;
            border-bottom: 3px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .tab-active {
            color: #0d7a3d;
            border-bottom-color: #0d7a3d;
            background: white;
        }
        .tab-inactive {
            color: #64748b;
        }
        .tab-inactive:hover {
            color: #0d7a3d;
            background: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-icon"><i class="fa-solid fa-eye"></i></div>
                <div>
                    <h1>Case Review</h1>
                    <p>{{ $incident->reference_number }}</p>
                </div>
            </div>
            <a href="{{ route('dashboards.hsc_conditions') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

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
                    <div class="field col-span-2">
                        <span class="field-label">Description</span>
                        <span class="field-desc">{{ $incident->incident_description }}</span>
                    </div>
                    
                    @if($incident->supervisor_verified_at)
                    <!-- Supervisor Verification Section -->
                    <div class="col-span-2" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #e1e7ee;">
                        <h6 style="font-size: 13px; font-weight: 700; color: #0d7a3d; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-user-check"></i> Supervisor Verification
                        </h6>
                        <div class="grid-2">
                            <div class="field">
                                <span class="field-label">Verification Status</span>
                                <span class="field-value">
                                    <span style="color: #22c55e; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Verified</span>
                                </span>
                            </div>
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
                    @endif
                </div>
            </div>
        </div>

        <!-- First Aid Record Card -->
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
                    <div class="alert alert-warning">
                        <p><i class="fa-solid fa-info-circle"></i> <strong>No First Aid Record</strong> — The nurse or person who rendered first aid must submit their record using the provided First Aid Token.</p>
                        @if($incident->first_aid_token)
                        <p style="margin-top: 8px; margin-bottom: 0;"><strong>First Aid Token:</strong> <span style="font-family: 'Courier New', monospace; letter-spacing: 1px; font-weight: 600;">{{ $incident->first_aid_token }}</span></p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- View Documents Button -->
        @php
            $documentsCount = $incident->attachments ? $incident->attachments->count() : 0;
        @endphp
        @if($documentsCount > 0)
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('hsc.incident.documents', ['incident' => $incident->id]) }}" class="view-docs-btn">
                <i class="fa-solid fa-paperclip"></i>
                <span>View Supporting Documents ({{ $documentsCount }})</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        
        <style>
            .view-docs-btn {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 18px 24px;
                background: linear-gradient(135deg, #0d7a3d 0%, #1bb74d 100%);
                color: white;
                text-decoration: none;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 650;
                transition: all 0.3s;
                box-shadow: 0 4px 12px rgba(13, 122, 61, 0.2);
            }
            .view-docs-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(13, 122, 61, 0.3);
            }
            .view-docs-btn i:first-child {
                font-size: 18px;
                margin-right: 12px;
            }
            .view-docs-btn span {
                flex: 1;
            }
            .view-docs-btn i:last-child {
                font-size: 14px;
                margin-left: 12px;
            }
        </style>
        @endif

        <!-- HOD/Supervisor Report & Witness Statements Card with Tabs -->
        @if($incident->hod_report || ($incident->witnessTokens && $incident->witnessTokens->count() > 0))
        <div class="card" x-data="{ activeTab: '{{ $incident->hod_report ? 'report' : 'witnesses' }}' }">
            <div class="card-header">
                <h3><i class="fa-solid fa-file-signature"></i> HOD/Supervisor Report & Witness Statements</h3>
            </div>
            
            <!-- Tabs -->
            <div style="border-bottom: 2px solid #e1e7ee; background: #f8fafb;">
                <div style="display: flex; gap: 0;">
                    @if($incident->hod_report)
                    <button 
                        @click="activeTab = 'report'" 
                        :class="activeTab === 'report' ? 'tab-active' : 'tab-inactive'"
                        class="tab-button"
                    >
                        <i class="fa-solid fa-file-text"></i> HOD/Supervisor Report
                    </button>
                    @endif
                    
                    @if($incident->witnessTokens && $incident->witnessTokens->count() > 0)
                    <button 
                        @click="activeTab = 'witnesses'" 
                        :class="activeTab === 'witnesses' ? 'tab-active' : 'tab-inactive'"
                        class="tab-button"
                    >
                        <i class="fa-solid fa-users"></i> Witness Statements ({{ $incident->witnessTokens->count() }})
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="card-body">
                <!-- HOD Report Tab Content -->
                @if($incident->hod_report)
                <div x-show="activeTab === 'report'">
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
                            <span class="field-value">{{ $incident->hod_report_submitted_at ? $incident->hod_report_submitted_at->format('d M Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="field col-span-2">
                            <span class="field-label">Report Content</span>
                            <span class="field-desc">{{ $incident->hod_report }}</span>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Witness Statements Tab Content -->
                @if($incident->witnessTokens && $incident->witnessTokens->count() > 0)
                <div x-show="activeTab === 'witnesses'">
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
                        <div class="witness-date">Submitted {{ $token->created_at->diffForHumans() }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</body>
</html>
