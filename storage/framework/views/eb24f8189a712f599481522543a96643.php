<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Audit Logs · Super Admin</title>
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
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        
        /* Filter Select */
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .badge-role { background: #dbeafe; color: #1e40af; }
        .badge-rejection { background: #fee2e2; color: #991b1b; }
        .badge-activity { background: #fef3c7; color: #92400e; }
        .badge-access { background: #dbeafe; color: #1e40af; }
        .badge-login { background: #fee2e2; color: #991b1b; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-failed { background: #fee2e2; color: #991b1b; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; border: none; border-radius: 6px; font-size: 10px; font-weight: 650; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 14px; max-width: 700px; width: 100%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; max-height: 90vh; overflow-y: auto; margin: auto; }
        .modal-header { position: sticky; top: 0; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; z-index: 2001; }
        .modal-header h2 { color: white; font-size: 18px; font-weight: 750; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        
        .detail-section { margin-bottom: 24px; }
        .detail-section h3 { font-size: 12px; font-weight: 750; color: #079b6d; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e4e9ee; padding-bottom: 8px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 12px; border-bottom: 1px solid #e4e9ee; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 650; color: #4e596c; min-width: 150px; }
        .detail-value { color: #263149; word-break: break-word; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
            .search-box { min-width: 100%; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-history"></i></div>
                <div>
                    <h1 class="title">Audit Logs</h1>
                    <div class="sub">Track system activities and user actions</div>
                    <span class="case">Activity Log</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Audit Log Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Audit Records [<span id="auditCount">0</span>]</div>
                <div class="filters">
                    <select id="logTypeFilter" class="filter-select">
                        <option value="all">All Logs</option>
                        <option value="role">Role Assignments</option>
                        <option value="rejection">User Rejections</option>
                        <option value="activity">User Activity</option>
                        <option value="access">System Access</option>
                        <option value="login">Failed Logins</option>
                        <option value="auditlog">All Audit Logs</option>
                    </select>
                    <select class="filter-select">
                        <option value="all">Show: All</option>
                        <option value="10">Show: 10</option>
                        <option value="50">Show: 50</option>
                        <option value="100">Show: 100</option>
                    </select>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="auditTableSearch" placeholder="Search logs..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Details</th>
                            <th>Action By</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                            <th style="width: 80px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Role Assignments -->
                        <?php $__empty_1 = true; $__currentLoopData = $roleAssignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-log-type="role" data-audit-row>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge badge-role">
                                        <i class="fa-solid fa-user-check"></i> Role
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 650; color: #101a31;"><?php echo e($assignment->user->name); ?></div>
                                    <div style="font-size: 11px; color: #718096;"><?php echo e($assignment->user->email); ?></div>
                                </td>
                                <td>Assigned: <strong><?php echo e($assignment->role->display_name ?? $assignment->role->name); ?></strong></td>
                                <td><?php echo e($assignment->assignedByUser->name ?? 'System'); ?></td>
                                <td><?php echo e($assignment->assigned_at->format('M d, Y H:i')); ?></td>
                                <td>
                                    <?php if($assignment->removed_at): ?>
                                        <span class="badge badge-failed"><i class="fa-solid fa-times"></i> Removed</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Active</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="viewAuditDetail('role', <?php echo e($assignment->id); ?>)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        <!-- Rejections -->
                        <?php $__empty_1 = true; $__currentLoopData = $rejectionLogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rejection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-log-type="rejection" data-audit-row>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge badge-rejection">
                                        <i class="fa-solid fa-ban"></i> Rejection
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 650; color: #101a31;"><?php echo e($rejection->user->name); ?></div>
                                    <div style="font-size: 11px; color: #718096;"><?php echo e($rejection->user->email); ?></div>
                                </td>
                                <td>Reason: <strong><?php echo e($rejection->reason); ?></strong></td>
                                <td><?php echo e($rejection->rejectedByUser->name ?? 'System'); ?></td>
                                <td><?php echo e($rejection->created_at->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge badge-failed"><i class="fa-solid fa-times"></i> Rejected</span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="viewAuditDetail('rejection', <?php echo e($rejection->id); ?>)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        <!-- User Activity Logs -->
                        <?php $__empty_1 = true; $__currentLoopData = $auditLogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if(in_array($log->action, ['USER_APPROVED', 'USER_REJECTED', 'DATA_ACCESS', 'USER_APPROVAL_FAILED', 'USER_REJECTION_FAILED'])): ?>
                            <tr data-log-type="activity" data-audit-row>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge badge-activity">
                                        <i class="fa-solid fa-person"></i> Activity
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 650; color: #101a31;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                    <div style="font-size: 11px; color: #718096;"><?php echo e($log->user->email ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo e($log->action); ?>: <?php echo e($log->affected_entity ?? $log->resource_path); ?></td>
                                <td><?php echo e($log->user->name ?? 'System'); ?></td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge <?php echo e($log->status === 'SUCCESS' ? 'badge-success' : 'badge-failed'); ?>">
                                        <i class="fa-solid <?php echo e($log->status === 'SUCCESS' ? 'fa-check' : 'fa-times'); ?>"></i> <?php echo e($log->status); ?>

                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="viewAuditDetail('auditlog', <?php echo e($log->id); ?>)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        <!-- System Access Logs -->
                        <?php $__empty_1 = true; $__currentLoopData = $auditLogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if(in_array($log->action, ['LOGIN_SUCCESS', 'LOGOUT', 'SESSION_CREATED'])): ?>
                            <tr data-log-type="access" data-audit-row>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge badge-access">
                                        <i class="fa-solid fa-lock"></i> Access
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 650; color: #101a31;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                    <div style="font-size: 11px; color: #718096;"><?php echo e($log->user->email ?? $log->ip_address); ?></div>
                                </td>
                                <td><?php echo e($log->action); ?>: <?php echo e($log->ip_address); ?></td>
                                <td><?php echo e($log->user->name ?? 'System'); ?></td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge <?php echo e($log->status === 'SUCCESS' ? 'badge-success' : 'badge-failed'); ?>">
                                        <i class="fa-solid <?php echo e($log->status === 'SUCCESS' ? 'fa-check' : 'fa-times'); ?>"></i> <?php echo e($log->status); ?>

                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="viewAuditDetail('auditlog', <?php echo e($log->id); ?>)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        <!-- Failed Login Logs -->
                        <?php $__empty_1 = true; $__currentLoopData = $auditLogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($log->action === 'LOGIN_FAILED'): ?>
                            <tr data-log-type="login" data-audit-row>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge badge-login">
                                        <i class="fa-solid fa-exclamation-triangle"></i> Login Failed
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 650; color: #101a31;"><?php echo e($log->user->name ?? 'Unknown'); ?></div>
                                    <div style="font-size: 11px; color: #718096;"><?php echo e($log->user->email ?? $log->affected_entity); ?></div>
                                </td>
                                <td>Failed attempt from <?php echo e($log->ip_address); ?></td>
                                <td>-</td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge badge-failed"><i class="fa-solid fa-times"></i> Failed</span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="viewAuditDetail('auditlog', <?php echo e($log->id); ?>)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        <!-- All Audit Logs (catch-all) -->
                        <?php $__empty_1 = true; $__currentLoopData = $auditLogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $logType = 'auditlog';
                                if (in_array($log->action, ['USER_APPROVED', 'USER_REJECTED', 'DATA_ACCESS'])) {
                                    $logType = 'activity';
                                } elseif (in_array($log->action, ['LOGIN_SUCCESS', 'LOGOUT', 'SESSION_CREATED'])) {
                                    $logType = 'access';
                                } elseif ($log->action === 'LOGIN_FAILED') {
                                    $logType = 'login';
                                }
                            ?>
                            <tr data-log-type="<?php echo e($logType); ?>" data-audit-row>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                        <i class="fa-solid fa-file-lines"></i> Log
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 650; color: #101a31;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                    <div style="font-size: 11px; color: #718096;"><?php echo e($log->user->email ?? $log->ip_address ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo e($log->component); ?>: <?php echo e($log->affected_entity ?? $log->resource_path); ?></td>
                                <td><?php echo e($log->user->name ?? 'System'); ?></td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge <?php echo e($log->status === 'SUCCESS' ? 'badge-success' : 'badge-failed'); ?>">
                                        <i class="fa-solid <?php echo e($log->status === 'SUCCESS' ? 'fa-check' : 'fa-times'); ?>"></i> <?php echo e($log->status); ?>

                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="action-btn" onclick="viewAuditDetail('auditlog', <?php echo e($log->id); ?>)">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fa-solid fa-file-lines"></i> Audit Details</h2>
                <button class="modal-close" onclick="closeAuditDetail()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function viewAuditDetail(type, id) {
            document.getElementById('detailContent').innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #079b6d;"></i><p style="margin-top: 1rem; color: #718096;">Loading details...</p></div>';
            document.getElementById('detailModal').classList.add('active');

            fetch(`/api/audit-detail?type=${type}&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('detailContent').innerHTML = `<div style="padding: 2rem; text-align: center; color: #dc2626;"><i class="fa-solid fa-exclamation-circle" style="font-size: 24px;"></i><p style="margin-top: 1rem;">${data.error}</p></div>`;
                        return;
                    }

                    let details = '';

                    if (type === 'role') {
                        details = `
                            <div class="detail-section">
                                <h3>Details</h3>
                                <div class="detail-row">
                                    <span class="detail-label">User Name:</span>
                                    <span class="detail-value">${data.user.name}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value">${data.user.email}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Role Assigned:</span>
                                    <span class="detail-value">${data.role}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Assigned By:</span>
                                    <span class="detail-value">${data.assignedBy}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Assigned At:</span>
                                    <span class="detail-value">${data.assignedAt}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Status:</span>
                                    <span class="detail-value" style="color: ${data.status === 'Active' ? '#065f46' : '#991b1b'}; font-weight: 650;">${data.status}</span>
                                </div>
                            </div>
                            <div class="detail-section">
                                <h3>Actions Performed</h3>
                                <div class="detail-row">
                                    <span class="detail-label">Pages Visited:</span>
                                    <span class="detail-value">${data.actionsPerformed['Pages Visited']}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Activities:</span>
                                    <span class="detail-value">${data.actionsPerformed.Activities}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Total Actions:</span>
                                    <span class="detail-value" style="font-weight: 650;">${data.actionsPerformed['Total Actions']}</span>
                                </div>
                            </div>
                        `;
                    } else if (type === 'rejection') {
                        details = `
                            <div class="detail-section">
                                <h3>Details</h3>
                                <div class="detail-row">
                                    <span class="detail-label">User Name:</span>
                                    <span class="detail-value">${data.user.name}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value">${data.user.email}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Rejection Reason:</span>
                                    <span class="detail-value">${data.reason}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Rejected By:</span>
                                    <span class="detail-value">${data.rejectedBy}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Rejection Date:</span>
                                    <span class="detail-value">${data.rejectedAt}</span>
                                </div>
                            </div>
                            <div class="detail-section">
                                <h3>Actions Performed</h3>
                                <div class="detail-row">
                                    <span class="detail-label">Pages Visited:</span>
                                    <span class="detail-value">${data.actionsPerformed['Pages Visited']}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Activities:</span>
                                    <span class="detail-value">${data.actionsPerformed.Activities}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Total Actions:</span>
                                    <span class="detail-value" style="font-weight: 650;">${data.actionsPerformed['Total Actions']}</span>
                                </div>
                            </div>
                        `;
                    } else {
                        details = `
                            <div class="detail-section">
                                <h3>Details</h3>
                                ${Object.entries(data.details).map(([key, value]) => `
                                    <div class="detail-row">
                                        <span class="detail-label">${key}:</span>
                                        <span class="detail-value">${value || 'N/A'}</span>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="detail-section">
                                <h3>Actions Performed</h3>
                                ${Object.entries(data.actionsPerformed).map(([key, value]) => `
                                    <div class="detail-row">
                                        <span class="detail-label">${key}:</span>
                                        <span class="detail-value">${value || 'N/A'}</span>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }

                    document.getElementById('detailContent').innerHTML = details;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detailContent').innerHTML = `<div style="padding: 2rem; text-align: center; color: #dc2626;"><i class="fa-solid fa-exclamation-circle" style="font-size: 24px;"></i><p style="margin-top: 1rem;">Failed to load audit details. Please try again.</p></div>`;
                });
        }

        function closeAuditDetail() {
            document.getElementById('detailModal').classList.remove('active');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('detailModal');
            if (e.target === modal) closeAuditDetail();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const logTypeFilter = document.getElementById('logTypeFilter');
            const auditSearch = document.getElementById('auditTableSearch');

            function filterAuditTable() {
                const searchTerm = auditSearch.value.toLowerCase();
                const logType = logTypeFilter.value;
                const rows = document.querySelectorAll('table tbody tr[data-audit-row]');
                let displayedCount = 0;

                rows.forEach(row => {
                    const rowLogType = row.getAttribute('data-log-type');
                    const text = row.textContent.toLowerCase();
                    
                    const typeMatch = logType === 'all' || rowLogType === logType;
                    const searchMatch = searchTerm === '' || text.includes(searchTerm);
                    
                    if (typeMatch && searchMatch) {
                        row.style.display = '';
                        displayedCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('auditCount').textContent = displayedCount;
            }

            logTypeFilter.addEventListener('change', filterAuditTable);
            auditSearch.addEventListener('keyup', filterAuditTable);
            
            filterAuditTable();
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\super-admin\audit-logs\index.blade.php ENDPATH**/ ?>