<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>All Users · Super Admin</title>
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
        .stat-card.approved { border-left: 3px solid #22c55e; }
        .stat-card.rejected { border-left: 3px solid #ef4444; }
        .stat-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Alert */
        .alert { padding: 16px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px; }
        .alert-success { background: #e9f8f3; color: #067c58; border: 1px solid #d0e8df; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert i { font-size: 18px; margin-top: 2px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .user-name { font-weight: 700; color: #079b6d; font-size: 12px; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-badge.pending { background: #fff8e7; color: #c67a00; }
        .status-badge.approved { background: #e9f8f3; color: #067c58; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Pagination */
        .pagination-wrapper { padding: 16px 22px; background: white; border-top: 1px solid #e4e9ee; }
        .pagination { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; align-items: center; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e4e9ee; background: white; cursor: pointer; border-radius: 6px; font-size: 11px; font-weight: 650; transition: all 0.2s; text-decoration: none; color: #4e596c; min-width: 36px; text-align: center; }
        .pagination a:hover { border-color: #079b6d; color: #079b6d; background: #f0f9f7; }
        .pagination .active span { background: #079b6d; color: white; border-color: #079b6d; }
        .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; background: #f8fafb; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
            .search-box { min-width: 100%; }
            table { table-layout: auto; }
            th:nth-child(4), th:nth-child(5), th:nth-child(6), th:nth-child(7), th:nth-child(8),
            td:nth-child(4), td:nth-child(5), td:nth-child(6), td:nth-child(7), td:nth-child(8) { display: none; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .stats-grid { grid-template-columns: 1fr; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
            th:nth-child(3), td:nth-child(3) { display: none; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h1 class="title">User Management</h1>
                    <div class="sub">Review and manage all system users</div>
                    <span class="case">Total: {{ $users->total() }} users</span>
                </div>
            </div>
            <a href="{{ route('dashboards.super_admin') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Users Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; User Registry</div>
                <div class="filters">
                    <select id="statusFilter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by name, email, phone..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role Assigned</th>
                            <th>Province</th>
                            <th>District</th>
                            <th>Facility</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="usersTable">
                        @forelse($users as $user)
                            <tr data-status="{{ $user->status ?? '' }}">
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                <td><span class="user-name">{{ $user->name }}</span></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                <td>{{ $user->roles->pluck('display_name')->implode(', ') ?: 'N/A' }}</td>
                                <td>{{ $user->province->name ?? 'N/A' }}</td>
                                <td>{{ $user->district->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($user->facility->name ?? 'N/A', 25) }}</td>
                                <td>
                                    @if ($user->status === 'pending')
                                        <span class="status-badge pending">
                                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                            Pending
                                        </span>
                                    @elseif ($user->status === 'approved')
                                        <span class="status-badge approved">
                                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                            Approved
                                        </span>
                                    @elseif ($user->status === 'rejected')
                                        <span class="status-badge rejected">
                                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('user-approval.show', $user) }}" class="action-btn">
                                        <i class="fa-solid fa-cog"></i> Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No users found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Search and Filter
        function filterTable() {
            const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#usersTable tr');
            
            rows.forEach(row => {
                // Skip empty state row
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) return;
                
                const text = row.textContent.toLowerCase();
                const rowStatus = row.getAttribute('data-status') || '';
                
                const searchMatch = searchTerm === '' || text.includes(searchTerm);
                
                let statusMatch = true;
                if (statusFilter !== 'all') {
                    statusMatch = rowStatus === statusFilter;
                }
                
                row.style.display = (searchMatch && statusMatch) ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
        });
    </script>
</body>
</html>
