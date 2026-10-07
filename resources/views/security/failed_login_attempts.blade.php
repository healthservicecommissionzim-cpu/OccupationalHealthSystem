<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Failed Login Attempts · Security & Audit</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .alert-banner { background: #fee2e2; border-left: 4px solid #dc2626; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; }
        .alert-banner strong { color: #991b1b; }
        .alert-banner p { font-size: 13px; color: #7f1d1d; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; border-left: 4px solid #dc2626; }
        .stat-card p { font-size: 11px; color: #64748b; margin-bottom: 4px; }
        .stat-card strong { font-size: 24px; font-weight: 700; display: block; color: #dc2626; }
        .filter-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; }
        .filter-group { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1rem; }
        .filter-item label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
        .filter-item input, .filter-item select { width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; }
        .btn-primary { background: #1D4533; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .btn-primary:hover { background: #163a2a; }
        .btn-danger { background: #dc2626; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; }
        .btn-danger:hover { background: #991b1b; }
        .report-section { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; }
        .report-section h3 { font-size: 14px; font-weight: 700; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        table thead th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; text-align: left; font-size: 12px; font-weight: 600; }
        table tbody td { border: 1px solid #e2e8f0; padding: 12px; font-size: 12px; }
        table tbody tr:hover { background: #f8fafc; }
        .severity-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .severity-low { background: #fef3c7; color: #92400e; }
        .severity-medium { background: #fed7aa; color: #9a3412; }
        .severity-high { background: #fecaca; color: #7f1d1d; }
        .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 1.5rem; }
        .pagination button { padding: 6px 12px; border: 1px solid #e2e8f0; background: white; cursor: pointer; border-radius: 4px; font-size: 12px; }
        .pagination button.active { background: #dc2626; color: white; border-color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-lock"></i> Failed Login Attempts</h1>
                <p>Security monitoring of authentication failures and suspicious activities</p>
            </div>
            <button class="btn-danger"><i class="fa-solid fa-ban"></i> Block Suspicious IPs</button>
        </div>

        <div class="alert-banner">
            <strong>Security Alert:</strong> <p style="margin-top: 4px;">Monitor failed login attempts to identify potential security threats and unauthorized access attempts. Repeated failures from the same IP may indicate brute-force attacks.</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <p>Failed Attempts (24h)</p>
                <strong>{{ $statistics['failed_24h'] }}</strong>
            </div>
            <div class="stat-card">
                <p>Unique IP Addresses</p>
                <strong>{{ $statistics['unique_ips'] }}</strong>
            </div>
            <div class="stat-card">
                <p>Blocked IPs</p>
                <strong>{{ $statistics['blocked_ips'] }}</strong>
            </div>
            <div class="stat-card">
                <p>Last Updated</p>
                <strong>{{ $statistics['last_updated'] }}</strong>
            </div>
        </div>

        <div class="filter-section">
            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 1rem;"><i class="fa-solid fa-filter"></i> Filter Attempts</h3>
            <div class="filter-group">
                <div class="filter-item">
                    <label>Username/Email</label>
                    <input type="text" placeholder="Search username or email">
                </div>
                <div class="filter-item">
                    <label>IP Address</label>
                    <input type="text" placeholder="Enter IP address">
                </div>
                <div class="filter-item">
                    <label>Severity</label>
                    <select>
                        <option>All</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label style="opacity: 0;">Action</label>
                    <button class="btn-primary"><i class="fa-solid fa-search"></i> Search</button>
                </div>
            </div>
        </div>

        <div class="report-section">
            <h3><i class="fa-solid fa-table"></i> Failed Login Attempts</h3>
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Username</th>
                        <th>IP Address</th>
                        <th>Failure Reason</th>
                        <th>Attempt #</th>
                        <th>Severity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedAttempts as $attempt)
                        <tr>
                            <td>{{ $attempt['timestamp']->format('M d, Y H:i') }}</td>
                            <td>{{ $attempt['email'] }}</td>
                            <td>{{ $attempt['ip_address'] }}</td>
                            <td>{{ $attempt['reason'] }}</td>
                            <td>{{ $attempt['attempts'] }}</td>
                            <td>
                                @if($attempt['severity'] === 'High')
                                    <span class="severity-badge severity-high">High</span>
                                @elseif($attempt['severity'] === 'Medium')
                                    <span class="severity-badge severity-medium">Medium</span>
                                @else
                                    <span class="severity-badge severity-low">Low</span>
                                @endif
                            </td>
                            <td><a href="#" style="color: {{ $attempt['severity'] === 'High' ? '#dc2626' : '#1D4533' }}; text-decoration: underline;">{{ $attempt['severity'] === 'High' ? 'Block IP' : 'View' }}</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b;">No failed login attempts</td>
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
            <h3><i class="fa-solid fa-info-circle"></i> Security Recommendations</h3>
            <ul style="font-size: 12px; color: #64748b; line-height: 1.8; list-style: disc; padding-left: 1.5rem;">
                <li>Monitor failed login attempts regularly to identify suspicious patterns</li>
                <li>Block IP addresses showing repeated failed attempts (after threshold is exceeded)</li>
                <li>Enforce strong password policies and multi-factor authentication (MFA) for all users</li>
                <li>Implement rate limiting to prevent brute-force attacks</li>
                <li>Alert administrators immediately when high-severity attempts are detected</li>
                <li>Review and reset passwords for accounts with multiple failed attempts</li>
                <li>Maintain audit logs for compliance and forensic analysis</li>
            </ul>
        </div>
    </div>
</body>
</html>
