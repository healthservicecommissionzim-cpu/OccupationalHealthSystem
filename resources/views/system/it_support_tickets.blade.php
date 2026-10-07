<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IT Support Tickets · System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
        .header { margin-bottom: 2rem; }
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
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
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
        .table-wrapper { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); overflow: hidden; }
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
            transition: all 0.3s ease;
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
            padding: 0.35rem 0.75rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        td { 
            padding: 0.35rem 0.75rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .ticket-number { 
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
        .status-open { background: #fee2e2; color: #991b1b; }
        .status-in_progress { background: #fef3c7; color: #92400e; }
        .status-resolved { background: #d1f2eb; color: #0f766e; }
        .status-closed { background: #f3f4f6; color: #1f2937; }
        .priority-badge { 
            display: inline-block; 
            padding: 0.4rem 0.75rem; 
            border-radius: 6px; 
            font-size: 11px; 
            font-weight: 700;
        }
        .priority-low { background: #d1f2eb; color: #0f766e; }
        .priority-medium { background: #fef3c7; color: #92400e; }
        .priority-high { background: #fed7aa; color: #b45309; }
        .priority-critical { background: #fee2e2; color: #991b1b; }
        .category-badge {
            display: inline-block;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            background: #f1f5f9;
            color: #334155;
        }
        .action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #1D4533;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .action-icon:hover {
            background: #1D4533;
            color: white;
            transform: translateX(2px);
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
            gap: 0.5rem; 
            flex-wrap: wrap;
        }
        .pagination a, .pagination span { 
            padding: 0.5rem 0.75rem; 
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #4a5568;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
        }
        .pagination a:hover {
            border-color: #1D4533;
            color: #1D4533;
        }
        .pagination .active, .pagination span.active { 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white; 
            border-color: #1D4533; 
        }
        .pagination svg {
            display: inline-block !important;
            width: 14px;
            height: 14px;
            vertical-align: middle;
        }
        .pagination i {
            display: none !important;
        }
        .pagination p {
            display: none !important;
        }
        .pagination nav {
            width: 100%;
        }
        .pagination > nav > div:first-child {
            display: none !important;
        }
        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        @media (max-width: 768px) {
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .table-header { flex-direction: column; align-items: flex-start; }
            .search-box { max-width: 100%; }
            th, td { padding: 0.75rem; font-size: 12px; }
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fa-solid fa-headset"></i> IT Support Tickets</h1>
            <p>
                <span>Manage all support tickets from system users</span>
                <span class="total-count">Total: {{ number_format($tickets->total()) }}</span>
            </p>
        </div>

        <div class="stats-inline">
            <div class="stat-inline">
                <p>Total Tickets</p>
                <strong>{{ number_format($statistics['total']) }}</strong>
            </div>
            <div class="stat-inline">
                <p>Open</p>
                <strong>{{ number_format($statistics['open']) }}</strong>
            </div>
            <div class="stat-inline">
                <p>In Progress</p>
                <strong>{{ number_format($statistics['in_progress']) }}</strong>
            </div>
            <div class="stat-inline">
                <p>Critical</p>
                <strong>{{ number_format($statistics['critical']) }}</strong>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-tickets"></i> All Tickets</h2>
                <div class="search-box">
                    <input type="text" placeholder="Search by ticket #, title, or submitter...">
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Submitter</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td><span class="ticket-number">{{ $ticket->ticket_number }}</span></td>
                                <td>{{ Str::limit($ticket->title, 30) }}</td>
                                <td>
                                    <span class="category-badge">
                                        {{ str_replace('_', ' ', ucfirst($ticket->category)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="priority-badge priority-{{ $ticket->priority }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $ticket->status }}">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                                <td>{{ $ticket->user->name ?? 'Unknown' }}</td>
                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                <td style="text-align: center;">
                                    <a href="#" class="action-icon" title="View ticket">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No support tickets yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    {{ $tickets->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
