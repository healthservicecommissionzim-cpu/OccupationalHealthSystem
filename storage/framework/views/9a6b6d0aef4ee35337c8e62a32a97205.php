<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Incidents · Incident Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f4f9; padding: 2rem; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .header p { font-size: 13px; color: #64748b; margin-top: 4px; }
        .search-box { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; }
        .search-box form { display: flex; gap: 1rem; }
        .search-box input { flex: 1; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; }
        .search-box button { background: #1D4533; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .table-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
        .table-header { padding: 1rem; border-bottom: 1px solid #e2e8f0; }
        table { width: 100%; font-size: 11px; }
        thead { background: #f8fafc; }
        th { text-align: left; padding: 12px 16px; font-weight: 700; color: #334155; }
        td { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; }
        tr:hover { background: #f8fafc; }
        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .action-btn { background: none; border: none; cursor: pointer; color: #3b82f6; margin: 0 4px; }
        .no-results { text-align: center; padding: 3rem; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1><i class="fa-solid fa-search"></i> Search Incidents</h1>
                <p>Find cases by reference number or employee name</p>
            </div>
        </div>

        <div class="search-box">
            <form method="GET">
                <input type="text" name="q" placeholder="Search by reference number or employee name..." value="<?php echo e($query); ?>" />
                <button type="submit"><i class="fa-solid fa-search"></i> Search</button>
            </form>
        </div>

        <?php if($query): ?>
        <div class="table-card">
            <div class="table-header">
                <h2 style="font-size: 14px; font-weight: 700;">Search Results for "<?php echo e($query); ?>"</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Employee</th>
                        <th>Facility</th>
                        <th>District</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong style="color: #1D4533;"><?php echo e($incident->reference_number); ?></strong></td>
                        <td><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td><?php echo e($incident->facility->name ?? 'N/A'); ?></td>
                        <td><?php echo e($incident->facility->district->name ?? 'N/A'); ?></td>
                        <td>
                            <?php
                                $statusClass = match($incident->status) {
                                    'PENDING_SUPERVISOR_VERIFICATION', 'HR_REVIEW', 'CLINICAL_EXECUTIVE_REVIEW' => 'badge-pending',
                                    'NATIONAL_SIGN_OFF_APPROVED' => 'badge-approved',
                                    'REJECTED' => 'badge-rejected',
                                    default => 'badge-pending'
                                };
                            ?>
                            <span class="status-badge <?php echo e($statusClass); ?>"><?php echo e(str_replace('_', ' ', $incident->status)); ?></span>
                        </td>
                        <td><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                        <td style="text-align: center;">
                            <button class="action-btn"><i class="fa-solid fa-eye"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">No incidents found matching "<?php echo e($query); ?>"</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="table-card">
            <div class="no-results">
                <i class="fa-solid fa-magnifying-glass" style="font-size: 48px; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <p style="font-size: 14px; font-weight: 600; margin-bottom: 0.5rem;">Enter a search query</p>
                <p>Search by case reference number or employee name</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\search.blade.php ENDPATH**/ ?>