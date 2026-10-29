

<?php $__env->startSection('page_title', 'Statistical Summary · HSC'); ?>

<?php $__env->startSection('header_title', 'Statistical Summary'); ?>
<?php $__env->startSection('header_subtitle', 'Key performance indicators and system statistics'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-chart-bar text-brand"></i> STATISTICAL SUMMARY
    </h2>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Cases</p>
        <p class="text-3xl font-bold text-slate-900 mt-1"><?php echo e($stats['total_cases']); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Active Cases</p>
        <p class="text-3xl font-bold text-amber-600 mt-1"><?php echo e($stats['active_cases']); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Resolved Cases</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1"><?php echo e($stats['resolved_cases']); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Rejected Cases</p>
        <p class="text-3xl font-bold text-red-600 mt-1"><?php echo e($stats['rejected_cases']); ?></p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">Cases by Incident Type</h3>
        </div>
        <div class="p-4">
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $stats['total_by_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600"><?php echo e($item->incidentType?->name ?? 'Unknown'); ?></span>
                        <span class="px-2 py-1 bg-brand bg-opacity-10 text-brand rounded text-xs font-bold"><?php echo e($item->count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs text-slate-500">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-900">Top 10 Facilities</h3>
        </div>
        <div class="p-4">
            <div class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $stats['total_by_facility']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600 truncate"><?php echo e($facility->name); ?></span>
                        <span class="px-2 py-1 bg-brand bg-opacity-10 text-brand rounded text-xs font-bold"><?php echo e($facility->incidents_count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs text-slate-500">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\reports\statistics.blade.php ENDPATH**/ ?>