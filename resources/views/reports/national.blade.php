<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>National Reports · Reports & Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; }
        .stat-card p { font-size: 11px; color: #64748b; margin-bottom: 4px; }
        .stat-card strong { font-size: 24px; font-weight: 700; display: block; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 2rem; }
        .report-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; }
        .report-section h3 { font-size: 14px; font-weight: 700; margin-bottom: 1rem; }
        .report-section p { font-size: 12px; color: #64748b; margin-bottom: 0.5rem; }
        .report-section strong { color: #1D4533; }
        .btn-export { background: #1D4533; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; margin-top: 1rem; }
        .btn-export:hover { background: #163a2a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-file-chart-line"></i> National Reports</h1>
                <p>Comprehensive incident system statistics and analysis</p>
            </div>
            <button class="btn-export"><i class="fa-solid fa-file-export"></i> Export Report</button>
        </div>

        <div class="stats">
            <div class="stat-card">
                <p>Total Cases</p>
                <strong>{{ number_format($statistics['total_cases']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Total Users</p>
                <strong>{{ number_format($statistics['total_users']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Total Provinces</p>
                <strong>{{ number_format($statistics['total_provinces']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Total Facilities</p>
                <strong>{{ number_format($statistics['total_facilities']) }}</strong>
            </div>
        </div>

        <div class="stats-grid">
            <div class="report-section">
                <h3><i class="fa-solid fa-spinner"></i> Workflow Status Summary</h3>
                <p>Pending Cases: <strong>{{ number_format($statistics['pending_cases']) }}</strong></p>
                <p>In Review: <strong>{{ number_format($statistics['in_review_cases']) }}</strong></p>
                <p>Resolved Cases: <strong>{{ number_format($statistics['resolved_cases']) }}</strong></p>
                <p>Rejected Cases: <strong>{{ number_format($statistics['rejected_cases']) }}</strong></p>
            </div>

            <div class="report-section">
                <h3><i class="fa-solid fa-chart-pie"></i> System Coverage</h3>
                <p>National Coverage: <strong>{{ $statistics['total_provinces'] }} Provinces</strong></p>
                <p>Facility Network: <strong>{{ number_format($statistics['total_facilities']) }} Facilities</strong></p>
                <p>Active Users: <strong>{{ number_format($statistics['total_users']) }}</strong></p>
                <p>Resolution Rate: <strong>{{ $statistics['total_cases'] > 0 ? round(($statistics['resolved_cases'] / $statistics['total_cases']) * 100, 1) : 0 }}%</strong></p>
            </div>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-download"></i> Export Options</h3>
            <p>Generate detailed reports in your preferred format:</p>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button class="btn-export" style="background: #3b82f6;"><i class="fa-solid fa-file-csv"></i> CSV Format</button>
                <button class="btn-export" style="background: #10b981;"><i class="fa-solid fa-file-excel"></i> Excel Format</button>
                <button class="btn-export" style="background: #ef4444;"><i class="fa-solid fa-file-pdf"></i> PDF Report</button>
            </div>
        </div>
    </div>
</body>
</html>
