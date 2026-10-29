<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border: 3px solid #1D4533;
        }
        .header {
            background: white;
            padding: 20px;
            border-bottom: 3px solid #D4A574;
            text-align: center;
        }
        .logo {
            height: 60px;
            margin-bottom: 10px;
        }
        .hsc-title {
            color: #1D4533;
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }
        .hsc-subtitle {
            color: #666;
            font-size: 12px;
            margin: 3px 0;
        }
        .ref-date {
            float: right;
            text-align: right;
            font-size: 11px;
        }
        .ref-date div {
            margin: 5px 0;
        }
        .clearfix {
            clear: both;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
        }
        .section-title {
            background: #1D4533;
            color: white;
            padding: 10px 15px;
            margin-top: 20px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 13px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table tr {
            border-bottom: 1px solid #e0e0e0;
        }
        .info-table td {
            padding: 10px;
            font-size: 13px;
        }
        .info-table .label {
            font-weight: bold;
            width: 40%;
            background: #f9f9f9;
            color: #1D4533;
        }
        .info-table .value {
            color: #333;
        }
        .status-box {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 12px;
            margin: 15px 0;
            font-size: 13px;
        }
        .witness-section {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #1D4533;
        }
        .witness-item {
            margin: 12px 0;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
        .witness-name {
            font-weight: bold;
            color: #1D4533;
            margin-bottom: 5px;
        }
        .witness-link {
            display: inline-block;
            background: #1D4533;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 5px;
        }
        .witness-link:hover {
            background: #0f2818;
        }
        .button {
            display: inline-block;
            background: #1D4533;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 15px 0;
        }
        .button:hover {
            background: #0f2818;
        }
        .footer {
            background: #f9f9f9;
            padding: 20px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        .important {
            background: #fff3cd;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 15px 0;
            font-size: 13px;
        }
        .bottom-border {
            background: #1D4533;
            height: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="ref-date">
                <div><strong>Reference:</strong> <?php echo e($incident->reference_number); ?></div>
                <div><strong>Date:</strong> <?php echo e(now()->format('d M Y')); ?></div>
            </div>
            <img src="https://injury.hsc.org.zw/images/hsc-logo.png" alt="HSC Logo" class="logo">
            <div class="hsc-title">HEALTH SERVICE COMMISSION</div>
            <div class="clearfix"></div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <?php echo e($incident->first_name); ?>,
            </div>

            <p style="font-size: 14px; color: #333; line-height: 1.6;">
                Your incident report has been <strong>successfully submitted</strong> to the Health Service Commission. 
                This email confirms receipt of your submission and provides important next steps.
            </p>

            <!-- Incident Details Section -->
            <div class="section-title">INCIDENT DETAILS</div>
            <table class="info-table">
                <tr>
                    <td class="label">Reference Number</td>
                    <td class="value"><strong><?php echo e($incident->reference_number); ?></strong></td>
                </tr>
                <tr>
                    <td class="label">Full Name</td>
                    <td class="value"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                </tr>
                <tr>
                    <td class="label">National ID</td>
                    <td class="value"><?php echo e($incident->id_number); ?></td>
                </tr>
                <tr>
                    <td class="label">Department</td>
                    <td class="value"><?php echo e($incident->department?->name ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Facility</td>
                    <td class="value"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Incident Date</td>
                    <td class="value"><?php echo e($incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('d M Y') : 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Incident Type</td>
                    <td class="value"><?php echo e($incident->incidentType?->name ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Submission Date</td>
                    <td class="value"><?php echo e($incident->created_at ? $incident->created_at->format('d M Y H:i') : now()->format('d M Y H:i')); ?></td>
                </tr>
            </table>

            <!-- Status -->
            <div class="status-box">
                <strong>Current Status:</strong> Pending Supervisor Verification
                <br>Your report has been submitted and is awaiting verification from your supervisor.
            </div>

            <!-- Witness Statements Section -->
            <?php if($witnessTokens->count() > 0): ?>
            <div class="section-title">WITNESS STATEMENT SUBMISSION</div>
            <div style="font-size: 13px; color: #333; margin-bottom: 15px;">
                The following witness(es) need to submit their statements. Share these links with them:
            </div>
            <div class="witness-section">
                <?php $__currentLoopData = $witnessTokens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $witness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="witness-item">
                    <div class="witness-name">Witness <?php echo e($index + 1); ?>: <?php echo e($witness->witness_name); ?></div>
                    <div style="font-size: 12px; color: #666; margin-bottom: 8px;">
                        Phone: <?php echo e($witness->phone_number); ?>

                    </div>
                    <a href="<?php echo e(route('incidents.witness_statement', ['token' => $witness->token])); ?>" class="witness-link">
                        Submit Statement
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <!-- Track Your Report -->
            <div style="text-align: center; margin: 25px 0;">
                <a href="<?php echo e(route('incidents.track', ['reference' => $incident->reference_number])); ?>" class="button">
                    Track Your Report Status
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="text-align: center; margin: 0;">
                This is an automated email from the HSC Occupational Health & Safety System.<br>
                Please do not reply to this email. Use your reference number for all inquiries.
            </p>
            <p style="text-align: center; margin: 10px 0 0 0; font-size: 11px; color: #999;">
                Health Service Commission © 2026. All rights reserved.
            </p>
        </div>

        <div class="bottom-border"></div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\emails\incident-confirmation.blade.php ENDPATH**/ ?>