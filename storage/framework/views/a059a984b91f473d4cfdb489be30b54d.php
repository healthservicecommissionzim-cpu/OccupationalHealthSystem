<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Incident Confirmation - <?php echo e($incident->reference_number); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            position: relative;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }
        .watermark img {
            width: 350px;
            height: auto;
        }
        .content {
            position: relative;
            z-index: 1;
        }
        .header {
            border-top: 5px solid #1D4533;
            border-bottom: 3px solid #D4A574;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            height: 80px;
            margin-bottom: 15px;
        }
        .hsc-title {
            color: #1D4533;
            font-size: 22px;
            font-weight: bold;
            margin: 10px 0;
        }
        .ref-info {
            text-align: right;
            margin-top: 15px;
            font-size: 11px;
            float: right;
            position: absolute;
            right: 30px;
            top: 30px;
        }
        .ref-info div {
            margin: 3px 0;
        }
        .section-title {
            background: #1D4533;
            color: white;
            padding: 10px 12px;
            margin-top: 20px;
            margin-bottom: 12px;
            font-weight: bold;
            font-size: 12px;
        }
        .greeting {
            font-size: 13px;
            margin-bottom: 12px;
            margin-top: 20px;
        }
        .section-content {
            font-size: 12px;
            margin-bottom: 12px;
            line-height: 1.7;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .info-table tr {
            border-bottom: 1px solid #ddd;
        }
        .info-table td {
            padding: 10px;
        }
        .info-table .label {
            font-weight: bold;
            width: 35%;
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
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }
        .footer-text {
            text-align: center;
            margin-bottom: 40px;
        }
        .signature-block {
            margin-top: 50px;
            text-align: left;
        }
        .signature-block strong {
            display: block;
            margin-top: 30px;
        }
        .bottom-border {
            border-bottom: 3px solid #1D4533;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        <img src="https://injury.hsc.org.zw/images/hsc-logo.png" alt="HSC Watermark">
    </div>

    <div class="container">
        <div class="content">
            <!-- Header -->
            <div class="header">
                <img src="https://injury.hsc.org.zw/images/hsc-logo.png" alt="HSC Logo" class="logo">
                <div class="hsc-title">HEALTH SERVICE COMMISSION</div>
                <h2 style="margin-top: 15px; color: #1D4533; font-size: 16px;">INCIDENT REPORT CONFIRMATION</h2>
                <div class="ref-info">
                    <div><strong>Reference:</strong> <?php echo e($incident->reference_number); ?></div>
                    <div><strong>Date:</strong> <?php echo e(now()->format('d M Y')); ?></div>
                </div>
            </div>

            <!-- Content -->
            <div class="greeting">
                Dear <?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?>,
            </div>

            <div class="section-content">
                The Health Service Commission acknowledges receipt of your incident report submitted on 
                <strong><?php echo e($incident->created_at ? $incident->created_at->format('d M Y') : now()->format('d M Y')); ?></strong>. 
                This letter confirms that your report has been officially registered in our system and is being processed.
            </div>

            <!-- Incident Details -->
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
                    <td class="label">National ID / EC Number</td>
                    <td class="value"><?php echo e($incident->id_number); ?></td>
                </tr>
                <tr>
                    <td class="label">Department</td>
                    <td class="value"><?php echo e($incident->department?->name ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Designation</td>
                    <td class="value"><?php echo e($incident->designation?->name ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Facility / Hospital</td>
                    <td class="value"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td class="label">Province</td>
                    <td class="value"><?php echo e($incident->province?->name ?? 'N/A'); ?></td>
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
                    <td class="label">Status</td>
                    <td class="value"><strong>Pending Supervisor Verification</strong></td>
                </tr>
            </table>

            <!-- Status -->
            <div class="status-box">
                ✓ CONFIRMED - Your incident report has been officially registered and is under review
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="signature-block">
                    <strong>HEALTH SERVICE COMMISSION</strong>
                    <strong>Occupational Health & Safety Unit</strong>
                </div>

                <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #999;">
                    <p>This is an official document of the Health Service Commission</p>
                    <p>Document Reference: <?php echo e($incident->reference_number); ?> | Generated: <?php echo e(now()->format('d M Y H:i')); ?></p>
                </div>
            </div>

            <div class="bottom-border"></div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\pdfs\incident-confirmation.blade.php ENDPATH**/ ?>