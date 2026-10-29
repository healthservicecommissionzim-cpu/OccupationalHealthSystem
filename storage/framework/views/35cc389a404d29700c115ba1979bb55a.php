

<?php $__env->startSection('page_title', 'Incident Trends · HSC'); ?>

<?php $__env->startSection('header_title', 'Incident Trends'); ?>
<?php $__env->startSection('header_subtitle', 'Analyze patterns and trends in occupational health incidents'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-chart-area text-brand"></i> INCIDENT TRENDS ANALYSIS
    </h2>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Incidents by Type (90 Days)</h3>
        <div class="space-y-3">
            <?php $__currentLoopData = $trendsByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600"><?php echo e($item->incidentType?->name ?? 'Unknown'); ?></span>
                    <div class="flex-1 mx-3 bg-slate-200 rounded-full h-2">
                        <div class="bg-brand h-2 rounded-full" style="width: <?php echo e(($item->count / $trendsByType->sum('count')) * 100); ?>%"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-900"><?php echo e($item->count); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">30-Day Daily Trend</h3>
        <p class="text-xs text-slate-500">Trend data visualization will appear here</p>
        <div id="trendChart" style="height: 300px;" class="mt-4"></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\monitoring\incident-trends.blade.php ENDPATH**/ ?>