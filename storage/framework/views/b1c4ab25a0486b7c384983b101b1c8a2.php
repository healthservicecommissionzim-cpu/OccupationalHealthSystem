<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Escalation Report · HR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .back-btn { padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; text-decoration: none; color: #64748b; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-top { height: 4px; background: #1D4533; margin: -1.5rem -1.5rem 1rem -1.5rem; }
        .card h3 { font-size: 13px; font-weight: 700; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        th { background: #f8fafc; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div><h1><i class="fa-solid fa-arrow-up-right"></i> Escalation Report</h1></div>
            <a href="<?php echo e(route('dashboards.hr')); ?>" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>
        <div class="card">
            <div class="card-top"></div>
            <h3>Escalation Distribution</h3>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Cases</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $escalations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $incidents): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(str_replace('_', ' ', $status)); ?></td>
                        <td><?php echo e($incidents->count()); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2">No data available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\hr\escalation_report.blade.php ENDPATH**/ ?>