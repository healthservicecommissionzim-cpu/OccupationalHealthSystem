

<?php $__env->startSection('page_title', 'Performance Metrics · HSC'); ?>

<?php $__env->startSection('header_title', 'Performance Metrics'); ?>
<?php $__env->startSection('header_subtitle', 'System SLA compliance and performance indicators'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-gauge text-brand"></i> PERFORMANCE METRICS
    </h2>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Average Processing Times</h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-slate-600 mb-2">Avg. Supervisor Verification Time</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo e(round($metrics['avg_supervisor_verification_time'], 1)); ?></p>
                <p class="text-xs text-slate-500">days</p>
            </div>
            <div class="pt-4 border-t border-slate-200">
                <p class="text-xs text-slate-600 mb-2">Avg. Time to Resolution</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo e(round($metrics['avg_time_to_resolution'], 1)); ?></p>
                <p class="text-xs text-slate-500">days (Target: 30 days)</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">System Health Indicators</h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-slate-600 mb-2">Escalations This Month</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo e($metrics['total_escalations_this_month']); ?></p>
                <p class="text-xs text-slate-500">cases escalated to clinical review</p>
            </div>
            <div class="pt-4 border-t border-slate-200">
                <p class="text-xs text-slate-600 mb-2">Overall Compliance Rate</p>
                <p class="text-3xl font-bold text-emerald-600"><?php echo e($metrics['compliance_percentage']); ?>%</p>
                <p class="text-xs text-slate-500">of cases meet requirements</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\system\performance-metrics.blade.php ENDPATH**/ ?>