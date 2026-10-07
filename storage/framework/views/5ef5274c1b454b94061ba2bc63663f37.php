

<?php $__env->startSection('page_title', 'Edit Dashboard Mapping'); ?>

<?php $__env->startSection('header_title', ''); ?>
<?php $__env->startSection('header_subtitle', ''); ?>

<?php $__env->startSection('dashboard_content'); ?>

<style>
    #sidebar { display: none !important; }
    #header { display: none !important; }
    #mainContent { margin-left: 0 !important; }
    #mainContent.collapsed { margin-left: 0 !important; }
</style>

<!-- Breadcrumb -->
<div class="mb-6 flex items-center gap-2 text-sm">
    <a href="<?php echo e(route('dashboards.super_admin')); ?>" class="text-brand hover:underline">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-slate-400"></i>
    <a href="<?php echo e(route('system.dashboard_mappings.index')); ?>" class="text-brand hover:underline">Dashboard Mappings</a>
    <i class="fa-solid fa-chevron-right text-slate-400"></i>
    <span class="text-slate-600">Edit</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
            <form action="<?php echo e(route('system.dashboard_mappings.update', $mapping->id)); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Role Display (Read-Only) -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-shield text-brand mr-2"></i>
                        Role
                    </label>
                    <div class="px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-medium">
                        <i class="fa-solid fa-shield text-brand mr-2"></i>
                        <?php echo e($mapping->role->name); ?>

                    </div>
                    <p class="text-xs text-slate-500 mt-2">Role cannot be changed after creation</p>
                </div>

                <!-- Dashboard Display (Read-Only) -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-window-maximize text-brand mr-2"></i>
                        Dashboard
                    </label>
                    <div class="px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-medium">
                        <i class="fa-solid fa-window-maximize text-brand mr-2"></i>
                        <?php echo e($mapping->dashboard_name); ?>

                    </div>
                    <p class="text-xs text-slate-500 mt-2">Dashboard cannot be changed after creation</p>
                </div>

                <!-- Dashboard Route Selection -->
                <div>
                    <label for="dashboard_route" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-link text-brand mr-2"></i>
                        Dashboard Route <span class="text-red-600">*</span>
                    </label>
                    <select id="dashboard_route" name="dashboard_route" required 
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors font-mono text-sm <?php $__errorArgs = ['dashboard_route'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Select a Dashboard Route --</option>
                        <?php $__currentLoopData = $dashboards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $dashboard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dashboard['route']); ?>" <?php echo e(old('dashboard_route', $mapping->dashboard_route) == $dashboard['route'] ? 'selected' : ''); ?>>
                            <?php echo e($dashboard['route']); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['dashboard_route'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-2">Choose from available dashboard URLs</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-note-sticky text-brand mr-2"></i>
                        Description (Optional)
                    </label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors resize-none <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="Internal notes about this mapping..."><?php echo e(old('description', $mapping->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-2">Internal notes for your reference</p>
                </div>

                <!-- Sort Order / Priority -->
                <div>
                    <label for="sort_order" class="block text-sm font-bold text-slate-900 mb-2">
                        <i class="fa-solid fa-arrow-up-arrow-down text-brand mr-2"></i>
                        Priority (Sort Order)
                    </label>
                    <input type="number" id="sort_order" name="sort_order" min="0" max="1000" 
                           value="<?php echo e(old('sort_order', $mapping->sort_order)); ?>"
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-colors <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-slate-500 mt-2">Lower numbers = higher priority (for users with multiple roles)</p>
                </div>

                <!-- Active Status -->
                <div class="pt-4 border-t border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $mapping->is_active) ? 'checked' : ''); ?> 
                               class="w-5 h-5 border border-slate-300 rounded accent-brand cursor-pointer">
                        <span class="text-sm font-medium text-slate-900">Active Mapping</span>
                    </label>
                    <p class="text-xs text-slate-600 mt-2 ml-8">Inactive mappings won't be used for routing</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-slate-200">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white rounded-lg hover:bg-brand-dark transition-colors font-medium shadow-sm">
                        <i class="fa-solid fa-save text-sm"></i>
                        Save Changes
                    </button>
                    <a href="<?php echo e(route('system.dashboard_mappings.index')); ?>" class="flex items-center gap-2 px-6 py-2.5 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium">
                        <i class="fa-solid fa-x text-sm"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <!-- Mapping Details Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 sticky top-20">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-brand text-lg"></i>
                Current Mapping
            </h3>
            
            <div class="space-y-4">
                <!-- Role Badge -->
                <div>
                    <p class="text-xs font-bold text-slate-600 uppercase mb-1">Role</p>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                        <i class="fa-solid fa-shield text-xs"></i>
                        <?php echo e($mapping->role->name); ?>

                    </span>
                </div>

                <!-- Dashboard Info -->
                <div>
                    <p class="text-xs font-bold text-slate-600 uppercase mb-1">Dashboard</p>
                    <p class="font-semibold text-slate-900 text-sm"><?php echo e($mapping->dashboard_name); ?></p>
                </div>

                <!-- Current Route -->
                <div>
                    <p class="text-xs font-bold text-slate-600 uppercase mb-1">Current Route</p>
                    <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-700 font-mono block overflow-x-auto"><?php echo e($mapping->dashboard_route); ?></code>
                </div>

                <!-- Status -->
                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs font-bold text-slate-600 uppercase mb-2">Status</p>
                    <?php if($mapping->is_active): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                        <i class="fa-solid fa-check text-xs"></i>
                        Active
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                        <i class="fa-solid fa-pause text-xs"></i>
                        Inactive
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Priority -->
                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs font-bold text-slate-600 uppercase mb-2">Priority</p>
                    <div class="w-12 h-12 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center font-bold">
                        <?php echo e($mapping->sort_order); ?>

                    </div>
                </div>

                <!-- Created Info -->
                <?php if($mapping->created_at): ?>
                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs font-bold text-slate-600 uppercase mb-1">Created</p>
                    <p class="text-xs text-slate-600"><?php echo e($mapping->created_at->format('M d, Y g:i A')); ?></p>
                </div>
                <?php endif; ?>

                <!-- Last Updated -->
                <?php if($mapping->updated_at): ?>
                <div class="pt-2">
                    <p class="text-xs font-bold text-slate-600 uppercase mb-1">Last Updated</p>
                    <p class="text-xs text-slate-600"><?php echo e($mapping->updated_at->format('M d, Y g:i A')); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mt-4">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-brand text-lg"></i>
                Quick Actions
            </h3>
            
            <div class="space-y-2">
                <!-- Toggle Status Button -->
                <form action="<?php echo e(route('system.dashboard_mappings.toggle', $mapping->id)); ?>" method="POST" class="w-full">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition-colors border border-slate-200">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid <?php echo e($mapping->is_active ? 'fa-eye-slash' : 'fa-eye'); ?> text-xs"></i>
                            <?php echo e($mapping->is_active ? 'Disable' : 'Enable'); ?> Mapping
                        </span>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                    </button>
                </form>

                <!-- Back to List -->
                <a href="<?php echo e(route('system.dashboard_mappings.index')); ?>" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition-colors border border-slate-200">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-list text-xs"></i>
                        View All Mappings
                    </span>
                    <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views/super-admin/dashboard-mappings/edit.blade.php ENDPATH**/ ?>