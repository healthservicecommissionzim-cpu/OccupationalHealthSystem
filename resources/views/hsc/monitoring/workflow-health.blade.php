<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Workflow Health · HSC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1400px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Stats Card */
        .stat-card { background: white; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; margin-bottom: 20px; text-align: center; border-left: 3px solid #079b6d; }
        .stat-number { font-size: 40px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 11px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .ctitle i { color: #079b6d; }
        
        /* Workflow Stage Row */
        .workflow-row { padding: 12px 22px; border-bottom: 1px solid #e4e9ee; display: flex; align-items: center; gap: 16px; transition: background 0.2s; }
        .workflow-row:hover { background: #f8fafb; }
        .workflow-row:last-child { border-bottom: none; }
        
        .stage-info { flex: 1; }
        .stage-name { font-size: 12px; font-weight: 700; color: #101a31; margin-bottom: 6px; display: block; }
        .progress-bar-container { width: 100%; height: 6px; background: #e4e9ee; border-radius: 3px; overflow: hidden; }
        .progress-bar { height: 100%; background: linear-gradient(90deg, #079b6d 0%, #06d888 100%); border-radius: 3px; transition: width 0.5s ease; }
        
        .stage-count { min-width: 60px; text-align: right; }
        .count-number { font-size: 18px; font-weight: 750; color: #079b6d; display: block; }
        .count-label { font-size: 9px; color: #8a95a5; text-transform: uppercase; font-weight: 600; margin-top: 2px; }
        
        .stage-percent { min-width: 60px; text-align: right; font-size: 14px; font-weight: 700; color: #64748b; }
        
        /* Footer Total */
        .footer-total { padding: 18px 22px; background: #f8fafb; border-top: 1px solid #e4e9ee; display: flex; justify-content: space-between; align-items: center; }
        .footer-text { font-size: 12px; color: #64748b; font-weight: 600; }
        .footer-number { font-size: 20px; font-weight: 750; color: #079b6d; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        @media (max-width: 600px) {
            .header { flex-direction: column; gap: 12px; }
            .workflow-row { flex-direction: column; align-items: stretch; text-align: left; }
            .stage-count, .stage-percent { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="page">
        
        {{-- Page Header --}}
        <div class="header">
            <div class="head">
                <div class="icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>
                    <h1 class="title">Workflow Health Monitoring</h1>
                    <p class="sub">Monitor case processing efficiency through workflow stages</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @php
            $total = collect($stageCounts)->sum();
        @endphp

        {{-- Workflow Stages Card --}}
        <div class="card">
            <div class="chead">
                <div class="ctitle">
                    <i class="fas fa-stream"></i>
                    Cases by Workflow Stage
                </div>
            </div>
            
            @if($total > 0)
                @foreach($stageCounts as $stage => $count)
                    @if($count > 0)
                        @php
                            $percentage = ($count / $total) * 100;
                            
                            // Map technical statuses to user-friendly, actionable labels
                            $statusMap = [
                                'PENDING_SUPERVISOR_VERIFICATION' => 'Awaiting Supervisor to Verify',
                                'VERIFIED_SUPERVISOR' => 'Supervisor Verified',
                                'REJECTED_SUPERVISOR' => 'Rejected by Supervisor',
                                'ESCALATED_TO_HR' => 'Awaiting HR Review',
                                'PENDING_HR_REVIEW' => 'Awaiting HR to Review',
                                'APPROVED_BY_HR' => 'HR Approved - Processing',
                                'REJECTED_BY_HR' => 'Rejected by HR',
                                'ESCALATED_TO_DMO' => 'Awaiting District Medical Officer to Action',
                                'ESCALATED_TO_MEDICAL_SUPERINTENDENT' => 'Awaiting Medical Superintendent to Action',
                                'ESCALATED_TO_PMD' => 'Awaiting Provincial Medical Director to Action',
                                'PENDING_PMD_REVIEW' => 'Awaiting PMD to Review and Decide',
                                'APPROVED_BY_PMD' => 'PMD Approved - Ready for Closure',
                                'REJECTED_BY_PMD' => 'Rejected by PMD',
                                'UNDER_MEDICAL_REVIEW' => 'Medical Board Reviewing',
                                'PENDING_MEDICAL_BOARD' => 'Awaiting Medical Board Decision',
                                'RESOLVED' => 'Case Resolved',
                                'CLOSED' => 'Case Closed',
                            ];
                            
                            $formattedStage = $statusMap[$stage] ?? str_replace('_', ' ', ucwords(strtolower($stage)));
                        @endphp
                        <div class="workflow-row">
                            <div class="stage-info">
                                <span class="stage-name">{{ $formattedStage }}</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="stage-count">
                                <span class="count-number">{{ $count }}</span>
                                <div class="count-label">Cases</div>
                            </div>
                            <div class="stage-percent">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                    @endif
                @endforeach
                
                <div class="footer-total">
                    <span class="footer-text">Total Cases in All Stages</span>
                    <span class="footer-number">{{ number_format($total) }}</span>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No cases in workflow at this time.</p>
                </div>
            @endif
        </div>

    </div>
</body>
</html>
