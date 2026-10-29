<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['incident']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['incident']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $auditTrail = \App\Models\AuditLog::where('incident_id', $incident->id)
        ->whereIn('action', ['leave_information_saved', 'milestone_flag_applied', 'medical_board_decision'])
        ->orderBy('created_at', 'desc')
        ->get();
?>

<?php if($auditTrail->count() > 0): ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">
            <i class="fas fa-history mr-2 text-slate-600"></i>
            Audit Trail
        </h2>

        <div class="space-y-4">
            <?php $__currentLoopData = $auditTrail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border-l-4 border-blue-600 pl-4 py-3 bg-slate-50 rounded-r">
                    <!-- Action Header -->
                    <div class="flex items-start justify-between mb-2">
                        <p class="font-semibold text-slate-800">
                            <?php switch($entry->action):
                                case ('leave_information_saved'): ?>
                                    <i class="fas fa-file-alt text-green-600 mr-2"></i>Leave Information Saved
                                    <?php break; ?>
                                <?php case ('milestone_flag_applied'): ?>
                                    <i class="fas fa-flag text-amber-600 mr-2"></i>Milestone Flag Applied
                                    <?php break; ?>
                                <?php case ('medical_board_decision'): ?>
                                    <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>Medical Board Decision
                                    <?php break; ?>
                                <?php default: ?>
                                    <?php echo e(ucwords(str_replace('_', ' ', $entry->action))); ?>

                            <?php endswitch; ?>
                        </p>
                        <span class="text-xs text-slate-600">
                            <?php echo e($entry->created_at->format('M d, Y H:i')); ?>

                        </span>
                    </div>

                    <!-- User Info -->
                    <p class="text-sm text-slate-600 mb-3">
                        By: <span class="font-medium"><?php echo e($entry->changed_by_user_id ? $entry->changedByUser?->name : 'System (Automated)'); ?></span>
                    </p>

                    <!-- Changed Fields -->
                    <?php if($entry->old_values || $entry->new_values): ?>
                        <div class="space-y-2 mt-3 text-sm">
                            <?php
                                $oldValues = json_decode($entry->old_values, true) ?? [];
                                $newValues = json_decode($entry->new_values, true) ?? [];
                            ?>

                            <?php $__currentLoopData = $newValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $newValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $oldValue = $oldValues[$field] ?? null;
                                    $fieldLabel = ucwords(str_replace('_', ' ', $field));
                                ?>

                                <?php if($oldValue !== $newValue): ?>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="text-slate-600"><?php echo e($fieldLabel); ?>:</div>
                                        <div class="bg-red-50 px-2 py-1 rounded text-red-800">
                                            <?php echo e($oldValue ?? '(empty)'); ?>

                                        </div>
                                        <div class="bg-green-50 px-2 py-1 rounded text-green-800">
                                            <?php echo e($newValue ?? '(empty)'); ?>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Metadata (Reason/Decision Details) -->
                    <?php if($entry->metadata): ?>
                        <?php
                            $metadata = json_decode($entry->metadata, true) ?? [];
                        ?>

                        <?php if(!empty($metadata)): ?>
                            <div class="mt-3 bg-blue-50 border border-blue-200 rounded p-3 text-sm">
                                <?php if(isset($metadata['decision'])): ?>
                                    <p class="text-slate-700">
                                        <span class="font-medium">Decision:</span>
                                        <?php echo e(ucwords(str_replace('_', ' ', $metadata['decision']))); ?>

                                    </p>
                                <?php endif; ?>

                                <?php if(isset($metadata['decision_reason'])): ?>
                                    <p class="text-slate-700 mt-1">
                                        <span class="font-medium">Reason:</span>
                                        <?php echo e($metadata['decision_reason']); ?>

                                    </p>
                                <?php endif; ?>

                                <?php if(isset($metadata['flag_reason'])): ?>
                                    <p class="text-slate-700">
                                        <span class="font-medium">Flag Reason:</span>
                                        <?php echo e(str_replace('_', ' ', $metadata['flag_reason'])); ?>

                                    </p>
                                <?php endif; ?>

                                <?php if(isset($metadata['decided_by'])): ?>
                                    <p class="text-slate-600 text-xs mt-1">
                                        Decided by: <?php echo e($metadata['decided_by']); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php else: ?>
    <div class="bg-slate-50 rounded-lg p-6 text-center">
        <i class="fas fa-info-circle text-slate-400 text-2xl mb-2 block"></i>
        <p class="text-slate-600">No leave-related audit history</p>
    </div>
<?php endif; ?>
<?php /**PATH D:\OccupationalHealth System\resources\views\components\audit-trail-display.blade.php ENDPATH**/ ?>