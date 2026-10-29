

<?php $__env->startSection('cos-content'); ?>
<div class="space-y-6">
    <!-- Case Header -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900"><?php echo e($case->reference_number); ?></h2>
                <p class="text-gray-600 mt-1"><?php echo e($case->first_name); ?> <?php echo e($case->surname); ?></p>
                <div class="flex gap-4 mt-3">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                        Stage: <?php echo e($case->current_stage); ?>

                    </span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-sm">
                        Status: <?php echo e($case->status); ?>

                    </span>
                    <?php if($isOverdue): ?>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded text-sm">
                            <i class="fas fa-exclamation-circle"></i> Overdue
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Facility: <?php echo e($case->facility?->name); ?></p>
                <p class="text-sm text-gray-600 mt-1">Created: <?php echo e($case->created_at->format('d M Y')); ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Timeline Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Timeline Visualization -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Case Timeline</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="timeline-item <?php echo e($event['stage'] === $case->current_stage ? 'current' : ''); ?>">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?php echo e($event['event']); ?></h4>
                                    <p class="text-sm text-gray-600 mt-1"><?php echo e($event['description']); ?></p>
                                    <p class="text-xs text-gray-500 mt-1">By: <?php echo e($event['actor']); ?></p>
                                </div>
                                <div class="text-right text-xs text-gray-500">
                                    <?php echo e($event['timestamp']->format('d M Y H:i')); ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Monitoring Notes -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Monitoring Notes</h3>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add_monitoring_note')): ?>
                    <form method="POST" action="<?php echo e(route('cos.case.add_note', $case->id)); ?>" class="mb-6">
                        <?php echo csrf_field(); ?>
                        <textarea name="content" placeholder="Add a governance observation..." required 
                                  class="w-full px-3 py-2 border rounded <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  rows="3"></textarea>
                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Add Note
                        </button>
                    </form>
                <?php endif; ?>

                <?php if($case->monitoringNotes->isNotEmpty()): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $case->monitoringNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                                <p class="text-sm text-gray-900"><?php echo e($note->content); ?></p>
                                <p class="text-xs text-gray-600 mt-2">
                                    By <?php echo e($note->author->name); ?> on <?php echo e($note->created_at->format('d M Y H:i')); ?>

                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-sm">No monitoring notes yet</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions Column -->
        <div class="space-y-4">
            <!-- Current Holder -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="font-semibold text-gray-900 mb-3">Current Holder</h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <p class="font-semibold"><?php echo e($currentHolder['holder_name']); ?></p>
                    </div>
                    <div>
                        <span class="text-gray-600">Designation:</span>
                        <p><?php echo e($currentHolder['holder_designation']); ?></p>
                    </div>
                    <div>
                        <span class="text-gray-600">Duration:</span>
                        <p class="font-semibold"><?php echo e($currentHolder['duration_at_stage_days']); ?> days</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white p-4 rounded-lg shadow space-y-2">
                <h4 class="font-semibold text-gray-900 mb-3">Actions</h4>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_reminder')): ?>
                    <form method="POST" action="<?php echo e(route('cos.case.send_nudge', $case->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 text-sm">
                            <i class="fas fa-bell"></i> Send Nudge
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('escalate_case')): ?>
                    <button onclick="document.getElementById('escalateModal').style.display='block'" 
                            class="w-full bg-purple-600 text-white px-3 py-2 rounded hover:bg-purple-700 text-sm">
                        <i class="fas fa-arrow-up"></i> Escalate
                    </button>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('run_compliance_checklist')): ?>
                    <a href="<?php echo e(route('cos.compliance.show', $case->id)); ?>" 
                       class="block text-center bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 text-sm">
                        <i class="fas fa-clipboard-list"></i> Run Checklist
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_audit_log')): ?>
                    <a href="<?php echo e(route('cos.audit.show', $case->id)); ?>" 
                       class="block text-center bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-sm">
                        <i class="fas fa-history"></i> Audit Log
                    </a>
                <?php endif; ?>
            </div>

            <!-- Case Information -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="font-semibold text-gray-900 mb-3">Case Details</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-semibold"><?php echo e($case->case_type ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span><?php echo e($case->created_at->format('d M Y')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span><?php echo e($case->updated_at->format('d M Y')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Escalate Modal -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('escalate_case')): ?>
        <div id="escalateModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Escalate Case</h3>
                <form method="POST" action="<?php echo e(route('cos.case.escalate', $case->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Next Stage</label>
                            <select name="next_stage" required class="w-full px-3 py-2 border rounded">
                                <option value="">Select stage...</option>
                                <option value="pmd_review">PMD Review</option>
                                <option value="hsc_resolution">HSC Resolution</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Holder User</label>
                            <select name="next_holder_id" required class="w-full px-3 py-2 border rounded">
                                <option value="">Select user...</option>
                                <!-- Users would be populated from database -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <textarea name="reason" class="w-full px-3 py-2 border rounded" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Escalate
                        </button>
                        <button type="button" onclick="document.getElementById('escalateModal').style.display='none'" 
                                class="flex-1 bg-gray-300 text-gray-900 px-4 py-2 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cos-dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\dashboards\cos\case-flow\show.blade.php ENDPATH**/ ?>