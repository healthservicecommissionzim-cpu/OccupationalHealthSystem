<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Witness Statements · Supervisor</title>
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
        .alert { display: flex; gap: 14px; padding: 17px 20px; background: #fff8e7; border: 1px solid #f6d48b; border-left: 4px solid #f59e0b; border-radius: 10px; margin-bottom: 22px; }
        .alerticon { width: 34px; height: 34px; border-radius: 9px; background: #fff0c9; color: #c67a00; display: grid; place-items: center; flex-shrink: 0; }
        .alert strong { display: block; color: #624300; font-size: 12px; margin-bottom: 4px; }
        .alert p { margin: 0; color: #7d6332; font-size: 11px; line-height: 1.6; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .body { padding: 23px; }
        
        /* Summary Cards */
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 22px; }
        .summary-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; }
        .summary-card.total { border-left: 3px solid #079b6d; }
        .summary-card.submitted { border-left: 3px solid #22c55e; }
        .summary-card.pending { border-left: 3px solid #f59e0b; }
        .summary-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .summary-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Table */
        .witness-table { width: 100%; border-collapse: collapse; }
        .witness-table th { background: #f8fafb; padding: 12px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; }
        .witness-table td { padding: 16px 12px; border-bottom: 1px solid #e4e9ee; vertical-align: middle; font-size: 11px; }
        .witness-name { font-weight: 700; color: #101a31; font-size: 12px; }
        .witness-phone { color: #8a95a5; font-size: 10px; margin-top: 2px; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-badge.pending { background: #fff8e7; color: #c67a00; }
        .status-badge.submitted { background: #e9f8f3; color: #067c58; }
        .timestamp { font-size: 10px; color: #8a95a5; }
        .btn-resend { background: #079b6d; color: white; border: none; padding: 7px 14px; border-radius: 7px; font-size: 10px; font-weight: 650; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s; }
        .btn-resend:hover:not(:disabled) { background: #067c58; transform: translateY(-1px); }
        .btn-resend:disabled { background: #e4e9ee; color: #8a95a5; cursor: not-allowed; transform: none; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
        .empty-state i { font-size: 36px; display: block; margin-bottom: 12px; color: #e4e9ef; }
        
        /* Actions */
        .actions { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .buttons { display: flex; gap: 9px; }
        .btn { border-radius: 8px; padding: 10px 17px; font-size: 10px; font-weight: 700; border: 1px solid #d9e0e7; background: white; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #263149; display: flex; align-items: center; gap: 6px; }
        .btn:hover { background: #f8fafb; }
        .primary { background: #079b6d; color: white; border-color: #079b6d; box-shadow: 0 5px 13px rgba(7, 155, 109, .18); }
        .primary:hover { background: #067c58; }
        
        /* Toast */
        .toast { position: fixed; top: 2rem; right: 2rem; background: white; padding: 14px 18px; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); display: none; align-items: center; gap: 10px; z-index: 1000; animation: slideIn 0.3s ease; border: 1px solid #e1e7ee; }
        .toast.active { display: flex; }
        .toast.success { border-left: 3px solid #22c55e; }
        .toast.error { border-left: 3px solid #ef4444; }
        .toast-icon { font-size: 18px; }
        .toast-icon.success { color: #22c55e; }
        .toast-icon.error { color: #ef4444; }
        .toast-message { font-size: 11px; color: #4e596c; font-weight: 600; }
        @keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        /* Responsive */
        @media (max-width: 900px) { 
            .summary-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; }
            .witness-table { display: block; }
            .witness-table thead { display: none; }
            .witness-table tbody { display: block; }
            .witness-table tr { display: block; margin-bottom: 1rem; background: #f8fafb; padding: 14px; border-radius: 10px; border: 1px solid #e4e9ee; }
            .witness-table td { display: block; padding: 8px 0; border: none; text-align: left !important; }
            .witness-table td:before { content: attr(data-label); font-weight: 750; color: #8a95a5; font-size: 9px; text-transform: uppercase; display: block; margin-bottom: 4px; }
            .btn-resend { width: 100%; justify-content: center; margin-top: 8px; }
            .actions { flex-direction: column; }
            .buttons { width: 100%; flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h1 class="title">Witness Statements</h1>
                    <div class="sub">Monitor and manage witness statement submissions</div>
                    <span class="case">CASE: {{ $incident->reference_number }}</span>
                </div>
            </div>
            <a href="{{ route('dashboards.supervisor') }}" class="back"><i class="fa-solid fa-arrow-left"></i> &nbsp; Back to Dashboard</a>
        </div>

        <!-- Alert -->
        <div class="alert">
            <div class="alerticon"><i class="fa-solid fa-info-circle"></i></div>
            <div>
                <strong>Witness Statement Monitoring</strong>
                <p>Track witness statement submissions and send reminder SMS to witnesses who haven't submitted yet.</p>
            </div>
        </div>

        <!-- Witnesses Table Card -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-list"></i> &nbsp; Witness List</div>
            </div>
            <div class="body">
                @if($witnessTokens->count() > 0)
                <table class="witness-table">
                    <thead>
                        <tr>
                            <th>Witness Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Submission Date</th>
                            <th>Last Reminder</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($witnessTokens as $token)
                        <tr>
                            <td data-label="Witness Name">
                                <div class="witness-name">{{ $token->witness_name }}</div>
                            </td>
                            <td data-label="Phone Number">
                                <div class="witness-phone">{{ $token->phone_number }}</div>
                            </td>
                            <td data-label="Email">
                                @if($token->email)
                                    <div class="witness-phone">{{ $token->email }}</div>
                                @else
                                    <button onclick="showAddEmailModal({{ $token->id }}, '{{ $token->witness_name }}')" style="background: #e9f8f3; color: #079b6d; border: 1px solid #79dbb7; padding: 4px 10px; border-radius: 5px; font-size: 9px; font-weight: 650; cursor: pointer;">
                                        <i class="fa-solid fa-plus"></i> Add Email
                                    </button>
                                @endif
                            </td>
                            <td data-label="Status">
                                @if($token->statement_submitted_at)
                                    <span class="status-badge submitted">
                                        <i class="fa-solid fa-check-circle"></i> Submitted
                                    </span>
                                @else
                                    <span class="status-badge pending">
                                        <i class="fa-solid fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td data-label="Submission Date">
                                @if($token->statement_submitted_at)
                                    <div class="timestamp">{{ $token->statement_submitted_at->format('d M Y, H:i') }}</div>
                                @else
                                    <div class="timestamp">—</div>
                                @endif
                            </td>
                            <td data-label="Last Reminder">
                                @if($token->witness_last_reminder_sent_at)
                                    <div class="timestamp">{{ $token->witness_last_reminder_sent_at->format('d M Y, H:i') }}</div>
                                @else
                                    <div class="timestamp">—</div>
                                @endif
                            </td>
                            <td data-label="Actions">
                                @if(!$token->statement_submitted_at)
                                    <button class="btn-resend" onclick="showContactModal({{ $token->id }}, '{{ $token->witness_name }}', '{{ $token->email ?? '' }}', '{{ $token->phone_number }}', '{{ $token->token }}')">
                                        <i class="fa-solid fa-phone"></i> Contact
                                    </button>
                                @else
                                    <button class="btn-resend" disabled>
                                        <i class="fa-solid fa-check"></i> Completed
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fa-solid fa-users-slash"></i>
                    <p style="font-size: 12px; color: #8a95a5;">No witnesses were added for this incident.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('dashboards.supervisor') }}" class="btn">
                <i class="fa-solid fa-times"></i> Cancel
            </a>
            <a href="{{ route('incidents.hod_report', ['incident' => $incident]) }}" class="btn primary">
                Proceed<i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fa-solid fa-check-circle toast-icon" id="toastIcon"></i>
        <div class="toast-message" id="toastMessage"></div>
    </div>

    <script>
        const incidentId = {{ $incident->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');
            
            toast.className = `toast ${type} active`;
            toastIcon.className = `fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} toast-icon ${type}`;
            toastMessage.textContent = message;
            
            setTimeout(() => {
                toast.classList.remove('active');
            }, 3000);
        }

        function showContactModal(tokenId, witnessName, witnessEmail, witnessPhone, witnessToken) {
            const modal = `
                <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;" id="contactModal">
                    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 450px; width: 95%;">
                        <h3 style="margin: 0 0 8px; font-size: 18px; color: #101a31; font-weight: 700;">Contact Witness</h3>
                        <p style="margin: 0 0 20px; font-size: 12px; color: #718096;">${witnessName} • ${witnessPhone}</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <!-- Email Option - ALWAYS SHOW -->
                            <button onclick="showEmailInputModal(${tokenId}, '${witnessName.replace(/'/g, "\\'")}', '${witnessEmail}')" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: linear-gradient(135deg, #0B5ED7 0%, #0A58CA 100%); color: white; border: 1px solid #0A58CA; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(11, 94, 215, 0.3);">
                                <i class="fa-solid fa-envelope" style="font-size: 28px;"></i>
                                <div style="text-align: left; flex: 1;">
                                    <div style="font-weight: 700; margin-bottom: 2px;">Send via Email</div>
                                    <div style="font-size: 11px; opacity: 0.9;">${witnessEmail || 'Enter email address'}</div>
                                </div>
                                <i class="fa-solid fa-chevron-right" style="font-size: 16px; opacity: 0.7;"></i>
                            </button>
                            
                            <!-- SMS Option -->
                            <button onclick="confirmSendSMS(${tokenId}, '${witnessName.replace(/'/g, "\\'")}', '${witnessPhone}')" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); color: white; border: 1px solid #4F46E5; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);">
                                <i class="fa-solid fa-message" style="font-size: 28px;"></i>
                                <div style="text-align: left; flex: 1;">
                                    <div style="font-weight: 700; margin-bottom: 2px;">Send via SMS</div>
                                    <div style="font-size: 11px; opacity: 0.9;">Text message reminder</div>
                                </div>
                                <i class="fa-solid fa-chevron-right" style="font-size: 16px; opacity: 0.7;"></i>
                            </button>
                            
                            <!-- Close Button -->
                            <button onclick="closeContactModal()" style="margin-top: 8px; padding: 12px; background: #f8fafb; color: #263149; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                <i class="fa-solid fa-times" style="margin-right: 6px;"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
        }
        
        function confirmSendEmail(tokenId, witnessName, witnessEmail) {
            // This function is no longer needed, we go straight to email input modal
            // But keeping for backwards compatibility
            showEmailInputModal(tokenId, witnessName, witnessEmail);
        }
        
        function confirmSendSMS(tokenId, witnessName, witnessPhone) {
            // Close main modal
            const mainModal = document.getElementById('contactModal');
            if (mainModal) mainModal.remove();
            
            // Show confirmation modal
            const confirmModal = `
                <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 10000;" id="confirmModal">
                    <div style="background: white; border-radius: 12px; padding: 28px; max-width: 420px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i class="fa-solid fa-message" style="font-size: 24px; color: white;"></i>
                            </div>
                            <h3 style="margin: 0 0 8px; font-size: 18px; color: #101a31; font-weight: 700;">Send SMS Reminder?</h3>
                            <p style="margin: 0; font-size: 13px; color: #718096; line-height: 1.6;">An SMS will be sent to <strong>${witnessName}</strong> at <strong>${witnessPhone}</strong> with the witness statement link.</p>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button onclick="closeConfirmModal()" style="flex: 1; padding: 12px; background: #f8fafb; color: #263149; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                Cancel
                            </button>
                            <button onclick="sendViaSMS(${tokenId}, '${witnessName.replace(/'/g, "\\'")}'); closeConfirmModal();" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); color: white; border: 1px solid #4F46E5; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);">
                                <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Send SMS
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', confirmModal);
        }
        
        async function sendViaSMS(tokenId, witnessName) {
            try {
                const response = await fetch(`/supervisor/incidents/${incidentId}/witness/${tokenId}/reminder`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showToast(`SMS sent successfully to ${witnessName}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Failed to send SMS', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while sending SMS', 'error');
            }
        }
        
        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            if (modal) modal.remove();
        }
        
        function closeContactModal() {
            const modal = document.getElementById('contactModal');
            if (modal) modal.remove();
        }
        
        // Success Modal - Green centered modal that auto-closes after 2 seconds
        function showSuccessModal(message) {
            const modal = `
                <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 99999;" id="successModal">
                    <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 16px; padding: 32px 40px; max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(16, 185, 129, 0.4); text-align: center; animation: scaleIn 0.3s ease;">
                        <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; backdrop-filter: blur(10px);">
                            <i class="fa-solid fa-check" style="font-size: 32px; color: white;"></i>
                        </div>
                        <h3 style="margin: 0 0 8px; font-size: 20px; color: white; font-weight: 700;">Success!</h3>
                        <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.95); line-height: 1.5;">${message}</p>
                    </div>
                </div>
                <style>
                    @keyframes scaleIn {
                        from { transform: scale(0.9); opacity: 0; }
                        to { transform: scale(1); opacity: 1; }
                    }
                </style>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
            
            // Auto-close after 2 seconds
            setTimeout(() => {
                const modalEl = document.getElementById('successModal');
                if (modalEl) {
                    modalEl.style.opacity = '0';
                    modalEl.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        modalEl.remove();
                        location.reload();
                    }, 300);
                }
            }, 2000);
        }
        
        // Email Input Modal - Collects email and sends reminder
        function showEmailInputModal(tokenId, witnessName, currentEmail) {
            // Close contact modal first
            const contactModal = document.getElementById('contactModal');
            if (contactModal) contactModal.remove();
            
            const modal = `
                <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 10000;" id="emailInputModal">
                    <div style="background: white; border-radius: 12px; padding: 28px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0B5ED7 0%, #0A58CA 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i class="fa-solid fa-envelope" style="font-size: 24px; color: white;"></i>
                            </div>
                            <h3 style="margin: 0 0 8px; font-size: 18px; color: #101a31; font-weight: 700;">Send Email Reminder</h3>
                            <p style="margin: 0 0 20px; font-size: 13px; color: #718096;">Enter email address for <strong>${witnessName}</strong></p>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <input type="email" id="witnessEmailInput" value="${currentEmail || ''}" placeholder="witness@example.com" style="width: 100%; padding: 12px; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 14px;" />
                            <div id="emailInputError" style="display: none; color: #ef4444; font-size: 11px; margin-top: 6px;">
                                <i class="fa-solid fa-exclamation-circle"></i> Please enter a valid email address
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button onclick="closeEmailInputModal()" style="flex: 1; padding: 12px; background: #f8fafb; color: #263149; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                Cancel
                            </button>
                            <button onclick="sendEmailWithInput(${tokenId}, '${witnessName.replace(/'/g, "\\'")}');" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #0B5ED7 0%, #0A58CA 100%); color: white; border: 1px solid #0A58CA; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(11, 94, 215, 0.3);">
                                <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Send Email
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
            // Focus on email input
            setTimeout(() => document.getElementById('witnessEmailInput').focus(), 100);
        }
        
        function closeEmailInputModal() {
            const modal = document.getElementById('emailInputModal');
            if (modal) modal.remove();
        }
        
        // Send email with input validation
        async function sendEmailWithInput(tokenId, witnessName) {
            const emailInput = document.getElementById('witnessEmailInput');
            const emailError = document.getElementById('emailInputError');
            const email = emailInput.value.trim();
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                emailError.style.display = 'block';
                emailInput.style.borderColor = '#ef4444';
                return;
            }
            
            try {
                // First save the email if needed
                const saveResponse = await fetch(`/supervisor/incidents/${incidentId}/witness/${tokenId}/add-email`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const saveData = await saveResponse.json();
                
                if (!saveData.success) {
                    emailError.textContent = saveData.message || 'Failed to save email';
                    emailError.style.display = 'block';
                    return;
                }
                
                // Now send the email reminder
                const sendResponse = await fetch(`/supervisor/incidents/${incidentId}/witness/${tokenId}/email-reminder`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                const sendData = await sendResponse.json();
                
                // Close the email input modal
                closeEmailInputModal();
                
                if (sendData.success) {
                    // Show success modal
                    showSuccessModal(`Email sent successfully to ${witnessName}`);
                } else {
                    showToast(sendData.message || 'Failed to send email', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                closeEmailInputModal();
                showToast('An error occurred while sending email', 'error');
            }
        }
        
        function showAddEmailModal(tokenId, witnessName) {
            const modal = `
                <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 10000;" id="addEmailModal">
                    <div style="background: white; border-radius: 12px; padding: 28px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0B5ED7 0%, #0A58CA 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i class="fa-solid fa-envelope" style="font-size: 24px; color: white;"></i>
                            </div>
                            <h3 style="margin: 0 0 8px; font-size: 18px; color: #101a31; font-weight: 700;">Add Email Address</h3>
                            <p style="margin: 0 0 20px; font-size: 13px; color: #718096;">Add email address for <strong>${witnessName}</strong></p>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <input type="email" id="witnessEmail" placeholder="witness@example.com" style="width: 100%; padding: 12px; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 14px;" />
                            <div id="emailError" style="display: none; color: #ef4444; font-size: 11px; margin-top: 6px;">
                                <i class="fa-solid fa-exclamation-circle"></i> Please enter a valid email address
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button onclick="closeAddEmailModal()" style="flex: 1; padding: 12px; background: #f8fafb; color: #263149; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                Cancel
                            </button>
                            <button onclick="saveWitnessEmail(${tokenId})" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #0B5ED7 0%, #0A58CA 100%); color: white; border: 1px solid #0A58CA; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(11, 94, 215, 0.3);">
                                <i class="fa-solid fa-save" style="margin-right: 6px;"></i> Save Email
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
            // Focus on email input
            setTimeout(() => document.getElementById('witnessEmail').focus(), 100);
        }
        
        function closeAddEmailModal() {
            const modal = document.getElementById('addEmailModal');
            if (modal) modal.remove();
        }
        
        async function saveWitnessEmail(tokenId) {
            const emailInput = document.getElementById('witnessEmail');
            const emailError = document.getElementById('emailError');
            const email = emailInput.value.trim();
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                emailError.style.display = 'block';
                emailInput.style.borderColor = '#ef4444';
                return;
            }
            
            try {
                const response = await fetch(`/supervisor/incidents/${incidentId}/witness/${tokenId}/add-email`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();
                
                if (data.success) {
                    showToast('Email address added successfully', 'success');
                    closeAddEmailModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    emailError.textContent = data.message || 'Failed to save email';
                    emailError.style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while saving email', 'error');
            }
        }
    </script>
</body>
</html>
