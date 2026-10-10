<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>Organizations · System Configuration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Tahoma, sans-serif; background: offwhite; padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 1rem; }
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

        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 0.75rem; 
            margin-bottom: 1.5rem;
        }
        .stat-inline { 
            background: white; 
            padding: 0.75rem 1rem; 
            border-radius: 8px; 
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-top: 3px solid #1D4533;
            transition: all 0.3s ease;
        }
        .stat-inline:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .stat-inline p { 
            font-size: 10px; 
            color: #64748b; 
            font-weight: 600; 
            margin-bottom: 0.4rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .stat-inline strong { 
            font-size: 20px; 
            font-weight: 700; 
            color: #1D4533;
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
            padding: 0.3rem 0.5rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            line-height: 1.8rem;
        }
        td { 
            padding: 0.3rem 0.5rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-family: Tahoma, sans-serif;
            line-height: 1.2;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .org-name { 
            font-weight: 600; 
            color: #1D4533;
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
            max-width: 500px;
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
            padding: 1.2rem 1.5rem;
        }
        .modal-body .form-group {
            margin-bottom: 1.2rem;
        }
        .modal-body label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .modal-body input[type="text"] {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            font-family: Tahoma, sans-serif;
            transition: all 0.2s ease;
            background: white;
            color: #2d3748;
        }
        .modal-body input[type="text"]:focus {
            outline: none;
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            flex: 1;
        }
        .btn-primary-modal {
            background: #1D4533;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-primary-modal:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .btn-secondary-modal {
            background: #e2e8f0;
            color: #2d3748;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-secondary-modal:hover {
            background: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .table-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .stats-inline { grid-template-columns: 1fr; }
            th, td { padding: 0.75rem; font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <h1><i class="fa-solid fa-sitemap"></i> Organizations</h1>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="stats-inline">
            <div class="stat-inline">
                <p>Total Organizations</p>
                <strong><?php echo e($organizations->total() ?? 0); ?></strong>
            </div>
            <div class="stat-inline">
                <p>Displayed</p>
                <strong id="orgDisplayCount"><?php echo e($organizations->count()); ?></strong>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> Organizations [<span id="orgCount"><?php echo e($organizations->count()); ?></span>]</h2>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="tableSearch" placeholder="Search by name..." />
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 35px; text-align: center;">#</th>
                            <th>Organization Name</th>
                            <th style="width: 80px; text-align: center;">Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $organizations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="org-name"><?php echo e($org->name); ?></span></td>
                                <td style="text-align: center;"><?php echo e($org->users_count ?? 0); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No organizations found</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('table tbody tr');
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                        return;
                    }

                    const text = row.textContent.toLowerCase();
                    if (searchTerm === '' || text.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('orgCount').textContent = visibleCount;
            });

            const paginationWrapper = document.querySelector('.pagination-wrapper');
            if (paginationWrapper) {
                paginationWrapper.style.display = 'none';
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/system/organizations.blade.php ENDPATH**/ ?>