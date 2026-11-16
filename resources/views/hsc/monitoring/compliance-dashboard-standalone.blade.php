<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Compliance Dashboard · HSC Conditions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1600px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .stat-card { background: #fff; border: 1px solid #e1e7ee; border-radius: 10px; padding: 14px 16px; box-shadow: 0 2px 8px rgba(16,26,49,.03); }
        .stat-label { font-size: 10px; color: #718096; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .stat-value { font-size: 24px; font-weight: 800; color: #101a31; }
        .stat-value.green { color: #067c58; }
        .stat-value.red { color: #dc2626; }
        .stat-value.blue { color: #1e40af; }
        
        /* Progress Cards */
        .progress-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .progress-card { background: #fff; border: 1px solid #e1e7ee; border-radius: 10px; padding: 16px; box-shadow: 0 2px 8px rgba(16,26,49,.03); }
        .progress-title { font-size: 12px; font-weight: 700; color: #101a31; margin-bottom: 12px; }
        .progress-bar-container { width: 100%; height: 12px; background: #e4e9ee; border-radius: 999px; overflow: hidden; margin-bottom: 10px; }
        .progress-bar { height: 100%; background: linear-gradient(90deg, #067c58, #0a9e6f); border-radius: 999px; transition: width 0.3s ease; }
        .progress-text { font-size: 11px; color: #718096; }
        .breakdown-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; }
        .breakdown-label { font-size: 12px; color: #4e596c; }
        .breakdown-value { font-size: 12px; font-weight: 700; }
        .breakdown-value.green { color: #067c58; }
        .breakdown-value.red { color: #dc2626; }
        
        /* Table Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; background: #f8fafb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .card-title { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 13px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        
        /* Search Box */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 280px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 14px 16px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 16px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .facility-name { font-weight: 700; color: #101a31; font-size: 13px; }
        .count-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; background: #e9f8f3; color: #067c58; font-size: 13px; font-weight: 750; text-align: center; min-width: 50px; }
        .compliance-badge { display: inline-flex; align-items: center; justify-content: center; padding: 6px 14px; border-radius: 999px; font-size: 11px; font-weight: 750; min-width: 60px; }
        .compliance-badge.high { background: #d1fae5; color: #065f46; }
        .compliance-badge.medium { background: #fef3c7; color: #92400e; }
        .compliance-badge.low { background: #fee2e2; color: #991b1b; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr; }
            .progress-grid { grid-template-columns: 1fr; }
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
                <div class="icon"><i class="fa-solid fa-clipboard-check"></i></div>
                <div>
                    <h1 class="title">Compliance Dashboard</h1>
                    <div class="sub">Real-time overview of system compliance metrics and facility performance</div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Cases</div>
                <div class="stat-value">{{ $totalCases }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Compliant Cases</div>
                <div class="stat-value green">{{ $compliantCases }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Non-Compliant Cases</div>
                <div class="stat-value red">{{ $nonCompliantCases }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Compliance Rate</div>
                <div class="stat-value blue">{{ $complianceRate }}%</div>
            </div>
        </div>

        <!-- Progress Cards -->
        <div class="progress-grid">
            <div class="progress-card">
                <div class="progress-title">Compliance Rate Progress</div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: {{ $complianceRate }}%"></div>
                </div>
                <div class="progress-text">{{ $complianceRate }}% of cases are fully compliant</div>
            </div>

            <div class="progress-card">
                <div class="progress-title">Compliance Breakdown</div>
                <div class="breakdown-item">
                    <span class="breakdown-label">Compliant Cases</span>
                    <span class="breakdown-value green">{{ $compliantCases }} cases</span>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-label">Non-Compliant Cases</span>
                    <span class="breakdown-value red">{{ $nonCompliantCases }} cases</span>
                </div>
            </div>
        </div>

        <!-- Facility Compliance Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-hospital"></i>
                    Facility Compliance Scores
                </div>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search facilities..." />
                </div>
            </div>
            
            @php
                $facilitiesWithCases = $facilityCompliance->filter(function($facility) {
                    return $facility->incidents_count > 0;
                });
            @endphp

            @if($facilitiesWithCases->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">#</th>
                                <th style="width: 350px;">FACILITY NAME</th>
                                <th style="width: 150px; text-align: center;">TOTAL CASES</th>
                                <th style="width: 150px; text-align: center;">COMPLIANT</th>
                                <th style="width: 150px; text-align: center;">NON-COMPLIANT</th>
                                <th style="width: 150px; text-align: center;">COMPLIANCE RATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facilitiesWithCases as $facility)
                                @php
                                    $total = $facility->incidents_count;
                                    $compliant = $facility->compliant_incidents;
                                    $nonCompliant = $total - $compliant;
                                    $percent = $total > 0 ? round(($compliant / $total) * 100, 1) : 0;
                                    
                                    $badgeClass = 'low';
                                    if ($percent >= 80) {
                                        $badgeClass = 'high';
                                    } elseif ($percent >= 50) {
                                        $badgeClass = 'medium';
                                    }
                                @endphp
                                <tr>
                                    <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="facility-name">{{ $facility->name }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="count-badge">{{ $total }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span style="font-weight: 700; color: #067c58;">{{ $compliant }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span style="font-weight: 700; color: #dc2626;">{{ $nonCompliant }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="compliance-badge {{ $badgeClass }}">
                                            {{ $percent }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No facility data available</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Live search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.querySelector('table tbody');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
