

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Approval Dashboard</h1>
        <p class="text-gray-600 mt-2">View user approval statistics and metrics</p>
    </div>

    <!-- Overall Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending Users Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">Pending Users</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2"><?php echo e($statistics['pending_count']); ?></p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('user-approval.index')); ?>" class="inline-block mt-4 text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                View Pending →
            </a>
        </div>

        <!-- Approved Users Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">Approved Users</p>
                    <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e($statistics['approved_count']); ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected Users Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">Rejected Users</p>
                    <p class="text-3xl font-bold text-red-600 mt-2"><?php echo e($statistics['rejected_count']); ?></p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase">Total Users</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($statistics['total_count']); ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Filter -->
    <?php if($organizations->count() > 0): ?>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter by Organization</h2>
        <form method="GET" action="<?php echo e(route('user-approval.dashboard')); ?>" class="flex gap-4">
            <select name="organization_id" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Organization --</option>
                <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($org->id); ?>" <?php if(request('organization_id') == $org->id): echo 'selected'; endif; ?>>
                        <?php echo e($org->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Filter
            </button>
            <?php if(request('organization_id')): ?>
                <a href="<?php echo e(route('user-approval.dashboard')); ?>" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>

    <!-- Organization-specific Statistics -->
    <?php if($organizationStats): ?>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Organization Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm font-semibold">Pending</p>
                <p class="text-2xl font-bold text-yellow-600 mt-2"><?php echo e($organizationStats['pending_count']); ?></p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm font-semibold">Approved</p>
                <p class="text-2xl font-bold text-green-600 mt-2"><?php echo e($organizationStats['approved_count']); ?></p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm font-semibold">Rejected</p>
                <p class="text-2xl font-bold text-red-600 mt-2"><?php echo e($organizationStats['rejected_count']); ?></p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm font-semibold">Total</p>
                <p class="text-2xl font-bold text-blue-600 mt-2"><?php echo e($organizationStats['total_count']); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Approvals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Approvals</h2>
            <?php if($recentApprovals->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900"><?php echo e($user->name); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($user->email); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">Approved</p>
                                <p class="text-xs text-gray-500"><?php echo e($user->approved_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No recent approvals</p>
            <?php endif; ?>
        </div>

        <!-- Recent Rejections -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Rejections</h2>
            <?php if($recentRejections->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentRejections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rejection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900"><?php echo e($rejection->user->name); ?></p>
                                <p class="text-sm text-gray-600 truncate" title="<?php echo e($rejection->reason); ?>"><?php echo e(Str::limit($rejection->reason, 30)); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-red-600">Rejected</p>
                                <p class="text-xs text-gray-500"><?php echo e($rejection->created_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No recent rejections</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?php echo e(route('user-approval.index')); ?>" class="p-4 border border-gray-300 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition">
                <p class="font-semibold text-gray-900">Review Pending Users</p>
                <p class="text-sm text-gray-600 mt-1">Approve or reject pending registrations</p>
            </a>
            <a href="<?php echo e(route('user-approval.index')); ?>" class="p-4 border border-gray-300 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition">
                <p class="font-semibold text-gray-900">All Users</p>
                <p class="text-sm text-gray-600 mt-1">View all system users</p>
            </a>
            <a href="<?php echo e(route('user-approval.audit_logs')); ?>" class="p-4 border border-gray-300 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition">
                <p class="font-semibold text-gray-900">Audit Logs</p>
                <p class="text-sm text-gray-600 mt-1">View role assignments and rejections</p>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\super-admin\dashboard\index.blade.php ENDPATH**/ ?>