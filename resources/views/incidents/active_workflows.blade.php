<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Active Workflows · Incident Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: Tahoma, sans-serif; background: offwhite; padding: 2.5rem 0; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
        .header { margin-bottom: 1rem; }
        .header h1 { 
            font-size: 32px; 
            font-weight: 800; 
            color: #0f172a; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        .header p { 
            font-size: 14px; 
            color: #64748b; 
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .total-count {
            font-size: 13px;
            color: #1D4533;
            font-weight: 700;
            background: rgba(29, 69, 51, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }
        .back-button {
            grid-column: 12;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            color: #1D4533;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid #1D4533;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            white-space: nowrap;
        }
        .back-button:hover {
            background: #1D4533;
            color: white;
            transform: translateX(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .back-button i {
            font-size: 13px;
        }
        .header-top {
            display: grid;
            grid-template-columns: auto auto 1fr;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
            min-height: 50px;
        }
        .header-top h1 {
            grid-column: 1;
            white-space: nowrap;
            margin: 0;
        }
        }
        .stats-inline { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 0.75rem; 
            margin-bottom: 1.5rem;
        }
        .stat-inline { 
            background: white; 
            padding: 0.75rem 1rem; 
            border-radius: 8px; 
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-top: 3px solid #1D4533;
            transition: all 0.3s ease;
        }
        .stat-inline:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .stat-inline p { 
            font-size: 10px; 
            color: #64748b; 
            font-weight: 600; 
            margin-bottom: 0.4rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        .stat-inline strong { 
            font-size: 20px; 
            font-weight: 700; 
            color: #1D4533;
        }
        .table-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); overflow: hidden; margin-bottom: 3rem; }
        .table-header { 
            padding: 1.5rem; 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .table-header h2 { 
            font-size: 16px; 
            font-weight: 700; 
            color: white; 
            display: flex; 
            align-items: center; 
            gap: 0.75rem;
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; font-size: 13px; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { 
            text-align: left; 
            padding: 0.75rem; 
            font-weight: 700; 
            color: #2d3748; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        td { 
            padding: 0.75rem; 
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-family: Tahoma, sans-serif;
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f7fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .ref-number { 
            font-family: Tahoma, sans-serif;
            font-weight: 600; 
            color: #1D4533;
        }
        .status-badge { 
            display: inline-block; 
            padding: 0.4rem 0.75rem; 
            border-radius: 6px; 
            font-size: 11px; 
            font-weight: 700;
        }
        .status-escalated { background: #dbeafe; color: #1e40af; }
        .status-pending { background: #fee2e2; color: #991b1b; }
        .status-in-review { background: #fef3c7; color: #92400e; }
        .compliance-compliant { background: #d1f2eb; color: #0f766e; }
        .compliance-partial { background: #fef3c7; color: #92400e; }
        .compliance-noncompliant { background: #fee2e2; color: #991b1b; }
        .empty-state { 
            text-align: center; 
            padding: 3rem 1.5rem;
        }
        .empty-state i { 
            font-size: 48px; 
            color: #cbd5e1; 
            margin-bottom: 1rem; 
        }
        .empty-state p { 
            font-size: 16px; 
            color: #64748b; 
            font-weight: 600; 
        }
        .pagination-wrapper { 
            padding: 1rem; 
            text-align: center; 
            background: white; 
            border-top: 1px solid #e2e8f0;
        }
        .pagination { 
            display: flex; 
            justify-content: center; 
            gap: 0.5rem; 
            flex-wrap: wrap;
        }
        .pagination a, .pagination span { 
            padding: 0.5rem 0.75rem; 
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #4a5568;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
        }
        .pagination a:hover {
            border-color: #1D4533;
            color: #1D4533;
        }
        .pagination .active, .pagination span.active { 
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white; 
            border-color: #1D4533; 
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
        }
        .search-box:focus-within {
            border-color: #1D4533;
            box-shadow: 0 0 0 3px rgba(29, 69, 51, 0.1);
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            color: #2d3748;
            font-family: 'Roboto', sans-serif;
        }
        .search-box input::placeholder {
            color: #a0aec0;
        }
        .search-box i {
            color: #64748b;
            font-size: 14px;
            flex-shrink: 0;
        }
        .workflow-nav {
            grid-column: 2;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
            align-items: center;
        }
        .workflow-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.45rem 0.75rem;
            background: #f7fafc;
            color: #2d3748;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .workflow-tab:hover {
            border-color: #1D4533;
            color: #1D4533;
            background: rgba(29, 69, 51, 0.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .workflow-tab i {
            font-size: 12px;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            max-width: 95%;
            width: 95%;
            max-height: 85vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }
        .modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .modal-header h2 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .modal-close:hover {
            background: rgba(255,255,255,0.3);
        }
        .modal-body {
            padding: 2rem 1.5rem;
            font-size: 13px;
            line-height: 1.8;
            overflow-x: auto;
        }
        .workflow-stage {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .workflow-horizontal {
            display: flex;
            align-items: center;
            gap: 0;
            flex-wrap: nowrap;
            min-width: max-content;
            padding: 1rem 0;
        }
        .stage-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            min-width: 160px;
            max-width: 180px;
            transition: all 0.3s ease;
            position: relative;
        }
        .stage-card:hover {
            border-color: #1D4533;
            box-shadow: 0 4px 12px rgba(29, 69, 51, 0.15);
            transform: translateY(-2px);
        }
        .stage-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
        }
        .stage-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
            margin-bottom: 0.5rem;
        }
        .stage-title {
            font-weight: 700;
            color: #1D4533;
            font-size: 13px;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        .stage-description {
            color: #64748b;
            font-size: 11px;
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }
        .stage-role {
            display: inline-block;
            background: rgba(29, 69, 51, 0.1);
            color: #1D4533;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 10px;
            margin-top: auto;
            width: 100%;
        }
        .stage-flow {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1D4533;
            font-size: 24px;
            padding: 0 0.5rem;
            flex-shrink: 0;
        }
        .stage-flow i {
            font-weight: 900;
        }
        @media (max-width: 768px) {
            .workflow-nav {
                flex-direction: column;
                gap: 0.5rem;
            }
            .workflow-tab {
                width: 100%;
                justify-content: center;
            }
            .modal-content {
                max-height: 90vh;
            }
            .modal-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .modal-close {
                align-self: flex-end;
            }
            body { padding: 1.5rem 0; }
            .header h1 { font-size: 24px; }
            .header p { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .table-header { flex-wrap: wrap; gap: 0.75rem; }
            th, td { padding: 0.75rem; font-size: 12px; }
            .stats-inline { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <h3><i class="fa-solid fa-spinner"></i> Active Workflows</h3>
                <div class="workflow-nav">
                    <button class="workflow-tab" data-workflow="district-small" onclick="openWorkflowModal('district-small')">
                        <i class="fa-solid fa-clinic-medical"></i>
                        District Small Facilities
                    </button>
                    <button class="workflow-tab" data-workflow="district-hospital" onclick="openWorkflowModal('district-hospital')">
                        <i class="fa-solid fa-hospital"></i>
                        District Hospital
                    </button>
                    <button class="workflow-tab" data-workflow="provincial-hospital" onclick="openWorkflowModal('provincial-hospital')">
                        <i class="fa-solid fa-building"></i>
                        Provincial/General Hospital
                    </button>
                    <button class="workflow-tab" data-workflow="central-hospital" onclick="openWorkflowModal('central-hospital')">
                        <i class="fa-solid fa-landmark"></i>
                        Central Hospital
                    </button>
                </div>
                <a href="javascript:history.back()" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2><i class="fa-solid fa-table-list"></i> Active Incident Details [{{ $incidents->total() }}]</h2>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="tableSearch" placeholder="Search incidents..." />
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Facility</th>
                            <th>District</th>
                            <th>Province</th>
                            <th>Case Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incidents as $index => $incident)
                            <tr>
                                <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $index + 1 }}</td>
                                <td><span class="ref-number">{{ $incident->reference_number ?? 'N/A' }}</span></td>
                                <td>{{ $incident->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ ($incident->first_name ?? '') . ' ' . ($incident->surname ?? '') }}</td>
                                <td>{{ Str::limit(optional($incident->facility)->name ?? 'N/A', 25) }}</td>
                                <td>{{ optional(optional($incident->facility)->district)->name ?? 'N/A' }}</td>
                                <td>{{ optional(optional(optional($incident->facility)->district)->province)->name ?? '-' }}</td>
                                <td>{{ $incident->incident_type?->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusLabel = '';
                                        $statusClass = 'status-pending';
                                        
                                        $status = $incident->status ?? '';
                                        
                                        switch($status) {
                                            case 'PENDING_SUPERVISOR_VERIFICATION':
                                                $statusLabel = 'Pending Verification';
                                                $statusClass = 'status-pending';
                                                break;
                                            case 'VERIFIED_SUPERVISOR':
                                                $statusLabel = 'Verified';
                                                $statusClass = 'status-escalated';
                                                break;
                                            case 'HR_REVIEW':
                                            case 'PENDING_HR_REVIEW':
                                                $statusLabel = 'HR Review';
                                                $statusClass = 'status-in-review';
                                                break;
                                            case 'CLINICAL_EXECUTIVE_REVIEW':
                                                $statusLabel = 'Executive Review';
                                                $statusClass = 'status-in-review';
                                                break;
                                            case 'PENDING_PROVINCIAL_APPROVAL':
                                                $statusLabel = 'Provincial Approval';
                                                $statusClass = 'status-in-review';
                                                break;
                                            case 'PENDING_MEDICAL_BOARD':
                                                $statusLabel = 'Medical Board';
                                                $statusClass = 'status-in-review';
                                                break;
                                            case 'APPROVED_BY_PMD':
                                                $statusLabel = 'Approved';
                                                $statusClass = 'status-escalated';
                                                break;
                                            case 'REJECTED_BY_HR':
                                                $statusLabel = 'Rejected';
                                                $statusClass = 'status-pending';
                                                break;
                                            case 'RESOLVED':
                                                $statusLabel = 'Resolved';
                                                $statusClass = 'status-escalated';
                                                break;
                                            default:
                                                $statusLabel = str_replace('_', ' ', $status);
                                                break;
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fa-solid fa-check text-xs"></i> {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No active workflows found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($incidents->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    {{ $incidents->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Workflow Modal -->
    <div id="workflowModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fa-solid fa-diagram-project"></i> Workflow</h2>
                <button class="modal-close" onclick="closeWorkflowModal()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content populated by JavaScript -->
            </div>
        </div>
    </div>
    
    <script>
        const workflowData = {
            'district-small': {
                title: 'District Small Facilities Workflow',
                icon: 'fa-clinic-medical',
                stages: [
                    {
                        number: 1,
                        title: 'Incident Reporting',
                        description: 'Worker reports injury at polyclinic/clinic',
                        role: 'Worker/Facility Staff'
                    },
                    {
                        number: 2,
                        title: 'Supervisor Verification',
                        description: 'Immediate supervisor verifies incident details',
                        role: 'Supervisor'
                    },
                    {
                        number: 3,
                        title: 'HOD Report Submission',
                        description: 'Head of Department submits formal report',
                        role: 'HOD'
                    },
                    {
                        number: 4,
                        title: 'District HR Review',
                        description: 'Assigned District HR officer reviews dossier. Load-balanced among available staff.',
                        role: 'District HR Officer'
                    },
                    {
                        number: 5,
                        title: 'Document Upload Notifications',
                        description: 'Worker uploads documents via public link. Only the assigned HR user is notified via SMS & Email.',
                        role: 'System Notification'
                    },
                    {
                        number: 6,
                        title: 'DMO Clinical Review',
                        description: 'District Medical Officer conducts executive audit',
                        role: 'District Medical Officer'
                    },
                    {
                        number: 7,
                        title: 'PMD Final Review',
                        description: 'Provincial Medical Director approves or modifies determination',
                        role: 'Provincial Medical Director'
                    },
                    {
                        number: 8,
                        title: 'Social Services Handoff',
                        description: 'Case submitted to Social Services for benefit processing',
                        role: 'Social Services'
                    }
                ]
            },
            'district-hospital': {
                title: 'District Hospital Workflow',
                icon: 'fa-hospital',
                stages: [
                    {
                        number: 1,
                        title: 'Incident Reporting',
                        description: 'Worker reports injury at district hospital',
                        role: 'Worker/Facility Staff'
                    },
                    {
                        number: 2,
                        title: 'Supervisor Verification',
                        description: 'Immediate supervisor verifies incident details',
                        role: 'Supervisor'
                    },
                    {
                        number: 3,
                        title: 'HOD Report Submission',
                        description: 'Head of Department submits formal report',
                        role: 'HOD'
                    },
                    {
                        number: 4,
                        title: 'Resident Local HR Review',
                        description: 'District Hospital\'s resident HR officer reviews dossier. Load-balanced among available staff.',
                        role: 'Resident Local HR Officer'
                    },
                    {
                        number: 5,
                        title: 'Document Upload Notifications',
                        description: 'Worker uploads documents via public link. Only the assigned HR user is notified via SMS & Email.',
                        role: 'System Notification'
                    },
                    {
                        number: 6,
                        title: 'District HR Secondary Review',
                        description: 'District HR conducts secondary review',
                        role: 'District HR Officer'
                    },
                    {
                        number: 7,
                        title: 'DMO Clinical Review',
                        description: 'District Medical Officer conducts executive audit',
                        role: 'District Medical Officer'
                    },
                    {
                        number: 8,
                        title: 'PMD Final Review',
                        description: 'Provincial Medical Director approves or modifies determination',
                        role: 'Provincial Medical Director'
                    },
                    {
                        number: 9,
                        title: 'Social Services Handoff',
                        description: 'Case submitted to Social Services for benefit processing',
                        role: 'Social Services'
                    }
                ]
            },
            'provincial-hospital': {
                title: 'Provincial/General Hospital Workflow',
                icon: 'fa-building',
                stages: [
                    {
                        number: 1,
                        title: 'Incident Reporting',
                        description: 'Worker reports injury at provincial or general hospital',
                        role: 'Worker/Facility Staff'
                    },
                    {
                        number: 2,
                        title: 'Supervisor Verification',
                        description: 'Immediate supervisor verifies incident details',
                        role: 'Supervisor'
                    },
                    {
                        number: 3,
                        title: 'HOD Report Submission',
                        description: 'Head of Department submits formal report',
                        role: 'HOD'
                    },
                    {
                        number: 4,
                        title: 'Hospital HR Review',
                        description: 'Hospital HR officer reviews dossier. Load-balanced among available staff.',
                        role: 'Hospital HR Officer'
                    },
                    {
                        number: 5,
                        title: 'Document Upload Notifications',
                        description: 'Worker uploads documents via public link. Only the assigned HR user is notified via SMS & Email.',
                        role: 'System Notification'
                    },
                    {
                        number: 6,
                        title: 'Medical Superintendent Review',
                        description: 'Medical Superintendent conducts clinical oversight',
                        role: 'Medical Superintendent'
                    },
                    {
                        number: 7,
                        title: 'PMD Final Review',
                        description: 'Provincial Medical Director approves or modifies determination',
                        role: 'Provincial Medical Director'
                    },
                    {
                        number: 8,
                        title: 'Social Services Handoff',
                        description: 'Case submitted to Social Services for benefit processing',
                        role: 'Social Services'
                    }
                ]
            },
            'central-hospital': {
                title: 'Central Hospital Workflow',
                icon: 'fa-landmark',
                stages: [
                    {
                        number: 1,
                        title: 'Incident Reporting',
                        description: 'Worker reports injury at central hospital',
                        role: 'Worker/Facility Staff'
                    },
                    {
                        number: 2,
                        title: 'Supervisor Verification',
                        description: 'Immediate supervisor verifies incident details',
                        role: 'Supervisor'
                    },
                    {
                        number: 3,
                        title: 'HOD Report Submission',
                        description: 'Head of Department submits formal report',
                        role: 'HOD'
                    },
                    {
                        number: 4,
                        title: 'Central HR Review',
                        description: 'Central HR officer reviews dossier',
                        role: 'Central HR Officer'
                    },
                    {
                        number: 5,
                        title: 'Document Upload Notifications',
                        description: 'Worker uploads documents via public link. Only the assigned HR user is notified via SMS & Email.',
                        role: 'System Notification'
                    },
                    {
                        number: 6,
                        title: 'Chief Medical Officer Review',
                        description: 'Chief Medical Officer conducts central-level oversight',
                        role: 'Chief Medical Officer'
                    },
                    {
                        number: 7,
                        title: 'PMD Final Review',
                        description: 'Provincial Medical Director approves or modifies determination',
                        role: 'Provincial Medical Director'
                    },
                    {
                        number: 8,
                        title: 'Social Services Handoff',
                        description: 'Case submitted to Social Services for benefit processing',
                        role: 'Social Services'
                    }
                ]
            }
        };

        function openWorkflowModal(workflowKey) {
            const workflow = workflowData[workflowKey];
            if (!workflow) return;

            const modal = document.getElementById('workflowModal');
            const title = document.getElementById('modalTitle');
            const body = document.getElementById('modalBody');

            title.innerHTML = `<i class="fa-solid ${workflow.icon}"></i> ${workflow.title}`;

            let stagesHtml = '<div class="workflow-horizontal">';
            workflow.stages.forEach((stage, index) => {
                stagesHtml += `
                    <div class="stage-card">
                        <div class="stage-number">${stage.number}</div>
                        <div class="stage-title">${stage.title}</div>
                        <div class="stage-description">${stage.description}</div>
                        <div class="stage-role">${stage.role}</div>
                    </div>
                `;
                
                if (index < workflow.stages.length - 1) {
                    stagesHtml += '<div class="stage-flow"><i class="fa-solid fa-arrow-right"></i></div>';
                }
            });
            stagesHtml += '</div>';

            body.innerHTML = stagesHtml;
            modal.classList.add('active');
        }

        function closeWorkflowModal() {
            const modal = document.getElementById('workflowModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('workflowModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWorkflowModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWorkflowModal();
            }
        });

        // Table search functionality
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (searchTerm === '' || text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update the count in the header
            const currentCount = visibleCount > 0 ? visibleCount : 0;
            const headerH2 = document.querySelector('.table-header h2');
            if (headerH2) {
                const allCount = document.querySelectorAll('table tbody tr:not([style*="display: none"])').length;
                const suffix = searchTerm !== '' ? ` [${currentCount} results]` : ` [{{ $incidents->total() }}]`;
                headerH2.innerHTML = '<i class="fa-solid fa-table-list"></i> Active Incident Details' + suffix;
            }
        });
    </script>
</body>
</html>
