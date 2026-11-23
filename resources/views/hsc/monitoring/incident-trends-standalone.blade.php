<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Incident Trends · HSC Conditions</title>
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
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #fff3e0; color: #f57c00; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-card { background: #fff; border: 1px solid #e1e7ee; border-radius: 10px; padding: 14px 16px; box-shadow: 0 2px 8px rgba(16,26,49,.03); }
        .stat-label { font-size: 10px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .stat-value { font-size: 24px; font-weight: 800; color: #101a31; line-height: 1; margin-bottom: 4px; }
        .stat-value.orange { color: #f57c00; }
        .stat-value.red { color: #dc2626; }
        .stat-value.blue { color: #2563eb; }
        .stat-desc { font-size: 10px; color: #718096; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        
        /* Table */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 14px 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        th.center { text-align: center; }
        td { padding: 16px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        td.center { text-align: center; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .rank { font-weight: 700; color: #8a95a5; }
        .incident-type { font-weight: 700; color: #101a31; font-size: 12px; }
        .description { color: #718096; font-size: 11px; }
        
        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .badge.blue { background: #dbeafe; color: #1e40af; }
        .badge.orange { background: #fed7aa; color: #c2410c; }
        .badge.red { background: #fecaca; color: #991b1b; }
        
        /* Button */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border: none; border-radius: 7px; background: #079b6d; color: white; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn:hover { background: #06845d; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(7, 155, 109, 0.2); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #10b981; display: block; margin-bottom: 16px; }
        .empty-state h3 { font-size: 16px; color: #101a31; font-weight: 700; margin-bottom: 8px; }
        .empty-state p { font-size: 13px; color: #718096; }
        
        /* Modal */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999; padding: 20px; }
        .modal.show { display: flex; }
        .modal-content { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); width: 100%; max-width: 1200px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; background: #f8fafb; }
        .modal-title { display: flex; align-items: center; gap: 10px; color: #101a31; font-size: 16px; font-weight: 750; }
        .modal-close { background: none; border: none; font-size: 28px; color: #8a95a5; cursor: pointer; line-height: 1; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
        .modal-close:hover { color: #263149; }
        .modal-body { padding: 24px; overflow-y: auto; flex: 1; }
        
        .facility-section { margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #e1e7ee; }
        .facility-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .facility-header { display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f8fafb; border-radius: 10px; margin-bottom: 16px; }
        .facility-name { display: flex; align-items: center; gap: 8px; font-weight: 700; color: #101a31; font-size: 14px; }
        .facility-count { background: #fed7aa; color: #c2410c; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        
        .incident-card { border: 1px solid #e1e7ee; border-radius: 10px; padding: 16px; margin-bottom: 12px; transition: all 0.2s; }
        .incident-card:hover { border-color: #079b6d; box-shadow: 0 4px 12px rgba(7, 155, 109, 0.1); }
        .incident-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .incident-ref { color: #079b6d; font-weight: 700; font-size: 11px; }
        .incident-date { color: #8a95a5; font-size: 11px; }
        .incident-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .incident-field-label { font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
        .incident-field-value { font-size: 12px; color: #263149; }
        .incident-link { color: #079b6d; font-weight: 700; text-decoration: none; }
        .incident-link:hover { text-decoration: underline; }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 650px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .stats-grid { grid-template-columns: 1fr; }
            table { font-size: 11px; }
            th, td { padding: 10px 8px; }
            .incident-grid { grid-template-columns: 1fr; }
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
                    <h1 class="title">Incident Trends Analysis</h1>
                    <div class="sub">Incident types that have occurred 2+ times in the last 180 days</div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pattern Types</div>
                <div class="stat-value">{{ $repeatPatterns->count() }}</div>
                <div class="stat-desc">Unique incident types repeating</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Repeat Incidents</div>
                <div class="stat-value orange">{{ $repeatPatterns->sum('repeat_count') }}</div>
                <div class="stat-desc">Across all patterns</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Highest Repetition</div>
                <div class="stat-value red">{{ $repeatPatterns->max('repeat_count') ?? 0 }}</div>
                <div class="stat-desc">Most repeated incident type</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Time Period</div>
                <div class="stat-value blue">180</div>
                <div class="stat-desc">Days analysis window</div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <!-- Card Header -->
            <div class="chead">
                <div class="ctitle">
                    <i class="fa-solid fa-repeat"></i>
                    REPEAT INCIDENT PATTERNS
                </div>
            </div>
            
            <!-- Table -->
            @if($repeatPatterns->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th class="center" style="width: 50px;">RANK</th>
                                <th>INCIDENT TYPE</th>
                                <th>DESCRIPTION</th>
                                <th class="center" style="width: 120px;">REPEAT COUNT</th>
                                <th class="center" style="width: 150px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repeatPatterns as $index => $pattern)
                                <tr>
                                    <td class="center">
                                        <span class="rank">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <span class="incident-type">{{ $pattern->incidentType->name ?? 'Unknown Type' }}</span>
                                    </td>
                                    <td>
                                        <span class="description">{{ $pattern->incidentType->description ?? 'No description available' }}</span>
                                    </td>
                                    <td class="center">
                                        @php
                                            $badgeClass = 'blue';
                                            if($pattern->repeat_count >= 5) {
                                                $badgeClass = 'red';
                                            } elseif($pattern->repeat_count >= 3) {
                                                $badgeClass = 'orange';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $pattern->repeat_count }}x
                                        </span>
                                    </td>
                                    <td class="center">
                                        <button 
                                            onclick="showPatternDetails({{ $pattern->incident_type_id }}, '{{ addslashes($pattern->incidentType->name ?? 'Unknown') }}', {{ $pattern->repeat_count }})"
                                            class="btn"
                                        >
                                            <i class="fa-solid fa-eye"></i> View Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-check-circle"></i>
                    <h3>No Repeat Patterns Found</h3>
                    <p>No incident types have occurred 2 or more times in the last 180 days</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="patternModal" class="modal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Pattern Details
                </div>
                <button onclick="closeModal()" class="modal-close">
                    &times;
                </button>
            </div>
            
            <!-- Modal Body -->
            <div id="modalBody" class="modal-body">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
    // Pattern details data from Laravel
    const patternDetails = @json($patternDetails);

    function showPatternDetails(patternKey, incidentType, repeatCount) {
        const modal = document.getElementById('patternModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        
        modalTitle.innerHTML = `
            <i class="fa-solid fa-clipboard-list"></i>
            ${incidentType} (${repeatCount} Incidents)
        `;
        
        const facilities = patternDetails[patternKey] || {};
        
        if (Object.keys(facilities).length === 0) {
            modalBody.innerHTML = `
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <h3>No Details Available</h3>
                    <p>Could not load incident details for this pattern</p>
                </div>
            `;
        } else {
            let html = '';
            
            Object.values(facilities).forEach((facilityData) => {
                const facility = facilityData.facility;
                const incidents = facilityData.incidents;
                const count = facilityData.count;
                
                html += `
                    <div class="facility-section">
                        <div class="facility-header">
                            <div class="facility-name">
                                <i class="fa-solid fa-hospital"></i>
                                ${facility?.name || 'Unknown Facility'}
                            </div>
                            <div class="facility-count">
                                ${count} incident${count > 1 ? 's' : ''}
                            </div>
                        </div>
                        
                        <div>
                `;
                
                incidents.forEach((incident) => {
                    html += `
                        <div class="incident-card">
                            <div class="incident-header">
                                <span class="incident-ref">
                                    <i class="fa-solid fa-hashtag"></i> ${incident.reference_number}
                                </span>
                                <span class="incident-date">
                                    ${new Date(incident.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </span>
                            </div>
                            
                            <div class="incident-grid">
                                <div>
                                    <div class="incident-field-label">Employee</div>
                                    <div class="incident-field-value">${incident.first_name} ${incident.surname}</div>
                                </div>
                                <div>
                                    <div class="incident-field-label">Description</div>
                                    <div class="incident-field-value">${incident.incident_description ? incident.incident_description.substring(0, 60) + '...' : 'No description'}</div>
                                </div>
                                <div>
                                    <div class="incident-field-label">Status</div>
                                    <div class="incident-field-value">${formatStatus(incident.status)}</div>
                                </div>
                                <div>
                                    <div class="incident-field-label">Action</div>
                                    <a href="/incidents/${incident.id}" target="_blank" class="incident-link">
                                        <i class="fa-solid fa-external-link-alt"></i> View Case
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });
            
            modalBody.innerHTML = html;
        }
        
        modal.classList.add('show');
    }

    function closeModal(event) {
        const modal = document.getElementById('patternModal');
        if (!event || event.target === modal || event.type === 'click') {
            modal.classList.remove('show');
        }
    }

    function formatStatus(status) {
        const statusMap = {
            'PENDING_SUPERVISOR_VERIFICATION': 'Pending Verification',
            'VERIFIED_SUPERVISOR': 'Supervisor Verified',
            'ESCALATED_TO_HR': 'Escalated to HR',
            'RESOLVED': 'Resolved',
            'CLOSED': 'Closed',
            'REJECTED': 'Rejected'
        };
        return statusMap[status] || status?.replace(/_/g, ' ') || 'Pending';
    }
    </script>
</body>
</html>
