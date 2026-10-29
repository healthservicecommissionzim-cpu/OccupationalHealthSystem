

<?php $__env->startSection('page_title', 'Escalation Tracking · HSC'); ?>

<?php $__env->startSection('header_title', 'Escalation Tracking'); ?>
<?php $__env->startSection('header_subtitle', 'Follow critical cases escalated to clinical and provincial review'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-arrow-up-right-dots text-red-600"></i> ESCALATION TRACKING
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Reference</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Employee</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Facility</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Escalation Status</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600">Escalated Date</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Assigned HR User</th>
                    <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $escalatedCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-3 px-4 font-semibold text-brand"><?php echo e($incident->reference_number); ?></td>
                        <td class="py-3 px-4 text-sm"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td class="py-3 px-4 text-sm"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                <?php echo e(str_replace('_', ' ', $incident->status)); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-500"><?php echo e($incident->escalated_to_dmo_at?->format('M d, Y') ?? 'N/A'); ?></td>
                        <td class="py-3 px-4 text-center text-sm"><?php echo e($incident->assignedHRUser?->name ?? 'Unassigned'); ?></td>
                        <td class="py-3 px-4 text-center">
                            <a href="<?php echo e(route('incidents.hsc_view', $incident)); ?>" class="text-brand hover:underline text-xs font-semibold">View</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">No escalated cases</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($escalatedCases->hasPages()): ?>
<div class="mt-6">
    <?php echo e($escalatedCases->links()); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\system\escalation-tracking.blade.php ENDPATH**/ ?>