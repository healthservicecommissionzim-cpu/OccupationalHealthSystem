

<?php $__env->startSection('page_title', 'Risk Assessment · HSC'); ?>

<?php $__env->startSection('header_title', 'Risk Assessment'); ?>
<?php $__env->startSection('header_subtitle', 'Identify and monitor high-risk incidents requiring urgent attention'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-exclamation-triangle text-orange-600"></i> RISK ASSESSMENT
    </h2>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">High-Risk Incidents (Last 30 Days)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-2 px-3">Reference</th>
                        <th class="text-left py-2 px-3">Facility</th>
                        <th class="text-left py-2 px-3">Status</th>
                        <th class="text-center py-2 px-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $highRiskIncidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="py-2 px-3 font-bold text-brand"><?php echo e($incident->reference_number); ?></td>
                            <td class="py-2 px-3"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs"><?php echo e(str_replace('_', ' ', $incident->status)); ?></span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <a href="<?php echo e(route('incidents.hsc_view', $incident)); ?>" class="text-brand hover:underline">View</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-500">No high-risk incidents</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">Escalated Cases (Clinical Review)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-2 px-3">Reference</th>
                        <th class="text-left py-2 px-3">Escalation Level</th>
                        <th class="text-left py-2 px-3">Date</th>
                        <th class="text-center py-2 px-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $escalatedCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="py-2 px-3 font-bold text-brand"><?php echo e($incident->reference_number); ?></td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs"><?php echo e(str_replace('_', ' ', $incident->status)); ?></span>
                            </td>
                            <td class="py-2 px-3"><?php echo e($incident->escalated_to_dmo_at?->format('M d, Y') ?? 'N/A'); ?></td>
                            <td class="py-2 px-3 text-center">
                                <a href="<?php echo e(route('incidents.hsc_view', $incident)); ?>" class="text-brand hover:underline">View</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-500">No escalated cases</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\monitoring\risk-assessment.blade.php ENDPATH**/ ?>