{{-- Modern Database Backup Interface - Updated 2026-11-04 --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Database Backup · System Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --ink: #14231c;
            --ink-soft: #52605a;
            --muted: #7f8b85;
            --line: #e6ebe8;
            --surface: #ffffff;
            --canvas: #f5f7f6;
            --brand: #1d4533;
            --brand-deep: #103022;
            --brand-soft: #eaf4ee;
            --success: #218653;
            --success-soft: #e8f7ef;
            --warning: #a86d0a;
            --warning-soft: #fff6df;
            --danger: #b33a42;
            --danger-soft: #fff0f1;
            --blue: #356bb3;
            --blue-soft: #edf4ff;
            --shadow: 0 18px 48px rgba(25, 46, 35, .07), 0 2px 8px rgba(25, 46, 35, .03);
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
            --ease: 180ms ease;
        }

        * { box-sizing: border-box; }
        html { background: var(--canvas); }
        body {
            margin: 0;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 0%, rgba(212, 235, 220, .55), transparent 30rem),
                var(--canvas);
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.5;
        }
        a { color: inherit; }
        button, input, select, textarea { font: inherit; }

        .shell {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
            padding: 16px 0 32px;
        }

        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .breadcrumb { display: flex; align-items: center; gap: 10px; color: var(--muted); font-size: .83rem; font-weight: 600; }
        .breadcrumb a { text-decoration: none; transition: color var(--ease); }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb i { font-size: .65rem; color: #b6c0ba; }
        .back-link { display: inline-flex; align-items: center; gap: 9px; padding: 10px 15px; border: 1px solid var(--line); border-radius: var(--radius-sm); background: rgba(255,255,255,.72); color: var(--ink-soft); text-decoration: none; font-size: .84rem; font-weight: 700; transition: all var(--ease); }
        .back-link:hover { border-color: #c9d8ce; color: var(--brand); background: var(--surface); transform: translateY(-1px); }

        .alert { display: flex; align-items: flex-start; gap: 12px; margin: 0 0 20px; padding: 14px 16px; border: 1px solid; border-radius: var(--radius-md); font-size: .88rem; font-weight: 600; }
        .alert i { margin-top: 2px; }
        .alert.success { border-color: #bde7cd; background: var(--success-soft); color: #17663f; }
        .alert.error { border-color: #f3c5c9; background: var(--danger-soft); color: #96343d; }

        .profile-hero { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 18px; margin-bottom: 16px; border: 1px solid #dce7df; border-radius: var(--radius-lg); background: linear-gradient(120deg, #ffffff 0%, #f8fcf9 100%); box-shadow: var(--shadow); }
        .identity { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .avatar { display: grid; place-items: center; flex: 0 0 48px; width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(145deg, var(--brand), #397557); color: white; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 800; box-shadow: 0 10px 20px rgba(29,69,51,.18); }
        .eyebrow { margin: 0 0 2px; color: var(--brand); font-size: .65rem; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1 { margin: 0; font-size: clamp(1.1rem, 2vw, 1.4rem); line-height: 1.15; letter-spacing: -.035em; }
        .hero-meta { margin: 3px 0 0; color: var(--ink-soft); font-size: .8rem; }
        .hero-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .stat-box { text-align: center; padding: 10px; border-radius: var(--radius-sm); background: var(--brand-soft); }
        .stat-label { margin: 0 0 3px; color: var(--muted); font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; }
        .stat-value { margin: 0; color: var(--brand); font-size: 1.3rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; }

        .layout { display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(320px, 1fr); align-items: start; gap: 16px; }
        .main-column { display: flex; flex-direction: column; gap: 16px; }
        .side-column { display: flex; flex-direction: column; gap: 16px; height: 100%; }
        .card { overflow: hidden; border: 1px solid var(--line); border-radius: var(--radius-lg); background: var(--surface); box-shadow: var(--shadow); display: flex; flex-direction: column; }
        .card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 14px 16px 12px; border-bottom: 1px solid var(--line); }
        .card-heading { display: flex; align-items: flex-start; gap: 10px; }
        .section-icon { display: grid; place-items: center; flex: 0 0 28px; width: 28px; height: 28px; border-radius: 8px; background: var(--brand-soft); color: var(--brand); font-size: .75rem; }
        .card-header h2 { margin: 0; font-size: .9rem; letter-spacing: -.025em; }
        .card-header p { margin: 2px 0 0; color: var(--muted); font-size: .7rem; }
        .count { color: var(--muted); font-size: .78rem; font-weight: 700; white-space: nowrap; }
        .card-body { padding: 14px 16px; display: flex; flex-direction: column; gap: 12px; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 9px 14px; border: 0; border-radius: var(--radius-sm); cursor: pointer; font-size: .78rem; font-weight: 700; transition: all var(--ease); text-decoration: none; }
        .btn-primary { background: var(--brand); color: white; box-shadow: 0 7px 15px rgba(29,69,51,.16); }
        .btn-primary:hover { background: var(--brand-deep); transform: translateY(-1px); box-shadow: 0 10px 20px rgba(29,69,51,.22); }
        .btn-secondary { background: var(--blue-soft); color: var(--blue); }
        .btn-secondary:hover { background: #d8e8ff; transform: translateY(-1px); }
        .btn-danger { background: var(--danger-soft); color: var(--danger); border: 1px solid #f3c5c9; }
        .btn-danger:hover { background: #ffe3e5; border-color: #e9a8ae; transform: translateY(-1px); }
        .btn-full { width: 100%; }

        .backup-table { width: 100%; border-collapse: collapse; }
        .backup-table th { padding: 10px 12px; text-align: left; color: var(--muted); font-size: .65rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; border-bottom: 1px solid var(--line); background: #f8faf9; }
        .backup-table td { padding: 12px; border-bottom: 1px solid var(--line); font-size: .8rem; }
        .backup-table tbody tr:hover { background: #fafcfb; }
        .backup-table tbody tr:last-child td { border-bottom: 0; }
        .backup-file { display: flex; align-items: center; gap: 10px; }
        .file-icon { display: grid; place-items: center; width: 32px; height: 32px; border-radius: 8px; background: var(--brand-soft); color: var(--brand); font-size: .75rem; }
        .file-name { font-weight: 600; color: var(--ink); }
        .file-meta { margin-top: 2px; color: var(--muted); font-size: .7rem; }
        .actions { display: flex; gap: 6px; justify-content: flex-end; }
        .actions .btn { padding: 6px 10px; font-size: .72rem; }

        .info-box { padding: 12px; border-radius: var(--radius-md); background: var(--warning-soft); border: 1px solid #f5e5b7; }
        .info-box strong { display: block; margin-bottom: 4px; color: var(--warning); font-size: .78rem; }
        .info-box p { margin: 0; color: #7a5e0c; font-size: .75rem; line-height: 1.6; }

        .tip-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 10px; }
        .tip-list li { display: flex; align-items: flex-start; gap: 8px; font-size: .75rem; color: var(--ink-soft); line-height: 1.5; }
        .tip-list i { margin-top: 2px; color: var(--brand); font-size: .7rem; }

        .empty-state { padding: 24px; text-align: center; color: var(--muted); }
        .empty-state i { display: block; margin-bottom: 8px; color: #c3cec7; font-size: 2rem; }
        .empty-state p { margin: 0; font-size: .8rem; }

        @media (max-width: 768px) {
            .layout { grid-template-columns: 1fr; }
            .hero-stats { grid-template-columns: 1fr; }
            .backup-table { font-size: .75rem; }
            .backup-table th, .backup-table td { padding: 8px; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <!-- Topbar -->
        <div class="topbar">
            <div class="breadcrumb">
                <a href="{{ route('dashboards.super_admin') }}">Dashboard</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span>Database Backup</span>
            </div>
            <a href="{{ route('dashboards.super_admin') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="alert success">
            <i class="fa-solid fa-circle-check"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Error!</strong> {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Hero Section -->
        <div class="profile-hero">
            <div class="identity">
                <div class="avatar">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <div class="eyebrow">System Management</div>
                    <h1>Database Backup</h1>
                    <div class="hero-meta">Create and manage database backups</div>
                </div>
            </div>
            <div class="hero-stats">
                <div class="stat-box">
                    <div class="stat-label">Total Backups</div>
                    <div class="stat-value">{{ count($backups) }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Latest</div>
                    <div class="stat-value" style="font-size: .9rem;">{{ count($backups) ? $backups[0]['date'] : 'None' }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Status</div>
                    <div class="stat-value" style="font-size: .8rem; color: var(--success);">Ready</div>
                </div>
            </div>
        </div>

        <!-- Layout -->
        <div class="layout">
            <!-- Main Column -->
            <div class="main-column">
                <!-- Create Backup Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-cloud-arrow-down"></i>
                            </div>
                            <div>
                                <h2>Create New Backup</h2>
                                <p>Generate a fresh database backup file</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('system.database_backup.create') }}" method="POST" id="backupForm">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-full" id="createBackupButton">
                                <i class="fa-solid fa-plus"></i>
                                <span>Create Backup Now</span>
                            </button>
                        </form>
                        <div class="info-box">
                            <strong><i class="fa-solid fa-lightbulb"></i> Best Practices</strong>
                            <p>Create a backup before major updates. Keep a secure off-server copy, restrict access to authorized staff, and test restoration periodically.</p>
                        </div>
                    </div>
                </div>

                <!-- Backup History Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <h2>Backup History</h2>
                                <p>Download or remove existing backup files</p>
                            </div>
                        </div>
                        <span class="count">{{ count($backups) }} {{ count($backups) === 1 ? 'backup' : 'backups' }}</span>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @if(count($backups) > 0)
                        <table class="backup-table">
                            <thead>
                                <tr>
                                    <th>Backup File</th>
                                    <th>Created</th>
                                    <th>Size</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                <tr>
                                    <td>
                                        <div class="backup-file">
                                            <div class="file-icon">
                                                <i class="fa-solid fa-file-zipper"></i>
                                            </div>
                                            <div>
                                                <div class="file-name">{{ $backup['filename'] }}</div>
                                                <div class="file-meta">Compressed database archive</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td>{{ $backup['size'] }}</td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('system.database_backup.download', $backup['filename']) }}" class="btn btn-secondary">
                                                <i class="fa-solid fa-download"></i> Download
                                            </a>
                                            <form action="{{ route('system.database_backup.delete', $backup['filename']) }}" method="POST" class="deleteBackupForm" data-filename="{{ $backup['filename'] }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="empty-state">
                            <i class="fa-solid fa-box-open"></i>
                            <p>No backups yet. Create your first backup to establish a recovery point.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div class="side-column">
                <!-- Checklist Card -->
                <div class="card" style="flex: 1; display: flex; flex-direction: column;">
                    <div class="card-header">
                        <div class="card-heading">
                            <div class="section-icon">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <h2>Before You Begin</h2>
                                <p>Pre-backup checklist</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="flex: 1;">
                        <div class="info-box" style="margin-bottom: 12px;">
                            <strong><i class="fa-solid fa-folder"></i> Backup Location</strong>
                            <p style="margin-top: 4px; font-family: 'Courier New', monospace; font-size: .7rem; word-break: break-all;">
                                storage/app/backups/database/
                            </p>
                        </div>
                        <ul class="tip-list">
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Confirm the application is operating normally</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Ensure server has enough free disk space</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Copy important backups to secure storage</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Test restoration periodically</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (() => {
        const form = document.getElementById('backupForm');
        const createButton = document.getElementById('createBackupButton');

        if (form && createButton) {
            form.addEventListener('submit', () => {
                createButton.disabled = true;
                createButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Creating backup…</span>';
            });
        }

        document.querySelectorAll('.deleteBackupForm').forEach(form => {
            form.addEventListener('submit', event => {
                const filename = form.dataset.filename || 'this backup';
                if (!window.confirm(`Delete "${filename}"? This action cannot be undone.`)) {
                    event.preventDefault();
                }
            });
        });
    })();
    </script>
</body>
</html>
W e d n e s d a y ,   N o v e m b e r   1 1 ,   2 0 2 6   1 0 : 5 2 : 4 1   A M 
