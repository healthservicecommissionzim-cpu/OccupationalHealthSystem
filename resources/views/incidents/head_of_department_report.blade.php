<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>HOD Report · Supervisor</title>
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
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .back:hover { background: #f8fafb; }
        
        /* Alert */
        .alert { display: flex; gap: 14px; padding: 18px 22px; background: #fff8e7; border: 1px solid #f6d48b; border-left: 4px solid #f59e0b; border-radius: 10px; margin-bottom: 22px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1); }
        .alerticon { width: 36px; height: 36px; border-radius: 9px; background: #fff0c9; color: #c67a00; display: grid; place-items: center; flex-shrink: 0; font-size: 16px; }
        .alert strong { display: block; color: #624300; font-size: 12.5px; margin-bottom: 5px; font-weight: 700; }
        .alert p { margin: 0; color: #7d6332; font-size: 11.5px; line-height: 1.65; }
        
        /* Grid Layout */
        .grid { display: grid; grid-template-columns: minmax(0, 1fr) 290px; gap: 22px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .witness { border: 1px solid #d8e0e8; background: white; color: #101a31; border-radius: 7px; padding: 8px 12px; font-size: 10px; font-weight: 650; cursor: pointer; transition: all 0.2s; }
        .witness:hover { background: #f8fafb; }
        .body { padding: 23px; }
        
        /* Details Grid */
        .details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 35px; }
        .detail label { display: block; color: #8a95a5; font-size: 9px; font-weight: 750; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 5px; }
        .detail strong { color: #101a31; font-size: 13px; background: #f8fafb; padding: 10px 12px; border-radius: 8px; display: block; border: 1px solid #e4e9ee; }
        
        /* Form Card */
        .formcard { border-top: 3px solid #079b6d; box-shadow: 0 10px 30px rgba(7, 155, 109, 0.08); }
        .intro { margin-bottom: 24px; color: #6e7a8d; font-size: 11.5px; line-height: 1.7; padding: 14px 16px; background: #f8fafb; border-left: 3px solid #079b6d; border-radius: 8px; }
        .labelrow { margin-bottom: 7px; color: #101a31; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; gap: 4px; }
        .required { color: #dc4c4c; font-size: 14px; }
        .help { margin-top: 6px; color: #8b95a4; font-size: 10.5px; display: flex; align-items: center; gap: 5px; }
        .help i { color: #079b6d; }
        textarea { width: 100%; min-height: 230px; resize: vertical; border: 1px solid #d7e0e8; border-radius: 9px; padding: 14px 15px; background: #fcfdfe; color: #263149; font: 12.5px 'Segoe UI'; line-height: 1.7; outline: none; transition: all 0.2s; }
        textarea:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.12); background: white; }
        .count { text-align: right; color: #079b6d; font-size: 9.5px; margin-top: 7px; font-weight: 600; }
        
        /* Checkboxes */
        .consider { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 12px; }
        .check { display: flex; align-items: center; gap: 9px; padding: 11px 12px; background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 10px; color: #4e596c; cursor: pointer; transition: all 0.2s; }
        .check:hover { background: #f3f6f8; }
        .check input { accent-color: #079b6d; cursor: pointer; }
        
        /* Timeline */
        .timeline { position: relative; padding-left: 10px; }
        .ti { position: relative; padding: 0 0 25px 38px; }
        .ti:before { content: ''; position: absolute; left: 12px; top: 25px; width: 2px; height: calc(100% - 12px); background: #e4e9ef; }
        .ti:last-child:before { display: none; }
        .dot { position: absolute; left: 3px; top: 0; width: 20px; height: 20px; border-radius: 50%; background: #edf1f5; border: 3px solid white; box-shadow: 0 0 0 1px #dce2e9; }
        .ti.done .dot { background: #079b6d; box-shadow: 0 0 0 1px #079b6d; }
        .ti.active .dot { background: white; box-shadow: 0 0 0 2px #079b6d; }
        .ti strong { display: block; color: #101a31; font-size: 11px; margin-bottom: 3px; }
        .ti small { color: #8a95a5; font-size: 9px; line-height: 1.4; }
        .badge { display: inline-block; margin-top: 7px; padding: 4px 8px; border-radius: 999px; background: #e9f8f3; color: #067c58; font-size: 8px; font-weight: 750; text-transform: uppercase; }
        
        /* Help Box */
        .helpbox { padding: 17px; border-radius: 11px; background: #f5faf8; border: 1px solid #dcefe8; }
        .helpbox strong { display: block; font-size: 11px; margin-bottom: 5px; color: #101a31; }
        .helpbox p { margin: 0; color: #748093; font-size: 9px; line-height: 1.6; }
        
        /* Actions */
        .actions { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .note { color: #8a95a4; font-size: 9px; }
        .buttons { display: flex; gap: 9px; }
        .btn { border-radius: 8px; padding: 10px 17px; font-size: 10px; font-weight: 700; border: 1px solid #d9e0e7; background: white; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #263149; }
        .btn:hover { background: #f8fafb; }
        .draft { background: #f3faf7; color: #067c58; border-color: #cbd7d3; }
        .draft:hover { background: #e9f5f1; }
        .primary { background: #079b6d; color: white; border-color: #079b6d; box-shadow: 0 5px 13px rgba(7, 155, 109, .18); }
        .primary:hover:not(:disabled) { background: #067c58; }
        .primary:disabled { opacity: 0.6; cursor: not-allowed; }
        
        /* Tooltip */
        .tooltip-container { position: relative; display: inline-block; width: 100%; }
        .tooltip { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: #ef4444; color: white; padding: 10px 14px; border-radius: 8px; font-size: 11px; font-weight: 600; white-space: nowrap; margin-bottom: 8px; display: none; z-index: 1000; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
        .tooltip::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 6px solid transparent; border-top-color: #ef4444; }
        .tooltip.show { display: block; animation: slideDown 0.2s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateX(-50%) translateY(-8px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
        
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        
        /* Responsive */
        @media (max-width: 900px) { 
            .grid { 
                grid-template-columns: 1fr; 
                display: flex;
                flex-direction: column;
            }
            .grid aside {
                order: -1; /* Move sidebar to top on mobile */
            }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; }
            .steps { grid-template-columns: repeat(5, 120px); overflow: auto; }
            .steps:before { display: none; }
            .details, .consider { grid-template-columns: 1fr; }
            .actions { flex-direction: column; }
            .buttons { width: 100%; flex-direction: column; }
            .btn { width: 100%; text-align: center; justify-content: center; display: flex; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <h1 class="title">HOD / Supervisor Report</h1>
                    <div class="sub">Departmental assessment and incident review</div>
                    <span class="case">CASE: {{ $incident->reference_number }}</span>
                </div>
            </div>
            <a href="{{ route('dashboards.supervisor') }}" class="back"><i class="fa-solid fa-arrow-left"></i> &nbsp; Back to Dashboard</a>
        </div>

        <!-- Alert -->
        <div class="alert">
            <div class="alerticon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <strong>Critical Step: This report is essential for determining injury-on-duty compensation eligibility.</strong>
                <p>Please provide a detailed and accurate description of the worker's duties at the time of the injury. Witness statements must be reviewed before completing this report. Your submission will form part of the official incident record.</p>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid">
            <main>
                <!-- Incident Details Card -->
                <div class="card">
                    <div class="chead">
                        <div class="ctitle"><i class="fa-solid fa-file-lines"></i> &nbsp; Incident Details</div>
                    </div>
                    <div class="body">
                        <div class="details">
                            <div class="detail">
                                <label>Worker Name</label>
                                <strong>{{ $incident->first_name }} {{ $incident->surname }}</strong>
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
                                <label>Incident Date</label>
                                <strong>{{ $incident->incident_date->format('d M Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="card formcard">
                    <div class="chead">
                        <div class="ctitle"><i class="fa-solid fa-clipboard-check"></i> &nbsp; Supervisor / Head of Department Report</div>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{ route('incidents.store_hod_report', ['incident' => $incident]) }}" id="hodForm">
                            @csrf

                            <div class="intro">
                                Your assessment should provide an accurate departmental account of the circumstances surrounding the incident. Please ensure that the information is factual, specific, and based on your knowledge of the worker's duties.
                            </div>

                            <div style="margin-bottom: 24px;">
                                <div class="labelrow">Worker's Duties at the Time of Injury <span class="required">*</span></div>
                                <div class="help">Minimum 100 characters. Maximum 5000 characters.</div>
                                <textarea 
                                    id="desc" 
                                    name="hod_report" 
                                    required
                                    minlength="100"
                                    maxlength="5000"
                                    placeholder="Provide a detailed description of the duties the worker was performing at the time of the injury.&#10;&#10;Include:&#10;• The task being performed&#10;• The worker's normal responsibilities&#10;• Equipment or materials involved&#10;• The circumstances immediately before the incident&#10;• Any factors that may have contributed to the incident"
                                >{{ old('hod_report') }}</textarea>
                                <div class="count"><span id="count">0</span> / 5000 characters</div>
                                @error('hod_report')
                                <div style="color: #dc4c4c; font-size: 10px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="{{ route('dashboards.supervisor') }}" class="btn">
                        <i class="fa-solid fa-times"></i> Cancel
                    </a>
                    <div class="tooltip-container">
                        <div class="tooltip" id="witnessTooltip">
                            <i class="fa-solid fa-exclamation-circle" style="margin-right: 6px;"></i>
                            Please review witness statements and check the acknowledgement box
                        </div>
                        <button type="submit" class="btn primary" id="submitBtn" disabled>
                            Review &amp; Continue<i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                </form>
            </main>

            <!-- Sidebar -->
            <aside>
                <!-- Witness Statements Card -->
                <div class="card" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="chead" style="background: #f8fafb;">
                        <div class="ctitle" style="color: #079b6d;"><i class="fa-solid fa-users"></i> &nbsp; Witness Statements</div>
                    </div>
                    <div class="body">
                        <div style="color: #6e7a8d; font-size: 10px; margin-bottom: 16px; line-height: 1.6;">
                            Review all witness testimonies before completing your report. Witness statements provide critical context for incident assessment.
                        </div>
                        
                        @if(isset($witnessStatements) && $witnessStatements->count() > 0)
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #e9f8f3; border-radius: 8px; margin-bottom: 8px;">
                                    <div style="font-size: 10px; color: #067c58; font-weight: 700;">TOTAL WITNESSES</div>
                                    <div style="font-size: 18px; color: #079b6d; font-weight: 750;">{{ $witnessStatements->count() }}</div>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #f0fdf4; border-radius: 8px;">
                                    <div style="font-size: 10px; color: #067c58; font-weight: 700;">STATEMENTS RECEIVED</div>
                                    <div style="font-size: 18px; color: #079b6d; font-weight: 750;">{{ $witnessStatements->where('statement_submitted_at', '!=', null)->count() }}</div>
                                </div>
                            </div>

                            <div style="margin-bottom: 16px;">
                                @foreach($witnessStatements->take(3) as $index => $witness)
                                <div style="padding: 10px; background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 6px; margin-bottom: 6px; font-size: 10px;">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <div style="width: 24px; height: 24px; background: #e9f8f3; border-radius: 6px; display: grid; place-items: center; color: #079b6d; flex-shrink: 0; font-size: 9px;">
                                            {{ $index + 1 }}
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 700; color: #101a31; font-size: 10px;">{{ $witness->witness_name ?? 'Pending Name' }}</div>
                                        </div>
                                    </div>
                                    <div style="font-size: 8px; color: #8a95a5; padding-left: 32px;">
                                        @if($witness->statement_submitted_at)
                                            <span style="color: #079b6d;"><i class="fa-solid fa-circle-check"></i> Submitted</span>
                                        @else
                                            <span style="color: #f59e0b;"><i class="fa-solid fa-clock"></i> Pending</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 24px 12px; background: #f8fafb; border-radius: 8px; margin-bottom: 16px;">
                                <i class="fa-solid fa-inbox" style="font-size: 24px; color: #e4e9ef; display: block; margin-bottom: 8px;"></i>
                                <p style="font-size: 9px; color: #8a95a5; margin: 0;">No witnesses recorded for this incident</p>
                            </div>
                        @endif

                        <button type="button" class="btn primary" onclick="openWitnessModal()" style="width: 100%; padding: 11px 16px; font-size: 10px; justify-content: center; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-comment-dots"></i> View All Statements
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Draft Save Confirmation Modal -->
    <div id="draftSaveModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
        <div style="background: white; border-radius: 14px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; text-align: center;">
            <div style="width: 64px; height: 64px; background: #e9f8f3; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; color: #079b6d;">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <h2 style="font-size: 20px; font-weight: 750; color: #101a31; margin-bottom: 12px;">
                Draft Saved Successfully
            </h2>
            <p style="font-size: 13px; color: #6e7a8d; line-height: 1.7; margin-bottom: 24px;">
                Your work has been automatically saved. You can safely leave this page and continue later. Your progress will be restored when you return.
            </p>
            <div style="background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 14px; margin-bottom: 24px; text-align: left;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <i class="fa-solid fa-info-circle" style="color: #079b6d; font-size: 16px;"></i>
                    <span style="font-size: 11px; font-weight: 700; color: #101a31;">What happens next?</span>
                </div>
                <ul style="margin: 0; padding-left: 24px; font-size: 11px; color: #6e7a8d; line-height: 1.8;">
                    <li>Your draft is stored securely in your browser</li>
                    <li>Return anytime to continue from where you left off</li>
                    <li>Clicking "Cancel" button will clear your draft</li>
                </ul>
            </div>
            <button onclick="closeDraftSaveModal()" class="btn primary" style="width: 100%; padding: 12px 20px; font-size: 12px; justify-content: center; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-check"></i> Got it, Thanks!
            </button>
        </div>
    </div>

    <!-- Draft Restore Modal -->
    <div id="draftRestoreModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 2001; justify-content: center; align-items: center; backdrop-filter: blur(6px);">
        <div style="background: white; border-radius: 14px; padding: 32px; max-width: 550px; width: 90%; box-shadow: 0 25px 80px rgba(0,0,0,0.5); border: 2px solid #e1e7ee;">
            <div style="text-align: center; margin-bottom: 24px;">
                <div style="width: 64px; height: 64px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 28px; color: #3b82f6;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h2 style="font-size: 20px; font-weight: 750; color: #101a31; margin-bottom: 10px;">
                    Welcome Back!
                </h2>
                <p style="font-size: 13px; color: #6e7a8d; line-height: 1.6;">
                    We found a draft you were working on earlier
                </p>
            </div>

            <div style="background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span style="font-size: 10px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Draft Info</span>
                </div>
                <div style="display: grid; grid-template-columns: auto 1fr; gap: 12px; font-size: 11px;">
                    <div style="display: flex; align-items: center; gap: 8px; color: #6e7a8d;">
                        <i class="fa-solid fa-calendar" style="color: #079b6d; width: 16px;"></i>
                        <span>Saved:</span>
                    </div>
                    <div style="font-weight: 600; color: #101a31;" id="draftTimestamp">—</div>
                    
                    <div style="display: flex; align-items: center; gap: 8px; color: #6e7a8d;">
                        <i class="fa-solid fa-text-width" style="color: #079b6d; width: 16px;"></i>
                        <span>Length:</span>
                    </div>
                    <div style="font-weight: 600; color: #101a31;" id="draftLength">—</div>
                </div>
                
                <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #e4e9ee;">
                    <div style="font-size: 10px; font-weight: 700; color: #8a95a5; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Preview</div>
                    <div id="draftPreview" style="font-size: 11px; color: #4e596c; line-height: 1.6; max-height: 80px; overflow-y: auto; background: white; padding: 10px; border-radius: 6px; border: 1px solid #e4e9ee;">
                        —
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button onclick="discardDraftFromModal()" class="btn" style="width: 100%; padding: 12px 16px; font-size: 11px; justify-content: center; display: flex; align-items: center; gap: 6px; color: #ef4444; border-color: #fecaca;">
                    <i class="fa-solid fa-trash"></i> Discard Draft
                </button>
                <button onclick="restoreDraftFromModal()" class="btn primary" style="width: 100%; padding: 12px 16px; font-size: 11px; justify-content: center; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-check"></i> Continue Writing
                </button>
            </div>
        </div>
    </div>

    <script>
        const d = document.getElementById('desc');
        const c = document.getElementById('count');
        const submitBtn = document.getElementById('submitBtn');
        const incidentId = {{ $incident->id }};
        const draftKey = `hod_report_draft_${incidentId}`;
        const draftTimeKey = `hod_report_draft_time_${incidentId}`;
        let savedDraftText = '';
        let draftSaveModalShown = false;

        // Check for existing draft on page load
        window.addEventListener('DOMContentLoaded', function() {
            const savedDraft = localStorage.getItem(draftKey);
            const savedTime = localStorage.getItem(draftTimeKey);
            
            if (savedDraft && savedDraft.trim().length > 0) {
                // Show restore modal
                showDraftRestoreModal(savedDraft, savedTime);
            }
        });

        function showDraftRestoreModal(draftText, savedTime) {
            savedDraftText = draftText;
            
            // Update modal content
            const timestamp = savedTime ? new Date(savedTime).toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit', 
                minute: '2-digit' 
            }) : 'Unknown';
            
            document.getElementById('draftTimestamp').textContent = timestamp;
            document.getElementById('draftLength').textContent = `${draftText.length} characters`;
            document.getElementById('draftPreview').textContent = draftText.substring(0, 200) + (draftText.length > 200 ? '...' : '');
            
            // Show modal
            document.getElementById('draftRestoreModal').style.display = 'flex';
        }

        function restoreDraftFromModal() {
            d.value = savedDraftText;
            c.textContent = d.value.length;
            updateSubmitButton();
            document.getElementById('draftRestoreModal').style.display = 'none';
            
            // Show success toast
            showToast('Draft restored successfully! Continue from where you left off.', 'success');
        }

        function discardDraftFromModal() {
            if (confirm('Are you sure you want to discard this draft? This action cannot be undone.')) {
                localStorage.removeItem(draftKey);
                localStorage.removeItem(draftTimeKey);
                document.getElementById('draftRestoreModal').style.display = 'none';
                showToast('Draft discarded. Starting fresh.', 'success');
            }
        }

        function showDraftSaveModal() {
            document.getElementById('draftSaveModal').style.display = 'flex';
            draftSaveModalShown = true;
        }

        function closeDraftSaveModal() {
            document.getElementById('draftSaveModal').style.display = 'none';
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                padding: 14px 18px;
                border-radius: 10px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 1000;
                border: 1px solid #e1e7ee;
                border-left: 3px solid ${type === 'success' ? '#22c55e' : '#ef4444'};
                animation: slideIn 0.3s ease;
            `;
            
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const color = type === 'success' ? '#22c55e' : '#ef4444';
            
            toast.innerHTML = `
                <i class="fa-solid ${icon}" style="font-size: 18px; color: ${color};"></i>
                <div style="font-size: 11px; color: #4e596c; font-weight: 600;">${message}</div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Auto-save draft as user types (with debounce)
        let saveTimeout;
        let autoSaveModalTimeout;
        
        d.addEventListener('input', () => {
            c.textContent = d.value.length;
            updateSubmitButton();
            
            // Clear existing timeouts
            clearTimeout(saveTimeout);
            clearTimeout(autoSaveModalTimeout);
            
            // Only save if there's meaningful content
            if (d.value.trim().length >= 50) {
                // Save after 2 seconds of inactivity
                saveTimeout = setTimeout(() => {
                    localStorage.setItem(draftKey, d.value);
                    localStorage.setItem(draftTimeKey, new Date().toISOString());
                    
                    // Show modal after 5 seconds of inactivity (3 more seconds after save)
                    if (!draftSaveModalShown) {
                        autoSaveModalTimeout = setTimeout(() => {
                            showDraftSaveModal();
                        }, 3000);
                    }
                }, 2000);
            }
        });

        // Initialize count
        c.textContent = d.value.length;

        function updateSubmitButton() {
            const hasText = d.value.length >= 100;
            const ackCheckbox = document.getElementById('ackCheckbox');
            const isAcknowledged = ackCheckbox && ackCheckbox.checked;

            if (hasText && isAcknowledged) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Show tooltip on hover when button is disabled
        submitBtn.addEventListener('mouseenter', function(e) {
            if (submitBtn.disabled) {
                const tooltip = document.getElementById('witnessTooltip');
                if (tooltip) {
                    tooltip.classList.add('show');
                }
            }
        });

        // Hide tooltip on mouse leave
        submitBtn.addEventListener('mouseleave', function(e) {
            const tooltip = document.getElementById('witnessTooltip');
            if (tooltip) {
                tooltip.classList.remove('show');
            }
        });

        // Listen for checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'ackCheckbox') {
                updateSubmitButton();
                // Remove highlight when checkbox is checked
                const witnessCard = document.querySelector('aside .card');
                if (witnessCard) {
                    witnessCard.style.animation = '';
                    witnessCard.style.border = '';
                    witnessCard.style.boxShadow = '';
                }
            }
        });

        // Handle Cancel button click - clear draft
        document.querySelector('a.btn[href*="dashboards.supervisor"]').addEventListener('click', function(e) {
            if (d.value.trim().length > 0) {
                const confirmed = confirm('You have unsaved work. Clicking Cancel will discard your draft. Are you sure you want to leave?');
                if (confirmed) {
                    localStorage.removeItem(draftKey);
                    localStorage.removeItem(draftTimeKey);
                } else {
                    e.preventDefault();
                }
            }
        });

        // Form submission - ALWAYS handle via AJAX
        const form = document.getElementById('hodForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault(); // Always prevent default form submission

                // Check if witness statements are acknowledged
                const ackCheckbox = document.getElementById('ackCheckbox');
                if (!ackCheckbox || !ackCheckbox.checked) {
                    // Highlight the witness card
                    const witnessCard = document.querySelector('aside .card');
                    if (witnessCard) {
                        // Shake animation
                        witnessCard.style.animation = 'shake 0.5s';
                        witnessCard.style.border = '2px solid #ef4444';
                        witnessCard.style.boxShadow = '0 8px 25px rgba(239, 68, 68, 0.25)';
                        
                        // Scroll to witness card
                        witnessCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // Show warning message
                        showToast('Please review witness statements and check the acknowledgment box', 'error');
                        
                        // Reset animation after it completes
                        setTimeout(() => {
                            witnessCard.style.animation = '';
                        }, 500);
                    }
                    return; // Stop form submission
                }

                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite;"></i> Submitting...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Clear draft after successful submission
                        localStorage.removeItem(draftKey);
                        localStorage.removeItem(draftTimeKey);
                        
                        submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Success!';
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route("incidents.supervisor_dossier_review", ["incident" => $incident]) }}';
                        }, 1000);
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Review &amp; Continue<i class="fa-solid fa-arrow-right"></i>';
                    }
                } catch (error) {
                    alert('An error occurred: ' + error.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Review &amp; Continue<i class="fa-solid fa-arrow-right"></i>';
                }
            });
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(400px); opacity: 0; }
            }
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
                20%, 40%, 60%, 80% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</html>



    <!-- Witness Statements Modal -->
    <div id="witnessModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
        <div style="background: white; border-radius: 14px; padding: 28px; max-width: 750px; width: 90%; max-height: 85vh; overflow-y: auto; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid #e1e7ee;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 750; color: #101a31; margin: 0 0 4px 0;">
                        <i class="fa-solid fa-comment-dots"></i> Witness Statements
                    </h2>
                    <p style="font-size: 10px; color: #8993a2; margin: 0;">Review all witness testimonies related to this incident</p>
                </div>
                <button onclick="closeWitnessModal()" style="background: #f5f7fa; border: 1px solid #e1e7ee; font-size: 18px; color: #718096; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s;">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <div id="witnessModalContent">
                @if(isset($witnessStatements) && $witnessStatements->count() > 0)
                    @foreach($witnessStatements as $index => $witness)
                    <div style="background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 16px; margin-bottom: 14px; transition: all 0.2s;" onmouseover="this.style.borderColor='#079b6d'" onmouseout="this.style.borderColor='#e4e9ee'">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <div style="width: 32px; height: 32px; background: #e9f8f3; border-radius: 8px; display: grid; place-items: center; color: #079b6d; flex-shrink: 0;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #101a31; font-size: 12px;">
                                    Witness {{ $index + 1 }}: {{ $witness->witness_name ?? 'Pending Name' }}
                                </div>
                                <div style="font-size: 9px; color: #8a95a5; margin-top: 2px;">
                                    <i class="fa-solid fa-phone"></i> {{ $witness->phone_number ?? 'N/A' }} &nbsp;|&nbsp; 
                                    <i class="fa-solid fa-calendar"></i> {{ $witness->statement_submitted_at ? $witness->statement_submitted_at->format('d M Y H:i') : 'Not submitted' }}
                                </div>
                            </div>
                        </div>
                        <div style="color: #4e596c; font-size: 11px; line-height: 1.7; white-space: normal; word-wrap: break-word; background: white; padding: 12px; border-radius: 6px; border-left: 3px solid #079b6d;">
                            {{ trim($witness->statement) ?? 'No statement provided' }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 40px; color: #94a3b8;">
                        <i class="fa-solid fa-inbox" style="font-size: 36px; display: block; margin-bottom: 12px; color: #e4e9ef;"></i>
                        <p style="font-size: 12px; color: #8a95a5;">No witness statements available for this incident.</p>
                    </div>
                @endif
            </div>

            <!-- Acknowledgement Checkbox in Modal -->
            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e1e7ee; background: #e9f8f3; padding: 18px; border-radius: 10px; border: 1px solid #a7e6d7;">
                <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px;">
                    <input type="checkbox" id="ackCheckbox" name="acknowledged_witness_statements" value="1" form="hodForm" required style="margin-top: 2px; width: 18px; height: 18px; accent-color: #079b6d; cursor: pointer; flex-shrink: 0;" />
                    <label for="ackCheckbox" style="font-size: 11px; color: #067c58; margin: 0; cursor: pointer; font-weight: 600; line-height: 1.6;">
                        <i class="fa-solid fa-hand-point-right"></i> I acknowledge that I have reviewed all witness statements above and understand their importance in this incident assessment. This information will be considered in my departmental report.
                    </label>
                </div>
                <button type="button" onclick="closeWitnessModal()" class="btn primary" style="width: 100%; padding: 12px 20px; font-size: 11px; justify-content: center; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-check-circle"></i> Close & Return to Report Form
                </button>
            </div>
        </div>
    </div>

    <script>
        function openWitnessModal() {
            document.getElementById('witnessModal').style.display = 'flex';
        }

        function closeWitnessModal() {
            document.getElementById('witnessModal').style.display = 'none';
            // Trigger update when modal closes
            if (typeof updateSubmitButton === 'function') {
                updateSubmitButton();
            }
        }

        // Close modal when clicking outside
        document.getElementById('witnessModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWitnessModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWitnessModal();
            }
        });
    </script>

</body>
</html>
