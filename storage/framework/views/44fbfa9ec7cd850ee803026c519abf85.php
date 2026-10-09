<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verification Rate · Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { 
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-content { flex: 1; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 2px solid #1D4533;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            white-space: nowrap;
        }
        .back-button:hover {
            background: #1D4533;
            color: white;
            transform: translateX(-2px);
        }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 2rem; }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; border-left: 4px solid #1D4533; }
        .stat-card p { font-size: 10px; color: #64748b; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        .stat-card strong { font-size: 20px; font-weight: 700; display: block; }
        .report-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; }
        .report-section h3 { font-size: 14px; font-weight: 700; margin-bottom: 1rem; }
        .report-section p { font-size: 12px; color: #64748b; line-height: 1.6; }
        .metric-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e2e8f0; }
        .metric-row:last-child { border-bottom: none; }
        .metric-label { font-size: 13px; font-weight: 600; }
        .metric-value { font-size: 20px; font-weight: 700; color: #1D4533; }
        .progress-bar { background: #e2e8f0; height: 12px; border-radius: 6px; margin: 0.5rem 0; overflow: hidden; }
        .progress-fill { background: #1D4533; height: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1><i class="fa-solid fa-percent"></i> Verification Rate Report</h1>
                <p>Your case verification performance metrics</p>
            </div>
            <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="back-button">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <p>Verification Rate</p>
                <strong><?php echo e($statistics['verification_rate']); ?>%</strong>
            </div>
            <div class="stat-card">
                <p>Total Assigned</p>
                <strong><?php echo e($statistics['total_assigned']); ?></strong>
            </div>
            <div class="stat-card">
                <p>Verified</p>
                <strong><?php echo e($statistics['verified']); ?></strong>
            </div>
            <div class="stat-card">
                <p>Pending</p>
                <strong><?php echo e($statistics['pending']); ?></strong>
            </div>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-chart-pie"></i> Performance Breakdown</h3>
            
            <div class="metric-row">
                <div>
                    <div class="metric-label">Verified Cases</div>
                    <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Successfully verified incidents</p>
                </div>
                <div style="text-align: right;">
                    <div class="metric-value" style="color: #10b981;"><?php echo e($statistics['verified']); ?></div>
                </div>
            </div>

            <div class="metric-row">
                <div>
                    <div class="metric-label">Pending Cases</div>
                    <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Awaiting verification action</p>
                </div>
                <div style="text-align: right;">
                    <div class="metric-value" style="color: #dc2626;"><?php echo e($statistics['pending']); ?></div>
                </div>
            </div>

            <div class="metric-row">
                <div>
                    <div class="metric-label">Rejected Cases</div>
                    <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Cases rejected during verification</p>
                </div>
                <div style="text-align: right;">
                    <div class="metric-value" style="color: #6b7280;"><?php echo e($statistics['rejected']); ?></div>
                </div>
            </div>

            <div class="metric-row">
                <div>
                    <div class="metric-label">Overall Rate</div>
                    <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Percentage of verified cases</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo e($statistics['verification_rate']); ?>%"></div>
                    </div>
                </div>
                <div style="text-align: right; font-size: 18px; font-weight: 700;"><?php echo e($statistics['verification_rate']); ?>%</div>
            </div>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-info-circle"></i> Performance Notes</h3>
            <p>
                This report shows your verification performance. A higher verification rate indicates that you're actively working through your assigned cases. 
                Focus on reducing the number of pending cases to improve your overall performance metrics.
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\supervisor\verification_rate.blade.php ENDPATH**/ ?>