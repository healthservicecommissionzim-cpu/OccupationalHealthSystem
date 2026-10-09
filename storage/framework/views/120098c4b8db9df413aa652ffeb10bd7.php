

<?php $__env->startSection('page_title', 'Return-to-Work Plan'); ?>
<?php $__env->startSection('header_title', 'Return-to-Work Plan'); ?>
<?php $__env->startSection('header_subtitle', 'Create or update RTW plan'); ?>

<?php $__env->startSection('dashboard_content'); ?>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-lg font-bold text-navy"><?php echo e($incident->reference_number); ?></h2>
                <p class="text-sm text-slate-600 mt-1"><?php echo e($incident->first_name); ?> <?php echo e($incident->surname); ?></p>
                <p class="text-sm text-slate-500"><?php echo e($incident->facility?->name); ?></p>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-slate-600">Incident Date</p>
                <p class="text-lg font-semibold text-navy"><?php echo e($incident->incident_date->format('M d, Y')); ?></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="<?php echo e(route('rtw.update', $incident->id)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Section 1: RTW Timeline -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <h3 class="text-sm font-bold text-navy mb-4">RTW Timeline</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="rtw_start_date" 
                        value="<?php echo e(old('rtw_start_date', $incident->rtw_start_date?->format('Y-m-d'))); ?>"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                        required>
                    <?php $__errorArgs = ['rtw_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        End Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="rtw_end_date"
                        value="<?php echo e(old('rtw_end_date', $incident->rtw_end_date?->format('Y-m-d'))); ?>"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                        required>
                    <?php $__errorArgs = ['rtw_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="rtw_status" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                        required>
                        <option value="">Select Status</option>
                        <option value="not_started" <?php echo e(old('rtw_status', $incident->rtw_status) === 'not_started' ? 'selected' : ''); ?>>Not Started</option>
                        <option value="active" <?php echo e(old('rtw_status', $incident->rtw_status) === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="on_track" <?php echo e(old('rtw_status', $incident->rtw_status) === 'on_track' ? 'selected' : ''); ?>>On Track</option>
                        <option value="requires_intervention" <?php echo e(old('rtw_status', $incident->rtw_status) === 'requires_intervention' ? 'selected' : ''); ?>>Requires Intervention</option>
                        <option value="completed" <?php echo e(old('rtw_status', $incident->rtw_status) === 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="terminated" <?php echo e(old('rtw_status', $incident->rtw_status) === 'terminated' ? 'selected' : ''); ?>>Terminated</option>
                    </select>
                    <?php $__errorArgs = ['rtw_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Section 2: Medical Restrictions -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-navy">Medical Restrictions <span class="text-red-500">*</span></h3>
                <button type="button" onclick="addRestriction()" class="text-xs bg-brand text-white px-3 py-1 rounded hover:bg-brand-dark transition">
                    + Add Restriction
                </button>
            </div>
            <div id="restrictions-container" class="space-y-2">
                <?php
                    $restrictions = old('rtw_medical_restrictions', $incident->getMedicalRestrictions());
                    $hasRestrictions = !empty($restrictions) && (is_array($restrictions) && count($restrictions) > 0);
                ?>
                <?php if($hasRestrictions): ?>
                    <?php $__currentLoopData = $restrictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $restriction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex gap-2">
                            <input type="text" name="rtw_medical_restrictions[]" 
                                value="<?php echo e($restriction); ?>"
                                placeholder="e.g., Cannot lift more than 5kg"
                                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                                required>
                            <button type="button" onclick="this.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="flex gap-2">
                        <input type="text" name="rtw_medical_restrictions[]" 
                            placeholder="e.g., Cannot lift more than 5kg"
                            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                            required>
                        <button type="button" onclick="this.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <?php $__errorArgs = ['rtw_medical_restrictions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-xs mt-2"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Section 3: Modified Duties -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-navy">Modified Duties <span class="text-red-500">*</span></h3>
                <button type="button" onclick="addDuty()" class="text-xs bg-brand text-white px-3 py-1 rounded hover:bg-brand-dark transition">
                    + Add Duty
                </button>
            </div>
            <div id="duties-container" class="space-y-2">
                <?php
                    $duties = old('rtw_modified_duties', $incident->getModifiedDuties());
                    $hasDuties = !empty($duties) && (is_array($duties) && count($duties) > 0);
                ?>
                <?php if($hasDuties): ?>
                    <?php $__currentLoopData = $duties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $duty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex gap-2">
                            <input type="text" name="rtw_modified_duties[]" 
                                value="<?php echo e($duty); ?>"
                                placeholder="e.g., Administrative data entry"
                                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                                required>
                            <button type="button" onclick="this.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="flex gap-2">
                        <input type="text" name="rtw_modified_duties[]" 
                            placeholder="e.g., Administrative data entry"
                            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                            required>
                        <button type="button" onclick="this.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <?php $__errorArgs = ['rtw_modified_duties'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-xs mt-2"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Section 4: Work Schedule -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-navy">Work Schedule Progression <span class="text-red-500">*</span></h3>
                <button type="button" onclick="addSchedule()" class="text-xs bg-brand text-white px-3 py-1 rounded hover:bg-brand-dark transition">
                    + Add Week
                </button>
            </div>
            <div id="schedule-container" class="space-y-3">
                <?php
                    $schedule = old('rtw_schedule', $incident->getWorkSchedule());
                    $hasSchedule = !empty($schedule) && is_array($schedule) && count($schedule) > 0;
                ?>
                <?php if($hasSchedule): ?>
                    <?php $__currentLoopData = $schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period => $hours): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="rtw_schedule[period][]" 
                                value="<?php echo e($period); ?>"
                                placeholder="e.g., Week 1"
                                class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                                required>
                            <div class="flex gap-2">
                                <input type="text" name="rtw_schedule[hours][]" 
                                    value="<?php echo e($hours); ?>"
                                    placeholder="e.g., 4 hours per day"
                                    class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                                    required>
                                <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="rtw_schedule[period][]" 
                            placeholder="e.g., Week 1"
                            class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                            required>
                        <div class="flex gap-2">
                            <input type="text" name="rtw_schedule[hours][]" 
                                placeholder="e.g., 4 hours per day"
                                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                                required>
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php $__errorArgs = ['rtw_schedule'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-xs mt-2"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Section 5: Accommodations -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <label class="block text-sm font-bold text-navy mb-2">
                Accommodations <span class="text-red-500">*</span>
            </label>
            <textarea name="rtw_accommodations" 
                placeholder="e.g., Ergonomic chair, sit-stand desk, proximity parking..."
                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                rows="3"
                required><?php echo e(old('rtw_accommodations', $incident->rtw_accommodations)); ?></textarea>
            <?php $__errorArgs = ['rtw_accommodations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Section 6: Review Tracking -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <h3 class="text-sm font-bold text-navy mb-4">Review Tracking</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Last Review Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Last Review Date
                    </label>
                    <input type="date" name="rtw_last_review_date" 
                        value="<?php echo e(old('rtw_last_review_date', $incident->rtw_last_review_date?->format('Y-m-d'))); ?>"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent">
                    <?php $__errorArgs = ['rtw_last_review_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Next Review Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Next Review Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="rtw_next_review_date" 
                        value="<?php echo e(old('rtw_next_review_date', $incident->rtw_next_review_date?->format('Y-m-d'))); ?>"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                        required>
                    <?php $__errorArgs = ['rtw_next_review_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Section 7: Documentation Status -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <h3 class="text-sm font-bold text-navy mb-4">Documentation Status</h3>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="rtw_doctor_clearance_received" value="1" 
                        <?php echo e(old('rtw_doctor_clearance_received', $incident->rtw_doctor_clearance_received) ? 'checked' : ''); ?>

                        class="w-4 h-4 border-slate-300 rounded focus:ring-brand">
                    <span class="text-sm font-medium text-slate-700">Doctor clearance received</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="rtw_plan_signed_off" value="1"
                        <?php echo e(old('rtw_plan_signed_off', $incident->rtw_plan_signed_off) ? 'checked' : ''); ?>

                        class="w-4 h-4 border-slate-300 rounded focus:ring-brand">
                    <span class="text-sm font-medium text-slate-700">Plan signed off by all stakeholders</span>
                </label>
            </div>
        </div>

        <!-- Section 8: Review Notes -->
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <label class="block text-sm font-bold text-navy mb-2">
                Review Notes
            </label>
            <textarea name="rtw_review_notes" 
                placeholder="Additional notes about progress, concerns, or recommendations..."
                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                rows="3"><?php echo e(old('rtw_review_notes', $incident->rtw_review_notes)); ?></textarea>
            <?php $__errorArgs = ['rtw_review_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark transition font-medium">
                Save RTW Plan
            </button>
            <a href="javascript:history.back()" class="px-4 py-2 bg-slate-200 text-slate-800 rounded-lg hover:bg-slate-300 transition font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function addRestriction() {
    const container = document.getElementById('restrictions-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="rtw_medical_restrictions[]" 
            placeholder="e.g., Cannot lift more than 5kg"
            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
            required>
        <button type="button" onclick="this.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
            <i class="fa-solid fa-trash text-sm"></i>
        </button>
    `;
    container.appendChild(div);
}

function addDuty() {
    const container = document.getElementById('duties-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="rtw_modified_duties[]" 
            placeholder="e.g., Administrative data entry"
            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
            required>
        <button type="button" onclick="this.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
            <i class="fa-solid fa-trash text-sm"></i>
        </button>
    `;
    container.appendChild(div);
}

function addSchedule() {
    const container = document.getElementById('schedule-container');
    const div = document.createElement('div');
    div.className = 'grid grid-cols-2 gap-2';
    div.innerHTML = `
        <input type="text" name="rtw_schedule[period][]" 
            placeholder="e.g., Week 1"
            class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
            required>
        <div class="flex gap-2">
            <input type="text" name="rtw_schedule[hours][]" 
                placeholder="e.g., 4 hours per day"
                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent text-sm"
                required>
            <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded">
                <i class="fa-solid fa-trash text-sm"></i>
            </button>
        </div>
    `;
    container.appendChild(div);
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\hr\rtw\edit.blade.php ENDPATH**/ ?>