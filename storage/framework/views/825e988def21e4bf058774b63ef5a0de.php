<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Case Dossier - <?php echo e($incident->reference_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
        }
        .header {
            border-bottom: 3px solid #1D4533;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .header h1 {
            margin: 0;
            color: #1D4533;
            font-size: 24px;
        }
        .header p {
            margin: 0.25rem 0;
            color: #666;
            font-size: 12px;
        }
        .section {
            margin-bottom: 1.5rem;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f0f0f0;
            border-left: 4px solid #1D4533;
            padding: 0.5rem;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 0.75rem;
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        .info-value {
            display: table-cell;
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        .document-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .document-item {
            background-color: #fafafa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            page-break-inside: avoid;
        }
        .document-type {
            font-weight: bold;
            color: #1D4533;
            font-size: 12px;
        }
        .document-details {
            font-size: 11px;
            color: #666;
            margin-top: 0.25rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 0.25rem;
        }
        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .status-verified {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .status-rejected {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .compliance-notes {
            background-color: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 1rem;
            font-size: 12px;
            line-height: 1.6;
            margin-top: 0.5rem;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 1rem;
            font-size: 10px;
            color: #999;
            text-align: center;
            margin-top: 2rem;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>CASE DOSSIER</h1>
            <p><strong>Reference Number:</strong> <?php echo e($incident->reference_number); ?></p>
            <p><strong>Generated:</strong> <?php echo e(date('M d, Y H:i')); ?></p>
        </div>

        <!-- Case Summary Section -->
        <div class="section">
            <div class="section-title">📋 CASE SUMMARY</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Reference Number</div>
                    <div class="info-value"><?php echo e($incident->reference_number); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Employee Name</div>
                    <div class="info-value"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Facility</div>
                    <div class="info-value"><?php echo e($incident->facility?->name ?? 'N/A'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Incident Date</div>
                    <div class="info-value"><?php echo e($incident->incident_date->format('M d, Y')); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value"><?php echo e($incident->status ?? 'PENDING'); ?></div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="section">
            <div class="section-title">📄 UPLOADED DOCUMENTS (<?php echo e($attachments->count()); ?>)</div>
            
            <?php if($attachments->count() > 0): ?>
                <ul class="document-list">
                    <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="document-item">
                        <div class="document-type"><?php echo e($attachment->getDocumentTypeLabel()); ?></div>
                        <div class="document-details">
                            <strong>File:</strong> <?php echo e($attachment->file_name); ?><br>
                            <strong>Size:</strong> <?php echo e(number_format($attachment->file_size / 1024 / 1024, 2)); ?> MB<br>
                            <strong>Uploaded:</strong> <?php echo e($attachment->created_at->format('M d, Y H:i')); ?>

                        </div>
                        <span class="status-badge 
                            <?php if($attachment->upload_status === 'VERIFIED'): ?>
                                status-verified
                            <?php elseif($attachment->upload_status === 'REJECTED'): ?>
                                status-rejected
                            <?php else: ?>
                                status-pending
                            <?php endif; ?>
                        ">
                            <?php echo e($attachment->upload_status); ?>

                        </span>
                        <?php if($attachment->verified_by): ?>
                            <div class="document-details" style="margin-top: 0.5rem;">
                                <strong>Reviewed by:</strong> <?php echo e($attachment->verifiedBy->name ?? 'System'); ?><br>
                                <strong>Date:</strong> <?php echo e($attachment->verified_at->format('M d, Y H:i')); ?>

                            </div>
                        <?php endif; ?>
                        <?php if($attachment->upload_notes): ?>
                            <div class="document-details" style="margin-top: 0.5rem; font-style: italic;">
                                <strong>Notes:</strong> <?php echo e($attachment->upload_notes); ?>

                            </div>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p style="color: #999; font-size: 12px;">No documents have been uploaded for this case.</p>
            <?php endif; ?>
        </div>

        <!-- HR Compliance Notes Section -->
        <?php if($incident->hr_compliance_notes): ?>
        <div class="section">
            <div class="section-title">✓ HR COMPLIANCE NOTES & FINDINGS</div>
            <div class="compliance-notes">
                <?php echo e($incident->hr_compliance_notes); ?>

            </div>
            <div class="document-details" style="margin-top: 0.75rem;">
                <strong>Reviewed by:</strong> <?php echo e(auth()->user()->name ?? 'System'); ?><br>
                <strong>Date:</strong> <?php echo e($incident->hr_compliance_notes_saved_at?->format('M d, Y H:i') ?? 'N/A'); ?>

            </div>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p>This document contains confidential information and is for official use only.</p>
            <p><?php echo e(config('app.name')); ?> - Occupational Health & Safety System</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\pdf\case_dossier.blade.php ENDPATH**/ ?>