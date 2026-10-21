<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Backup - HSC OHS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --backup-green: #176044;
            --backup-ink: #172b23;
            --backup-muted: #65766d;
            --backup-line: #e1e9e3;
            --backup-bg: #f4f7f5;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--backup-ink);
            background: var(--backup-bg);
            min-height: 100vh;
            padding: 32px 20px;
        }

        .backup-wrap {
            max-width: 900px;
            margin: 0 auto;
        }

        .backup-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .backup-header h1 {
            font-size: 32px;
            color: var(--backup-green);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .backup-header p {
            color: var(--backup-muted);
            font-size: 14px;
        }

.backup-flash {
    display: flex;
    gap: 12px;
    margin: 16px 0 0;
    padding: 14px 16px;
    border-radius: 12px;
    font-size: 12px;
    line-height: 1.7;
}

.backup-success {
    color: #17603c;
    background: #effaf3;
    border: 1px solid #bde8cb;
}

.backup-error {
    color: #9d2727;
    background: #fff3f2;
    border: 1px solid #ffd2ce;
}

.backup-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin: 18px 0;
}

.backup-stat {
    background: #fff;
    border: 1px solid var(--backup-line);
    border-radius: 15px;
    padding: 17px 19px;
    display: flex;
    align-items: center;
    gap: 13px;
}

.backup-stat-icon {
    width: 40px;
    height: 40px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: #e8f5ed;
    color: var(--backup-green);
}

.backup-stat small {
    display: block;
    color: var(--backup-muted);
    font-size: 10px;
    font-weight: 700;
}

.backup-stat strong {
    display: block;
    font-size: 21px;
    margin-top: 2px;
}

.backup-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(270px, .75fr);
    gap: 19px;
    align-items: start;
}

.backup-stack,
.backup-side {
    display: grid;
    gap: 19px;
}

.backup-panel {
    background: #fff;
    border: 1px solid var(--backup-line);
    border-radius: 17px;
    overflow: hidden;
    box-shadow: 0 10px 28px #102b2208;
    min-width: 0;
}

.backup-head {
    padding: 21px 22px 17px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 13px;
}

.backup-heading {
    display: flex;
    gap: 11px;
    align-items: center;
}

.backup-tile {
    width: 42px;
    height: 42px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: #e8f5ed;
    color: var(--backup-green);
    flex: 0 0 auto;
}

.backup-panel h2 {
    font-size: 15px;
    letter-spacing: -.3px;
    margin: 0;
}

.backup-sub {
    color: var(--backup-muted);
    font-size: 11px;
    margin: 5px 0 0;
    line-height: 1.6;
}

.backup-body {
    padding: 0 22px 22px;
}

.backup-btn {
    border: 1px solid transparent;
    border-radius: 10px;
    min-height: 39px;
    padding: 9px 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
    text-decoration: none;
}

.backup-primary {
    color: #fff;
    background: linear-gradient(120deg, #23714d, #174b34);
    box-shadow: 0 5px 12px #1b62401c;
}

.backup-secondary {
    color: #245bc5;
    background: #eff6ff;
    border-color: #d9e7ff;
}

.backup-danger {
    color: #b42318;
    background: #fff2f1;
    border-color: #ffdedb;
}

.backup-create-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
}

.backup-micro {
    font-size: 11px;
    color: var(--backup-muted);
}

.backup-note {
    display: flex;
    gap: 11px;
    padding: 14px 15px;
    border: 1px solid #f0dfb0;
    background: #fffbef;
    color: #76571c;
    border-radius: 12px;
    margin-top: 19px;
    font-size: 11px;
    line-height: 1.75;
}

.backup-note strong {
    display: block;
    margin-bottom: 3px;
}

.backup-history-head {
    padding: 21px 22px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid var(--backup-line);
}

.backup-count {
    padding: 5px 9px;
    border-radius: 999px;
    background: #e8f5ed;
    color: var(--backup-green);
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.backup-toolbar {
    padding: 13px 18px;
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--backup-line);
}

.backup-search {
    max-width: 320px;
    flex: 1;
}

.backup-search input,
.backup-sort {
    width: 100%;
    background: #f7faf8;
    border: 1px solid var(--backup-line);
    border-radius: 9px;
    padding: 9px 11px;
    color: var(--backup-ink);
    font-size: 11px;
}

.backup-table-wrap {
    overflow-x: auto;
}

.backup-table {
    border-collapse: collapse;
    width: 100%;
    text-align: left;
}

.backup-table th {
    background: #f7faf8;
    color: #7b8a81;
    font-size: 9px;
    letter-spacing: .7px;
    text-transform: uppercase;
    padding: 12px 16px;
    white-space: nowrap;
}

.backup-table td {
    padding: 13px 16px;
    border-top: 1px solid #edf1ee;
    font-size: 11px;
    color: #65766d;
    vertical-align: middle;
}

.backup-table tbody tr:hover {
    background: #fbfdfb;
}

.backup-file {
    display: flex;
    gap: 9px;
    align-items: center;
    min-width: 205px;
}

.backup-file-icon {
    width: 33px;
    height: 33px;
    display: grid;
    place-items: center;
    background: #e8f5ed;
    color: var(--backup-green);
    border-radius: 9px;
}

.backup-filename {
    color: var(--backup-ink);
    font-weight: 750;
    max-width: 240px;
    overflow-wrap: anywhere;
}

.backup-filemeta {
    font-size: 9px;
    color: #89978f;
    margin-top: 3px;
}

.backup-actions {
    display: flex;
    gap: 6px;
    align-items: center;
    justify-content: flex-end;
}

.backup-actions .backup-btn {
    min-height: 30px;
    padding: 6px 8px;
    font-size: 10px;
}

.backup-empty {
    text-align: center;
    padding: 36px 20px;
}

.backup-empty[hidden] {
    display: none;
}

.backup-empty i {
    font-size: 25px;
    color: #6e9d81;
    margin-bottom: 10px;
}

.backup-empty h3 {
    font-size: 13px;
    margin: 0 0 5px;
}

.backup-empty p {
    font-size: 11px;
    color: var(--backup-muted);
    margin: 0;
}

.backup-status {
    padding: 20px;
    background: linear-gradient(140deg, #16452f, #23714b);
    color: #fff;
    border: 0;
}

.backup-status p {
    font-size: 11px;
    line-height: 1.8;
    color: #d3e8db;
    margin: 12px 0 0;
}

.backup-side-panel {
    padding: 20px;
}

.backup-side-panel h3 {
    display: flex;
    gap: 9px;
    align-items: center;
    font-size: 13px;
    margin: 0 0 14px;
}

.backup-tips {
    display: grid;
    gap: 12px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.backup-tips li {
    display: flex;
    gap: 9px;
    font-size: 11px;
    line-height: 1.7;
    color: var(--backup-muted);
}

.backup-tips i,
.backup-side-panel h3 i {
    color: var(--backup-green);
}

.backup-help {
    margin-top: 14px;
    border: 1px solid var(--backup-line);
    background: #fbfdfb;
    border-radius: 10px;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    color: var(--backup-green);
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}

.backup-footer {
    margin-top: 26px;
    padding: 17px 0;
    border-top: 1px solid var(--backup-line);
    display: flex;
    justify-content: space-between;
    gap: 12px;
    color: #819087;
    font-size: 10px;
}

.backup-footer strong {
    color: #50665a;
}

@media (max-width: 950px) {
    .backup-grid {
        grid-template-columns: 1fr;
    }
    .backup-side {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .backup-status {
        grid-column: span 2;
    }
}

@media (max-width: 620px) {
    .backup-wrap {
        width: calc(100% - 26px);
    }
    .backup-hero {
        padding: 22px;
        border-radius: 17px;
        align-items: flex-start;
    }
    .backup-hero h1 {
        font-size: 28px;
    }
    .backup-hero-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        font-size: 22px;
    }
    .backup-stats {
        gap: 8px;
    }
    .backup-stat {
        padding: 12px 9px;
        display: block;
    }
    .backup-stat-icon {
        width: 30px;
        height: 30px;
        margin-bottom: 7px;
    }
    .backup-stat small {
        font-size: 9px;
    }
    .backup-stat strong {
        font-size: 17px;
    }
    .backup-head,
    .backup-history-head {
        padding: 17px;
    }
    .backup-body {
        padding: 0 17px 17px;
    }
    .backup-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .backup-search {
        max-width: none;
    }
    .backup-side {
        grid-template-columns: 1fr;
    }
    .backup-status {
        grid-column: auto;
    }
    .backup-create-row .backup-btn {
        width: 100%;
    }
    .backup-footer {
        flex-direction: column;
    }
    .backup-table th,
    .backup-table td {
        padding: 11px 12px;
    }
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}
</style>

<div class="backup-page">
    <div class="backup-wrap">
        <a href="<?php echo e(route('dashboards.super_admin')); ?>" class="backup-crumb">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Dashboard
        </a>

        <section class="backup-hero" aria-labelledby="backup-title">
            <div>
                <span class="backup-eyebrow">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Secure system operations
                </span>
                <h1 id="backup-title">Database Backup</h1>
                <p>Create, download and manage database backups from one secure workspace. Maintain a dependable recovery point before important updates and configuration changes.</p>
            </div>
            <div class="backup-hero-icon" aria-hidden="true">
                <i class="fa-solid fa-database"></i>
            </div>
        </section>

        <?php if(session('success')): ?>
        <div class="backup-flash backup-success" role="status">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            <div>
                <strong>Backup operation successful</strong><br>
                <?php echo e(session('success')); ?>

            </div>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="backup-flash backup-error" role="alert">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
            <div>
                <strong>Backup operation failed</strong><br>
                <?php echo e(session('error')); ?>

            </div>
        </div>
        <?php endif; ?>

        <section class="backup-stats" aria-label="Backup summary">
            <div class="backup-stat">
                <div class="backup-stat-icon">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <div>
                    <small>Available backups</small>
                    <strong><?php echo e(count($backups)); ?></strong>
                </div>
            </div>
            <div class="backup-stat">
                <div class="backup-stat-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <small>Latest backup</small>
                    <strong style="font-size:16px"><?php echo e(count($backups) ? $backups[0]['date'] : 'Not yet created'); ?></strong>
                </div>
            </div>
            <div class="backup-stat">
                <div class="backup-stat-icon">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>
                <div>
                    <small>Recovery readiness</small>
                    <strong style="font-size:16px">Stay protected</strong>
                </div>
            </div>
        </section>

        <div class="backup-grid">
            <div class="backup-stack">
                <section class="backup-panel" aria-labelledby="create-title">
                    <div class="backup-head">
                        <div class="backup-heading">
                            <div class="backup-tile">
                                <i class="fa-solid fa-cloud-arrow-down"></i>
                            </div>
                            <div>
                                <h2 id="create-title">Create a new backup</h2>
                                <p class="backup-sub">Generate a fresh database backup file on the server.</p>
                            </div>
                        </div>
                    </div>
                    <div class="backup-body">
                        <form action="<?php echo e(route('system.database_backup.create')); ?>" method="POST" id="backupForm" class="backup-create-row">
                            <?php echo csrf_field(); ?>
                            <div class="backup-micro">
                                <i class="fa-solid fa-circle-check" style="color:#268457;margin-right:6px"></i>
                                Recommended before system changes
                            </div>
                            <button type="submit" class="backup-btn backup-primary" id="createBackupButton">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                <span>Create backup now</span>
                            </button>
                        </form>
                        <div class="backup-note">
                            <i class="fa-solid fa-lightbulb" aria-hidden="true"></i>
                            <div>
                                <strong>Backup best practices</strong>
                                Create a backup before major updates. Keep a secure off-server copy, restrict access to authorised staff, and test restoration periodically. Backup files may contain sensitive occupational health data.
                            </div>
                        </div>
                    </div>
                </section>

                <section class="backup-panel" aria-labelledby="history-title">
                    <div class="backup-history-head">
                        <div>
                            <h2 id="history-title">Backup history</h2>
                            <p class="backup-sub">Search, download or remove existing backup files.</p>
                        </div>
                        <span class="backup-count"><?php echo e(count($backups)); ?> <?php echo e(count($backups) === 1 ? 'backup' : 'backups'); ?></span>
                    </div>
                    <div class="backup-toolbar">
                        <label class="backup-search">
                            <span class="sr-only">Search backup filenames</span>
                            <input type="search" id="backupSearch" placeholder="Search backup filenames…" aria-label="Search backup filenames">
                        </label>
                        <select id="backupSort" class="backup-sort" aria-label="Sort backups">
                            <option value="newest">Newest first</option>
                            <option value="oldest">Oldest first</option>
                            <option value="name">Filename A–Z</option>
                        </select>
                    </div>
                    <?php if(count($backups) > 0): ?>
                    <div class="backup-table-wrap" id="backupTableWrap">
                        <table class="backup-table">
                            <thead>
                                <tr>
                                    <th scope="col">Backup file</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Size</th>
                                    <th scope="col" style="text-align:right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="backupRows">
                                <?php $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-filename="<?php echo e(strtolower($backup['filename'])); ?>" data-date="<?php echo e($backup['timestamp'] ?? 0); ?>">
                                    <td>
                                        <div class="backup-file">
                                            <div class="backup-file-icon">
                                                <i class="fa-solid fa-file-zipper" aria-hidden="true"></i>
                                            </div>
                                            <div>
                                                <div class="backup-filename"><?php echo e($backup['filename']); ?></div>
                                                <div class="backup-filemeta">Compressed database archive</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($backup['date']); ?></td>
                                    <td><?php echo e($backup['size']); ?></td>
                                    <td>
                                        <div class="backup-actions">
                                            <a href="<?php echo e(route('system.database_backup.download', $backup['filename'])); ?>" class="backup-btn backup-secondary" aria-label="Download <?php echo e($backup['filename']); ?>">
                                                <i class="fa-solid fa-download" aria-hidden="true"></i> Download
                                            </a>
                                            <form action="<?php echo e(route('system.database_backup.delete', $backup['filename'])); ?>" method="POST" class="deleteBackupForm" data-filename="<?php echo e($backup['filename']); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="backup-btn backup-danger" aria-label="Delete <?php echo e($backup['filename']); ?>">
                                                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="backup-empty" id="backupNoMatches" hidden>
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <h3>No matching backups</h3>
                        <p>Try another search term.</p>
                    </div>
                    <?php else: ?>
                    <div class="backup-empty">
                        <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                        <h3>No backups yet</h3>
                        <p>Your backup history is empty. Create your first backup to establish a recovery point.</p>
                    </div>
                    <?php endif; ?>
                </section>
            </div>

            <aside class="backup-side">
                <section class="backup-panel backup-status">
                    <div class="backup-heading">
                        <div class="backup-tile">
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>
                        <div>
                            <h2>Protect your system data</h2>
                            <p class="backup-sub" style="color:#cde5d7">Recovery readiness</p>
                        </div>
                    </div>
                    <p>Backups support service continuity. Keep copies in approved, access-controlled storage and verify that recovery procedures work before you need them.</p>
                </section>

                <section class="backup-panel backup-side-panel">
                    <h3><i class="fa-solid fa-list-check"></i> Before you begin</h3>
                    <ul class="backup-tips">
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Confirm the application is operating normally.</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Ensure the server has enough free disk space.</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Copy important backups to approved secure storage.</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Verify file integrity and test restoration periodically.</span>
                        </li>
                    </ul>
                </section>

                <section class="backup-panel backup-side-panel">
                    <h3><i class="fa-solid fa-life-ring"></i> Need help?</h3>
                    <p class="backup-sub" style="font-size:11px">Review the backup guide for procedures and recovery guidance.</p>
                    <a href="#" class="backup-help">
                        <span><i class="fa-solid fa-book-open" style="margin-right:7px"></i>Database backup guide</span>
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </section>
            </aside>
        </div>

        <footer class="backup-footer">
            <div>
                <strong>HSC Occupational Health System</strong>
                <span style="margin:0 7px">|</span> Database Backup
            </div>
            <div>
                <i class="fa-solid fa-shield-halved" style="margin-right:6px;color:#39815e"></i>
                Health · Safety · Excellence
            </div>
        </footer>
    </div>
</div>

<script>
(() => {
    const form = document.getElementById('backupForm');
    const createButton = document.getElementById('createBackupButton');
    
    if (form && createButton) {
        form.addEventListener('submit', () => {
            createButton.disabled = true;
            createButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Creating backup…</span>';
            createButton.setAttribute('aria-busy', 'true');
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

    const search = document.getElementById('backupSearch');
    const sort = document.getElementById('backupSort');
    const rows = document.getElementById('backupRows');

    function filterRows() {
        if (!rows) return;
        
        const query = (search.value || '').toLowerCase().trim();
        const all = Array.from(rows.querySelectorAll('tr'));
        
        all.forEach(row => {
            row.hidden = !row.dataset.filename.includes(query);
        });
        
        const visible = all.filter(row => !row.hidden);
        
        visible.sort((a, b) => {
            if (sort.value === 'oldest') {
                return Number(a.dataset.date) - Number(b.dataset.date);
            } else if (sort.value === 'name') {
                return a.dataset.filename.localeCompare(b.dataset.filename);
            } else {
                return Number(b.dataset.date) - Number(a.dataset.date);
            }
        });
        
        visible.forEach(row => rows.appendChild(row));
        
        const noMatches = document.getElementById('backupNoMatches');
        if (noMatches) noMatches.hidden = visible.length !== 0;
        
        const tableWrap = document.getElementById('backupTableWrap');
        if (tableWrap) tableWrap.hidden = visible.length === 0;
    }

    if (search && sort && rows) {
        search.addEventListener('input', filterRows);
        sort.addEventListener('change', filterRows);
    }
})();
</script>
<?php $__env->stopSection(); ?>
<?php /**PATH D:\OccupationalHealth System\resources\views/system/database-backup.blade.php ENDPATH**/ ?>