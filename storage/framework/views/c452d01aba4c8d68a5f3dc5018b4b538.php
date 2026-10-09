

<?php $__env->startSection('title', 'Medical Board Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-stethoscope text-red-600 mr-3"></i>
                    Medical Board Dashboard
                </h1>
                <p class="text-slate-600 mt-2">Review and decide on flagged leave cases</p>
            </div>
            <a href="<?php echo e(route('dashboards.medical-board')); ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-home mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-amber-50 rounded-lg shadow p-6 border-l-4 border-amber-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">90-Day Review</p>
                    <p class="text-2xl font-bold text-amber-700 mt-2"><?php echo e($stats['pending_90_day']); ?></p>
                </div>
                <i class="fas fa-flag text-amber-600 text-3xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-red-50 rounded-lg shadow p-6 border-l-4 border-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Medical Discharge</p>
                    <p class="text-2xl font-bold text-red-700 mt-2"><?php echo e($stats['pending_discharge']); ?></p>
                </div>
                <i class="fas fa-alert-circle text-red-600 text-3xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-blue-50 rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Pending</p>
                    <p class="text-2xl font-bold text-blue-700 mt-2"><?php echo e($stats['total_pending']); ?></p>
                </div>
                <i class="fas fa-inbox text-blue-600 text-3xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="<?php echo e(route('medical-board.dashboard')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Reference or worker name..." 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="REFERRED_TO_MEDICAL_BOARD" <?php if(request('status') === 'REFERRED_TO_MEDICAL_BOARD'): echo 'selected'; endif; ?>>90-Day Review</option>
                        <option value="MEDICAL_DISCHARGE_ELIGIBLE" <?php if(request('status') === 'MEDICAL_DISCHARGE_ELIGIBLE'): echo 'selected'; endif; ?>>Medical Discharge Eligible</option>
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="<?php echo e(route('medical-board.dashboard')); ?>" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Cases Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Reference</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Worker</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Days Elapsed</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Milestone</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Flagged Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php $__empty_1 = true; $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $milestone = $incident->leaveMilestones->first();
                        $isDischargeEligible = $incident->status === 'MEDICAL_DISCHARGE_ELIGIBLE';
                        $rowClass = $isDischargeEligible ? 'bg-red-50' : 'bg-amber-50';
                    ?>
                    <tr class="<?php echo e($rowClass); ?> hover:bg-opacity-75 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-800"><?php echo e($incident->reference_number); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-700"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></td>
                        <td class="px-6 py-4 text-center text-sm font-semibold text-slate-800">
                            <?php echo e($incident->days_elapsed ?? 0); ?> days
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if($milestone): ?>
                                <?php if($milestone->milestone_type === '90_DAY'): ?>
                                    <span class="inline-block px-3 py-1 bg-amber-200 text-amber-900 text-xs font-semibold rounded-full">90-Day</span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 bg-red-200 text-red-900 text-xs font-semibold rounded-full">180-Day</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <?php echo e($milestone?->applied_at?->format('M d, Y H:i') ?? '—'); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <a href="<?php echo e(route('medical-board.review', $incident->id)); ?>" 
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-3 rounded text-xs transition-colors">
                                <i class="fas fa-eye mr-1"></i>Review
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-600">
                            <i class="fas fa-inbox text-3xl opacity-50 mb-3 block"></i>
                            No flagged cases pending review
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($cases->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($cases->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\medical-board\dashboard.blade.php ENDPATH**/ ?>