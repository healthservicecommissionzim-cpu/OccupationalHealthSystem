<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Monthly Statistics · Reports & Analytics</title>
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
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: flex-end;
            gap: 1rem;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .filter-group label {
            font-size: 12px;
            font-weight: 600;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-group select {
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .filter-group select:focus {
            outline: none;
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .btn-filter {
            background: #1D4533;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-filter:hover {
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
            gap: 1rem; 
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: white;
            border: none;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border-top: 3px solid #1D4533;
        }
        .stat-card p { 
            font-size: 11px; 
            color: #718096;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .stat-card strong { 
            font-size: 22px; 
            font-weight: 700;
            color: #1a202c;
        }
        .table-wrapper { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
        .table-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .table-header h2 { font-size: 16px; font-weight: 700; color: white; display: flex; align-items: center; gap: 0.5rem; }
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
        .progress-bar { 
            background: #e2e8f0; 
            height: 6px; 
            border-radius: 4px; 
            overflow: hidden;
            display: inline-block;
            width: 100px;
        }
        .progress-fill { 
            background: #1D4533; 
            height: 100%;
            border-radius: 4px;
        }
        .btn-export { 
            background: #1D4533; 
            color: white; 
            border: none; 
            padding: 0.75rem 1.25rem; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 13px; 
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }
        .btn-export:hover { 
            background: #0f2818;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .info-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .info-section h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .info-section p {
            font-size: 13px;
            color: #718096;
            margin-bottom: 1rem;
            line-height: 1.5;
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
        @media (max-width: 768px) {
            body { padding: 1rem 0; }
            .header h1 { font-size: 22px; }
            .filter-section { flex-direction: column; align-items: stretch; }
            .filter-group select { width: 100%; }
            th, td { padding: 0.75rem 1rem; font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fa-solid fa-chart-line"></i> Monthly Statistics</h1>
            <p>12-month trend analysis and incident patterns</p>
        </div>

        @php
            $currentYear = request('year', now()->year);
            $startYear = now()->year - 99;
        @endphp

        <div class="stats-grid">
            <div class="stat-card">
                <p>Total Incidents ({{ $currentYear }})</p>
                <strong>{{ number_format($statistics['total_incidents']) }}</strong>
            </div>
            <div class="stat-card">
                <p>Average per Month</p>
                <strong>{{ $statistics['average_per_month'] }}</strong>
            </div>
            <div class="stat-card">
                <p>Report Period</p>
                <strong>12 Months</strong>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2>
                    <i class="fa-solid fa-chart-bar"></i>
                    MONTHLY INCIDENT TRENDS - {{ $currentYear }}
                </h2>
                <form method="GET" action="{{ route('reports.monthly_statistics') }}" style="display: flex; align-items: center; gap: 1rem;">
                    <div class="filter-group" style="margin: 0;">
                        <select id="yearFilter" name="year" onchange="this.form.submit()" style="min-width: 120px;">
                            @for ($year = now()->year; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
            @if($months->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th style="text-align: center;">Incidents</th>
                                <th style="text-align: center;">% of Total</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($months as $month)
                                @php
                                    $percentage = $statistics['total_incidents'] > 0 ? round(($month->count / $statistics['total_incidents']) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td><strong style="color: #1D4533;">{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F') }}</strong></td>
                                    <td style="text-align: center;">{{ number_format($month->count) }}</td>
                                    <td style="text-align: center;">{{ $percentage }}%</td>
                                    <td>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ min($percentage * 2, 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-inbox"></i>
                                            <p>No Data Available</p>
                                            <p class="empty-state-sub">No incidents recorded for {{ $currentYear }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No Data Available</p>
                    <p class="empty-state-sub">No incidents recorded for {{ $currentYear }}</p>
                </div>
            @endif
        </div>

        <div class="info-section">
            <h3><i class="fa-solid fa-info-circle"></i> Analysis Summary</h3>
            <p>
                This report provides monthly trends for incident reporting in {{ $currentYear }}. Use this data to identify patterns, seasonal peaks, and workflow optimization opportunities.
            </p>
            <button class="btn-export"><i class="fa-solid fa-download"></i> Download Full Report</button>
        </div>
    </div>
</body>
</html>
