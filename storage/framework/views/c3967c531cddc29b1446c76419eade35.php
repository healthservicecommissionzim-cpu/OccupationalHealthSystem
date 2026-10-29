

<?php $__env->startSection('page_title', 'User Activity Log · HSC'); ?>

<?php $__env->startSection('header_title', 'User Activity Log'); ?>
<?php $__env->startSection('header_subtitle', 'Complete audit trail of all system user actions'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-user-secret text-brand"></i> USER ACTIVITY LOG
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">User</th>
                    <th class="text-left py-2 px-3">Action</th>
                    <th class="text-left py-2 px-3">Description</th>
                    <th class="text-left py-2 px-3">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-semibold"><?php echo e($log->user?->name ?? 'System'); ?></td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs"><?php echo e($log->action ?? 'N/A'); ?></span>
                        </td>
                        <td class="py-2 px-3 text-slate-600"><?php echo e($log->description ?? 'No description'); ?></td>
                        <td class="py-2 px-3 text-slate-500"><?php echo e($log->created_at?->format('M d, Y H:i') ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No activity logs found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($logs->hasPages()): ?>
<div class="mt-6">
    <?php echo e($logs->links()); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\system\user-activity.blade.php ENDPATH**/ ?>