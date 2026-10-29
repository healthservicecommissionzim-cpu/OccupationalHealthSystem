<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
        .summary { background-color: #f8f9fa; padding: 20px; margin: 20px 0; }
        .stat { display: inline-block; background-color: white; padding: 15px 25px; margin: 10px 5px; border-radius: 4px; text-align: center; }
        .stat-number { font-size: 24px; font-weight: bold; color: #3498db; }
        .stat-label { font-size: 12px; color: #999; }
        .errors { background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 15px; margin: 20px 0; }
        .error-item { background-color: white; padding: 10px; margin: 5px 0; border-left: 3px solid #ffc107; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Leave Milestone Check Summary</h1>
            <p>Automated Daily Report - <?php echo e($timestamp); ?></p>
        </div>

        <div class="summary">
            <h2>Daily Results</h2>
            <div style="text-align: center;">
                <div class="stat">
                    <div class="stat-number"><?php echo e($flagged90); ?></div>
                    <div class="stat-label">Cases Flagged at 90 Days</div>
                </div>
                <div class="stat">
                    <div class="stat-number"><?php echo e($flagged180); ?></div>
                    <div class="stat-label">Cases Flagged at 180 Days</div>
                </div>
                <div class="stat">
                    <div class="stat-number"><?php echo e(count($errors)); ?></div>
                    <div class="stat-label">Errors Encountered</div>
                </div>
            </div>
        </div>

        <?php if(!empty($errors)): ?>
        <div class="errors">
            <h3>⚠️ Errors During Processing</h3>
            <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="error-item">
                <strong>Incident ID:</strong> <?php echo e($error['incident_id']); ?><br>
                <strong>Error:</strong> <?php echo e($error['error']); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <div style="background-color: #e8f4f8; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>Review flagged cases on the Leave Management Dashboard</li>
                <li>Ensure PMD/CMO received notification emails</li>
                <li>Monitor for any system errors in the logs</li>
            </ul>
        </div>

        <div class="footer">
            <p>Occupational Health and Safety System - HSC</p>
            <p>This is an automated daily report. For issues, contact system administrator.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\emails\leave-milestone-summary.blade.php ENDPATH**/ ?>