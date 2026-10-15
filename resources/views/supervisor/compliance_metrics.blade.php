<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Compliance Metrics · Reports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>
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
        .period-badge { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; }
        .stat-card.compliant { border-left: 3px solid #079b6d; }
        .stat-card.partial { border-left: 3px solid #f59e0b; }
        .stat-card.noncompliant { border-left: 3px solid #ef4444; }
        .stat-card.rate { border-left: 3px solid #3b82f6; }
        .stat-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Filter */
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        /* Chart Container */
        #complianceChart { height: 300px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .case-ref { font-weight: 700; color: #079b6d; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-badge.compliant { background: #d1f2eb; color: #0f766e; }
        .status-badge.partial { background: #fef3c7; color: #92400e; }
        .status-badge.noncompliant { background: #fee2e2; color: #991b1b; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
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
                <div class="icon"><i class="fa-solid fa-shield-check"></i></div>
                <div>
                    <h1 class="title">Compliance Metrics</h1>
                    <div class="sub">Case compliance status and analysis</div>
                    <span class="period-badge">All Cases</span>
                </div>
            </div>
            <a href="{{ route('dashboards.supervisor') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- KPI Cards -->
        <div class="stats-grid">
            <!-- Total Cases -->
            <div class="stat-card">
                <span class="stat-number">{{ number_format($statistics['total_cases']) }}</span>
                <span class="stat-label">Total Cases</span>
            </div>

            <!-- Compliant Cases -->
            <div class="stat-card compliant">
                <span class="stat-number">{{ number_format($statistics['compliant']) }}</span>
                <span class="stat-label">Compliant Cases</span>
            </div>

            <!-- Partial Compliant Cases -->
            <div class="stat-card partial">
                <span class="stat-number">{{ number_format($statistics['partial_compliant']) }}</span>
                <span class="stat-label">Partial Compliant</span>
            </div>

            <!-- Non-Compliant Cases -->
            <div class="stat-card noncompliant">
                <span class="stat-number">{{ number_format($statistics['noncompliant']) }}</span>
                <span class="stat-label">Non-Compliant</span>
            </div>

            <!-- Compliance Rate (spans on smaller screens) -->
            <div class="stat-card rate" style="grid-column: 1 / -1; @media (max-width: 900px) { grid-column: auto; }">
                <span class="stat-number">{{ number_format($statistics['compliant_rate'], 1) }}%</span>
                <span class="stat-label">Compliance Rate</span>
            </div>
        </div>

        <!-- Compliance Distribution Chart -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-chart-pie"></i> &nbsp; Compliance Distribution</div>
            </div>
            <div style="padding: 22px;">
                <div id="complianceChart"></div>
            </div>
        </div>

        <!-- Compliance Breakdown Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Compliance Breakdown</div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th style="text-align: right;">Count</th>
                            <th style="text-align: right;">Percentage</th>
                            <th style="text-align: center;">Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = $statistics['total_cases'];
                            $compliantPct = $total > 0 ? round(($statistics['compliant'] / $total) * 100, 1) : 0;
                            $partialPct = $total > 0 ? round(($statistics['partial_compliant'] / $total) * 100, 1) : 0;
                            $noncompliantPct = $total > 0 ? round(($statistics['noncompliant'] / $total) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="status-badge compliant">
                                    <i class="fa-solid fa-check-circle"></i>Compliant
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #0f766e;">{{ number_format($statistics['compliant']) }}</td>
                            <td style="text-align: right; font-weight: 600; color: #0f766e;">{{ $compliantPct }}%</td>
                            <td style="text-align: center;">
                                <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="background: #079b6d; height: 100%; width: {{ $compliantPct }}%;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="status-badge partial">
                                    <i class="fa-solid fa-alert-circle"></i>Partial Compliant
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #92400e;">{{ number_format($statistics['partial_compliant']) }}</td>
                            <td style="text-align: right; font-weight: 600; color: #92400e;">{{ $partialPct }}%</td>
                            <td style="text-align: center;">
                                <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="background: #f59e0b; height: 100%; width: {{ $partialPct }}%;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="status-badge noncompliant">
                                    <i class="fa-solid fa-times-circle"></i>Non-Compliant
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #991b1b;">{{ number_format($statistics['noncompliant']) }}</td>
                            <td style="text-align: right; font-weight: 600; color: #991b1b;">{{ $noncompliantPct }}%</td>
                            <td style="text-align: center;">
                                <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="background: #ef4444; height: 100%; width: {{ $noncompliantPct }}%;"></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-info-circle"></i> &nbsp; Compliance Summary</div>
            </div>
            <div style="padding: 22px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    <div>
                        <p style="font-size: 11px; color: #8a95a5; text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">Overall Compliance Status</p>
                        @if($statistics['compliant_rate'] >= 75)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-check-circle" style="color: #079b6d; font-size: 18px;"></i>
                                <span style="font-size: 14px; font-weight: 600; color: #079b6d;">Excellent (≥75%)</span>
                            </div>
                        @elseif($statistics['compliant_rate'] >= 50)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-alert" style="color: #f59e0b; font-size: 18px;"></i>
                                <span style="font-size: 14px; font-weight: 600; color: #f59e0b;">Good (50-74%)</span>
                            </div>
                        @else
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-times-circle" style="color: #ef4444; font-size: 18px;"></i>
                                <span style="font-size: 14px; font-weight: 600; color: #ef4444;">Needs Improvement (<50%)</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #8a95a5; text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">Compliant Cases Trend</p>
                        <span style="font-size: 14px; font-weight: 600; color: #101a31;">{{ number_format($statistics['compliant']) }} of {{ number_format($statistics['total_cases']) }} cases</span>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #8a95a5; text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">Action Required</p>
                        <span style="font-size: 14px; font-weight: 600; color: #ef4444;">{{ number_format($statistics['noncompliant']) }} non-compliant cases</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @php
                $compliantCount = $statistics['compliant'];
                $partialCount = $statistics['partial_compliant'];
                $noncompliantCount = $statistics['noncompliant'];
            @endphp

            // Compliance Distribution Pie Chart
            const complianceOptions = {
                series: [{{ $compliantCount }}, {{ $partialCount }}, {{ $noncompliantCount }}],
                chart: {
                    type: 'donut',
                    height: 300,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                labels: ['Compliant', 'Partial Compliant', 'Non-Compliant'],
                colors: ['#079b6d', '#f59e0b', '#ef4444'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '12px',
                                    fontWeight: 700,
                                    color: '#8a95a5'
                                },
                                value: {
                                    show: true,
                                    fontSize: '18px',
                                    fontWeight: 750,
                                    color: '#101a31',
                                    formatter: function(val) {
                                        return val;
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Cases',
                                    fontSize: '12px',
                                    fontWeight: 700,
                                    color: '#8a95a5',
                                    formatter: function(w) {
                                        return {{ $statistics['total_cases'] }};
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        return Math.round(val) + '%';
                    },
                    style: {
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: ['#fff']
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px',
                    fontWeight: 600,
                    labels: {
                        colors: '#4e596c'
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 50
                    }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function(val) {
                            return val + ' case' + (val !== 1 ? 's' : '');
                        }
                    }
                }
            };

            const complianceChart = new ApexCharts(document.querySelector("#complianceChart"), complianceOptions);
            complianceChart.render();
        });
    </script>
</body>
</html>
