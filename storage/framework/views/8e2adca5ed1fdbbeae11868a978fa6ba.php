<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Case Dossier - READ ONLY · <?php echo e($incident->reference_number); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1100px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start; }
        .header h1 { font-size: 20px; font-weight: 700; }
        .header p { font-size: 12px; color: #64748b; margin-top: 4px; }
        .read-only-badge { display: inline-flex; align-items: center; gap: 6px; background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; padding: 8px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; margin-top: 8px; }
        .read-only-badge i { font-size: 14px; }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid #1D4533;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .back-btn:hover {
            background: #1D4533;
            color: white;
            transform: translateX(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .card { font-family: 'Plus Jakarta Sans', sans-serif; background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #1D4533; margin: -1.5rem -1.5rem 1rem -1.5rem; }
        .card h3 { font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
        .card h3 i { color: #1D4533; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .field { display: flex; flex-direction: column; }
        .field-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-bottom: 4px; }
        .field-value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; font-weight: 600; color: #0f172a; padding: 8px; background: #f8fafc; border-radius: 4px; border-left: 2px solid #1D4533; }
        .field-value.empty { color: #94a3b8; font-style: italic; }
        .section-title { font-size: 12px; font-weight: 700; color: #0f172a; margin-top: 1.5rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #1D4533; }
        .witness-list { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem; }
        .witness-item { background: white; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; }
        .witness-item:last-child { margin-bottom: 0; }
        .witness-name { font-weight: 700; color: #1D4533; margin-bottom: 8px; font-size: 12px; }
        .witness-details { font-size: 11px; color: #64748b; margin-bottom: 8px; }
        .witness-statement { font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; font-size: 12px; line-height: 1.5; white-space: normal; word-wrap: break-word; background: #f1f5f9; padding: 8px; border-radius: 4px; }
        .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .status-badge.verified { background: #dcfce7; color: #065f46; }
        .actions { display: flex; gap: 8px; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; }
        .btn { font-family: 'Plus Jakarta Sans', sans-serif; padding: 10px 20px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: #1D4533; color: white; }
        .btn-primary:hover { background: #0f2818; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(29, 69, 51, 0.3); }
        .btn-secondary { background: white; color: #64748b; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #f8fafc; }
        .btn-full { flex: 1; justify-content: center; }
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 12px; margin-bottom: 1rem; font-size: 12px; color: #1e40af; }
        .info-box i { color: #3b82f6; margin-right: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-file-contract"></i> Case Dossier</h1>
                <p><?php echo e($incident->reference_number); ?></p>
                <div class="read-only-badge">
                    <i class="fa-solid fa-lock"></i> READ-ONLY VIEW
                </div>
            </div>
            <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Info Alert -->
        <div class="info-box">
            <i class="fa-solid fa-info-circle"></i>
            <strong>This case has been verified and submitted to HR.</strong> This is a read-only view of the complete case dossier for your reference.
        </div>

        <!-- Case Status -->
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-flag-checkered"></i> CASE STATUS</h3>
            <div class="grid-3">
                <div class="field">
                    <span class="field-label">Status</span>
                    <div class="status-badge verified">
                        <i class="fa-solid fa-check-circle"></i> Verified & Submitted
                    </div>
                </div>
                <div class="field">
                    <span class="field-label">Reference Number</span>
                    <span class="field-value"><?php echo e($incident->reference_number); ?></span>
                </div>
                <div class="field">
                    <span class="field-label">Submission Date</span>
                    <span class="field-value"><?php echo e($incident->hod_report_submitted_at?->format('d M Y, H:i') ?? 'Pending'); ?></span>
                </div>
            </div>
        </div>

        <!-- Incident Details -->
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-exclamation-triangle"></i> INCIDENT DETAILS</h3>
            <div class="grid-2">
                <div class="field">
                    <span class="field-label">Worker Name</span>
                    <span class="field-value"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></span>
                </div>
                <div class="field">
                    <span class="field-label">Incident Date</span>
                    <span class="field-value"><?php echo e($incident->incident_date->format('d M Y')); ?></span>
                </div>
                <div class="field">
                    <span class="field-label">Facility</span>
                    <span class="field-value"><?php echo e($incident->facility->name ?? 'N/A'); ?></span>
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
                    <span class="field-label">Incident Type</span>
                    <span class="field-value"><?php echo e($incident->incidentType->name ?? 'N/A'); ?></span>
                </div>
            </div>
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                <div class="field">
                    <span class="field-label">Incident Description</span>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-top: 8px; color: #334155; font-size: 12px; line-height: 1.6; white-space: normal; word-wrap: break-word;">
                        <?php echo e(trim($incident->incident_description) ?? 'No description provided'); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Witness Statements -->
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-comment-dots"></i> WITNESS STATEMENTS</h3>
            <?php if($incident->witnessTokens->whereNotNull('statement')->count() > 0): ?>
                <div class="witness-list">
                    <?php $__currentLoopData = $incident->witnessTokens->whereNotNull('statement'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $witness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="witness-item">
                        <div class="witness-name">
                            <i class="fa-solid fa-user-circle"></i> Witness <?php echo e($index + 1); ?>: <?php echo e($witness->witness_name); ?>

                        </div>
                        <div class="witness-details">
                            Phone: <?php echo e($witness->phone_number); ?> | Submitted: <?php echo e($witness->statement_submitted_at?->format('d M Y, H:i')); ?>

                        </div>
                        <div class="witness-statement">
                            <?php echo e(trim($witness->statement)); ?>

                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p style="color: #64748b; font-size: 12px;">No witness statements recorded.</p>
            <?php endif; ?>
        </div>

        <!-- Head of Department Report -->
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-clipboard-check"></i> HEAD OF DEPARTMENT REPORT</h3>
            <?php if($incident->hod_report): ?>
                <div class="field">
                    <span class="field-label">Description of Worker's Duties at Time of Injury</span>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-top: 8px; color: #334155; font-size: 12px; line-height: 1.6; white-space: normal; word-wrap: break-word;">
                        <?php echo e(trim($incident->hod_report)); ?>

                    </div>
                </div>
            <?php else: ?>
                <p style="color: #94a3b8; font-style: italic; font-size: 12px;">No HOD report submitted.</p>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="actions">
            <div style="display: flex; gap: 8px;">
                <a href="<?php echo e(route('incidents.download_case_dossier', ['incident' => $incident])); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-file-pdf"></i> Print to PDF
                </a>
                <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\case_dossier_view.blade.php ENDPATH**/ ?>