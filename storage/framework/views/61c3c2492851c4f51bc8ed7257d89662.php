

<?php $__env->startSection('page_title', 'Cases by Status · HSC'); ?>

<?php $__env->startSection('header_title', 'Cases by Status'); ?>
<?php $__env->startSection('header_subtitle', 'View cases organized by their current workflow status'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-filter text-brand"></i> CASES BY STATUS
    </h2>
</div>

<div class="space-y-6">
    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $cases = $casesByStatus[$status] ?? collect();
            $count = $cases->count();
        ?>
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900"><?php echo e(str_replace('_', ' ', $status)); ?></h3>
                <span class="px-3 py-1 bg-brand bg-opacity-10 text-brand text-xs font-bold rounded-full"><?php echo e($count); ?></span>
            </div>
            
            <?php if($count > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Reference</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Employee</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Facility</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Type</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Date</th>
                                <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-slate-200 hover:bg-slate-50">
                                    <td class="py-2 px-3 font-semibold text-brand text-xs"><?php echo e($incident->reference_number); ?></td>
                                    <td class="py-2 px-3 text-xs"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                                    <td class="py-2 px-3 text-xs"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                                    <td class="py-2 px-3 text-xs"><?php echo e($incident->incidentType?->name ?? 'Unknown'); ?></td>
                                    <td class="py-2 px-3 text-xs text-slate-500"><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                                    <td class="py-2 px-3 text-center">
                                        <a href="<?php echo e(route('incidents.hsc_view', $incident)); ?>" class="inline-flex items-center gap-1 px-2 py-1 bg-brand text-white rounded text-xs hover:bg-brand-dark">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="py-8 text-center text-slate-500 text-sm">
                    <i class="fa-solid fa-inbox text-2xl text-slate-300 mb-2"></i>
                    <p>No cases with this status</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\cases\by-status.blade.php ENDPATH**/ ?>