

<?php $__env->startSection('page_title', 'Return-to-Work Tracking'); ?>

<?php $__env->startSection('page_content'); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-brand-light flex items-center justify-center">
                    <i class="fa-solid fa-person-hiking text-brand text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-ink">Return-to-Work Tracking</h1>
                    <p class="text-sm text-muted mt-1">Monitor worker recovery and return schedules</p>
                </div>
            </div>
            <a href="<?php echo e(route('dashboards.supervisor')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-brand text-surface rounded-lg font-medium hover:bg-brand-deep transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
                <span class="hidden sm:inline">Back to Dashboard</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <input type="text" id="tableSearch" placeholder="Search reference, worker name..." class="flex-1 px-4 py-2.5 bg-surface border border-line rounded-lg text-sm placeholder-muted focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        <select id="recordsPerPage" class="px-4 py-2.5 bg-surface border border-line rounded-lg text-sm font-medium text-ink cursor-pointer hover:border-brand transition-colors">
            <option value="all">All Records</option>
            <option value="50">50 Records</option>
            <option value="100">100 Records</option>
            <option value="300">300 Records</option>
        </select>
    </div>

    <!-- Return-to-Work Cases Table -->
    <div class="bg-surface rounded-lg border border-line shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-line flex items-center justify-between gap-4">
            <div>
                <h3 class="text-base md:text-lg font-semibold text-ink">
                    Return-to-Work Cases
                </h3>
                <p class="text-xs text-muted mt-1"><?php echo e($incidents->total()); ?> total cases</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-line bg-canvas">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">#</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">Reference</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden sm:table-cell">Worker</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden md:table-cell">Facility</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">RTW Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider hidden lg:table-cell">Progress</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">Next Review</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-muted uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $statusClass = match($incident->rtw_status) {
                                'on_track' => 'bg-emerald-50 text-emerald-700',
                                'requires_intervention' => 'bg-amber-50 text-amber-700',
                                'completed' => 'bg-green-50 text-green-700',
                                default => 'bg-blue-50 text-blue-700',
                            };
                        ?>
                        <tr class="border-b border-line hover:bg-canvas transition-colors" data-incident-id="<?php echo e($incident->id); ?>">
                            <td class="py-3 px-4 text-xs font-semibold text-muted"><?php echo e($loop->iteration); ?></td>
                            <td class="py-3 px-4 text-xs md:text-sm font-semibold text-brand"><?php echo e($incident->reference_number); ?></td>
                            <td class="py-3 px-4 text-xs md:text-sm text-ink-soft hidden sm:table-cell">
                                <div><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></div>
                                <div class="text-muted text-xs">ID: <?php echo e($incident->id_number); ?></div>
                            </td>
                            <td class="py-3 px-4 text-xs md:text-sm text-ink-soft hidden md:table-cell truncate"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($statusClass); ?>">
                                    <i class="fa-solid fa-circle" style="font-size: 4px;"></i>
                                    <?php echo e(str_replace('_', ' ', ucfirst($incident->rtw_status ?? 'active'))); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-xs md:text-sm text-ink-soft hidden lg:table-cell">
                                <?php if($incident->rtw_start_date && $incident->rtw_end_date): ?>
                                    <?php $progress = $incident->getRTWProgressPercentage(); ?>
                                    <div class="w-full bg-line rounded-full h-1.5">
                                        <div class="bg-brand h-1.5 rounded-full transition-all" style="width: <?php echo e($progress); ?>%"></div>
                                    </div>
                                    <span class="text-xs text-muted mt-1 block"><?php echo e($progress); ?>%</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-xs md:text-sm">
                                <?php if($incident->rtw_next_review_date): ?>
                                    <div class="font-semibold text-ink"><?php echo e($incident->rtw_next_review_date->format('M d')); ?></div>
                                    <?php
                                        $daysUntilReview = now()->diffInDays($incident->rtw_next_review_date, false);
                                    ?>
                                    <div class="text-xs <?php echo e($daysUntilReview < 0 ? 'text-red-600 font-semibold' : 'text-muted'); ?>">
                                        <?php echo e($daysUntilReview < 0 ? 'Overdue' : "$daysUntilReview days"); ?>

                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">Not scheduled</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="toggleDetails(<?php echo e($incident->id); ?>)" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-brand text-surface rounded-lg text-xs font-semibold hover:bg-brand-deep transition-colors">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    <span class="hidden sm:inline">View</span>
                                </button>
                            </td>
                        </tr>
                        <!-- Details Row -->
                        <tr id="details-<?php echo e($incident->id); ?>" class="details-row hidden">
                            <td colspan="8" class="py-4 px-4 bg-canvas">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="text-xs font-semibold text-muted uppercase tracking-wide">Medical Restrictions</label>
                                        <div class="mt-2 text-sm text-ink-soft space-y-1">
                                            <?php $restrictions = $incident->getMedicalRestrictions(); ?>
                                            <?php if($restrictions): ?>
                                                <?php $__currentLoopData = $restrictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $restriction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex items-start gap-2">
                                                        <span class="text-brand mt-0.5">•</span>
                                                        <span><?php echo e($restriction); ?></span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted">No restrictions</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-semibold text-muted uppercase tracking-wide">Modified Duties</label>
                                        <div class="mt-2 text-sm text-ink-soft space-y-1">
                                            <?php $duties = $incident->getModifiedDuties(); ?>
                                            <?php if($duties): ?>
                                                <?php $__currentLoopData = $duties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex items-start gap-2">
                                                        <span class="text-brand mt-0.5">•</span>
                                                        <span><?php echo e($duty); ?></span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Not assigned</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-semibold text-muted uppercase tracking-wide">Accommodations</label>
                                        <p class="mt-2 text-sm text-ink-soft"><?php echo e($incident->rtw_accommodations ?? 'None recorded'); ?></p>
                                    </div>

                                    <div>
                                        <label class="text-xs font-semibold text-muted uppercase tracking-wide">Doctor Clearance</label>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-semibold <?php echo e($incident->rtw_doctor_clearance_received ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'); ?>">
                                                <i class="fa-solid <?php echo e($incident->rtw_doctor_clearance_received ? 'fa-check-circle' : 'fa-circle-xmark'); ?> text-xs"></i>
                                                <?php echo e($incident->rtw_doctor_clearance_received ? 'Received' : 'Pending'); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-semibold text-muted uppercase tracking-wide">Plan Sign-Off</label>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-semibold <?php echo e($incident->rtw_plan_signed_off ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'); ?>">
                                                <i class="fa-solid <?php echo e($incident->rtw_plan_signed_off ? 'fa-check-circle' : 'fa-circle-xmark'); ?> text-xs"></i>
                                                <?php echo e($incident->rtw_plan_signed_off ? 'Signed' : 'Pending'); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-semibold text-muted uppercase tracking-wide">Days Status</label>
                                        <div class="mt-2 text-sm text-ink-soft space-y-1">
                                            <div><span class="font-semibold"><?php echo e($incident->getRTWDaysElapsed() ?? 0); ?></span> days elapsed</div>
                                            <?php if($incident->rtw_end_date): ?>
                                                <div><span class="font-semibold"><?php echo e($incident->getRTWDaysRemaining() ?? 0); ?></span> days remaining</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if($incident->rtw_review_notes): ?>
                                <div class="pt-4 border-t border-line">
                                    <label class="text-xs font-semibold text-muted uppercase tracking-wide">Review Notes</label>
                                    <p class="mt-2 text-sm text-ink-soft"><?php echo e($incident->rtw_review_notes); ?></p>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-solid fa-inbox text-4xl text-line"></i>
                                    <p class="text-sm font-medium text-muted">No return-to-work cases assigned yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($incidents->hasPages()): ?>
        <div class="px-4 md:px-6 py-3 border-t border-line bg-canvas text-center">
            <?php echo e($incidents->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleDetails(incidentId) {
    const detailsRow = document.getElementById('details-' + incidentId);
    if (detailsRow) {
        detailsRow.classList.toggle('hidden');
    }
}

document.getElementById('recordsPerPage')?.addEventListener('change', function() {
    const perPage = this.value;
    const rows = document.querySelectorAll('tbody tr:not(.details-row)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        visibleCount++;
        if (perPage === 'all') {
            row.style.display = 'table-row';
        } else {
            row.style.display = visibleCount <= parseInt(perPage) ? 'table-row' : 'none';
        }
    });
});

document.getElementById('tableSearch')?.addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr:not(.details-row)');
    
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const isVisible = text.includes(searchTerm);
        row.style.display = isVisible ? 'table-row' : 'none';
        
        const incidentId = row.getAttribute('data-incident-id');
        if (incidentId) {
            const detailsRow = document.getElementById('details-' + incidentId);
            if (detailsRow && !isVisible) {
                detailsRow.classList.add('hidden');
            }
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.minimal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\supervisor\return_to_work_tracking.blade.php ENDPATH**/ ?>