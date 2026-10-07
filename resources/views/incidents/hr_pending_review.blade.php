<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Review · HR</title>
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
            padding: 0.4rem 0.75rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 11px;
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
            font-size: 12px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
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
        .ref-number { 
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
        .status-escalated { background: #dbeafe; color: #1e40af; }
        .status-pending { background: #fee2e2; color: #991b1b; }
        .compliance-compliant { background: #d1f2eb; color: #0f766e; }
        .compliance-partial { background: #fef3c7; color: #92400e; }
        .compliance-noncompliant { background: #fee2e2; color: #991b1b; }
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
        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            background: #1D4533;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .action-link:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(29, 69, 51, 0.2);
        }
        .action-link i {
            font-size: 11px;
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header p { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .table-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            th, td { padding: 0.75rem; font-size: 12px; }
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <h3><i class="fa-solid fa-list-check"></i> Pending Review</h3>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> Incident Details [{{ $incidents->total() }}]</h2>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search incidents..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Reference</th>
                            <th>Date Reported</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Facility</th>
                            <th>District</th>
                            <th>Incident Type</th>
                            <th>Status</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incidents as $incident)
                            <tr data-status="{{ $incident->status ?? '' }}" data-created="{{ $incident->created_at->format('Y-m-d') ?? '' }}">
                                <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $loop->iteration }}</td>
                                <td><span class="ref-number">{{ $incident->reference_number ?? 'N/A' }}</span></td>
                                <td>{{ $incident->created_at->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td>{{ trim(($incident->first_name ?? '') . ' ' . ($incident->surname ?? '')) ?: 'N/A' }}</td>
                                <td>{{ $incident->department->name ?? 'N/A' }}</td>
                                <td>{{ $incident->designation->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($incident->facility->name ?? 'N/A', 20) }}</td>
                                <td>{{ $incident->facility->district->name ?? 'N/A' }}</td>
                                <td>{{ $incident->incidentType->name ?? '-' }}</td>
                                <td>
                                    <span class="status-badge status-pending">
                                        Pending HR Review
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('incidents.hr_dossier', ['incident' => $incident]) }}" class="action-link" title="Start HR Review">
                                        <i class="fa-solid fa-arrow-right"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No incidents assigned to you</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($incidents->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    {{ $incidents->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function filterTable() {
            const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');

            rows.forEach((row) => {
                // Skip empty state row
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                    return;
                }

                const text = row.textContent.toLowerCase();
                const searchMatch = searchTerm === '' || text.includes(searchTerm);
                
                if (searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', filterTable);
        }
    </script>
</body>
</html>
