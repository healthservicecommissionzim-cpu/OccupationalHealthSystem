

<?php $__env->startSection('page_title', 'All Cases · HSC'); ?>

<?php $__env->startSection('header_title', 'All Cases'); ?>
<?php $__env->startSection('header_subtitle', 'Complete view of all incidents in the system'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-list-check text-brand"></i> ALL CASES
        </h2>
        <div class="flex gap-2">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search by reference, name, ID..." 
                    class="px-3 py-2 border border-slate-200 rounded-lg text-[12px]" value="<?php echo e(request('search')); ?>">
                <select name="status" class="px-3 py-2 border border-slate-200 rounded-lg text-[12px]">
                    <option value="">All Statuses</option>
                    <option value="PENDING_SUPERVISOR_VERIFICATION" <?php echo e(request('status') === 'PENDING_SUPERVISOR_VERIFICATION' ? 'selected' : ''); ?>>Pending Verification</option>
                    <option value="ESCALATED_TO_HR" <?php echo e(request('status') === 'ESCALATED_TO_HR' ? 'selected' : ''); ?>>HR Review</option>
                    <option value="RESOLVED" <?php echo e(request('status') === 'RESOLVED' ? 'selected' : ''); ?>>Resolved</option>
                    <option value="REJECTED" <?php echo e(request('status') === 'REJECTED' ? 'selected' : ''); ?>>Rejected</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg text-[12px] font-semibold hover:bg-brand-dark">
                    Filter
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Cases</p>
        <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($statusCounts->sum() ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Pending Review</p>
        <p class="text-2xl font-bold text-amber-600 mt-1"><?php echo e($statusCounts->get('PENDING_SUPERVISOR_VERIFICATION', 0)); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">In HR Review</p>
        <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($statusCounts->get('ESCALATED_TO_HR', 0)); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Resolved</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1"><?php echo e($statusCounts->get('RESOLVED', 0) + $statusCounts->get('CLOSED', 0)); ?></p>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Reference</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Employee</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Facility</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Type</th>
                <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Date</th>
                <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
                <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-slate-200 hover:bg-slate-50">
                    <td class="py-3 px-4 text-xs font-semibold text-brand"><?php echo e($incident->reference_number); ?></td>
                    <td class="py-3 px-4 text-xs text-slate-700"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                    <td class="py-3 px-4 text-xs text-slate-700"><?php echo e($incident->facility?->name ?? 'N/A'); ?></td>
                    <td class="py-3 px-4 text-xs text-slate-700"><?php echo e($incident->incidentType?->name ?? 'Unknown'); ?></td>
                    <td class="py-3 px-4 text-xs text-slate-500"><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                    <td class="py-3 px-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            <?php echo e(str_replace('_', ' ', $incident->status)); ?>

                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <a href="<?php echo e(route('incidents.hsc_view', $incident)); ?>" class="inline-flex items-center gap-1 px-3 py-1 bg-brand text-white rounded text-xs font-semibold hover:bg-brand-dark">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="py-8 text-center text-slate-500 text-sm">No cases found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if($incidents->hasPages()): ?>
<div class="mt-6">
    <?php echo e($incidents->links()); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\cases\all.blade.php ENDPATH**/ ?>