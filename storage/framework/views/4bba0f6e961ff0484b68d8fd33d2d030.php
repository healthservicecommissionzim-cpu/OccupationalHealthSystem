<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title>All Incidents · Supervisor</title>
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
        .icon { width: 46px; height: 46px; border-radius: 12px; background: #e9f8f3; color: #079b6d; display: grid; place-items: center; font-size: 22px; }
        .title { margin: 0; color: #101a31; font-size: 24px; font-weight: 750; }
        .sub { margin-top: 5px; color: #718096; font-size: 13px; }
        .case { display: inline-block; margin-top: 9px; padding: 5px 10px; border-radius: 6px; background: #eef2f6; color: #5d687b; font-size: 11px; font-weight: 700; }
        .back { padding: 10px 17px; border: 1px solid #ccd6df; border-radius: 8px; background: white; color: #101a31; font-weight: 650; font-size: 12px; text-decoration: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .back:hover { background: #f8fafb; }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; padding: 18px; text-align: center; }
        .stat-card.total { border-left: 3px solid #079b6d; }
        .stat-card.verified { border-left: 3px solid #22c55e; }
        .stat-card.pending { border-left: 3px solid #f59e0b; }
        .stat-number { font-size: 32px; font-weight: 750; color: #101a31; display: block; margin-bottom: 6px; }
        .stat-label { font-size: 10px; color: #8a95a5; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; }
        
        /* Search and Filter */
        .search-box { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white; border: 1px solid #e4e9ee; border-radius: 8px; min-width: 250px; }
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
        th { text-align: left; padding: 12px; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; border-bottom: 2px solid #e4e9ee; letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid #e4e9ee; color: #4e596c; font-size: 12px; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background: #f8fafb; }
        tbody tr:last-child td { border-bottom: none; }
        
        .ref-number { font-weight: 700; color: #079b6d; font-size: 12px; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 999px; font-size: 9px; font-weight: 750; white-space: nowrap; text-transform: uppercase; }
        .status-badge.pending { background: #fff8e7; color: #c67a00; }
        .status-badge.verified { background: #e9f8f3; color: #067c58; }
        .status-badge.escalated { background: #eff6ff; color: #1e40af; }
        
        .action-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #079b6d; color: white; text-decoration: none; border-radius: 6px; font-size: 10px; font-weight: 650; transition: all 0.2s; border: none; cursor: pointer; }
        .action-btn:hover { background: #067c58; transform: translateY(-1px); }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #e4e9ee; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; color: #8a95a5; font-weight: 600; }
        
        /* Pagination */
        .pagination-wrapper { padding: 16px 22px; background: white; border-top: 1px solid #e4e9ee; }
        .pagination { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; align-items: center; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e4e9ee; background: white; cursor: pointer; border-radius: 6px; font-size: 11px; font-weight: 650; transition: all 0.2s; text-decoration: none; color: #4e596c; min-width: 36px; text-align: center; }
        .pagination a:hover { border-color: #079b6d; color: #079b6d; background: #f0f9f7; }
        .pagination .active span { background: #079b6d; color: white; border-color: #079b6d; }
        .pagination .disabled span { color: #cbd5e1; cursor: not-allowed; background: #f8fafb; }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: flex-start; justify-content: center; overflow-y: auto; backdrop-filter: blur(4px); padding: 40px 20px; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 14px; max-width: 1200px; width: 100%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); border: 2px solid #e1e7ee; max-height: 90vh; overflow-y: auto; margin: auto; }
        .modal-header { position: sticky; top: 0; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #079b6d; z-index: 2001; }
        .modal-header h2 { color: white; font-size: 18px; font-weight: 750; margin: 0; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; color: white; font-size: 20px; cursor: pointer; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 24px; }
        
        .info-section { margin-bottom: 24px; }
        .section-title { font-size: 14px; font-weight: 700; color: #101a31; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .info-card { background: #f8fafb; border: 1px solid #e4e9ee; border-radius: 10px; overflow: hidden; }
        .info-header { background: #e9f8f3; padding: 12px 16px; border-bottom: 1px solid #d0e8df; }
        .info-header-title { font-size: 11px; font-weight: 700; color: #067c58; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-table { width: 100%; }
        .info-table tr { border-bottom: 1px solid #e4e9ee; }
        .info-table tr:last-child { border-bottom: none; }
        .info-table td { padding: 12px 16px; font-size: 12px; }
        .info-table td:first-child { font-weight: 650; color: #4e596c; width: 30%; background: #fafbfc; }
        .info-table td:last-child { color: #263149; }
        
        @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr; }
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
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-list-check"></i></div>
                <div>
                    <h1 class="title">All Incidents</h1>
                    <div class="sub">Monitor and manage all assigned incidents</div>
                </div>
            </div>
            <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Incidents Table -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-table-list"></i> &nbsp; Incident Records <span style="display: inline-block; margin-left: 12px; padding: 4px 10px; border-radius: 6px; background: #e9f8f3; color: #067c58; font-size: 10px; font-weight: 700;"><?php echo e($incidents->total()); ?></span></div>
                <div class="filters">
                    <select id="statusFilter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="pending">Pending Verification</option>
                        <option value="verified">Verified</option>
                        <option value="escalated">Escalated to HR</option>
                    </select>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Search by reference, name, facility..." />
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Reference</th>
                            <th>Date Reported</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Facility</th>
                            <th>District</th>
                            <th>Incident Type</th>
                            <th>Status</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="incidentsTable">
                        <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-status="<?php echo e($incident->status ?? ''); ?>">
                                <td style="text-align: center; font-weight: 700; color: #8a95a5;"><?php echo e($loop->iteration); ?></td>
                                <td><span class="ref-number"><?php echo e($incident->reference_number ?? 'N/A'); ?></span></td>
                                <td><?php echo e($incident->created_at ? $incident->created_at->format('d M Y, H:i') : 'N/A'); ?></td>
                                <td><?php echo e(trim(($incident->first_name ?? '') . ' ' . ($incident->surname ?? '')) ?: 'N/A'); ?></td>
                                <td><?php echo e($incident->department->name ?? 'N/A'); ?></td>
                                <td><?php echo e($incident->designation->name ?? 'N/A'); ?></td>
                                <td><?php echo e(Str::limit($incident->facility->name ?? 'N/A', 25)); ?></td>
                                <td><?php echo e($incident->facility->district->name ?? 'N/A'); ?></td>
                                <td><?php echo e($incident->incidentType->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php
                                        $status = $incident->status ?? '';
                                        $statusLabel = '';
                                        $statusClass = 'pending';
                                        
                                        switch($status) {
                                            case 'PENDING_SUPERVISOR_VERIFICATION':
                                                $statusLabel = 'Pending';
                                                $statusClass = 'pending';
                                                break;
                                            case 'VERIFIED_SUPERVISOR':
                                                $statusLabel = 'Verified';
                                                $statusClass = 'verified';
                                                break;
                                            case 'ESCALATED_TO_HR':
                                                $statusLabel = 'Escalated';
                                                $statusClass = 'escalated';
                                                break;
                                            default:
                                                $statusLabel = str_replace('_', ' ', ucwords(strtolower($status)));
                                                break;
                                        }
                                    ?>
                                    <span class="status-badge <?php echo e($statusClass); ?>">
                                        <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                                        <?php echo e($statusLabel); ?>

                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button onclick="viewIncidentDetails(<?php echo e($incident->id); ?>)" class="action-btn">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No incidents assigned to you</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($incidents->hasPages()): ?>
            <div class="pagination-wrapper">
                <div class="pagination">
                    <?php echo e($incidents->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Incident Details Modal -->
    <div id="incidentModal" class="modal">
        <div class="modal-content" style="animation: slideIn 0.3s ease;">
            <div class="modal-header">
                <h2><i class="fa-solid fa-file-lines"></i> <span id="modalTitle">Incident Details</span></h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 48px; color: #079b6d; margin-bottom: 16px;"></i>
                    <p style="color: #8a95a5; font-weight: 600;">Loading incident details...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search and Filter
        function filterTable() {
            const searchTerm = document.getElementById('tableSearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#incidentsTable tr');
            
            rows.forEach(row => {
                // Skip empty state row
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) return;
                
                const text = row.textContent.toLowerCase();
                const rowStatus = row.getAttribute('data-status') || '';
                
                const searchMatch = searchTerm === '' || text.includes(searchTerm);
                
                let statusMatch = true;
                if (statusFilter === 'pending') {
                    statusMatch = rowStatus === 'PENDING_SUPERVISOR_VERIFICATION';
                } else if (statusFilter === 'verified') {
                    statusMatch = rowStatus === 'VERIFIED_SUPERVISOR';
                } else if (statusFilter === 'escalated') {
                    statusMatch = rowStatus === 'ESCALATED_TO_HR';
                }
                
                row.style.display = (searchMatch && statusMatch) ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tableSearch').addEventListener('keyup', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
        });

        // Modal Functions
        function viewIncidentDetails(incidentId) {
            const modal = document.getElementById('incidentModal');
            const modalBody = document.getElementById('modalBody');
            
            modal.classList.add('active');
            
            // Fetch incident details
            fetch(`/api/supervisor/incidents/${incidentId}/dossier`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load incident details');
                    return response.json();
                })
                .then(data => {
                    renderIncidentDetails(data, modalBody);
                })
                .catch(error => {
                    modalBody.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px; color: #ef4444;">
                            <i class="fa-solid fa-exclamation-circle" style="font-size: 48px; margin-bottom: 16px;"></i>
                            <p style="font-weight: 700; font-size: 16px; margin-bottom: 8px;">Error Loading Details</p>
                            <p style="font-size: 13px; color: #8a95a5;">${error.message}</p>
                        </div>
                    `;
                });
        }

        function closeModal() {
            document.getElementById('incidentModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('incidentModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        function renderIncidentDetails(data, container) {
            const incident = data.incident;
            
            let html = `
                <!-- Incident Summary -->
                <div style="background: linear-gradient(135deg, #e9f8f3 0%, #d0e8df 100%); padding: 20px; border-radius: 10px; margin-bottom: 24px; border-left: 4px solid #079b6d;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <div>
                            <p style="font-size: 10px; color: #067c58; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Reference Number</p>
                            <p style="font-size: 16px; font-weight: 750; color: #101a31; margin: 0;">${incident.reference_number}</p>
                        </div>
                        <div>
                            <p style="font-size: 10px; color: #067c58; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Date Reported</p>
                            <p style="font-size: 16px; font-weight: 750; color: #101a31; margin: 0;">${new Date(incident.created_at).toLocaleDateString()}</p>
                        </div>
                        <div>
                            <p style="font-size: 10px; color: #067c58; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Status</p>
                            <span style="display: inline-block; padding: 6px 12px; background: white; color: #079b6d; border-radius: 6px; font-size: 11px; font-weight: 700;">${formatStatus(incident.status)}</span>
                        </div>
                    </div>
                </div>

                <!-- Worker Information -->
                <div class="info-section">
                    <h3 class="section-title"><i class="fa-solid fa-user"></i> Worker Information</h3>
                    <div class="info-card">
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td>Full Name</td>
                                    <td>${(incident.first_name || '') + ' ' + (incident.surname || '')}</td>
                                </tr>
                                <tr>
                                    <td>Department</td>
                                    <td>${incident.department?.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td>${incident.designation?.name || 'N/A'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Incident Details -->
                <div class="info-section">
                    <h3 class="section-title"><i class="fa-solid fa-exclamation-triangle"></i> Incident Details</h3>
                    <div class="info-card">
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td>Facility</td>
                                    <td>${incident.facility?.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td>${incident.facility?.district?.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Province</td>
                                    <td>${incident.facility?.district?.province?.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Incident Type</td>
                                    <td>${incident.incident_type?.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Date & Time</td>
                                    <td>${new Date(incident.incident_date).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td style="white-space: pre-wrap;">${incident.incident_description || 'N/A'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- First Aid Records -->
                ${renderFirstAidRecords(data.first_aid_records)}

                <!-- Witness Statements -->
                ${renderWitnessStatements(data.witness_statements)}

                <!-- Supervisor Verification -->
                ${renderSupervisorInfo(data.supervisor)}
            `;
            
            container.innerHTML = html;
        }

        function renderFirstAidRecords(records) {
            if (!records || records.length === 0) {
                return `
                    <div class="info-section">
                        <h3 class="section-title"><i class="fa-solid fa-kit-medical"></i> First Aid Records</h3>
                        <div style="background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 10px; padding: 20px; text-align: center;">
                            <i class="fa-solid fa-info-circle" style="color: #22c55e; font-size: 24px; margin-bottom: 8px;"></i>
                            <p style="color: #15803d; font-size: 13px; margin: 0;">No first aid records available</p>
                        </div>
                    </div>
                `;
            }

            let html = `
                <div class="info-section">
                    <h3 class="section-title"><i class="fa-solid fa-kit-medical"></i> First Aid Records (${records.length})</h3>
            `;

            records.forEach((record, index) => {
                html += `
                    <div class="info-card" style="margin-bottom: 12px;">
                        <div class="info-header">
                            <p class="info-header-title">Record ${index + 1}</p>
                        </div>
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td>Nurse Name</td>
                                    <td>${record.nurse_name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>License Number</td>
                                    <td>${record.nurse_license_number || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Location</td>
                                    <td>${record.location || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Injury Description</td>
                                    <td style="white-space: pre-wrap;">${record.injury_description || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>First Aid Provided</td>
                                    <td style="white-space: pre-wrap;">${record.first_aid_description || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Severity Level</td>
                                    <td>${record.severity_level || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Time Administered</td>
                                    <td>${record.time_administered ? new Date(record.time_administered).toLocaleString() : 'N/A'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            });

            html += `</div>`;
            return html;
        }

        function renderWitnessStatements(witnesses) {
            if (!witnesses || witnesses.length === 0) {
                return `
                    <div class="info-section">
                        <h3 class="section-title"><i class="fa-solid fa-users"></i> Witness Statements</h3>
                        <div style="background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 10px; padding: 20px; text-align: center;">
                            <i class="fa-solid fa-info-circle" style="color: #22c55e; font-size: 24px; margin-bottom: 8px;"></i>
                            <p style="color: #15803d; font-size: 13px; margin: 0;">No witness statements submitted</p>
                        </div>
                    </div>
                `;
            }

            let html = `
                <div class="info-section">
                    <h3 class="section-title"><i class="fa-solid fa-users"></i> Witness Statements (${witnesses.length})</h3>
            `;

            witnesses.forEach((witness, index) => {
                html += `
                    <div class="info-card" style="margin-bottom: 12px;">
                        <div class="info-header">
                            <p class="info-header-title">Witness ${index + 1}</p>
                        </div>
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td>Witness Name</td>
                                    <td>${witness.witness_name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Statement</td>
                                    <td style="white-space: pre-wrap;">${witness.statement || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Submitted</td>
                                    <td>${witness.statement_submitted_at ? new Date(witness.statement_submitted_at).toLocaleString() : 'Pending'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            });

            html += `</div>`;
            return html;
        }

        function renderSupervisorInfo(supervisor) {
            if (!supervisor || !supervisor.name) {
                return `
                    <div class="info-section">
                        <h3 class="section-title"><i class="fa-solid fa-clipboard-check"></i> Supervisor Report</h3>
                        <div style="background: #fff8e7; border: 1px solid #fcd34d; border-radius: 10px; padding: 20px; text-align: center;">
                            <i class="fa-solid fa-clock" style="color: #f59e0b; font-size: 24px; margin-bottom: 8px;"></i>
                            <p style="color: #92400e; font-size: 13px; margin: 0;">No supervisor report submitted yet</p>
                        </div>
                    </div>
                `;
            }

            return `
                <div class="info-section">
                    <h3 class="section-title"><i class="fa-solid fa-clipboard-check"></i> Supervisor Report</h3>
                    
                    <div class="info-card" style="margin-bottom: 12px;">
                        <div class="info-header">
                            <p class="info-header-title">Supervisor Information</p>
                        </div>
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>${supervisor.name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td>${supervisor.designation || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Verified By</td>
                                    <td>${supervisor.verified_by || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td>Verification Date</td>
                                    <td>${supervisor.verified_at ? new Date(supervisor.verified_at).toLocaleString() : 'Pending'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    ${supervisor.hod_report ? `
                    <div class="info-card">
                        <div class="info-header">
                            <p class="info-header-title">Head of Department Report</p>
                        </div>
                        <div style="padding: 16px;">
                            <div style="background: #eff6ff; border-left: 3px solid #3b82f6; padding: 16px; border-radius: 6px;">
                                <p style="font-size: 13px; color: #1e3a8a; line-height: 1.7; margin: 0; white-space: pre-wrap;">${supervisor.hod_report.trim()}</p>
                            </div>
                            ${supervisor.hod_report_submitted_at ? `
                            <p style="font-size: 11px; color: #8a95a5; margin-top: 12px; margin-bottom: 0;">
                                <i class="fa-solid fa-check-circle" style="color: #22c55e;"></i> Submitted: ${new Date(supervisor.hod_report_submitted_at).toLocaleString()}
                            </p>
                            ` : ''}
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        function formatStatus(status) {
            return status
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' ');
        }
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views/supervisor/all_incidents.blade.php ENDPATH**/ ?>