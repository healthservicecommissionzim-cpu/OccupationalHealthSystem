

<?php $__env->startSection('page_title', 'Return-to-Work Tracking'); ?>

<?php $__env->startSection('page_content'); ?>

<style>
    .container { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
    .header { margin-bottom: 1rem; }
    .header h3 { 
        font-size: 20px; 
        font-weight: 700; 
        color: #0f172a; 
        display: flex; 
        align-items: center; 
        gap: 0.75rem; 
        margin: 0 0 0.5rem 0;
    }
    .header p { 
        font-size: 14px; 
        color: #64748b; 
        font-weight: 500;
        margin: 0;
    }
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .back-button {
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
    }
    .back-button:hover {
        background: #1D4533;
        color: white;
        transform: translateX(-2px);
    }
    .table-wrapper { 
        background: white; 
        border-radius: 12px; 
        box-shadow: 0 4px 16px rgba(0,0,0,0.12); 
        overflow: hidden; 
        margin-bottom: 3rem; 
    }
    .table-header { 
        padding: 1.5rem; 
        background: linear-gradient(135deg, #1D4533 0%, #1e5140 100%);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .table-header h2 { 
        font-size: 16px; 
        font-weight: 700; 
        color: white; 
        display: flex; 
        align-items: center; 
        gap: 0.75rem;
        margin: 0;
    }
    .header-controls {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .filter-select, .search-input {
        padding: 0.6rem 1rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12px;
        font-family: Tahoma, sans-serif;
    }
    .filter-select {
        cursor: pointer;
    }
    .search-input {
        width: 200px;
    }
    .search-input::placeholder {
        color: #a0aec0;
    }
    .table-container { 
        overflow-x: auto; 
    }
    table { 
        width: 100%; 
        font-size: 13px; 
        border-collapse: collapse; 
    }
    thead { 
        background: #f7fafc; 
    }
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
    }
    tbody tr:hover { 
        background: #f7fafc; 
    }
    tbody tr:last-child td { 
        border-bottom: none; 
    }
    .ref-number { 
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
    .status-active { 
        background: #dbeafe; 
        color: #1e40af; 
    }
    .status-on-track { 
        background: #d1f2eb; 
        color: #0f766e; 
    }
    .status-requires-intervention { 
        background: #fef3c7; 
        color: #92400e; 
    }
    .status-completed { 
        background: #d1fae5; 
        color: #065f46; 
    }
    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin: 0.4rem 0;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #1D4533 0%, #1e5140 100%);
    }
    .action-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.8rem;
        background: #1D4533;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .action-link:hover {
        background: #0f2818;
        transform: translateY(-1px);
    }
    .details-row {
        display: none;
    }
    .details-row.show {
        display: table-row;
        background: #f7fafc;
        animation: slideDown 0.3s ease-out;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .details-content {
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    .detail-section h4 {
        font-size: 12px;
        font-weight: 700;
        color: #2d3748;
        margin: 0 0 0.75rem 0;
        text-transform: uppercase;
    }
    .detail-section p {
        font-size: 13px;
        color: #4a5568;
        margin: 0;
    }
    .empty-state { 
        text-align: center; 
        padding: 3rem 1.5rem;
    }
    .empty-state i { 
        font-size: 48px; 
        color: #cbd5e1; 
        margin-bottom: 1rem;
        display: block;
    }
    .empty-state p { 
        font-size: 16px; 
        color: #64748b; 
        font-weight: 600;
        margin: 0;
    }
    .pagination-wrapper { 
        padding: 1rem; 
        text-align: center; 
        background: white; 
        border-top: 1px solid #e2e8f0;
    }
    @media (max-width: 768px) {
        .header h3 { font-size: 24px; }
        .header-top { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .table-header { flex-direction: column; align-items: flex-start; }
        .header-controls { flex-direction: column; width: 100%; }
        th, td { padding: 0.5rem; font-size: 12px; }
    }
</style>

<div class="container">
    <div class="header">
        <div class="header-top">
            <h3><i class="fa-solid fa-person-hiking"></i> Return-to-Work Tracking</h3>
            <a href="<?php echo e(route('dashboard')); ?>" class="back-button">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
        <p>Monitor worker return-to-work status and schedule reviews</p>
    </div>

    <div class="table-wrapper">
        <div class="table-header">
            <h2><i class="fa-solid fa-table-list"></i> Return-to-Work Cases [<?php echo e($incidents->total()); ?>]</h2>
            <div class="header-controls">
                <select id="recordsPerPage" class="filter-select">
                    <option value="all" selected>All Records</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="300">300</option>
                    <option value="500">500</option>
                </select>
                <input type="text" id="tableSearch" class="search-input" placeholder="Search..." />
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Reference</th>
                        <th>Worker</th>
                        <th>Facility</th>
                        <th>RTW Status</th>
                        <th>Progress</th>
                        <th>Days</th>
                        <th>Next Review</th>
                        <th style="width: 80px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-incident-id="<?php echo e($incident->id); ?>">
                            <td style="text-align: center; font-weight: 600; color: #64748b;"><?php echo e($loop->iteration); ?></td>
                            <td><span class="ref-number"><?php echo e($incident->reference_number); ?></span></td>
                            <td>
                                <div style="font-weight: 600;"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></div>
                                <div style="font-size: 11px; color: #64748b;">ID: <?php echo e($incident->id_number); ?></div>
                            </td>
                            <td><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                    $statusColor = match($incident->rtw_status) {
                                        'active' => 'status-active',
                                        'on_track' => 'status-on-track',
                                        'requires_intervention' => 'status-requires-intervention',
                                        'completed' => 'status-completed',
                                        default => 'status-active',
                                    };
                                ?>
                                <span class="status-badge <?php echo e($statusColor); ?>">
                                    <?php echo e(str_replace('_', ' ', ucfirst($incident->rtw_status))); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($incident->rtw_start_date && $incident->rtw_end_date): ?>
                                    <?php $progress = $incident->getRTWProgressPercentage(); ?>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo e($progress); ?>%"></div>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b;"><?php echo e($progress); ?>%</div>
                                <?php else: ?>
                                    <span style="color: #a0aec0;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($incident->rtw_start_date): ?>
                                    <div style="font-size: 12px;">
                                        <div style="font-weight: 600;"><?php echo e($incident->getRTWDaysElapsed()); ?> days elapsed</div>
                                        <?php if($incident->rtw_end_date): ?>
                                            <div style="font-size: 11px; color: #64748b;"><?php echo e($incident->getRTWDaysRemaining()); ?> remaining</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #a0aec0;">Not started</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($incident->rtw_next_review_date): ?>
                                    <div style="font-size: 12px;">
                                        <div style="font-weight: 600;"><?php echo e($incident->rtw_next_review_date->format('M d, Y')); ?></div>
                                        <?php
                                            $daysUntilReview = now()->diffInDays($incident->rtw_next_review_date, false);
                                        ?>
                                        <div style="font-size: 11px; <?php echo e($daysUntilReview < 0 ? 'color: #dc2626; font-weight: 600;' : 'color: #64748b;'); ?>">
                                            <?php echo e($daysUntilReview < 0 ? 'Overdue' : "$daysUntilReview days"); ?>

                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #a0aec0;">Not scheduled</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <button class="action-link" onclick="toggleDetails(<?php echo e($incident->id); ?>)" title="View Details">
                                    <i class="fa-solid fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <tr id="details-<?php echo e($incident->id); ?>" class="details-row">
                            <td colspan="9">
                                <div class="details-content">
                                    <div class="detail-section">
                                        <h4>Medical Restrictions</h4>
                                        <?php $restrictions = $incident->getMedicalRestrictions(); ?>
                                        <?php if($restrictions): ?>
                                            <?php $__currentLoopData = $restrictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $restriction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p>• <?php echo e($restriction); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <p style="color: #a0aec0;">No restrictions recorded</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="detail-section">
                                        <h4>Modified Duties</h4>
                                        <?php $duties = $incident->getModifiedDuties(); ?>
                                        <?php if($duties): ?>
                                            <?php $__currentLoopData = $duties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p>• <?php echo e($duty); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <p style="color: #a0aec0;">No duties assigned</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="detail-section">
                                        <h4>Work Schedule</h4>
                                        <?php $schedule = $incident->getWorkSchedule(); ?>
                                        <?php if($schedule): ?>
                                            <?php $__currentLoopData = $schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week => $hours): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p><span style="font-weight: 600;"><?php echo e($week); ?>:</span> <?php echo e($hours); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <p style="color: #a0aec0;">No schedule defined</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="detail-section">
                                        <h4>Accommodations</h4>
                                        <?php if($incident->rtw_accommodations): ?>
                                            <p><?php echo e($incident->rtw_accommodations); ?></p>
                                        <?php else: ?>
                                            <p style="color: #a0aec0;">No accommodations recorded</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="detail-section">
                                        <h4>Doctor Clearance</h4>
                                        <p style="<?php echo e($incident->rtw_doctor_clearance_received ? 'color: #0f766e; font-weight: 600;' : 'color: #64748b;'); ?>">
                                            <?php echo e($incident->rtw_doctor_clearance_received ? '✓ Received' : '✗ Pending'); ?>

                                        </p>
                                    </div>

                                    <div class="detail-section">
                                        <h4>Plan Signed Off</h4>
                                        <p style="<?php echo e($incident->rtw_plan_signed_off ? 'color: #0f766e; font-weight: 600;' : 'color: #64748b;'); ?>">
                                            <?php echo e($incident->rtw_plan_signed_off ? '✓ Yes' : '✗ No'); ?>

                                        </p>
                                    </div>
                                </div>
                                <?php if($incident->rtw_review_notes): ?>
                                    <div style="padding: 0 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; margin-top: 1rem;">
                                        <h4 style="font-size: 12px; font-weight: 700; color: #2d3748; margin: 0 0 0.5rem 0;">Review Notes</h4>
                                        <p style="font-size: 13px; color: #4a5568;"><?php echo e($incident->rtw_review_notes); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div style="padding: 0 1.5rem 1.5rem; display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                                    <div>
                                        <p style="font-size: 11px; font-weight: 700; color: #64748b; margin: 0 0 0.5rem 0; text-transform: uppercase;">Last Review</p>
                                        <p style="font-size: 13px; color: #2d3748; font-weight: 600; margin: 0;"><?php echo e($incident->rtw_last_review_date ? $incident->rtw_last_review_date->format('M d, Y') : 'Not yet reviewed'); ?></p>
                                    </div>
                                    <div>
                                        <p style="font-size: 11px; font-weight: 700; color: #64748b; margin: 0 0 0.5rem 0; text-transform: uppercase;">Next Review Due</p>
                                        <p style="font-size: 13px; margin: 0; <?php echo e($incident->isRTWReviewOverdue() ? 'color: #dc2626; font-weight: 600;' : 'color: #2d3748; font-weight: 600;'); ?>">
                                            <?php echo e($incident->rtw_next_review_date ? $incident->rtw_next_review_date->format('M d, Y') : 'Not scheduled'); ?>

                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <p>No return-to-work cases assigned yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($incidents->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($incidents->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleDetails(incidentId) {
    const detailsRow = document.getElementById('details-' + incidentId);
    if (detailsRow) {
        detailsRow.classList.toggle('show');
    }
}

document.getElementById('recordsPerPage').addEventListener('change', function() {
    const perPage = this.value;
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) {
            row.style.display = 'table-row';
            return;
        }
        if (!row.id.startsWith('details-')) {
            visibleCount++;
            if (perPage === 'all') {
                row.style.display = 'table-row';
            } else {
                row.style.display = visibleCount <= parseInt(perPage) ? 'table-row' : 'none';
            }
        }
    });
});

document.getElementById('tableSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        if (row.id.startsWith('details-')) return;
        
        const text = row.innerText.toLowerCase();
        const isVisible = text.includes(searchTerm);
        row.style.display = isVisible ? 'table-row' : 'none';
        
        const incidentId = row.getAttribute('data-incident-id');
        if (incidentId) {
            const detailsRow = document.getElementById('details-' + incidentId);
            if (detailsRow && !isVisible) {
                detailsRow.classList.remove('show');
            }
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.minimal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views/supervisor/return_to_work_tracking.blade.php ENDPATH**/ ?>