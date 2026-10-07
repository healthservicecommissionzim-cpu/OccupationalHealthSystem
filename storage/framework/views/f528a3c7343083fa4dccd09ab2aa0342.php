<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Audit Logs · Super Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Tahoma, sans-serif; background: offwhite; padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
        .header { margin-bottom: 1rem; }
        .header h1 { 
            font-size: 32px; 
            font-weight: 800; 
            color: #0f172a; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid #1D4533;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .back-button:hover {
            background: #1D4533;
            color: white;
            transform: translateX(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .back-button i {
            font-size: 13px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .table-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); overflow: hidden; margin-bottom: 3rem; }
        .table-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .table-header h2 { 
            font-size: 16px; 
            font-weight: 700; 
            color: white; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem;
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; font-size: 13px; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { 
            text-align: left; 
            padding: 0.4rem 0.75rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            line-height: 1.2;
        }
        td { 
            padding: 0.4rem 0.75rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-family: Tahoma, sans-serif;
            line-height: 1.3;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-removed {
            background: #fee2e2;
            color: #991b1b;
        }
        .empty-state { 
            text-align: center; 
            padding: 3rem 1.5rem;
        }
        .empty-state i { 
            font-size: 48px; 
            color: #cbd5e1; 
            margin-bottom: 1rem; 
        }
        .empty-state p { 
            font-size: 16px; 
            color: #64748b; 
            font-weight: 600; 
        }
        .pagination-wrapper { 
            padding: 1rem; 
            text-align: center; 
            background: white; 
            border-top: 1px solid #e2e8f0;
        }
        .pagination { 
            display: flex; 
            justify-content: center; 
            gap: 0.3rem; 
            flex-wrap: wrap;
            align-items: center;
        }
        .pagination a, .pagination span { 
            padding: 0.4rem 0.6rem; 
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #4a5568;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
        }
        .pagination a:hover {
            border-color: #1D4533;
            color: #1D4533;
            background: #f0f4f2;
        }
        .pagination .active span, .pagination .active a { 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white; 
            border-color: #1D4533; 
        }
        .pagination .disabled span {
            color: #cbd5e1;
            cursor: not-allowed;
            background: #f7fafc;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
        }
        .search-box:focus-within {
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            color: #2d3748;
            font-family: 'Roboto', sans-serif;
        }
        .search-box input::placeholder {
            color: #a0aec0;
        }
        .search-box i {
            color: #64748b;
            font-size: 14px;
            flex-shrink: 0;
        }
        .filter-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #2d3748;
            font-weight: 600;
        }
        .filter-dropdown:hover {
            border-color: #1D4533;
        }
        .filter-dropdown i {
            color: #64748b;
            font-size: 14px;
        }
        .records-per-page {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        .records-per-page:focus-within {
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .records-per-page select {
            border: none;
            background: transparent;
            outline: none;
            cursor: pointer;
            font-size: 12px;
            color: #2d3748;
            font-weight: 600;
            font-family: Tahoma, sans-serif;
        }
        .records-per-page label {
            color: #64748b;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            background: #1D4533;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(29, 69, 51, 0.3);
        }
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .modal-header h2 {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }
        .modal-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .modal-close:hover {
            transform: scale(1.1);
        }
        .modal-body {
            padding: 1.5rem;
        }
        .detail-section {
            margin-bottom: 1.5rem;
        }
        .detail-section h3 {
            font-size: 13px;
            font-weight: 700;
            color: #1D4533;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            border-bottom: 2px solid #1D4533;
            padding-bottom: 0.5rem;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 13px;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #2d3748;
            min-width: 150px;
        }
        .detail-value {
            color: #4a5568;
            word-break: break-word;
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .table-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            th, td { padding: 0.75rem; font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <h3><i class="fa-solid fa-history"></i> Audit Logs</h3>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Combined Audit Log -->
        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> Audit Log [<span id="auditCount">0</span>]</h2>
                <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                    <select id="logTypeFilter" class="filter-dropdown">
                        <option value="all">All Logs</option>
                        <option value="role">Role Assignments</option>
                        <option value="rejection">User Rejections</option>
                        <option value="activity">User Activity</option>
                        <option value="access">System Access</option>
                        <option value="login">Failed Logins</option>
                        <option value="auditlog">All Audit Logs</option>
                    </select>
                    <div class="records-per-page">
                        <label for="auditRecordsPerPage">Show:</label>
                        <select id="auditRecordsPerPage">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="999999" selected>All</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="auditTableSearch" placeholder="Search audit logs..." />
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                        <i class="fa-solid fa-user-check"></i> Role Assignment
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1D4533;"><?php echo e($assignment->user->name); ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($assignment->user->email); ?></div>
                                </td>
                                <td>Assigned role: <strong><?php echo e($assignment->role->display_name ?? $assignment->role->name); ?></strong></td>
                                <td><?php echo e($assignment->assignedByUser->name ?? 'System'); ?></td>
                                <td><?php echo e($assignment->assigned_at->format('M d, Y H:i')); ?></td>
                                <td>
                                    <?php if($assignment->removed_at): ?>
                                        <span class="badge badge-removed"><i class="fa-solid fa-times"></i> Removed</span>
                                    <?php else: ?>
                                        <span class="badge badge-active"><i class="fa-solid fa-check"></i> Active</span>
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                        <i class="fa-solid fa-ban"></i> User Rejection
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1D4533;"><?php echo e($rejection->user->name); ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($rejection->user->email); ?></div>
                                </td>
                                <td>Reason: <strong><?php echo e($rejection->reason); ?></strong></td>
                                <td><?php echo e($rejection->rejectedByUser->name ?? 'System'); ?></td>
                                <td><?php echo e($rejection->created_at->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge badge-removed"><i class="fa-solid fa-times"></i> Rejected</span>
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #fef3c7; color: #92400e;">
                                        <i class="fa-solid fa-person"></i> User Activity
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1D4533;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($log->user->email ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo e($log->action); ?>: <?php echo e($log->affected_entity ?? $log->resource_path); ?></td>
                                <td><?php echo e($log->user->name ?? 'System'); ?></td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge" style="background: <?php echo e($log->status === 'SUCCESS' ? '#d1fae5' : '#fee2e2'); ?>; color: <?php echo e($log->status === 'SUCCESS' ? '#065f46' : '#991b1b'); ?>;">
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #dbeafe; color: #1e40af;">
                                        <i class="fa-solid fa-lock"></i> System Access
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1D4533;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($log->user->email ?? $log->ip_address); ?></div>
                                </td>
                                <td><?php echo e($log->action); ?>: <?php echo e($log->ip_address); ?></td>
                                <td><?php echo e($log->user->name ?? 'System'); ?></td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge" style="background: <?php echo e($log->status === 'SUCCESS' ? '#d1fae5' : '#fee2e2'); ?>; color: <?php echo e($log->status === 'SUCCESS' ? '#065f46' : '#991b1b'); ?>;">
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #fee2e2; color: #991b1b;">
                                        <i class="fa-solid fa-exclamation-triangle"></i> Failed Login
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1D4533;"><?php echo e($log->user->name ?? 'Unknown User'); ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($log->user->email ?? $log->affected_entity); ?></div>
                                </td>
                                <td>Failed login attempt from <?php echo e($log->ip_address); ?></td>
                                <td>-</td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge badge-removed"><i class="fa-solid fa-times"></i> Failed</span>
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
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                                        <i class="fa-solid fa-file-lines"></i> <?php echo e($log->action); ?>

                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1D4533;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($log->user->email ?? $log->ip_address ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo e($log->component); ?>: <?php echo e($log->affected_entity ?? $log->resource_path); ?></td>
                                <td><?php echo e($log->user->name ?? 'System'); ?></td>
                                <td><?php echo e($log->timestamp_utc->format('M d, Y H:i')); ?></td>
                                <td>
                                    <span class="badge" style="background: <?php echo e($log->status === 'SUCCESS' ? '#d1fae5' : '#fee2e2'); ?>; color: <?php echo e($log->status === 'SUCCESS' ? '#065f46' : '#991b1b'); ?>;">
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
    <div id="detailModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fa-solid fa-file-lines"></i> Audit Details</h2>
                <button class="modal-close" onclick="closeAuditDetail()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function viewAuditDetail(type, id) {
            // Show loading state
            document.getElementById('detailContent').innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #1D4533;"></i><p style="margin-top: 1rem; color: #64748b;">Loading details...</p></div>';
            document.getElementById('detailModal').style.display = 'flex';

            // Fetch data from API
            fetch(`/api/audit-detail?type=${type}&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('detailContent').innerHTML = `<div style="padding: 2rem; text-align: center; color: #991b1b;"><i class="fa-solid fa-exclamation-circle" style="font-size: 24px;"></i><p style="margin-top: 1rem;">${data.error}</p></div>`;
                        return;
                    }

                    let details = '';

                    if (type === 'role') {
                        details = `
                            <div class="detail-section">
                                <h3>${data.logType} Details</h3>
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
                                    <span class="detail-value" style="color: ${data.status === 'Active' ? '#065f46' : '#991b1b'}; font-weight: 600;">${data.status}</span>
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
                                    <span class="detail-value" style="font-weight: 600;">${data.actionsPerformed['Total Actions']}</span>
                                </div>
                            </div>
                        `;
                    } else if (type === 'rejection') {
                        details = `
                            <div class="detail-section">
                                <h3>${data.logType} Details</h3>
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
                                    <span class="detail-value" style="font-weight: 600;">${data.actionsPerformed['Total Actions']}</span>
                                </div>
                            </div>
                        `;
                    } else if (type === 'auditlog' || type === 'activity' || type === 'access' || type === 'login') {
                        details = `
                            <div class="detail-section">
                                <h3>${data.logType} Details</h3>
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
                    document.getElementById('detailContent').innerHTML = `<div style="padding: 2rem; text-align: center; color: #991b1b;"><i class="fa-solid fa-exclamation-circle" style="font-size: 24px;"></i><p style="margin-top: 1rem;">Failed to load audit details. Please try again.</p></div>`;
                });
        }

        function closeAuditDetail() {
            document.getElementById('detailModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('detailModal');
            if (e.target === modal) {
                closeAuditDetail();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const logTypeFilter = document.getElementById('logTypeFilter');
            const auditSearch = document.getElementById('auditTableSearch');
            const auditPerPage = document.getElementById('auditRecordsPerPage');

            function filterAuditTable() {
                const searchTerm = auditSearch.value.toLowerCase();
                const logType = logTypeFilter.value;
                const limit = parseInt(auditPerPage.value);
                const rows = document.querySelectorAll('table tbody tr[data-audit-row]');
                let visibleCount = 0;
                let displayedCount = 0;

                rows.forEach(row => {
                    const rowLogType = row.getAttribute('data-log-type');
                    const text = row.textContent.toLowerCase();
                    
                    // Check log type filter
                    const typeMatch = logType === 'all' || rowLogType === logType;
                    
                    // Check search term match
                    const searchMatch = searchTerm === '' || text.includes(searchTerm);
                    
                    if (typeMatch && searchMatch) {
                        visibleCount++;
                        if (displayedCount < limit) {
                            row.style.display = '';
                            displayedCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('auditCount').textContent = displayedCount;
            }

            logTypeFilter.addEventListener('change', filterAuditTable);
            auditSearch.addEventListener('keyup', filterAuditTable);
            auditPerPage.addEventListener('change', filterAuditTable);
            
            // Initial count
            filterAuditTable();
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/super-admin/audit-logs/index.blade.php ENDPATH**/ ?>