<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>My Support Tickets</title>
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
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Search */
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
        tbody tr { transition: background-color 0.2s ease; cursor: pointer; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .ticket-id { font-weight: 700; color: #079b6d; font-size: 12px; cursor: pointer; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-open { background: #dbeafe; color: #1e40af; }
        .status-in-progress { background: #fff8e7; color: #c67a00; }
        .status-resolved { background: #e9f8f3; color: #067c58; }
        .status-closed { background: #f3f4f6; color: #4b5563; }
        
        .replies-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            background: #e9f8f3;
            color: #067c58;
            border-radius: 6px;
            font-weight: 650;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .replies-badge:hover {
            background: #d0e8df;
        }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        .empty-state a { color: #079b6d; text-decoration: none; font-weight: 650; }
        .empty-state a:hover { text-decoration: underline; }
        
        /* Pagination */
        .pagination-wrapper { padding: 16px 22px; background: white; border-top: 1px solid #e4e9ee; }
        .pagination { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; align-items: center; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e4e9ee; background: white; cursor: pointer; border-radius: 6px; font-size: 11px; font-weight: 650; transition: all 0.2s; text-decoration: none; color: #4e596c; min-width: 36px; text-align: center; }
        .pagination a:hover { border-color: #079b6d; color: #079b6d; background: #f0f9f7; }
        .pagination .active span { background: #079b6d; color: white; border-color: #079b6d; }
        .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; background: #f8fafb; }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 14px; max-width: 700px; width: 100%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; max-height: 90vh; overflow-y: auto; margin: auto; }
        .modal-header { position: sticky; top: 0; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; z-index: 2001; }
        .modal-header h2 { color: white; font-size: 18px; font-weight: 750; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        
        .reply-item { margin-bottom: 16px; padding: 16px; background: #f8fafb; border-radius: 10px; border-left: 3px solid #079b6d; }
        .reply-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .reply-sender { font-weight: 700; color: #101a31; font-size: 13px; }
        .reply-date { color: #8a95a5; font-size: 11px; }
        .reply-message { color: #4e596c; font-size: 12px; line-height: 1.6; margin: 0; }
        
        .detail-row { margin-bottom: 16px; }
        .detail-label { color: #8a95a5; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
        .detail-value { color: #263149; font-size: 13px; line-height: 1.5; }
        .detail-value.title { color: #101a31; font-weight: 700; font-size: 15px; }
        
        .view-full-btn { display: inline-block; padding: 10px 20px; background: #079b6d; color: white; border-radius: 8px; text-decoration: none; font-weight: 650; font-size: 12px; transition: all 0.2s; margin-top: 16px; }
        .view-full-btn:hover { background: #067c58; transform: translateY(-1px); }
        
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
                <div class="icon"><i class="fa-solid fa-ticket"></i></div>
                <div>
                    <h1 class="title">My Support Tickets</h1>
                    <div class="sub">View and manage your support requests</div>
                    <span class="case">Total: <?php echo e($tickets->total()); ?> tickets</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Tickets Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Support Tickets</div>
                <div class="filters">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by ticket ID, title, category..." />
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
                            <tr data-status="<?php echo e(strtolower(str_replace('_', '-', $ticket->status))); ?>">
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="ticket-id" onclick="openTicketModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>', '<?php echo e($ticket->title); ?>')"><?php echo e($ticket->ticket_number); ?></span></td>
                                <td onclick="openTicketModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>', '<?php echo e($ticket->title); ?>')" style="cursor: pointer;"><?php echo e($ticket->title); ?></td>
                                <td><?php echo e($ticket->category); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo e(strtolower(str_replace('_', '-', $ticket->status))); ?>">
                                        <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                        <?php echo e(ucwords(str_replace('_', ' ', $ticket->status))); ?>

                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <?php if($ticket->replies->count() > 0): ?>
                                        <span class="replies-badge" onclick="openRepliesModal(<?php echo e($ticket->id); ?>, '<?php echo e($ticket->ticket_number); ?>')">
                                            <i class="fa-solid fa-comment"></i> <?php echo e($ticket->replies->count()); ?>

                                        </span>
                                    <?php else: ?>
                                        <span style="color: #a0aec0; font-size: 11px;">No replies</span>
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
    <div id="repliesModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fa-solid fa-comments"></i> Replies</h2>
                <button class="modal-close" onclick="closeRepliesModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Replies will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Modal for viewing ticket details -->
    <div id="ticketModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="ticketModalTitle"><i class="fa-solid fa-ticket"></i> <span></span></h2>
                <button class="modal-close" onclick="closeTicketModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-body" id="ticketModalContent">
                <!-- Ticket details will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        async function openRepliesModal(ticketId, ticketNumber) {
            const modal = document.getElementById('repliesModal');
            const content = document.getElementById('modalContent');
            const title = document.getElementById('modalTitle');
            
            title.innerHTML = `<i class="fa-solid fa-comments"></i> Replies for ${ticketNumber}`;
            content.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 32px; color: #079b6d;"></i><p style="margin-top: 12px; color: #8a95a5; font-size: 12px;">Loading replies...</p></div>';
            modal.classList.add('active');
            
            try {
                const response = await fetch(`/support/ticket/${ticketId}/replies-json`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    content.innerHTML = '<p style="text-align: center; color: #dc2626; font-size: 13px;">' + (data.message || 'Failed to load replies') + '</p>';
                    return;
                }
                
                if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                    let repliesHTML = '';
                    data.data.forEach(reply => {
                        const replyDate = new Date(reply.created_at);
                        const formattedDate = replyDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                        repliesHTML += `
                            <div class="reply-item">
                                <div class="reply-header">
                                    <span class="reply-sender">${reply.sender?.name || 'Admin'}</span>
                                    <span class="reply-date">${formattedDate}</span>
                                </div>
                                <p class="reply-message">${reply.message}</p>
                            </div>
                        `;
                    });
                    content.innerHTML = repliesHTML;
                } else {
                    content.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fa-solid fa-inbox" style="font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px;"></i><p style="color: #8a95a5; font-size: 13px;">No replies yet.</p></div>';
                }
            } catch (error) {
                console.error('Fetch error:', error);
                content.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc2626;"><i class="fa-solid fa-exclamation-circle" style="font-size: 48px; display: block; margin-bottom: 12px;"></i><p style="font-size: 13px;">Error loading replies: ' + error.message + '</p></div>';
            }
        }

        function closeRepliesModal() {
            document.getElementById('repliesModal').classList.remove('active');
        }

        async function openTicketModal(ticketId, ticketNumber, ticketTitle) {
            const modal = document.getElementById('ticketModal');
            const content = document.getElementById('ticketModalContent');
            const title = document.getElementById('ticketModalTitle');
            
            title.innerHTML = `<i class="fa-solid fa-ticket"></i> <span>${ticketNumber}</span>`;
            content.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 32px; color: #079b6d;"></i><p style="margin-top: 12px; color: #8a95a5; font-size: 12px;">Loading ticket details...</p></div>';
            modal.classList.add('active');
            
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
                        <div class="detail-row">
                            <div class="detail-label">Title</div>
                            <div class="detail-value title">${ticket.title}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Category</div>
                            <div class="detail-value">${ticket.category}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">${ticket.description}</div>
                        </div>
                        <div style="text-align: center;">
                            <a href="/support/ticket/${ticketId}" class="view-full-btn">
                                <i class="fa-solid fa-external-link-alt"></i> View Full Conversation
                            </a>
                        </div>
                    `;
                }
            } catch (error) {
                content.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc2626;"><i class="fa-solid fa-exclamation-circle" style="font-size: 48px; display: block; margin-bottom: 12px;"></i><p style="font-size: 13px;">Error loading ticket: ' + error.message + '</p></div>';
            }
        }

        function closeTicketModal() {
            document.getElementById('ticketModal').classList.remove('active');
        }

        // Close modals when clicking outside
        document.getElementById('repliesModal').addEventListener('click', function(e) {
            if (e.target === this) closeRepliesModal();
        });
        
        document.getElementById('ticketModal').addEventListener('click', function(e) {
            if (e.target === this) closeTicketModal();
        });
        
        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRepliesModal();
                closeTicketModal();
            }
        });
    </script>
    
    <script>
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
                const searchMatch = searchTerm === '' || text.includes(searchTerm);
                
                if (searchMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update the count in the header
            const headerCase = document.querySelector('.case');
            if (headerCase) {
                headerCase.textContent = 'Total: ' + visibleCount + ' tickets';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', filterTable);
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\support_tickets\my_tickets.blade.php ENDPATH**/ ?>