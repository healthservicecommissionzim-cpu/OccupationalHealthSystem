

<?php $__env->startSection('page_title', 'Notification History · HSC'); ?>

<?php $__env->startSection('header_title', 'Notification History'); ?>
<?php $__env->startSection('header_subtitle', 'Complete audit trail of all system notifications sent'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-bell text-brand"></i> NOTIFICATION HISTORY
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3">Case Reference</th>
                    <th class="text-left py-2 px-3">Notification Type</th>
                    <th class="text-left py-2 px-3">Channel</th>
                    <th class="text-left py-2 px-3">Recipient</th>
                    <th class="text-center py-2 px-3">Status</th>
                    <th class="text-left py-2 px-3">Sent At</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 font-bold text-brand"><?php echo e($notification->incident?->reference_number ?? 'N/A'); ?></td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs"><?php echo e(str_replace('_', ' ', $notification->notification_type)); ?></span>
                        </td>
                        <td class="py-2 px-3 text-slate-600"><?php echo e($notification->notification_channel ?? 'N/A'); ?></td>
                        <td class="py-2 px-3"><?php echo e($notification->recipientUser?->name ?? 'System'); ?></td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold <?php echo e($notification->status === 'SENT' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e($notification->status); ?>

                            </span>
                        </td>
                        <td class="py-2 px-3 text-slate-500"><?php echo e($notification->sent_at?->format('M d, Y H:i') ?? $notification->created_at?->format('M d, Y H:i') ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-500">No notification history</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($notifications->hasPages()): ?>
<div class="mt-6">
    <?php echo e($notifications->links()); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\system\notification-history.blade.php ENDPATH**/ ?>