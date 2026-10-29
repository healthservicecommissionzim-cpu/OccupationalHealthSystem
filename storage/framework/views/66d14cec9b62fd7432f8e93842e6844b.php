

<?php $__env->startSection('title', isset($assignment) ? 'Edit Assignment' : 'Create Assignment'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-calendar-plus text-blue-600 mr-3"></i>
                    <?php echo e(isset($assignment) ? 'Edit Assignment' : 'Create Assignment'); ?>

                </h1>
                <p class="text-slate-600 mt-2">
                    <?php echo e(isset($assignment) ? 'Update facility assignment details' : 'Create a new facility assignment for staff'); ?>

                </p>
            </div>
            <a href="<?php echo e(route('system.roster.facility-assignments.index')); ?>" class="text-slate-600 hover:text-slate-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Assignments
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="<?php echo e(isset($assignment) ? route('system.roster.facility-assignments.update', $assignment->id) : route('system.roster.facility-assignments.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if(isset($assignment)): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>

            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <div class="font-semibold text-red-800 mb-2">Please fix the following errors:</div>
                            <ul class="list-disc list-inside space-y-1 text-red-700">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Overlap Error - Display Prominently -->
            <?php if(session('overlap_error')): ?>
                <div class="bg-orange-50 border-l-4 border-orange-500 px-4 py-4 rounded mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-orange-500 text-2xl mr-3"></i>
                        <div class="flex-1">
                            <div class="font-bold text-orange-900 text-lg mb-1">Assignment Overlap Detected</div>
                            <p class="text-orange-800"><?php echo e(session('overlap_error')); ?></p>
                            <p class="text-orange-700 text-sm mt-2">Please adjust the dates to avoid overlapping assignments at the same facility.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <!-- Staff Roster Selection -->
                <div>
                    <label for="staff_roster_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Staff Roster <span class="text-red-500">*</span>
                    </label>
                    <?php if(isset($assignment)): ?>
                        <input type="text" 
                            value="<?php echo e($assignment->staffRoster->user->name); ?> - <?php echo e($assignment->staffRoster->staff_type); ?> (<?php echo e($assignment->staffRoster->primaryFacility->name); ?>)" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" readonly>
                        <input type="hidden" name="staff_roster_id" value="<?php echo e($assignment->staff_roster_id); ?>">
                    <?php else: ?>
                        <select name="staff_roster_id" id="staff_roster_id" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['staff_roster_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                            <option value="">Select a staff roster...</option>
                            <?php $__currentLoopData = $rosters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($roster->id); ?>" <?php echo e(old('staff_roster_id') == $roster->id ? 'selected' : ''); ?>>
                                    <?php echo e($roster->user->name); ?> - <?php echo e(ucfirst($roster->staff_type)); ?> (<?php echo e($roster->primaryFacility->name); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php endif; ?>
                    <?php $__errorArgs = ['staff_roster_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-1">Select the staff member to assign to a facility</p>
                </div>

                <!-- Facility Selection -->
                <div>
                    <label for="facility_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Assignment Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="facility_id" id="facility_id" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required>
                        <option value="">Select facility...</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->id); ?>" <?php echo e((old('facility_id', $assignment->facility_id ?? '') == $facility->id) ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-1">The facility where the staff will be assigned</p>
                </div>

                <!-- Assignment Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="assignment_start_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Assignment Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="assignment_start_date" id="assignment_start_date" 
                            value="<?php echo e(old('assignment_start_date', isset($assignment) ? $assignment->assignment_start_date->format('Y-m-d') : '')); ?>"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['assignment_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <?php $__errorArgs = ['assignment_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-slate-500 mt-1">Cannot be more than 2 years in the future</p>
                    </div>

                    <div>
                        <label for="assignment_end_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Assignment End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="assignment_end_date" id="assignment_end_date" 
                            value="<?php echo e(old('assignment_end_date', isset($assignment) ? $assignment->assignment_end_date->format('Y-m-d') : '')); ?>"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['assignment_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <?php $__errorArgs = ['assignment_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-slate-500 mt-1">Must be after start date</p>
                    </div>
                </div>

                <!-- Cost Center Facility -->
                <div>
                    <label for="cost_center_facility_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Cost Center Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="cost_center_facility_id" id="cost_center_facility_id" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['cost_center_facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required>
                        <option value="">Select cost center facility...</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->id); ?>" <?php echo e((old('cost_center_facility_id', $assignment->cost_center_facility_id ?? '') == $facility->id) ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['cost_center_facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-1">The facility financially responsible for incidents during this assignment</p>
                </div>

                <!-- Assignment Type -->
                <div>
                    <label for="assignment_type" class="block text-sm font-medium text-slate-700 mb-2">
                        Assignment Type <span class="text-red-500">*</span>
                    </label>
                    <select name="assignment_type" id="assignment_type" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['assignment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required>
                        <option value="">Select assignment type...</option>
                        <option value="rotation" <?php echo e((old('assignment_type', $assignment->assignment_type ?? '') === 'rotation') ? 'selected' : ''); ?>>
                            Rotation
                        </option>
                        <option value="locum_contract" <?php echo e((old('assignment_type', $assignment->assignment_type ?? '') === 'locum_contract') ? 'selected' : ''); ?>>
                            Locum Contract
                        </option>
                        <option value="student_placement" <?php echo e((old('assignment_type', $assignment->assignment_type ?? '') === 'student_placement') ? 'selected' : ''); ?>>
                            Student Placement
                        </option>
                    </select>
                    <?php $__errorArgs = ['assignment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Supervisor (Optional) -->
                <div>
                    <label for="supervisor_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Supervisor <span class="text-slate-400">(Optional)</span>
                    </label>
                    <select name="supervisor_id" id="supervisor_id" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['supervisor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">No supervisor assigned</option>
                        <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supervisor->id); ?>" <?php echo e((old('supervisor_id', $assignment->supervisor_id ?? '') == $supervisor->id) ? 'selected' : ''); ?>>
                                <?php echo e($supervisor->name); ?> - <?php echo e($supervisor->facility->name ?? 'No facility'); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['supervisor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-1">Supervisor at the assignment facility</p>
                </div>

                <!-- Status (Edit only) -->
                <?php if(isset($assignment)): ?>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="active" <?php echo e((old('status', $assignment->status) === 'active') ? 'selected' : ''); ?>>Active</option>
                            <option value="completed" <?php echo e((old('status', $assignment->status) === 'completed') ? 'selected' : ''); ?>>Completed</option>
                            <option value="cancelled" <?php echo e((old('status', $assignment->status) === 'cancelled') ? 'selected' : ''); ?>>Cancelled</option>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                        Notes <span class="text-slate-400">(Optional)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="4" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Additional notes about this assignment..."><?php echo e(old('notes', $assignment->notes ?? '')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i><?php echo e(isset($assignment) ? 'Update Assignment' : 'Create Assignment'); ?>

                    </button>
                    <a href="<?php echo e(route('system.roster.facility-assignments.index')); ?>" 
                        class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php if(isset($assignment)): ?>
        <!-- Assignment Details Section -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">
                <i class="fas fa-info-circle text-slate-600 mr-2"></i>Assignment Details
            </h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="font-medium text-slate-600">Created by</dt>
                    <dd class="text-slate-800 mt-1"><?php echo e($assignment->createdBy->name); ?></dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-600">Created at</dt>
                    <dd class="text-slate-800 mt-1"><?php echo e($assignment->created_at->format('M d, Y H:i')); ?></dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-600">Last updated</dt>
                    <dd class="text-slate-800 mt-1"><?php echo e($assignment->updated_at->format('M d, Y H:i')); ?></dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-600">Duration</dt>
                    <dd class="text-slate-800 mt-1"><?php echo e($assignment->assignment_start_date->diffInDays($assignment->assignment_end_date)); ?> days</dd>
                </div>
            </dl>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-populate cost center facility based on assignment facility selection
        const facilitySelect = document.getElementById('facility_id');
        const costCenterSelect = document.getElementById('cost_center_facility_id');
        
        if (facilitySelect && costCenterSelect) {
            facilitySelect.addEventListener('change', function() {
                if (this.value && !costCenterSelect.value) {
                    costCenterSelect.value = this.value;
                }
            });
        }

        // Date validation
        const startDateInput = document.getElementById('assignment_start_date');
        const endDateInput = document.getElementById('assignment_end_date');
        
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value <= this.value) {
                    endDateInput.value = '';
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\system\roster\assignment-form.blade.php ENDPATH**/ ?>