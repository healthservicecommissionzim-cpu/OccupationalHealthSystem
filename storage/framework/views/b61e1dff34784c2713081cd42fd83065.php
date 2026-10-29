<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Incident · Supervisor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
        .radio-group { display: flex; gap: 1rem; }
        .radio-option { flex: 1; position: relative; }
        .radio-option input { display: none; }
        .radio-label { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 16px; border: 2px solid #e4e9ee; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 600; background: white; transition: all 0.2s; color: #4e596c; }
        .radio-option input:checked + .radio-label { border-color: #22c55e; background: #dcfce7; color: #15803d; }
        .radio-option input[value="0"]:checked + .radio-label { border-color: #ef4444; background: #fecaca; color: #991b1b; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #101a31; margin-bottom: 12px; }
        .required { color: #ef4444; }
        .error { font-size: 11px; color: #ef4444; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
        .actions { display: flex; gap: 10px; padding-top: 1.5rem; border-top: 1px solid #e1e7ee; }
        .btn { padding: 11px 24px; border-radius: 8px; border: none; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: #079b6d; color: white; flex: 1; justify-content: center; }
        .btn-primary:hover:not(:disabled) { background: #067c58; }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-secondary { background: white; color: #4e596c; border: 1px solid #e4e9ee; flex: 1; justify-content: center; }
        .btn-secondary:hover { background: #f8fafb; border-color: #ccd6df; }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-icon"><i class="fa-solid fa-check-double"></i></div>
                <div>
                    <h1>Verify Incident</h1>
                    <p><?php echo e($incident->reference_number); ?></p>
                </div>
            </div>
            <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="back-btn">
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
                        <span class="field-value"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">ID Number</span>
                        <span class="field-value"><?php echo e($incident->id_number); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">Department</span>
                        <span class="field-value"><?php echo e($incident->department->name ?? 'N/A'); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">Designation</span>
                        <span class="field-value"><?php echo e($incident->designation->name ?? 'N/A'); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">Incident Date</span>
                        <span class="field-value"><?php echo e($incident->incident_date->format('d M Y')); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">Incident Time</span>
                        <span class="field-value"><?php echo e($incident->incident_time); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">Incident Type</span>
                        <span class="field-value"><?php echo e($incident->incidentType->name ?? 'Not Specified'); ?></span>
                    </div>
                    <div class="field">
                        <span class="field-label">Facility</span>
                        <span class="field-value"><?php echo e($incident->facility->name ?? 'N/A'); ?></span>
                    </div>
                    <div class="field col-span-2">
                        <span class="field-label">Description</span>
                        <span class="field-desc"><?php echo e($incident->incident_description); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- First Aid Record Card -->
        <?php
            $firstAidRecord = $incident->firstAidRecords->first();
        ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-heart-pulse"></i> First Aid Record</h3>
            </div>
            <div class="card-body">
                <?php if($firstAidRecord): ?>
                    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                        <p><i class="fa-solid fa-check-circle"></i> First Aid Record Submitted</p>
                    </div>
                    
                    <div class="grid-2">
                        <div class="field">
                            <span class="field-label">Nurse/First Aider Name</span>
                            <span class="field-value"><?php echo e($firstAidRecord->nurse_name ?? 'N/A'); ?></span>
                        </div>
                        <div class="field">
                            <span class="field-label">License Number</span>
                            <span class="field-value"><?php echo e($firstAidRecord->nurse_license_number ?? 'N/A'); ?></span>
                        </div>
                        <div class="field">
                            <span class="field-label">Time Administered</span>
                            <span class="field-value"><?php echo e($firstAidRecord->time_administered?->format('d M Y H:i') ?? 'N/A'); ?></span>
                        </div>
                        <div class="field">
                            <span class="field-label">Location of First Aid</span>
                            <span class="field-value"><?php echo e($firstAidRecord->location ?? 'N/A'); ?></span>
                        </div>
                        <div class="field">
                            <span class="field-label">Severity Level</span>
                            <span class="field-value">
                                <?php if($firstAidRecord->severity_level): ?>
                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600;
                                        <?php if($firstAidRecord->severity_level === 'CRITICAL'): ?> background: #fee2e2; color: #991b1b;
                                        <?php elseif($firstAidRecord->severity_level === 'SEVERE'): ?> background: #fecaca; color: #7c2d12;
                                        <?php elseif($firstAidRecord->severity_level === 'MODERATE'): ?> background: #fcd34d; color: #92400e;
                                        <?php else: ?> background: #dcfce7; color: #065f46;
                                        <?php endif; ?>
                                    ">
                                        <?php echo e(ucfirst(strtolower($firstAidRecord->severity_level))); ?>

                                    </span>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="field">
                            <span class="field-label">Record Status</span>
                            <span class="field-value">
                                <?php if($firstAidRecord->status === 'approved'): ?>
                                    <span style="color: #22c55e; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Approved</span>
                                <?php elseif($firstAidRecord->status === 'rejected'): ?>
                                    <span style="color: #ef4444; font-weight: 600;"><i class="fa-solid fa-times-circle"></i> Rejected</span>
                                <?php else: ?>
                                    <span style="color: #f59e0b; font-weight: 600;"><i class="fa-solid fa-clock"></i> Pending Review</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="field col-span-2">
                            <span class="field-label">Injury Description</span>
                            <span class="field-desc"><?php echo e($firstAidRecord->injury_description ?? 'No description provided'); ?></span>
                        </div>
                        
                        <div class="field col-span-2">
                            <span class="field-label">First Aid Provided</span>
                            <span class="field-desc"><?php echo e($firstAidRecord->first_aid_description ?? 'No description provided'); ?></span>
                        </div>
                        
                        <?php if($firstAidRecord->notes): ?>
                        <div class="field col-span-2">
                            <span class="field-label">Additional Notes</span>
                            <span class="field-desc"><?php echo e($firstAidRecord->notes); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($firstAidRecord->status !== 'pending' && $firstAidRecord->reviewed_by): ?>
                        <div class="field col-span-2">
                            <span class="field-label">Review Information</span>
                            <div style="font-size: 13px; color: #4e596c; line-height: 1.6; margin-top: 6px;">
                                <p style="margin: 0 0 6px 0;"><strong>Reviewed By:</strong> <?php echo e($firstAidRecord->reviewer?->name ?? 'Unknown'); ?></p>
                                <p style="margin: 0 0 6px 0;"><strong>Reviewed At:</strong> <?php echo e($firstAidRecord->reviewed_at?->format('d M Y H:i') ?? 'N/A'); ?></p>
                                <?php if($firstAidRecord->review_comments): ?>
                                <p style="margin: 0;"><strong>Comments:</strong> <?php echo e($firstAidRecord->review_comments); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <p><i class="fa-solid fa-info-circle"></i> <strong>No First Aid Record</strong> — The nurse or person who rendered first aid must submit their record using the provided First Aid Token.</p>
                        <p style="margin-top: 8px; margin-bottom: 0;"><strong>First Aid Token:</strong> <span style="font-family: 'Courier New', monospace; letter-spacing: 1px; font-weight: 600;"><?php echo e($incident->first_aid_token); ?></span> (expires in 7 days)</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Verification Form Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-check-double"></i> On-Duty Confirmation</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                    <strong>Stage 2: Supervisor Verification</strong>
                    <p style="margin-top: 6px; margin-bottom: 0;">As the Immediate Supervisor, verify this incident in compliance with HSB Circular No. 3 of 2017. Your confirmation is mandatory.</p>
                </div>

                <form method="POST" action="<?php echo e(route('incidents.supervisor_verify', ['incident' => $incident])); ?>" id="verificationForm">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label>
                            I certify that this employee was actively engaged in official duties at the time of the incident
                            <span class="required">*</span>
                        </label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="on_duty_yes" name="on_duty_confirmation" value="1" required <?php echo e(old('on_duty_confirmation') == '1' ? 'checked' : ''); ?> />
                                <label for="on_duty_yes" class="radio-label">
                                    <i class="fa-solid fa-check-circle"></i> Yes, On Duty
                                </label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="on_duty_no" name="on_duty_confirmation" value="0" required <?php echo e(old('on_duty_confirmation') == '0' ? 'checked' : ''); ?> />
                                <label for="on_duty_no" class="radio-label">
                                    <i class="fa-solid fa-x-circle"></i> No, Not On Duty
                                </label>
                            </div>
                        </div>
                        <?php $__errorArgs = ['on_duty_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="alert alert-warning">
                        <strong>Important Legal Notice</strong>
                        <p style="margin-top: 6px; margin-bottom: 0;">Your certification must be truthful and accurate. This is a mandatory requirement under the Zimbabwean Health and Safety Act. False certification is subject to disciplinary and legal action.</p>
                    </div>

                    <div class="actions">
                        <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Next: Witness Statement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verificationForm');
            const submitBtn = form?.querySelector('button[type="submit"]');
            
            if (submitBtn) {
                // Lock the button initially
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.innerHTML = '<i class="fa-solid fa-lock"></i> Complete Verification First';
                
                // Enable button only when form is valid (radio button selected)
                const radioBtns = form.querySelectorAll('input[name="on_duty_confirmation"]');
                radioBtns.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Check if any radio button is selected
                        const isChecked = Array.from(radioBtns).some(r => r.checked);
                        
                        if (isChecked) {
                            submitBtn.disabled = false;
                            submitBtn.style.opacity = '1';
                            submitBtn.style.cursor = 'pointer';
                            submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Next: Witness Statement';
                        } else {
                            submitBtn.disabled = true;
                            submitBtn.style.opacity = '0.6';
                            submitBtn.style.cursor = 'not-allowed';
                            submitBtn.innerHTML = '<i class="fa-solid fa-lock"></i> Complete Verification First';
                        }
                    });
                });
            }
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite;"></i> Processing...';
                    }
                });
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\supervisor_verify.blade.php ENDPATH**/ ?>