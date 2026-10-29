<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Executive Review · <?php echo e($incident->reference_number ?? 'Case Dossier'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; font-size: 13px; background: #f5f7fa; min-height: 100vh; overflow-x: hidden; }
        
        /* Top Header Bar */
        .top-bar { background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; padding: 1.25rem 2rem; box-shadow: 0 4px 12px rgba(7, 155, 109, 0.15); }
        .top-bar-content { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .case-header h1 { font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .case-ref { display: inline-block; padding: 4px 12px; background: rgba(255,255,255,0.2); border-radius: 6px; font-size: 11px; font-weight: 600; margin-left: 12px; }
        .case-subtitle { font-size: 11px; opacity: 0.9; margin-top: 4px; }
        .back-btn { padding: 8px 16px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; color: white; text-decoration: none; font-size: 12px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
        .back-btn:hover { background: rgba(255,255,255,0.25); }
        
        /* Main Container */
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        
        /* Layout Grid */
        .layout { display: grid; grid-template-columns: 380px 1fr; gap: 1.5rem; min-width: 0; }
        
        /* Sidebar */
        .sidebar { display: flex; flex-direction: column; gap: 1rem; min-width: 0; }
        
        /* Info Cards */
        .info-card { background: white; border: 1px solid #e1e7ee; border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 8px rgba(16,26,49,.04); min-width: 0; }
        .info-card-header { display: flex; align-items: center; gap: 8px; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f1f4f7; }
        .info-icon { width: 32px; height: 32px; background: #e9f8f3; color: #079b6d; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .info-card-title { font-size: 11px; font-weight: 700; color: #101a31; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .info-item { margin-bottom: 0.875rem; min-width: 0; }
        .info-item:last-child { margin-bottom: 0; }
        .info-label { font-size: 9px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .info-value { font-size: 12px; font-weight: 600; color: #101a31; line-height: 1.5; word-wrap: break-word; overflow-wrap: break-word; }
        
        .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; }
        .status-yes { background: #d1fae5; color: #065f46; }
        .status-no { background: #fee2e2; color: #991b1b; }
        
        /* Main Content */
        .main-content { display: flex; flex-direction: column; gap: 1.5rem; min-width: 0; }
        
        /* Section Card */
        .section-card { background: white; border: 1px solid #e1e7ee; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(16,26,49,.04); min-width: 0; }
        .section-header { background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%); padding: 1rem 1.125rem; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .section-title { font-size: 12px; font-weight: 700; color: #101a31; display: flex; align-items: center; gap: 9px; }
        .section-title i { color: #079b6d; font-size: 15px; flex-shrink: 0; }
        .section-body { padding: 1.125rem; min-width: 0; overflow-x: auto; }
        
        /* Tabs */
        .tabs { display: flex; gap: 4px; background: #f8fafb; padding: 6px; border-radius: 8px; margin-bottom: 1.25rem; flex-wrap: wrap; }
        .tab { flex: 1; min-width: max-content; padding: 10px 16px; background: transparent; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; color: #64748b; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .tab:hover { background: rgba(7, 155, 109, 0.08); color: #079b6d; }
        .tab.active { background: #079b6d; color: white; box-shadow: 0 2px 8px rgba(7, 155, 109, 0.25); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Metadata Bar */
        .metadata-bar { display: flex; align-items: center; gap: 16px; padding: 12px 16px; background: #f8fafb; border-radius: 8px; font-size: 11px; color: #64748b; margin-bottom: 1rem; flex-wrap: wrap; }
        .metadata-bar strong { color: #101a31; font-weight: 600; }
        
        /* Text Content */
        .text-content { font-size: 12px; color: #475569; line-height: 1.8; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; }
        .text-preview { max-height: 180px; overflow: hidden; position: relative; }
        .text-preview.expanded { max-height: none; }
        
        .toggle-btn { margin-top: 12px; padding: 8px 14px; background: #e9f8f3; border: 1px solid #079b6d; border-radius: 6px; color: #067c58; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .toggle-btn:hover { background: #d1f4e8; }
        
        /* List Items */
        .list-item { padding: 14px; background: #f8fafb; border: 1px solid #e1e7ee; border-radius: 8px; margin-bottom: 10px; min-width: 0; }
        .list-item:last-child { margin-bottom: 0; }
        .list-item-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px; gap: 12px; flex-wrap: wrap; }
        .list-item-title { font-size: 12px; font-weight: 600; color: #101a31; word-wrap: break-word; overflow-wrap: break-word; }
        .list-item-meta { font-size: 10px; color: #64748b; white-space: nowrap; }
        .list-item-content { font-size: 11px; color: #475569; line-height: 1.6; padding: 10px; background: white; border-radius: 6px; border-left: 3px solid #079b6d; word-wrap: break-word; overflow-wrap: break-word; }
        
        /* Document Grid */
        .doc-grid { display: grid; gap: 8px; }
        .doc-card { display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: #f8fafb; border: 1px solid #e1e7ee; border-radius: 7px; transition: all 0.2s; min-width: 0; }
        .doc-card:hover { background: #f3f6f8; border-color: #079b6d; }
        .doc-icon { width: 34px; height: 34px; background: linear-gradient(135deg, #079b6d 0%, #067c58 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-title { font-size: 11px; font-weight: 600; color: #101a31; margin-bottom: 2px; }
        .doc-subtitle { font-size: 9px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doc-badge { padding: 4px 8px; border-radius: 4px; font-size: 9px; font-weight: 600; background: #d1fae5; color: #065f46; flex-shrink: 0; white-space: nowrap; }
        .doc-action { padding: 6px 11px; background: white; border: 1px solid #d7e0e8; border-radius: 5px; color: #475569; font-size: 10px; font-weight: 600; text-decoration: none; transition: all 0.2s; white-space: nowrap; flex-shrink: 0; }
        .doc-action:hover { background: #f8fafb; }
        
        /* Grid Layout for Info */
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .info-grid-item { display: flex; flex-direction: column; min-width: 0; }
        .info-grid-label { font-size: 9px; font-weight: 700; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .info-grid-value { font-size: 12px; font-weight: 600; color: #101a31; background: #f8fafb; padding: 10px 12px; border-radius: 8px; border: 1px solid #e4e9ee; word-wrap: break-word; overflow-wrap: break-word; }
        
        /* Checkbox Group */
        .checkbox-group { display: flex; align-items: start; gap: 10px; padding: 16px; background: #f8fafb; border: 1px solid #e1e7ee; border-radius: 10px; border-left: 3px solid #079b6d; }
        .checkbox-group input[type="checkbox"] { margin-top: 2px; width: 20px; height: 20px; cursor: pointer; accent-color: #079b6d; flex-shrink: 0; }
        .checkbox-group label { font-size: 11px; font-weight: 600; color: #4e596c; line-height: 1.6; cursor: pointer; }
        
        /* Action Buttons */
        .action-section { background: white; border: 1px solid #e1e7ee; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(16,26,49,.04); }
        .btn { padding: 12px 24px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 1px solid; white-space: nowrap; }
        .btn-primary { background: #079b6d; color: white; border-color: #079b6d; box-shadow: 0 4px 12px rgba(7, 155, 109, 0.2); }
        .btn-primary:hover { background: #067c58; border-color: #067c58; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(7, 155, 109, 0.3); }
        
        /* Alert Banner */
        .alert-banner { display: flex; align-items: start; gap: 14px; padding: 16px; background: #fff8e7; border: 1px solid #f6d48b; border-left: 4px solid #f59e0b; border-radius: 10px; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1); }
        .alert-icon { width: 40px; height: 40px; background: #fff0c9; color: #c67a00; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .alert-content { flex: 1; min-width: 0; }
        .alert-content strong { display: block; font-size: 12px; color: #624300; margin-bottom: 4px; font-weight: 700; }
        .alert-content p { font-size: 11px; color: #7d6332; line-height: 1.6; margin: 0; word-wrap: break-word; overflow-wrap: break-word; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
        .empty-state i { font-size: 48px; color: #e4e9ef; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 12px; color: #8a95a5; }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
            .container { padding: 1rem; }
        }
        @media (max-width: 768px) {
            .sidebar { grid-template-columns: 1fr; }
            .info-grid { grid-template-columns: 1fr; }
            .tabs { flex-direction: column; }
            .tab { min-width: 100%; }
            .doc-card { flex-wrap: wrap; }
            .top-bar { padding: 1rem; }
            .top-bar-content { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <!-- Top Header Bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="case-header">
                <h1>
                    <i class="fa-solid fa-gavel"></i> 
                    Executive Clinical Review
                    <?php if(isset($incident)): ?>
                    <span class="case-ref"><?php echo e($incident->reference_number); ?></span>
                    <?php endif; ?>
                </h1>
                <div class="case-subtitle">Clinical audit and executive recommendation for injury-on-duty determination</div>
            </div>
            <a href="<?php echo e(url()->previous()); ?>" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php if(!isset($incident)): ?>
        <div class="container">
            <div class="alert-banner">
                <div class="alert-icon"><i class="fa-solid fa-exclamation-triangle"></i></div>
                <div class="alert-content">
                    <strong>Error:</strong>
                    <p>Incident data not found. Please return to the dashboard and try again.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
    <div class="container">
        <?php if($isReadOnly ?? false): ?>
        <div class="alert-banner" style="background: #eff6ff; border-color: #bfdbfe; border-left-color: #3b82f6; margin-bottom: 1.5rem;">
            <div class="alert-icon" style="background: #dbeafe; color: #1e40af;"><i class="fa-solid fa-info-circle"></i></div>
            <div class="alert-content">
                <strong style="color: #1e40af;">Read-Only Mode</strong>
                <p style="color: #1e40af;">This case has been escalated and is now in read-only mode. You can review the information but cannot make changes.</p>
            </div>
        </div>
        <?php endif; ?>
        <div class="layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Worker Info Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon"><i class="fa-solid fa-user"></i></div>
                        <div class="info-card-title">Worker Information</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?php echo e($incident->first_name ?? ''); ?> <?php echo e($incident->surname ?? ''); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID Number</div>
                        <div class="info-value"><?php echo e($incident->id_number ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Designation</div>
                        <div class="info-value"><?php echo e($incident->designation?->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Department</div>
                        <div class="info-value"><?php echo e($incident->department?->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact</div>
                        <div class="info-value"><?php echo e($incident->phone_number ?? 'N/A'); ?></div>
                    </div>
                </div>

                <!-- Incident Info Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon"><i class="fa-solid fa-exclamation-circle"></i></div>
                        <div class="info-card-title">Incident Details</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Incident Date</div>
                        <div class="info-value"><?php echo e($incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') : 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Incident Type</div>
                        <div class="info-value"><?php echo e($incident->incidentType?->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Work Related</div>
                        <div>
                            <span class="status-badge <?php echo e($incident->is_work_related ? 'status-yes' : 'status-no'); ?>">
                                <?php echo e($incident->is_work_related ? 'Yes' : 'No'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Description</div>
                        <div class="info-value" style="font-size: 11px; font-weight: 500; line-height: 1.6;"><?php echo e($incident->incident_description ?? 'N/A'); ?></div>
                    </div>
                </div>

                <!-- Facility Info Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div class="info-card-title">Facility Information</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Facility</div>
                        <div class="info-value"><?php echo e($incident->facility?->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Facility Type</div>
                        <div class="info-value"><?php echo e($incident->facilityType?->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">District</div>
                        <div class="info-value"><?php echo e($incident->district?->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Province</div>
                        <div class="info-value"><?php echo e($incident->province?->name ?? 'N/A'); ?></div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- HOD/Supervisor Report Section -->
                <?php if(!empty($incident->hod_report)): ?>
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fa-solid fa-clipboard-list"></i>
                            Departmental Assessment & Witness Documentation
                        </div>
                        <span style="font-size: 10px; color: #64748b; font-weight: 600;">
                            <?php echo e($incident->witnessTokens ? $incident->witnessTokens->count() : 0); ?> Witnesses · 
                            <?php echo e($incident->firstAidRecords ? $incident->firstAidRecords->count() : 0); ?> First Aid Records
                        </span>
                    </div>
                    <div class="section-body">
                        <div class="tabs">
                            <button type="button" class="tab active" onclick="switchTab('hod-report')">
                                <i class="fa-solid fa-file-lines"></i> Supervisor Report
                            </button>
                            <button type="button" class="tab" onclick="switchTab('witness-statements')">
                                <i class="fa-solid fa-users"></i> Witnesses (<?php echo e($incident->witnessTokens ? $incident->witnessTokens->count() : 0); ?>)
                            </button>
                            <button type="button" class="tab" onclick="switchTab('first-aid')">
                                <i class="fa-solid fa-heart-pulse"></i> First Aid (<?php echo e($incident->firstAidRecords ? $incident->firstAidRecords->count() : 0); ?>)
                            </button>
                        </div>

                        <!-- Tab 1: HOD Report -->
                        <div id="hod-report" class="tab-content active">
                            <div class="metadata-bar">
                                <div><strong>Submitted by:</strong> <?php echo e($incident->hodReportSubmittedBy?->name ?? 'N/A'); ?></div>
                                <?php if($incident->hod_report_submitted_at): ?>
                                <div><strong>Date:</strong> <?php echo e($incident->hod_report_submitted_at->format('M d, Y H:i')); ?></div>
                                <?php endif; ?>
                            </div>
                            <div id="hodReportPreview" class="text-content text-preview"><?php echo e($incident->hod_report); ?></div>
                            <div id="hodReportFull" class="text-content" style="display: none;"><?php echo e($incident->hod_report); ?></div>
                            <?php if(strlen($incident->hod_report ?? '') > 400): ?>
                            <button type="button" id="toggleHodReport" onclick="toggleHodReport()" class="toggle-btn">
                                <i class="fa-solid fa-chevron-down"></i> Show More
                            </button>
                            <?php endif; ?>
                        </div>

                        <!-- Tab 2: Witness Statements -->
                        <div id="witness-statements" class="tab-content">
                            <?php if($incident->witnessTokens && $incident->witnessTokens->count() > 0): ?>
                                <div class="alert-banner" style="margin-bottom: 1rem; background: <?php echo e($incident->hod_acknowledged_witness_statements ? '#d1fae5' : '#fef3c7'); ?>; border-color: <?php echo e($incident->hod_acknowledged_witness_statements ? '#10b981' : '#f59e0b'); ?>;">
                                    <div class="alert-icon" style="background: <?php echo e($incident->hod_acknowledged_witness_statements ? '#a7e6d7' : '#fde68a'); ?>; color: <?php echo e($incident->hod_acknowledged_witness_statements ? '#065f46' : '#92400e'); ?>;">
                                        <i class="fa-solid <?php echo e($incident->hod_acknowledged_witness_statements ? 'fa-check-circle' : 'fa-exclamation-circle'); ?>"></i>
                                    </div>
                                    <div class="alert-content">
                                        <strong>Supervisor Acknowledgment</strong>
                                        <p>
                                            <?php if($incident->hod_acknowledged_witness_statements): ?>
                                                <?php echo e($incident->supervisor_name ?? 'Supervisor'); ?> has reviewed and acknowledged all witness statements.
                                            <?php else: ?>
                                                <?php echo e($incident->supervisor_name ?? 'Supervisor'); ?> has not yet acknowledged the witness statements.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <?php $__currentLoopData = $incident->witnessTokens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $witness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="list-item">
                                        <div class="list-item-header">
                                            <div class="list-item-title">
                                                <i class="fa-solid fa-user" style="color: #079b6d;"></i>
                                                <?php echo e($witness->witness_name ?? 'Unnamed Witness'); ?>

                                            </div>
                                            <?php if($witness->statement_submitted_at): ?>
                                                <span class="status-badge status-yes">
                                                    <i class="fa-solid fa-check"></i> <?php echo e($witness->statement_submitted_at->format('M d, Y')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge" style="background: #fef3c7; color: #92400e;">
                                                    <i class="fa-solid fa-clock"></i> Pending
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($witness->statement): ?>
                                            <div class="list-item-content"><?php echo e($witness->statement); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <p>No witness statements recorded for this incident</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tab 3: First Aid Records -->
                        <div id="first-aid" class="tab-content">
                            <?php if($incident->firstAidRecords && $incident->firstAidRecords->count() > 0): ?>
                                <?php $__currentLoopData = $incident->firstAidRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="list-item">
                                        <div class="list-item-header">
                                            <div class="list-item-title">
                                                <i class="fa-solid fa-user-nurse" style="color: #079b6d;"></i>
                                                <?php echo e($record->nurse_name ?? 'First Aid Record'); ?>

                                            </div>
                                            <div class="list-item-meta"><?php echo e($record->time_administered ? \Carbon\Carbon::parse($record->time_administered)->format('M d, Y H:i') : 'N/A'); ?></div>
                                        </div>
                                        <div style="display: grid; gap: 8px; margin-top: 8px;">
                                            <?php if($record->injury_description): ?>
                                                <div class="list-item-content"><strong>Injury:</strong> <?php echo e($record->injury_description); ?></div>
                                            <?php endif; ?>
                                            <?php if($record->first_aid_description): ?>
                                                <div class="list-item-content"><strong>Treatment:</strong> <?php echo e($record->first_aid_description); ?></div>
                                            <?php endif; ?>
                                            <?php if($record->severity_level): ?>
                                                <div class="list-item-content"><strong>Severity:</strong> <?php echo e(ucfirst($record->severity_level)); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-kit-medical"></i>
                                    <p>No first aid records available for this incident</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- HR Compliance Assessment Section -->
                <?php if(!empty($incident->hr_compliance_notes)): ?>
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fa-solid fa-notes-medical"></i>
                            HR Compliance Assessment
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="metadata-bar">
                            <div><strong>Reviewed by:</strong> <?php echo e($incident->assignedHRUser?->name ?? 'N/A'); ?></div>
                            <?php if($incident->hr_compliance_notes_saved_at): ?>
                            <div><strong>Date:</strong> <?php echo e($incident->hr_compliance_notes_saved_at->format('M d, Y H:i')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div id="hrNotesPreview" class="text-content text-preview"><?php echo e($incident->hr_compliance_notes); ?></div>
                        <div id="hrNotesFull" class="text-content" style="display: none;"><?php echo e($incident->hr_compliance_notes); ?></div>
                        <?php if(strlen($incident->hr_compliance_notes ?? '') > 300): ?>
                        <button type="button" id="toggleHrNotes" onclick="toggleHrNotes()" class="toggle-btn">
                            <i class="fa-solid fa-chevron-down"></i> Show More
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Uploaded Documents Section -->
                <?php if($incident->attachments && $incident->attachments->count() > 0): ?>
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fa-solid fa-file-medical"></i>
                            Uploaded Documents
                        </div>
                        <span style="font-size: 10px; color: #64748b; font-weight: 600;">
                            <?php echo e($incident->attachments->count()); ?> <?php echo e($incident->attachments->count() === 1 ? 'Document' : 'Documents'); ?>

                        </span>
                    </div>
                    <div class="section-body">
                        <div class="doc-grid">
                            <?php $__currentLoopData = $incident->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="doc-card">
                                <div class="doc-icon"><i class="fa-solid fa-file-pdf"></i></div>
                                <div class="doc-info">
                                    <div class="doc-title"><?php echo e($doc->getDocumentTypeLabel()); ?></div>
                                    <div class="doc-subtitle"><?php echo e($doc->file_name); ?></div>
                                </div>
                                <span class="doc-badge"><i class="fa-solid fa-check"></i> <?php echo e(ucfirst($doc->upload_status)); ?></span>
                                <a href="<?php echo e(route('incidents.view_attachment', [$incident, $doc])); ?>" target="_blank" class="doc-action">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Next Step -->
                <div class="section-card" style="text-align: center;">
                    <div class="section-body" style="padding: 2rem 1.5rem;">
                        <a href="<?php echo e(route('executive.recommendation', $incident)); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-right"></i> Continue to Executive Recommendation
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function toggleHodReport() {
            const preview = document.getElementById('hodReportPreview');
            const full = document.getElementById('hodReportFull');
            const button = document.getElementById('toggleHodReport');
            
            if (preview.style.display === 'none') {
                // Show preview (collapsed state)
                preview.style.display = 'block';
                full.style.display = 'none';
                button.innerHTML = '<i class="fa-solid fa-chevron-down"></i> Show More';
            } else {
                // Show full (expanded state)
                preview.style.display = 'none';
                full.style.display = 'block';
                button.innerHTML = '<i class="fa-solid fa-chevron-up"></i> Show Less';
            }
        }

        function toggleHrNotes() {
            const preview = document.getElementById('hrNotesPreview');
            const full = document.getElementById('hrNotesFull');
            const button = document.getElementById('toggleHrNotes');
            
            if (preview.style.display === 'none') {
                // Show preview (collapsed state)
                preview.style.display = 'block';
                full.style.display = 'none';
                button.innerHTML = '<i class="fa-solid fa-chevron-down"></i> Show More';
            } else {
                // Show full (expanded state)
                preview.style.display = 'none';
                full.style.display = 'block';
                button.innerHTML = '<i class="fa-solid fa-chevron-up"></i> Show Less';
            }
        }

        function switchTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.closest('.tab-btn').classList.add('active');
        }
    </script>
</body>
</html><?php /**PATH D:\OccupationalHealth System\resources\views\incidents\executive_review.blade.php ENDPATH**/ ?>