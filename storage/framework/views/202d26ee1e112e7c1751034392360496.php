

<?php $__env->startSection('title', 'Audit Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Audit Report</h1>
            <p class="text-gray-600 mt-1">Case audit statistics and trends</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_data')): ?>
        <a href="<?php echo e(route('cos.audit-log.report-export')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Export Report
        </a>
        <?php endif; ?>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e(request('start_date', now()->subMonths(3)->format('Y-m-d'))); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e(request('end_date', now()->format('Y-m-d'))); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="actor_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select id="actor_id" name="actor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">All Users</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e(request('actor_id') == $user->id ? 'selected' : ''); ?>>
                            <?php echo e($user->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                        Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Report Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Total Actions</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($statistics['total_actions'] ?? 0); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Escalations</p>
            <p class="text-3xl font-bold text-orange-600 mt-2"><?php echo e($statistics['escalations'] ?? 0); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Nudges Sent</p>
            <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($statistics['nudges_sent'] ?? 0); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium">Notes Added</p>
            <p class="text-3xl font-bold text-purple-600 mt-2"><?php echo e($statistics['notes_added'] ?? 0); ?></p>
        </div>
    </div>

    <!-- Actions by Type -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions by Type</h3>
        <div class="space-y-2">
            <?php $__currentLoopData = $actionsByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between">
                <span class="text-gray-700"><?php echo e(ucwords(str_replace('_', ' ', $action))); ?></span>
                <div class="flex items-center gap-2">
                    <div class="w-24 h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-blue-600 rounded" style="width: <?php echo e(($count / ($statistics['total_actions'] ?: 1) * 100)); ?>%"></div>
                    </div>
                    <span class="text-gray-900 font-medium w-12 text-right"><?php echo e($count); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Top Users -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Users by Activity</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700"><?php echo e($user); ?></span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-900 text-sm font-medium rounded"><?php echo e($count); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-sm">No data available</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cases with Most Activity</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700"><?php echo e($case); ?></span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-900 text-sm font-medium rounded"><?php echo e($count); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-sm">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\audit\report.blade.php ENDPATH**/ ?>