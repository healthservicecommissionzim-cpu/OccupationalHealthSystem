<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Communication Logs · System</title>
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
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e8f5ff; color: #0ea5e9; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
        .search-box:focus-within { border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #0ea5e9; }
        .filter-select:focus { border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1); }
        
        .btn { padding: 8px 14px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; }
        .btn-primary { background: #0ea5e9; color: white; }
        .btn-primary:hover { background: #0284c7; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        
        .filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .badge-registration { background: #dbeafe; color: #1e40af; }
        .badge-approval { background: #dcfce7; color: #166534; }
        .badge-rejection { background: #fee2e2; color: #991b1b; }
        .badge-incident-confirmation { background: #f3e8ff; color: #6d28d9; }
        .badge-document-upload { background: #f3e8ff; color: #6d28d9; }
        .badge-email { background: #cffafe; color: #164e63; }
        .badge-sms { background: #ccfbf1; color: #134e4a; }
        .badge-database { background: #f3f4f6; color: #374151; }
        .badge-sent { background: #dcfce7; color: #166534; }
        .badge-failed { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        
        .link { color: #0ea5e9; text-decoration: none; font-weight: 650; cursor: pointer; }
        .link:hover { color: #0284c7; text-decoration: underline; }
        
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Pagination */
        .pagination-wrapper { padding: 16px 22px; background: white; border-top: 1px solid #e4e9ee; }
        .pagination { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; align-items: center; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e4e9ee; background: white; cursor: pointer; border-radius: 6px; font-size: 11px; font-weight: 650; transition: all 0.2s; text-decoration: none; color: #4e596c; min-width: 36px; text-align: center; }
        .pagination a:hover { border-color: #0ea5e9; color: #0ea5e9; background: #f0f9fd; }
        .pagination .active span { background: #0ea5e9; color: white; border-color: #0ea5e9; }
        .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; background: #f8fafb; }
        
        @media (max-width: 900px) {
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
            .search-box { min-width: 100%; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
        }
    </style>
</head>
<body>
<div class="page">
    <!-- Header -->
    <div class="header">
        <div class="head">
            <div class="icon"><i class="fa-solid fa-envelope"></i></div>
            <div>
                <h1 class="title">Communication Logs</h1>
                <div class="sub">Track all emails and SMS sent from the system</div>
                <span class="case">Total: {{ $logs->total() }} communications</span>
            </div>
        </div>
        <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <!-- Communications Table -->
    <div class="card">
        <div class="chead">
            <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Communication Records</div>
            <div class="filters">
                <form method="GET" action="{{ route('system.communication_logs.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%;">
                    <select name="type" class="filter-select">
                        <option value="">All Types</option>
                        <option value="REGISTRATION" {{ request('type') == 'REGISTRATION' ? 'selected' : '' }}>Registration</option>
                        <option value="APPROVAL" {{ request('type') == 'APPROVAL' ? 'selected' : '' }}>Approval</option>
                        <option value="REJECTION" {{ request('type') == 'REJECTION' ? 'selected' : '' }}>Rejection</option>
                        <option value="INCIDENT_CONFIRMATION" {{ request('type') == 'INCIDENT_CONFIRMATION' ? 'selected' : '' }}>Incident</option>
                        <option value="DOCUMENT_UPLOAD" {{ request('type') == 'DOCUMENT_UPLOAD' ? 'selected' : '' }}>Document Upload</option>
                    </select>
                    <select name="channel" class="filter-select">
                        <option value="">All Channels</option>
                        <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="database" {{ request('channel') == 'database' ? 'selected' : '' }}>Database</option>
                    </select>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        <option value="SENT" {{ request('status') == 'SENT' ? 'selected' : '' }}>Sent</option>
                        <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <select name="date_range" class="filter-select">
                        <option value="7" {{ request('date_range') == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ request('date_range', '30') == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('date_range') == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="all" {{ request('date_range') == 'all' ? 'selected' : '' }}>All Time</option>
                    </select>
                    <div class="search-box" style="flex: 1; min-width: 200px;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" placeholder="Email, phone, subject..." value="{{ request('search') }}" />
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Filter</button>
                    <a href="{{ route('system.communication_logs.index') }}" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                    <a href="{{ route('system.communication_logs.export', request()->query()) }}" class="btn btn-success"><i class="fa-solid fa-download"></i> Export</a>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Type</th>
                        <th>Channel</th>
                        <th>Recipient</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th>Sent At</th>
                        <th style="width: 60px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                        <td><span class="badge badge-{{ strtolower(str_replace('_', '-', $log->communication_type)) }}"><i class="fa-solid fa-circle" style="font-size: 6px;"></i> {{ str_replace('_', ' ', $log->communication_type) }}</span></td>
                        <td><span class="badge badge-{{ strtolower($log->channel) }}"><i class="fa-solid fa-{{ $log->channel == 'email' ? 'envelope' : ($log->channel == 'sms' ? 'comment' : 'database') }}"></i> {{ ucfirst($log->channel) }}</span></td>
                        <td>
                            @if($log->recipient_email){{ $log->recipient_email }}@endif
                            @if($log->recipient_phone && $log->recipient_email)<br>@endif
                            @if($log->recipient_phone)<small>{{ $log->recipient_phone }}</small>@endif
                        </td>
                        <td>{{ Str::limit($log->subject, 35) }}</td>
                        <td><span class="badge badge-{{ strtolower($log->status) }}"><i class="fa-solid fa-{{ $log->status == 'SENT' ? 'check' : ($log->status == 'FAILED' ? 'xmark' : 'clock') }}"></i> {{ $log->status }}</span></td>
                        <td>
                            <span class="badge badge-{{ strtolower(str_replace('_', '-', $log->delivery_status ?? 'PENDING')) }}" title="{{ $log->failure_reason ?? 'No issues' }}">
                                <i class="fa-solid fa-{{ $log->delivery_status == 'DELIVERED' ? 'check-double' : ($log->delivery_status == 'FAILED' ? 'exclamation' : ($log->delivery_status == 'PENDING' ? 'hourglass' : 'circle-exclamation')) }}"></i>
                                {{ $log->delivery_status ?? 'PENDING' }}
                            </span>
                        </td>
                        <td>{{ $log->sent_at?->format('M d, H:i') ?? '-' }}</td>
                        <td><a href="javascript:void(0)" class="link" onclick="openLogModal({{ $log->id }})">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <p>No communications found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination">
                {{ $logs->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div id="logModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="max-width: 900px; margin: 20px auto; background: white; border-radius: 14px; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
        <!-- Modal Header -->
        <div style="padding: 24px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center;">
            <h2 id="modalTitle" style="margin: 0; font-size: 20px; font-weight: 700; color: #101a31;">Communication Details</h2>
            <button onclick="closeLogModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #8a95a5;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div id="modalContent" style="padding: 24px; overflow-y: auto; max-height: 70vh;">
            <div style="text-align: center; padding: 40px;">
                <div style="display: inline-block; padding: 20px; background: #f0f4f8; border-radius: 50%; margin-bottom: 15px;">
                    <i class="fa-solid fa-spinner" style="font-size: 30px; color: #0ea5e9; animation: spin 1s linear infinite;"></i>
                </div>
                <p style="color: #64748b; font-weight: 600;">Loading details...</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .modal-section {
        margin-bottom: 20px;
        border: 1px solid #e1e7ee;
        border-radius: 12px;
        padding: 16px;
    }
    
    .modal-section-title {
        font-size: 13px;
        font-weight: 750;
        color: #8a95a5;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    
    .modal-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }
    
    .modal-field {
        padding: 12px;
        background: #f8fafb;
        border-radius: 8px;
    }
    
    .modal-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    
    .modal-value {
        font-size: 13px;
        color: #263149;
        font-weight: 600;
        word-break: break-all;
    }
    
    .timeline-item {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
    }
    
    .timeline-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 4px;
    }
    
    .failure-box {
        border-left: 4px solid #ef4444;
        background: #fef2f2;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    
    .failure-box-title {
        font-weight: 700;
        color: #991b1b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .failure-box-content {
        font-size: 12px;
        color: #7f1d1d;
        line-height: 1.6;
    }
    
    .content-box {
        background: #f8fafb;
        padding: 12px;
        border-radius: 8px;
        font-size: 12px;
        color: #4e596c;
        line-height: 1.6;
        max-height: 200px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>

<script>
    function openLogModal(logId) {
        const modal = document.getElementById('logModal');
        const modalContent = document.getElementById('modalContent');
        
        // Fetch log details
        fetch(`/system/communication-logs/${logId}/details`)
            .then(response => response.json())
            .then(data => {
                modalContent.innerHTML = buildModalContent(data);
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<p style="color: #ef4444; text-align: center; padding: 20px;">Error loading details</p>';
                modal.style.display = 'block';
            });
    }
    
    function closeLogModal() {
        const modal = document.getElementById('logModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    function buildModalContent(data) {
        let html = '';
        
        // Status badges
        html += `
            <div class="modal-grid" style="margin-bottom: 20px;">
                <div class="modal-field">
                    <div class="modal-label">Type</div>
                    <div class="modal-value">${escapeHtml(data.communication_type)}</div>
                </div>
                <div class="modal-field">
                    <div class="modal-label">Channel</div>
                    <div class="modal-value">${escapeHtml(data.channel)}</div>
                </div>
                <div class="modal-field">
                    <div class="modal-label">Status</div>
                    <div class="modal-value">${escapeHtml(data.status)}</div>
                </div>
                <div class="modal-field">
                    <div class="modal-label">Delivery Status</div>
                    <div class="modal-value">${escapeHtml(data.delivery_status || 'PENDING')}</div>
                </div>
            </div>
        `;
        
        // Recipient info
        html += `
            <div class="modal-section">
                <div class="modal-section-title"><i class="fa-solid fa-user"></i> Recipient Information</div>
                <div class="modal-grid">
                    ${data.recipient_email ? `<div class="modal-field"><div class="modal-label">Email</div><div class="modal-value">${escapeHtml(data.recipient_email)}</div></div>` : ''}
                    ${data.recipient_phone ? `<div class="modal-field"><div class="modal-label">Phone</div><div class="modal-value">${escapeHtml(data.recipient_phone)}</div></div>` : ''}
                    ${data.recipient_name ? `<div class="modal-field"><div class="modal-label">User</div><div class="modal-value">${escapeHtml(data.recipient_name)}</div></div>` : ''}
                </div>
            </div>
        `;
        
        // Message
        html += `
            <div class="modal-section">
                <div class="modal-section-title"><i class="fa-solid fa-envelope"></i> Message</div>
                <div class="modal-field">
                    <div class="modal-label">Subject</div>
                    <div class="modal-value">${escapeHtml(data.subject)}</div>
                </div>
                ${data.message_preview ? `
                    <div class="modal-field" style="margin-top: 12px;">
                        <div class="modal-label">Preview</div>
                        <div class="content-box">${escapeHtml(data.message_preview)}</div>
                    </div>
                ` : ''}
            </div>
        `;
        
        // Delivery timeline
        if (data.timeline && data.timeline.length > 0) {
            html += `
                <div class="modal-section">
                    <div class="modal-section-title"><i class="fa-solid fa-timeline"></i> Delivery Timeline</div>
                    ${data.timeline.map(event => `
                        <div class="timeline-item">
                            <div class="timeline-dot" style="background: ${event.color};"></div>
                            <div>
                                <div style="font-weight: 700; color: #263149; font-size: 12px;">${escapeHtml(event.title)}</div>
                                <div style="font-size: 11px; color: #8a95a5;">${escapeHtml(event.timestamp)}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        // Failure info
        if (data.failure_reason) {
            html += `
                <div class="failure-box">
                    <div class="failure-box-title">
                        <i class="fa-solid fa-exclamation-circle"></i>
                        Delivery Failed
                    </div>
                    <div class="failure-box-content">
                        ${data.failure_reason ? `<strong>Reason:</strong> ${escapeHtml(data.failure_reason)}<br>` : ''}
                        ${data.failure_code ? `<strong>Code:</strong> ${escapeHtml(data.failure_code)}` : ''}
                    </div>
                </div>
            `;
        }
        
        // Retry history
        if (data.retry_history && data.retry_history.length > 0) {
            html += `
                <div class="modal-section">
                    <div class="modal-section-title"><i class="fa-solid fa-redo"></i> Retry History</div>
                    ${data.retry_history.map(retry => `
                        <div style="padding: 8px; background: #f8fafb; border-radius: 6px; margin-bottom: 8px; font-size: 11px;">
                            <strong>Attempt ${retry.attempt}:</strong> ${escapeHtml(retry.status)} - ${escapeHtml(retry.reason || 'No reason')}
                            <br><span style="color: #8a95a5;">${escapeHtml(retry.timestamp)}</span>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        // Provider info
        if (data.provider) {
            html += `
                <div class="modal-section">
                    <div class="modal-section-title"><i class="fa-solid fa-server"></i> Provider Details</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="modal-label">Provider</div>
                            <div class="modal-value">${escapeHtml(data.provider)}</div>
                        </div>
                        ${data.provider_message_id ? `
                            <div class="modal-field">
                                <div class="modal-label">Message ID</div>
                                <div class="modal-value" style="font-family: monospace; font-size: 11px;">${escapeHtml(data.provider_message_id)}</div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }
        
        // Metadata
        html += `
            <div class="modal-section">
                <div class="modal-section-title">Metadata</div>
                <div class="modal-grid">
                    <div class="modal-field">
                        <div class="modal-label">Attempts</div>
                        <div class="modal-value">${data.delivery_attempts || 1}</div>
                    </div>
                    <div class="modal-field">
                        <div class="modal-label">Sent At</div>
                        <div class="modal-value">${escapeHtml(data.sent_at_formatted || '-')}</div>
                    </div>
                    ${data.delivered_at_formatted ? `
                        <div class="modal-field">
                            <div class="modal-label">Delivered At</div>
                            <div class="modal-value">${escapeHtml(data.delivered_at_formatted)}</div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        return html;
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Close modal when clicking outside
    document.getElementById('logModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeLogModal();
        }
    });

