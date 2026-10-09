<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Export Data · Reports & Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; border-left: 4px solid #1D4533; }
        .stat-card p { font-size: 11px; color: #64748b; margin-bottom: 4px; }
        .stat-card strong { font-size: 24px; font-weight: 700; display: block; }
        .export-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .export-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s ease; }
        .export-card:hover { border-color: #1D4533; box-shadow: 0 2px 8px rgba(29, 69, 51, 0.1); }
        .export-card i { font-size: 32px; color: #1D4533; margin-bottom: 1rem; display: block; }
        .export-card h3 { font-size: 14px; font-weight: 700; margin-bottom: 8px; }
        .export-card p { font-size: 12px; color: #64748b; margin-bottom: 1rem; }
        .btn-primary { background: #1D4533; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .btn-primary:hover { background: #163a2a; }
        .report-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; }
        .report-section h3 { font-size: 14px; font-weight: 700; margin-bottom: 1rem; }
        .report-section p { font-size: 12px; color: #64748b; line-height: 1.6; }
        .filter-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; }
        .filter-group { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem; }
        .filter-item label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
        .filter-item select { width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table thead th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; text-align: left; font-size: 12px; font-weight: 600; }
        table tbody td { border: 1px solid #e2e8f0; padding: 12px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-file-export"></i> Export Data</h1>
                <p>Download incident data in your preferred format</p>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <p>Total Cases</p>
                <strong><?php echo e(number_format($statistics['total_cases'])); ?></strong>
            </div>
            <div class="stat-card">
                <p>Total Users</p>
                <strong><?php echo e(number_format($statistics['total_users'])); ?></strong>
            </div>
            <div class="stat-card">
                <p>Last Export</p>
                <strong><?php echo e($statistics['last_export']); ?></strong>
            </div>
        </div>

        <div class="export-grid">
            <div class="export-card">
                <i class="fa-solid fa-file-csv"></i>
                <h3>CSV Format</h3>
                <p>Comma-separated values for spreadsheet applications</p>
                <button class="btn-primary"><i class="fa-solid fa-download"></i> Export CSV</button>
            </div>
            <div class="export-card">
                <i class="fa-solid fa-file-excel"></i>
                <h3>Excel Format</h3>
                <p>Microsoft Excel workbook with charts and analysis</p>
                <button class="btn-primary"><i class="fa-solid fa-download"></i> Export Excel</button>
            </div>
            <div class="export-card">
                <i class="fa-solid fa-file-pdf"></i>
                <h3>PDF Report</h3>
                <p>Professional PDF report for sharing and archival</p>
                <button class="btn-primary"><i class="fa-solid fa-download"></i> Export PDF</button>
            </div>
        </div>

        <div class="filter-section">
            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 1rem;"><i class="fa-solid fa-filter"></i> Filter Data</h3>
            <div class="filter-group">
                <div class="filter-item">
                    <label>Date Range</label>
                    <select>
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                        <option>Last Year</option>
                        <option>All Time</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Status</label>
                    <select>
                        <option>All Statuses</option>
                        <option>Active</option>
                        <option>Resolved</option>
                        <option>Rejected</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Data Type</label>
                    <select>
                        <option>All Data</option>
                        <option>Incidents Only</option>
                        <option>Users Only</option>
                        <option>Statistics Only</option>
                    </select>
                </div>
            </div>
            <button class="btn-primary"><i class="fa-solid fa-download"></i> Apply Filters & Export</button>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-info-circle"></i> Export Guidelines</h3>
            <p>
                <strong>CSV Format:</strong> Best for data analysis in Excel, Google Sheets, or Python/R tools. Includes all incident records with basic information.<br><br>
                <strong>Excel Format:</strong> Professional spreadsheet with multiple sheets including incidents, users, and statistical summaries with pre-built charts.<br><br>
                <strong>PDF Report:</strong> Formatted report suitable for printing, sharing with stakeholders, and archival purposes. Includes comprehensive analysis and visual charts.<br><br>
                All exports include filtering options to customize data according to your requirements. Sensitive information is handled according to organizational policies.
            </p>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-history"></i> Recent Exports</h3>
            <table>
                <thead>
                    <tr>
                        <th>Export Date</th>
                        <th>Format</th>
                        <th>Records</th>
                        <th>File Size</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e(now()->format('M d, Y H:i')); ?></td>
                        <td>CSV</td>
                        <td><?php echo e(number_format($statistics['total_cases'])); ?></td>
                        <td>~2.4 MB</td>
                        <td><span style="color: #10b981; font-weight: 600;">Completed</span></td>
                    </tr>
                    <tr>
                        <td><?php echo e(now()->subDay()->format('M d, Y H:i')); ?></td>
                        <td>PDF</td>
                        <td><?php echo e(number_format($statistics['total_cases'])); ?></td>
                        <td>~5.1 MB</td>
                        <td><span style="color: #10b981; font-weight: 600;">Completed</span></td>
                    </tr>
                    <tr>
                        <td><?php echo e(now()->subDays(2)->format('M d, Y H:i')); ?></td>
                        <td>Excel</td>
                        <td><?php echo e(number_format($statistics['total_cases'])); ?></td>
                        <td>~3.8 MB</td>
                        <td><span style="color: #10b981; font-weight: 600;">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\reports\export_data.blade.php ENDPATH**/ ?>