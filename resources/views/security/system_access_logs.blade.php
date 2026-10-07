<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>System Access Logs · Security & Audit</title>
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
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; border-left: 4px solid #1D4533; }
        .stat-card p { font-size: 11px; color: #64748b; margin-bottom: 4px; }
        .stat-card strong { font-size: 24px; font-weight: 700; display: block; }
        .filter-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; }
        .filter-group { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1rem; }
        .filter-item label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
        .filter-item input, .filter-item select { width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; }
        .btn-primary { background: #1D4533; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .btn-primary:hover { background: #163a2a; }
        .report-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; }
        .report-section h3 { font-size: 14px; font-weight: 700; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        table thead th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; text-align: left; font-size: 12px; font-weight: 600; }
        table tbody td { border: 1px solid #e2e8f0; padding: 12px; font-size: 12px; }
        table tbody tr:hover { background: #f8fafc; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .status-allowed { background: #d1f2eb; color: #0f766e; }
        .status-denied { background: #fee2e2; color: #991b1b; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 1.5rem; }
        .pagination button { padding: 6px 12px; border: 1px solid #e2e8f0; background: white; cursor: pointer; border-radius: 4px; font-size: 12px; }
        .pagination button.active { background: #1D4533; color: white; border-color: #1D4533; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-shield-alt"></i> System Access Logs</h1>
                <p>Monitor system access, permissions, and security events</p>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <p>Total Access Events</p>
                <strong>{{ number_format($statistics['total_events']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Allowed Access</p>
                <strong>{{ number_format($statistics['allowed']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Denied Access</p>
                <strong>{{ number_format($statistics['denied']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Last Updated</p>
                <strong>{{ $statistics['last_updated'] }}</strong>
            </div>
        </div>

        <div class="filter-section">
            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 1rem;"><i class="fa-solid fa-filter"></i> Filter Access Events</h3>
            <div class="filter-group">
                <div class="filter-item">
                    <label>Resource Type</label>
                    <select>
                        <option>All Resources</option>
                        <option>Dashboard</option>
                        <option>Incident Records</option>
                        <option>User Management</option>
                        <option>System Settings</option>
                        <option>Reports</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Access Status</label>
                    <select>
                        <option>All</option>
                        <option>Allowed</option>
                        <option>Denied</option>
                        <option>Conditional</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Date Range</label>
                    <input type="date">
                </div>
                <div class="filter-item">
                    <label style="opacity: 0;">Action</label>
                    <button class="btn-primary"><i class="fa-solid fa-search"></i> Search</button>
                </div>
            </div>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-table"></i> Access Events Log</h3>
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Resource</th>
                        <th>Permission</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accessLogs as $log)
                        <tr>
                            <td>{{ $log['timestamp']->format('M d, Y H:i') }}</td>
                            <td>{{ $log['name'] }}</td>
                            <td>{{ $log['resource'] }}</td>
                            <td>{{ $log['permission'] }}</td>
                            <td><span class="status-badge status-allowed">{{ $log['status'] }}</span></td>
                            <td><span style="background: #e6e6fa; color: #6b21a8; padding: 2px 8px; border-radius: 3px; font-size: 11px;">{{ $log['role'] }}</span></td>
                            <td>{{ $log['ip_address'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b;">No access logs available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>Next</button>
            </div>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-info-circle"></i> Access Control Information</h3>
            <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                System access logs record all attempts to access system resources and features based on user roles and permissions. 
                Each entry shows the timestamp, user identity, resource being accessed, required permission, access outcome (allowed/denied), 
                user role, and originating IP address. This audit trail is critical for security monitoring, compliance verification, and 
                identifying unauthorized access attempts. Denied access attempts are flagged for investigation.
            </p>
        </div>
    </div>
</body>
</html>
