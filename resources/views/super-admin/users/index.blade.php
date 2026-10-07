<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>User Management · Super Admin</title>
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
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1D4533;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.7rem;
        }
        .user-name { 
            font-weight: 600; 
            color: #1D4533;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-pending {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
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
        /* Hide previous/next text but keep page numbers */
        .pagination a:contains("Previous"),
        .pagination a:contains("Next"),
        .pagination span:contains("Previous"),
        .pagination span:contains("Next") {
            display: none !important;
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
                <h3><i class="fa-solid fa-users"></i> User Management</h3>
                <a href="{{ route('dashboards.super_admin') }}" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Alerts -->c
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

        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> User Registry [<span id="userCount">{{ $users->total() }}</span>]</h2>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <select id="statusFilter" class="filter-dropdown">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <div class="records-per-page">
                        <label for="recordsPerPage">Show:</label>
                        <select id="recordsPerPage">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by name or email..." />
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
                    <tbody>
                        @forelse($users ?? [] as $user)
                            <tr data-status="{{ $user->status ?? '' }}">
                                <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="user-name">{{ $user->name }}</div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number ?? '-' }}</td>
                                <td>{{ $user->roles->pluck('display_name')->implode(', ') ?: '-' }}</td>
                                <td>{{ $user->province->name ?? '-' }}</td>
                                <td>{{ $user->district->name ?? '-' }}</td>
                                <td>{{ $user->facility->name ?? '-' }}</td>
                                <td>
                                    @if ($user->status === 'pending')
                                        <span class="badge badge-pending"><i class="fa-solid fa-hourglass"></i> Pending</span>
                                    @elseif ($user->status === 'approved')
                                        <span class="badge badge-approved"><i class="fa-solid fa-check"></i> Approved</span>
                                    @elseif ($user->status === 'rejected')
                                        <span class="badge badge-rejected"><i class="fa-solid fa-times"></i> Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('user-approval.show', $user) }}" class="action-btn" title="Manage User">
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
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
            document.getElementById('recordsPerPage').addEventListener('change', filterTable);

            function filterTable() {
                const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const recordsPerPage = document.getElementById('recordsPerPage').value;
                const rows = document.querySelectorAll('table tbody tr');
                let visibleCount = 0;
                let displayedCount = 0;

                rows.forEach((row, index) => {
                    // Skip empty state row
                    if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                        return;
                    }

                    const text = row.textContent.toLowerCase();
                    const rowStatus = row.getAttribute('data-status') || '';
                    
                    // Check search term match
                    const searchMatch = searchTerm === '' || text.includes(searchTerm);
                    
                    // Check status filter match
                    const statusMatch = statusFilter === 'all' || rowStatus === statusFilter;
                    
                    if (searchMatch && statusMatch) {
                        visibleCount++;
                        // Check if we should display this row based on records per page
                        const limit = parseInt(recordsPerPage);
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

                // Update the count in the header
                const headerH2 = document.querySelector('.table-header h2');
                if (headerH2) {
                    const suffix = searchTerm !== '' || statusFilter !== 'all' || recordsPerPage !== '10' ? ` [${displayedCount} of ${visibleCount} results]` : ` [${displayedCount}]`;
                    headerH2.innerHTML = '<i class="fa-solid fa-table-list"></i> User Registry' + suffix;
                }
            }
        });
    </script>
</body>
</html>
