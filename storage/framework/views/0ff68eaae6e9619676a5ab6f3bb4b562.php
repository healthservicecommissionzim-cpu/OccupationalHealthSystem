<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
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
        .header p { 
            font-size: 14px; 
            color: #64748b; 
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .total-count {
            font-size: 13px;
            color: #1D4533;
            font-weight: 700;
            background: rgba(29, 69, 51, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 6px;
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
        .ticket-id { 
            font-family: Tahoma, sans-serif;
            font-weight: 600; 
            color: #1D4533;
        }
        .status-badge { 
            display: inline-block; 
            padding: 0.4rem 0.75rem; 
            border-radius: 6px; 
            font-size: 11px; 
            font-weight: 700;
        }
        .status-open { background: #dbeafe; color: #1e40af; }
        .status-in-progress { background: #fef3c7; color: #92400e; }
        .status-resolved { background: #d1f2eb; color: #0f766e; }
        .status-closed { background: #f3f4f6; color: #4b5563; }
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
                <h3><i class="fa-solid fa-headset"></i> Support Tickets</h3>
                <a href="<?php echo e(route(auth()->user()->getDashboardRoute())); ?>" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> Ticket List [<?php echo e($tickets->total()); ?>]</h2>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <select id="statusFilter" class="filter-dropdown">
                        <option value="all">All Tickets</option>
                        <option value="open">Open</option>
                        <option value="in-progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search tickets..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Ticket ID</th>
                            <th>Originator</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th style="width: 60px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-status="<?php echo e(strtolower(str_replace('_', '-', $ticket->status))); ?>">
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="ticket-id"><?php echo e($ticket->ticket_number); ?></span></td>
                                <td><?php echo e($ticket->originator->name ?? 'Unknown'); ?></td>
                                <td><?php echo e($ticket->title); ?></td>
                                <td><?php echo e($ticket->category); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo e(strtolower(str_replace('_', '-', $ticket->status))); ?>">
                                        <?php echo e(ucwords(str_replace('_', ' ', $ticket->status))); ?>

                                    </span>
                                </td>
                                <td><?php echo e($ticket->created_at->format('M d, Y')); ?></td>
                                <td><?php echo e($ticket->updated_at->format('M d, Y')); ?></td>
                                <td style="text-align: center;">
                                    <a href="<?php echo e(route('support.ticket.show', $ticket)); ?>" style="color: #1D4533; text-decoration: none; font-weight: 600; font-size: 12px;">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No tickets found</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($tickets->hasPages()): ?>
            <div class="pagination-wrapper">
                <div class="pagination">
                    <?php echo e($tickets->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        const statusFilters = {
            'open': ['OPEN'],
            'in-progress': ['IN_PROGRESS'],
            'resolved': ['RESOLVED'],
            'closed': ['CLOSED']
        };

        function filterTable() {
            const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('table tbody tr');
            let visibleCount = 0;

            rows.forEach((row) => {
                // Skip empty state row
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                    return;
                }

                const text = row.textContent.toLowerCase();
                const rowStatus = row.getAttribute('data-status') || '';
                
                // Check search term match
                const searchMatch = searchTerm === '' || text.includes(searchTerm);
                
                // Check status filter match
                let statusMatch = true;
                if (statusFilter !== 'all') {
                    statusMatch = statusFilters[statusFilter]?.some(status => 
                        rowStatus.includes(status.toLowerCase().replace('_', '-'))
                    ) || false;
                }
                
                if (searchMatch && statusMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update the count in the header
            const headerH2 = document.querySelector('.table-header h2');
            if (headerH2) {
                headerH2.innerHTML = '<i class="fa-solid fa-table-list"></i> Ticket List [' + visibleCount + ']';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
        });
    </script>
</body>
</html><?php /**PATH D:\OccupationalHealth System\resources\views/support_tickets/index.blade.php ENDPATH**/ ?>