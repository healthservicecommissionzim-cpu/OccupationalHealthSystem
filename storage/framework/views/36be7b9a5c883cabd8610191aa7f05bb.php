

<?php $__env->startSection('page_title', 'Compliance Dashboard · HSC'); ?>

<?php $__env->startSection('header_title', 'Compliance Dashboard'); ?>
<?php $__env->startSection('header_subtitle', 'Real-time overview of system compliance metrics and facility performance'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-clipboard-check text-brand"></i> COMPLIANCE OVERVIEW
    </h2>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Total Cases</p>
        <p class="text-3xl font-bold text-slate-900 mt-1"><?php echo e($totalCases); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Compliant Cases</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1"><?php echo e($compliantCases); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Non-Compliant Cases</p>
        <p class="text-3xl font-bold text-red-600 mt-1"><?php echo e($nonCompliantCases); ?></p>
    </div>
    <div class="bg-white rounded-lg border border-slate-200 p-4">
        <p class="text-xs text-slate-600 font-semibold">Compliance Rate</p>
        <p class="text-3xl font-bold text-blue-600 mt-1"><?php echo e($complianceRate); ?>%</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Compliance Rate Progress</h3>
        <div class="w-full bg-slate-200 rounded-full h-3">
            <div class="bg-emerald-600 h-3 rounded-full transition-all" style="width: <?php echo e($complianceRate); ?>%"></div>
        </div>
        <p class="text-xs text-slate-500 mt-2"><?php echo e($complianceRate); ?>% of cases are fully compliant</p>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Compliance Breakdown</h3>
        <div class="space-y-2">
            <div class="flex justify-between text-xs">
                <span class="text-slate-600">Compliant</span>
                <span class="font-bold text-emerald-600"><?php echo e($compliantCases); ?> cases</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-slate-600">Non-Compliant</span>
                <span class="font-bold text-red-600"><?php echo e($nonCompliantCases); ?> cases</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">Facility Compliance Scores</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Facility</th>
                    <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Total Cases</th>
                    <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Compliant</th>
                    <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Compliance %</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $facilityCompliance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $total = $facility->incidents_count;
                        $compliant = $facility->compliant_incidents;
                        $percent = $total > 0 ? round(($compliant / $total) * 100, 1) : 0;
                    ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="py-2 px-3 text-xs font-semibold"><?php echo e($facility->name); ?></td>
                        <td class="py-2 px-3 text-center text-xs font-bold"><?php echo e($total); ?></td>
                        <td class="py-2 px-3 text-center text-xs font-bold text-emerald-600"><?php echo e($compliant); ?></td>
                        <td class="py-2 px-3 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-bold <?php echo e($percent >= 80 ? 'bg-emerald-100 text-emerald-700' : ($percent >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')); ?>">
                                <?php echo e($percent); ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\monitoring\compliance-dashboard.blade.php ENDPATH**/ ?>