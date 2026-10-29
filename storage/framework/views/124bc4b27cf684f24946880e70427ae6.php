<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Case Dossier Review · <?php echo e($incident->reference_number); ?></title>
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
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .body { padding: 23px; }
        
        /* Details Grid */
        .details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 35px; }
        .detail label { display: block; color: #8a95a5; font-size: 9px; font-weight: 750; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 5px; }
        .detail strong { color: #101a31; font-size: 13px; background: #f8fafb; padding: 10px 12px; border-radius: 8px; display: block; border: 1px solid #e4e9ee; }
        
        .description-box { background: #f8fafb; padding: 16px; border-radius: 9px; line-height: 1.65; font-size: 12px; color: #4e596c; margin-bottom: 1rem; border: 1px solid #e4e9ee; }
        
        .details-links { display: flex; gap: 9px; }
        .details-link { padding: 8px 14px; background: #079b6d; color: white; border-radius: 7px; font-size: 10px; font-weight: 650; cursor: pointer; border: none; transition: all 0.2s; display: flex; align-items: center; gap: 6px; }
        .details-link:hover { background: #067c58; transform: translateY(-1px); }
        
        .section-header { font-size: 11px; font-weight: 700; color: #079b6d; text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px; }
        
        .hod-report { background: #fffbeb; border-left: 3px solid #f59e0b; padding: 14px 16px; border-radius: 8px; color: #4e596c; line-height: 1.7; font-size: 12px; }
        
        /* Actions */
        .actions { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .buttons { display: flex; gap: 9px; }
        .btn { border-radius: 8px; padding: 10px 17px; font-size: 10px; font-weight: 700; border: 1px solid #d9e0e7; background: white; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #263149; display: flex; align-items: center; gap: 6px; }
        .btn:hover { background: #f8fafb; }
        .primary { background: #079b6d; color: white; border-color: #079b6d; box-shadow: 0 5px 13px rgba(7, 155, 109, .18); flex: 1; justify-content: center; }
        .primary:hover:not(:disabled) { background: #067c58; }
        .primary:disabled { opacity: 0.6; cursor: not-allowed; }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px); }
        .modal-content { background: white; border-radius: 14px; padding: 28px; max-width: 750px; width: 90%; max-height: 85vh; overflow-y: auto; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid #e1e7ee; }
        .modal-header h2 { font-size: 18px; font-weight: 750; color: #101a31; margin: 0; }
        .modal-close { background: #f5f7fa; border: 1px solid #e1e7ee; font-size: 18px; color: #718096; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: #eef2f7; }
        .modal-item { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 16px; margin-bottom: 14px; transition: all 0.2s; }
        .modal-item:hover { border-color: #079b6d; }
        .modal-item-title { font-weight: 700; color: #101a31; margin-bottom: 8px; font-size: 12px; display: flex; align-items: center; gap: 8px; }
        .modal-item-meta { font-size: 9px; color: #8a95a5; margin-bottom: 10px; }
        .modal-item-content { color: #4e596c; font-size: 11px; line-height: 1.7; background: white; padding: 12px; border-radius: 6px; border-left: 3px solid #079b6d; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
        .empty-state i { font-size: 36px; display: block; margin-bottom: 12px; color: #e4e9ef; }
        .modal-footer { margin-top: 20px; padding-top: 20px; border-top: 2px solid #e1e7ee; }
        .btn-close { background: #079b6d; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; width: 100%; transition: all 0.2s; }
        .btn-close:hover { background: #067c58; }
        
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loading { opacity: 0.7; pointer-events: none; }
        .loading i { animation: spin 1s linear infinite; }
        
        /* Print-only sections should be hidden on screen */
        .print-only { display: none !important; }
        
        /* Responsive */
        @media (max-width: 900px) { 
            .details { grid-template-columns: 1fr; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; }
            .details { grid-template-columns: 1fr; }
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
                <div class="icon"><i class="fa-solid fa-file-contract"></i></div>
                <div>
                    <h1 class="title">Case Dossier Review</h1>
                    <div class="sub">Final review before HR escalation</div>
                    <span class="case">CASE: <?php echo e($incident->reference_number); ?></span>
                </div>
            </div>
            <a href="<?php echo e(route('incidents.hod_report', ['incident' => $incident])); ?>" class="back"><i class="fa-solid fa-arrow-left"></i> &nbsp; Back</a>
        </div>

        <!-- Alert -->
        <div class="alert">
            <div class="alerticon"><i class="fa-solid fa-info-circle"></i></div>
            <div>
                <strong>Final Review:</strong>
                <p>Please review all information below. Once you submit to HR, this case will be escalated for official review and processing.</p>
            </div>
        </div>

        <!-- Incident Details Card -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-briefcase"></i> &nbsp; Incident Details</div>
            </div>
            <div class="body">
                <div class="details">
                    <div class="detail">
                        <label>Reference Number</label>
                        <strong><?php echo e($incident->reference_number); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Worker Name</label>
                        <strong><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></strong>
                    </div>
                    <div class="detail">
                        <label>ID Number</label>
                        <strong><?php echo e($incident->id_number ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Department</label>
                        <strong><?php echo e($incident->department->name ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Designation</label>
                        <strong><?php echo e($incident->designation->name ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Facility</label>
                        <strong><?php echo e($incident->facility->name ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>District</label>
                        <strong><?php echo e($incident->district->name ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Province</label>
                        <strong><?php echo e($incident->province->name ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Incident Date</label>
                        <strong><?php echo e($incident->incident_date->format('d M Y')); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Incident Time</label>
                        <strong><?php echo e($incident->incident_time ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Incident Type</label>
                        <strong><?php echo e($incident->incidentType->name ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Phone Number</label>
                        <strong><?php echo e($incident->phone_number ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Email Address</label>
                        <strong><?php echo e($incident->email ?? 'N/A'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Work Related</label>
                        <strong><?php echo e($incident->is_work_related ? 'Yes' : 'No'); ?></strong>
                    </div>
                    <div class="detail">
                        <label>Home Address</label>
                        <strong><?php echo e($incident->home_address ?? 'N/A'); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incident Description with Links -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-align-left"></i> &nbsp; Incident Description</div>
                <div class="details-links">
                    <button onclick="openFirstAidModal()" class="details-link">
                        <i class="fa-solid fa-heart-pulse"></i> First Aid (<?php echo e($firstAidRecords->count()); ?>)
                    </button>
                    <button onclick="openWitnessModal()" class="details-link">
                        <i class="fa-solid fa-comment-dots"></i> Witness (<?php echo e($witnessStatements->count()); ?>)
                    </button>
                </div>
            </div>
            <div class="body">
                <div class="description-box">
                    <?php echo e($incident->incident_description ?? 'No description provided'); ?>

                </div>
            </div>
        </div>

        <!-- Supervisor & HOD Report Combined Card -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-user-check"></i> &nbsp; Supervisor Assessment & HOD Report</div>
            </div>
            <div class="body">
                <!-- Supervisor Verification Section -->
                <div style="margin-bottom: 1.5rem;">
                    <div class="section-header">
                        <i class="fa-solid fa-shield-halved"></i> Supervisor Verification
                    </div>
                    <div class="details">
                        <div class="detail">
                            <label>Supervisor Name</label>
                            <strong><?php echo e($incident->supervisor_name ?? 'N/A'); ?></strong>
                        </div>
                        <div class="detail">
                            <label>Verified On</label>
                            <strong><?php echo e($incident->supervisor_verified_at ? $incident->supervisor_verified_at->format('d M Y H:i') : 'Not verified'); ?></strong>
                        </div>
                    </div>
                    <?php if($incident->supervisor_witness_statement): ?>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; color: #8a95a5; font-size: 9px; font-weight: 750; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 8px;">Supervisor Witness Statement</label>
                        <div class="description-box">
                            <?php echo e($incident->supervisor_witness_statement); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- HOD Report Section -->
                <div>
                    <div class="section-header">
                        <i class="fa-solid fa-file-lines"></i> Head of Department Report
                    </div>
                    <div class="hod-report">
                        <?php echo e($incident->hod_report); ?>

                    </div>
                    <div style="font-size: 10px; color: #8a95a5; margin-top: 12px; display: flex; gap: 16px;">
                        <span><i class="fa-solid fa-user"></i> <?php echo e($incident->submittedByUser->name ?? 'Unknown'); ?></span>
                        <span><i class="fa-solid fa-calendar"></i> <?php echo e($incident->hod_report_submitted_at->format('d M Y H:i')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <a href="<?php echo e(route('incidents.hod_report', ['incident' => $incident])); ?>" class="btn">
                <i class="fa-solid fa-pen"></i> Edit Report
            </a>
            <button id="submitBtn" onclick="submitToHR()" class="btn primary">
                <i class="fa-solid fa-paper-plane" id="submitBtnIcon"></i> 
                <span id="submitBtnText">Submit & Escalate to HR</span>
            </button>
        </div>
    </div>

    <!-- First Aid Records Modal -->
    <div id="firstAidModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fa-solid fa-heart-pulse"></i> First Aid Records</h2>
                <button onclick="closeFirstAidModal()" class="modal-close">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <div id="firstAidModalContent">
                <?php if($firstAidRecords->count() > 0): ?>
                    <?php $__currentLoopData = $firstAidRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="modal-item">
                        <div class="modal-item-title">
                            <i class="fa-solid fa-hospital"></i> First Aid Record <?php echo e($index + 1); ?>

                        </div>
                        <div class="modal-item-meta">
                            <i class="fa-solid fa-calendar"></i> <?php echo e($record->created_at->format('d M Y H:i')); ?>

                        </div>
                        <div class="modal-item-content">
                            <strong>Nurse Name:</strong> <?php echo e($record->nurse_name ?? 'N/A'); ?><br>
                            <strong>Nurse License Number:</strong> <?php echo e($record->nurse_license_number ?? 'N/A'); ?><br>
                            <strong>Location:</strong> <?php echo e($record->location ?? 'N/A'); ?><br>
                            <strong>Time Administered:</strong> <?php echo e($record->time_administered ? $record->time_administered->format('d M Y H:i') : 'N/A'); ?><br>
                            <strong>Severity Level:</strong> <?php echo e($record->severity_level ?? 'N/A'); ?><br>
                            <strong>Status:</strong> <?php echo e(ucfirst($record->status ?? 'N/A')); ?><br><br>
                            <strong>Injury Description:</strong><br>
                            <?php echo e($record->injury_description ?? 'N/A'); ?><br><br>
                            <strong>First Aid Treatment Provided:</strong><br>
                            <?php echo e($record->first_aid_description ?? 'N/A'); ?><br>
                            <?php if($record->notes): ?>
                            <br><strong>Additional Notes:</strong><br>
                            <?php echo e($record->notes); ?><br>
                            <?php endif; ?>
                            <?php if($record->reviewed_at): ?>
                            <br><strong>Reviewed By:</strong> <?php echo e($record->reviewer->name ?? 'N/A'); ?><br>
                            <strong>Reviewed On:</strong> <?php echo e($record->reviewed_at->format('d M Y H:i')); ?><br>
                            <?php if($record->review_comments): ?>
                            <strong>Review Comments:</strong><br>
                            <?php echo e($record->review_comments); ?>

                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <p>No first aid records available.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <button onclick="closeFirstAidModal()" class="btn-close">
                    <i class="fa-solid fa-check"></i> Close
                </button>
            </div>
        </div>
    </div>

    <!-- Witness Statements Modal -->
    <div id="witnessModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fa-solid fa-comment-dots"></i> Witness Statements</h2>
                <button onclick="closeWitnessModal()" class="modal-close">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <div id="witnessModalContent">
                <?php if($witnessStatements->count() > 0): ?>
                    <?php $__currentLoopData = $witnessStatements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $witness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="modal-item">
                        <div class="modal-item-title">
                            <i class="fa-solid fa-user-circle"></i> Witness <?php echo e($index + 1); ?>: <?php echo e($witness->witness_name ?? 'Pending Name'); ?>

                        </div>
                        <div class="modal-item-meta">
                            <i class="fa-solid fa-phone"></i> <?php echo e($witness->phone_number ?? 'N/A'); ?> | 
                            <i class="fa-solid fa-calendar"></i> <?php echo e($witness->statement_submitted_at ? $witness->statement_submitted_at->format('d M Y H:i') : 'Not submitted'); ?>

                        </div>
                        <div class="modal-item-content">
                            <?php echo e(trim($witness->statement) ?? 'No statement provided'); ?>

                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <p>No witness statements available.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <button onclick="closeWitnessModal()" class="btn-close">
                    <i class="fa-solid fa-check"></i> Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openFirstAidModal() {
            document.getElementById('firstAidModal').style.display = 'flex';
        }

        function closeFirstAidModal() {
            document.getElementById('firstAidModal').style.display = 'none';
        }

        function openWitnessModal() {
            document.getElementById('witnessModal').style.display = 'flex';
        }

        function closeWitnessModal() {
            document.getElementById('witnessModal').style.display = 'none';
        }

        // Close modals when clicking outside
        document.getElementById('firstAidModal').addEventListener('click', function(e) {
            if (e.target === this) closeFirstAidModal();
        });

        document.getElementById('witnessModal').addEventListener('click', function(e) {
            if (e.target === this) closeWitnessModal();
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFirstAidModal();
                closeWitnessModal();
            }
        });

        function submitToHR() {
            const btn = document.getElementById('submitBtn');
            const icon = document.getElementById('submitBtnIcon');
            const text = document.getElementById('submitBtnText');
            
            btn.classList.add('loading');
            btn.disabled = true;
            icon.className = 'fa-solid fa-spinner';
            text.textContent = 'Submitting...';

            fetch('<?php echo e(route("incidents.submit_dossier_to_hr", ["incident" => $incident])); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Response Error:', {status: response.status, body: text});
                        throw new Error(`Server Error (${response.status}): ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessModal(data.message);
                } else {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                    icon.className = 'fa-solid fa-paper-plane';
                    text.textContent = 'Submit & Escalate to HR';
                    console.error('API Error:', data);
                    showErrorModal(data.message || 'Failed to submit case');
                }
            })
            .catch(error => {
                btn.classList.remove('loading');
                btn.disabled = false;
                icon.className = 'fa-solid fa-paper-plane';
                text.textContent = 'Submit & Escalate to HR';
                console.error('Full Error:', error);
                showErrorModal(error.message || 'An error occurred. Please try again.');
            });
        }

        function showErrorModal(message) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2000;
                backdrop-filter: blur(4px);
            `;
            modal.innerHTML = `
                <div style="
                    background: white;
                    border-radius: 14px;
                    box-shadow: 0 25px 80px rgba(0,0,0,0.4);
                    padding: 32px;
                    max-width: 500px;
                    width: 90%;
                    text-align: center;
                    border: 2px solid #e1e7ee;
                ">
                    <div style="margin-bottom: 20px;">
                        <i class="fa-solid fa-exclamation-circle" style="font-size: 48px; color: #ef4444;"></i>
                    </div>
                    <h2 style="font-size: 20px; font-weight: 750; color: #101a31; margin-bottom: 16px; margin-top: 0;">Error</h2>
                    <p style="
                        color: #4e596c;
                        margin: 0 0 24px 0;
                        background: #fef2f2;
                        padding: 16px;
                        border-radius: 10px;
                        border: 1px solid #fecaca;
                        max-height: 150px;
                        overflow-y: auto;
                        font-size: 12px;
                        line-height: 1.6;
                        text-align: left;
                    ">${message}</p>
                    <button onclick="this.closest('div').parentElement.remove()" style="
                        background: #ef4444;
                        color: white;
                        padding: 12px 24px;
                        border: none;
                        border-radius: 8px;
                        font-size: 12px;
                        font-weight: 700;
                        cursor: pointer;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                        <i class="fa-solid fa-times"></i> Close
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function showSuccessModal(message) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2000;
                backdrop-filter: blur(4px);
            `;
            modal.innerHTML = `
                <div style="
                    background: white;
                    border-radius: 14px;
                    box-shadow: 0 25px 80px rgba(0,0,0,0.4);
                    padding: 40px;
                    max-width: 500px;
                    width: 90%;
                    text-align: center;
                    border: 2px solid #e1e7ee;
                ">
                    <div style="margin-bottom: 20px;">
                        <i class="fa-solid fa-check-circle" style="font-size: 56px; color: #22c55e;"></i>
                    </div>
                    <h2 style="font-size: 24px; font-weight: 750; color: #101a31; margin: 0 0 12px 0;">Success!</h2>
                    <p style="
                        color: #4e596c;
                        margin: 0 0 28px 0;
                        font-size: 13px;
                        line-height: 1.6;
                    ">${message}</p>
                    <div style="
                        background: #f0fdf4;
                        border: 1px solid #a7f3d0;
                        border-radius: 10px;
                        padding: 20px;
                        margin-bottom: 24px;
                    ">
                        <p style="color: #4b7c59; font-size: 11px; margin: 0 0 8px 0; font-weight: 600;">Redirecting to dashboard in</p>
                        <p style="font-size: 36px; font-weight: 750; color: #079b6d; margin: 0;" id="countdown">5</p>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            let count = 5;
            const countdownEl = document.getElementById('countdown');
            
            const interval = setInterval(() => {
                count--;
                countdownEl.textContent = count;
                
                if (count <= 0) {
                    clearInterval(interval);
                    window.location.href = '<?php echo e(route("dashboards.supervisor")); ?>';
                }
            }, 1000);
        }
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\supervisor_dossier_review.blade.php ENDPATH**/ ?>