

<?php $__env->startSection('title', 'Manage Staff Rosters'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-users-cog text-blue-600 mr-3"></i>
                    Manage Staff Rosters
                </h1>
                <p class="text-slate-600 mt-2">Track rotational staff, locum doctors, and nursing students across facilities</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('system.roster.import')); ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fas fa-file-upload mr-2"></i>Bulk Import
                </a>
                <a href="<?php echo e(route('dashboards.super_admin')); ?>" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="<?php echo e(route('system.roster.manage-rosters.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="User name..." 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Staff Type</label>
                    <select name="staff_type" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="permanent" <?php echo e(request('staff_type') === 'permanent' ? 'selected' : ''); ?>>Permanent</option>
                        <option value="locum" <?php echo e(request('staff_type') === 'locum' ? 'selected' : ''); ?>>Locum</option>
                        <option value="student" <?php echo e(request('staff_type') === 'student' ? 'selected' : ''); ?>>Student</option>
                        <option value="rotational" <?php echo e(request('staff_type') === 'rotational' ? 'selected' : ''); ?>>Rotational</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Primary Facility</label>
                    <select name="facility_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->id); ?>" <?php echo e(request('facility_id') == $facility->id ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="active" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="1" <?php echo e(request('active') === '1' ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo e(request('active') === '0' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('system.roster.manage-rosters.index')); ?>" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Create Roster Button -->
    <div class="mb-6">
        <a href="<?php echo e(route('system.roster.manage-rosters.create')); ?>" 
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Roster
        </a>
    </div>

    <!-- Rosters Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">User</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Staff Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Primary Facility</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Start Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">End Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Assignments</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php $__empty_1 = true; $__currentLoopData = $rosters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="font-medium"><?php echo e($roster->user->name); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($roster->user->email); ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full uppercase">
                                <?php echo e($roster->staff_type); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700"><?php echo e($roster->primaryFacility->name); ?></td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <?php echo e($roster->start_date->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <?php echo e($roster->end_date ? $roster->end_date->format('M d, Y') : '—'); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if($roster->active): ?>
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-700">
                            <a href="<?php echo e(route('system.roster.facility-assignments.index', ['staff_roster_id' => $roster->id])); ?>" 
                                class="text-blue-600 hover:text-blue-700 font-medium">
                                <?php echo e($roster->assignments->count()); ?> assignment(s)
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('system.roster.manage-rosters.edit', $roster->id)); ?>" 
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <?php if($roster->active): ?>
                                    <form action="<?php echo e(route('system.roster.manage-rosters.destroy', $roster->id)); ?>" 
                                        method="POST" 
                                        onsubmit="return confirm('Are you sure you want to deactivate this roster?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                            <i class="fas fa-times-circle mr-1"></i>Deactivate
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-600">
                            <i class="fas fa-inbox text-3xl opacity-50 mb-3 block"></i>
                            No rosters found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($rosters->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($rosters->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\system\roster\manage-rosters.blade.php ENDPATH**/ ?>