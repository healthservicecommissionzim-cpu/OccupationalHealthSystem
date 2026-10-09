

<?php $__env->startSection('title', 'Announcements'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="text-gray-600 mt-1">Policy updates and procedural communications</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('broadcast_announcement')): ?>
        <a href="<?php echo e(route('cos.announcements.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            New Announcement
        </a>
        <?php endif; ?>
    </div>

    <!-- Active Banner Announcement -->
    <?php if($activeBannerAnnouncement): ?>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-900"><?php echo e($activeBannerAnnouncement->title); ?></h3>
                <p class="text-yellow-800 text-sm mt-1"><?php echo e($activeBannerAnnouncement->content); ?></p>
                <p class="text-xs text-yellow-700 mt-2">
                    By: <?php echo e($activeBannerAnnouncement->author->name); ?> • 
                    <?php echo e($activeBannerAnnouncement->published_at->diffForHumans()); ?>

                </p>
            </div>
            <button onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="text-yellow-600 hover:text-yellow-900">
                ✕
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-gray-200">
        <a href="?tab=active" class="px-4 py-2 border-b-2 <?php echo e(request('tab', 'active') === 'active' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900'); ?> font-medium">
            Active
        </a>
        <a href="?tab=archive" class="px-4 py-2 border-b-2 <?php echo e(request('tab') === 'archive' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900'); ?> font-medium">
            Archive
        </a>
    </div>

    <!-- Active Announcements -->
    <?php if(request('tab', 'active') === 'active'): ?>
    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($announcement->title); ?></h3>
                    <p class="text-gray-600 mt-2"><?php echo e($announcement->content); ?></p>
                    <div class="flex items-center gap-4 mt-4 text-sm text-gray-500">
                        <span>Target: <strong><?php echo e(str_replace('_', ' ', ucfirst($announcement->target_audience))); ?></strong></span>
                        <span>By: <strong><?php echo e($announcement->author->name); ?></strong></span>
                        <span>Published: <strong><?php echo e($announcement->published_at?->format('M d, Y H:i') ?? 'Pending'); ?></strong></span>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('broadcast_announcement')): ?>
                <div class="flex gap-2">
                    <a href="<?php echo e(route('cos.announcements.edit', $announcement->id)); ?>" class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded">
                        Edit
                    </a>
                    <form action="<?php echo e(route('cos.announcements.destroy', $announcement->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this announcement?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded">
                            Delete
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <p>No active announcements.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <!-- Archive -->
    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $archivedAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-lg shadow p-6 opacity-75">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($announcement->title); ?></h3>
                    <p class="text-gray-600 mt-2"><?php echo e($announcement->content); ?></p>
                    <div class="flex items-center gap-4 mt-4 text-sm text-gray-500">
                        <span>Target: <strong><?php echo e(str_replace('_', ' ', ucfirst($announcement->target_audience))); ?></strong></span>
                        <span>By: <strong><?php echo e($announcement->author->name); ?></strong></span>
                        <span>Published: <strong><?php echo e($announcement->published_at?->format('M d, Y H:i')); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <p>No archived announcements.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\announcements\index.blade.php ENDPATH**/ ?>