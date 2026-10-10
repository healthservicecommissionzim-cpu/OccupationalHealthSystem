

<?php $__env->startSection('page_title', 'Dashboard Routing Configuration'); ?>

<?php $__env->startSection('header_title', ''); ?>
<?php $__env->startSection('header_subtitle', ''); ?>

<?php $__env->startSection('dashboard_content'); ?>

<style>
    #sidebar { display: none !important; }
    #header { display: none !important; }
    #mainContent { margin-left: 0 !important; }
    #mainContent.collapsed { margin-left: 0 !important; }
    body > .bg-white.rounded-lg.border { margin: 0 !important; }
</style>

<!-- Header with Action Button -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-navy flex items-center gap-2">
            <i class="fa-solid fa-arrow-right-to-bracket text-brand"></i>
            Dashboard Role Mappings
        </h2>
        <p class="text-slate-600 mt-1">Configure which dashboard each role accesses on login</p>
    </div>
    <a href="<?php echo e(route('system.dashboard_mappings.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand text-white rounded-lg hover:bg-brand-dark transition-colors font-medium shadow-sm">
        <i class="fa-solid fa-plus text-sm"></i>
        <span>Add Mapping</span>
    </a>
</div>

<!-- Alert Messages -->
<?php if($errors->any()): ?>
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
    <div class="flex gap-3">
        <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 flex-shrink-0"></i>
        <div>
            <h3 class="font-semibold text-red-900 text-sm">Errors:</h3>
            <ul class="text-red-700 text-sm mt-2 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>• <?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(session('success')): ?>
<div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
    <div class="flex gap-3">
        <i class="fa-solid fa-check-circle text-emerald-600 mt-0.5 flex-shrink-0"></i>
        <div>
            <p class="text-emerald-900 font-medium text-sm"><?php echo e(session('success')); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
    <div class="flex gap-3">
        <i class="fa-solid fa-exclamation-circle text-red-600 mt-0.5 flex-shrink-0"></i>
        <div>
            <p class="text-red-900 font-medium text-sm"><?php echo e(session('error')); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Mappings Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <?php if($mappings->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Dashboard</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Route</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__currentLoopData = $mappings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                <i class="fa-solid fa-shield text-xs"></i>
                                <?php echo e($mapping->role->name); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-slate-900 text-sm"><?php echo e($mapping->dashboard_name); ?></p>
                                <?php if($mapping->description): ?>
                                <p class="text-xs text-slate-500 mt-0.5"><?php echo e(Str::limit($mapping->description, 50)); ?></p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-700 font-mono"><?php echo e($mapping->dashboard_route); ?></code>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-700 rounded-full text-xs font-bold">
                                <?php echo e($mapping->sort_order); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
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
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Edit Button -->
                                <a href="<?php echo e(route('system.dashboard_mappings.edit', $mapping->id)); ?>" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                   title="Edit">
                                    <i class="fa-solid fa-pen text-sm"></i>
                                </a>

                                <!-- Toggle Button -->
                                <form action="<?php echo e(route('system.dashboard_mappings.toggle', $mapping->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="p-2 <?php echo e($mapping->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50'); ?> rounded-lg transition-colors" 
                                            title="<?php echo e($mapping->is_active ? 'Disable' : 'Enable'); ?>">
                                        <i class="fa-solid <?php echo e($mapping->is_active ? 'fa-eye-slash' : 'fa-eye'); ?> text-sm"></i>
                                    </button>
                                </form>

                                <!-- Delete Button -->
                                <form action="<?php echo e(route('system.dashboard_mappings.destroy', $mapping->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this mapping? This action cannot be undone.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($mappings->hasPages()): ?>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            <?php echo e($mappings->links()); ?>

        </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- Empty State -->
        <div class="px-6 py-12 text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-inbox text-2xl text-slate-400"></i>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-1">No Mappings Yet</h3>
            <p class="text-slate-600 mb-6">Get started by creating your first dashboard mapping</p>
            <a href="<?php echo e(route('system.dashboard_mappings.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-brand text-white rounded-lg hover:bg-brand-dark transition-colors font-medium">
                <i class="fa-solid fa-plus"></i>
                Create First Mapping
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Available Dashboards Reference Card -->
<div class="mt-8">
    <div class="flex items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-info text-brand text-lg"></i>
        <h3 class="text-lg font-bold text-navy">Available Dashboards</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $dashboards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $dashboard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 bg-brand bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-window-maximize text-brand text-sm"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-900 text-sm"><?php echo e($dashboard['name']); ?></h4>
                </div>
            </div>
            <p class="text-xs text-slate-600 mb-3"><?php echo e($dashboard['description']); ?></p>
            <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-700 font-mono block overflow-x-auto"><?php echo e($dashboard['route']); ?></code>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Help Section -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <div class="flex gap-4">
        <i class="fa-solid fa-lightbulb text-blue-600 text-xl flex-shrink-0 mt-0.5"></i>
        <div>
            <h3 class="font-bold text-blue-900 mb-2">How it Works</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li><span class="font-semibold">Create Mapping:</span> Assign a dashboard to a role</li>
                <li><span class="font-semibold">Priority:</span> Lower numbers have higher priority for users with multiple roles</li>
                <li><span class="font-semibold">Active/Inactive:</span> Toggle mappings without deleting them</li>
                <li><span class="font-semibold">Auto-Routing:</span> Users are automatically routed to their mapped dashboard on login</li>
                <li><span class="font-semibold">Caching:</span> Changes take effect immediately with automatic cache invalidation</li>
            </ul>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views/super-admin/dashboard-mappings/index.blade.php ENDPATH**/ ?>