<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>National Reports · Reports & Analytics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1400px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; border-left: 3px solid #079b6d; }
        .stat-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; padding: 22px; }
        .card h3 { font-size: 14px; font-weight: 700; color: #101a31; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; }
        .card h3 i { color: #079b6d; font-size: 16px; }
        
        /* Report Grid */
        .report-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 22px; }
        .report-item { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 16px; }
        .report-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px; }
        .report-value { font-size: 20px; font-weight: 750; color: #079b6d; }
        
        /* Button Group */
        .btn-group { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
        .btn-export { background: #079b6d; color: white; border: none; padding: 10px 17px; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-export:hover { background: #067c58; transform: translateY(-1px); }
        .btn-export.csv { background: #3b82f6; }
        .btn-export.csv:hover { background: #2563eb; }
        .btn-export.excel { background: #10b981; }
        .btn-export.excel:hover { background: #059669; }
        .btn-export.pdf { background: #ef4444; }
        .btn-export.pdf:hover { background: #dc2626; }
        
        /* Two Column Layout */
        .two-column { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 22px; margin-bottom: 22px; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .two-column { grid-template-columns: 1fr; }
            .btn-group { flex-direction: column; }
            .btn-export { width: 100%; justify-content: center; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-file-chart-line"></i></div>
                <div>
                    <h1 class="title">National Reports</h1>
                    <div class="sub">Comprehensive incident system statistics and analysis</div>
                    <span class="case">System Overview</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Key Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number"><?php echo e(number_format($statistics['total_cases'])); ?></span>
                <div class="stat-label">Total Cases</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo e(number_format($statistics['total_users'])); ?></span>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo e(number_format($statistics['total_provinces'])); ?></span>
                <div class="stat-label">Provinces</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo e(number_format($statistics['total_facilities'])); ?></span>
                <div class="stat-label">Facilities</div>
            </div>
        </div>

        <!-- Workflow Status and System Coverage -->
        <div class="two-column">
            <div class="card">
                <h3><i class="fa-solid fa-spinner"></i> Workflow Status Summary</h3>
                <div class="report-grid">
                    <div class="report-item">
                        <div class="report-label">Pending Cases</div>
                        <div class="report-value"><?php echo e(number_format($statistics['pending_cases'])); ?></div>
                    </div>
                    <div class="report-item">
                        <div class="report-label">In Review</div>
                        <div class="report-value"><?php echo e(number_format($statistics['in_review_cases'])); ?></div>
                    </div>
                    <div class="report-item">
                        <div class="report-label">Resolved</div>
                        <div class="report-value"><?php echo e(number_format($statistics['resolved_cases'])); ?></div>
                    </div>
                    <div class="report-item">
                        <div class="report-label">Rejected</div>
                        <div class="report-value"><?php echo e(number_format($statistics['rejected_cases'])); ?></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3><i class="fa-solid fa-chart-pie"></i> System Coverage</h3>
                <div class="report-grid">
                    <div class="report-item">
                        <div class="report-label">National Coverage</div>
                        <div class="report-value"><?php echo e($statistics['total_provinces']); ?></div>
                    </div>
                    <div class="report-item">
                        <div class="report-label">Facilities</div>
                        <div class="report-value"><?php echo e(number_format($statistics['total_facilities'])); ?></div>
                    </div>
                    <div class="report-item">
                        <div class="report-label">Active Users</div>
                        <div class="report-value"><?php echo e(number_format($statistics['total_users'])); ?></div>
                    </div>
                    <div class="report-item">
                        <div class="report-label">Resolution Rate</div>
                        <div class="report-value"><?php echo e($statistics['total_cases'] > 0 ? round(($statistics['resolved_cases'] / $statistics['total_cases']) * 100, 1) : 0); ?>%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="card">
            <h3><i class="fa-solid fa-download"></i> Export Options</h3>
            <p style="font-size: 12px; color: #718096; margin-bottom: 16px;">Generate detailed reports in your preferred format:</p>
            <div class="btn-group">
                <button class="btn-export"><i class="fa-solid fa-file-export"></i> Full Report</button>
                <button class="btn-export csv"><i class="fa-solid fa-file-csv"></i> CSV Format</button>
                <button class="btn-export excel"><i class="fa-solid fa-file-excel"></i> Excel Format</button>
                <button class="btn-export pdf"><i class="fa-solid fa-file-pdf"></i> PDF Report</button>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\reports\national.blade.php ENDPATH**/ ?>