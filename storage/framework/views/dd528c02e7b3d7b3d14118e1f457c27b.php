<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Approved Cases · HR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 24px; font-weight: 700; color: #0f172a; }
        .back-btn { padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; text-decoration: none; color: #64748b; transition: all 0.2s; }
        .back-btn:hover { background: #f8fafc; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #0d7a3d; margin: -1.5rem -1.5rem 1rem -1.5rem; border-radius: 2px 2px 0 0; }
        .card h3 { font-size: 13px; font-weight: 700; margin-bottom: 1rem; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        th { background: #f8fafc; font-weight: 600; color: #475569; }
        tr:hover { background: #f8fafc; }
        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .status-approved { background: #dcfce7; color: #15803d; }
        .status-escalated { background: #fef08a; color: #854d0e; }
        .action-btn { padding: 6px 12px; background: white; border: 1px solid #0d7a3d; border-radius: 6px; font-size: 11px; font-weight: 600; color: #0d7a3d; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; }
        .action-btn:hover { background: #0d7a3d; color: white; }
        .empty-state { text-align: center; padding: 40px; color: #64748b; }
        .empty-state i { font-size: 32px; margin-bottom: 12px; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-check-circle"></i> Approved Cases</h1>
                <p style="font-size: 12px; color: #64748b; margin-top: 4px;">HR-approved cases including those escalated to DMO</p>
            </div>
            <a href="<?php echo e(route('dashboards.hr')); ?>" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="card">
            <div class="card-top"></div>
            <h3><i class="fa-solid fa-list"></i> Approved Cases (<?php echo e($statistics['total']); ?> total)</h3>
            <?php if($incidents->count() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Employee</th>
                        <th>Facility</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($incident->reference_number); ?></strong></td>
                        <td><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td><?php echo e($incident->facility->name ?? 'N/A'); ?></td>
                        <td><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                        <td>
                            <?php if($incident->status === 'ESCALATED_TO_DMO'): ?>
                                <span class="status-badge status-escalated"><i class="fa-solid fa-arrow-up"></i> Escalated to DMO</span>
                            <?php else: ?>
                                <span class="status-badge status-approved"><i class="fa-solid fa-check"></i> Approved</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('incidents.hr_dossier', $incident)); ?>" class="action-btn">
                                <i class="fa-solid fa-eye"></i> View Dossier
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>No approved cases</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\hr\approved_cases.blade.php ENDPATH**/ ?>