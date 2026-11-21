<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Repeat Incident Patterns — Health Service Commission</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0d7a3d 0%, #1bb74d 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: white;
            border-radius: 16px;
            padding: 24px 32px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(13, 122, 61, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 800;
            color: #0d7a3d;
            margin-bottom: 6px;
        }

        .header-left p {
            font-size: 14px;
            color: #64748b;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #0d7a3d 0%, #1bb74d 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(13, 122, 61, 0.3);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 122, 61, 0.4);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(13, 122, 61, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(13, 122, 61, 0.2);
        }

        .stat-card h3 {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .stat-card .value {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stat-card .value.green { color: #0d7a3d; }
        .stat-card .value.orange { color: #f59e0b; }
        .stat-card .value.red { color: #dc2626; }
        .stat-card .value.blue { color: #3b82f6; }

        .stat-card .subtext {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(13, 122, 61, 0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-bottom: 2px solid #0d7a3d;
        }

        .table-header h2 {
            font-size: 16px;
            font-weight: 800;
            color: #0d7a3d;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
        }

        th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        th.center { text-align: center; }

        td {
            padding: 18px 20px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        td.center { text-align: center; }

        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background: #f8fdf9;
        }

        .rank {
            font-size: 18px;
            font-weight: 800;
            color: #94a3b8;
        }

        .incident-type {
            font-weight: 700;
            color: #0d7a3d;
        }

        .description {
            color: #64748b;
            font-size: 13px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .badge.critical {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge.high {
            background: #fef3c7;
            color: #f59e0b;
        }

        .badge.medium {
            background: #dbeafe;
            color: #3b82f6;
        }

        /* Button */
        .view-btn {
            padding: 8px 18px;
            background: linear-gradient(135deg, #0d7a3d 0%, #1bb74d 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .view-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 122, 61, 0.3);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
            animation: modalSlide 0.3s ease-out;
        }

        @keyframes modalSlide {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 20px 28px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-bottom: 2px solid #0d7a3d;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 800;
            color: #0d7a3d;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: #fee2e2;
            border-color: #dc2626;
            color: #dc2626;
        }

        .modal-body {
            padding: 28px;
            overflow-y: auto;
            max-height: calc(90vh - 80px);
        }

        .facility-section {
            margin-bottom: 28px;
            padding-bottom: 28px;
            border-bottom: 2px solid #f1f5f9;
        }

        .facility-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .facility-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .facility-name {
            font-size: 16px;
            font-weight: 700;
            color: #0d7a3d;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .facility-count {
            background: #fef3c7;
            color: #f59e0b;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .incident-card {
            background: white;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .incident-card:hover {
            border-color: #1bb74d;
            box-shadow: 0 4px 12px rgba(27, 183, 77, 0.1);
        }

        .incident-ref {
            font-size: 13px;
            font-weight: 700;
            color: #1bb74d;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .incident-date {
            font-size: 12px;
            color: #94a3b8;
        }

        .incident-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .detail-item {
            font-size: 13px;
        }

        .detail-label {
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .detail-value {
            color: #334155;
            font-weight: 500;
        }

        .detail-value a {
            color: #1bb74d;
            font-weight: 700;
            text-decoration: none;
        }

        .detail-value a:hover {
            text-decoration: underline;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #1bb74d;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 700;
            color: #0d7a3d;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14px;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .back-button {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 10px;
            }

            .incident-details {
                grid-template-columns: 1fr;
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #1bb74d;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0d7a3d;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1><i class="fa-solid fa-repeat"></i> Repeat Incident Patterns</h1>
                <p>Incident types that have occurred 2+ times in the last 180 days</p>
            </div>
            <a href="{{ route('dashboards.hsc_conditions') }}" class="back-button">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pattern Types</h3>
                <div class="value green">{{ $repeatPatterns->count() }}</div>
                <div class="subtext">Unique incident types repeating</div>
            </div>
            <div class="stat-card">
                <h3>Total Repeat Incidents</h3>
                <div class="value orange">{{ $repeatPatterns->sum('repeat_count') }}</div>
                <div class="subtext">Across all patterns</div>
            </div>
            <div class="stat-card">
                <h3>Highest Repetition</h3>
                <div class="value red">{{ $repeatPatterns->max('repeat_count') ?? 0 }}</div>
                <div class="subtext">Most repeated incident type</div>
            </div>
            <div class="stat-card">
                <h3>Time Period</h3>
                <div class="value blue">180</div>
                <div class="subtext">Days analysis window</div>
            </div>
        </div>

        <!-- Table -->
        @if($repeatPatterns->count() > 0)
        <div class="table-card">
            <div class="table-header">
                <h2><i class="fa-solid fa-table"></i> REPEAT INCIDENT PATTERNS</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="center">RANK</th>
                        <th>INCIDENT TYPE</th>
                        <th>DESCRIPTION</th>
                        <th class="center">REPEAT COUNT</th>
                        <th class="center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repeatPatterns as $index => $pattern)
                    <tr>
                        <td class="center">
                            <div class="rank">#{{ $index + 1 }}</div>
                        </td>
                        <td>
                            <div class="incident-type">{{ $pattern->incidentType->name ?? 'Unknown Type' }}</div>
                        </td>
                        <td>
                            <div class="description">{{ $pattern->incidentType->description ?? 'No description available' }}</div>
                        </td>
                        <td class="center">
                            @php
                                $badgeClass = 'medium';
                                if($pattern->repeat_count >= 5) {
                                    $badgeClass = 'critical';
                                } elseif($pattern->repeat_count >= 3) {
                                    $badgeClass = 'high';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $pattern->repeat_count }}x
                            </span>
                        </td>
                        <td class="center">
                            <button 
                                onclick="showPatternDetails({{ $pattern->incident_type_id }}, '{{ addslashes($pattern->incidentType->name ?? 'Unknown') }}', {{ $pattern->repeat_count }})"
                                class="view-btn"
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
        <div class="table-card">
            <div class="empty-state">
                <i class="fa-solid fa-check-circle"></i>
                <h3>No Repeat Patterns Found</h3>
                <p>No incident types have occurred 2 or more times in the last 180 days</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div id="patternModal" class="modal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Pattern Details
                </div>
                <button onclick="closeModal()" class="close-btn">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
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
                            <div class="facility-count">${count} incident${count > 1 ? 's' : ''}</div>
                        </div>
                `;
                
                incidents.forEach((incident) => {
                    html += `
                        <div class="incident-card">
                            <div class="incident-ref">
                                <span><i class="fa-solid fa-hashtag"></i> ${incident.reference_number}</span>
                                <span class="incident-date">${new Date(incident.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                            </div>
                            <div class="incident-details">
                                <div class="detail-item">
                                    <div class="detail-label">Employee</div>
                                    <div class="detail-value">${incident.first_name} ${incident.surname}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Description</div>
                                    <div class="detail-value">${incident.incident_description ? incident.incident_description.substring(0, 60) + '...' : 'No description'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status</div>
                                    <div class="detail-value">${formatStatus(incident.status)}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Action</div>
                                    <div class="detail-value">
                                        <a href="/incidents/${incident.id}" target="_blank">
                                            <i class="fa-solid fa-external-link-alt"></i> View Case
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

    function closeModal(event) {
        const modal = document.getElementById('patternModal');
        if (!event || event.target === modal) {
            modal.classList.remove('active');
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
