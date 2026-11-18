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

        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 22px; }
        .stat-card { background: white; border: 1px solid #e1e7ee; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(16,26,49,.05); }
        .stat-label { font-size: 11px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
        .stat-value { font-size: 32px; font-weight: 800; color: #101a31; line-height: 1; }
        .stat-subtext { font-size: 11px; color: #8a95a5; margin-top: 8px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; background: #f8fafb; }
        .card-title { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 13px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .card-body { padding: 0; }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafb; }
        th { text-align: left; padding: 14px 16px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        th.center { text-align: center; }
        td { padding: 16px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 13px; }
        td.center { text-align: center; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .incident-type { font-weight: 700; color: #101a31; font-size: 14px; }
        .injury-type { display: inline-block; padding: 4px 10px; background: #e0f2fe; color: #0284c7; border-radius: 6px; font-size: 11px; font-weight: 600; margin-top: 4px; }
        .count-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 50px; padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 800; }
        .count-badge.critical { background: #fee; color: #dc2626; }
        .count-badge.high { background: #fef3e6; color: #d97706; }
        .count-badge.medium { background: #dbeafe; color: #2563eb; }
        .view-btn { padding: 8px 16px; background: #1bb74d; color: white; border: none; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .view-btn:hover { background: #159f41; transform: translateY(-1px); }
        
        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); animation: fadeIn 0.2s ease; }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background-color: #fefefe; margin: 20px; padding: 0; border-radius: 16px; width: 95%; max-width: 1400px; max-height: 90vh; box-shadow: 0 25px 80px rgba(0,0,0,0.4); animation: slideIn 0.3s ease; overflow: hidden; display: flex; flex-direction: column; }
        .modal-header { padding: 22px 28px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f8fafb 0%, #fff 100%); flex-shrink: 0; }
        .modal-title { font-size: 18px; font-weight: 750; color: #101a31; display: flex; align-items: center; gap: 10px; }
        .close { color: #8a95a5; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; transition: all 0.2s; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
        .close:hover { color: #dc2626; background: #fee; }
        .modal-body { padding: 28px; overflow-y: auto; flex: 1; }
        
        .facility-section { margin-bottom: 28px; padding-bottom: 28px; border-bottom: 2px solid #e4e9ee; }
        .facility-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .facility-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 16px; background: #f8fafb; border-radius: 10px; }
        .facility-name { font-size: 16px; font-weight: 700; color: #101a31; display: flex; align-items: center; gap: 8px; }
        .facility-count { background: #fef3e6; color: #d97706; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; }
        
        .incident-card { background: white; border: 1px solid #e4e9ee; border-radius: 10px; padding: 16px; margin-bottom: 12px; transition: all 0.2s; }
        .incident-card:hover { border-color: #1bb74d; box-shadow: 0 4px 12px rgba(27, 183, 77, 0.1); }
        .incident-ref { font-size: 13px; font-weight: 700; color: #1bb74d; margin-bottom: 10px; }
        .incident-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
        .detail-item { font-size: 12px; }
        .detail-label { color: #8a95a5; font-weight: 600; margin-bottom: 3px; }
        .detail-value { color: #4e596c; font-weight: 500; }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideIn { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 80px 20px; color: #8a95a5; }
        .empty-state i { font-size: 64px; color: #d1d5db; margin-bottom: 16px; display: block; }
        .empty-state h3 { font-size: 18px; font-weight: 700; color: #101a31; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .modal-content { width: 98%; margin: 10px; }
            .incident-details { grid-template-columns: 1fr; }
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
                    <div class="sub">Incident types that have occurred 2+ times in the last 180 days</div>
                </div>
            </div>
            <a href="{{ route('dashboards.hsc_conditions') }}" class="back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Pattern Types</div>
                <div class="stat-value">{{ $repeatPatterns->count() }}</div>
                <div class="stat-subtext">Unique incident/injury combinations</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Repeat Incidents</div>
                <div class="stat-value">{{ $repeatPatterns->sum('repeat_count') }}</div>
                <div class="stat-subtext">Across all patterns</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Highest Repetition</div>
                <div class="stat-value">{{ $repeatPatterns->max('repeat_count') ?? 0 }}</div>
                <div class="stat-subtext">
                    @if($repeatPatterns->first())
                        {{ $repeatPatterns->first()->incidentType->name ?? 'N/A' }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Time Period</div>
                <div class="stat-value">180</div>
                <div class="stat-subtext">Days analysis window</div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Repeat Incident Patterns
                </div>
            </div>
            <div class="card-body">
                @if($repeatPatterns->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>RANK</th>
                            <th>INCIDENT TYPE</th>
                            <th>BODY PART AFFECTED</th>
                            <th class="center">REPEAT COUNT</th>
                            <th class="center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repeatPatterns as $index => $pattern)
                        @php
                            $patternKey = $pattern->incident_type_id . '_' . ($pattern->body_part ?? 'null');
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #8a95a5; font-size: 16px;">
                                #{{ $index + 1 }}
                            </td>
                            <td>
                                <div class="incident-type">{{ $pattern->incidentType->name ?? 'Unknown Type' }}</div>
                            </td>
                            <td>
                                <span class="injury-type">
                                    <i class="fa-solid fa-heart-pulse"></i>
                                    {{ $pattern->body_part ?: 'Not Specified' }}
                                </span>
                            </td>
                            <td class="center">
                                <span class="count-badge {{ $pattern->repeat_count >= 5 ? 'critical' : ($pattern->repeat_count >= 3 ? 'high' : 'medium') }}">
                                    {{ $pattern->repeat_count }}x
                                </span>
                            </td>
                            <td class="center">
                                <button class="view-btn" onclick="showPatternDetails('{{ $patternKey }}', '{{ addslashes($pattern->incidentType->name ?? 'Unknown') }}', '{{ addslashes($pattern->body_part ?: 'Not Specified') }}', {{ $pattern->repeat_count }})">
                                    <i class="fa-solid fa-eye"></i> View Details
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fa-solid fa-check-circle"></i>
                    <h3>No Repeat Patterns Found</h3>
                    <p>No incident types have occurred 2 or more times in the last 180 days</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for Pattern Details -->
    <div id="patternModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Pattern Details
                </h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        // Pattern details data from Laravel
        const patternDetails = @json($patternDetails);
        
        // Modal elements
        const modal = document.getElementById('patternModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const closeBtn = document.getElementsByClassName('close')[0];

        closeBtn.onclick = function() {
            modal.classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.remove('active');
            }
        }

        function showPatternDetails(patternKey, incidentType, injuryType, repeatCount) {
            modalTitle.innerHTML = `
                <i class="fa-solid fa-clipboard-list"></i>
                ${incidentType} - ${injuryType} (${repeatCount} Incidents)
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
                
                Object.values(facilities).forEach((facilityData, index) => {
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
                                <div class="facility-count">${count} incident${count > 1 ? 's' : ''}</div>
                            </div>
                    `;
                    
                    incidents.forEach((incident, idx) => {
                        html += `
                            <div class="incident-card">
                                <div class="incident-ref">
                                    <i class="fa-solid fa-hashtag"></i> ${incident.reference_number}
                                </div>
                                <div class="incident-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Employee</div>
                                        <div class="detail-value">${incident.first_name} ${incident.surname}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Date Occurred</div>
                                        <div class="detail-value">${new Date(incident.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Body Part Affected</div>
                                        <div class="detail-value">${incident.body_part || 'Not specified'}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value">${formatStatus(incident.status)}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Severity</div>
                                        <div class="detail-value">${incident.severity || 'Not assessed'}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Action</div>
                                        <div class="detail-value">
                                            <a href="/incidents/${incident.id}" target="_blank" style="color: #1bb74d; font-weight: 600; text-decoration: none;">
                                                <i class="fa-solid fa-external-link-alt"></i> View Full Case
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                });
                
                modalBody.innerHTML = html;
            }
            
            modal.classList.add('active');
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
