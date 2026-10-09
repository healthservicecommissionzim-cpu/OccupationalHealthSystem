

<?php $__env->startSection('cos-content'); ?>
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Compliance Checklist</h2>
    <p class="text-gray-600 mb-6">Case: <?php echo e($case->reference_number); ?> - <?php echo e($case->first_name); ?> <?php echo e($case->surname); ?></p>

    <form method="POST" action="<?php echo e(route('cos.compliance.save', $case->id)); ?>">
        <?php echo csrf_field(); ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $evaluatedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="compliance-item <?php echo e($item['compliant'] ? 'compliant' : 'non-compliant'); ?>">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900"><?php echo e($item['label']); ?></h4>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e($item['evidence']); ?></p>
                    </div>
                    <div class="ml-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="checklist_items[]" value='{"item":"<?php echo e($item['item']); ?>","status":"NON_COMPLIANT"}'>
                            <input type="checkbox" name="checklist_items[]" 
                                   value='{"item":"<?php echo e($item['item']); ?>","status":"COMPLIANT"}'
                                   <?php echo e($item['compliant'] ? 'checked' : ''); ?> class="rounded">
                            <span class="text-sm"><?php echo e($item['compliant'] ? 'Compliant' : 'Non-Compliant'); ?></span>
                        </label>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                Save Checklist
            </button>
            <a href="<?php echo e(route('cos.index')); ?>" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                Cancel
            </a>
        </div>
    </form>

    <?php if($latestCheck): ?>
        <div class="mt-8 pt-6 border-t">
            <h3 class="font-semibold text-gray-900 mb-3">Latest Result</h3>
            <div class="p-4 bg-blue-50 rounded">
                <p class="text-sm">
                    <strong>Status:</strong> 
                    <span class="px-2 py-1 rounded <?php echo e($latestCheck->overall_status === 'COMPLIANT' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                        <?php echo e($latestCheck->overall_status); ?>

                    </span>
                </p>
                <p class="text-sm mt-2">Checked: <?php echo e($latestCheck->created_at->format('d M Y H:i')); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\compliance\show.blade.php ENDPATH**/ ?>