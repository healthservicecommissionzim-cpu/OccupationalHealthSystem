<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-left: 4px solid #3498db; }
        .details { background-color: white; padding: 15px; margin: 10px 0; border-radius: 4px; }
        .details-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #2c3e50; }
        .alert { background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 15px; margin: 20px 0; }
        .action-button { display: inline-block; background-color: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Medical Board Review Request</h1>
            <p><?php echo e($milestoneType); ?> Leave Milestone - HSC OHS System</p>
        </div>

        <div class="content">
            <p>Dear <?php echo e($medicalOfficer->name); ?>,</p>
            
            <p>A case has reached the <?php echo e(strtolower($milestoneType)); ?> leave milestone and requires your medical board review decision.</p>
        </div>

        <h3>Case Information</h3>
        <div class="details">
            <div class="details-row">
                <span class="label">Reference Number:</span>
                <span><?php echo e($incident->reference_number); ?></span>
            </div>
            <div class="details-row">
                <span class="label">Worker Name:</span>
                <span><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></span>
            </div>
            <div class="details-row">
                <span class="label">Facility:</span>
                <span><?php echo e($incident->facility?->name ?? 'N/A'); ?></span>
            </div>
            <div class="details-row">
                <span class="label">Incident Date:</span>
                <span><?php echo e($incident->incident_date?->format('Y-m-d') ?? 'N/A'); ?></span>
            </div>
        </div>

        <h3>Leave Information</h3>
        <div class="details">
            <div class="details-row">
                <span class="label">Leave Start Date:</span>
                <span><?php echo e($incident->sick_leave_start_date?->format('Y-m-d') ?? 'Not recorded'); ?></span>
            </div>
            <div class="details-row">
                <span class="label">Days Elapsed:</span>
                <span><?php echo e($daysElapsed); ?> days</span>
            </div>
            <div class="details-row">
                <span class="label">Payment Stage:</span>
                <span>
                    <?php if($paymentStage === 'FULL_PAY'): ?>
                        <strong style="color: green;">Full Pay (Days 1-90)</strong>
                    <?php elseif($paymentStage === 'HALF_PAY'): ?>
                        <strong style="color: orange;">Half Pay (Days 91-180)</strong>
                    <?php else: ?>
                        <strong style="color: red;">Medical Discharge Eligible (181+ days)</strong>
                    <?php endif; ?>
                </span>
            </div>
            <div class="details-row">
                <span class="label">Medical Certificate:</span>
                <span><?php echo e($incident->medical_certificate_provided ? 'Yes' : 'No'); ?></span>
            </div>
            <?php if($recoveryDate): ?>
            <div class="details-row">
                <span class="label">Expected Recovery Date:</span>
                <span><?php echo e($recoveryDate); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <?php if($milestoneType === '90-Day'): ?>
        <div class="alert">
            <strong>⚠️ 90-Day Milestone Reached</strong>
            <p>This case has reached 90 days of sick leave and is now eligible for medical board review. Under HSB 3/2017, workers may qualify for half-pay sick leave for an additional 90 days with valid medical certification.</p>
        </div>
        <?php else: ?>
        <div class="alert">
            <strong>⚠️ 180-Day Milestone Reached</strong>
            <p>This case has reached 180 days of sick leave (full 90 days + additional 90 days on half pay). The worker may now be eligible for medical discharge if recovery is not anticipated.</p>
        </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="<?php echo e(route('incidents.show', $incident->id)); ?>" class="action-button">
                Review Case Details
            </a>
        </div>

        <div class="footer">
            <p>Occupational Health and Safety System - HSC</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\emails\leave-milestone-notification.blade.php ENDPATH**/ ?>