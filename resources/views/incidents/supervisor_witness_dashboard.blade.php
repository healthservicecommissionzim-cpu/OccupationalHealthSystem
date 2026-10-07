<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Witness Management Dashboard · Supervisor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            font-size: 14px; 
            background: #f1f5f9; 
            padding: 2rem; 
            min-height: 100vh; 
        }
        .container { max-width: 1200px; margin: 0 auto; }
        
        .header { 
            margin-bottom: 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: start; 
        }
        .header h1 { 
            font-size: 24px; 
            font-weight: 700; 
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .header p { 
            font-size: 13px; 
            color: #64748b; 
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #1D4533;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .back-btn:hover {
            background: #1D4533;
            color: white;
            transform: translateX(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }
        
        .witness-count {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #1D4533 0%, #2d6d4d 100%);
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .witness-table-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        
        th {
            padding: 1rem;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s ease;
        }
        
        tbody tr:hover {
            background: #f8fafc;
        }
        
        td {
            padding: 1rem;
            font-size: 13px;
            color: #334155;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .status-badge.submitted {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-badge i {
            font-size: 10px;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border: 1px solid #1D4533;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover:not(:disabled) {
            background: #1D4533;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(29, 69, 51, 0.2);
        }
        
        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: #cbd5e1;
            color: #94a3b8;
        }
        
        .action-btn.download {
            background: #1D4533;
            color: white;
        }
        
        .action-btn.download:hover {
            background: #163b28;
        }
        
        .date-text {
            font-size: 12px;
            color: #64748b;
        }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        .sms-history-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            background: #f0fdf4;
            color: #065f46;
            border: 1px solid #10b981;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .sms-history-btn:hover {
            background: #dcfce7;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }
        
        .sms-history-row {
            display: none;
        }
        
        .sms-history-row.expanded {
            display: table-row;
        }
        
        .sms-history-cell {
            padding: 1rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        
        .sms-history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        .sms-history-table thead {
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sms-history-table th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .sms-history-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sms-history-table td {
            padding: 0.75rem;
            color: #334155;
        }
        
        .sms-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .sms-type-badge.initial {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .sms-type-badge.reminder {
            background: #f3e8ff;
            color: #6b21a8;
        }
        
        .sms-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .sms-status-badge.sent {
            background: #d1fae5;
            color: #065f46;
        }
        
        .sms-status-badge.failed {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .error-message {
            font-size: 11px;
            color: #991b1b;
            margin-top: 0.25rem;
            padding: 0.5rem;
            background: #fef2f2;
            border-left: 2px solid #ef4444;
            border-radius: 4px;
            max-width: 300px;
            word-wrap: break-word;
        }
        
        .retry-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.625rem;
            background: white;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .retry-btn:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }
        
        .no-history {
            padding: 1rem;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            font-style: italic;
        }
        
        .attachment-metadata {
            font-size: 12px;
            color: #475569;
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 6px;
            margin-top: 0.5rem;
            display: grid;
            gap: 0.5rem;
        }
        
        .attachment-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .attachment-label {
            font-weight: 600;
            color: #64748b;
            min-width: 100px;
        }
        
        .attachment-value {
            color: #334155;
            word-break: break-all;
        }
        
        .file-size {
            color: #94a3b8;
            font-size: 11px;
        }
        
        .toast {
            position: fixed;
            top: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 14px;
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        
        .toast.success {
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        
        .toast.error {
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* ─── Button Loading States ─── */
        .action-btn.loading {
            position: relative;
            color: transparent;
        }

        .action-btn.loading::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            top: 50%;
            left: 50%;
            margin-left: -6px;
            margin-top: -6px;
            border: 2px solid #f3f4f6;
            border-radius: 50%;
            border-top-color: #1D4533;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ─── Improved Toast Styling ─── */
        .toast {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .header {
                flex-direction: column;
                gap: 1rem;
            }

            .header h1 {
                font-size: 20px;
            }

            .header p {
                font-size: 12px;
            }
            
            .back-btn {
                width: 100%;
                text-align: center;
                padding: 0.625rem 0.75rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .info-item {
                padding: 0.75rem 0;
                border-bottom: 1px solid #e2e8f0;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .info-label {
                font-size: 10px;
            }

            .info-value {
                font-size: 13px;
                margin-top: 0.25rem;
            }

            .witness-count {
                width: 100%;
                justify-content: center;
                padding: 0.75rem;
                font-size: 13px;
            }
            
            .witness-table-card {
                border-radius: 8px;
                overflow: hidden;
            }

            .table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                font-size: 11px;
                min-width: 600px;
            }
            
            th {
                padding: 0.75rem 0.5rem;
                font-size: 10px;
            }
            
            td {
                padding: 0.75rem 0.5rem;
                font-size: 11px;
            }

            .status-badge {
                padding: 0.3rem 0.5rem;
                font-size: 10px;
            }
            
            .action-btn {
                padding: 0.4rem 0.6rem;
                font-size: 10px;
                white-space: nowrap;
            }

            .action-btn i {
                font-size: 0.8rem;
            }

            .date-text {
                font-size: 11px;
                display: block;
                margin-top: 0.25rem;
            }

            .sms-history-btn {
                padding: 0.4rem 0.6rem;
                font-size: 10px;
            }

            .sms-history-table {
                font-size: 10px;
            }

            .sms-history-table th,
            .sms-history-table td {
                padding: 0.5rem 0.375rem;
            }

            .attachment-metadata {
                font-size: 11px;
                padding: 0.5rem;
                gap: 0.25rem;
            }

            .attachment-item {
                flex-direction: column;
                gap: 0.25rem;
            }

            .attachment-label {
                font-size: 10px;
                min-width: auto;
                color: #94a3b8;
            }

            .attachment-value {
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.75rem;
            }

            .container {
                max-width: 100%;
            }

            .header {
                gap: 0.75rem;
            }

            .header h1 {
                font-size: 16px;
                margin-bottom: 0;
            }

            .header p {
                font-size: 11px;
                margin-top: 0.25rem;
            }

            .back-btn {
                padding: 0.5rem 0.75rem;
                font-size: 11px;
                width: 100%;
            }

            .info-card {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .info-label {
                font-size: 9px;
            }

            .info-value {
                font-size: 12px;
            }

            .witness-count {
                padding: 0.5rem;
                font-size: 12px;
                gap: 0.5rem;
            }

            .witness-count i {
                font-size: 0.9rem;
            }

            .witness-table-card {
                margin-bottom: 1rem;
            }

            table {
                font-size: 10px;
                min-width: 550px;
            }

            th {
                padding: 0.5rem 0.375rem;
                font-size: 9px;
            }

            td {
                padding: 0.5rem 0.375rem;
                font-size: 10px;
            }

            .status-badge {
                padding: 0.25rem 0.4rem;
                font-size: 9px;
            }

            .status-badge i {
                font-size: 8px;
            }

            .action-btn {
                padding: 0.375rem 0.5rem;
                font-size: 9px;
            }

            .action-btn i {
                font-size: 0.7rem;
            }

            .date-text {
                font-size: 10px;
            }

            .sms-history-btn {
                padding: 0.375rem 0.5rem;
                font-size: 9px;
            }

            .sms-type-badge,
            .sms-status-badge {
                padding: 0.2rem 0.4rem;
                font-size: 9px;
            }

            .sms-type-badge i,
            .sms-status-badge i {
                font-size: 0.7rem;
            }

            .sms-history-table th,
            .sms-history-table td {
                padding: 0.375rem 0.25rem;
                font-size: 9px;
            }

            .error-message {
                font-size: 9px;
                padding: 0.375rem;
                max-width: 100%;
            }

            .attachment-metadata {
                font-size: 10px;
                padding: 0.375rem;
            }

            .attachment-label {
                font-size: 9px;
            }

            .attachment-value {
                font-size: 10px;
            }

            .file-size {
                font-size: 9px;
            }

            /* Modal adjustments for small screens */
            #statementModal > div {
                width: 95% !important;
                max-height: 85vh !important;
            }

            #statementModal > div > div:first-child {
                padding: 1rem !important;
            }

            #statementModal > div > div:last-child {
                padding: 1rem !important;
                max-height: calc(85vh - 100px) !important;
            }

            .toast {
                top: 0.5rem !important;
                right: 0.5rem !important;
                left: 0.5rem !important;
                width: auto;
                max-width: calc(100% - 1rem);
                font-size: 12px;
                padding: 0.75rem 1rem;
            }
        }

        /* Tablet-specific improvements */
        @media (min-width: 481px) and (max-width: 768px) {
            .witness-count {
                max-width: 400px;
            }

            .action-btn {
                white-space: nowrap;
            }
        }

        /* Touch-friendly button sizes */
        @media (hover: none) and (pointer: coarse) {
            .action-btn,
            .sms-history-btn,
            button {
                min-height: 44px;
                min-width: 44px;
                padding: 0.5rem 0.875rem;
            }

            .status-badge {
                min-height: 32px;
                display: inline-flex;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Witness Management Dashboard</h1>
                <p>Incident Reference: <strong>{{ $incident->reference_number }}</strong></p>
            </div>
            <a href="{{ route('dashboards.supervisor') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
        
        <!-- Incident Information Card -->
        <div class="info-card">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Worker Name</span>
                    <span class="info-value">{{ $incident->first_name }} {{ $incident->surname }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Facility</span>
                    <span class="info-value">{{ $incident->facility->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Department</span>
                    <span class="info-value">{{ $incident->department->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Incident Date</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($incident->incident_date)->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Witness Count Badge -->
        <div class="witness-count">
            <i class="fas fa-users"></i>
            <span>{{ $submittedCount }} submitted / {{ $totalCount }} total witnesses</span>
        </div>
        
        <!-- Witness Table -->
        <div class="witness-table-card">
            <div class="table-wrapper">
                @if($witnessTokens->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Last Reminder</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($witnessTokens as $token)
                        <tr>
                            <td>{{ $token->witness_name }}</td>
                            <td>{{ $token->phone_number }}</td>
                            <td>
                                @if($token->statement_submitted_at)
                                    <span class="status-badge submitted">
                                        <i class="fas fa-check-circle"></i>
                                        Submitted
                                    </span>
                                @else
                                    <span class="status-badge pending">
                                        <i class="fas fa-clock"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{-- Task 9.1: Show submission date/time for submitted witnesses --}}
                                @if($token->statement_submitted_at)
                                    <span class="date-text">
                                        {{ \Carbon\Carbon::parse($token->statement_submitted_at)->format('d M Y, H:i') }}
                                    </span>
                                @else
                                    <span class="date-text">—</span>
                                @endif
                            </td>
                            <td>
                                @if($token->witness_last_reminder_sent_at)
                                    <span class="date-text">
                                        {{ \Carbon\Carbon::parse($token->witness_last_reminder_sent_at)->format('d M Y, H:i') }}
                                    </span>
                                @else
                                    <span class="date-text">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                                    @if($token->statement_submitted_at)
                                        <!-- View statement (Task 5.4) -->
                                        @if($token->statement)
                                            <button 
                                                class="action-btn view-statement-btn" 
                                                data-statement="{{ htmlspecialchars($token->statement, ENT_QUOTES, 'UTF-8') }}"
                                                data-witness-name="{{ $token->witness_name }}"
                                                data-submitted-at="{{ \Carbon\Carbon::parse($token->statement_submitted_at)->format('d M Y, H:i') }}">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </button>
                                            <a 
                                                href="{{ route('incidents.download_witness_statement', ['incident' => $incident->id, 'tokenId' => $token->id]) }}"
                                                class="action-btn download"
                                                title="Download witness statement">
                                                <i class="fas fa-download"></i>
                                                Download
                                            </a>
                                        @else
                                            <span class="date-text">No statement</span>
                                        @endif
                                    @else
                                        <!-- Send Reminder button (Task 6.3) -->
                                        <button 
                                            class="action-btn send-reminder-btn" 
                                            data-token-id="{{ $token->id }}"
                                            data-witness-name="{{ $token->witness_name }}">
                                            <i class="fas fa-paper-plane"></i>
                                            Send Reminder
                                        </button>
                                    @endif
                                    
                                    <!-- Task 7.1: SMS History button -->
                                    @if(isset($smsHistory[$token->id]) && $smsHistory[$token->id]->count() > 0)
                                        <button 
                                            class="sms-history-btn toggle-sms-history" 
                                            data-token-id="{{ $token->id }}"
                                            title="View SMS history for this witness">
                                            <i class="fas fa-envelope"></i>
                                            SMS History
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        
                        {{-- Task 9.2: Show attachment metadata for submitted witnesses --}}
                        @if($token->statement_submitted_at && $token->attachments->count() > 0)
                        <tr style="background: #f8fafc;">
                            <td colspan="6">
                                <div style="padding: 0.5rem 1rem;">
                                    <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 0.75rem;">
                                        <i class="fas fa-paperclip" style="margin-right: 0.5rem;"></i>
                                        Statement Files
                                    </div>
                                    @foreach($token->attachments as $attachment)
                                    <div class="attachment-metadata">
                                        <div class="attachment-item">
                                            <span class="attachment-label">File Name:</span>
                                            <span class="attachment-value">{{ $attachment->file_name }}</span>
                                        </div>
                                        <div class="attachment-item">
                                            <span class="attachment-label">File Size:</span>
                                            <span class="attachment-value file-size">{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                                        </div>
                                        <div class="attachment-item">
                                            <span class="attachment-label">Uploaded:</span>
                                            <span class="attachment-value">{{ \Carbon\Carbon::parse($attachment->created_at)->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                        
                        @if(isset($smsHistory[$token->id]) && $smsHistory[$token->id]->count() > 0)
                        <tr class="sms-history-row" id="sms-history-{{ $token->id }}">
                            <td colspan="6" class="sms-history-cell">
                                <div style="margin-bottom: 0.5rem;">
                                    <h4 style="font-size: 13px; font-weight: 600; color: #0f172a; margin-bottom: 0.75rem;">
                                        <i class="fas fa-history" style="margin-right: 0.5rem;"></i>
                                        SMS History
                                    </h4>
                                </div>
                                <table class="sms-history-table">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Phone</th>
                                            <th>Initiated By</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($smsHistory[$token->id] as $notification)
                                        <tr>
                                            <td>
                                                {{ \Carbon\Carbon::parse($notification->sent_at)->format('d M Y, H:i') }}
                                            </td>
                                            <td>
                                                @if($notification->notification_type === 'WITNESS_INITIAL_SMS')
                                                    <span class="sms-type-badge initial">
                                                        <i class="fas fa-arrow-right"></i>
                                                        Initial
                                                    </span>
                                                @elseif($notification->notification_type === 'WITNESS_REMINDER_SMS')
                                                    <span class="sms-type-badge reminder">
                                                        <i class="fas fa-redo"></i>
                                                        Reminder
                                                    </span>
                                                @else
                                                    <span style="font-size: 11px; color: #64748b;">{{ $notification->notification_type }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($notification->status === 'SENT')
                                                    <span class="sms-status-badge sent">
                                                        <i class="fas fa-check"></i>
                                                        Sent
                                                    </span>
                                                @elseif($notification->status === 'DELIVERED')
                                                    <span class="sms-status-badge sent">
                                                        <i class="fas fa-check-double"></i>
                                                        Delivered
                                                    </span>
                                                @elseif($notification->status === 'FAILED')
                                                    <span class="sms-status-badge failed">
                                                        <i class="fas fa-times"></i>
                                                        Failed
                                                    </span>
                                                @else
                                                    <span style="font-size: 11px; color: #64748b;">{{ $notification->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span style="font-size: 12px; color: #475569; font-family: monospace;">
                                                    {{ $notification->recipient_phone ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{-- Task 7.3: Display supervisor who initiated SMS --}}
                                                @if($notification->initiatedBy)
                                                    <span style="font-size: 11px; color: #475569;">
                                                        {{ $notification->initiatedBy->first_name }} {{ $notification->initiatedBy->surname }}
                                                    </span>
                                                @else
                                                    <span style="font-size: 11px; color: #94a3b8;">System</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($notification->status === 'FAILED' && $notification->error_message)
                                                    <div class="error-message">
                                                        {{ $notification->error_message }}
                                                    </div>
                                                @elseif($notification->message_content)
                                                    <span style="font-size: 11px; color: #64748b; display: block; max-width: 300px; word-break: break-word;">
                                                        {{ Str::limit($notification->message_content, 100, '...') }}
                                                    </span>
                                                @else
                                                    <span style="font-size: 11px; color: #94a3b8;">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endif
                        
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>No witnesses have been added to this incident yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Statement View Modal -->
    <div id="statementModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; max-width: 700px; width: 90%; max-height: 80vh; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Witness Statement</h2>
                    <p id="modalWitnessInfo" style="font-size: 13px; color: #64748b;"></p>
                </div>
                <button id="closeModalBtn" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 1.5rem; overflow-y: auto; max-height: calc(80vh - 120px);">
                <div id="modalStatementContent" style="font-size: 14px; line-height: 1.6; color: #334155; white-space: pre-wrap; word-wrap: break-word;"></div>
            </div>
        </div>
    </div>
    
    <script>
        // Handle reminder SMS sending (Task 6.3)
        document.addEventListener('DOMContentLoaded', function() {
            // Task 7.1: Handle SMS history toggle
            const smsHistoryButtons = document.querySelectorAll('.toggle-sms-history');
            
            smsHistoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tokenId = this.dataset.tokenId;
                    const historyRow = document.getElementById(`sms-history-${tokenId}`);
                    
                    if (historyRow) {
                        historyRow.classList.toggle('expanded');
                        
                        // Update button appearance
                        if (historyRow.classList.contains('expanded')) {
                            this.style.background = '#dcfce7';
                            this.innerHTML = '<i class="fas fa-chevron-up"></i> Hide SMS History';
                        } else {
                            this.style.background = '#f0fdf4';
                            this.innerHTML = '<i class="fas fa-envelope"></i> SMS History';
                        }
                    }
                });
            });
            
            const reminderButtons = document.querySelectorAll('.send-reminder-btn');
            
            reminderButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const tokenId = this.dataset.tokenId;
                    const witnessName = this.dataset.witnessName;
                    
                    // Store original state
                    const originalHTML = this.innerHTML;
                    
                    // Disable button during request and show loading state
                    this.disabled = true;
                    this.classList.add('loading');
                    this.innerHTML = '';
                    
                    try {
                        const response = await fetch(`/supervisor/incidents/{{ $incident->id }}/witness/${tokenId}/reminder`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showToast('success', `Reminder SMS sent to ${witnessName}`);
                            
                            // Update last reminder date in the table after a short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast('error', data.message || 'Failed to send reminder SMS');
                            this.disabled = false;
                            this.classList.remove('loading');
                            this.innerHTML = originalHTML;
                        }
                    } catch (error) {
                        console.error('Error sending reminder:', error);
                        showToast('error', 'An error occurred while sending the reminder');
                        this.disabled = false;
                        this.classList.remove('loading');
                        this.innerHTML = originalHTML;
                    }
                });
            });
            
            // Handle view statement buttons (Task 5.4)
            const viewStatementButtons = document.querySelectorAll('.view-statement-btn');
            const modal = document.getElementById('statementModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const modalWitnessInfo = document.getElementById('modalWitnessInfo');
            const modalStatementContent = document.getElementById('modalStatementContent');
            
            viewStatementButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const statement = this.dataset.statement;
                    const witnessName = this.dataset.witnessName;
                    const submittedAt = this.dataset.submittedAt;
                    
                    modalWitnessInfo.textContent = `${witnessName} - Submitted on ${submittedAt}`;
                    modalStatementContent.textContent = statement;
                    modal.style.display = 'flex';
                    
                    showToast('success', 'Statement loaded. Close this when done reading.');
                });
            });
            
            closeModalBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    modal.style.display = 'none';
                }
            });
        });
        
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            // Auto-remove after 4 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        }
    </script>
</body>
</html>