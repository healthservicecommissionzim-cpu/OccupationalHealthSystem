<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Database Backup | HSC Occupational Health System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --green-950: #102b22;
            --green-900: #163d30;
            --green-800: #1d4f3d;
            --green-700: #24664d;
            --green-100: #e4f3eb;
            --green-50: #f2faf5;
            --ink: #172b25;
            --muted: #64766f;
            --line: #e1eae5;
            --canvas: #f5f8f6;
            --white: #fff;
            --blue: #2563eb;
            --red: #b42318;
            --shadow: 0 12px 34px rgba(20, 55, 40, .06);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--ink);
            background: var(--canvas);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }
        button, input { font: inherit; }
        button:focus-visible, a:focus-visible, input:focus-visible {
            outline: 3px solid #8ac9aa;
            outline-offset: 3px;
        }
        .topbar {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 14px clamp(18px, 4vw, 54px);
            color: #fff;
            background: linear-gradient(115deg, var(--green-950), #1c4b3c 72%, #225744);
            box-shadow: 0 3px 16px rgba(15, 40, 30, .12);
        }
        .brand { display: flex; align-items: center; gap: 13px; min-width: 0; }
        .brand-mark {
            width: 43px; height: 43px; display: grid; place-items: center;
            border: 1px solid rgba(255,255,255,.24); border-radius: 13px;
            background: rgba(255,255,255,.09); font-size: 19px;
        }
        .brand-name { font-size: 16px; font-weight: 800; letter-spacing: -.3px; }
        .brand-sub { margin-top: 3px; color: #c5ddd1; font-size: 11px; }
        .topbar-tag { color: #d3e6dc; font-size: 12px; font-weight: 600; }
        .page { width: min(1240px, calc(100% - 40px)); margin: 34px auto 56px; }
        .breadcrumb {
            display: inline-flex; align-items: center; gap: 9px; margin-bottom: 20px;
            color: var(--green-800); font-size: 13px; font-weight: 700; text-decoration: none;
        }
        .breadcrumb:hover { color: var(--green-950); }
        .hero {
            position: relative; overflow: hidden; display: flex; justify-content: space-between;
            align-items: center; gap: 28px; padding: clamp(24px, 4vw, 42px);
            border: 1px solid #d9e9df; border-radius: 22px;
            background: radial-gradient(circle at 95% 10%, rgba(188, 226, 204, .55), transparent 31%),
                        linear-gradient(115deg, #fff 0%, #f2faf5 65%, #e5f4eb 100%);
            box-shadow: var(--shadow);
        }
        .hero-copy { max-width: 760px; position: relative; z-index: 1; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px; padding: 7px 11px;
            border: 1px solid #cce6d6; border-radius: 999px; background: rgba(255,255,255,.8);
            color: var(--green-800); font-size: 11px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase;
        }
        h1 { margin: 17px 0 10px; font-size: clamp(28px, 4vw, 39px); line-height: 1.15; letter-spacing: -1.3px; }
        .hero p { margin: 0; max-width: 650px; color: var(--muted); font-size: 14px; line-height: 1.8; }
        .hero-icon {
            flex: 0 0 auto; width: 104px; height: 104px; display: grid; place-items: center;
            border-radius: 28px; color: #fff; font-size: 42px;
            background: linear-gradient(145deg, #2d7a59, #174633);
            box-shadow: 0 15px 30px rgba(28, 92, 62, .22);
            transform: rotate(3deg);
        }
        .flash {
            display: flex; align-items: flex-start; gap: 12px; margin: 20px 0 0; padding: 15px 17px;
            border-radius: 13px; font-size: 13px; line-height: 1.6;
        }
        .flash strong { display: block; margin-bottom: 2px; }
        .flash-success { color: #17603c; background: #effaf3; border: 1px solid #bde8cb; }
        .flash-error { color: #9d2727; background: #fff3f2; border: 1px solid #ffd2ce; }
        .content-grid { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(280px, .75fr); gap: 22px; margin-top: 24px; align-items: start; }
        .panel {
            min-width: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 18px;
            background: var(--white); box-shadow: var(--shadow);
        }
        .panel-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; padding: 24px 25px 20px; }
        .panel-title { display: flex; align-items: center; gap: 12px; }
        .panel-title h2 { margin: 0; font-size: 17px; letter-spacing: -.35px; }
        .panel-title p { margin: 6px 0 0; color: var(--muted); font-size: 12px; line-height: 1.6; }
        .icon-tile {
            width: 44px; height: 44px; flex: 0 0 auto; display: grid; place-items: center;
            border-radius: 13px; color: var(--green-800); background: var(--green-100); font-size: 18px;
        }
        .panel-body { padding: 0 25px 25px; }
        .primary-btn, .secondary-btn, .danger-btn {
            display: inline-flex; justify-content: center; align-items: center; gap: 9px;
            min-height: 43px; padding: 11px 16px; border: 1px solid transparent; border-radius: 11px;
            font-size: 12px; font-weight: 800; text-decoration: none; cursor: pointer;
            transition: transform .18s ease, background .18s ease, box-shadow .18s ease;
        }
        .primary-btn { color: #fff; background: linear-gradient(120deg, #236849, #174633); box-shadow: 0 6px 14px rgba(29, 90, 60, .17); }
        .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 9px 18px rgba(29, 90, 60, .23); }
        .primary-btn:disabled { opacity: .72; cursor: wait; transform: none; }
        .secondary-btn { min-height: 34px; padding: 8px 11px; color: var(--blue); background: #eff6ff; border-color: #dbeafe; }
        .secondary-btn:hover { background: #dbeafe; }
        .danger-btn { min-height: 34px; padding: 8px 11px; color: var(--red); background: #fff2f1; border-color: #ffdedb; }
        .danger-btn:hover { background: #ffe3e0; }
        .safety-note {
            display: flex; gap: 12px; margin-top: 22px; padding: 16px;
            border: 1px solid #f2dfae; border-radius: 13px; background: #fffbef; color: #72541b;
        }
        .safety-note > i { margin-top: 3px; color: #b98516; }
        .safety-note strong { display: block; margin-bottom: 8px; font-size: 12px; }
        .safety-note ul { margin: 0; padding-left: 17px; font-size: 11px; line-height: 1.9; }
        .history-head { padding: 24px 25px 19px; border-bottom: 1px solid var(--line); }
        .history-head h2 { margin: 0; font-size: 17px; }
        .history-head p { margin: 6px 0 0; color: var(--muted); font-size: 12px; }
        .history-count { padding: 6px 10px; border-radius: 999px; background: var(--green-50); color: var(--green-800); font-size: 11px; font-weight: 800; white-space: nowrap; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 13px 20px; background: #f8faf9; color: #78877f; font-size: 10px; letter-spacing: .7px; text-transform: uppercase; white-space: nowrap; }
        td { padding: 16px 20px; border-top: 1px solid #edf1ee; color: #52665d; font-size: 12px; vertical-align: middle; }
        tbody tr:hover { background: #fbfdfb; }
        .file-cell { display: flex; align-items: center; gap: 10px; min-width: 220px; }
        .file-icon { width: 34px; height: 34px; display: grid; place-items: center; flex: 0 0 auto; border-radius: 10px; background: #edf7f0; color: var(--green-800); }
        .file-name { max-width: 300px; overflow-wrap: anywhere; color: var(--ink); font-weight: 700; }
        .file-type { margin-top: 4px; color: #8a9991; font-size: 10px; }
        .actions { display: flex; justify-content: center; align-items: center; gap: 7px; }
        .empty { padding: 48px 24px; text-align: center; }
        .empty-icon { width: 58px; height: 58px; margin: 0 auto 15px; display: grid; place-items: center; border-radius: 18px; background: var(--green-50); color: #66947c; font-size: 23px; }
        .empty h3 { margin: 0 0 7px; font-size: 14px; }
        .empty p { margin: 0; color: var(--muted); font-size: 12px; line-height: 1.7; }
        .side-stack { display: grid; gap: 18px; }
        .status-card { padding: 21px; border-radius: 17px; background: linear-gradient(130deg, #173f30, #24674b); color: white; box-shadow: var(--shadow); }
        .status-top { display: flex; align-items: center; gap: 12px; }
        .status-top i { font-size: 21px; color: #c4ead2; }
        .status-card h3 { margin: 0; font-size: 14px; }
        .status-card p { margin: 12px 0 0; color: #d5e8dc; font-size: 12px; line-height: 1.8; }
        .side-panel { padding: 21px; }
        .side-panel h3 { display: flex; align-items: center; gap: 9px; margin: 0 0 14px; font-size: 14px; }
        .side-panel h3 i { color: var(--green-800); }
        .tip-list { display: grid; gap: 13px; margin: 0; padding: 0; list-style: none; }
        .tip-list li { display: flex; align-items: flex-start; gap: 10px; color: var(--muted); font-size: 11px; line-height: 1.7; }
        .tip-list i { margin-top: 3px; color: #39815e; }
        .help-link { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 13px; border: 1px solid var(--line); border-radius: 11px; color: var(--green-800); background: #fbfdfb; font-size: 11px; font-weight: 800; text-decoration: none; }
        .help-link:hover { border-color: #b8d8c5; background: var(--green-50); }
        .footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 30px; padding: 18px 0; border-top: 1px solid var(--line); color: #819087; font-size: 10px; }
        .footer strong { color: #50665a; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
        @media (max-width: 900px) {
            .content-grid { grid-template-columns: 1fr; }
            .side-stack { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .status-card { min-height: 100%; }
        }
        @media (max-width: 600px) {
            .topbar { min-height: 66px; padding: 12px 17px; }
            .brand-mark { width: 38px; height: 38px; }
            .brand-name { font-size: 13px; }
            .brand-sub, .topbar-tag { display: none; }
            .page { width: calc(100% - 28px); margin: 22px auto 35px; }
            .hero { padding: 23px; border-radius: 17px; }
            .hero-icon { width: 58px; height: 58px; border-radius: 17px; font-size: 24px; }
            h1 { font-size: 27px; letter-spacing: -.8px; }
            .content-grid { gap: 16px; margin-top: 17px; }
            .panel-head, .history-head { padding: 19px; }
            .panel-body { padding: 0 19px 19px; }
            .side-stack { grid-template-columns: 1fr; }
            th, td { padding: 13px 14px; }
            .footer { align-items: flex-start; flex-direction: column; }
            .create-action { width: 100%; }
            .create-action .primary-btn { width: 100%; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition: none !important; }
        }
    </style>
</head>
<body>
    <main class="page" style="margin-top: 34px;">
        <a href="{{ route('dashboards.super_admin') }}" class="breadcrumb">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Dashboard
        </a>

        <section class="hero" aria-labelledby="page-title">
            <div class="hero-copy">
                <span class="eyebrow"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Secure system operations</span>
                <h1 id="page-title">Database Backup</h1>
                <p>Create, download, and manage database backups from one secure workspace. Keep a reliable recovery point before important system changes.</p>
            </div>
            <div class="hero-icon" aria-hidden="true"><i class="fa-solid fa-database"></i></div>
        </section>

        @if(session('success'))
            <div class="flash flash-success" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <div><strong>Backup operation successful</strong>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-error" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <div><strong>Backup operation failed</strong>{{ session('error') }}</div>
            </div>
        @endif

        <div class="content-grid">
            <div>
                <section class="panel" aria-labelledby="create-title">
                    <div class="panel-head">
                        <div class="panel-title">
                            <div class="icon-tile"><i class="fa-solid fa-cloud-arrow-down" aria-hidden="true"></i></div>
                            <div>
                                <h2 id="create-title">Create a new backup</h2>
                                <p>Generate a database backup file on the server.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('system.database_backup.create') }}" method="POST" id="backupForm" class="create-action">
                            @csrf
                            <button type="submit" class="primary-btn" id="createBackupButton">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                <span>Create backup now</span>
                            </button>
                        </form>
                        <div class="safety-note">
                            <i class="fa-solid fa-lightbulb" aria-hidden="true"></i>
                            <div>
                                <strong>Backup best practices</strong>
                                <ul>
                                    <li>Create a backup before major updates or configuration changes.</li>
                                    <li>Follow a regular backup schedule appropriate to your organisation.</li>
                                    <li>Store downloaded copies in an approved, access-controlled location.</li>
                                    <li>Test restoration periodically and protect backup files as sensitive data.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="panel" style="margin-top:22px" aria-labelledby="history-title">
                    <div class="history-head" style="display:flex;align-items:center;justify-content:space-between;gap:12px">
                        <div>
                            <h2 id="history-title">Backup history</h2>
                            <p>Review and manage existing backup files.</p>
                        </div>
                        <span class="history-count">{{ count($backups) }} {{ count($backups) === 1 ? 'backup' : 'backups' }}</span>
                    </div>

                    @if(count($backups) > 0)
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th scope="col">Backup file</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Size</th>
                                        <th scope="col" style="text-align:center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>
                                                <div class="file-cell">
                                                    <div class="file-icon"><i class="fa-solid fa-file-zipper" aria-hidden="true"></i></div>
                                                    <div>
                                                        <div class="file-name">{{ $backup['filename'] }}</div>
                                                        <div class="file-type">Database archive</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $backup['date'] }}</td>
                                            <td>{{ $backup['size'] }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('system.database_backup.download', $backup['filename']) }}" class="secondary-btn" aria-label="Download {{ $backup['filename'] }}">
                                                        <i class="fa-solid fa-download" aria-hidden="true"></i><span>Download</span>
                                                    </a>
                                                    <form action="{{ route('system.database_backup.delete', $backup['filename']) }}" method="POST" class="deleteBackupForm" data-filename="{{ $backup['filename'] }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="danger-btn" aria-label="Delete {{ $backup['filename'] }}">
                                                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i><span>Delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">
                            <div class="empty-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></div>
                            <h3>No backups yet</h3>
                            <p>Your backup history is empty. Create your first backup to establish a recovery point.</p>
                        </div>
                    @endif
                </section>
            </div>

            <aside class="side-stack" aria-label="Backup guidance">
                <section class="status-card">
                    <div class="status-top">
                        <i class="fa-solid fa-shield-heart" aria-hidden="true"></i>
                        <h3>Protect your system data</h3>
                    </div>
                    <p>Backups are an important part of service continuity. Keep copies secure, limit access to authorised staff, and verify that recovery procedures work.</p>
                </section>

                <section class="panel side-panel">
                    <h3><i class="fa-solid fa-list-check" aria-hidden="true"></i> Before you begin</h3>
                    <ul class="tip-list">
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Confirm the application is operating normally.</span></li>
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Ensure the server has enough free disk space.</span></li>
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Download important backups to approved secure storage.</span></li>
                        <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Verify backup integrity before relying on it for recovery.</span></li>
                    </ul>
                </section>

                <section class="panel side-panel">
                    <h3><i class="fa-solid fa-life-ring" aria-hidden="true"></i> Need help?</h3>
                    <p style="margin:0 0 15px;color:var(--muted);font-size:12px;line-height:1.8">Read the backup guide for instructions on managing database backup files.</p>
                    <a href="{{ route('system.documentation.show', 'database-backup') }}" class="help-link">
                        <span><i class="fa-solid fa-book-open" aria-hidden="true" style="margin-right:8px"></i>Database backup guide</span>
                        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                    </a>
                </section>
            </aside>
        </div>

        <footer class="footer">
            <div><strong>HSC Occupational Health System</strong> <span style="margin:0 7px">|</span> Database Backup</div>
            <div><i class="fa-solid fa-shield-halved" aria-hidden="true" style="margin-right:6px;color:#39815e"></i> Health · Safety · Excellence</div>
        </footer>
    </main>

    <script>
        (() => {
            const backupForm = document.getElementById('backupForm');
            const createButton = document.getElementById('createBackupButton');

            if (backupForm && createButton) {
                backupForm.addEventListener('submit', () => {
                    createButton.disabled = true;
                    createButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Creating backup…</span>';
                    createButton.setAttribute('aria-busy', 'true');
                });
            }

            document.querySelectorAll('.deleteBackupForm').forEach((form) => {
                form.addEventListener('submit', (event) => {
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