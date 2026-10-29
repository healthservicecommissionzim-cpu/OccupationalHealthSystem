<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HR Review · Incident</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; font-weight: 600; color: #64748b; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { background: #f8fafc; color: #1D4533; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #1D4533; border-radius: 2px 2px 0 0; margin: -1.5rem -1.5rem 1rem -1.5rem; padding: 0; }
        .card h3 { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
        .card h3 i { color: #1D4533; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .field { display: flex; flex-direction: column; }
        .field-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-bottom: 4px; }
        .field-value { font-size: 12px; font-weight: 600; color: #0f172a; }
        .field-desc { font-size: 12px; color: #475569; line-height: 1.5; margin-top: 4px; }
        .col-span-2 { grid-column: 1 / -1; }
        .alert { padding: 12px; border-radius: 6px; border-left: 4px solid; margin-bottom: 1rem; }
        .alert-info { background: #eff6ff; border-color: #3b82f6; }
        .alert-info p { font-size: 12px; color: #1e40af; }
        .alert-info strong { font-weight: 700; }
        .alert-success { background: #f0fdf4; border-color: #22c55e; }
        .alert-success p { font-size: 12px; color: #166534; }
        .alert-success strong { font-weight: 700; }
        .doc-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 8px; }
        .doc-item:last-child { margin-bottom: 0; }
        .doc-item i { color: #94a3b8; font-size: 16px; flex-shrink: 0; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-type { font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 2px; }
        .doc-name { font-size: 11px; color: #64748b; }
        .doc-status { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; flex-shrink: 0; }
        .doc-status.verified { background: #d1fae5; color: #065f46; }
        .doc-status.pending { background: #fef3c7; color: #92400e; }
        .witness-item { display: flex; flex-direction: column; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 8px; }
        .witness-item:last-child { margin-bottom: 0; }
        .witness-name { font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 6px; }
        .witness-statement { font-size: 12px; color: #475569; line-height: 1.5; margin: 8px 0; }
        .witness-date { font-size: 11px; color: #94a3b8; }
        .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 12px; }
        .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; font-family: 'Inter', sans-serif; resize: vertical; }
        .form-group textarea:focus { outline: none; ring: 2px; ring-color: #1D4533; border-color: transparent; }
        .required { color: #ef4444; }
        .error { font-size: 11px; color: #ef4444; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
        .actions { display: flex; gap: 8px; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; }
        .btn { padding: 10px 20px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: #1D4533; color: white; flex: 1; justify-content: center; }
        .btn-primary:hover:not(:disabled) { background: #0f2818; }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-secondary { background: white; color: #64748b; border: 1px solid #e2e8f0; flex: 1; justify-content: center; }
        .btn-secondary:hover { background: #f8fafc; }
        .btn-danger { background: #ef4444; color: white; flex: 1; justify-content: center; }
        .btn-danger:hover { background: #dc2626; }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-clipboard-check"></i> HR Review</h1>
                <p><?php echo e($incident->reference_number); ?></p>
            </div>
            <a href="<?php echo e(route('incidents.hr_pending_review')); ?>" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Pending
            </a>
        </div>

        <!-- Incident Details Card -->
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-file-lines"></i> INCIDENT DETAILS</h3>
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
                    <span class="field-label">Facility</span>
                    <span class="field-value"><?php echo e($incident->facility->name ?? 'N/A'); ?></span>
                </div>
                <div class="field">
                    <span class="field-label">Incident Date</span>
                    <span class="field-value"><?php echo e($incident->incident_date->format('d M Y')); ?></span>
                </div>
                <div class="field">
                    <span class="field-label">Reported Date</span>
                    <span class="field-value"><?php echo e($incident->created_at->format('d M Y')); ?></span>
                </div>
                <div class="field col-span-2">
                    <span class="field-label">Incident Description</span>
                    <span class="field-desc"><?php echo e($incident->incident_description); ?></span>
                </div>
            </div>
        </div>

        <!-- Supervisor Verification Card -->
        <?php if($incident->supervisor_verified_at): ?>
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-user-check"></i> SUPERVISOR VERIFICATION</h3>
            <div class="alert alert-success">
                <strong>Verified by Supervisor</strong>
                <p style="margin-top: 4px;"><?php echo e($incident->assignedSupervisor->name ?? 'N/A'); ?> • <?php echo e($incident->supervisor_verified_at->format('d M Y H:i')); ?></p>
            </div>
            <div class="grid-2">
                <div class="field">
                    <span class="field-label">On-Duty Status</span>
                    <span class="field-value"><?php echo e($incident->on_duty_confirmation ? 'Yes, On Duty' : 'No, Not On Duty'); ?></span>
                </div>
                <div class="field">
                    <span class="field-label">Verification Date</span>
                    <span class="field-value"><?php echo e($incident->supervisor_verified_at->format('d M Y')); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Head of Department Report Card -->
        <?php if($incident->hod_report): ?>
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-file-signature"></i> HEAD OF DEPARTMENT REPORT</h3>
            <div class="alert alert-info">
                <strong>Report Submitted</strong>
                <p style="margin-top: 4px;"><?php echo e($incident->hodReportSubmittedBy->name ?? 'N/A'); ?> • <?php echo e($incident->hod_report_submitted_at->format('d M Y H:i')); ?></p>
            </div>
            <div class="field col-span-2">
                <span class="field-label">Report Content</span>
                <span class="field-desc" style="margin-top: 8px; padding: 12px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0;"><?php echo e($incident->hod_report); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Witness Statements Card -->
        <?php if($incident->witnessTokens && $incident->witnessTokens->count() > 0): ?>
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-users"></i> WITNESS STATEMENTS (<?php echo e($incident->witnessTokens->count()); ?>)</h3>
            <?php $__currentLoopData = $incident->witnessTokens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $token): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="witness-item">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div class="witness-name"><?php echo e($token->witness_name ?? 'Witness'); ?></div>
                    <?php if($token->status === 'COMPLETED'): ?>
                    <span class="status-badge status-completed">
                        <i class="fa-solid fa-check"></i> Completed
                    </span>
                    <?php else: ?>
                    <span class="status-badge status-pending">
                        <i class="fa-solid fa-clock"></i> Pending
                    </span>
                    <?php endif; ?>
                </div>
                <?php if($token->witness_statement): ?>
                <div class="witness-statement"><?php echo e($token->witness_statement); ?></div>
                <?php endif; ?>
                <?php if($token->created_at): ?>
                <div class="witness-date"><?php echo e($token->created_at->diffForHumans()); ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- Supporting Documents Card -->
        <?php
            $relevantDocuments = $incident->attachments->whereIn('document_type', ['doctors_report', 'injury_report'])->values();
        ?>
        <?php if($relevantDocuments->count() > 0): ?>
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-paperclip"></i> SUPPORTING DOCUMENTS</h3>
            <?php $__currentLoopData = $relevantDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="doc-item">
                <i class="fa-solid fa-file"></i>
                <div class="doc-info">
                    <div class="doc-type"><?php echo e(\App\Models\IncidentAttachment::DOCUMENT_TYPES[$attachment->document_type] ?? 'Document'); ?></div>
                    <div class="doc-name"><?php echo e($attachment->file_name); ?></div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
                    <span class="doc-status <?php echo e(strtolower($attachment->upload_status)); ?>">
                        <i class="fa-solid fa-<?php echo e($attachment->upload_status === 'VERIFIED' ? 'check' : 'clock'); ?>"></i> <?php echo e($attachment->upload_status); ?>

                    </span>
                    <a href="<?php echo e(route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment])); ?>" target="_blank" class="btn" style="padding: 6px 12px; font-size: 11px; background: #1D4533; color: white; border-radius: 4px; text-decoration: none; flex: 0;">
                        <i class="fa-solid fa-eye"></i> View
                    </a>
                    <a href="<?php echo e(route('incidents.view_attachment', ['incident' => $incident, 'attachment' => $attachment])); ?>" download class="btn" style="padding: 6px 12px; font-size: 11px; background: #f1f4f9; color: #1D4533; border: 1px solid #e2e8f0; border-radius: 4px; text-decoration: none; flex: 0;">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- HR Review Decision Card -->
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-check-double"></i> HR REVIEW DECISION</h3>

            <div class="alert alert-info">
                <strong>Review Required</strong>
                <p style="margin-top: 4px;">Please review all incident documentation and determine next action for escalation or closure.</p>
            </div>

            <form method="POST" action="<?php echo e(route('incidents.submit_compliance', ['incident' => $incident])); ?>" id="hrReviewForm">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>
                        HR Review Notes
                        <span class="required">*</span>
                    </label>
                    <textarea name="hr_review_notes" rows="5" placeholder="Enter your HR review comments and decision..." required><?php echo e(old('hr_review_notes')); ?></textarea>
                    <?php $__errorArgs = ['hr_review_notes'];
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

                <div class="actions">
                    <a href="<?php echo e(route('incidents.hr_pending_review')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-times"></i> Cancel
                    </a>
                    <button type="submit" name="action" value="approve" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Approve & Continue
                    </button>
                    <button type="submit" name="action" value="reject" class="btn btn-danger">
                        <i class="fa-solid fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('hrReviewForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = e.submitter;
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
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\hr_view.blade.php ENDPATH**/ ?>