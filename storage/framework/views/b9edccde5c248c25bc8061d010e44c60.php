

<?php $__env->startSection('title', isset($roster) ? 'Edit Roster' : 'Create Roster'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                    <?php echo e(isset($roster) ? 'Edit Roster' : 'Create Roster'); ?>

                </h1>
                <p class="text-slate-600 mt-2">
                    <?php echo e(isset($roster) ? 'Update roster information' : 'Add a new staff roster record'); ?>

                </p>
            </div>
            <a href="<?php echo e(route('system.roster.manage-rosters.index')); ?>" class="text-slate-600 hover:text-slate-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Rosters
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="<?php echo e(isset($roster) ? route('system.roster.manage-rosters.update', $roster->id) : route('system.roster.manage-rosters.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if(isset($roster)): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>

            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <div class="font-semibold mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">
                        User <span class="text-red-500">*</span>
                    </label>
                    <?php if(isset($roster)): ?>
                        <input type="text" value="<?php echo e($roster->user->name); ?> (<?php echo e($roster->user->email); ?>)" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" readonly>
                        <input type="hidden" name="user_id" value="<?php echo e($roster->user_id); ?>">
                    <?php else: ?>
                        <select name="user_id" id="user_id" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                            <option value="">Select a user...</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php endif; ?>
                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-1">Select the staff member for this roster</p>
                </div>

                <!-- Staff Type -->
                <div>
                    <label for="staff_type" class="block text-sm font-medium text-slate-700 mb-2">
                        Staff Type <span class="text-red-500">*</span>
                    </label>
                    <select name="staff_type" id="staff_type" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['staff_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required>
                        <option value="">Select staff type...</option>
                        <option value="permanent" <?php echo e((old('staff_type', $roster->staff_type ?? '') === 'permanent') ? 'selected' : ''); ?>>
                            Permanent
                        </option>
                        <option value="locum" <?php echo e((old('staff_type', $roster->staff_type ?? '') === 'locum') ? 'selected' : ''); ?>>
                            Locum
                        </option>
                        <option value="student" <?php echo e((old('staff_type', $roster->staff_type ?? '') === 'student') ? 'selected' : ''); ?>>
                            Student
                        </option>
                        <option value="rotational" <?php echo e((old('staff_type', $roster->staff_type ?? '') === 'rotational') ? 'selected' : ''); ?>>
                            Rotational
                        </option>
                    </select>
                    <?php $__errorArgs = ['staff_type'];
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

                <!-- Primary Facility -->
                <div>
                    <label for="primary_facility_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Primary Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="primary_facility_id" id="primary_facility_id" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['primary_facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required>
                        <option value="">Select primary facility...</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->id); ?>" <?php echo e((old('primary_facility_id', $roster->primary_facility_id ?? '') == $facility->id) ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['primary_facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-1">The staff member's home base or permanent facility</p>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" 
                            value="<?php echo e(old('start_date', isset($roster) ? $roster->start_date->format('Y-m-d') : '')); ?>"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <?php $__errorArgs = ['start_date'];
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

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">
                            End Date <span class="text-slate-400">(Optional)</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" 
                            value="<?php echo e(old('end_date', isset($roster) && $roster->end_date ? $roster->end_date->format('Y-m-d') : '')); ?>"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-slate-500 mt-1">Leave blank for ongoing roster</p>
                    </div>
                </div>

                <!-- Active Status (Edit only) -->
                <?php if(isset($roster)): ?>
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="active" value="1" 
                                <?php echo e(old('active', $roster->active) ? 'checked' : ''); ?>

                                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700">Active</span>
                        </label>
                        <p class="text-xs text-slate-500 mt-1 ml-6">Uncheck to deactivate this roster</p>
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
                        placeholder="Additional notes or comments..."><?php echo e(old('notes', $roster->notes ?? '')); ?></textarea>
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
                        <i class="fas fa-save mr-2"></i><?php echo e(isset($roster) ? 'Update Roster' : 'Create Roster'); ?>

                    </button>
                    <a href="<?php echo e(route('system.roster.manage-rosters.index')); ?>" 
                        class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php if(isset($roster)): ?>
        <!-- Audit Log Section -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">
                <i class="fas fa-history text-slate-600 mr-2"></i>Change History
            </h2>
            <p class="text-sm text-slate-600 mb-4">Created by <?php echo e($roster->createdBy->name); ?> on <?php echo e($roster->created_at->format('M d, Y H:i')); ?></p>
            <p class="text-sm text-slate-600">Last updated: <?php echo e($roster->updated_at->format('M d, Y H:i')); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Add searchable dropdown functionality if needed
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        if (userSelect) {
            // Simple filter functionality
            userSelect.addEventListener('change', function() {
                if (this.value) {
                    this.classList.remove('border-red-500');
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\system\roster\form.blade.php ENDPATH**/ ?>