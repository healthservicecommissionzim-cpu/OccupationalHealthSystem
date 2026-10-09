<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leave Information · HR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Inter, 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            padding: 2rem;
            min-height: 100vh;
            color: #263149;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* header */
        .header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
        }
        .header-left { display: flex; gap: 1.5rem; align-items: flex-start; }
        .header-icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; flex-shrink: 0; }
        .header-left h1 {
            font-size: 24px;
            font-weight: 750;
            color: #101a31;
            margin: 0;
        }
        .header-left p {
            font-size: 13px;
            color: #718096;
            margin-top: 5px;
        }
        .header-left .case {
            display: inline-block;
            margin-top: 9px;
            padding: 5px 10px;
            border-radius: 6px;
            background: #eef2f6;
            color: #5d687b;
            font-size: 11px;
            font-weight: 700;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 17px;
            background: white;
            color: #101a31;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 650;
            border: 1px solid #ccd6df;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: #f8fafb;
        }

        /* card */
        .card {
            background: white;
            border: 1px solid #e1e7ee;
            border-radius: 14px;
            border-top: 3px solid #079b6d;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(16,26,49,.06);
        }
        .card-accent {
            display: none;
        }
        .card h3 {
            font-size: 12px;
            font-weight: 750;
            color: #101a31;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 9px;
            text-transform: uppercase;
            letter-spacing: .55px;
        }
        .card h3 i { color: #079b6d; font-size: 14px; }

        /* alert */
        .alert {
            padding: 18px 22px;
            border-radius: 10px;
            border-left: 4px solid;
            margin-bottom: 1rem;
            display: flex;
            gap: 14px;
        }
        .alert-info {
            background: #eff6ff;
            border-color: #3b82f6;
        }
        .alert-info strong { color: #1e40af; font-size: 12.5px; font-weight: 700; }
        .alert-info p { color: #1e3a8a; font-size: 11.5px; }
        .alert-warning {
            background: #fff8e7;
            border-color: #f59e0b;
        }
        .alert-warning strong { color: #624300; font-size: 12.5px; font-weight: 700; }
        .alert-warning p { color: #7d6332; font-size: 11.5px; }
        .alert-success {
            background: #dcfce7;
            border-color: #22c55e;
        }
        .alert-success strong { color: #065f46; font-size: 12.5px; font-weight: 700; }
        .alert-success p { color: #065f46; font-size: 11.5px; }
        .alerticon { width: 36px; height: 36px; border-radius: 9px; display: grid; place-items: center; flex-shrink: 0; font-size: 16px; }
        .alert-info .alerticon { background: #dbeafe; color: #1e40af; }
        .alert-warning .alerticon { background: #fff0c9; color: #c67a00; }
        .alert-success .alerticon { background: #d1fae5; color: #065f46; }
        .alert strong { display: block; margin-bottom: 6px; }
        .alert p { margin: 0; }

        /* form */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #8a95a5;
            text-transform: uppercase;
            letter-spacing: .7px;
        }
        .form-group input[type="date"] {
            max-width: 280px;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            font-family: Inter, 'Segoe UI', sans-serif;
            background: #fcfdfe;
            color: #263149;
            transition: all 0.2s;
        }
        .form-group input[type="date"]:focus {
            outline: none;
            border-color: #079b6d;
            box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.12);
            background: white;
        }
        .form-group small {
            font-size: 10px;
            color: #8a95a5;
            display: block;
            margin-top: 5px;
            line-height: 1.4;
        }

        /* checkbox */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            color: #1e3a4b;
            padding: 4px 0;
        }
        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #079b6d;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.1s;
        }
        .checkbox-label span { flex: 1; }

        /* stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e2eaf1;
        }
        .stat-box {
            background: white;
            padding: 14px 12px;
            border-radius: 10px;
            transition: all 0.2s;
            border: 1px solid #e0efe7;
        }
        .stat-box.alt { background: white; border-color: #dae9f5; }
        .stat-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: #079b6d;
            margin-top: 6px;
            line-height: 1.2;
        }
        .stat-box.alt .stat-value { color: #079b6d; }

        /* badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-orange { background: #fed7aa; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        /* milestones */
        .milestone-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #fffbf0;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
            border-left: 4px solid #f59e0b;
            transition: background 0.2s;
        }
        .milestone-item i {
            color: #d97706;
            margin-top: 2px;
            font-size: 1rem;
        }
        .milestone-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1f2e3a;
        }
        .milestone-date {
            font-size: 0.68rem;
            color: #5c6f7e;
            margin-top: 3px;
        }

        /* actions */
        .actions {
            display: flex;
            gap: 12px;
            padding-top: 1.5rem;
            border-top: 1px solid #e2eaf1;
            margin-top: 0.5rem;
        }
        .btn {
            padding: 10px 16px;
            border-radius: 12px;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.2px;
        }
        .btn-primary {
            background: #079b6d;
            color: white;
            flex: 1;
            box-shadow: 0 4px 10px rgba(7, 155, 109, 0.2);
        }
        .btn-primary:hover:not(:disabled) {
            background: #067c58;
            box-shadow: 0 8px 18px rgba(7, 155, 109, 0.3);
            transform: translateY(-1px);
        }
        .btn-primary:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        .btn-secondary {
            background: white;
            color: #406274;
            border: 1.5px solid #dbe6ed;
            padding: 10px 18px;
        }
        .btn-secondary:hover {
            background: #f6fafd;
            border-color: #b6cfdd;
            color: #1d3e52;
        }

        /* tooltip */
        .tooltip { position: relative; display: inline-block; width: 100%; }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 210px;
            background-color: #1b3e2e;
            color: white;
            text-align: center;
            border-radius: 10px;
            padding: 10px 12px;
            position: absolute;
            z-index: 10;
            bottom: 135%;
            left: 50%;
            margin-left: -105px;
            opacity: 0;
            transition: opacity 0.2s;
            font-size: 0.7rem;
            font-weight: 500;
            line-height: 1.4;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.25);
            letter-spacing: 0.2px;
            pointer-events: none;
        }
        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -6px;
            border-width: 6px;
            border-style: solid;
            border-color: #1b3e2e transparent transparent transparent;
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* loading */
        #loadingState {
            text-align: center;
            padding: 20px;
            background: #f8fcfa;
            border-radius: 16px;
            margin-top: 12px;
        }
        #loadingState i { font-size: 1.8rem; color: #079b6d; }
        #loadingState p { font-size: 0.75rem; color: #54707e; margin-top: 6px; font-weight: 500; }

        /* hidden */
        .hidden { display: none !important; }

        /* success modal */
        .success-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(10, 25, 20, 0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .success-modal.show { display: flex; }
        .success-modal-content {
            background: white;
            border-radius: 28px;
            padding: 2rem 1.8rem;
            text-align: center;
            max-width: 360px;
            width: 100%;
            box-shadow: 0 30px 50px -15px rgba(0, 0, 0, 0.4);
            animation: modalPop 0.35s cubic-bezier(0.21, 1.11, 0.43, 1);
        }
        .success-modal-icon {
            width: 70px;
            height: 70px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 2.2rem;
            color: #065f46;
        }
        .success-modal-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #101a31;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }
        .success-modal-message {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .success-modal-button {
            background: #079b6d;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            letter-spacing: 0.2px;
        }
        .success-modal-button:hover {
            background: #067c58;
            transform: scale(1.02);
            box-shadow: 0 8px 18px rgba(7, 155, 109, 0.3);
        }

        @keyframes modalPop {
            from { transform: scale(0.92); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* responsive */
        @media (max-width: 600px) {
            body { padding: 1rem 0.75rem; }
            .card { padding: 1.25rem 1rem 1.5rem; border-radius: 18px; }
            .header-left h1 { font-size: 1.2rem; }
            .stats-grid { grid-template-columns: 1fr; gap: 10px; }
            .actions { flex-direction: column-reverse; }
            .btn-secondary { width: 100%; justify-content: center; }
            .tooltip .tooltiptext { width: 180px; margin-left: -90px; bottom: 130%; }
            .badge { white-space: normal; }
            .form-group input[type="date"] { font-size: 0.8rem; padding: 9px 12px; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-accent"></div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <h3 style="margin-bottom: 0;"><i class="fa-regular fa-pen-to-square"></i> Leave Information Form</h3>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="display: inline-block; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700;">CASE: <?php echo e($incident->reference_number); ?></span>
                    <a href="<?php echo e(route('dashboards.hr')); ?>" class="back-btn" style="margin: 0; padding: 8px 14px; font-size: 11px;">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="alert alert-info">
                <strong><i class="fa-regular fa-circle-info" style="margin-right: 6px;"></i>Sick Leave Management</strong>
                <p>Capture leave information to track sick leave duration and ensure compliance with HSB Circular No. 3 of 2017 payment regulations (Full Pay: 0-90 days, Half Pay: 91-180 days with medical certificate).</p>
            </div>

            <form id="leaveInformationForm">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="sick_leave_start_date">
                        Sick Leave Start Date <span style="color: #dc2626;">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="sick_leave_start_date" 
                        name="sick_leave_start_date"
                        value="<?php echo e($incident->sick_leave_start_date?->format('Y-m-d')); ?>"
                        min="<?php echo e($incident->incident_date?->format('Y-m-d')); ?>"
                        max="<?php echo e(now()->format('Y-m-d')); ?>"
                        required
                    >
                    <small>Must be between incident date (<?php echo e($incident->incident_date?->format('M d, Y')); ?>) and today</small>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            id="medical_certificate_provided" 
                            name="medical_certificate_provided"
                            value="1"
                            <?php if($incident->medical_certificate_provided): echo 'checked'; endif; ?>
                        >
                        <span>Medical Certificate Provided</span>
                    </label>
                    <small style="margin-left: 28px; display: block;">Check this if a valid medical certificate from a registered doctor has been provided</small>
                </div>

                <div id="recoveryDateGroup" class="form-group hidden">
                    <label for="expected_recovery_date">
                        Expected Recovery Date <span style="color: #dc2626;">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="expected_recovery_date" 
                        name="expected_recovery_date"
                        value="<?php echo e($incident->expected_recovery_date?->format('Y-m-d')); ?>"
                    >
                    <small>Must be after leave start date and within 365 days</small>
                </div>

                <div class="stats-grid">
                    <div class="stat-box">
                        <p class="stat-label">Days Elapsed</p>
                        <p class="stat-value" id="daysElapsedDisplay">
                            <?php if($incident->sick_leave_start_date): ?>
                                <?php echo e((int)abs(now()->diffInDays($incident->sick_leave_start_date))); ?> days
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="stat-box alt">
                        <p class="stat-label">Payment Stage</p>
                        <p style="margin-top: 8px;" id="paymentStageDisplay">
                            <?php if($incident->leave_payment_stage): ?>
                                <?php if($incident->leave_payment_stage === 'FULL_PAY'): ?>
                                    <span class="badge badge-green">Full Pay (Days 1-90)</span>
                                <?php elseif($incident->leave_payment_stage === 'HALF_PAY'): ?>
                                    <span class="badge badge-orange">Half Pay (Days 91-180)</span>
                                <?php else: ?>
                                    <span class="badge badge-red">Medical Discharge Eligible (181+)</span>
                                <?php endif; ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php if($incident->leaveMilestones()->exists()): ?>
                <div style="margin-top: 1.8rem; padding-top: 1.2rem; border-top: 1px solid #e2eaf1;">
                    <p style="font-size: 0.8rem; font-weight: 700; margin-bottom: 14px; letter-spacing: 0.2px; color: #1e3a4b;">
                        <i class="fa-regular fa-flag" style="margin-right: 6px; color: #c47b1a;"></i>Milestones Reached
                    </p>
                    <?php $__currentLoopData = $incident->leaveMilestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="milestone-item">
                            <i class="fas fa-flag"></i>
                            <div>
                                <p class="milestone-title">
                                    <?php echo e($milestone->milestone_type === '90_DAY' ? '90-Day' : '180-Day'); ?> Milestone
                                </p>
                                <p class="milestone-date">
                                    Flagged on <?php echo e($milestone->applied_at->format('M d, Y H:i')); ?> 
                                    (<?php echo e($milestone->days_elapsed_at_flag); ?> days elapsed)
                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <div id="loadingState" class="hidden">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Saving and completing...</p>
                </div>

                <div id="errorAlert" class="alert alert-warning hidden" style="margin-top: 16px;">
                    <strong><i class="fa-regular fa-circle-exclamation" style="margin-right: 6px;"></i>Error</strong>
                    <p id="errorMessage" style="margin-top: 4px;"></p>
                </div>

                <div class="actions">
                    <a href="<?php echo e(route('dashboards.hr')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </a>
                    <div class="tooltip" style="flex: 1;">
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="width: 100%;">
                            <i class="fa-regular fa-floppy-disk"></i> Save & Complete
                        </button>
                        <span class="tooltiptext">Upon saving, the case will be escalated for further processing by the appropriate authority</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal">
        <div class="success-modal-content">
            <div class="success-modal-icon">
                <i class="fa-regular fa-circle-check"></i>
            </div>
            <div class="success-modal-title">Success!</div>
            <div class="success-modal-message">
                Leave information has been saved successfully.<br>
                The case is now being escalated for further processing.
            </div>
            <button class="success-modal-button" onclick="closeSuccessModal()">
                Continue to Dashboard
            </button>
        </div>
    </div>

    <script>
        function closeSuccessModal() {
            window.location.href = '<?php echo e(route("dashboards.hr")); ?>';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('leaveInformationForm');
            const medicalCertCheckbox = document.getElementById('medical_certificate_provided');
            const recoveryDateGroup = document.getElementById('recoveryDateGroup');
            const recoveryDateInput = document.getElementById('expected_recovery_date');
            const leaveStartInput = document.getElementById('sick_leave_start_date');
            
            // Toggle recovery date field
            medicalCertCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    recoveryDateGroup.classList.remove('hidden');
                    recoveryDateInput.required = true;
                } else {
                    recoveryDateGroup.classList.add('hidden');
                    recoveryDateInput.required = false;
                    recoveryDateInput.value = '';
                }
            });
            
            // Trigger on page load
            if (medicalCertCheckbox.checked) {
                recoveryDateGroup.classList.remove('hidden');
                recoveryDateInput.required = true;
            }
            
            // Calculate days elapsed and update payment stage
            leaveStartInput.addEventListener('change', function() {
                if (this.value) {
                    const startDate = new Date(this.value);
                    const today = new Date();
                    const daysElapsed = Math.floor((today - startDate) / (1000 * 60 * 60 * 24));
                    document.getElementById('daysElapsedDisplay').textContent = Math.abs(daysElapsed) + ' days';
                    
                    const paymentDisplay = document.getElementById('paymentStageDisplay');
                    const absDaysElapsed = Math.abs(daysElapsed);
                    
                    if (absDaysElapsed <= 90) {
                        paymentDisplay.innerHTML = '<span class="badge badge-green">Full Pay (Days 1-90)</span>';
                    } else if (absDaysElapsed <= 180) {
                        if (medicalCertCheckbox.checked) {
                            paymentDisplay.innerHTML = '<span class="badge badge-orange">Half Pay (Days 91-180)</span>';
                        } else {
                            paymentDisplay.innerHTML = '<span class="badge badge-green">Full Pay (Days 1-90)</span>';
                        }
                    } else {
                        paymentDisplay.innerHTML = '<span class="badge badge-red">Medical Discharge Eligible (181+)</span>';
                    }
                }
            });
            
            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const csrfToken = formData.get('_token');
                
                // Build form data for both calls
                const leaveData = new URLSearchParams();
                leaveData.append('sick_leave_start_date', formData.get('sick_leave_start_date'));
                leaveData.append('medical_certificate_provided', formData.get('medical_certificate_provided') ? '1' : '0');
                if (formData.get('expected_recovery_date')) {
                    leaveData.append('expected_recovery_date', formData.get('expected_recovery_date'));
                }
                
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('loadingState').classList.remove('hidden');
                document.getElementById('errorAlert').classList.add('hidden');
                
                try {
                    const response = await fetch('<?php echo e(route("incidents.leave_information_store", $incident)); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        credentials: 'same-origin',
                        body: leaveData,
                    });
                    
                    const result = await response.json();
                    
                    if (!response.ok) {
                        // Handle validation errors
                        if (result.errors) {
                            const errorMessages = Object.values(result.errors).flat().join(', ');
                            throw new Error(errorMessages);
                        }
                        throw new Error(result.message || result.error || 'Failed to save leave information');
                    }
                    
                    // Now submit compliance to escalate the case
                    const compliancePayload = {
                        compliance_decisions: [
                            { status: 'ACCEPTED', reason: null }
                        ],
                        optional_decisions: []
                    };
                    
                    const complianceResponse = await fetch('<?php echo e(route("incidents.submit_compliance", $incident)); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(compliancePayload),
                    });
                    
                    const complianceResult = await complianceResponse.json();
                    
                    if (!complianceResponse.ok) {
                        throw new Error(complianceResult.message || 'Failed to escalate case');
                    }
                    
                    document.getElementById('successModal').classList.add('show');
                    document.getElementById('loadingState').classList.add('hidden');
                    
                } catch (error) {
                    console.error('Form submission error:', error);
                    document.getElementById('errorMessage').textContent = error.message;
                    document.getElementById('errorAlert').classList.remove('hidden');
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('loadingState').classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html><?php /**PATH D:\OccupationalHealth System\resources\views\incidents\leave_information_capture.blade.php ENDPATH**/ ?>