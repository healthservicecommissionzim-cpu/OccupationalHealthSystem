<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Medical Review · DMO Panel</title>
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
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; }
        .stat-card.total { border-left: 3px solid #079b6d; }
        .stat-card.pending { border-left: 3px solid #f59e0b; }
        .stat-card.verified { border-left: 3px solid #22c55e; }
        .stat-card.recommended { border-left: 3px solid #3b82f6; }
        .stat-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Filter Buttons */
        .filter-btn { padding: 8px 14px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; font-weight: 600; color: #263149; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .filter-btn:hover { border-color: #079b6d; background: #f0f9f7; }
        .filter-btn.active { background: #079b6d; color: white; border-color: #079b6d; }
        
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .ref-number { font-weight: 700; color: #079b6d; font-size: 12px; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-badge.pending { background: #fff8e7; color: #c67a00; }
        .status-badge.verified { background: #e9f8f3; color: #067c58; }
        .status-badge.recommended { background: #eff6ff; color: #1e40af; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Responsive */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr; }
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
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
                <div class="icon"><i class="fa-solid fa-stethoscope"></i></div>
                <div>
                    <h1 class="title">Medical Review</h1>
                    <div class="sub">Clinical case assessment and medical review panel</div>
                    <span class="case">Total: <?php echo e($statistics['total']); ?> cases</span>
                </div>
            </div>
            <a href="<?php echo e(route('dashboards.clinical_executive')); ?>" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Cases Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Medical Cases</div>
                <div class="filters">
                    <a href="<?php echo e(route('dashboards.dmo.medical_review', ['filter' => 'pending', 'date_range' => $dateRange])); ?>" class="filter-btn <?php echo e($filter === 'pending' ? 'active' : ''); ?>">
                        <i class="fa-solid fa-inbox"></i> Pending
                    </a>
                    <a href="<?php echo e(route('dashboards.dmo.medical_review', ['filter' => 'verified', 'date_range' => $dateRange])); ?>" class="filter-btn <?php echo e($filter === 'verified' ? 'active' : ''); ?>">
                        <i class="fa-solid fa-check-circle"></i> Verified
                    </a>
                    <a href="<?php echo e(route('dashboards.dmo.medical_review', ['filter' => 'recommended', 'date_range' => $dateRange])); ?>" class="filter-btn <?php echo e($filter === 'recommended' ? 'active' : ''); ?>">
                        <i class="fa-solid fa-thumbs-up"></i> Recommended
                    </a>
                    <a href="<?php echo e(route('dashboards.dmo.medical_review', ['filter' => 'all', 'date_range' => $dateRange])); ?>" class="filter-btn <?php echo e($filter === 'all' ? 'active' : ''); ?>">
                        <i class="fa-solid fa-list"></i> All
                    </a>
                    <form method="GET" style="margin: 0;">
                        <input type="hidden" name="filter" value="<?php echo e($filter); ?>">
                        <select name="date_range" onchange="this.form.submit()" class="filter-select">
                            <option value="all" <?php echo e($dateRange === 'all' ? 'selected' : ''); ?>>All Time</option>
                            <option value="7days" <?php echo e($dateRange === '7days' ? 'selected' : ''); ?>>Last 7 Days</option>
                            <option value="30days" <?php echo e($dateRange === '30days' ? 'selected' : ''); ?>>Last 30 Days</option>
                            <option value="90days" <?php echo e($dateRange === '90days' ? 'selected' : ''); ?>>Last 90 Days</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Reference</th>
                            <th>Employee</th>
                            <th>Facility</th>
                            <th>Incident Date</th>
                            <th>Status</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="ref-number"><?php echo e($incident->reference_number); ?></span></td>
                                <td><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                                <td><?php echo e($incident->facility->name ?? 'N/A'); ?></td>
                                <td><?php echo e($incident->created_at->format('d M Y, H:i')); ?></td>
                                <td>
                                    <?php if($incident->executive_audit_verified): ?>
                                        <span class="status-badge verified">
                                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                            Verified
                                        </span>
                                    <?php elseif($incident->executive_recommendation_submitted_at): ?>
                                        <span class="status-badge recommended">
                                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                            Recommended
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge pending">
                                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="<?php echo e(route('case.dossier_with_findings', $incident)); ?>" class="action-btn">
                                        <i class="fa-solid fa-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No cases found for this filter</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/hr/escalated_to_dmo.blade.php ENDPATH**/ ?>