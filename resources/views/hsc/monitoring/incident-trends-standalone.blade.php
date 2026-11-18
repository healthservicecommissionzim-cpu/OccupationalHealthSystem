<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Repeat Incident Patterns · HSC Conditions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { margin: 0; background: #f5f7fa; color: #263149; font: 14px Inter, 'Segoe UI', Arial, sans-serif; }
        .page { max-width: 1600px; margin: auto; padding: 35px 25px 70px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .head { display: flex; gap: 14px; }
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #fef3e6; color: #d97706; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }

        /* Stats Row */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 22px; }
        .stat-card { background: white; border: 1px solid #e1e7ee; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(16,26,49,.05); }
        .stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .stat-label { font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-icon { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; font-size: 18px; }
        .stat-icon.danger { background: #fee; color: #dc2626; }
        .stat-icon.warning { background: #fef3e6; color: #d97706; }
        .stat-icon.info { background: #e0f2fe; color: #0284c7; }
        .stat-value { font-size: 32px; font-weight: 800; color: #101a31; line-height: 1; }
        .stat-subtext { font-size: 11px; color: #8a95a5; margin-top: 8px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; background: #f8fafb; display: flex; justify-content: space-between; align-items: center; }
        .card-title { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 13px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .card-body { padding: 22px; }

        /* Alert Banner */
        .alert { padding: 16px 20px; margin-bottom: 22px; border-radius: 12px; display: flex; align-items: center; gap: 12px; border-left: 4px solid; }
        .alert.warning { background: #fef3e6; border-left-color: #d97706; color: #92400e; }
        .alert.danger { background: #fee; border-left-color: #dc2626; color: #7f1d1d; }
        .alert i { font-size: 20px; }
        .alert-content { flex: 1; }
        .alert-title { font-weight: 700; font-size: 13px; margin-bottom: 4px; }
        .alert-text { font-size: 12px; opacity: 0.9; }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; cursor: pointer; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .facility-name { font-weight: 700; color: #101a31; font-size: 13px; }
        .count-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .count-badge.high { background: #fee; color: #dc2626; }
        .count-badge.medium { background: #fef3e6; color: #d97706; }
        .count-badge.low { background: #e0f2fe; color: #0284c7; }
        .date-range { font-size: 11px; color: #8a95a5; }
        .pattern-type { display: inline-block; padding: 3px 8px; background: #f1f5f9; color: #475569; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .ref-number { font-weight: 700; color: #079b6d; font-size: 12px; }
        
        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal.active { display: block; }
        .modal-content { background-color: #fefefe; margin: 3% auto; padding: 0; border-radius: 14px; width: 90%; max-width: 1200px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .modal-header { padding: 20px 25px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; background: #f8fafb; border-radius: 14px 14px 0 0; }
        .modal-title { font-size: 16px; font-weight: 750; color: #101a31; }
        .close { color: #8a95a5; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
        .close:hover { color: #dc2626; }
        .modal-body { padding: 25px; max-height: 70vh; overflow-y: auto; }
        
        /* Responsive */
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .stats { grid-template-columns: 1fr; }
            .modal-content { width: 95%; margin: 5% auto; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-repeat"></i></div>
                <div>
                    <h1 class="title">Repeat Incident Patterns</h1>
                    <div class="sub">Identify facilities with recurring occupational health incidents (Last 180 Days)</div>
                </div>
            </div>
            <a href="{{ route('dashboards.hsc_conditions') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Alert Banner -->
        @if($facilitiesWithRepeats > 0)
        <div class="alert {{ $facilitiesWithRepeats >= 10 ? 'danger' : 'warning' }}">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div class="alert-content">
                <div class="alert-title">Action Required: {{ $facilitiesWithRepeats }} Facilities with Repeat Incidents</div>
                <div class="alert-text">
                    {{ $facilitiesWithRepeats }} out of {{ $totalFacilities }} facilities ({{ round(($facilitiesWithRepeats / max($totalFacilities, 1)) * 100, 1) }}%) have experienced 2 or more incidents in the last 6 months. 
                    Immediate safety audits and corrective actions recommended.
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Facilities with Repeats</div>
                    <div class="stat-icon danger">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $facilitiesWithRepeats }}</div>
                <div class="stat-subtext">Out of {{ $totalFacilities }} total facilities reporting</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Pattern Instances</div>
                    <div class="stat-icon warning">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $repeatPatterns->count() }}</div>
                <div class="stat-subtext">Same incident type repeated at same facility</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Highest Repeat Facility</div>
                    <div class="stat-icon danger">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $repeatFacilities->first()->incident_count ?? 0 }}</div>
                <div class="stat-subtext">
                    {{ $repeatFacilities->first()->facility->name ?? 'N/A' }}
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Repeat Rate</div>
                    <div class="stat-icon {{ $facilitiesWithRepeats >= 10 ? 'danger' : 'warning' }}">
                        <i class="fa-solid fa-percent"></i>
                    </div>
                </div>
                <div class="stat-value">{{ round(($facilitiesWithRepeats / max($totalFacilities, 1)) * 100, 1) }}%</div>
                <div class="stat-subtext">Facilities experiencing repeat incidents</div>
            </div>
        </div>

        <!-- Facilities with Repeat Incidents -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-building-circle-exclamation"></i>
                    Facilities with Repeat Incidents
                </div>
                <div style="font-size: 11px; color: #8a95a5;">
                    <i class="fa-solid fa-info-circle"></i> Click any row to view detailed incident history
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($repeatFacilities->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>RANK</th>
                            <th>FACILITY NAME</th>
                            <th>INCIDENT COUNT</th>
                            <th>DATE RANGE</th>
                            <th>RISK LEVEL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repeatFacilities as $index => $facility)
                        <tr onclick="showFacilityDetails({{ $facility->facility_id }}, '{{ $facility->facility->name ?? 'Unknown' }}', {{ $facility->incident_count }})">
                            <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $index + 1 }}</td>
                            <td>
                                <div class="facility-name">{{ $facility->facility->name ?? 'Unknown Facility' }}</div>
                                <div style="font-size: 11px; color: #8a95a5;">{{ $facility->facility->facility_type ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="count-badge {{ $facility->incident_count >= 5 ? 'high' : ($facility->incident_count >= 3 ? 'medium' : 'low') }}">
                                    {{ $facility->incident_count }} incidents
                                </span>
                            </td>
                            <td>
                                <div class="date-range">
                                    {{ \Carbon\Carbon::parse($facility->first_incident)->format('M d, Y') }}
                                    <i class="fa-solid fa-arrow-right" style="font-size: 9px; margin: 0 4px;"></i>
                                    {{ \Carbon\Carbon::parse($facility->last_incident)->format('M d, Y') }}
                                </div>
                            </td>
                            <td>
                                @if($facility->incident_count >= 5)
                                    <span style="color: #dc2626; font-weight: 700;"><i class="fa-solid fa-circle-exclamation"></i> Critical</span>
                                @elseif($facility->incident_count >= 3)
                                    <span style="color: #d97706; font-weight: 700;"><i class="fa-solid fa-triangle-exclamation"></i> High</span>
                                @else
                                    <span style="color: #0284c7; font-weight: 700;"><i class="fa-solid fa-circle-info"></i> Moderate</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align: center; padding: 60px 20px; color: #8a95a5;">
                    <i class="fa-solid fa-check-circle" style="font-size: 48px; color: #079b6d; margin-bottom: 12px; display: block;"></i>
                    <div style="font-size: 16px; font-weight: 700; color: #101a31; margin-bottom: 6px;">No Repeat Incidents Found</div>
                    <div style="font-size: 13px;">All facilities have reported fewer than 2 incidents in the last 180 days</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Specific Pattern Repeats (Same Type at Same Facility) -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-diagram-project"></i>
                    Specific Pattern Repeats (Same Incident Type, Same Facility)
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($repeatPatterns->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>FACILITY</th>
                            <th>INCIDENT TYPE</th>
                            <th>OCCURRENCES</th>
                            <th>PATTERN SEVERITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repeatPatterns as $index => $pattern)
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #8a95a5;">{{ $index + 1 }}</td>
                            <td>
                                <div class="facility-name">{{ $pattern->facility->name ?? 'Unknown' }}</div>
                            </td>
                            <td>
                                <span class="pattern-type">{{ $pattern->incidentType->name ?? 'Unknown Type' }}</span>
                            </td>
                            <td>
                                <span class="count-badge {{ $pattern->pattern_count >= 4 ? 'high' : 'medium' }}">
                                    {{ $pattern->pattern_count }}x
                                </span>
                            </td>
                            <td>
                                @if($pattern->pattern_count >= 4)
                                    <span style="color: #dc2626; font-weight: 700;"><i class="fa-solid fa-fire"></i> Systemic Issue</span>
                                @elseif($pattern->pattern_count >= 3)
                                    <span style="color: #d97706; font-weight: 700;"><i class="fa-solid fa-exclamation-triangle"></i> Recurring Problem</span>
                                @else
                                    <span style="color: #0284c7; font-weight: 700;"><i class="fa-solid fa-eye"></i> Monitor</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align: center; padding: 60px 20px; color: #8a95a5;">
                    <i class="fa-solid fa-shield-check" style="font-size: 48px; color: #079b6d; margin-bottom: 12px; display: block;"></i>
                    <div style="font-size: 16px; font-weight: 700; color: #101a31; margin-bottom: 6px;">No Pattern Repeats Detected</div>
                    <div style="font-size: 13px;">No facility has experienced the same incident type multiple times</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for Facility Incident Details -->
    <div id="facilityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Facility Incident History</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <table id="incidentTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>REFERENCE</th>
                            <th>DATE</th>
                            <th>EMPLOYEE</th>
                            <th>INCIDENT TYPE</th>
                            <th>SEVERITY</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="incidentTableBody">
                        <!-- Content loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Prepare data from Laravel
        const facilityIncidents = @json($facilityIncidents);
        
        // Modal Functions
        const modal = document.getElementById('facilityModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalTableBody = document.getElementById('incidentTableBody');
        const closeBtn = document.getElementsByClassName('close')[0];

        closeBtn.onclick = function() {
            modal.classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.remove('active');
            }
        }

        function showFacilityDetails(facilityId, facilityName, incidentCount) {
            modalTitle.innerHTML = `<i class="fa-solid fa-building"></i> ${facilityName} - ${incidentCount} Incidents`;
            
            const incidents = facilityIncidents[facilityId] || [];
            
            if (incidents.length === 0) {
                modalTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #8a95a5;">No incidents found</td></tr>';
            } else {
                modalTableBody.innerHTML = incidents.map((incident, index) => `
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #8a95a5;">${index + 1}</td>
                        <td><span class="ref-number">${incident.reference_number}</span></td>
                        <td>${new Date(incident.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                        <td>${incident.first_name} ${incident.surname}</td>
                        <td><span class="pattern-type">${incident.incident_type?.name || 'Unknown'}</span></td>
                        <td>${formatSeverity(incident.severity)}</td>
                        <td>${formatStatus(incident.status)}</td>
                    </tr>
                `).join('');
            }
            
            modal.classList.add('active');
        }

        function formatSeverity(severity) {
            const severityMap = {
                'MINOR': '<span style="color: #0284c7;">Minor</span>',
                'MODERATE': '<span style="color: #d97706;">Moderate</span>',
                'MAJOR': '<span style="color: #dc2626;">Major</span>',
                'CRITICAL': '<span style="color: #7f1d1d; font-weight: 700;">Critical</span>'
            };
            return severityMap[severity] || '<span style="color: #8a95a5;">N/A</span>';
        }

        function formatStatus(status) {
            const statusMap = {
                'PENDING_SUPERVISOR_VERIFICATION': 'Pending Verification',
                'VERIFIED_SUPERVISOR': 'Supervisor Verified',
                'ESCALATED_TO_HR': 'Escalated to HR',
                'RESOLVED': 'Resolved',
                'CLOSED': 'Closed'
            };
            return statusMap[status] || status?.replace(/_/g, ' ') || 'Pending';
        }
    </script>
</body>
</html>
