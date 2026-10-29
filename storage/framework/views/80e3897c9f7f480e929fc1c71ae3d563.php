

<?php $__env->startSection('title', 'Facility Assignments'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Facility Assignments
                </h1>
                <p class="text-slate-600 mt-2">View and manage staff assignments across facilities</p>
            </div>
            <a href="<?php echo e(route('system.roster.manage-rosters.index')); ?>" class="text-slate-600 hover:text-slate-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Rosters
            </a>
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
        <form method="GET" action="<?php echo e(route('system.roster.facility-assignments.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Facility</label>
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
                    <label class="block text-sm font-medium text-slate-700 mb-2">Start Date From</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Start Date To</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('system.roster.facility-assignments.index')); ?>" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Create Assignment Button -->
    <div class="mb-6">
        <a href="<?php echo e(route('system.roster.facility-assignments.create')); ?>" 
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Assignment
        </a>
    </div>

    <!-- Assignments Grouped by Facility -->
    <?php $__currentLoopData = $assignmentsByFacility; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facilityName => $facilityAssignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="bg-slate-100 px-6 py-3 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fas fa-hospital mr-2 text-blue-600"></i><?php echo e($facilityName); ?>

                </h2>
                <p class="text-sm text-slate-600"><?php echo e($facilityAssignments->count()); ?> assignment(s)</p>
            </div>
            
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700">Staff Member</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700">Staff Type</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700">Assignment Type</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700">Start Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700">End Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700">Duration</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__currentLoopData = $facilityAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isActive = $assignment->status === 'active' && 
                                       now()->between($assignment->assignment_start_date, $assignment->assignment_end_date);
                            $rowClass = $isActive ? 'bg-green-50' : '';
                            $durationDays = $assignment->assignment_start_date->diffInDays($assignment->assignment_end_date);
                        ?>
                        <tr class="<?php echo e($rowClass); ?> hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="font-medium"><?php echo e($assignment->staffRoster->user->name); ?></div>
                                <div class="text-xs text-slate-500"><?php echo e($assignment->staffRoster->primaryFacility->name); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded uppercase">
                                    <?php echo e($assignment->staffRoster->staff_type); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-slate-700">
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded capitalize">
                                    <?php echo e(str_replace('_', ' ', $assignment->assignment_type)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-slate-700">
                                <?php echo e($assignment->assignment_start_date->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 text-center text-sm text-slate-700">
                                <?php echo e($assignment->assignment_end_date->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 text-center text-sm text-slate-700">
                                <?php echo e($durationDays); ?> days
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <?php if($assignment->status === 'active'): ?>
                                    <?php if($isActive): ?>
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            Active Now
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                            Scheduled
                                        </span>
                                    <?php endif; ?>
                                <?php elseif($assignment->status === 'completed'): ?>
                                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">
                                        Completed
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        Cancelled
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if($assignment->status === 'active'): ?>
                                        <a href="<?php echo e(route('system.roster.facility-assignments.edit', $assignment->id)); ?>" 
                                            class="text-blue-600 hover:text-blue-700 font-medium">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form action="<?php echo e(route('system.roster.facility-assignments.cancel', $assignment->id)); ?>" 
                                            method="POST" 
                                            onsubmit="return confirm('Are you sure you want to cancel this assignment?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                                <i class="fas fa-ban mr-1"></i>Cancel
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($assignmentsByFacility->isEmpty()): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-inbox text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-600 text-lg">No assignments found</p>
            <p class="text-slate-500 text-sm mt-2">Try adjusting your filters or create a new assignment</p>
        </div>
    <?php endif; ?>

    <!-- Statistics Summary -->
    <?php if(!$assignmentsByFacility->isEmpty()): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Total Assignments</p>
                        <p class="text-2xl font-bold text-slate-800 mt-2"><?php echo e($totalAssignments); ?></p>
                    </div>
                    <i class="fas fa-calendar-check text-blue-500 text-3xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Active Now</p>
                        <p class="text-2xl font-bold text-green-700 mt-2"><?php echo e($activeCount); ?></p>
                    </div>
                    <i class="fas fa-user-check text-green-500 text-3xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Upcoming</p>
                        <p class="text-2xl font-bold text-blue-700 mt-2"><?php echo e($upcomingCount); ?></p>
                    </div>
                    <i class="fas fa-clock text-blue-500 text-3xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium">Completed</p>
                        <p class="text-2xl font-bold text-slate-700 mt-2"><?php echo e($completedCount); ?></p>
                    </div>
                    <i class="fas fa-check-circle text-slate-500 text-3xl opacity-50"></i>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\OccupationalHealth System\resources\views\system\roster\facility-assignments.blade.php ENDPATH**/ ?>