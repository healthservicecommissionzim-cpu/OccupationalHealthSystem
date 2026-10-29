

<?php $__env->startSection('page_title', 'Workflow Health · HSC'); ?>

<?php $__env->startSection('header_title', 'Workflow Health'); ?>
<?php $__env->startSection('header_subtitle', 'Monitor case processing efficiency through workflow stages'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-heartbeat text-brand"></i> WORKFLOW HEALTH MONITORING
    </h2>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
    <h3 class="text-sm font-semibold text-slate-900 mb-4">Cases by Workflow Stage</h3>
    <div class="space-y-4">
        <?php
            $total = collect($stageCounts)->sum();
        ?>
        <?php $__currentLoopData = $stageCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-slate-600 mb-1"><?php echo e(str_replace('_', ' ', $stage)); ?></p>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-brand h-2 rounded-full transition-all" style="width: <?php echo e($total > 0 ? ($count / $total) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <span class="ml-3 px-3 py-1 bg-brand bg-opacity-10 text-brand font-bold rounded-full text-sm min-w-[3rem] text-center"><?php echo e($count); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-6 pt-4 border-t border-slate-200">
        <p class="text-xs text-slate-600">
            <span class="font-bold text-brand"><?php echo e($total); ?></span> total cases in workflow
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\monitoring\workflow-health.blade.php ENDPATH**/ ?>