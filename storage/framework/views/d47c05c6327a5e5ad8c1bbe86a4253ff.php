

<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600 mt-1">Case creation, completion, and SLA alerts</p>
        </div>
        <?php if($unreadCount > 0): ?>
        <form action="<?php echo e(route('cos.notifications.mark-all-read')); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Mark All as Read
            </button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Notification Type</label>
                    <select id="type" name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">All Types</option>
                        <option value="CASE_CREATED" <?php echo e(request('type') === 'CASE_CREATED' ? 'selected' : ''); ?>>Case Created</option>
                        <option value="CASE_COMPLETED" <?php echo e(request('type') === 'CASE_COMPLETED' ? 'selected' : ''); ?>>Case Completed</option>
                        <option value="SLA_BREACH" <?php echo e(request('type') === 'SLA_BREACH' ? 'selected' : ''); ?>>SLA Breach</option>
                        <option value="DOCUMENT_UPLOAD" <?php echo e(request('type') === 'DOCUMENT_UPLOAD' ? 'selected' : ''); ?>>Document Upload</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">All</option>
                        <option value="unread" <?php echo e(request('status') === 'unread' ? 'selected' : ''); ?>>Unread</option>
                        <option value="read" <?php echo e(request('status') === 'read' ? 'selected' : ''); ?>>Read</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Unread Count Badge -->
    <?php if($unreadCount > 0): ?>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-blue-900">
            You have <strong><?php echo e($unreadCount); ?></strong> unread notification@if($unreadCount !== 1)s<?php endif; ?>
        </p>
    </div>
    @endif

    <!-- Notifications List -->
    <div class="space-y-3">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-lg shadow p-4 <?php echo e(!$notification->read_at ? 'border-l-4 border-l-blue-600' : ''); ?> hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <span class="inline-block px-3 py-1 <?php echo e($notification->notification_type === 'CASE_CREATED' ? 'bg-green-100 text-green-800' :
                            ($notification->notification_type === 'CASE_COMPLETED' ? 'bg-blue-100 text-blue-800' :
                            ($notification->notification_type === 'SLA_BREACH' ? 'bg-red-100 text-red-800' : 'bg-purple-100 text-purple-800'))); ?> text-xs font-medium rounded">
                            <?php echo e(str_replace('_', ' ', $notification->notification_type)); ?>

                        </span>
                        <?php if(!$notification->read_at): ?>
                        <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="text-gray-900 font-medium mt-2">
                        <?php echo e($notification->message ?? 'Notification'); ?>

                    </p>
                    
                    <?php if($notification->incident_id): ?>
                    <p class="text-sm text-gray-600 mt-1">
                        Case: <a href="<?php echo e(route('cos.case-flow.show', $notification->incident_id)); ?>" class="text-blue-600 hover:underline">
                            <?php echo e($notification->incident->incident_reference ?? 'Unknown'); ?>

                        </a>
                    </p>
                    <?php endif; ?>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        <?php echo e($notification->created_at->diffForHumans()); ?>

                    </p>
                </div>

                <?php if(!$notification->read_at): ?>
                <form action="<?php echo e(route('cos.notifications.mark-read', $notification->id)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded">
                        Mark as read
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <p>No notifications yet. Check back soon!</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($notifications->hasPages()): ?>
    <div class="mt-6">
        <?php echo e($notifications->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\notifications\index.blade.php ENDPATH**/ ?>