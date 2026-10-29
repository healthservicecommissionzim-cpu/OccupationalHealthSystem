

<?php $__env->startSection('page_title', 'Timeline Compliance · HSC'); ?>

<?php $__env->startSection('header_title', 'Timeline Compliance'); ?>
<?php $__env->startSection('header_subtitle', 'Verify cases are processed within SLA timeframes'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-hourglass-end text-brand"></i> TIMELINE COMPLIANCE
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">SLA Compliance (30-Day Resolution Target)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Reference</th>
                    <th class="text-left py-2 px-3">Supervisor Verification (Days)</th>
                    <th class="text-left py-2 px-3">Total Time to Resolution (Days)</th>
                    <th class="text-center py-2 px-3">Within SLA?</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $within_sla = $item['within_sla'];
                    ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand"><?php echo e($item['incident']->reference_number); ?></td>
                        <td class="py-2 px-3">
                            <span class="text-<?php echo e($item['supervisor_verification_time'] && $item['supervisor_verification_time'] <= 7 ? 'emerald-600' : 'amber-600'); ?> font-bold">
                                <?php echo e($item['supervisor_verification_time'] ?? 'Pending'); ?>

                            </span>
                        </td>
                        <td class="py-2 px-3">
                            <span class="text-<?php echo e($item['total_time_to_resolution'] && $item['total_time_to_resolution'] <= 30 ? 'emerald-600' : 'amber-600'); ?> font-bold">
                                <?php echo e($item['total_time_to_resolution'] ?? 'Pending'); ?>

                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold <?php echo e($within_sla ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'); ?>">
                                <i class="fa-solid fa-<?php echo e($within_sla ? 'check-circle' : 'times-circle'); ?> mr-1"></i> <?php echo e($within_sla ? 'Yes' : 'No'); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No timeline data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\quality\audit-timeline.blade.php ENDPATH**/ ?>