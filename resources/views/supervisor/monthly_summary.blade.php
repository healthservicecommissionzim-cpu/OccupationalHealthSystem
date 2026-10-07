<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Monthly Summary · Reports</title>
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
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; }
        .stat-card.excellent { border-left: 3px solid #079b6d; }
        .stat-card.good { border-left: 3px solid #f59e0b; }
        .stat-card.low { border-left: 3px solid #ef4444; }
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
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .month-label { font-weight: 700; color: #101a31; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-badge.excellent { background: #d1f2eb; color: #0f766e; }
        .status-badge.good { background: #fef3c7; color: #92400e; }
        .status-badge.low { background: #fee2e2; color: #991b1b; }
        
        .chart-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .chart-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Progress Bar */
        .progress-wrapper { display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .progress-bar { background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; width: 100px; flex-shrink: 0; }
        .progress-fill { height: 100%; }
        .progress-text { font-weight: 700; white-space: nowrap; font-size: 11px; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 14px; max-width: 800px; width: 100%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; max-height: 90vh; overflow-y: auto; margin: auto; }
        .modal-header { position: sticky; top: 0; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; z-index: 2001; }
        .modal-header h2 { color: white; font-size: 18px; font-weight: 750; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        #monthlyChart { height: 350px; }
        
        @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr; }
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
                <div class="icon"><i class="fa-solid fa-chart-bar"></i></div>
                <div>
                    <h1 class="title">Monthly Summary</h1>
                    <div class="sub">Performance overview and trends</div>
                    <span class="period-badge" id="periodBadge">All Periods</span>
                </div>
            </div>
            <a href="{{ route('dashboards.supervisor') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Performance Trends Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Monthly Performance Trends</div>
                <div class="filters">
                    <select id="yearFilter" class="filter-select" onchange="filterByYear(this.value)">
                        <option value="all">All Years</option>
                        @php
                            $years = [];
                            if(isset($monthlyData) && count($monthlyData) > 0) {
                                foreach($monthlyData as $month) {
                                    $year = substr($month->month, 0, 4);
                                    if(!in_array($year, $years)) {
                                        $years[] = $year;
                                    }
                                }
                                rsort($years);
                            }
                        @endphp
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th style="text-align: center;">Total Cases</th>
                            <th style="text-align: center;">Verified</th>
                            <th style="text-align: center;">Compliant</th>
                            <th style="text-align: center;">Verification Rate</th>
                            <th style="text-align: center;">Performance</th>
                            <th style="text-align: center; width: 100px;">Chart</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyData as $month)
                            @php
                                $verificationRate = $month->total > 0 ? round(($month->verified / $month->total) * 100, 1) : 0;
                                $performanceClass = $verificationRate >= 75 ? 'excellent' : ($verificationRate >= 50 ? 'good' : 'low');
                                $performanceLabel = $verificationRate >= 75 ? 'Excellent' : ($verificationRate >= 50 ? 'Good' : 'Low');
                                $performanceIcon = $verificationRate >= 75 ? 'fa-arrow-up' : ($verificationRate >= 50 ? 'fa-arrow-right' : 'fa-arrow-down');
                                $trendColor = $verificationRate >= 75 ? '#079b6d' : ($verificationRate >= 50 ? '#f59e0b' : '#ef4444');
                                $monthStr = $month->month;
                                $year = substr($monthStr, 0, 4);
                            @endphp
                            <tr class="data-row" data-year="{{ $year }}">
                                <td class="month-label">{{ \Carbon\Carbon::createFromFormat('Y-m', $monthStr)->format('M Y') }}</td>
                                <td style="text-align: center;">{{ $month->total }}</td>
                                <td style="text-align: center; font-weight: 600; color: #079b6d;">{{ $month->verified }}</td>
                                <td style="text-align: center;">{{ $month->compliant }}</td>
                                <td style="text-align: center;">
                                    <div class="progress-wrapper">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="background: {{ $trendColor }}; width: {{ $verificationRate }}%;"></div>
                                        </div>
                                        <span class="progress-text" style="color: {{ $trendColor }};">{{ $verificationRate }}%</span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="status-badge {{ $performanceClass }}">
                                        <i class="fa-solid {{ $performanceIcon }}" style="font-size: 9px;"></i>{{ $performanceLabel }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button class="chart-btn" onclick="openChartModal('{{ $monthStr }}', '{{ \Carbon\Carbon::createFromFormat('Y-m', $monthStr)->format('M Y') }}')" title="View breakdown by case type">
                                        <i class="fa-solid fa-chart-pie"></i> Chart
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No monthly data available</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart Modal -->
    <div id="chartModal" class="modal">
        <div class="modal-content" style="animation: slideIn 0.3s ease;">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fa-solid fa-chart-pie"></i> Cases by Type</h2>
                <button class="modal-close" onclick="closeChartModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div id="monthlyChart"></div>
            </div>
        </div>
    </div>

    <script>
        // Update summary stats on page load
        function updateSummaryStats() {
            let totalCases = 0, totalVerified = 0, totalCompliant = 0;
            const rows = document.querySelectorAll('.data-row');
            
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    totalCases += parseInt(row.cells[1].textContent) || 0;
                    totalVerified += parseInt(row.cells[2].textContent) || 0;
                    totalCompliant += parseInt(row.cells[3].textContent) || 0;
                }
            });
            
            document.getElementById('totalCases').textContent = totalCases;
            document.getElementById('totalVerified').textContent = totalVerified;
            document.getElementById('totalCompliant').textContent = totalCompliant;
        }

        function filterByYear(year) {
            const rows = document.querySelectorAll('.data-row');
            rows.forEach(row => {
                if (year === 'all' || row.dataset.year === year) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update badge and stats
            document.getElementById('periodBadge').textContent = year === 'all' ? 'All Periods' : year;
            updateSummaryStats();
        }

        function openChartModal(monthStr, monthLabel) {
            document.getElementById('modalTitle').textContent = monthLabel + ' - Cases by Type';
            document.getElementById('chartModal').classList.add('active');
            
            fetch(`/supervisor/incidents/by-month/${monthStr}`)
                .then(response => response.json())
                .then(data => {
                    renderChart(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('monthlyChart').innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;"><i class="fa-solid fa-exclamation-circle" style="font-size: 32px; margin-bottom: 12px;"></i><p>Error loading chart data</p></div>';
                });
        }

        function closeChartModal() {
            document.getElementById('chartModal').classList.remove('active');
        }

        function renderChart(data) {
            const chartElement = document.getElementById('monthlyChart');
            chartElement.innerHTML = '';
            
            if (!data || data.length === 0) {
                chartElement.innerHTML = '<div style="text-align: center; padding: 40px; color: #8a95a5;"><i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 12px;"></i><p>No data available</p></div>';
                return;
            }

            const labels = data.map(item => item.type);
            const values = data.map(item => item.count);

            const options = {
                series: [{
                    name: 'Cases',
                    data: values
                }],
                chart: {
                    type: 'bar',
                    height: 350,
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
                    },
                    animations: {
                        enabled: true,
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 6,
                        dataLabels: {
                            position: 'top'
                        },
                        columnWidth: '60%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: ['#079b6d'],
                        fontFamily: 'Inter'
                    }
                },
                colors: ['#079b6d'],
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: '#8a95a5',
                            fontFamily: 'Inter'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Cases',
                        style: {
                            fontSize: '12px',
                            fontWeight: 700,
                            color: '#4e596c',
                            fontFamily: 'Inter'
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '11px',
                            colors: '#8a95a5',
                            fontFamily: 'Inter'
                        },
                        formatter: function(val) {
                            return Math.floor(val);
                        }
                    },
                    min: 0,
                    forceNiceScale: true
                },
                grid: {
                    borderColor: '#e4e9ee',
                    strokeDashArray: 3,
                    xaxis: { lines: { show: false } }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.1,
                        gradientToColors: ['#079b6d'],
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.6,
                        stops: [0, 100]
                    }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function(val) {
                            return val + ' case' + (val !== 1 ? 's' : '');
                        }
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter'
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'darken',
                            value: 0.15
                        }
                    },
                    active: {
                        filter: {
                            type: 'darken',
                            value: 0.15
                        }
                    }
                }
            };

            const chart = new ApexCharts(chartElement, options);
            chart.render();
        }

        // Close modal when clicking outside
        document.getElementById('chartModal').addEventListener('click', function(e) {
            if (e.target === this) closeChartModal();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeChartModal();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSummaryStats();
        });
    </script>
</body>
</html>
