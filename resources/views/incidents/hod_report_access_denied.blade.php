<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>HOD Report - Waiting · Supervisor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 900px; margin: auto; padding: 35px 25px 70px; }

        
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
        .alert { display: flex; gap: 14px; padding: 17px 20px; background: #fff8e7; border: 1px solid #f6d48b; border-left: 4px solid #f59e0b; border-radius: 10px; margin-bottom: 22px; }
        .alerticon { width: 34px; height: 34px; border-radius: 9px; background: #fff0c9; color: #c67a00; display: grid; place-items: center; flex-shrink: 0; }
        .alert strong { display: block; color: #624300; font-size: 12px; margin-bottom: 4px; font-weight: 700; }
        .alert p { margin: 0; color: #7d6332; font-size: 11px; line-height: 1.6; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .body { padding: 23px; }
        
        /* Checklist */
        .checklist { list-style: none; }
        .checklist-item { display: flex; align-items: flex-start; gap: 12px; padding: 14px; background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 8px; margin-bottom: 10px; font-size: 11px; transition: all 0.2s; }
        .checklist-item:hover { background: #f3f6f8; }
        .checklist-icon { width: 32px; height: 32px; border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; font-size: 14px; }
        .checklist-icon.complete { background: #e9f8f3; color: #079b6d; }
        .checklist-icon.pending { background: #fff8e7; color: #f59e0b; }
        .checklist-text { flex: 1; }
        .checklist-label { font-weight: 700; color: #101a31; display: block; font-size: 12px; margin-bottom: 4px; }
        .checklist-detail { font-size: 10px; color: #8a95a5; display: block; }
        
        /* Progress Bar */
        .witness-progress { margin-top: 10px; }
        .progress-bar { height: 6px; background: #e4e9ee; border-radius: 3px; overflow: hidden; margin-top: 6px; }
        .progress-fill { height: 100%; background: #079b6d; transition: width 0.3s; }
        .progress-text { font-size: 9px; color: #8a95a5; margin-top: 6px; display: block; font-weight: 600; }
        
        /* Info Box */
        .infobox { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 3px solid #3b82f6; border-radius: 10px; padding: 16px 18px; margin-bottom: 22px; }
        .infobox-title { display: flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; color: #1e40af; margin-bottom: 8px; }
        .infobox-title i { font-size: 14px; }
        .infobox p { font-size: 10px; color: #1e40af; line-height: 1.7; margin: 0; }
        .infobox p + p { margin-top: 8px; }
        
        /* Actions */
        .actions { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 16px 20px; display: flex; align-items: center; justify-content: flex-start; gap: 10px; }
        .btn { border-radius: 8px; padding: 10px 17px; font-size: 10px; font-weight: 700; border: 1px solid #d9e0e7; background: white; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #263149; display: inline-flex; align-items: center; gap: 6px; }
        .btn:hover { background: #f8fafb; }
        .btn.primary { background: #079b6d; color: white; border-color: #079b6d; box-shadow: 0 5px 13px rgba(7, 155, 109, .18); }
        .btn.primary:hover { background: #067c58; }
        
        /* Footer */
        .footer { text-align: center; padding: 20px; color: #8a95a5; font-size: 9px; }
        
        /* Responsive */
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .actions { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <h1 class="title">HOD / Supervisor Report - Not Ready</h1>
                    <div class="sub">Waiting for prerequisites to be completed</div>
                    <span class="case">CASE: {{ $incident->reference_number }}</span>
                </div>
            </div>
            <a href="{{ route('dashboards.supervisor') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Alert -->
        <div class="alert">
            <div class="alerticon"><i class="fa-solid fa-info-circle"></i></div>
            <div>
                <strong>Not Ready Yet</strong>
                <p>{{ $message }}</p>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-clipboard-list"></i> &nbsp; Workflow Checklist</div>
            </div>
            <div class="body">
                <ul class="checklist">
                    <!-- Supervisor Verification -->
                    <li class="checklist-item">
                        <div class="checklist-icon {{ $incident->supervisor_verified_at ? 'complete' : 'pending' }}">
                            <i class="fa-solid fa-{{ $incident->supervisor_verified_at ? 'check' : 'clock' }}"></i>
                        </div>
                        <div class="checklist-text">
                            <span class="checklist-label">1. Supervisor Verification</span>
                            @if($incident->supervisor_verified_at)
                                <span class="checklist-detail">✓ Completed on {{ $incident->supervisor_verified_at->format('d M Y, H:i') }}</span>
                            @else
                                <span class="checklist-detail">⏳ Awaiting supervisor verification</span>
                            @endif
                        </div>
                    </li>

                    <!-- Witness Collection -->
                    <li class="checklist-item">
                        <div class="checklist-icon {{ count($incident->witnessTokens) > 0 ? 'complete' : 'pending' }}">
                            <i class="fa-solid fa-{{ count($incident->witnessTokens) > 0 ? 'check' : 'clock' }}"></i>
                        </div>
                        <div class="checklist-text">
                            <span class="checklist-label">2. Witness Notification</span>
                            @if(count($incident->witnessTokens) > 0)
                                <span class="checklist-detail">✓ {{ count($incident->witnessTokens) }} witness(es) added</span>
                            @else
                                <span class="checklist-detail">⏳ No witnesses added yet</span>
                            @endif
                        </div>
                    </li>

                    <!-- Witness Statements -->
                    @if(count($incident->witnessTokens) > 0)
                    <li class="checklist-item">
                        <div class="checklist-icon {{ $incident->allWitnessStatementsReceived() ? 'complete' : 'pending' }}">
                            <i class="fa-solid fa-{{ $incident->allWitnessStatementsReceived() ? 'check' : 'clock' }}"></i>
                        </div>
                        <div class="checklist-text">
                            <span class="checklist-label">3. Witness Statements</span>
                            @php
                                $submitted = $incident->witnessTokens->filter(fn($t) => $t->statement_submitted_at)->count();
                                $total = count($incident->witnessTokens);
                                $percentage = ($submitted / $total) * 100;
                            @endphp
                            <span class="checklist-detail">{{ $submitted }} of {{ $total }} statements received</span>
                            @if($submitted < $total)
                            <div class="witness-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                                </div>
                                <span class="progress-text">{{ number_format($percentage, 0) }}% complete - Waiting for {{ $total - $submitted }} witness(es)</span>
                            </div>
                            @endif
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="infobox">
            <div class="infobox-title">
                <i class="fa-solid fa-arrow-right"></i>
                What To Do Next
            </div>
            <p>{{ $action }}</p>
            @if($reason === 'pending_witnesses')
                <p>You can check witness submission status in real-time from the witness management page. The system will notify you once all witnesses have submitted their statements.</p>
            @endif
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('dashboards.supervisor') }}" class="btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            @if($reason === 'pending_witnesses')
                <a href="{{ route('incidents.supervisor_witness', ['incident' => $incident]) }}" class="btn primary">
                    <i class="fa-solid fa-users"></i> Check Witness Status
                </a>
            @endif
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} HSC-OHS. All rights reserved.
        </div>
    </div>
</body>
</html>
