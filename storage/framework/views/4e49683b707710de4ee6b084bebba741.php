

<?php $__env->startSection('title', 'Audit Log - ' . $case->incident_reference); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Audit Log</h1>
            <p class="text-gray-600 mt-1">Case: <?php echo e($case->incident_reference); ?></p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_data')): ?>
        <a href="<?php echo e(route('cos.audit-log.export', $case->id)); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Export to CSV
        </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="action_type" class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
                    <select id="action_type" name="action_type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">All Actions</option>
                        <option value="created" <?php echo e(request('action_type') === 'created' ? 'selected' : ''); ?>>Created</option>
                        <option value="escalated" <?php echo e(request('action_type') === 'escalated' ? 'selected' : ''); ?>>Escalated</option>
                        <option value="monitoring_note_added" <?php echo e(request('action_type') === 'monitoring_note_added' ? 'selected' : ''); ?>>Note Added</option>
                        <option value="nudge_sent" <?php echo e(request('action_type') === 'nudge_sent' ? 'selected' : ''); ?>>Nudge Sent</option>
                        <option value="checklist_run" <?php echo e(request('action_type') === 'checklist_run' ? 'selected' : ''); ?>>Compliance Check</option>
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                    Filter
                </button>
                <a href="<?php echo e(route('cos.audit-log.show', $case->id)); ?>" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Entries -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php if($auditEntries->count() > 0): ?>
            <div class="space-y-1 divide-y">
                <?php $__currentLoopData = $auditEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                    <?php echo e($entry->action_type); ?>

                                </span>
                                <span class="text-sm font-medium text-gray-900">
                                    <?php echo e($entry->actor->name ?? 'System'); ?>

                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php echo e($entry->created_at->format('M d, Y H:i:s')); ?>

                            </p>
                            
                            <?php if($entry->action_data): ?>
                            <div class="mt-2 text-sm text-gray-600">
                                <details class="cursor-pointer">
                                    <summary class="font-medium hover:text-gray-900">Details</summary>
                                    <pre class="mt-2 bg-gray-100 p-2 rounded text-xs overflow-auto"><?php echo e(json_encode($entry->action_data, JSON_PRETTY_PRINT)); ?></pre>
                                </details>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t bg-gray-50">
                <?php echo e($auditEntries->links()); ?>

            </div>
        <?php else: ?>
            <div class="p-8 text-center text-gray-500">
                <p>No audit entries found for this case.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\audit\show.blade.php ENDPATH**/ ?>