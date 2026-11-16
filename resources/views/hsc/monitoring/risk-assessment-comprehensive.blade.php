<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Risk Assessment & Predictive Safety · HSC</title>
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
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #fee2e2; color: #dc2626; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        .btn-primary { padding: 10px 17px; border: none; border-radius: 8px; background: #dc2626; color: white; font-weight: 650; font-size: 12px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { background: #b91c1c; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: white; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; border-left: 3px solid #dc2626; }
        .stat-content { display: flex; align-items: center; gap: 12px; }
        .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: grid; place-items: center; font-size: 18px; flex-shrink: 0; }
        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
        .stat-icon.red { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; }
        .stat-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; }
        .stat-icon.crimson { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; }
        .stat-icon.gray { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .stat-number { font-size: 24px; font-weight: 750; color: #101a31; display: block; }
        .stat-label { font-size: 9px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-top: 2px; }
        
        /* Alert Banner */
        .alert-banner { padding: 18px 20px; border-radius: 10px; margin-bottom: 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; display: flex; align-items: start; gap: 14px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }
        .alert-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 10px; display: grid; place-items: center; font-size: 24px; flex-shrink: 0; }
        .alert-content h3 { margin: 0 0 6px 0; font-size: 16px; font-weight: 700; }
        .alert-content p { margin: 0; font-size: 12px; line-height: 1.6; opacity: 0.95; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .ctitle i { color: #3b82f6; }
        
        /* Filters */
        .filters-row { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; align-items: end; }
        .filter-group label { display: block; font-size: 10px; font-weight: 700; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .filter-select { width: 100%; padding: 9px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; font-size: 12px; color: #263149; font-weight: 600; cursor: pointer; outline: none; }
        .filter-select:hover, .filter-select:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        .btn-filter { width: 100%; padding: 9px 16px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-size: 12px; font-weight: 650; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-filter:hover { background: #2563eb; }
        
        /* Search Box */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; margin-bottom: 14px; }
        .search-box:focus-within { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 12px; color: #263149; }
        .search-box input::placeholder { color: #8a95a5; }
        .search-box i { color: #8a95a5; font-size: 13px; }
        
        /* Risk Matrix */
        .matrix-container { padding: 22px; overflow-x: auto; }
        .matrix-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 20px; }
        .matrix-table th { padding: 10px; text-align: center; font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .matrix-table td { padding: 14px; text-align: center; border-radius: 8px; cursor: pointer; position: relative; min-width: 100px; }
        .matrix-table td.critical { background: #dc2626; color: white; }
        .matrix-table td.high { background: #f59e0b; color: white; }
        .matrix-table td.medium { background: #eab308; color: white; }
        .matrix-table td.low { background: #10b981; color: white; }
        .matrix-score { font-size: 18px; font-weight: 700; display: block; }
        .matrix-count { font-size: 10px; margin-top: 4px; }
        .matrix-ai { font-size: 9px; margin-top: 2px; background: rgba(255,255,255,0.3); padding: 2px 6px; border-radius: 4px; display: inline-block; }
        .matrix-legend { padding-top: 18px; border-top: 1px solid #e2e8f0; display: flex; gap: 20px; flex-wrap: wrap; font-size: 11px; }
        .matrix-legend-item { display: flex; align-items: center; gap: 8px; }
        .matrix-legend-box { width: 14px; height: 14px; border-radius: 4px; }
        
        /* Table */
        .table-container { overflow-x: auto; padding: 22px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .facility-name { font-weight: 700; color: #101a31; font-size: 12px; }
        .facility-meta { font-size: 10px; color: #64748b; margin-top: 2px; }
        .hazard-desc { font-weight: 600; color: #101a31; font-size: 12px; margin-bottom: 4px; }
        .hazard-badge { display: inline-block; padding: 2px 8px; background: #e0e7ff; color: #3730a3; font-size: 9px; font-weight: 600; border-radius: 4px; }
        
        .risk-score { display: inline-block; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 13px; color: white; }
        .risk-score.critical { background: #dc2626; }
        .risk-score.high { background: #f59e0b; }
        .risk-score.medium { background: #eab308; }
        .risk-score.low { background: #10b981; }
        .risk-label { font-size: 9px; color: #64748b; margin-top: 4px; font-weight: 600; text-align: center; }
        
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; }
        .status-badge.active { background: #d1fae5; color: #065f46; }
        .status-badge.review { background: #fef3c7; color: #92400e; }
        .status-badge.expired { background: #fecaca; color: #991b1b; }
        .status-badge.warning { font-size: 9px; color: #dc2626; margin-top: 4px; font-weight: 600; }
        
        .btn-view { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; border: none; cursor: pointer; }
        .btn-view:hover { background: #2563eb; }
        
        .incident-count { font-weight: 700; font-size: 14px; }
        .incident-count.danger { color: #dc2626; }
        .incident-count.safe { color: #10b981; }
        .dod-badge { font-size: 9px; color: #dc2626; font-weight: 700; margin-top: 2px; }
        
        .priority-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; color: white; }
        .priority-badge.critical { background: #dc2626; }
        .priority-badge.high { background: #f59e0b; }
        .priority-badge.medium { background: #eab308; }
        .priority-badge.low { background: #10b981; }
        
        .action-type-badge { display: inline-block; padding: 2px 8px; font-size: 9px; font-weight: 600; border-radius: 4px; }
        .action-type-badge.corrective { background: #fef3c7; color: #92400e; }
        .action-type-badge.preventive { background: #dbeafe; color: #1e40af; }
        
        .progress-bar-container { width: 100%; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 4px; }
        .progress-bar { height: 100%; background: #3b82f6; transition: width 0.3s; }
        
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
            .filters-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .header { flex-direction: column; gap: 12px; }
        }
    </style>
</head>
<body>
    <div class="page">
        
        {{-- Page Header --}}
        <div class="header">
            <div class="head">
                <div class="icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 class="title">Risk Assessment & Predictive Safety</h1>
                    <p class="sub">AI-driven hazard analysis powered by historical IOD/DOD incident data</p>
                </div>
            </div>
            <button onclick="openCreateAssessmentModal()" class="btn-primary">
                <i class="fas fa-plus"></i> New Risk Assessment
            </button>
        </div>

        {{-- Stats KPI Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon blue">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <span class="stat-number">{{ $totalAssessments }}</span>
                        <div class="stat-label">Total Assessments</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <span class="stat-number">{{ $highRiskCount }}</span>
                        <div class="stat-label">High Risk Hazards</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon orange">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <span class="stat-number">{{ $escalationWarnings }}</span>
                        <div class="stat-label">AI Warnings</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon purple">
                        <i class="fas fa-link"></i>
                    </div>
                    <div>
                        <span class="stat-number">{{ $totalLinkedIncidents30d }}</span>
                        <div class="stat-label">Incidents (30d)</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon crimson">
                        <i class="fas fa-skull-crossbones"></i>
                    </div>
                    <div>
                        <span class="stat-number">{{ $totalLinkedDODs }}</span>
                        <div class="stat-label">Death on Duty</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon gray">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <span class="stat-number">{{ $overdueActions }}</span>
                        <div class="stat-label">Overdue CAPAs</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- AI Predictive Alert Banner --}}
        @if($aiPredictedHighRisks->count() > 0)
        <div class="alert-banner">
            <div class="alert-icon">
                <i class="fas fa-brain"></i>
            </div>
            <div class="alert-content">
                <h3>⚠️ AI Predictive Alert: {{ $aiPredictedHighRisks->count() }} Location(s) Show Elevated Risk</h3>
                <p>Our predictive analytics engine has detected {{ $aiPredictedHighRisks->count() }} location(s) where AI-calculated risk scores are <strong>higher than manual assessments</strong>, based on historical incident patterns. These areas may require immediate review and enhanced controls despite current manual ratings.</p>
            </div>
        </div>
        @endif

        {{-- Filters --}}
        <div class="card">
            <form method="GET" action="{{ route('hsc.risk.assessment') }}">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>FACILITY</label>
                        <select name="facility_id" class="filter-select">
                            <option value="">All Facilities</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ $selectedFacility == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>HAZARD CATEGORY</label>
                        <select name="hazard_category" class="filter-select">
                            <option value="">All Categories</option>
                            @foreach($hazardCategories as $category)
                                <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>STATUS</label>
                        <select name="status" class="filter-select">
                            <option value="">All Statuses</option>
                            <option value="ACTIVE" {{ $selectedStatus == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="NEEDS_REVIEW" {{ $selectedStatus == 'NEEDS_REVIEW' ? 'selected' : '' }}>Needs Review</option>
                            <option value="EXPIRED" {{ $selectedStatus == 'EXPIRED' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>

        {{-- Interactive 5x5 Risk Matrix --}}
        <div class="card">
            <div class="chead">
                <div class="ctitle">
                    <i class="fas fa-th"></i>
                    Interactive 5×5 Risk Matrix
                </div>
            </div>
            <div class="matrix-container">
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>RARE (1)</th>
                            <th>UNLIKELY (2)</th>
                            <th>POSSIBLE (3)</th>
                            <th>LIKELY (4)</th>
                            <th>ALMOST CERTAIN (5)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riskMatrix as $severity => $likelihoodRow)
                            <tr>
                                <th>
                                    @if($severity == 5) CATASTROPHIC (5)
                                    @elseif($severity == 4) MAJOR (4)
                                    @elseif($severity == 3) MODERATE (3)
                                    @elseif($severity == 2) MINOR (2)
                                    @else NEGLIGIBLE (1)
                                    @endif
                                </th>
                                @foreach($likelihoodRow as $likelihood => $cell)
                                    @php
                                        $cellClass = 'low';
                                        if ($cell['level'] === 'CRITICAL') $cellClass = 'critical';
                                        elseif ($cell['level'] === 'HIGH') $cellClass = 'high';
                                        elseif ($cell['level'] === 'MEDIUM') $cellClass = 'medium';
                                    @endphp
                                    <td class="{{ $cellClass }}">
                                        <div class="matrix-score">{{ $cell['score'] }}</div>
                                        <div class="matrix-count">{{ $cell['residual_count'] }} Current</div>
                                        @if($cell['predicted_count'] > 0)
                                            <div class="matrix-ai">
                                                <i class="fas fa-robot"></i> {{ $cell['predicted_count'] }} AI
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="matrix-legend">
                    <div class="matrix-legend-item">
                        <div class="matrix-legend-box" style="background: #dc2626;"></div>
                        <span style="color: #64748b; font-weight: 600;">Critical (20-25)</span>
                    </div>
                    <div class="matrix-legend-item">
                        <div class="matrix-legend-box" style="background: #f59e0b;"></div>
                        <span style="color: #64748b; font-weight: 600;">High (15-19)</span>
                    </div>
                    <div class="matrix-legend-item">
                        <div class="matrix-legend-box" style="background: #eab308;"></div>
                        <span style="color: #64748b; font-weight: 600;">Medium (10-14)</span>
                    </div>
                    <div class="matrix-legend-item">
                        <div class="matrix-legend-box" style="background: #10b981;"></div>
                        <span style="color: #64748b; font-weight: 600;">Low (5-9)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Risk Assessments Table --}}
        <div class="card">
            <div class="chead">
                <div class="ctitle">
                    <i class="fas fa-list-ul"></i>
                    Risk Assessments with AI Predictions
                </div>
            </div>
            <div style="padding: 18px 22px; border-bottom: 1px solid #e1e7ee;">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchTable" placeholder="Search assessments...">
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Facility/Location</th>
                            <th>Hazard</th>
                            <th style="text-align: center;">Residual Risk</th>
                            <th style="text-align: center;">AI Predicted</th>
                            <th style="text-align: center;">Incidents (30d)</th>
                            <th>Status</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riskAssessments as $assessment)
                            <tr class="risk-row">
                                <td>
                                    <div class="facility-name">{{ $assessment->facility->name }}</div>
                                    @if($assessment->specific_zone)
                                        <div class="facility-meta">Zone: {{ $assessment->specific_zone }}</div>
                                    @endif
                                    @if($assessment->department)
                                        <div class="facility-meta">Dept: {{ $assessment->department->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="hazard-desc">{{ Str::limit($assessment->hazard_description, 60) }}</div>
                                    <span class="hazard-badge">{{ $assessment->hazard_category }}</span>
                                </td>
                                <td style="text-align: center;">
                                    @php
                                        $riskClass = 'low';
                                        if ($assessment->residual_risk_level === 'CRITICAL') $riskClass = 'critical';
                                        elseif ($assessment->residual_risk_level === 'HIGH') $riskClass = 'high';
                                        elseif ($assessment->residual_risk_level === 'MEDIUM') $riskClass = 'medium';
                                    @endphp
                                    <div class="risk-score {{ $riskClass }}">{{ $assessment->residual_risk_score }}</div>
                                    <div class="risk-label">{{ $assessment->residual_risk_level }}</div>
                                </td>
                                <td style="text-align: center;">
                                    @if($assessment->predicted_risk_score)
                                        @php
                                            $predClass = 'low';
                                            if ($assessment->predicted_risk_level === 'CRITICAL') $predClass = 'critical';
                                            elseif ($assessment->predicted_risk_level === 'HIGH') $predClass = 'high';
                                            elseif ($assessment->predicted_risk_level === 'MEDIUM') $predClass = 'medium';
                                        @endphp
                                        <div class="risk-score {{ $predClass }}">
                                            <i class="fas fa-robot" style="font-size: 10px; margin-right: 4px;"></i>
                                            {{ $assessment->predicted_risk_score }}
                                        </div>
                                        <div class="risk-label">{{ $assessment->predicted_risk_level }}</div>
                                        @if($assessment->escalation_warning)
                                            <div class="status-badge warning">
                                                <i class="fas fa-exclamation-triangle"></i> AI Alert
                                            </div>
                                        @endif
                                    @else
                                        <span style="color: #94a3b8; font-size: 12px;">—</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="incident-count {{ $assessment->linked_incidents_30d > 0 ? 'danger' : 'safe' }}">
                                        {{ $assessment->linked_incidents_30d }}
                                    </div>
                                    @if($assessment->linked_dod_total > 0)
                                        <div class="dod-badge">
                                            <i class="fas fa-skull-crossbones"></i> {{ $assessment->linked_dod_total }} DOD
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($assessment->status === 'ACTIVE')
                                        <span class="status-badge active">ACTIVE</span>
                                    @elseif($assessment->status === 'NEEDS_REVIEW')
                                        <span class="status-badge review">NEEDS REVIEW</span>
                                    @elseif($assessment->status === 'EXPIRED')
                                        <span class="status-badge expired">EXPIRED</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <button onclick="viewDetails({{ $assessment->id }})" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard-list"></i>
                                        <p>No risk assessments found. Click "New Risk Assessment" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CAPA Tracker --}}
        <div class="card">
            <div class="chead">
                <div class="ctitle">
                    <i class="fas fa-tasks"></i>
                    Corrective & Preventive Actions (CAPA)
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Facility</th>
                            <th style="text-align: center;">Priority</th>
                            <th>Assigned To</th>
                            <th style="text-align: center;">Due Date</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingActions as $action)
                            <tr>
                                <td>
                                    <div class="hazard-desc">{{ Str::limit($action->action_description, 80) }}</div>
                                    <span class="action-type-badge {{ $action->action_type === 'CORRECTIVE' ? 'corrective' : 'preventive' }}">
                                        {{ $action->action_type }}
                                    </span>
                                </td>
                                <td style="font-size: 12px; color: #64748b;">
                                    {{ $action->riskAssessment->facility->name }}
                                </td>
                                <td style="text-align: center;">
                                    @php
                                        $priorityClass = 'low';
                                        if ($action->priority === 'CRITICAL') $priorityClass = 'critical';
                                        elseif ($action->priority === 'HIGH') $priorityClass = 'high';
                                        elseif ($action->priority === 'MEDIUM') $priorityClass = 'medium';
                                    @endphp
                                    <span class="priority-badge {{ $priorityClass }}">{{ $action->priority }}</span>
                                </td>
                                <td style="font-size: 12px; color: #64748b;">
                                    {{ $action->assignedTo->name ?? 'Unassigned' }}
                                </td>
                                <td style="text-align: center; font-size: 12px;">
                                    <div style="color: {{ $action->is_overdue ? '#dc2626' : '#64748b' }}; font-weight: {{ $action->is_overdue ? '700' : '500' }};">
                                        {{ $action->due_date?->format('M d, Y') ?? 'No date' }}
                                    </div>
                                    @if($action->is_overdue)
                                        <div class="dod-badge">
                                            <i class="fas fa-exclamation-circle"></i> OVERDUE
                                        </div>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($action->status === 'PENDING')
                                        <span class="status-badge review">PENDING</span>
                                    @elseif($action->status === 'IN_PROGRESS')
                                        <span class="status-badge active" style="background: #dbeafe; color: #1e40af;">IN PROGRESS</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div style="font-size: 13px; font-weight: 700; color: #3b82f6;">{{ $action->completion_percentage }}%</div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" style="width: {{ $action->completion_percentage }}%;"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-tasks"></i>
                                        <p>No pending corrective actions.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // Live search
        document.getElementById('searchTable').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.risk-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function viewDetails(id) {
            alert('View details for risk assessment ID: ' + id + '\n\nThis will open a detailed modal showing:\n- Full hazard description\n- All control measures\n- AI predictive insights\n- Historical incidents\n- Audit trail\n- Corrective actions');
        }

        function openCreateAssessmentModal() {
            alert('Create New Risk Assessment Modal\n\nThis will open a form to:\n1. Select facility and location\n2. Describe the hazard\n3. Score inherent risk (before controls)\n4. List control measures by hierarchy\n5. Score residual risk (after controls)\n6. AI will automatically calculate predictive risk');
        }
    </script>
</body>
</html>
