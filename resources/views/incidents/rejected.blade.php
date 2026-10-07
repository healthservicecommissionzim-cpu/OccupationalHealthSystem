<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rejected Cases · Incident Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: #e5e7eb; padding: 2rem 0; min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .header { margin-bottom: 1.5rem; }
        .header h1 { font-size: 28px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; }
        .header p { font-size: 14px; color: #718096; }
        .table-wrapper { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
        .table-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .table-header h2 { font-size: 16px; font-weight: 700; color: white; display: flex; align-items: center; gap: 0.5rem; }
        .search-box {
            flex: 1;
            max-width: 400px;
        }
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.95);
            color: #2d3748;
        }
        .search-box input::placeholder {
            color: #a0aec0;
        }
        .search-box input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; font-size: 13px; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { 
            text-align: left; 
            padding: 1rem 1.25rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        td { 
            padding: 1rem 1.25rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .ref-number { font-weight: 600; color: #1D4533; }
        .action-group {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }
        .action-btn {
            background: none;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            color: #667eea;
            transition: all 0.2s ease;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .action-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .empty-state { 
            text-align: center; 
            padding: 3rem 1.5rem;
        }
        .empty-state i { 
            font-size: 48px; 
            color: #10b981; 
            margin-bottom: 1rem; 
        }
        .empty-state p { 
            font-size: 16px; 
            color: #718096; 
            margin-bottom: 0.5rem;
        }
        .empty-state-sub { 
            font-size: 13px; 
            color: #6b7280;
        }
        .pagination-wrapper { 
            padding: 1.5rem; 
            text-align: center; 
            background: #f7fafc; 
            border-top: 1px solid #e2e8f0;
        }
        .pagination { 
            display: flex; 
            justify-content: center; 
            gap: 0.5rem; 
            flex-wrap: wrap;
        }
        .pagination a, .pagination span { 
            padding: 0.5rem 0.75rem; 
            border-radius: 6px; 
            border: 1px solid #e2e8f0;
            color: #667eea;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .pagination a:hover { 
            background: #667eea; 
            color: white;
            border-color: #667eea;
        }
        .pagination .active span { 
            background: #667eea; 
            color: white; 
            border-color: #667eea;
        }
        .pagination .disabled {
            color: #cbd5e1;
            cursor: not-allowed;
        }
        @media (max-width: 768px) {
            body { padding: 1rem 0; }
            .header h1 { font-size: 22px; }
            th, td { padding: 0.75rem 1rem; font-size: 12px; }
            .table-header { 
                padding: 1rem; 
                flex-direction: column;
                align-items: stretch;
            }
            .search-box { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fa-solid fa-times-circle"></i> Rejected Cases</h1>
            <p>Cases that have been rejected or not approved</p>
        </div>

        <div class="table-wrapper">
            @if($incidents->count() > 0)
                <div class="table-header">
                    <h2>
                        <i class="fa-solid fa-ban"></i> 
                        REJECTED CASES
                    </h2>
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search by reference, employee, or facility..." />
                    </div>
                </div>
                <div class="table-container">
                    <table id="incidentsTable">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Employee</th>
                                <th>Facility</th>
                                <th>District</th>
                                <th>Rejected Date</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incidents as $incident)
                            <tr>
                                <td><span class="ref-number">{{ $incident->reference_number }}</span></td>
                                <td>{{ $incident->first_name }} {{ $incident->surname }}</td>
                                <td>{{ $incident->facility->name ?? 'N/A' }}</td>
                                <td>{{ $incident->facility->district->name ?? 'N/A' }}</td>
                                <td>{{ $incident->updated_at->format('M d, Y') }}</td>
                                <td style="text-align: center;">
                                    <div class="action-group">
                                        <button class="action-btn" title="View Details"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No Rejected Cases</p>
                                        <p class="empty-state-sub">There are no rejected incidents to display</p>
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
            @else
                <div class="table-header">
                    <h2>
                        <i class="fa-solid fa-ban"></i> 
                        REJECTED CASES
                    </h2>
                </div>
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No Rejected Cases</p>
                    <p class="empty-state-sub">There are no rejected incidents to display</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                const table = document.getElementById('incidentsTable');
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');

                    searchInput.addEventListener('keyup', function() {
                        const searchTerm = this.value.toLowerCase();

                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchTerm) ? '' : 'none';
                        });
                    });
                }
            }
        });
    </script>
</body>
</html>
