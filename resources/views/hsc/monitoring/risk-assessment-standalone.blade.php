<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Risk Assessment & Management · HSC Conditions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1800px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #fef3c7; color: #d97706; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Alert Banner */
        .alert-banner { background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border: 2px solid #f59e0b; border-radius: 12px; padding: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; }
        .alert-icon { width: 50px; height: 50px; background: white; border-radius: 50%; display: grid; place-items: center; font-size: 24px; color: #d97706; flex-shrink: 0; }
        .alert-content h3 { font-size: 16px; font-weight: 750; color: #78350f; margin-bottom: 5px; }
        .alert-content p { font-size: 13px; color: #92400e; line-height: 1.5; }
        
        /* Risk Matrix */
        .risk-matrix-section { background: white; border: 1px solid #e1e7ee; border-radius: 14px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(16,26,49,.04); }
        .section-title { font-size: 14px; font-weight: 750; color: #101a31; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
        .risk-matrix { display: grid; grid-template-columns: auto repeat(5, 1fr); gap: 8px; margin-top: 15px; }
        .matrix-cell { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; border-radius: 6px; }
        .matrix-label { font-size: 10px; font-weight: 700; color: #4e596c; display: flex; align-items: center; justify-content: center; text-transform: uppercase; }
        .risk-low { background: #d1fae5; color: #065f46; }
        .risk-medium { background: #fef3c7; color: #92400e; }
        .risk-high { background: #fed7aa; color: #9a3412; }
        .risk-critical { background: #fecaca; color: #991b1b; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 25px; }
        .stat-card { background: white; border: 1px solid #e1e7ee; border-radius: 10px; padding: 16px; box-shadow: 0 2px 8px rgba(16,26,49,.03); }
        .stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .stat-label { font-size: 11px; color: #718096; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-icon { width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center; font-size: 14px; }
        .stat-value { font-size: 28px; font-weight: 800; color: #101a31; }
        .stat-change { font-size: 11px; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; background: #f8fafb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .card-title { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 13px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        
        /* Search Box */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 280px; }
        .search-box:focus-within { border-color: #d97706; box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; position: sticky; top: 0; z-index: 10; }
        th { text-align: left; padding: 14px 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 16px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .ref-number { font-weight: 700; color: #d97706; font-size: 12px; text-decoration: none; }
        .ref-number:hover { text-decoration: underline; }
        
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .badge.critical { background: #fecaca; color: #991b1b; }
        .badge.high { background: #fed7aa; color: #9a3412; }
        .badge.medium { background: #fef3c7; color: #92400e; }
        .badge.low { background: #d1fae5; color: #065f46; }
        .badge.escalated { background: #dbeafe; color: #1e40af; }
        .badge.resolved { background: #e0e7ff; color: #3730a3; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Legend */
        .legend { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px; }
        .legend-item { display: flex; align-items: center; gap: 6px; font-size: 11px; }
        .legend-color { width: 20px; height: 20px; border-radius: 4px; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .card-header { flex-direction: column; align-items: stretch; }
            .search-box { min-width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
            .risk-matrix { grid-template-columns: auto repeat(5, 1fr); font-size: 10px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <h1 class="title">Risk Assessment & Management</h1>
                    <div class="sub">Proactive hazard identification and post-incident risk recalculation for workplace safety</div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Alert Banner -->
        <div class="alert-banner">
            <div class="alert-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="alert-content">
                <h3>Integrated IOD/DOD Risk Assessment System</h3>
                <p>This system bridges incident reporting and preventative action. Every logged Injury on Duty (IOD) or Death on Duty (DOD) automatically triggers risk reassessment for affected tasks, locations, and departments to ensure continuous workplace safety improvement.</p>
            </div>
        </div>

        <!-- Risk Matrix Section -->
        <div class="risk-matrix-section">
            <div class="section-title">
                <i class="fa-solid fa-table-cells"></i>
                Dynamic Risk Matrix (5×5 Likelihood × Severity)
            </div>
            <p style="font-size: 12px; color: #718096; margin-bottom: 15px;">
                Automated risk scoring with color-coded classification for hazard prioritization
            </p>
            
            <div class="risk-matrix">
                <!-- Empty corner -->
                <div class="matrix-cell"></div>
                <!-- Severity headers -->
                <div class="matrix-label">Negligible</div>
                <div class="matrix-label">Minor</div>
                <div class="matrix-label">Moderate</div>
                <div class="matrix-label">Major</div>
                <div class="matrix-label">Catastrophic</div>
                
                <!-- Rare -->
                <div class="matrix-label">Rare</div>
                <div class="matrix-cell risk-low">1</div>
                <div class="matrix-cell risk-low">2</div>
                <div class="matrix-cell risk-medium">3</div>
                <div class="matrix-cell risk-medium">4</div>
                <div class="matrix-cell risk-high">5</div>
                
                <!-- Unlikely -->
                <div class="matrix-label">Unlikely</div>
                <div class="matrix-cell risk-low">2</div>
                <div class="matrix-cell risk-medium">4</div>
                <div class="matrix-cell risk-medium">6</div>
                <div class="matrix-cell risk-high">8</div>
                <div class="matrix-cell risk-high">10</div>
                
                <!-- Possible -->
                <div class="matrix-label">Possible</div>
                <div class="matrix-cell risk-medium">3</div>
                <div class="matrix-cell risk-medium">6</div>
                <div class="matrix-cell risk-high">9</div>
                <div class="matrix-cell risk-high">12</div>
                <div class="matrix-cell risk-critical">15</div>
                
                <!-- Likely -->
                <div class="matrix-label">Likely</div>
                <div class="matrix-cell risk-medium">4</div>
                <div class="matrix-cell risk-high">8</div>
                <div class="matrix-cell risk-high">12</div>
                <div class="matrix-cell risk-critical">16</div>
                <div class="matrix-cell risk-critical">20</div>
                
                <!-- Almost Certain -->
                <div class="matrix-label">Almost Certain</div>
                <div class="matrix-cell risk-high">5</div>
                <div class="matrix-cell risk-high">10</div>
                <div class="matrix-cell risk-critical">15</div>
                <div class="matrix-cell risk-critical">20</div>
                <div class="matrix-cell risk-critical">25</div>
            </div>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color risk-low"></div>
                    <span>Low Risk (1-4)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color risk-medium"></div>
                    <span>Medium Risk (5-9)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color risk-high"></div>
                    <span>High Risk (10-16)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color risk-critical"></div>
                    <span>Critical Risk (17-25)</span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">High-Risk Incidents</span>
                    <div class="stat-icon" style="background: #fecaca; color: #991b1b;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #991b1b;">{{ $highRiskIncidents->count() }}</div>
                <div class="stat-change" style="color: #dc2626;">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    Last 30 Days
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Escalated Cases</span>
                    <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">
                        <i class="fa-solid fa-arrow-up-right-dots"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #1e40af;">{{ $escalatedCases->count() }}</div>
                <div class="stat-change" style="color: #2563eb;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Requires Review
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Active Incidents</span>
                    <div class="stat-icon" style="background: #fed7aa; color: #9a3412;">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #9a3412;">{{ $highRiskIncidents->count() + $escalatedCases->count() }}</div>
                <div class="stat-change" style="color: #ea580c;">
                    <i class="fa-solid fa-clock"></i>
                    Unresolved
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Review Required</span>
                    <div class="stat-icon" style="background: #fef3c7; color: #92400e;">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #92400e;">{{ $highRiskIncidents->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                <div class="stat-change" style="color: #d97706;">
                    <i class="fa-solid fa-calendar-week"></i>
                    Last 7 Days
                </div>
            </div>
        </div>

        <!-- High-Risk Incidents Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    High-Risk Incidents Requiring Assessment
                </div>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchHighRisk" placeholder="Search incidents..." />
                </div>
            </div>
            
            @if($highRiskIncidents->count() > 0)
                <div class="table-container">
                    <table id="highRiskTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 150px;">REFERENCE</th>
                                <th style="width: 120px;">DATE</th>
                                <th>EMPLOYEE</th>
                                <th>FACILITY</th>
                                <th style="width: 150px;">INCIDENT TYPE</th>
                                <th style="width: 120px;">DAYS OPEN</th>
                                <th style="width: 150px;">STATUS</th>
                                <th style="width: 100px; text-align: center;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($highRiskIncidents as $incident)
                                @php
                                    $daysOpen = now()->diffInDays($incident->created_at);
                                    $riskLevel = 'medium';
                                    if ($daysOpen > 14) $riskLevel = 'critical';
                                    elseif ($daysOpen > 7) $riskLevel = 'high';
                                @endphp
                                <tr>
                                    <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('incidents.hsc_view', $incident) }}" class="ref-number">
                                            {{ $incident->reference_number }}
                                        </a>
                                    </td>
                                    <td>{{ $incident->created_at->format('M d, Y') }}</td>
                                    <td>{{ $incident->first_name }} {{ $incident->surname }}</td>
                                    <td>{{ Str::limit($incident->facility?->name ?? 'N/A', 30) }}</td>
                                    <td>{{ $incident->incidentType?->name ?? 'Unknown' }}</td>
                                    <td>
                                        <span class="badge {{ $riskLevel }}">
                                            <i class="fa-solid fa-clock"></i>
                                            {{ $daysOpen }} days
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ in_array($incident->status, ['ESCALATED_TO_HR', 'ESCALATED_TO_PMD']) ? 'escalated' : 'high' }}">
                                            {{ str_replace('_', ' ', $incident->status) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('incidents.hsc_view', $incident) }}" style="color: #d97706; font-weight: 700; text-decoration: none; font-size: 11px;">
                                            <i class="fa-solid fa-eye"></i> ASSESS
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-circle-check"></i>
                    <p>No high-risk incidents requiring assessment</p>
                </div>
            @endif
        </div>

        <!-- Escalated Cases Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-arrow-up-right-dots"></i>
                    Escalated Cases (Clinical & Executive Review)
                </div>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchEscalated" placeholder="Search escalated cases..." />
                </div>
            </div>
            
            @if($escalatedCases->count() > 0)
                <div class="table-container">
                    <table id="escalatedTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 150px;">REFERENCE</th>
                                <th>EMPLOYEE</th>
                                <th>FACILITY</th>
                                <th style="width: 150px;">ESCALATION LEVEL</th>
                                <th style="width: 120px;">ESCALATED ON</th>
                                <th style="width: 100px; text-align: center;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($escalatedCases as $incident)
                                <tr>
                                    <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('incidents.hsc_view', $incident) }}" class="ref-number">
                                            {{ $incident->reference_number }}
                                        </a>
                                    </td>
                                    <td>{{ $incident->first_name }} {{ $incident->surname }}</td>
                                    <td>{{ Str::limit($incident->facility?->name ?? 'N/A', 30) }}</td>
                                    <td>
                                        <span class="badge escalated">
                                            {{ str_replace(['ESCALATED_TO_', 'PENDING_', '_REVIEW'], '', $incident->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $incident->escalated_to_hr_at?->format('M d, Y') ?? $incident->created_at->format('M d, Y') }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('incidents.hsc_view', $incident) }}" style="color: #1e40af; font-weight: 700; text-decoration: none; font-size: 11px;">
                                            <i class="fa-solid fa-file-lines"></i> REVIEW
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-circle-check"></i>
                    <p>No escalated cases requiring review</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Search functionality for High-Risk table
        document.getElementById('searchHighRisk')?.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('highRiskTable');
            const rows = table?.getElementsByTagName('tbody')[0]?.getElementsByTagName('tr');
            
            if (rows) {
                for (let i = 0; i < rows.length; i++) {
                    const text = rows[i].textContent.toLowerCase();
                    rows[i].style.display = text.includes(searchValue) ? '' : 'none';
                }
            }
        });

        // Search functionality for Escalated table
        document.getElementById('searchEscalated')?.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('escalatedTable');
            const rows = table?.getElementsByTagName('tbody')[0]?.getElementsByTagName('tr');
            
            if (rows) {
                for (let i = 0; i < rows.length; i++) {
                    const text = rows[i].textContent.toLowerCase();
                    rows[i].style.display = text.includes(searchValue) ? '' : 'none';
                }
            }
        });
    </script>
</body>
</html>
