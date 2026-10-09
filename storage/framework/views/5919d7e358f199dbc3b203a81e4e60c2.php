<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Case Dossier · <?php echo e($incident->reference_number ?? 'Case'); ?></title>
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
        
        /* Card */
        .card { background: #fff; border: 1px solid #e1e7ee; border-radius: 14px; box-shadow: 0 8px 25px rgba(16,26,49,.06); overflow: hidden; margin-bottom: 22px; }
        .chead { padding: 18px 22px; border-bottom: 1px solid #e1e7ee; }
        .ctitle { display: flex; align-items: center; gap: 9px; color: #101a31; font-size: 12px; font-weight: 750; text-transform: uppercase; letter-spacing: .55px; }
        
        /* Info Sections */
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
        
        /* Summary Box */
        .summary-box { background: linear-gradient(135deg, #e9f8f3 0%, #d0e8df 100%); padding: 20px; border-radius: 10px; margin-bottom: 24px; border-left: 4px solid #079b6d; }
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .summary-item p:first-child { font-size: 10px; color: #067c58; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
        .summary-item p:last-child { font-size: 16px; font-weight: 750; color: #101a31; margin: 0; }
        
        /* Status Badge */
        .status-badge { display: inline-block; padding: 6px 12px; background: white; color: #079b6d; border-radius: 6px; font-size: 11px; font-weight: 700; }
        
        /* Text Content */
        .text-content { white-space: pre-wrap; line-height: 1.6; color: #263149; }
        
        /* Empty State */
        .empty-state { background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 10px; padding: 20px; text-align: center; }
        .empty-state i { color: #22c55e; font-size: 24px; margin-bottom: 8px; display: block; }
        .empty-state p { color: #15803d; font-size: 13px; margin: 0; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page { padding: 22px 15px; }
            .header { flex-direction: column; gap: 15px; }
            .back { width: 100%; text-align: center; justify-content: center; }
            .summary-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="head">
                <div class="icon"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <h1 class="title">Case Dossier</h1>
                    <div class="sub">Complete audit trail with all clinical findings</div>
                    <span class="case"><?php echo e($incident->reference_number); ?></span>
                </div>
            </div>
            <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <!-- Case Summary -->
        <div class="summary-box">
            <div class="summary-grid">
                <div class="summary-item">
                    <p>Reference Number</p>
                    <p><?php echo e($incident->reference_number); ?></p>
                </div>
                <div class="summary-item">
                    <p>Date Reported</p>
                    <p><?php echo e($incident->created_at ? $incident->created_at->format('M d, Y') : 'N/A'); ?></p>
                </div>
                <div class="summary-item">
                    <p>Incident Date</p>
                    <p><?php echo e($incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') : 'N/A'); ?></p>
                </div>
                <div class="summary-item">
                    <p>Current Status</p>
                    <p><span class="status-badge"><?php echo e($incident->status ? str_replace('_', ' ', ucwords(strtolower($incident->status))) : 'N/A'); ?></span></p>
                </div>
            </div>
        </div>

        <!-- Worker Information -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-user"></i> Worker Information</div>
            </div>
            <div class="info-card">
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Full Name</td>
                            <td><?php echo e($incident->first_name ?? ''); ?> <?php echo e($incident->surname ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td>ID Number</td>
                            <td><?php echo e($incident->id_number ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>EC Number</td>
                            <td><?php echo e($incident->ec_number ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Department</td>
                            <td><?php echo e($incident->department?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Designation</td>
                            <td><?php echo e($incident->designation?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Contact Number</td>
                            <td><?php echo e($incident->phone_number ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?php echo e($incident->email ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Home Address</td>
                            <td><?php echo e($incident->home_address ?? 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Incident Details -->
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-exclamation-circle"></i> Incident Details</div>
            </div>
            <div class="info-card">
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Incident Type</td>
                            <td><?php echo e($incident->incidentType?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Facility</td>
                            <td><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Facility Type</td>
                            <td><?php echo e($incident->facilityType?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>District</td>
                            <td><?php echo e($incident->district?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Province</td>
                            <td><?php echo e($incident->province?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Work Related</td>
                            <td><?php echo e($incident->is_work_related ? 'Yes' : 'No'); ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Description</td>
                            <td><div class="text-content"><?php echo e($incident->incident_description ?? 'N/A'); ?></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Supervisor Verification & HOD Report -->
        <?php if($incident->isSupervisorVerified() || !empty($incident->hod_report)): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-clipboard-user"></i> Supervisor Verification & HOD Report</div>
            </div>
            <div class="info-card">
                <div class="info-header">
                    <p class="info-header-title">Supervisor Information</p>
                </div>
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Supervisor Name</td>
                            <td><?php echo e($incident->supervisor_name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Supervisor Designation</td>
                            <td><?php echo e($incident->supervisorDesignation?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Verified By</td>
                            <td><?php echo e($incident->assignedSupervisor?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Verification Date</td>
                            <td><?php echo e($incident->supervisor_verified_at ? $incident->supervisor_verified_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($incident->supervisor_witness_statement)): ?>
            <div class="info-card" style="margin-top: 16px;">
                <div class="info-header">
                    <p class="info-header-title">Supervisor Witness Statement</p>
                </div>
                <div style="padding: 16px;">
                    <div class="text-content"><?php echo e($incident->supervisor_witness_statement); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($incident->hod_report)): ?>
            <div class="info-card" style="margin-top: 16px;">
                <div class="info-header">
                    <p class="info-header-title">HOD / Supervisor Report</p>
                </div>
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Submitted By</td>
                            <td><?php echo e($incident->hodReportSubmittedBy?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Submission Date</td>
                            <td><?php echo e($incident->hod_report_submitted_at ? $incident->hod_report_submitted_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Witness Statements Acknowledged</td>
                            <td><?php echo e($incident->hod_acknowledged_witness_statements ? 'Yes' : 'No'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding: 16px; border-top: 1px solid #e4e9ee;">
                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Report Content</p>
                    <div class="text-content"><?php echo e($incident->hod_report); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(($incident->witnessTokens && $incident->witnessTokens->count() > 0) || ($incident->firstAidRecords && $incident->firstAidRecords->count() > 0) || true): ?>
            <div class="info-card" style="margin-top: 16px;">
                <!-- Tab Buttons -->
                <div style="display: flex; border-bottom: 2px solid #e1e7ee;">
                    <button onclick="switchTab('witnesses')" id="witnessesTab" style="flex: 1; padding: 14px 20px; background: white; color: #079b6d; border: none; border-bottom: 3px solid #079b6d; font-size: 11px; font-weight: 750; cursor: pointer; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-users"></i> Witness Statements <?php if($incident->witnessTokens && $incident->witnessTokens->count() > 0): ?>(<?php echo e($incident->witnessTokens->count()); ?>)<?php endif; ?>
                    </button>
                    <button onclick="switchTab('firstaid')" id="firstaidTab" style="flex: 1; padding: 14px 20px; background: #f8fafb; color: #8a95a5; border: none; border-bottom: 3px solid transparent; font-size: 11px; font-weight: 750; cursor: pointer; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-heart-pulse"></i> First Aid Records <?php if($incident->firstAidRecords && $incident->firstAidRecords->count() > 0): ?>(<?php echo e($incident->firstAidRecords->count()); ?>)<?php endif; ?>
                    </button>
                </div>

                <!-- Witnesses Tab Content -->
                <div id="witnessesContent" style="display: block;">
                    <?php if($incident->witnessTokens && $incident->witnessTokens->count() > 0): ?>
                        <?php $__currentLoopData = $incident->witnessTokens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $witness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <td>Witness Name</td>
                                    <td><?php echo e($witness->witness_name ?? 'Unnamed'); ?></td>
                                </tr>
                                <tr>
                                    <td>Submission Status</td>
                                    <td>
                                        <?php if($witness->statement_submitted_at): ?>
                                            <span style="display: inline-block; padding: 4px 8px; background: #d1fae5; color: #065f46; border-radius: 4px; font-size: 10px; font-weight: 700;">Submitted - <?php echo e($witness->statement_submitted_at->format('M d, Y')); ?></span>
                                        <?php else: ?>
                                            <span style="display: inline-block; padding: 4px 8px; background: #fef3c7; color: #92400e; border-radius: 4px; font-size: 10px; font-weight: 700;">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if($witness->statement): ?>
                                <tr>
                                    <td style="vertical-align: top;">Statement</td>
                                    <td><div class="text-content"><?php echo e($witness->statement); ?></div></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div style="padding: 40px; text-align: center; background: #f8fafb;">
                            <i class="fa-solid fa-users" style="color: #cbd5e0; font-size: 48px; display: block; margin-bottom: 16px;"></i>
                            <p style="color: #718096; font-size: 14px; font-weight: 600; margin: 0;">No Witness Statements</p>
                            <p style="color: #a0aec0; font-size: 12px; margin-top: 8px;">No witness statements have been recorded for this incident.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- First Aid Tab Content -->
                <div id="firstaidContent" style="display: none;">
                    <?php if($incident->firstAidRecords && $incident->firstAidRecords->count() > 0): ?>
                        <div style="overflow-x: auto;">
                            <table class="info-table" style="width: 100%;">
                                <thead style="background: #f8fafb;">
                                    <tr style="border-bottom: 2px solid #e4e9ee;">
                                        <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Nurse Name</th>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Time Administered</th>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Severity</th>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Injury</th>
                                        <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Treatment</th>
                                        <th style="padding: 12px 16px; text-align: center; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $incident->firstAidRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr style="border-bottom: 1px solid #e4e9ee;">
                                        <td style="padding: 12px 16px; font-size: 12px;"><?php echo e($record->nurse_name ?? 'N/A'); ?></td>
                                        <td style="padding: 12px 16px; font-size: 12px;"><?php echo e($record->time_administered ? \Carbon\Carbon::parse($record->time_administered)->format('M d, Y H:i') : 'N/A'); ?></td>
                                        <td style="padding: 12px 16px; font-size: 12px;">
                                            <span style="display: inline-block; padding: 4px 8px; background: #ffe9e9; color: #c41e3a; border-radius: 4px; font-size: 10px; font-weight: 700;"><?php echo e(ucfirst($record->severity_level ?? 'N/A')); ?></span>
                                        </td>
                                        <td style="padding: 12px 16px; font-size: 11px; max-width: 150px; word-wrap: break-word; overflow-wrap: break-word;"><?php echo e(Str::limit($record->injury_description ?? 'N/A', 40)); ?></td>
                                        <td style="padding: 12px 16px; font-size: 11px; max-width: 150px; word-wrap: break-word; overflow-wrap: break-word;"><?php echo e(Str::limit($record->first_aid_description ?? 'N/A', 40)); ?></td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <button onclick="viewFirstAidRecord(<?php echo e($record->id); ?>)" style="padding: 6px 10px; background: #079b6d; color: white; border: none; border-radius: 4px; font-size: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px; text-align: center; background: #f8fafb;">
                            <i class="fa-solid fa-heart-pulse" style="color: #cbd5e0; font-size: 48px; display: block; margin-bottom: 16px;"></i>
                            <p style="color: #718096; font-size: 14px; font-weight: 600; margin: 0;">No First Aid Records</p>
                            <p style="color: #a0aec0; font-size: 12px; margin-top: 8px;">No first aid records have been recorded for this incident.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- HR Compliance Assessment -->
        <?php if(!empty($incident->hr_compliance_notes)): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-clipboard-check"></i> HR Compliance Assessment</div>
            </div>
            <div class="info-card">
                <div class="info-header">
                    <p class="info-header-title">HR Assessment</p>
                </div>
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Reviewed By</td>
                            <td><?php echo e($incident->assignedHRUser?->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td>Review Date</td>
                            <td><?php echo e($incident->hr_compliance_notes_saved_at ? $incident->hr_compliance_notes_saved_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding: 16px; border-top: 1px solid #e4e9ee;">
                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Assessment Notes</p>
                    <div class="text-content"><?php echo e($incident->hr_compliance_notes); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- DMO Findings -->
        <?php if($dmoUser && !empty($incident->executive_recommendation_text)): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-stethoscope"></i> DMO Clinical Findings & Recommendation</div>
            </div>
            <div class="info-card">
                <div class="info-header">
                    <p class="info-header-title">DMO Review</p>
                </div>
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Reviewed By</td>
                            <td><?php echo e($dmoUser->name ?? 'N/A'); ?> (<?php echo e($dmoUser->email ?? 'N/A'); ?>)</td>
                        </tr>
                        <tr>
                            <td>Audit Verified</td>
                            <td><?php echo e($incident->executive_audit_verified ? 'Yes' : 'No'); ?></td>
                        </tr>
                        <tr>
                            <td>Submission Date</td>
                            <td><?php echo e($incident->executive_recommendation_submitted_at ? $incident->executive_recommendation_submitted_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding: 16px; border-top: 1px solid #e4e9ee;">
                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">DMO Recommendation</p>
                    <div class="text-content"><?php echo e($incident->executive_recommendation_text); ?></div>
                </div>
                <?php if(!empty($incident->service_connection_notes)): ?>
                <div style="padding: 16px; border-top: 1px solid #e4e9ee;">
                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Service Connection Notes</p>
                    <div class="text-content"><?php echo e($incident->service_connection_notes); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php elseif($dmoUser): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-stethoscope"></i> DMO Clinical Findings & Recommendation</div>
            </div>
            <div class="empty-state">
                <i class="fa-solid fa-hourglass-end"></i>
                <p>DMO review is pending. No findings submitted yet.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Medical Superintendent Findings -->
        <?php if($medicalSuperintendentUser && !empty($incident->executive_recommendation_text)): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-hospital-user"></i> Medical Superintendent Findings</div>
            </div>
            <div class="info-card">
                <div class="info-header">
                    <p class="info-header-title">Medical Superintendent Review</p>
                </div>
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Reviewed By</td>
                            <td><?php echo e($medicalSuperintendentUser->name ?? 'N/A'); ?> (<?php echo e($medicalSuperintendentUser->email ?? 'N/A'); ?>)</td>
                        </tr>
                        <tr>
                            <td>Submission Date</td>
                            <td><?php echo e($incident->executive_recommendation_submitted_at ? $incident->executive_recommendation_submitted_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding: 16px; border-top: 1px solid #e4e9ee;">
                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Assessment</p>
                    <div class="text-content"><?php echo e($incident->executive_recommendation_text); ?></div>
                </div>
            </div>
        </div>
        <?php elseif($medicalSuperintendentUser): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-hospital-user"></i> Medical Superintendent Findings</div>
            </div>
            <div class="empty-state">
                <i class="fa-solid fa-hourglass-end"></i>
                <p>Medical Superintendent review is pending. No findings submitted yet.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- CMO Findings -->
        <?php if($cmoUser && !empty($incident->executive_recommendation_text)): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-hospital"></i> CMO Clinical Review</div>
            </div>
            <div class="info-card">
                <div class="info-header">
                    <p class="info-header-title">CMO Review</p>
                </div>
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td>Reviewed By</td>
                            <td><?php echo e($cmoUser->name ?? 'N/A'); ?> (<?php echo e($cmoUser->email ?? 'N/A'); ?>)</td>
                        </tr>
                        <tr>
                            <td>Submission Date</td>
                            <td><?php echo e($incident->executive_recommendation_submitted_at ? $incident->executive_recommendation_submitted_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding: 16px; border-top: 1px solid #e4e9ee;">
                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">CMO Recommendation</p>
                    <div class="text-content"><?php echo e($incident->executive_recommendation_text); ?></div>
                </div>
            </div>
        </div>
        <?php elseif($cmoUser): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-hospital"></i> CMO Clinical Review</div>
            </div>
            <div class="empty-state">
                <i class="fa-solid fa-hourglass-end"></i>
                <p>CMO review is pending. No findings submitted yet.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Uploaded Documents -->
        <?php if($incident->attachments && $incident->attachments->count() > 0): ?>
        <div class="card">
            <div class="chead">
                <div class="ctitle"><i class="fa-solid fa-file-pdf"></i> Uploaded Documents (<?php echo e($incident->attachments->count()); ?>)</div>
            </div>
            <div style="overflow-x: auto;">
                <table class="info-table" style="width: 100%;">
                    <thead style="background: #f8fafb;">
                        <tr style="border-bottom: 2px solid #e4e9ee;">
                            <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">File Name</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Type</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Uploaded By</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Size</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 12px 16px; text-align: center; font-size: 10px; font-weight: 750; color: #8a95a5; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $incident->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border-bottom: 1px solid #e4e9ee;">
                            <td style="padding: 12px 16px; font-size: 12px; font-weight: 600;"><?php echo e($attachment->file_name ?? 'Document'); ?></td>
                            <td style="padding: 12px 16px; font-size: 12px;"><?php echo e($attachment->document_type ? str_replace('_', ' ', ucfirst($attachment->document_type)) : 'N/A'); ?></td>
                            <td style="padding: 12px 16px; font-size: 12px;"><?php echo e($attachment->uploadedByUser?->name ?? 'System'); ?></td>
                            <td style="padding: 12px 16px; font-size: 12px;"><?php echo e($attachment->created_at ? $attachment->created_at->format('M d, Y') : 'N/A'); ?></td>
                            <td style="padding: 12px 16px; font-size: 12px;"><?php echo e($attachment->file_size ? round($attachment->file_size / 1024, 2) . ' KB' : 'N/A'); ?></td>
                            <td style="padding: 12px 16px; font-size: 12px;">
                                <?php if($attachment->upload_status === 'VERIFIED'): ?>
                                    <span style="display: inline-block; padding: 4px 8px; background: #d1fae5; color: #065f46; border-radius: 4px; font-size: 10px; font-weight: 700;"><i class="fa-solid fa-check-circle"></i> Verified</span>
                                <?php elseif($attachment->upload_status === 'PENDING'): ?>
                                    <span style="display: inline-block; padding: 4px 8px; background: #fef3c7; color: #92400e; border-radius: 4px; font-size: 10px; font-weight: 700;"><i class="fa-solid fa-clock"></i> Pending</span>
                                <?php elseif($attachment->upload_status === 'REJECTED'): ?>
                                    <span style="display: inline-block; padding: 4px 8px; background: #fee2e2; color: #991b1b; border-radius: 4px; font-size: 10px; font-weight: 700;"><i class="fa-solid fa-times-circle"></i> Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px 16px; text-align: center; display: flex; gap: 6px; justify-content: center;">
                                <a href="<?php echo e(route('incidents.view_attachment', [$incident, $attachment])); ?>" class="action-btn" style="padding: 6px 10px; background: #079b6d; color: white; border: none; border-radius: 4px; font-size: 10px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                <a href="<?php echo e(route('incidents.download_attachment', [$incident, $attachment])); ?>" class="action-btn" style="padding: 6px 10px; background: #3b82f6; color: white; border: none; border-radius: 4px; font-size: 10px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                    <i class="fa-solid fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- First Aid Modal -->
    <div id="firstAidModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 14px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
            <div style="padding: 20px; border-bottom: 1px solid #e1e7ee; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: white; z-index: 10;">
                <h2 style="margin: 0; color: #101a31; font-size: 18px; font-weight: 750;">First Aid Record Details</h2>
                <button onclick="closeFirstAidModal()" style="background: none; border: none; font-size: 20px; color: #8a95a5; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div id="firstAidContent" style="padding: 20px;">
                <div style="text-align: center; padding: 40px;">
                    <div style="display: inline-block; color: #079b6d;">
                        <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px;"></i>
                    </div>
                    <p style="color: #718096; margin-top: 12px;">Loading record details...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Get tab buttons
            const witnessBtn = document.getElementById('witnessesTab');
            const firstaidBtn = document.getElementById('firstaidTab');
            
            // Get content divs
            const witnessContent = document.getElementById('witnessesContent');
            const firstaidContent = document.getElementById('firstaidContent');
            
            if (tab === 'witnesses') {
                // Show witness content
                if (witnessContent) witnessContent.style.display = 'block';
                if (firstaidContent) firstaidContent.style.display = 'none';
                
                // Style witness button as active
                if (witnessBtn) {
                    witnessBtn.style.background = 'white';
                    witnessBtn.style.color = '#079b6d';
                    witnessBtn.style.borderBottom = '3px solid #079b6d';
                }
                
                // Style first aid button as inactive
                if (firstaidBtn) {
                    firstaidBtn.style.background = '#f8fafb';
                    firstaidBtn.style.color = '#8a95a5';
                    firstaidBtn.style.borderBottom = '3px solid transparent';
                }
            } else if (tab === 'firstaid') {
                // Show first aid content
                if (witnessContent) witnessContent.style.display = 'none';
                if (firstaidContent) firstaidContent.style.display = 'block';
                
                // Style first aid button as active
                if (firstaidBtn) {
                    firstaidBtn.style.background = 'white';
                    firstaidBtn.style.color = '#079b6d';
                    firstaidBtn.style.borderBottom = '3px solid #079b6d';
                }
                
                // Style witness button as inactive
                if (witnessBtn) {
                    witnessBtn.style.background = '#f8fafb';
                    witnessBtn.style.color = '#8a95a5';
                    witnessBtn.style.borderBottom = '3px solid transparent';
                }
            }
        }

        function viewFirstAidRecord(recordId) {
            const modal = document.getElementById('firstAidModal');
            const content = document.getElementById('firstAidContent');
            
            // Show modal with loading state
            modal.style.display = 'flex';
            
            // Fetch record details
            fetch(`/incidents/first-aid/${recordId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load record');
                    return response.json();
                })
                .then(data => {
                    const html = `
                        <div style="display: grid; gap: 16px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div>
                                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Nurse Name</p>
                                    <p style="font-size: 14px; font-weight: 600; color: #101a31; margin: 0;">${data.nurse_name || 'N/A'}</p>
                                </div>
                                <div>
                                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">License Number</p>
                                    <p style="font-size: 14px; font-weight: 600; color: #101a31; margin: 0;">${data.nurse_license_number || 'N/A'}</p>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div>
                                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Time Administered</p>
                                    <p style="font-size: 14px; font-weight: 600; color: #101a31; margin: 0;">${data.time_administered ? new Date(data.time_administered).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'N/A'}</p>
                                </div>
                                <div>
                                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Severity Level</p>
                                    <span style="display: inline-block; padding: 4px 8px; background: #ffe9e9; color: #c41e3a; border-radius: 4px; font-size: 11px; font-weight: 700;">${data.severity_level ? data.severity_level.charAt(0).toUpperCase() + data.severity_level.slice(1) : 'N/A'}</span>
                                </div>
                            </div>

                            <div>
                                <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Injury Description</p>
                                <div style="background: #f8fafb; padding: 12px; border-radius: 8px; border-left: 3px solid #079b6d; font-size: 12px; color: #263149; line-height: 1.6; white-space: pre-wrap;">${data.injury_description || 'N/A'}</div>
                            </div>

                            <div>
                                <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">First Aid Treatment Provided</p>
                                <div style="background: #f8fafb; padding: 12px; border-radius: 8px; border-left: 3px solid #079b6d; font-size: 12px; color: #263149; line-height: 1.6; white-space: pre-wrap;">${data.first_aid_description || 'N/A'}</div>
                            </div>

                            <div>
                                <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Notes</p>
                                <div style="background: #f8fafb; padding: 12px; border-radius: 8px; border-left: 3px solid #079b6d; font-size: 12px; color: #263149; line-height: 1.6; white-space: pre-wrap;">${data.notes || 'None'}</div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                                <div>
                                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Status</p>
                                    <p style="font-size: 12px; color: #101a31; margin: 0;">
                                        ${data.status === 'approved' ? '<span style="display: inline-block; padding: 4px 8px; background: #d1fae5; color: #065f46; border-radius: 4px; font-size: 10px; font-weight: 700;">Approved</span>' : 
                                          data.status === 'pending' ? '<span style="display: inline-block; padding: 4px 8px; background: #fef3c7; color: #92400e; border-radius: 4px; font-size: 10px; font-weight: 700;">Pending</span>' :
                                          data.status === 'rejected' ? '<span style="display: inline-block; padding: 4px 8px; background: #fee2e2; color: #991b1b; border-radius: 4px; font-size: 10px; font-weight: 700;">Rejected</span>' :
                                          'N/A'}
                                    </p>
                                </div>
                                <div>
                                    <p style="font-size: 10px; color: #8a95a5; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">Created At</p>
                                    <p style="font-size: 12px; color: #101a31; margin: 0;">${new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = `
                        <div style="text-align: center; padding: 40px;">
                            <i class="fa-solid fa-exclamation-circle" style="color: #ef4444; font-size: 24px; display: block; margin-bottom: 12px;"></i>
                            <p style="color: #991b1b; margin: 0;">Failed to load record details</p>
                        </div>
                    `;
                    console.error('Error:', error);
                });
        }

        function closeFirstAidModal() {
            document.getElementById('firstAidModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        document.getElementById('firstAidModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeFirstAidModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\OccupationalHealth System\resources\views\incidents\case_dossier_with_findings.blade.php ENDPATH**/ ?>