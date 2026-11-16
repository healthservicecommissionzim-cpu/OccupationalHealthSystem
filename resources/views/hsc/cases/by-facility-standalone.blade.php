<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Cases by Facility Summary · HSC Conditions</title>
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
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 280px; }
        .search-box:focus-within { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        .filter-select { padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover { border-color: #079b6d; }
        .filter-select:focus { border-color: #079b6d; box-shadow: 0 0 0 3px rgba(7, 155, 109, 0.1); }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 14px 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 16px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; vertical-align: top; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .year-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; background: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 750; }
        .facility-name { font-weight: 700; color: #101a31; font-size: 13px; display: block; }
        .location-info { color: #718096; font-size: 11px; margin-top: 3px; }
        .count-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; background: #e9f8f3; color: #067c58; font-size: 13px; font-weight: 750; text-align: center; }
        .description-text { line-height: 1.6; color: #4e596c; font-size: 12px; }
        .case-type { display: inline-block; margin-right: 10px; margin-bottom: 5px; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Stats Summary */
        .stats-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; padding: 20px; background: #f8fafb; border-bottom: 1px solid #e4e9ee; }
        .stat-item { text-align: center; }
        .stat-value { display: block; font-size: 24px; font-weight: 750; color: #101a31; margin-bottom: 5px; }
        .stat-label { display: block; font-size: 11px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .chead { flex-direction: column; align-items: stretch; }
            .filters { flex-direction: column; }
            .search-box { min-width: 100%; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
            .stats-summary { grid-template-columns: 1fr; }
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
                    <h1 class="title">Cases by Facility Summary</h1>
                    <div class="sub">Aggregated incident summary by facility and year</div>
                    @php
                        $totalIncidents = $facilitySummary->sum('total_cases');
                        $uniqueFacilities = $facilitySummary->pluck('facility_name')->unique()->count();
                        $years = $facilitySummary->pluck('year')->unique()->sort()->values();
                    @endphp
                    <span class="case">{{ $totalIncidents }} total incidents · {{ $uniqueFacilities }} facilities · Years: {{ $years->join(', ') }}</span>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Main Card -->
        <div class="card">
            <!-- Statistics Summary -->
            <div class="stats-summary">
                <div class="stat-item">
                    <span class="stat-value">{{ $facilitySummary->count() }}</span>
                    <span class="stat-label">Total Records</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $uniqueFacilities }}</span>
                    <span class="stat-label">Unique Facilities</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $totalIncidents }}</span>
                    <span class="stat-label">Total Cases</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $years->count() }}</span>
                    <span class="stat-label">Years Covered</span>
                </div>
            </div>
            
            <!-- Card Header -->
            <div class="chead">
                <div class="ctitle">
                    <i class="fa-solid fa-table"></i>
                    FACILITY INCIDENT SUMMARY
                </div>
                <div class="filters">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Search by facility name, year, or case type..." />
                    </div>
                    <select id="yearFilter" class="filter-select">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Summary Table -->
            @if($facilitySummary->count() > 0)
                <div class="table-container">
                    <table id="summaryTable">
                        <thead>
                            <tr>
                                <th style="width: 60px; text-align: center;">#</th>
                                <th style="width: 100px;">YEAR</th>
                                <th>FACILITY NAME</th>
                                <th>LOCATION</th>
                                <th style="width: 100px; text-align: center;">COUNT</th>
                                <th>CASE TYPES DESCRIPTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facilitySummary as $summary)
                                <tr data-year="{{ $summary['year'] }}">
                                    <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="year-badge">{{ $summary['year'] }}</span>
                                    </td>
                                    <td>
                                        <span class="facility-name">{{ $summary['facility_name'] }}</span>
                                    </td>
                                    <td>
                                        <div class="location-info">
                                            <i class="fa-solid fa-location-dot"></i> {{ $summary['district'] }}, {{ $summary['province'] }}
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="count-badge">{{ $summary['total_cases'] }}</span>
                                    </td>
                                    <td>
                                        <div class="description-text">
                                            {{ $summary['description'] }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No incident data available</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const yearFilter = document.getElementById('yearFilter');
        const table = document.getElementById('summaryTable');
        const tbody = table ? table.getElementsByTagName('tbody')[0] : null;

        function filterTable() {
            if (!tbody) return;
            
            const searchValue = searchInput.value.toLowerCase();
            const yearValue = yearFilter.value;
            const rows = tbody.getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                const rowYear = row.getAttribute('data-year');
                
                const matchesSearch = text.includes(searchValue);
                const matchesYear = !yearValue || rowYear === yearValue;
                
                if (matchesSearch && matchesYear) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('keyup', filterTable);
        }
        
        if (yearFilter) {
            yearFilter.addEventListener('change', filterTable);
        }
    </script>
</body>
</html>
