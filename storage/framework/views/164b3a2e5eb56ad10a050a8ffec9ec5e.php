

<?php $__env->startSection('page_title', 'Cases by Facility · HSC'); ?>

<?php $__env->startSection('header_title', 'Cases by Facility'); ?>
<?php $__env->startSection('header_subtitle', 'View incident distribution across all health facilities'); ?>

<?php $__env->startSection('sidebar_content'); ?>
<?php echo $__env->make('hsc.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('dashboard_content'); ?>
<div class="mb-6">
    <h2 class="text-[14px] font-bold text-navy flex items-center gap-2">
        <i class="fa-solid fa-hospital text-brand"></i> CASES BY FACILITY
    </h2>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <?php $__currentLoopData = $facilityStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg border border-slate-200 p-4 hover:shadow-md transition-all">
            <p class="text-xs text-slate-600 font-semibold truncate"><?php echo e($facility->name); ?></p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($facility->incidents_count); ?></p>
            <p class="text-xs text-slate-500 mt-1">incidents</p>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="space-y-4">
    <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $incidents = $facility->incidents;
        ?>
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900"><?php echo e($facility->name); ?></h3>
                    <p class="text-xs text-slate-500 mt-0.5"><?php echo e($facility->district?->name ?? 'N/A'); ?> District</p>
                </div>
                <span class="px-3 py-1 bg-brand bg-opacity-10 text-brand text-xs font-bold rounded-full"><?php echo e($incidents->count()); ?> cases</span>
            </div>
            
            <?php if($incidents->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Reference</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Employee</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Type</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Date</th>
                                <th class="text-left py-2 px-3 text-xs font-semibold text-slate-600">Status</th>
                                <th class="text-center py-2 px-3 text-xs font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $incidents->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-slate-200 hover:bg-slate-50">
                                    <td class="py-2 px-3 font-semibold text-brand text-xs"><?php echo e($incident->reference_number); ?></td>
                                    <td class="py-2 px-3 text-xs"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                                    <td class="py-2 px-3 text-xs"><?php echo e($incident->incidentType?->name ?? 'Unknown'); ?></td>
                                    <td class="py-2 px-3 text-xs text-slate-500"><?php echo e($incident->created_at->format('M d, Y')); ?></td>
                                    <td class="py-2 px-3 text-xs">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs"><?php echo e(str_replace('_', ' ', $incident->status)); ?></span>
                                    </td>
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
                <?php if($incidents->count() > 5): ?>
                    <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 text-center text-xs text-slate-500">
                        Showing 5 of <?php echo e($incidents->count()); ?> cases
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="py-8 text-center text-slate-500 text-sm">
                    <p>No cases for this facility</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hsc\cases\by-facility.blade.php ENDPATH**/ ?>