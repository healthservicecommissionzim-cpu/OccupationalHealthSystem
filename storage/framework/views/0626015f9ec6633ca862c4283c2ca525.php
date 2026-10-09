<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>My Support Tickets</title>
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
        .quick-actions {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #1D4533;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            text-decoration: none;
        }
        .btn-primary:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
        .empty-state a {
            color: #1D4533;
            text-decoration: none;
            font-weight: 600;
        }
        .empty-state a:hover {
            text-decoration: underline;
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
            display: none;
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
    <div class="container" style="max-width: 1200px;">
        <div class="header">
            <div class="header-top">
                <h3><i class="fa-solid fa-ticket"></i> My Support Tickets</h3>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>



        <!-- Tickets Table -->
        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> My Tickets [<?php echo e($tickets->total()); ?>]</h2>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
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
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Replies</th>
                            <th>Created</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-status="<?php echo e(strtolower(str_replace('_', '-', $ticket->status))); ?>" style="cursor: pointer;">
                                <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="ticket-id" onclick="openTicketModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>', '<?php echo e($ticket->title); ?>')"><?php echo e($ticket->ticket_number); ?></span></td>
                                <td onclick="openTicketModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>', '<?php echo e($ticket->title); ?>')"><?php echo e($ticket->title); ?></td>
                                <td><?php echo e($ticket->category); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo e(strtolower(str_replace('_', '-', $ticket->status))); ?>">
                                        <?php echo e(ucwords(str_replace('_', ' ', $ticket->status))); ?>

                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <?php if($ticket->replies->count() > 0): ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.6rem; background: #d1f2eb; color: #0f766e; border-radius: 4px; font-weight: 600; font-size: 12px; cursor: pointer;" onclick="openRepliesModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>')">
                                            <i class="fa-solid fa-comment"></i> <?php echo e($ticket->replies->count()); ?>

                                        </span>
                                    <?php else: ?>
                                        <span style="color: #a0aec0; font-size: 12px;">No replies</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($ticket->created_at->format('M d, Y')); ?></td>
                                <td><?php echo e($ticket->updated_at->format('M d, Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>You haven't created any support tickets yet.</p>
                                        <br>
                                        <a href="<?php echo e(route('support.create_ticket')); ?>">Create your first ticket</a>
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
    
    <!-- Modal for viewing replies -->
    <div id="repliesModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
        <div style="background: white; margin: 2rem auto; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 id="modalTitle" style="font-size: 18px; font-weight: 700; color: #1D4533; margin: 0;">Replies</h2>
                <button onclick="closeRepliesModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            <div id="modalContent" style="padding: 1.5rem;">
                <!-- Replies will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Modal for viewing ticket details -->
    <div id="ticketModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
        <div style="background: white; margin: 2rem auto; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 id="ticketModalTitle" style="font-size: 18px; font-weight: 700; color: #1D4533; margin: 0;"></h2>
                <button onclick="closeTicketModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            <div id="ticketModalContent" style="padding: 1.5rem;">
                <!-- Ticket details will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        async function openRepliesModal(ticketId, ticketNumber) {
            const modal = document.getElementById('repliesModal');
            const content = document.getElementById('modalContent');
            const title = document.getElementById('modalTitle');
            
            title.innerHTML = `Replies for ${ticketNumber}`;
            content.innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #1D4533;"></i></div>';
            modal.style.display = 'block';
            
            try {
                // Use the web route which properly handles session authentication
                const response = await fetch(`/support/ticket/${ticketId}/replies-json`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                });
                
                console.log('Response Status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                console.log('Replies Response:', data);
                
                if (!data.success) {
                    content.innerHTML = '<p style="text-align: center; color: #dc2626;">' + (data.message || 'Failed to load replies') + '</p>';
                    return;
                }
                
                if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                    let repliesHTML = '';
                    data.data.forEach(reply => {
                        const replyDate = new Date(reply.created_at);
                        const formattedDate = replyDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                        repliesHTML += `
                            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f7fafc; border-radius: 8px; border-left: 3px solid #1D4533;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <strong style="color: #1D4533;">${reply.sender?.name || 'Admin'}</strong>
                                    <small style="color: #64748b;">${formattedDate}</small>
                                </div>
                                <p style="margin: 0; color: #2d3748; line-height: 1.5;">${reply.message}</p>
                            </div>
                        `;
                    });
                    content.innerHTML = repliesHTML;
                } else {
                    content.innerHTML = '<p style="text-align: center; color: #64748b;">No replies yet.</p>';
                }
            } catch (error) {
                console.error('Fetch error:', error);
                content.innerHTML = '<p style="color: #dc2626;">Error loading replies: ' + error.message + '</p>';
            }
        }

        function closeRepliesModal() {
            document.getElementById('repliesModal').style.display = 'none';
        }

        async function openTicketModal(ticketId, ticketNumber, ticketTitle) {
            const modal = document.getElementById('ticketModal');
            const content = document.getElementById('ticketModalContent');
            const title = document.getElementById('ticketModalTitle');
            
            title.innerHTML = ticketNumber;
            content.innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #1D4533;"></i></div>';
            modal.style.display = 'block';
            
            try {
                const response = await fetch(`/api/tickets/${ticketId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const ticket = data.data;
                    content.innerHTML = `
                        <div style="space-y: 1rem;">
                            <div style="margin-bottom: 1rem;">
                                <label style="color: #64748b; font-size: 12px; font-weight: 600;">Title</label>
                                <p style="margin: 0.3rem 0 0 0; color: #1D4533; font-weight: 600;">${ticket.title}</p>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="color: #64748b; font-size: 12px; font-weight: 600;">Category</label>
                                <p style="margin: 0.3rem 0 0 0; color: #2d3748;">${ticket.category}</p>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="color: #64748b; font-size: 12px; font-weight: 600;">Description</label>
                                <p style="margin: 0.3rem 0 0 0; color: #2d3748; line-height: 1.5;">${ticket.description}</p>
                            </div>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <a href="/support/ticket/${ticketId}" style="display: inline-block; padding: 0.6rem 1.2rem; background: #1D4533; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13px;">View Full Conversation</a>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                content.innerHTML = '<p style="color: #dc2626;">Error loading ticket: ' + error.message + '</p>';
            }
        }

        function closeTicketModal() {
            document.getElementById('ticketModal').style.display = 'none';
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const repliesModal = document.getElementById('repliesModal');
            const ticketModal = document.getElementById('ticketModal');
            
            if (event.target === repliesModal) {
                repliesModal.style.display = 'none';
            }
            if (event.target === ticketModal) {
                ticketModal.style.display = 'none';
            }
        });
    </script>
    
    <script>
        const statusFilters = {
            'open': ['OPEN'],
            'in-progress': ['IN_PROGRESS'],
            'resolved': ['RESOLVED'],
            'closed': ['CLOSED']
        };

        function filterTable() {
            const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            let visibleCount = 0;

            rows.forEach((row) => {
                // Skip empty state row
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                    return;
                }

                const text = row.textContent.toLowerCase();
                
                // Check search term match
                const searchMatch = searchTerm === '' || text.includes(searchTerm);
                
                if (searchMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update the count in the header
            const headerH2 = document.querySelector('.table-header h2');
            if (headerH2) {
                headerH2.innerHTML = '<i class="fa-solid fa-table-list"></i> My Tickets [' + visibleCount + ']';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', filterTable);
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\support_tickets\my_tickets.blade.php ENDPATH**/ ?>