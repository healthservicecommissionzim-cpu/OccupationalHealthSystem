<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Monthly Statistics · Reports & Analytics</title>
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
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; border-left: 3px solid #079b6d; }
        .stat-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Filter Select */
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .month-name { font-weight: 700; color: #079b6d; font-size: 12px; }
        .progress-bar { background: #e2e8f0; border-radius: 4px; height: 6px; overflow: hidden; display: inline-block; width: 100px; }
        .progress-fill { background: #079b6d; height: 100%; border-radius: 4px; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        .empty-state-sub { font-size: 12px; color: #a0afc1; margin-top: 4px; }
        
        /* Info Section */
        .info-section { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; padding: 22px; margin-top: 22px; box-shadow: 0 8px 25px rgba(16,26,49,.06); }
        .info-section h3 { font-size: 14px; font-weight: 700; color: #101a31; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .info-section p { font-size: 13px; color: #718096; margin-bottom: 16px; line-height: 1.6; }
        
        .btn-export { background: #079b6d; color: white; border: none; padding: 10px 17px; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-export:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Responsive */
        @media (max-width: 900px) {
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
            .filter-select { width: 100%; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .stats-grid { grid-template-columns: 1fr; }
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
                <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
                <div>
                    <h1 class="title">Monthly Statistics</h1>
                    <div class="sub">12-month trend analysis and incident patterns</div>
                    <span class="case">Statistics Report</span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        @php
            $currentYear = request('year', now()->year);
            $startYear = now()->year - 99;
        @endphp

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number">{{ number_format($statistics['total_incidents']) }}</span>
                <div class="stat-label">Total Incidents ({{ $currentYear }})</div>
            </div>
            <div class="stat-card">
                <span class="stat-number">{{ $statistics['average_per_month'] }}</span>
                <div class="stat-label">Average per Month</div>
            </div>
            <div class="stat-card">
                <span class="stat-number">12</span>
                <div class="stat-label">Report Period</div>
            </div>
        </div>

        <!-- Monthly Trends Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-chart-bar"></i> &nbsp; Monthly Incident Trends</div>
                <div class="filters">
                    <form method="GET" action="{{ route('reports.monthly_statistics') }}" style="display: flex; align-items: center; gap: 8px;">
                        <select name="year" onchange="this.form.submit()" class="filter-select">
                            @for ($year = now()->year; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
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
                                    <td><span class="month-name">{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F') }}</span></td>
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

        <!-- Analysis Summary -->
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
